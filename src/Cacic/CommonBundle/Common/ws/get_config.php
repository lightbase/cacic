<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 Objetivo:
 ---------
 Esse script tem como objetivo enviar aos agentes as configura��es (em XML) que s�o espec�ficas para
 o agente em quest�o. S�o levados em considera��o a rede do agente e seu sistema operacional.
 Tamb�m h� um sistema de exce��es, onde um computador que consta nessa rela��o de exce��es 
 n�o recebe as configura��es.
*/

require_once('../include/library.php');

// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 

$retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><CONFIGS>';
$retorno_xml_values	 = '';

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decripta��o/encripta��o
if ($_POST['padding_key'])
	{
	// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
	$strPaddingKey 	= $_POST['padding_key']; // A vers�o inicial do agente em Python exige esse complemento na chave...
	}
	
$boolAgenteLinux 	= (trim(DeCrypt($key,$iv,$_POST['AgenteLinux'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) <> ''?true:false);

// Obtenho o IP da esta��o por meio da decriptografia...
$v_id_ip_estacao = trim(DeCrypt($key,$iv,$_POST['id_ip_estacao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));

// ...caso o IP esteja inv�lido, obtenho-o a partir de vari�vel do servidor
if (substr_count($v_id_ip_estacao,'zf')>0 || trim($v_id_ip_estacao)=='')
	$v_id_ip_estacao = 	$_SERVER['REMOTE_ADDR'];

$v_id_ip_estacao = 	$_SERVER['REMOTE_ADDR'];	

//LimpaTESTES();

$v_dados_rede = getDadosRede($v_id_ip_estacao);

// Essa condi��o testa se foi o "chkcacic" chamado para instala��o ou o "Gerente de Coletas" para validar IP da esta��o...
if (trim(DeCrypt($key,$iv,$_POST['in_chkcacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='chkcacic' || 
	trim(DeCrypt($key,$iv,$_POST['in_teste']   ,$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='OK')
	{	
	$retorno_xml_values .= '<TE_REDE_OK>' . EnCrypt($key,$iv,($v_dados_rede['id_ip_rede'] <> ''?'S':'N'),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</TE_REDE_OK>';		

	if (trim(DeCrypt($key,$iv,$_POST['in_chkcacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='chkcacic')
		{
		// Retorno as vers�es dos tr�s principais agentes ao CHKCACIC para que sejam verificadas...
		$MainFolder		= GetMainFolder();
		$v_array_versoes_agentes = array();
		if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
			{
			$v_array_versoes_agentes = parse_ini_file($MainFolder . '/repositorio/versoes_agentes.ini');
			if ($boolAgenteLinux)
				{
				// Arghh! O PyCACIC espera pelo nome completo do pacote TGZ
				$retorno_xml_values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . EnCrypt($key,$iv,$v_array_versoes_agentes['te_pacote_PyCACIC']     ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
				$retorno_xml_values .= '<' . 'TE_HASH_PYCACIC>' 			 . EnCrypt($key,$iv,$v_array_versoes_agentes['te_pacote_PyCACIC_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_HASH_PYCACIC>';													
				}
			else
				{
				$retorno_xml_values .= '<CACICSERVICE>'   	    . EnCrypt($key,$iv,$v_array_versoes_agentes['cacicservice.exe']  	 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)   	. '<' . '/CACICSERVICE>';
				$retorno_xml_values .= '<TE_HASH_CACICSERVICE>' . EnCrypt($key,$iv,$v_array_versoes_agentes['cacicservice.exe_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)   	. '<' . '/TE_HASH_CACICSERVICE>';								
				$retorno_xml_values .= '<te_MainProgramName>'  	 	. EnCrypt($key,$iv,CACIC_MAIN_PROGRAM_NAME.'.EXE'	 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</te_MainProgramName>';
				$retorno_xml_values .= '<te_MainProgramHash>'   . EnCrypt($key,$iv,$v_array_versoes_agentes[strtolower(CACIC_MAIN_PROGRAM_NAME).'.exe_HASH']  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</te_MainProgramHash>';				
				$retorno_xml_values .= '<'. strtoupper(CACIC_MAIN_PROGRAM_NAME).'>'  	 	. EnCrypt($key,$iv,$v_array_versoes_agentes[strtolower(CACIC_MAIN_PROGRAM_NAME.'.exe')] ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '</'.strtoupper(CACIC_MAIN_PROGRAM_NAME).'>';
				$retorno_xml_values .= '<GER_COLS>' 	 	. EnCrypt($key,$iv,$v_array_versoes_agentes['ger_cols.exe']	 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 		. '<' . '/GER_COLS>';			
				$retorno_xml_values .= '<TE_HASH_GER_COLS>' . EnCrypt($key,$iv,$v_array_versoes_agentes['ger_cols.exe_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 		. '<' . '/TE_HASH_GER_COLS>';							
				$retorno_xml_values .= '<CHKSIS>'   	 	. EnCrypt($key,$iv,$v_array_versoes_agentes['chksis.exe']  	 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)   		. '<' . '/CHKSIS>';						
				$retorno_xml_values .= '<TE_HASH_CHKSIS>'   . EnCrypt($key,$iv,$v_array_versoes_agentes['chksis.exe_HASH']  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)   	. '<' . '/TE_HASH_CHKSIS>';										
				}
			}
		}

	$v_te_fila_ftp = '0';
	$v_id_ftp      = ($_POST['id_ftp']?trim(DeCrypt($key,$iv,$_POST['id_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)):'');

	conecta_bd_cacic();	
	// Opera��es para agrupamento de FTP por subredes	
	if (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='1' &&
	    !$v_id_ftp)
		{
		// TimeOut definido para 5 minutos, ou seja, tempo m�ximo para as esta��es efetuarem FTP dos m�dulos necess�rios
		// 1 minuto = 60000 milisegundos
		// 5 * 60000 milisegundos = 5 minutos (TimeOut)
		$v_timeout = time() - (5 * 60000);

		// Exclus�o por timeout
		$query_del = 'DELETE 
					  FROM  	redes_grupos_ftp
					  WHERE 	id_local = '.$v_dados_rede['id_local'].' AND
					  			id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
								id_ip_estacao = "'.$v_id_ip_estacao.'"';
		$result_del = mysql_query($query_del);	
		
		// Contagem por subrede
		$query_grupo = 'SELECT 		count(*) as total_estacoes
					   	FROM 		redes_grupos_ftp
					   	WHERE 		id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
									id_local = '.$v_dados_rede['id_local'].' FOR UPDATE';
		$result_grupo = mysql_query($query_grupo);
		$total = mysql_fetch_array($result_grupo);
		
		// Caso o grupo de esta��es esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
		// Posteriormente, poderemos calcular uma m�dia para o intervalo, em fun��o do link da subrede
		if ($total['total_estacoes'] >= $v_dados_rede['nu_limite_ftp']) // Se for maior que o Limite FTP, configurado em Administra��o/Cadastros/SubRedes 
			{
			$v_te_fila_ftp = '5'; // Tempo em minutos
			}
		else
			{
			$queryINS  = 'INSERT 
						  INTO 		redes_grupos_ftp(id_ip_rede,id_ip_estacao,nu_hora_inicio, id_local) 
						  VALUES    ("'.$v_dados_rede['id_ip_rede'].'","'.
						               $v_id_ip_estacao.'",'.
									   time().','.
									   $v_dados_rede['id_local'].')';
			$resultINS = mysql_query($queryINS);			
			}
		$retorno_xml_values .= '<TE_FILA_FTP>' .EnCrypt($key,$iv,$v_te_fila_ftp  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_FILA_FTP>';											
		$retorno_xml_values .= '<ID_FTP>' . EnCrypt($key,$iv, mysql_insert_id()  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_FTP>';													
		}		
	elseif (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='2') // Opera��o conclu�da com sucesso!
		{
		$query_del = 'DELETE 
					  FROM  redes_grupos_ftp
					  WHERE id_local = '.$v_dados_rede['id_local'].' AND
					  	    id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
					        id_ip_estacao = "'.$v_id_ip_estacao.'"';
		$result_del = mysql_query($query_del);
		}
	}
else
	{	 
	
	// Autentica��o dos agentes:
	autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

	$id_ip_rede 					 = $v_dados_rede['id_ip_rede'];
	$v_te_serv_updates 				 = $v_dados_rede['te_serv_updates'];
	$v_te_web_manager_address 		 = $v_dados_rede['v_te_web_manager_address'];
	$v_te_path_serv_updates			 = $v_dados_rede['te_path_serv_updates'];
	$v_nm_usuario_login_serv_updates = $v_dados_rede['nm_usuario_login_serv_updates'];	
	$v_te_senha_login_serv_updates	 = $v_dados_rede['te_senha_login_serv_updates'];		
	$v_nu_porta_serv_updates	 	 = $v_dados_rede['nu_porta_serv_updates'];				

	$te_node_address 				 = DeCrypt($key,$iv,$_POST['te_node_address']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Endere�o MAC (MAC Address) 
	$id_so_new         				 = DeCrypt($key,$iv,$_POST['id_so']					,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Antigo Identificador de S.O. (Old O.S. ID) 
	$te_so           				 = DeCrypt($key,$iv,$_POST['te_so']					,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Novo Identificador de S.O. (New O.S. Id)
	$te_nome_computador				 = DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Nome do Computador (Computer Name)
	$te_workgroup 					 = DeCrypt($key,$iv,$_POST['te_workgroup']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Nome do Grupo de Trabalho (WorkGroup Name)
	$te_versao_cacic				 = DeCrypt($key,$iv,$_POST['te_versao_cacic']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Vers�o do Agente Principal (Version of Principal Agent)
	$te_versao_gercols				 = DeCrypt($key,$iv,$_POST['te_versao_gercols']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Vers�o do Agente Gerente de Coletas Ger_Cols (Version of PickUp Manager)
	$te_palavra_chave				 = DeCrypt($key,$iv,$_POST['te_palavra_chave']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Palavra-Chave para Acesso ao Agente Principal (Keyword to Access to Principal Agent)
	$te_tripa_perfis    			 = DeCrypt($key,$iv,$_POST['te_tripa_perfis']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); // Lista com Resultados de Sistemas Monitorados Pesquisados na Esta��o (Results of Search of Station�s Monitored Systems)


	/* Todas as vezes em que � feita a recupera��o das configura��es por um agente, � inclu�do 
	 o computador deste agente no BD, caso ainda n�o esteja inserido. */
	// ATEN��O: Retornar� um ARRAY contendo "id_so" e "te_so".
	$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
												$id_so_new,
												$te_so,
												$id_ip_rede, 
												$v_id_ip_estacao, 
												$te_nome_computador,
												$te_workgroup);									
											
	/* Atualizo a data/hora da �ltima vez em que o agente foi executado. */
	/* Atualizo as vers�es dos agentes principais. */	
	/* Atualizo a informa��o de vers�o(para uso futuro) do Sistema Operacional da esta��o. */		
	conecta_bd_cacic();

	$query = 'UPDATE 	computadores SET 
						dt_hr_ult_acesso = NOW(),
						te_so = "'.$arrSO['te_so'].'",
						te_ip = "'.$v_id_ip_estacao.'",						
			  	  		te_versao_cacic  = "' . $te_versao_cacic . '",
				  		te_versao_gercols= "' . $te_versao_gercols . '",
						te_palavra_chave="'.$te_palavra_chave.'"  
			  WHERE 	te_node_address = "'.$te_node_address.'" AND 
			  			id_so = "'.$arrSO['id_so'].'"';
	$result = mysql_query($query);

//  Alternativa de solu��o enviada ao sr. Elton Levi Schroder Fenner [elton.fenner@al.rs.gov.br], por ocasi�o da mensagem de erro
//  "Cannot redeclare eh_excecao() (previously declared in /var/www/cacic2/ws/get_config.php:228) in <b>/var/www/cacic2/ws/get_config.php</b> on line <b>228"
//
//	if (!function_exists('eh_excecao'))
//		{

		/* Essa funcao retorna 1 caso $te_node_address seja uma excecao para a a��o id_acao e 0 caso n�o seja. */
		function eh_excecao($id_acao, $te_node_address) 
			{
			$query_exc = '	SELECT 	count(*) as num_registros
							FROM 	acoes_excecoes
							WHERE 	id_acao = "'.$id_acao.'" AND 
									te_node_address = "'.$te_node_address.'"';
			conecta_bd_cacic();
			$result_exc = mysql_query($query_exc);
			$campos_exc = mysql_fetch_array($result_exc);
			return ($campos_exc['num_registros'] == 0?0:1);
			}
//		}

	/* Seleciona todos os perfis de aplicativos cadastrados para tratamento posterior */
	$query_monitorado = '	SELECT 		*
							FROM 		perfis_aplicativos_monitorados a,
										aplicativos_redes b
							WHERE       a.id_aplicativo = b.id_aplicativo AND
										a.nm_aplicativo NOT LIKE "%#DESATIVADO#%" AND
										b.id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
										b.id_local = '.$v_dados_rede['id_local'].' 										
							ORDER BY	a.id_aplicativo';				
	
	$result_monitorado 	= mysql_query($query_monitorado);
	$v_tripa_perfis1 = explode('#',$te_tripa_perfis);
	$v_retorno_MONITORADOS = '';			

	/* Seleciona os dados de coleta_forcada espec�ficos para este computador, que foram setados
		via detalhes/Op��es Administrativas */
	
	$query_coleta_forcada = '	SELECT 		dt_hr_coleta_forcada_estacao,te_nomes_curtos_modulos
								FROM 		computadores
								WHERE 		te_node_address = "'.$te_node_address.'" AND 
											id_so = "'.$arrSO['id_so'].'"';
	$result_coleta_forcada 	= mysql_query($query_coleta_forcada);
	$te_tripa_coleta = mysql_fetch_array($result_coleta_forcada);
	$v_tripa_coleta = explode('#',$te_tripa_coleta['te_nomes_curtos_modulos']);

	
	/* Seleciona todas as a��es/configura��es que tenham sido setadas como T (todas as redes) ou 
	   que tenham sido setadas como S (apenas redes selecionadas) e cuja a rede do agente seja uma das redes selecionadas.
	   Tamb�m � realizado um filtro baseado no sistema operacional do agente.
	   Al�m disso, o node address do agente n�o pode constar da rela��o de exce��es. */
	   
	$query = '	SELECT 		distinct acoes.id_acao, 
							acoes_redes.dt_hr_coleta_forcada,
							acoes.te_nome_curto_modulo
				FROM 		acoes, 
							acoes_so,
							acoes_redes
				WHERE 		(acoes_redes.cs_situacao = "T" OR 
				 			 acoes_redes.cs_situacao = "S") AND 
							 acoes_redes.id_ip_rede = "'.$id_ip_rede.'" AND
							 acoes_redes.id_local = '.$v_dados_rede['id_local'].' AND 
							 acoes.id_acao = acoes_redes.id_acao AND
							 acoes_so.id_acao = acoes.id_acao AND 
							 acoes_so.id_so = '.$arrSO['id_so'].' AND
							 acoes_so.id_local = '.$v_dados_rede['id_local'];	
//GravaTESTES($query);							 
	$result = mysql_query($query);

	while ($campos = mysql_fetch_array($result))
		{ 
		$id_acao = $campos['id_acao'];
		if (eh_excecao($id_acao, $te_node_address) == 0)
			{ 			
			$retorno_xml_values .= '<' . $id_acao . '>'.EnCrypt($key,$iv,'S',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</' . $id_acao . '>';

			if ($campos['dt_hr_coleta_forcada'] || $te_tripa_coleta['dt_hr_coleta_forcada_estacao'])
				{
				$v_dt_hr_coleta_forcada = $campos["dt_hr_coleta_forcada"];
				if (count($v_tripa_coleta) > 0 and
					$v_dt_hr_coleta_forcada < $te_tripa_coleta['dt_hr_coleta_forcada_estacao'] and
					in_array($campos["te_nome_curto_modulo"],$v_tripa_coleta))
					{
					$v_dt_hr_coleta_forcada = $te_tripa_coleta['dt_hr_coleta_forcada_estacao'];
					}
				$retorno_xml_values .= '<' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>' . EnCrypt($key,$iv,$v_dt_hr_coleta_forcada,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>';
				}
			if (!$boolAgenteLinux && trim($id_acao) == "cs_coleta_monitorado" && mysql_num_rows($result_monitorado))
				{
				// ***************************************************
				// TODO: Melhorar identifica��o do S.O. neste ponto!!!
				// ***************************************************
				// Apenas catalogo as vers�es anteriores aos NT Like
				// Colocar abaixo, como elementos do array as identifica��es internas dos MS-Windows menores que WinNT
				$v_arr_W9x = array(	'2.4.0.1',   // 95
									'2',   // 95OSR
									'3',   // 98
									'4',   // 98OSR2
									'5'    // ME
								   );

				// reset($result_monitorado);			
				mysql_data_seek($result_monitorado,0);				
				while ($campo_monitorado = mysql_fetch_array($result_monitorado)) 
					{ 
					$v_achei = 0;
					for($i = 0; $i < count($v_tripa_perfis1); $i++ ) 
						{
						$v_tripa_perfis2 = explode(',',$v_tripa_perfis1[$i]);
						if ($campo_monitorado['id_aplicativo']==$v_tripa_perfis2[0] &&
							$campo_monitorado['dt_atualizacao']==$v_tripa_perfis2[1])
								$v_achei = 1;
						}

					if ($v_achei==0 && ($campo_monitorado['id_so'] == 0 || $campo_monitorado['id_so'] == $arrSO['id_so']))
						{
						if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';
	
						$v_te_ide_licenca = trim($campo_monitorado['te_ide_licenca']);					
						if ($campo_monitorado['cs_ide_licenca']=='0') 	
							$v_te_ide_licenca = '';					
						
						$v_retorno_MONITORADOS .= $campo_monitorado['id_aplicativo']	.	','.
										  $campo_monitorado['dt_atualizacao']			.	','.
										  $campo_monitorado['cs_ide_licenca'] 			. 	','.
										  $v_te_ide_licenca								.	',';
	
						if (in_array($arrSO['id_so'],$v_arr_W9x)) 
							{
							$v_te_arq_ver_eng_w9x 	= trim($campo_monitorado['te_arq_ver_eng_w9x']);
							if ($v_te_arq_ver_eng_w9x=='') 	$v_te_arq_ver_eng_w9x 	= '.';						
	
							$v_te_arq_ver_pat_w9x 	= trim($campo_monitorado['te_arq_ver_pat_w9x']);				
							if ($v_te_arq_ver_pat_w9x=='') 	$v_te_arq_ver_pat_w9x 	= '.';
	
							$v_te_car_inst_w9x 	    = trim($campo_monitorado['te_car_inst_w9x']);								
							if ($campo_monitorado['cs_car_inst_w9x']=='0') 	$v_te_car_inst_w9x 	= '';
	
							$v_te_car_ver_w9x 	    = trim($campo_monitorado['te_car_ver_w9x']);								
							if ($campo_monitorado['cs_car_ver_wnt']=='0') 	$v_te_car_ver_w9x 	= '';
	
	//						$v_retorno_MONITORADOS .= $campo_monitorado['te_dir_padrao_w9x']	.','.
	//                      Linha comentada devido ao fato da possibilidade de se informar o caminho completo na descri��o do arquivo a ser pesquisado
	//                      Foi mantido o transporte de "." para futuras implementa��es
							$v_retorno_MONITORADOS .= '.'                                     	.','.									
												$campo_monitorado['cs_car_inst_w9x']	.','.						
												$v_te_car_inst_w9x						.','.
												$campo_monitorado['cs_car_ver_w9x']		.','.	
												$v_te_car_ver_w9x						.','.														
												$v_te_arq_ver_eng_w9x					.','.
												$v_te_arq_ver_pat_w9x						;
							}
						else
							{
							
							$v_te_arq_ver_eng_wnt 	= trim($campo_monitorado['te_arq_ver_eng_wnt']);
							if ($v_te_arq_ver_eng_wnt=='') 	$v_te_arq_ver_eng_wnt 				= '.';						
	
							$v_te_arq_ver_pat_wnt 	= trim($campo_monitorado['te_arq_ver_pat_wnt']);								
							if ($v_te_arq_ver_pat_wnt=='') 	$v_te_arq_ver_pat_wnt 				= '.';
	
							$v_te_car_inst_wnt 	    = trim($campo_monitorado['te_car_inst_wnt']);								
							if ($campo_monitorado['cs_car_inst_wnt']=='0') 	$v_te_car_inst_wnt 	= '';
	
							$v_te_car_ver_wnt 	    = trim($campo_monitorado['te_car_ver_wnt']);								
							if ($campo_monitorado['cs_car_ver_wnt']=='0') 	$v_te_car_ver_wnt 	= '';
	
	//						$v_retorno_MONITORADOS .= $campo_monitorado['te_dir_padrao_wnt']	.','.						
	//                      Linha comentada devido ao fato da possibilidade de se informar o caminho completo na descri��o do arquivo a ser pesquisado
	//                      Foi mantido o transporte de "." para futuras implementa��es
							$v_retorno_MONITORADOS .=   '.'                    					.','.						
												$campo_monitorado['cs_car_inst_wnt']	.','.
												$v_te_car_inst_wnt                 		.','.
												$campo_monitorado['cs_car_ver_wnt']		.','.	
												$v_te_car_ver_wnt               		.','.														
												$v_te_arq_ver_eng_wnt					.','.
												$v_te_arq_ver_pat_wnt;
							
							}
						$v_retorno_MONITORADOS .=   ',' . $campo_monitorado['in_disponibiliza_info'];

						if ($campo_monitorado['in_disponibiliza_info']=='S')
							{
							$v_retorno_MONITORADOS .= ',' . $campo_monitorado['nm_aplicativo'];
							}
						else
							{
							$v_retorno_MONITORADOS .= ',.';														
							}
						}
					}
				}
			}
		}

	$query_modulos = '	SELECT 	*
						FROM	redes_versoes_modulos
						WHERE 	id_ip_rede = "'.$id_ip_rede.'" AND
								id_local = '.$v_dados_rede['id_local'];

	//if $boolAgenteLinux && 
	$result_modulos	= mysql_query($query_modulos);
	while ($row_modulos = mysql_fetch_array($result_modulos))
		{
		if ($boolAgenteLinux && trim($row_modulos['cs_tipo_so']) == 'GNU/LINUX')
			{
			$retorno_xml_values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . EnCrypt($key,$iv,$row_modulos['nm_modulo'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
			$retorno_xml_values .= '<' . 'TE_HASH_PYCACIC>' . EnCrypt($key,$iv,$row_modulos['te_hash'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_HASH_PYCACIC>';									
			}
		elseif (!$boolAgenteLinux)
			{			
			$retorno_xml_values .= '<' . 'DT_VERSAO_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>' . EnCrypt($key,$iv,$row_modulos['te_versao_modulo'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/DT_VERSAO_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>';			
			$retorno_xml_values .= '<' . 'TE_HASH_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>' . EnCrypt($key,$iv,$row_modulos['te_hash'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_HASH_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>';						
			}
		}
	
	if ($v_retorno_MONITORADOS <> '') 
		$retorno_xml_values .= '<SISTEMAS_MONITORADOS_PERFIS>'.EnCrypt($key,$iv,$v_retorno_MONITORADOS,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</SISTEMAS_MONITORADOS_PERFIS>';

	// Configura��es relacionadas ao comportamento do agente.
	$query = 'SELECT 	in_exibe_bandeja,
						in_exibe_erros_criticos,
						nu_exec_apos,
						nu_intervalo_exec,
						nu_intervalo_renovacao_patrim,
						te_senha_adm_agente,
						te_enderecos_mac_invalidos,
						te_janelas_excecao,
						nu_porta_srcacic,
						nu_timeout_srcacic
			FROM 		configuracoes_locais
			WHERE		id_local = '.$v_dados_rede['id_local'];

	//conecta_bd_cacic();										
	$result_configs = mysql_query($query);
	$campos_configs = mysql_fetch_array($result_configs);

	for ($i=0; $i < mysql_num_fields($result_configs); $i++) 
		{
		$nome_campo = mysql_field_name($result_configs, $i); 
		$retorno_xml_values .= '<' . $nome_campo . '>' . EnCrypt($key,$iv,$campos_configs[$nome_campo],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</' . $nome_campo . '>';      
		}

	// Caso n�o haja identificador de servidor de updates informado, assumir� o nome do servidor gerente
	// PERIGO: O servidor WEB talvez n�o tenha FTP configurado
	if (trim($v_te_serv_updates)      == '') $v_te_serv_updates      = substr($_ENV['HOSTNAME'],0,strpos($_ENV['HOSTNAME'],'.'));

	// Caso n�o haja identificador de path no servidor de updates informado, assumir� o caminho abaixo
	if (trim($v_te_path_serv_updates) == '') $v_te_path_serv_updates = '/home/cacic/updates';

	}						
$retorno_xml_values .= '<TE_WEB_MANAGER_ADDRESS>'                 . EnCrypt($key,$iv,$v_dados_rede['te_serv_cacic']					,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</TE_WEB_MANAGER_ADDRESS>';		
$retorno_xml_values .= '<TE_SERV_UPDATES>'               . EnCrypt($key,$iv,$v_dados_rede['te_serv_updates']				,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</TE_SERV_UPDATES>';			
$retorno_xml_values .= '<NU_PORTA_SERV_UPDATES>'         . EnCrypt($key,$iv,$v_dados_rede['nu_porta_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</NU_PORTA_SERV_UPDATES>';
$retorno_xml_values .= '<TE_PATH_SERV_UPDATES>'          . EnCrypt($key,$iv,$v_dados_rede['te_path_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</TE_PATH_SERV_UPDATES>';			
$retorno_xml_values .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . EnCrypt($key,$iv,$v_dados_rede['nm_usuario_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</NM_USUARIO_LOGIN_SERV_UPDATES>';	
$retorno_xml_values .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . EnCrypt($key,$iv,$v_dados_rede['te_senha_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</TE_SENHA_LOGIN_SERV_UPDATES>';
$retorno_xml_values .= '<CS_PERMITIR_DESATIVAR_SRCACIC>' . EnCrypt($key,$iv,$v_dados_rede['cs_permitir_desativar_srcacic']  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</CS_PERMITIR_DESATIVAR_SRCACIC>';
$retorno_xml_values .= '<ID_LOCAL>' 					 . EnCrypt($key,$iv,$v_dados_rede['id_local']        				,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</ID_LOCAL>';

// --------------- Retorno de Classificador de CRIPTOGRAFIA --------------- //
if ($v_cs_cipher <> '1') $v_cs_cipher --;	

// Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informa��es trafegadas 
//$v_cs_cipher = '0';

$retorno_xml_header .= '<cs_cipher>'.$v_cs_cipher.'</cs_cipher>';		
// ----------------------------------------------------------------------- //


// --------------- Retorno de Classificador de COMPRESS�O --------------- //
$pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
if ($pos <> -1 && $v_cs_compress <>'1') $v_cs_compress -= 1;

// Caso o n�vel de compress�o sera setado para 0(zero) o indicador deve retornar 0(zero)
if ($v_compress_level == '0') $v_cs_compress = '0';

// Comente/Descomente a linha abaixo para habilitar/desabilitar a compacta��o de informa��es trafegadas 
$v_cs_compress = '0'; 
$retorno_xml_header .= '<cs_compress>'.$v_cs_compress.'</cs_compress>';
// ---------------------------------------------------------------------- //

$retorno_xml = $retorno_xml_header . $retorno_xml_values . "</CONFIGS>";  
//GravaTESTES($retorno_xml);
echo $retorno_xml;	  
?>