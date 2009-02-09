<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 

require_once('../include/library.php');

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
// Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
if ($_POST['padding_key'])
	$strPaddingKey 	= $_POST['padding_key']; // A versão inicial do agente em Python exige esse complemento na chave...

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress, $strPaddingKey);

// Obtenho o IP da estação por meio da decriptografia...
$v_id_ip_estacao = trim(DeCrypt($key,$iv,$_POST['id_ip_estacao'],$v_cs_cipher,$v_cs_compress, $strPaddingKey));

// ...caso o IP esteja inválido, obtenho-o a partir de variável do servidor
if (substr_count($v_id_ip_estacao,'zf')>0 || trim($v_id_ip_estacao)=='')
	$v_id_ip_estacao = 	$_SERVER['REMOTE_ADDR'];

$te_node_address 	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher, $v_cs_compress, $strPaddingKey); 
$id_so_new         	= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher, $v_cs_compress, $strPaddingKey); 
$te_so           	= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher, $v_cs_compress, $strPaddingKey); 
$id_ip_rede     	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher, $v_cs_compress, $strPaddingKey);
$te_nome_computador	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher, $v_cs_compress, $strPaddingKey); 
$te_workgroup 		= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher, $v_cs_compress, $strPaddingKey); 


// *****************************************************************************************************
// Procedimentos para o Tratamento de Múltiplos Componentes de Hardware - Anderson Peterle em Março/2008
// *****************************************************************************************************

// ==============================================================================================================================================================
// Informe no array abaixo os tipos de componentes para a verificação de multiplicidade
// ***** ATENÇÃO ***** Para acrescentar tipo de componente à lista (array) é necessário implementar o envio das informações no agente Col_Hard, em forma de Tripa
// ==============================================================================================================================================================
$arrTiposComponentes = array( 'CDROM',
							  'CPU',
							  'TCPIP');

// Informe abaixo os ítens de componentes contidos na tripa oriunda do agente coletor							  
// ----------------------------------------------------------------------------------
// te_Tripa_CDROM								
// --------------
// CDROMName
//
// te_Tripa_CPU
// ------------
// CPUName, Vendor, Serial Number, Frequency
//
// te_Tripa_TCPIP
// --------------
// Name, PhysicalAddress, IPAddress, IPMask, Gateway_IPAddress, DHCP_IPAddress, PrimaryWINS_IPAddress, SecondaryWINS_IPAddress


// Criação das "Tripas" na memória com os dados dos componentes a serem tratados
for ($intTiposComponentes = 0;$intTiposComponentes < count($arrTiposComponentes);$intTiposComponentes++)
	{
	$strNomeTripaMemoria  = 'strTripa_'.$arrTiposComponentes[$intTiposComponentes];		
	$strNomeTripaRecebida = 'te_Tripa_'.$arrTiposComponentes[$intTiposComponentes];	
	$$strNomeTripaMemoria = DeCrypt($key,$iv,$_POST[$strNomeTripaRecebida]	,$v_cs_cipher, $v_cs_compress, $strPaddingKey);		
	}

// Devido à grande variação de frequência, causada pelo recurso de gerenciamento de energia existentes nos processadores atuais,
// o bloco abaixo retira a informação referente à Frequência da CPU.
if ($strTripa_CPU)
	{
	$strTripa_CPUaux = $strTripa_CPU;
	$intPos = stripos2($strTripa_CPUaux,'#FIELD#te_cpu_frequencia',true);
	if ($intPos)
		$strTripa_CPU = substr($strTripa_CPUaux,0,$intPos);
	}	
	
// Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
// o computador deste agente no BD, caso ainda não esteja inserido. 
if ($te_node_address <> '')
	{ 
	$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
												$id_so_new, 
												$te_so, 										
												$id_ip_rede, 
												$v_id_ip_estacao, 
												$te_nome_computador, 
												$te_workgroup);										

	conecta_bd_cacic();

	$strQueryTotalizaGeralExistentes = ' SELECT  count(cs_tipo_componente) TotalGeralExistentes
								 		 FROM	 componentes_estacoes
										 WHERE   te_node_address = "'.$te_node_address . '" AND
									 		 	  			id_so='  . $arrSO['id_so'];
	$resultTotalizaGeralExistentes   = mysql_query($strQueryTotalizaGeralExistentes) or die('Problema Consultando Tabela Componentes_Estações 1!');
	$rowTotalizaGeralExistentes      = mysql_fetch_array($resultTotalizaGeralExistentes);	

	$boolHardwareAlterado = false;
	for ($intTiposComponentes = 0;$intTiposComponentes < count($arrTiposComponentes);$intTiposComponentes++)
		{
		$strNomeArray  = 'arr'.$arrTiposComponentes[$intTiposComponentes];
		$strNomeTripa  = 'strTripa_'.$arrTiposComponentes[$intTiposComponentes];
		$$strNomeArray = VerificaComponentes($arrTiposComponentes[$intTiposComponentes],$$strNomeTripa,$rowTotalizaGeralExistentes['TotalGeralExistentes']);			
		$arrTemp 	   = $$strNomeArray;

		if (($arrTemp['ACR'])<>'' || ($arrTemp['REM'])<>'')
			$boolHardwareAlterado = true;
		}
	
	if ($boolHardwareAlterado)
		{
		$v_dados_rede = getDadosRede();
		
		$strValues = '';
		for ($intTiposComponentes = 0;$intTiposComponentes < count($arrTiposComponentes);$intTiposComponentes++)
			{
			$strNomeArray  	 = 'arr'.$arrTiposComponentes[$intTiposComponentes];
			$arrTemp 	   	 = $$strNomeArray;			
			$strValueACR 	 = ($arrTemp['ACR']<>''?'("'.$te_node_address.'",'.$arrSO['id_so'].',"'.$arrTiposComponentes[$intTiposComponentes].'","'.$arrTemp['ACR'].'",now(),"ACR")':'');
			$strValueREM 	 = ($arrTemp['REM']<>''?'("'.$te_node_address.'",'.$arrSO['id_so'].',"'.$arrTiposComponentes[$intTiposComponentes].'","'.$arrTemp['REM'].'",now(),"REM")':'');
				
			$strValues 		.= ($strValues <> '' && ($strValueACR . $strValueREM)<>''?',':'');
			$strVirgula		 = ($strValueACR <> '' && $strValueREM <> ''?',':'');
			$strValues 		.= $strValueACR . $strVirgula . $strValueREM;
			}
			
		if ($strValues)
			{
			// Armazeno as ocorrências no Histórico
			$strQueryInsereHistorico  =  ' INSERT
										   INTO	 	componentes_estacoes_historico(	te_node_address,
																		  			id_so,
																		  			cs_tipo_componente,
																		  			te_valor,
																					dt_alteracao,
																					cs_tipo_alteracao)
						   				   VALUES '.$strValues;
			$resultInsereHistorico 	  = mysql_query($strQueryInsereHistorico) or die('Problema Inserindo Dados na Tabela Componentes_Estações_Histórico!');												
			}
										
	    // Verifico se há emails para notificação de alteração na configuração de hardware.
		$arrConfiguracoesLocais = getValores('configuracoes_locais', 'te_notificar_mudanca_hardware','id_local='.$v_dados_rede['id_local']);
	  	if (trim($strEmailsDestinatarios = $arrConfiguracoesLocais['te_notificar_mudanca_hardware']) <> '') 
			{
			// Obtenho os nomes do hardware passível de controle
			$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
			
	        // Consulto todos os hardwares que foram selecionados para notificacao. Isso é setado pelo administrador na página de 'Configurações Gerais'.
			$queryHardwareSelecionado  = "SELECT 	nm_campo_tab_hardware, 
													te_desc_hardware
					  					  FROM 		descricao_hardware 
					  					  WHERE 	te_locais_notificacao_ativada like '%,".$v_dados_rede['id_local'].",%'";
			$resultHardwareSelecionado = mysql_query($queryHardwareSelecionado) or die('Ocorreu um erro durante a consulta à tabela descricao_hardware.');

			// Crio um array que conterá nm_campo_tab_hardware => te_desc_hardware dos hardwares selecionados para notificação
			$arrHardwareSelecionado = array();			
			while($rowHardwareSelecionado = mysql_fetch_array($resultHardwareSelecionado)) 	
				$arrHardwareSelecionado[trim($rowHardwareSelecionado['nm_campo_tab_hardware'])] = $rowHardwareSelecionado['te_desc_hardware'];

			// Obtenho uma string contendo as alterações/inclusões/exclusões efetuadas
			$strCamposAlterados .= TrataAlteracoes($arrTiposComponentes, $arrHardwareSelecionado,$arrDescricoesColunasComputadores);

			// Caso a string acima não esteja vazia, monto o email para notificação
			 if ($strCamposAlterados <> '') 
				{ 
				$strCorpoMail = '';
				$strCorpoMail .= " Prezado administrador,\n\n";
				$strCorpoMail .= " foi identificada uma alteração na configuração de hardware do seguinte computador:\n\n";				
				$strCorpoMail .= " Nome...........: ". $te_nome_computador ."\n";
				$strCorpoMail .= " Endereço IP: ". $v_id_ip_estacao . "\n";
				$strCorpoMail .= " Rede............: ". $v_dados_rede['id_ip_rede'] ."\n";
				$strCorpoMail .= str_replace('<br>',(chr(13).chr(10)),$strCamposAlterados) ;
				$strCorpoMail .= "\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://";
				$strCorpoMail .= $_SERVER['SERVER_ADDR'] . '/cacic2/relatorios/computador/computador.php?te_node_address=' . $te_node_address . '&id_so=' . $arrSO['id_so'];
				$strCorpoMail .= "\n\n\n________________________________________________\n";
				$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
				$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";
	
				// Manda mail para os administradores.
				mail("$strEmailsDestinatarios", "[Sistema CACIC] Alteracao de Hardware Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
				}				
			}
		}
		
	// Lembre-se de que o computador já existe. Ele é criado durante a obtenção das configurações, no arquivo get_config.php.
	$query = "	UPDATE 	computadores 
				SET		te_mem_ram_desc          = '" . DeCrypt($key,$iv,$_POST['te_mem_ram_desc']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						qt_mem_ram               = '" . DeCrypt($key,$iv,$_POST['qt_mem_ram']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_bios_desc             = '" . DeCrypt($key,$iv,$_POST['te_bios_desc']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_bios_data             = '" . DeCrypt($key,$iv,$_POST['te_bios_data']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_bios_fabricante       = '" . DeCrypt($key,$iv,$_POST['te_bios_fabricante']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_placa_mae_desc        = '" . DeCrypt($key,$iv,$_POST['te_placa_mae_desc']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_placa_mae_fabricante  = '" . DeCrypt($key,$iv,$_POST['te_placa_mae_fabricante']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						qt_placa_video_mem       = '" . DeCrypt($key,$iv,$_POST['qt_placa_video_mem']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						qt_placa_video_cores     = '" . DeCrypt($key,$iv,$_POST['qt_placa_video_cores']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_placa_video_desc      = '" . DeCrypt($key,$iv,$_POST['te_placa_video_desc']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_placa_video_resolucao = '" . DeCrypt($key,$iv,$_POST['te_placa_video_resolucao']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_placa_som_desc        = '" . DeCrypt($key,$iv,$_POST['te_placa_som_desc']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_teclado_desc          = '" . DeCrypt($key,$iv,$_POST['te_teclado_desc']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_mouse_desc            = '" . DeCrypt($key,$iv,$_POST['te_mouse_desc']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
						te_modem_desc            = '" . DeCrypt($key,$iv,$_POST['te_modem_desc']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "'
				WHERE 	te_node_address    		 = '" . $te_node_address . "' and id_so = '" . $arrSO['id_so'] . "'";
//GravaTESTES('Query: '.$query);				
	$result = mysql_query($query);		
		
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';	
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inválida</STATUS>';

// Função para montagem do texto de componentes acrescentados
function TrataAlteracoes($arrTiposComponentes, $arrHardwareSelecionado,$arrDescricoesColunasComputadores)
	{
	$strAlteracoes  = '';
	$arrOperacoes 	   = array('REM','ACR'); 
	$strAlteracoes = '';
	$strOperacaoAtual = '';
	for ($g=0; $g < count($arrOperacoes); $g++)
		{
		$boolNovaOperacao = true;				
		$strOperacaoAtual = $arrOperacoes[$g];
		for ($h=0; $h < count($arrTiposComponentes);$h++)
			{		
			$strNomeArrayComponente = 'arr'.trim($arrTiposComponentes[$h]);

			// Importo a variável array do componente via variável-variável
			global $$strNomeArrayComponente;

			$arrItensAlteracoesExterno = $$strNomeArrayComponente;

			// Explodo os tipos de componentes
			$arrAlteracoes = explode('#'.trim($arrTiposComponentes[$h]).'#',$arrItensAlteracoesExterno[$arrOperacoes[$g]]);

			for ($i=0; $i < count($arrAlteracoes); $i++) 
				{
				// Explodo os conjuntos de campos
				$arrItensAlteracoes = explode('#FIELD#',$arrAlteracoes[$i]);
			
				// Indicador para montagem do email alerta
				$boolAlerta = false;

				// Verifico se algum campo foi selecionado para alerta de alteração
				for ($j=0; $j < count($arrItensAlteracoes); $j++)
					if ($arrItensAlteracoes[$j])
						{
						// Explodo os campos
						$arrCampos = explode('###',$arrItensAlteracoes[$j]);					
						if ($arrHardwareSelecionado[trim($arrCampos[0])]<>'')
							{
							$boolAlerta = true;
							$j = count($arrItensAlteracoes);
							}
						}
				
				// Caso haja campo selecionado para alerta, monto informações para composição do email
				if ($boolAlerta)
					{
					for ($j=0; $j < count($arrItensAlteracoes); $j++)
						if ($arrItensAlteracoes[$j])
							{
							// Explodo os campos
							$arrCampos = explode('###',$arrItensAlteracoes[$j]);					
							$strDescricaoColuna = $arrDescricoesColunasComputadores[trim($arrCampos[0])];
							$strDescricaoColuna = ($strDescricaoColuna?$strDescricaoColuna:trim($arrCampos[0]));						
							if ($boolNovaOperacao)
								{
								$strPluralItem = 'ns';
								$strPlural = 's';								
								if (count($arrAlteracoes)==1)
									{
									$strPluralItem = 'm';
									$strPlural = '';									
									}
								$boolNovaOperacao = false;
								$strAlteracoes .= '<br><br>'.str_repeat('=',50).' Ite'.$strPluralItem.' '.($strOperacaoAtual=='ACR'?'Acrescentado'.$strPlural:($strOperacaoAtual=='REM'?'Removido'.$strPlural:'Alterado'.$strPlural)).' '.str_repeat('=',50).'<br>';			
								}
							
							if ($j == 0)										
								{
								$strTextoItem = $arrDescricoesColunasComputadores[trim($arrCampos[0])] . '  =>  ';
								$strAlteracoes .= $strTextoItem;
								}
							else
								$strAlteracoes .= str_repeat('  ',strlen($strTextoItem)). $strDescricaoColuna . ': ';							
							$strAlteracoes .= $arrCampos[1].'<br>';
							}
					}			
				}
			}
		}		
	
	return $strAlteracoes;
	}
	
function VerificaComponentes($strCsTipoComponente, $strTripaComponentesRecebidos, $intTotalGeralExistentes = 0)
	{
	// =============================================================
	// Esta função retorna um array contendo os seguintes elementos:
	// =============================================================
	// ACR     => Acrescentados
	// REM     => Removidos
	// =============================================================
	
	global $te_node_address;
	global $arrSO;

	$strTripaComponentesExistentes   = '';

	$strTripaACR 	 	= '';
	$strTripaREM 	 	= '';
	$intTotalExistentes = 0;
	$intTotalRecebidos  = 0;

	$arrComponentesAcrescentados = array();
	
	// ===============================================================================
	// Monto array com tripa de valores enviada pelo agente e passada para verificação
	// ===============================================================================
	$strTripaComponentesRecebidosAux = $strTripaComponentesRecebidos;	
	$arrComponentesRecebidos  		 = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesRecebidosAux); 							
	$intTotalRecebidos		  		 = count($arrComponentesRecebidos);

	conecta_bd_cacic();											
	

	$strQueryComponentesExistentes = '  SELECT 	 *
										FROM	 componentes_estacoes
										WHERE    te_node_address = "'.$te_node_address . '" AND
												 id_so=' . $arrSO['id_so'].' AND
												 cs_tipo_componente = "'.$strCsTipoComponente.'" 
										ORDER BY te_valor';											

	$resultComponentesExistentes = mysql_query($strQueryComponentesExistentes) or die('Problema Consultando Tabela Componentes_Estações 2!');
	$intTotalExistentes = @mysql_num_rows($resultComponentesExistentes);
	
	// ====================================================================================
	// Se já existem componentes associados à estação, vou tratar ACRESCENTADOS e REMOVIDOS
	// ====================================================================================
	if ($intTotalExistentes > 0)			
		{
		while ($rowComponentesExistentes = mysql_fetch_array($resultComponentesExistentes))		
			{
			if ($strTripaComponentesExistentes)
				$strTripaComponentesExistentes .= '#'.$strCsTipoComponente.'#';
			$strTripaComponentesExistentes .= $rowComponentesExistentes['te_valor'];
			}

		// ==============================
		// Retiro as tags de aspas duplas
		// ==============================
		$strTripaComponentesExistentes    = str_replace('<AD>','',$strTripaComponentesExistentes);		
		$strTripaComponentesExistentesAux = $strTripaComponentesExistentes;				
		$arrComponentesExistentes 	      = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesExistentes);

		// ===============================================================================================================================================
		// Verifico se houve ACRÉSCIMO de componentes
		// ===============================================================================================================================================		
		$strTripaComponentesRecebidosAux = $strTripaComponentesRecebidos;
	
		for ($intContaComponentesExistentes = 0;$intContaComponentesExistentes < count($arrComponentesExistentes);$intContaComponentesExistentes++)
			$strTripaComponentesRecebidosAux = str_replace($arrComponentesExistentes[$intContaComponentesExistentes],'',$strTripaComponentesRecebidosAux);

		if ($strTripaComponentesRecebidosAux <> '' && $strTripaComponentesRecebidosAux <> '#'.$strCsTipoComponente.'#')
			$arrComponentesAcrescentados = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesRecebidosAux);
		// ===============================================================================================================================================

		// ===============================================================================================================================================
		// Verifico se houve REMOÇÃO de componentes
		// ===============================================================================================================================================		
		$strTripaComponentesExistentesAux = $strTripaComponentesExistentes;		
		for ($intContaComponentesRecebidos = 0;$intContaComponentesRecebidos < count($arrComponentesRecebidos);$intContaComponentesRecebidos++)
			$strTripaComponentesExistentesAux = str_replace($arrComponentesRecebidos[$intContaComponentesRecebidos],'',$strTripaComponentesExistentesAux);

		if ($strTripaComponentesExistentesAux <> '' && $strTripaComponentesExistentesAux <> '#'.$strCsTipoComponente.'#')
			$arrComponentesRemovidos = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesExistentesAux);
		// ===============================================================================================================================================		
		
		}
	else
		$arrComponentesAcrescentados = $arrComponentesRecebidos;
		
	// ===============================
	// Acrescento os componentes novos
	// ===============================
	if (count($arrComponentesAcrescentados)>0)
		{

		$strTripaInsereComponentes = '';
		$strTripaACR			   = '';
		for ($intItemComponentesAcrescentados = 0;$intItemComponentesAcrescentados < count($arrComponentesAcrescentados);$intItemComponentesAcrescentados ++)
			{
			if ($arrComponentesAcrescentados[$intItemComponentesAcrescentados] <> '')
				{
				if ($strTripaInsereComponentes<>'')
					$strTripaInsereComponentes .= ',';
				$strTripaInsereComponentes .= '("'.$te_node_address.'",'.$arrSO['id_so'].',"'.$strCsTipoComponente.'","'.$arrComponentesAcrescentados[$intItemComponentesAcrescentados].'")';			

				// Somente retorno ACRESCENTADOS se já houverem componentes registrados anteriormente
				if ($intTotalGeralExistentes > 0)			
					{
					if ($strTripaACR)
						$strTripaACR .= '#'.$strCsTipoComponente.'#';
					$strTripaACR .= $arrComponentesAcrescentados[$intItemComponentesAcrescentados];			
					}
				}
			}	
		if ($strTripaInsereComponentes)
			{
			$strQueryInsereComponente =  ' INSERT
										   INTO	 	componentes_estacoes(te_node_address,
																		  id_so,
																		  cs_tipo_componente,
																		  te_valor)
								   		   VALUES				'.$strTripaInsereComponentes;
										   
			$resultInsereComponente = mysql_query($strQueryInsereComponente) or die('Problema Inserindo Dados na Tabela Componentes_Estações!');					
			}
		}
		
	// =============================================
	// Removo os componentes que não foram recebidos
	// =============================================
	if (count($arrComponentesRemovidos)>0)
		{
		$strTripaRemoveComponentes = '';
		$strTripaREM			   = '';
		for ($intItemComponentesRemovidos = 0;$intItemComponentesRemovidos < count($arrComponentesRemovidos);$intItemComponentesRemovidos ++)
			{
			if ($strTripaRemoveComponentes)
				$strTripaRemoveComponentes .= ',';
			$strTripaRemoveComponentes .= '"'.$arrComponentesRemovidos[$intItemComponentesRemovidos].'"';			

			if ($strTripaREM)
				$strTripaREM .= '#'.$strCsTipoComponente.'#';
			$strTripaREM .= $arrComponentesRemovidos[$intItemComponentesRemovidos];			
			}
			
		if ($strTripaRemoveComponentes)
			{
			$strQueryRemoveComponente =  ' DELETE
										   FROM	 	componentes_estacoes
										   WHERE	te_node_address = "'.$te_node_address.'" AND
										   			id_so = '.$arrSO['id_so'].' AND
													cs_tipo_componente = "'.$strCsTipoComponente.'" AND
													te_valor IN (\''.$strTripaRemoveComponentes.'\')';
			$resultRemoveComponente = mysql_query($strQueryRemoveComponente) or die('Problema Removendo Dados na Tabela Componentes_Estações!');					
			}
		}

	$arrRetorno = array('ACR' 	  => $strTripaACR, 
						'REM' 	  => $strTripaREM);													
	return $arrRetorno;
	}
?>
