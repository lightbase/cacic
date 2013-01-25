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

// Essa condição testa se foi o "InstallCACIC" chamado para instalação ou o "Gerente de Coletas" para validar IP da estação...
if (trim($_POST['in_instalacao'])=='OK')
	{	
	$v_te_fila_ftp = '0';
	$v_id_ftp      = ($_POST['id_ftp']?trim($_POST['id_ftp']):'');

	conecta_bd_cacic();	
	// Operações para agrupamento de FTP por subredes	
	if (trim($_POST['te_fila_ftp'])=='1' &&
	    !$v_id_ftp)
		{
		// TimeOut definido para 5 minutos, ou seja, tempo máximo para as estações efetuarem FTP dos módulos necessários
		// 1 minuto = 60000 milisegundos
		// 5 * 60000 milisegundos = 5 minutos (TimeOut)
		$v_timeout = time() - (5 * 60000);

		// Exclusão por timeout
		$query_del = 'DELETE 
					  FROM  	redes_grupos_ftp
					  WHERE 	id_rede = '			 .$arrDadosComputador[0]['id_rede']		 .' AND
								te_ip_computador = "'.$arrDadosComputador[0]['te_ip_computador'].'"';
		$result_del = mysql_query($query_del);	
		
		// Contagem por subrede
		$query_grupo = 'SELECT 		count(*) as total_estacoes
					   	FROM 		redes_grupos_ftp
					   	WHERE 		id_rede = '.$arrDadosComputador[0]['id_rede'].' FOR UPDATE';
		$result_grupo = mysql_query($query_grupo);
		$total = mysql_fetch_array($result_grupo);
		
		// Caso o grupo de estações esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
		// Posteriormente, poderemos calcular uma média para o intervalo, em função do link da subrede
		if ($total['total_estacoes'] >= $arrDadosRede[0]['nu_limite_ftp']) // Se for maior que o Limite FTP, configurado em Administração/Cadastros/SubRedes 
			$v_te_fila_ftp = '5'; // Tempo em minutos
		else
			{
			$queryINS  = 'INSERT 
						  INTO 		redes_grupos_ftp(id_rede,te_ip_computador,nu_hora_inicio) 
						  VALUES    ('.$arrDadosComputador[0]['id_rede'].',
						  			 "'. $arrDadosComputador[0]['te_ip_computador'].'",
									 NOW())';
			$resultINS = mysql_query($queryINS);			
			}
		$strXML_Values .= '<TE_FILA_FTP>' 	. $v_te_fila_ftp  	 . '</TE_FILA_FTP>';											
		$strXML_Values .= '<ID_FTP>' 		. mysql_insert_id()  . '</ID_FTP>';													
		}		
	elseif (trim($_POST['te_fila_ftp'])=='2') // Operação concluída com sucesso!
		{
		$query_del = 'DELETE 
					  FROM  redes_grupos_ftp
					  WHERE id_rede = '				. $arrDadosComputador[0]['id_rede']		 .' AND
					        te_ip_computador = "'	. $arrDadosComputador[0]['te_ip_computador'].'"';
		$result_del = mysql_query($query_del);
		}
	}
