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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar aos agentes as configurações (em XML) que são específicas para
 o agente em questão. São levados em consideração a rede do agente e seu sistema operacional.
 Também há um sistema de exceções, onde um computador que consta nessa relação de exceções 
 não recebe as configurações.
*/

require_once('../include/library.php');

// Definição do nível de compressão (Default = 1 => mínimo)
//$v_compress_level = 1;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 

$retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><CONFIGS>';
$retorno_xml_values	 = '';

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

// Essa condição testa se foi o "chkcacic" chamado para instalação ou o "Gerente de Coletas" para validar IP da estação...
if (trim(DeCrypt($key,$iv,$_POST['in_chkcacic'],$v_cs_cipher,$v_cs_compress))=='chkcacic' || 
	trim(DeCrypt($key,$iv,$_POST['in_teste']   ,$v_cs_cipher,$v_cs_compress))=='OK')
	{	
	$v_dados_rede = getDadosRede();
	$retorno_xml_values .= '<TE_REDE_OK>'                    . EnCrypt($key,$iv,($v_dados_rede['id_ip_rede'] <> ''?'S':'N')		,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</TE_REDE_OK>';		
	$retorno_xml_values .= '<TE_SERV_CACIC>'                 . EnCrypt($key,$iv,$v_dados_rede['te_serv_cacic']					,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</TE_SERV_CACIC>';		
	$retorno_xml_values .= '<TE_SERV_UPDATES>'               . EnCrypt($key,$iv,$v_dados_rede['te_serv_updates']				,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</TE_SERV_UPDATES>';			
	$retorno_xml_values .= '<NU_PORTA_SERV_UPDATES>'         . EnCrypt($key,$iv,$v_dados_rede['nu_porta_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</NU_PORTA_SERV_UPDATES>';
	$retorno_xml_values .= '<TE_PATH_SERV_UPDATES>'          . EnCrypt($key,$iv,$v_dados_rede['te_path_serv_updates']			,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</TE_PATH_SERV_UPDATES>';			
	$retorno_xml_values .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . EnCrypt($key,$iv,$v_dados_rede['nm_usuario_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</NM_USUARIO_LOGIN_SERV_UPDATES>';	
	$retorno_xml_values .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . EnCrypt($key,$iv,$v_dados_rede['te_senha_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level). '</TE_SENHA_LOGIN_SERV_UPDATES>';						

	if (trim(DeCrypt($key,$iv,$_POST['in_chkcacic'],$v_cs_cipher,$v_cs_compress))=='chkcacic')
		{
		// Retorno as versões dos três principais agentes ao CHKCACIC para que sejam 
		$query_modulos = '	SELECT 	*
							FROM	redes_versoes_modulos
							WHERE 	id_ip_rede = "'.$v_dados_rede['id_local'].'" AND
									id_local = '.$v_dados_rede['id_local'];
		$result_modulos	= mysql_query($query_modulos);

		while ($row_modulos = mysql_fetch_array($result_modulos))
			{
			if (strtoupper($row_modulos['nm_modulo']) == 'CACIC2.EXE' ||
				strtoupper($row_modulos['nm_modulo']) == 'GER_COLS.EXE' ||
				strtoupper($row_modulos['nm_modulo']) == 'CHKSIS.EXE')
				{
				$retorno_xml_values .= '<' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>' . EnCrypt($key,$iv,$row_modulos['te_versao_modulo'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '<' . '/' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '>';
				}
			}
		}

	$v_te_fila_ftp = '0';
	$v_id_ftp      = ($_POST['id_ftp']?trim(DeCrypt($key,$iv,$_POST['id_ftp'],$v_cs_cipher,$v_cs_compress)):'');
	
	// Operações para agrupamento de FTP por subredes	
	if (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress))=='1' &&
	    !$v_id_ftp)
		{
		// TimeOut definido para 5 minutos, ou seja, tempo máximo para as estações efetuarem FTP dos módulos necessários
		// 1 minuto = 60000 milisegundos
		// 5 * 60000 milisegundos = 5 minutos (TimeOut)
		$v_timeout = time() - (5 * 60000);

		// Exclusão por timeout
		$query_del = 'DELETE 
					  FROM  	redes_grupos_ftp
					  WHERE 	id_local = '.$v_dados_rede['id_local'].' AND
					  			id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
								id_ip_estacao = "'.trim(DeCrypt($key,$iv,$_POST['id_ip_estacao'],$v_cs_cipher,$v_cs_compress)).'"';
		$result_del = mysql_query($query_del);	
		
		// Contagem por subrede
		$query_grupo = 'SELECT 		count(*) as total_estacoes
					   	FROM 		redes_grupos_ftp
					   	WHERE 		id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
									id_local = '.$v_dados_rede['id_local'].' FOR UPDATE';
		$result_grupo = mysql_query($query_grupo);
		$total = mysql_fetch_array($result_grupo);
		
		// Caso o grupo de estações esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
		// Posteriormente, poderemos calcular uma média para o intervalo, em função do link da subrede
		if ($total['total_estacoes'] >= $v_dados_rede['nu_limite_ftp']) // Se for maior que o Limite FTP, configurado em Administração/Cadastros/SubRedes 
			{
			$v_te_fila_ftp = '5'; // Tempo em minutos
			}
		else
			{
			$queryINS  = 'INSERT 
						  INTO 		redes_grupos_ftp(id_ip_rede,id_ip_estacao,nu_hora_inicio, id_local) 
						  VALUES    ("'.$v_dados_rede['id_ip_rede'].'","'.
						               trim(DeCrypt($key,$iv,$_POST['id_ip_estacao'],$v_cs_cipher,$v_cs_compress)).'",'.
									   time().','.
									   $v_dados_rede['id_local'].')';
			$resultINS = mysql_query($queryINS);			
			}
		$retorno_xml_values .= '<TE_FILA_FTP>' . $v_te_fila_ftp . '</TE_FILA_FTP>';											
		$retorno_xml_values .= '<ID_FTP>' . mysql_insert_id() . '</ID_FTP>';													
		}		
	elseif (trim(DeCrypt($key,$iv,$_POST['te_fila_ftp'],$v_cs_cipher,$v_cs_compress))=='2') // Operação concluída com sucesso!
		{
		$query_del = 'DELETE 
					  FROM  redes_grupos_ftp
					  WHERE id_local = '.$v_dados_rede['id_local'].' AND
					  	    id_ip_rede = "'.$v_dados_rede['id_ip_rede'].'" AND
					        id_ip_estacao = "'.trim(DeCrypt($key,$iv,$_POST['id_ip_estacao'],$v_cs_cipher,$v_cs_compress)).'"';
		$result_del = mysql_query($query_del);
		// Refaço o retorno_xml para redução do pacote a retornar...
		}
	}
else
	{	 
	// Autenticação dos agentes:
	autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress);

	$v_dados_rede 					 = getDadosRede();

	$id_ip_rede 					 = $v_dados_rede['id_ip_rede'];
	$v_te_serv_updates 				 = $v_dados_rede['te_serv_updates'];
	$v_te_serv_cacic				 = $v_dados_rede['te_serv_cacic'];
	$v_te_path_serv_updates			 = $v_dados_rede['te_path_serv_updates'];
	$v_nm_usuario_login_serv_updates = $v_dados_rede['nm_usuario_login_serv_updates'];	
	$v_te_senha_login_serv_updates	 = $v_dados_rede['te_senha_login_serv_updates'];		
	$v_nu_porta_serv_updates	 	 = $v_dados_rede['nu_porta_serv_updates'];				

	$te_node_address 				 = DeCrypt($key,$iv,$_POST['te_node_address']		,$v_cs_cipher,$v_cs_compress); 
	$id_so           				 = DeCrypt($key,$iv,$_POST['id_so']					,$v_cs_cipher,$v_cs_compress); 
	$te_so           				 = DeCrypt($key,$iv,$_POST['te_so']					,$v_cs_cipher,$v_cs_compress); 	
	$te_nome_computador				 = DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress); 
	$te_workgroup 					 = DeCrypt($key,$iv,$_POST['te_workgroup']			,$v_cs_cipher,$v_cs_compress); 
	$te_versao_cacic				 = DeCrypt($key,$iv,$_POST['te_versao_cacic']		,$v_cs_cipher,$v_cs_compress); 		
	$te_versao_gercols				 = DeCrypt($key,$iv,$_POST['te_versao_gercols']		,$v_cs_cipher,$v_cs_compress); 			
	$te_tripa_perfis    			 = DeCrypt($key,$iv,$_POST['te_tripa_perfis']		,$v_cs_cipher,$v_cs_compress); 

	/* Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
	 o computador deste agente no BD, caso ainda não esteja inserido. */
	inclui_computador_caso_nao_exista(	$te_node_address, 
										$id_so,
										$id_ip_rede, 
										DeCrypt($key,$iv,$_POST['te_ip'],$v_cs_cipher,$v_cs_compress), 
										$te_nome_computador,
										$te_workgroup);									
	
	
	/* Atualizo a data/hora da última vez em que o agente foi executado. */
	/* Atualizo as versões dos agentes principais. */	
	/* Atualizo a informação de versão(para uso futuro) do Sistema Operacional da estação. */		
	conecta_bd_cacic();

	$query = 'UPDATE 	computadores SET 
						dt_hr_ult_acesso = NOW(),
						te_so = "'.$te_so.'",
			  	  		te_versao_cacic  = "' . $te_versao_cacic . '",
				  		te_versao_gercols= "' . $te_versao_gercols . '" 
			  WHERE 	te_node_address = "'.$te_node_address.'" AND 
			  			id_so = "'.$id_so.'"';
	$result = mysql_query($query);

//GravaTESTES($query);
//  Alternativa de solução enviada ao sr. Elton Levi Schroder Fenner [elton.fenner@al.rs.gov.br], por ocasião da mensagem de erro
//  "Cannot redeclare eh_excecao() (previously declared in /var/www/cacic2/ws/get_config.php:228) in <b>/var/www/cacic2/ws/get_config.php</b> on line <b>228"
//
//	if (!function_exists('eh_excecao'))
//		{

		/* Essa funcao retorna 1 caso $te_node_address seja uma excecao para a ação id_acao e 0 caso não seja. */
		function eh_excecao($id_acao, $te_node_address) 
			{
			$query_exc = '	SELECT 	count(*) as num_registros
							FROM 	acoes_excecoes
							WHERE 	id_acao = "'.$id_acao.'" AND 
									te_node_address = "'.$te_node_address.'"';
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
	conecta_bd_cacic();
	$result_monitorado 	= mysql_query($query_monitorado);
	$v_tripa_perfis1 = explode('#',$te_tripa_perfis);
	$v_retorno_MONITORADOS = '';			

	/* Seleciona os dados de coleta_forcada específicos para este computador, que foram setados
		via detalhes/Opções Administrativas */
	
	$query_coleta_forcada = '	SELECT 		dt_hr_coleta_forcada_estacao,te_nomes_curtos_modulos
								FROM 		computadores
								WHERE 		te_node_address = "'.$te_node_address.'" AND 
											id_so = "'.$id_so.'"';
	conecta_bd_cacic();
	$result_coleta_forcada 	= mysql_query($query_coleta_forcada);
	$te_tripa_coleta = mysql_fetch_array($result_coleta_forcada);
	$v_tripa_coleta = explode('#',$te_tripa_coleta['te_nomes_curtos_modulos']);

	
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
				WHERE 		(acoes_redes.cs_situacao = "T" OR 
				 			 acoes_redes.cs_situacao = "S") AND 
							 acoes_redes.id_ip_rede = "'.$id_ip_rede.'" AND
							 acoes_redes.id_local = '.$v_dados_rede['id_local'].' AND 
							 acoes.id_acao = acoes_redes.id_acao AND
							 acoes_so.id_acao = acoes.id_acao AND 
							 acoes_so.id_so = "'.$id_so.'" AND
							 acoes_so.id_local = '.$v_dados_rede['id_local'];
	conecta_bd_cacic();
	$result = mysql_query($query);

	while ($campos = mysql_fetch_array($result))
		{ 
		$id_acao = $campos['id_acao'];
		if (eh_excecao($id_acao, $te_node_address) == 0)
			{ 			
			$retorno_xml_values .= '<' . $id_acao . '>'.EnCrypt($key,$iv,'S',$v_cs_cipher,$v_cs_compress,$v_compress_level).'</' . $id_acao . '>';

			if ($campos['dt_hr_coleta_forcada'] || $te_tripa_coleta['dt_hr_coleta_forcada_estacao'])
				{
				$v_dt_hr_coleta_forcada = $campos["dt_hr_coleta_forcada"];
				if (count($v_tripa_coleta) > 0 and
					$v_dt_hr_coleta_forcada < $te_tripa_coleta['dt_hr_coleta_forcada_estacao'] and
					in_array($campos["te_nome_curto_modulo"],$v_tripa_coleta))
					{
					$v_dt_hr_coleta_forcada = $te_tripa_coleta['dt_hr_coleta_forcada_estacao'];
					}
				$retorno_xml_values .= '<' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>' . EnCrypt($key,$iv,$v_dt_hr_coleta_forcada,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</' . 'DT_HR_COLETA_FORCADA_' . $campos["te_nome_curto_modulo"] . '>';
				}

			if (trim($id_acao) == "cs_coleta_monitorado" && mysql_num_rows($result_monitorado))
				{
				$v_arr_WNT = array(	'6',   // NT
									'7',   // 2K
									'8',   // XP
									'13'   // SERVER2003 	
								   ); 

				$v_arr_W9x = array(	'1',   // 95
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

					if ($v_achei==0 && ($campo_monitorado['id_so'] == 0 || $campo_monitorado['id_so'] == $id_so))
						{
						if ($v_retorno_MONITORADOS <> '') $v_retorno_MONITORADOS .= '#';
	
						$v_te_ide_licenca = trim($campo_monitorado['te_ide_licenca']);					
						if ($campo_monitorado['cs_ide_licenca']=='0') 	$v_te_ide_licenca = '';					
						
						$v_retorno_MONITORADOS .= $campo_monitorado['id_aplicativo']	.	','.
										  $campo_monitorado['dt_atualizacao']	.	','.
										  $campo_monitorado['cs_ide_licenca'] 	. 	','.
										  $v_te_ide_licenca						.	',';
	
						if (in_array($id_so,$v_arr_WNT)) 
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
	//                      Linha comentada devido ao fato da possibilidade de se informar o caminho completo na descrição do arquivo a ser pesquisado
	//                      Foi mantido o transporte de "." para futuras implementações
							$v_retorno_MONITORADOS .=   '.'                    					.','.						
												$campo_monitorado['cs_car_inst_wnt']	.','.
												$v_te_car_inst_wnt                 		.','.
												$campo_monitorado['cs_car_ver_wnt']		.','.	
												$v_te_car_ver_wnt               		.','.														
												$v_te_arq_ver_eng_wnt					.','.
												$v_te_arq_ver_pat_wnt;
							}
						else
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
	//                      Linha comentada devido ao fato da possibilidade de se informar o caminho completo na descrição do arquivo a ser pesquisado
	//                      Foi mantido o transporte de "." para futuras implementações
							$v_retorno_MONITORADOS .= '.'                                     	.','.									
												$campo_monitorado['cs_car_inst_w9x']	.','.						
												$v_te_car_inst_w9x						.','.
												$campo_monitorado['cs_car_ver_w9x']		.','.	
												$v_te_car_ver_w9x						.','.														
												$v_te_arq_ver_eng_w9x					.','.
												$v_te_arq_ver_pat_w9x						;
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

				$query_modulos = '	SELECT 	*
									FROM	redes_versoes_modulos
									WHERE 	id_ip_rede = "'.$id_ip_rede.'" AND
											id_local = '.$v_dados_rede['id_local'];
			
				$result_modulos	= mysql_query($query_modulos);
			
				while ($row_modulos = mysql_fetch_array($result_modulos))
					{
					$retorno_xml_values .= '<' . 'DT_VERSAO_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>' . EnCrypt($key,$iv,$row_modulos['te_versao_modulo'],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '<' . '/DT_VERSAO_' . str_replace('.EXE','',strtoupper($row_modulos['nm_modulo'])) . '_DISPONIVEL>';
					}				

				if ($v_retorno_MONITORADOS <> '') 
					{
					$retorno_xml_values .= '<SISTEMAS_MONITORADOS_PERFIS>'.EnCrypt($key,$iv,$v_retorno_MONITORADOS,$v_cs_cipher,$v_cs_compress,$v_compress_level).'</SISTEMAS_MONITORADOS_PERFIS>';
					}
			
				// Configurações relacionadas ao comportamento do agente.
				$query = 'SELECT 	in_exibe_bandeja,
									in_exibe_erros_criticos,
									nu_exec_apos,
									nu_intervalo_exec,
									nu_intervalo_renovacao_patrim,
									te_senha_adm_agente,
									te_enderecos_mac_invalidos,
									te_janelas_excecao
						FROM 		configuracoes_locais
						WHERE		id_local = '.$v_dados_rede['id_local'];
			
				conecta_bd_cacic();										
				$result_configs = mysql_query($query);
				$campos_configs = mysql_fetch_array($result_configs);
				for ($i=0; $i < mysql_num_fields($result_configs); $i++) 
					{
					$nome_campo = mysql_field_name($result_configs, $i); 
					$retorno_xml_values .= '<' . $nome_campo . '>' . EnCrypt($key,$iv,$campos_configs[$nome_campo],$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</' . $nome_campo . '>';      
					}
				
				// Caso não haja identificador de servidor de updates informado, assumirá o nome do servidor gerente
				// PERIGO: O servidor WEB talvez não tenha FTP configurado
				if (trim($v_te_serv_updates)      == '') $v_te_serv_updates      = substr($_ENV['HOSTNAME'],0,strpos($_ENV['HOSTNAME'],'.'));
			
				// Caso não haja identificador de path no servidor de updates informado, assumirá o caminho abaixo
				if (trim($v_te_path_serv_updates) == '') $v_te_path_serv_updates = '/home/cacic/updates';
			
				$retorno_xml_values .= '<TE_SERV_CACIC>' 				  . EnCrypt($key,$iv,$v_te_serv_cacic								,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</TE_SERV_CACIC>';		
				$retorno_xml_values .= '<TE_SERV_UPDATES>'               . EnCrypt($key,$iv,$v_te_serv_updates								,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</TE_SERV_UPDATES>';		
				$retorno_xml_values .= '<NU_PORTA_SERV_UPDATES>'         . EnCrypt($key,$iv,$v_nu_porta_serv_updates						,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</NU_PORTA_SERV_UPDATES>';
				$retorno_xml_values .= '<TE_PATH_SERV_UPDATES>'          . EnCrypt($key,$iv,$v_te_path_serv_updates							,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</TE_PATH_SERV_UPDATES>';			
				$retorno_xml_values .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . EnCrypt($key,$iv,$v_dados_rede['nm_usuario_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</NM_USUARIO_LOGIN_SERV_UPDATES>';	
				$retorno_xml_values .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . EnCrypt($key,$iv,$v_dados_rede['te_senha_login_serv_updates']	,$v_cs_cipher,$v_cs_compress,$v_compress_level) . '</TE_SENHA_LOGIN_SERV_UPDATES>';							
			}	
		}
	}	



// --------------- Retorno de Classificador de CRIPTOGRAFIA --------------- //
// Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informações trafegadas 
//$v_cs_cipher = '0';

// Testes do Anderson Peterle
if ($_SERVER['REMOTE_ADDR']<>'10.71.0.58') 
	{
	// Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informações trafegadas 
	$v_cs_cipher = '0'; 
	}
else 
	{
	if ($v_cs_cipher <> '1') $v_cs_cipher --;	
	}

$retorno_xml_header .= '<cs_cipher>'.$v_cs_cipher.'</cs_cipher>';		
// ----------------------------------------------------------------------- //


// --------------- Retorno de Classificador de COMPRESSÃO --------------- //
$pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
if ($pos <> -1 && $v_cs_compress <>'1') $v_cs_compress -= 1;

// Caso o nível de compressão sera setado para 0(zero) o indicador deve retornar 0(zero)
if ($v_compress_level == '0') $v_cs_compress = '0';

// Comente/Descomente a linha abaixo para habilitar/desabilitar a compactação de informações trafegadas 
$v_cs_compress = '0'; 
$retorno_xml_header .= '<cs_compress>'.$v_cs_compress.'</cs_compress>';
// ---------------------------------------------------------------------- //

$retorno_xml = $retorno_xml_header . $retorno_xml_values . "</CONFIGS>";  
echo $retorno_xml;	  
?>
