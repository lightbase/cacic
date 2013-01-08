<?php 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

 Objetivo:
 ---------
 Esse script tem como objetivo enviar aos agentes as configurações (em XML) que são específicas para
 o agente em questão. São levados em consideração a rede do agente e seu sistema operacional.
 Também há um sistema de exceções, onde um computador que consta nessa relação de exceções 
 não recebe as configurações.
*/
require_once('../include/common_top.php');

GravaTESTES('*************');
GravaTESTES('* getConfig *');
GravaTESTES('*************');
// Essa condição testa se foi o "InstallCACIC" chamado para instalação ou o "Gerente de Coletas" para validar IP da estação...
if (trim(DeCrypt($key,$iv,$_POST['in_instalacao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='OK' || 
	trim(DeCrypt($key,$iv,$_POST['in_teste']     ,$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='OK')
	{	
GravaTESTES('Entrada 1 : POST');	
foreach($HTTP_POST_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('Entrada 1.1 : GET');	
foreach($HTTP_GET_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);

	$v_te_fila_ftp = '0';
	$v_id_ftp      = ($_POST['id_ftp']?trim(DeCrypt($key,$iv,$_POST['id_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)):'');

	conecta_bd_cacic();	
	// Operações para agrupamento de FTP por subredes	
	if (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='1' &&
	    !$v_id_ftp)
		{
GravaTESTES('Entrada 2');			
		// TimeOut definido para 5 minutos, ou seja, tempo máximo para as estações efetuarem FTP dos módulos necessários
		// 1 minuto = 60000 milisegundos
		// 5 * 60000 milisegundos = 5 minutos (TimeOut)
		$v_timeout = time() - (5 * 60000);

		// Exclusão por timeout
		$query_del = 'DELETE 
					  FROM  	redes_grupos_ftp
					  WHERE 	id_rede = '.$arrDadosRede['id_rede'].' AND
								te_ip_computador = "'.$arrDadosComputador['te_ip_computador'].'"';
		$result_del = mysql_query($query_del);	
		
		// Contagem por subrede
		$query_grupo = 'SELECT 		count(*) as total_estacoes
					   	FROM 		redes_grupos_ftp
					   	WHERE 		id_rede = '.$arrDadosComputador['id_rede'].' FOR UPDATE';
		$result_grupo = mysql_query($query_grupo);
		$total = mysql_fetch_array($result_grupo);
		
		// Caso o grupo de estações esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
		// Posteriormente, poderemos calcular uma média para o intervalo, em função do link da subrede
		if ($total['total_estacoes'] >= $arrDadosRede['nu_limite_ftp']) // Se for maior que o Limite FTP, configurado em Administração/Cadastros/SubRedes 
			$v_te_fila_ftp = '5'; // Tempo em minutos
		else
			{
			$queryINS  = 'INSERT 
						  INTO 		redes_grupos_ftp(id_rede,te_ip_computador,nu_hora_inicio) 
						  VALUES    ('.$arrDadosRede['id_rede'].',
						  			 "'. $arrDadosComputador['te_ip_computador'].'",'.
									   time().')';
			$resultINS = mysql_query($queryINS);			
			}
		$strXML_Values .= '<TE_FILA_FTP>' 	. EnCrypt($key,$iv,$v_te_fila_ftp  		,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_FILA_FTP>';											
		$strXML_Values .= '<ID_FTP>' 		. EnCrypt($key,$iv, mysql_insert_id()  	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_FTP>';													
		}		
	elseif (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='2') // Operação concluída com sucesso!
		{
GravaTESTES('Entrada 3');			
		$query_del = 'DELETE 
					  FROM  redes_grupos_ftp
					  WHERE id_rede = '.$arrDadosRede['id_rede'].' AND
					        te_ip_computador = "'.$arrDadosComputador['te_ip_computador'].'"';
		$result_del = mysql_query($query_del);
		}
	}
else
	{	 
GravaTESTES('Entrada 4');		
	/* Atualizo a data/hora da última vez em que o agente foi executado. */
	/* Atualizo as versões dos agentes principais. */	
	/* Atualizo a informação de versão(para uso futuro) do Sistema Operacional da estação. */		
	conecta_bd_cacic();

	$query = 'UPDATE 	computadores SET 
						dt_hr_ult_acesso = NOW(),
						te_ip_computador = "'	. $arrDadosComputador['te_ip_computador']													. '",						
						id_rede = '				. $arrDadosRede['id_rede']																	. ',																		
			  	  		te_versao_cacic  = "' 	. DeCrypt($key,$iv,$_POST['te_versao_cacic']  , $v_cs_cipher,$v_cs_compress,$strPaddingKey) . '",
				  		te_versao_gercols= "' 	. DeCrypt($key,$iv,$_POST['te_versao_gercols'], $v_cs_cipher,$v_cs_compress,$strPaddingKey) . '",
						te_palavra_chave="'		. DeCrypt($key,$iv,$_POST['te_palavra_chave'] , $v_cs_cipher,$v_cs_compress,$strPaddingKey)	. '"  
			  WHERE 	id_computador = '		. $arrDadosComputador['id_computador'];
GravaTESTES('query: ' . $query);					  
	$result = mysql_query($query);

	/* Essa funcao retorna 1 caso $te_node_address seja uma excecao para a ação id_acao e 0 caso não seja. */
	function eh_excecao($id_acao, $te_node_address) 
		{
		$query_exc = '	SELECT 	count(*) as num_registros
						FROM 	acoes_excecoes
						WHERE 	id_acao = "'.$id_acao.'" AND 
								te_node_address = "'.$te_node_address.'"';
GravaTESTES('eh_excecao: ' . $query_exc);																	
		conecta_bd_cacic();
		$result_exc = mysql_query($query_exc);
		$campos_exc = mysql_fetch_array($result_exc);
		return ($campos_exc['num_registros'] ? $campos_exc['num_registros'] : 0);
		}

	/* Seleciona todos os perfis de aplicativos cadastrados para tratamento posterior */
	$query_monitorado = '	SELECT 		*
							FROM 		perfis_aplicativos_monitorados a,
										aplicativos_redes b
							WHERE       a.id_aplicativo = b.id_aplicativo AND
										a.nm_aplicativo NOT LIKE "%#DESATIVADO#%" AND
										b.id_rede = '.$arrDadosRede['id_rede'].' 										
							ORDER BY	a.id_aplicativo';				
GravaTESTES('query_monitorado: ' . $query_monitorado);	
	$result_monitorado 		= mysql_query($query_monitorado);
	$arrPerfis1				= explode('#',DeCrypt($key,$iv,$_POST['te_tripa_perfis'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));
	$v_retorno_MONITORADOS 	= '';			

	/* Seleciona os dados de coleta_forcada específicos para este computador, que foram setados
		via detalhes/Opções Administrativas */
	
	$query_coleta_forcada 	= '	SELECT 		dt_hr_coleta_forcada_estacao,te_nomes_curtos_modulos
								FROM 		computadores
								WHERE 		id_computador = '.$arrDadosComputador['id_computador'];
GravaTESTES('query_coleta_forcada: ' . $query_coleta_forcada);									
	$result_coleta_forcada 	= mysql_query($query_coleta_forcada);
	$te_tripa_coleta 		= mysql_fetch_array($result_coleta_forcada);
	$v_tripa_coleta 		= explode('#',$te_tripa_coleta['te_nomes_curtos_modulos']);

	
	/* Seleciona todas as ações/configurações que tenham sido setadas como T (todas as redes) ou 
	   que tenham sido setadas como S (apenas redes selecionadas) e cuja a rede do agente seja uma das redes selecionadas.
	   Também é realizado um filtro baseado no sistema operacional do agente.
	   Além disso, o node address do agente não pode constar da relação de exceções. */
	   
	$query = '	SELECT 		distinct acoes.id_acao, 
							acoes_redes.dt_hr_coleta_forcada,
							acoes.te_nome_curto_modulo
				FROM 		acoes, 
							acoes_so,
							acoes_redes
				WHERE 		acoes_redes.id_rede = '.$arrDadosRede['id_rede'].' AND 
							acoes.id_acao = acoes_redes.id_acao AND
							acoes_so.id_acao = acoes.id_acao AND 
							acoes_so.id_so = '.$arrDadosComputador['id_so'].' AND
							acoes_so.id_rede = '.$arrDadosRede['id_rede'];	
GravaTESTES('query: ' . $query);									
	$result = mysql_query($query);

	while ($campos = mysql_fetch_array($result))
		{ 
		if (eh_excecao($campos['id_acao'], $arrDadosComputador['te_node_address']) == 0)
			{ 			
			$strXML_Values .= '<' . $campos['id_acao'] . '>'.EnCrypt($key,$iv,'S',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</' . $campos['id_acao'] . '>';
GravaTESTES('strXML_Values 1: ' . $strXML_Values);									
			if ($campos['dt_hr_coleta_forcada'] || $te_tripa_coleta['dt_hr_coleta_forcada_estacao'])
				{
				$v_dt_hr_coleta_forcada = $campos["dt_hr_coleta_forcada"];
				if (count($v_tripa_coleta) > 0 and
					$v_dt_hr_coleta_forcada < $te_tripa_coleta['dt_hr_coleta_forcada_estacao'] and
					in_array($campos["te_nome_curto_modulo"],$v_tripa_coleta))
					{
					$v_dt_hr_coleta_forcada = $te_tripa_coleta['dt_hr_coleta_forcada_estacao'];
					}
				$strXML_Values .= '<' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>' . EnCrypt($key,$iv,$v_dt_hr_coleta_forcada,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>';
				}
GravaTESTES('strXML_Values 2: ' . $strXML_Values);													
			if (!$boolAgenteLinux && trim($campos['id_acao']) == "cs_coleta_monitorado" && mysql_num_rows($result_monitorado))
				{
				// ***************************************************
				// TODO: Melhorar identificação do S.O. neste ponto!!!
				// ***************************************************
				// Apenas catalogo as versões anteriores aos NT Like
				// Colocar abaixo, como elementos do array as identificações internas dos MS-Windows menores que WinNT
				$arrSgSOtoOlds = array(	'W95',  
										'W95OSR',
										'W98', 
										'W98SE',
										'WME');

				// reset($result_monitorado);			
				mysql_data_seek($result_monitorado,0);				
				while ($campo_monitorado = mysql_fetch_array($result_monitorado)) 
					{ 
					$v_achei = 0;
					for($i = 0; $i < count($arrPerfis1); $i++ ) 
						{
						$arrPerfis2 = explode(',',$arrPerfis1[$i]);
						if ($campo_monitorado['id_aplicativo']==$arrPerfis2[0] &&
							$campo_monitorado['dt_atualizacao']==$arrPerfis2[1])
								$v_achei = 1;
						}

					if ($v_achei==0 && ($campo_monitorado['id_so'] == 0 || $campo_monitorado['id_so'] == $arrDadosComputador['id_so']))
						{
						if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';
	
						$v_te_ide_licenca = trim($campo_monitorado['te_ide_licenca']);					
						if ($campo_monitorado['cs_ide_licenca']=='0') 	
							$v_te_ide_licenca = '';					
						
						$v_retorno_MONITORADOS .= $campo_monitorado['id_aplicativo']	.	','.
										  $campo_monitorado['dt_atualizacao']			.	','.
										  $campo_monitorado['cs_ide_licenca'] 			. 	','.
										  $v_te_ide_licenca								.	',';
	
						if (in_array($arrSO['sg_so'],$arrSgSOtoOlds)) 
							{
							$v_te_arq_ver_eng_w9x 	= trim($campo_monitorado['te_arq_ver_eng_w9x']);
							if ($v_te_arq_ver_eng_w9x=='') 	$v_te_arq_ver_eng_w9x 	= '.';						
	
							$v_te_arq_ver_pat_w9x 	= trim($campo_monitorado['te_arq_ver_pat_w9x']);				
							if ($v_te_arq_ver_pat_w9x=='') 	$v_te_arq_ver_pat_w9x 	= '.';
	
							$v_te_car_inst_w9x 	    = trim($campo_monitorado['te_car_inst_w9x']);								
							if ($campo_monitorado['cs_car_inst_w9x']=='0') 	$v_te_car_inst_w9x 	= '';
	
							$v_te_car_ver_w9x 	    = trim($campo_monitorado['te_car_ver_w9x']);								
							if ($campo_monitorado['cs_car_ver_wnt']=='0') 	$v_te_car_ver_w9x 	= '';
	
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
						WHERE 	id_rede = '.$arrDadosRede['id_rede'];

	$result_modulos	= mysql_query($query_modulos);
	while ($row_modulos = mysql_fetch_array($result_modulos))
		{
		if (!$boolAgenteLinux)
			{			
			$strXML_Values .= '<' . 'DT_VERSAO_' 	. str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>' . EnCrypt($key,$iv,$row_modulos['te_versao_modulo']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/DT_VERSAO_' 	. str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>';			
			$strXML_Values .= '<' . 'TE_HASH_' 	. str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>' 			. EnCrypt($key,$iv,$row_modulos['te_hash']			,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/TE_HASH_' 	. str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>';						
			}		
		else
			{
			$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' 	. EnCrypt($key,$iv,$row_modulos['nm_modulo'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
			$strXML_Values .= '<' . 'TE_HASH_PYCACIC>' 				. EnCrypt($key,$iv,$row_modulos['te_hash']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_HASH_PYCACIC>';									
			}
		}
	
	if ($v_retorno_MONITORADOS <> '') 
		$strXML_Values .= '<SISTEMAS_MONITORADOS_PERFIS>'.EnCrypt($key,$iv,$v_retorno_MONITORADOS,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</SISTEMAS_MONITORADOS_PERFIS>';

	// Configurações relacionadas ao comportamento do agente.
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
			WHERE		id_local = '.$arrDadosRede['id_local'];

	conecta_bd_cacic();										
	$result_configs = mysql_query($query);
	$campos_configs = mysql_fetch_array($result_configs);

	for ($i=0; $i < mysql_num_fields($result_configs); $i++) 
		{
		$nome_campo = mysql_field_name($result_configs, $i); 
		$strXML_Values .= '<' . $nome_campo . '>' . EnCrypt($key,$iv,$campos_configs[$nome_campo],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</' . $nome_campo . '>';      
		}
	}						

$strXML_Values .= '<TE_REDE_OK>' 		. EnCrypt($key,$iv,($arrDadosComputador['id_rede'] ? 'S' : 'N' ) , $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_REDE_OK>';		

$arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
if ($boolAgenteLinux)
	{
	// Arghh! O PyCACIC espera pelo nome completo do pacote TGZ
	$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . EnCrypt($key,$iv,$arrVersionsAndHashes['te_pacote_PyCACIC']     ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
	$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_HASH>'		 . EnCrypt($key,$iv,$arrVersionsAndHashes['te_pacote_PyCACIC_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' . '/TE_PACOTE_PYCACIC_HASH>';													
	}
else
	{
	$strXML_Values .= '<MainProgramName>'  									. EnCrypt($key,$iv,CACIC_MAIN_PROGRAM_NAME.'.EXE'	 										,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/MainProgramName>';
	$strXML_Values .= '<LocalFolderName>' 									. EnCrypt($key,$iv,CACIC_LOCAL_FOLDER_NAME													,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/LocalFolderName>';				
	$strXML_Values .= '<'. strtoupper(CACIC_MAIN_PROGRAM_NAME).'.EXE_VER>' 	. EnCrypt($key,$iv,$arrVersionsAndHashes[strtolower(CACIC_MAIN_PROGRAM_NAME).'.exe_VER'] 	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/'.strtoupper(CACIC_MAIN_PROGRAM_NAME).'.EXE_VER>';
	$strXML_Values .= '<'. strtoupper(CACIC_MAIN_PROGRAM_NAME).'.EXE_HASH>'	. EnCrypt($key,$iv,$arrVersionsAndHashes[strtolower(CACIC_MAIN_PROGRAM_NAME).'.exe_HASH'] 	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/'.strtoupper(CACIC_MAIN_PROGRAM_NAME).'.EXE_HASH>';				
	$strXML_Values .= '<CACICSERVICE.EXE_VER>'   	    					. EnCrypt($key,$iv,$arrVersionsAndHashes['cacicservice.exe_VER'] 							,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)  . '<' . '/CACICSERVICE.EXE_VER>';								
	$strXML_Values .= '<CACICSERVICE.EXE_HASH>' 							. EnCrypt($key,$iv,$arrVersionsAndHashes['cacicservice.exe_HASH']							,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)  . '<' . '/CACICSERVICE.EXE_HASH>';								
	$strXML_Values .= '<GERCOLS.EXE_VER>' 	 								. EnCrypt($key,$iv,$arrVersionsAndHashes['gercols.exe_VER']									,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/GERCOLS.EXE_VER>';			
	$strXML_Values .= '<GERCOLS.EXE_HASH>' 									. EnCrypt($key,$iv,$arrVersionsAndHashes['gercols.exe_HASH']								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/GERCOLS.EXE_HASH>';							
	$strXML_Values .= '<CHKSIS.EXE_VER>'   	 								. EnCrypt($key,$iv,$arrVersionsAndHashes['chksis.exe_VER'] 	 								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)  . '<' . '/CHKSIS.EXE_VER>';						
	$strXML_Values .= '<CHKSIS.EXE_HASH>'   								. EnCrypt($key,$iv,$arrVersionsAndHashes['chksis.exe_HASH']  								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/CHKSIS.EXE_HASH>';										
	$strXML_Values .= '<SRCACICSRV.EXE_VER>' 	 							. EnCrypt($key,$iv,$arrVersionsAndHashes['srcacicsrv.exe_VER']								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/SRCACICSRV.EXE_VER>';			
	$strXML_Values .= '<SRCACICSRV.EXE_HASH>' 								. EnCrypt($key,$iv,$arrVersionsAndHashes['srcacicsrv.exe_HASH']								,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) 	. '<' . '/SRCACICSRV.EXE_HASH>';											
	}

$strXML_Values .= '<WebServicesFolderName>' 		. EnCrypt($key,$iv,CACIC_WEB_SERVICES_FOLDER_NAME					,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/WebServicesFolderName>';			
$strXML_Values .= '<WebManagerAddress>'        	 	. EnCrypt($key,$iv,$arrDadosRede['te_serv_cacic']					,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/WebManagerAddress>';		
$strXML_Values .= '<TE_SERV_UPDATES>'               . EnCrypt($key,$iv,$arrDadosRede['te_serv_updates']					,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/TE_SERV_UPDATES>';			
$strXML_Values .= '<NU_PORTA_SERV_UPDATES>'         . EnCrypt($key,$iv,$arrDadosRede['nu_porta_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/NU_PORTA_SERV_UPDATES>';
$strXML_Values .= '<TE_PATH_SERV_UPDATES>'          . EnCrypt($key,$iv,$arrDadosRede['te_path_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/TE_PATH_SERV_UPDATES>';			
$strXML_Values .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . EnCrypt($key,$iv,$arrDadosRede['nm_usuario_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/NM_USUARIO_LOGIN_SERV_UPDATES>';	
$strXML_Values .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . EnCrypt($key,$iv,$arrDadosRede['te_senha_login_serv_updates']		,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/TE_SENHA_LOGIN_SERV_UPDATES>';
$strXML_Values .= '<CS_PERMITIR_DESATIVAR_SRCACIC>' . EnCrypt($key,$iv,$arrDadosRede['cs_permitir_desativar_srcacic']  	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/CS_PERMITIR_DESATIVAR_SRCACIC>';
$strXML_Values .= '<ID_LOCAL>' 					 	. EnCrypt($key,$iv,$arrDadosRede['id_local']        				,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' . '/ID_LOCAL>';
$strXML_Values .= '<STATUS>' 						. 'OK'																																. '<' . '/STATUS>';		

require_once('../include/common_bottom.php');
?>