else
	{	 
	conecta_bd_cacic();

	/* Caso tenha recebido uma palavra-chave (atribuída em common_top), atualizo-a */
	if ($strTePalavraChave <> '')
		{
		$query = 'UPDATE 	computadores SET 
							te_palavra_chave="' . $strTePalavraChave . '"  
				  WHERE 	id_computador = '	. $arrDadosComputador[0]['id_computador'];
		$result = mysql_query($query);
		}

	/* Essa funcao retorna 1 caso $te_node_address seja uma excecao para a ação id_acao e 0 caso não seja. */
	function eh_excecao($id_acao, $te_node_address) 
		{
		$query_exc = '	SELECT 	count(*) as num_registros
						FROM 	acoes_excecoes
						WHERE 	id_acao = "'.$id_acao.'" AND 
								te_node_address = "'.$te_node_address.'"';
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
										b.id_rede = '.$arrDadosComputador[0]['id_rede'].' 										
							ORDER BY	a.id_aplicativo';				
	$result_monitorado 		= mysql_query($query_monitorado);
	$arrPerfis1				= explode('#',DeCrypt($_POST['te_tripa_perfis'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));
	$v_retorno_MONITORADOS 	= '';			

	/* Seleciona os dados de coleta_forcada específicos para este computador, que foram setados
		via detalhes/Opções Administrativas */
	
	$query_coleta_forcada 	= '	SELECT 		dt_hr_coleta_forcada_estacao,te_nomes_curtos_modulos
								FROM 		computadores
								WHERE 		id_computador = '.$arrDadosComputador[0]['id_computador'];
	$result_coleta_forcada 	= mysql_query($query_coleta_forcada);
	$te_tripa_coleta 		= mysql_fetch_array($result_coleta_forcada);
	$v_tripa_coleta 		= explode('#',$te_tripa_coleta['te_nomes_curtos_modulos']);

	
	/* Seleciona todas as ações/configurações que tenham sido setadas como T (todas as redes) ou 
	   que tenham sido setadas como S (apenas redes selecionadas) e cuja a rede do agente seja uma das redes selecionadas.
	   Também é realizado um filtro baseado no sistema operacional do agente.
	   Além disso, o node address do agente não pode constar da relação de exceções. 
	*/
	   
	$query = '	SELECT 		distinct acoes.id_acao, 
							acoes_redes.dt_hr_coleta_forcada,
							acoes.te_nome_curto_modulo,
							acoes.te_descricao_breve							
				FROM 		acoes, 
							acoes_so,
							acoes_redes
				WHERE 		acoes_redes.id_rede = '.$arrDadosComputador[0]['id_rede'].' AND 
							acoes.id_acao = acoes_redes.id_acao AND
							acoes_so.id_acao = acoes.id_acao AND 
							acoes_so.id_so = '.$arrDadosComputador[0]['id_so'].' AND
							acoes_so.id_rede = '.$arrDadosComputador[0]['id_rede'];	
GravaTESTES('query para CollectsDefinitions: ' . $query);							
	$result = mysql_query($query);

	while ($campos = mysql_fetch_array($result))
		{ 
		$strCollectsDefinitions .= '[' . $campos['id_acao'] . ']';				
		if (eh_excecao($campos['id_acao'], $arrDadosComputador[0]['te_node_address']) == 0)
			{ 			
			if (substr($campos['id_acao'],0,4) == 'col_')
				{
				$strCollectsDefinitions .= '[te_descricao_breve]' . $campos['te_descricao_breve'] . '[/te_descricao_breve]';									
				$strAcoesSelecionadas .= ($strAcoesSelecionadas ? ',' : '') . $campos['id_acao'];
				
				// Obtendo Definições de Classes para Coletas
				$strCollectsDefinitions .= '[ClassesAndProperties]';									
					
				$queryCD = 'SELECT 		c.nm_class_name,cp.nm_property_name,cd.te_where_clause
							FROM		classes c,
										classes_properties cp,
										collects_def_classes cd
							WHERE 		cd.id_acao = "' . $campos['id_acao'] . '" AND
										c.id_class = cd.id_class AND
										cp.id_class = cd.id_class 
							ORDER BY	c.nm_class_name,cp.nm_property_name';
				$resultCD				= mysql_query($queryCD);
				$arrClassesNames 		= array();
				$arrClassesWhereClauses = array();
				$strActualClassName		= '';
				$strPropertiesNames		= '';
				while ($rowCD = mysql_fetch_array($resultCD))
					{
					if (!$arrClassesNames[$rowCD['nm_class_name']])
						$arrClassesNames[$rowCD['nm_class_name']] = $rowCD['nm_class_name'];		
								
					if (($rowCD['te_where_clause']) && ($rowCD['te_where_clause'] <> 'NULL') && !$arrClassesWhereClauses[$rowCD['nm_class_name'] . '.WhereClause'])
						{
						$arrClassesWhereClauses[$rowCD['nm_class_name'] . '.WhereClause'] = '.';
						$strCollectsDefinitions .= '[' . $rowCD['nm_class_name'] . '.WhereClause]' . $rowCD['te_where_clause'] .'[/' . $rowCD['nm_class_name'] . '.WhereClause]';																							
						}

					if ($strActualClassName <> $rowCD['nm_class_name'])	
						{
						$strPropertiesNames .= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');
						$strPropertiesNames .= '[' . $rowCD['nm_class_name'] . '.Properties]';
						$strActualClassName  = $rowCD['nm_class_name'];
						}
					else
						$strPropertiesNames .= ',';
					
					$strPropertiesNames .= $rowCD['nm_property_name'];					
					}
	
				$strPropertiesNames 	.= ($strActualClassName ? '[/' . $strActualClassName . '.Properties]' : '');	
				
				$strCollectsDefinitions .= '[Classes]' 	  	. implode(',',$arrClassesNames) . '[/Classes]';												
				$strCollectsDefinitions .= '[Properties]' 	. $strPropertiesNames  			. '[/Properties]';																		
				$strCollectsDefinitions .= '[/ClassesAndProperties]';									
							
				if ($campos['dt_hr_coleta_forcada'] || $te_tripa_coleta['dt_hr_coleta_forcada_estacao'])
					{
					$v_dt_hr_coleta_forcada = $campos["dt_hr_coleta_forcada"];
					if (count($v_tripa_coleta) > 0 and
						$v_dt_hr_coleta_forcada < $te_tripa_coleta['dt_hr_coleta_forcada_estacao'] and
						in_array($campos["te_nome_curto_modulo"],$v_tripa_coleta))
						{
						$v_dt_hr_coleta_forcada = $te_tripa_coleta['dt_hr_coleta_forcada_estacao'];
						}
					$strCollectsDefinitions .= '[DT_HR_COLETA_FORCADA]' . $v_dt_hr_coleta_forcada . '[/DT_HR_COLETA_FORCADA]';
					}
					
				if (!$boolAgenteLinux && trim($campos['id_acao']) == "col_moni" && mysql_num_rows($result_monitorado))
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
	
						if ($v_achei==0 && ($campo_monitorado['id_so'] == 0 || $campo_monitorado['id_so'] == $arrDadosComputador[0]['id_so']))
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
					if ($v_retorno_MONITORADOS <> '') 
						$strXML_Values .= '<SISTEMAS_MONITORADOS_PERFIS>'. replaceInvalidHTTPChars($v_retorno_MONITORADOS).'</SISTEMAS_MONITORADOS_PERFIS>';
						
					$strCollectsDefinitions .= $v_retorno_MONITORADOS;						
					}
				}
			else
				$strCollectsDefinitions .= 'OK';											
			}
			
		$strCollectsDefinitions .= '[/' . $campos['id_acao'] . ']';					
		}
	
	$strCollectsDefinitions .= '[Actions]' . $strAcoesSelecionadas . '[/Actions]';
	}

if ($strCollectsDefinitions)
	$strXML_Values .= '<CollectsDefinitions>' . EnCrypt($strCollectsDefinitions,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</CollectsDefinitions>';		
	
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
		WHERE		id_local = '.$arrDadosRede[0]['id_local'];

conecta_bd_cacic();										
$result_configs = mysql_query($query);
$campos_configs = mysql_fetch_array($result_configs);

for ($i=0; $i < mysql_num_fields($result_configs); $i++) 
	{
	$nome_campo = mysql_field_name($result_configs, $i); 
	$value = $campos_configs[$nome_campo];
	if (($nome_campo == 'te_senha_adm_agente') ||
		($nome_campo == 'nu_porta_srcacic'))
		$value = EnCrypt($campos_configs[$nome_campo],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true);
		
	$strXML_Values .= '<' . $nome_campo . '>' . $value . '</' . $nome_campo . '>';      
	}

$queryRVM = 'SELECT 	*
			 FROM	redes_versoes_modulos
			 WHERE 	id_rede = '.$arrDadosComputador[0]['id_rede'];
			 
$resultRVM= mysql_query($queryRVM);
while ($rowRVM = mysql_fetch_array($resultRVM))
	{
	if (!$boolAgenteLinux)
		{			
		$strXML_Values .= '<' . strtoupper($rowRVM['nm_modulo']) . '_VER>'	. 		  $rowRVM['te_versao_modulo'] 																	 . '</' . strtoupper($rowRVM['nm_modulo']) . '_VER>';								
		$strXML_Values .= '<' . strtoupper($rowRVM['nm_modulo']) . '_HASH>'	. EnCrypt($rowRVM['te_hash']		 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true) . '</' . strtoupper($rowRVM['nm_modulo']) . '_HASH>';										
		}		
	else
		{
		$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . $row_modulos['nm_modulo']	. '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
		$strXML_Values .= '<' . 'TE_HASH_PYCACIC>' 				. $row_modulos['te_hash']	. '<' . '/TE_HASH_PYCACIC>';									
		}
	}

$arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
if ($boolAgenteLinux)
	{
	// Arghh! O PyCACIC espera pelo nome completo do pacote TGZ
	$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_DISPONIVEL>' . $arrVersionsAndHashes['te_pacote_PyCACIC']      . '<' . '/TE_PACOTE_PYCACIC_DISPONIVEL>';			
	$strXML_Values .= '<' . 'TE_PACOTE_PYCACIC_HASH>'		. $arrVersionsAndHashes['te_pacote_PyCACIC_HASH'] . '<' . '/TE_PACOTE_PYCACIC_HASH>';													
	}
else
	{
	$strXML_Values .= '<MainProgramName>'. CACIC_MAIN_PROGRAM_NAME.'.exe'. '<' . '/MainProgramName>';
	$strXML_Values .= '<LocalFolderName>'. CACIC_LOCAL_FOLDER_NAME		 . '<' . '/LocalFolderName>';				
	}

$strXML_Values .= '<TE_SERV_UPDATES>'               . $arrDadosRede[0]['te_serv_updates']																							. '<' . '/TE_SERV_UPDATES>';			
$strXML_Values .= '<NU_PORTA_SERV_UPDATES>'         . $arrDadosRede[0]['nu_porta_serv_updates']																					. '<' . '/NU_PORTA_SERV_UPDATES>';
$strXML_Values .= '<TE_PATH_SERV_UPDATES>'          . $arrDadosRede[0]['te_path_serv_updates']																						. '<' . '/TE_PATH_SERV_UPDATES>';			
$strXML_Values .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . EnCrypt($arrDadosRede[0]['nm_usuario_login_serv_updates'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true)	. '<' . '/NM_USUARIO_LOGIN_SERV_UPDATES>';	
$strXML_Values .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . EnCrypt($arrDadosRede[0]['te_senha_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true)	. '<' . '/TE_SENHA_LOGIN_SERV_UPDATES>';
$strXML_Values .= '<CS_PERMITIR_DESATIVAR_SRCACIC>' . $arrDadosRede[0]['cs_permitir_desativar_srcacic']  																			. '<' . '/CS_PERMITIR_DESATIVAR_SRCACIC>';
$strXML_Values .= '<ID_LOCAL>' 					 	. $arrDadosRede[0]['id_local']        																							. '<' . '/ID_LOCAL>';

require_once('../include/common_bottom.php');
?>