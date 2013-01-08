<?php 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
require_once('../include/common_top.php');

// *****************************************************************************************************
// Procedimentos para o Tratamento de M�ltiplos Componentes de Hardware - Anderson Peterle em Mar�o/2008
// *****************************************************************************************************

// ==============================================================================================================================================================
// Informe no array abaixo os tipos de componentes para a verifica��o de multiplicidade
// ***** ATEN��O ***** Para acrescentar tipo de componente � lista (array) � necess�rio implementar o envio das informa��es no agente Col_Hard, em forma de Tripa
// ==============================================================================================================================================================
$arrTiposComponentes = array( 'CDROM',
							  'CPU',
							  'TCPIP');

// Informe abaixo os �tens de componentes contidos na tripa oriunda do agente coletor							  
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


// Cria��o das "Tripas" na mem�ria com os dados dos componentes a serem tratados
for ($intTiposComponentes = 0;$intTiposComponentes < count($arrTiposComponentes);$intTiposComponentes++)
	{
	$strNomeTripaMemoria  = 'strTripa_'.$arrTiposComponentes[$intTiposComponentes];		
	$strNomeTripaRecebida = 'te_Tripa_'.$arrTiposComponentes[$intTiposComponentes];	
	$$strNomeTripaMemoria = DeCrypt($key,$iv,$_POST[$strNomeTripaRecebida]	,$v_cs_cipher, $v_cs_compress, $strPaddingKey);		
	}

// Devido � grande varia��o de frequ�ncia, causada pelo recurso de gerenciamento de energia existentes nos processadores atuais,
// o bloco abaixo retira a informa��o referente � Frequ�ncia da CPU.
if ($strTripa_CPU)
	{
	$strTripa_CPUaux = $strTripa_CPU;
	$intPos = stripos2($strTripa_CPUaux,'#FIELD#te_cpu_frequencia',true);
	if ($intPos)
		$strTripa_CPU = substr($strTripa_CPUaux,0,$intPos);
	}	
	

conecta_bd_cacic();

$strQueryTotalizaGeralExistentes = ' SELECT  count(cs_tipo_componente) TotalGeralExistentes
									 FROM	 componentes_estacoes
									 WHERE   id_computador = '.$arrDadosComputador['id_computador'];
$resultTotalizaGeralExistentes   = mysql_query($strQueryTotalizaGeralExistentes) or die('Problema Consultando Tabela Componentes_Esta��es 1!');
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
	$strValues = '';
	for ($intTiposComponentes = 0;$intTiposComponentes < count($arrTiposComponentes);$intTiposComponentes++)
		{
		$strNomeArray  	 = 'arr'.$arrTiposComponentes[$intTiposComponentes];
		$arrTemp 	   	 = $$strNomeArray;			
		$strValueACR 	 = ($arrTemp['ACR']<>''?'('.$arrDadosComputador['id_computador'].',"'.$arrTiposComponentes[$intTiposComponentes].'","'.$arrTemp['ACR'].'",now(),"ACR")':'');
		$strValueREM 	 = ($arrTemp['REM']<>''?'('.$arrDadosComputador['id_computador'].',"'.$arrTiposComponentes[$intTiposComponentes].'","'.$arrTemp['REM'].'",now(),"REM")':'');
			
		$strValues 		.= ($strValues <> '' && ($strValueACR . $strValueREM)<>''?',':'');
		$strVirgula		 = ($strValueACR <> '' && $strValueREM <> ''?',':'');
		$strValues 		.= $strValueACR . $strVirgula . $strValueREM;
		}
		
	if ($strValues)
		{
		// Armazeno as ocorr�ncias no Hist�rico
		$strQueryInsereHistorico  =  ' INSERT
									   INTO	 	componentes_estacoes_historico(	id_computador,
																				cs_tipo_componente,
																				te_valor,
																				dt_alteracao,
																				cs_tipo_alteracao)
									   VALUES '.$strValues;
		$resultInsereHistorico 	  = mysql_query($strQueryInsereHistorico) or die('Problema Inserindo Dados na Tabela Componentes_Esta��es_Hist�rico!');												
		}
									
	// Verifico se h� emails para notifica��o de altera��o na configura��o de hardware.
	$arrConfiguracoesLocais = getValores('configuracoes_locais', 'te_notificar_mudanca_hardware','id_local='.$arrDadosRede['id_local']);
	if (trim($strEmailsDestinatarios = $arrConfiguracoesLocais['te_notificar_mudanca_hardware']) <> '') 
		{
		// Obtenho os nomes do hardware pass�vel de controle
		$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
		
		// Consulto todos os hardwares que foram selecionados para notificacao. Isso � setado pelo administrador na p�gina de 'Configura��es Gerais'.
		$queryHardwareSelecionado  = "SELECT 	nm_campo_tab_hardware, 
												te_desc_hardware
									  FROM 		descricao_hardware 
									  WHERE 	te_locais_notificacao_ativada like '%,".$arrDadosRede['id_local'].",%'";
		$resultHardwareSelecionado = mysql_query($queryHardwareSelecionado) or die('Ocorreu um erro durante a consulta � tabela descricao_hardware.');

		// Crio um array que conter� nm_campo_tab_hardware => te_desc_hardware dos hardwares selecionados para notifica��o
		$arrHardwareSelecionado = array();			
		while($rowHardwareSelecionado = mysql_fetch_array($resultHardwareSelecionado)) 	
			$arrHardwareSelecionado[trim($rowHardwareSelecionado['nm_campo_tab_hardware'])] = $rowHardwareSelecionado['te_desc_hardware'];

		// Obtenho uma string contendo as altera��es/inclus�es/exclus�es efetuadas
		$strCamposAlterados .= TrataAlteracoes($arrTiposComponentes, $arrHardwareSelecionado,$arrDescricoesColunasComputadores);

		// Caso a string acima n�o esteja vazia, monto o email para notifica��o
		 if ($strCamposAlterados <> '') 
			{ 
			$strCorpoMail = '';
			$strCorpoMail .= " Prezado administrador,\n\n";
			$strCorpoMail .= " foi identificada uma altera��o na configura��o de hardware do seguinte computador:\n\n";				
			$strCorpoMail .= " Nome...........: ". $arrDadosComputador['te_nome_computador'] ."\n";
			$strCorpoMail .= " Endere�o IP: ". $arrDadosComputador['te_ip_computador'] . "\n";
			$strCorpoMail .= " Rede............: ". $arrDadosRede['nm_rede'] . ' (' . $arrDadosRede['te_ip_rede'] .")\n";
			$strCorpoMail .= str_replace('<br>',(chr(13).chr(10)),$strCamposAlterados) ;
			$strCorpoMail .= "\n\nPara visualizar mais informa��es sobre esse computador, acesse o endere�o\nhttp://";
			$strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $arrDadosComputador['id_computador'];
			$strCorpoMail .= "\n\n\n________________________________________________\n";
			$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
			$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";

			// Manda mail para os administradores.
			mail("$strEmailsDestinatarios", "[Sistema CACIC] Alteracao de Hardware Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
			}				
		}
	}
	
// Lembre-se de que o computador j� existe. Ele � criado durante a obten��o das configura��es, no arquivo get_config.php.
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
			WHERE 	id_computador    		 = "  . $arrDadosComputador['id_computador'];
//GravaTESTES('Query: '.$query);				
$result = mysql_query($query);		

// Fun��o para montagem do texto de componentes acrescentados
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

			// Importo a vari�vel array do componente via vari�vel-vari�vel
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

				// Verifico se algum campo foi selecionado para alerta de altera��o
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
				
				// Caso haja campo selecionado para alerta, monto informa��es para composi��o do email
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
	// Esta fun��o retorna um array contendo os seguintes elementos:
	// =============================================================
	// ACR     => Acrescentados
	// REM     => Removidos
	// =============================================================	
	global $arrDadosComputador;

	$strTripaComponentesExistentes   = '';

	$strTripaACR 	 	= '';
	$strTripaREM 	 	= '';
	$intTotalExistentes = 0;
	$intTotalRecebidos  = 0;

	$arrComponentesAcrescentados = array();
	
	// ===============================================================================
	// Monto array com tripa de valores enviada pelo agente e passada para verifica��o
	// ===============================================================================
	$strTripaComponentesRecebidosAux = $strTripaComponentesRecebidos;	
	$arrComponentesRecebidos  		 = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesRecebidosAux); 							
	$intTotalRecebidos		  		 = count($arrComponentesRecebidos);

	conecta_bd_cacic();												

	$strQueryComponentesExistentes = '  SELECT 	 *
										FROM	 componentes_estacoes
										WHERE    id_computador=' . $arrDadosComputador['id_computador'].' AND
												 cs_tipo_componente = "'.$strCsTipoComponente.'" 
										ORDER BY te_valor';											

	$resultComponentesExistentes = mysql_query($strQueryComponentesExistentes) or die('Problema Consultando Tabela Componentes_Esta��es 2!');
	$intTotalExistentes = @mysql_num_rows($resultComponentesExistentes);
	
	// ====================================================================================
	// Se j� existem componentes associados � esta��o, vou tratar ACRESCENTADOS e REMOVIDOS
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
		// Verifico se houve ACR�SCIMO de componentes
		// ===============================================================================================================================================		
		$strTripaComponentesRecebidosAux = $strTripaComponentesRecebidos;
	
		for ($intContaComponentesExistentes = 0;$intContaComponentesExistentes < count($arrComponentesExistentes);$intContaComponentesExistentes++)
			$strTripaComponentesRecebidosAux = str_replace($arrComponentesExistentes[$intContaComponentesExistentes],'',$strTripaComponentesRecebidosAux);

		if ($strTripaComponentesRecebidosAux <> '' && $strTripaComponentesRecebidosAux <> '#'.$strCsTipoComponente.'#')
			$arrComponentesAcrescentados = explode('#'.$strCsTipoComponente.'#',$strTripaComponentesRecebidosAux);
		// ===============================================================================================================================================

		// ===============================================================================================================================================
		// Verifico se houve REMO��O de componentes
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
				$strTripaInsereComponentes .= '('.$arrDadosComputador['id_computador'].',"'.$strCsTipoComponente.'","'.$arrComponentesAcrescentados[$intItemComponentesAcrescentados].'")';			

				// Somente retorno ACRESCENTADOS se j� houverem componentes registrados anteriormente
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
										   INTO	 	componentes_estacoes(id_computador,
																		 cs_tipo_componente,
																		 te_valor)
								   		   VALUES				'.$strTripaInsereComponentes;
										   
			$resultInsereComponente = mysql_query($strQueryInsereComponente) or die('Problema Inserindo Dados na Tabela Componentes_Esta��es!');					
			}
		}
		
	// =============================================
	// Removo os componentes que n�o foram recebidos
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
										   WHERE	id_computador = '.$arrDadosComputador['id_computador'].' AND
													cs_tipo_componente = "'.$strCsTipoComponente.'" AND
													te_valor IN (\''.$strTripaRemoveComponentes.'\')';
			$resultRemoveComponente = mysql_query($strQueryRemoveComponente) or die('Problema Removendo Dados na Tabela Componentes_Esta��es!');					
			}
		}

	$arrRetorno = array('ACR' 	  => $strTripaACR, 
						'REM' 	  => $strTripaREM);													
	return $arrRetorno;
	}
	
$strXML_Values .= '<STATUS>OK</STATUS>';			
require_once('../include/common_bottom.php');	
?>