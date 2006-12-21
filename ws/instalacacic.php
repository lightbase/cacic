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
if (trim($_POST['in_instalacacic'])=='instala' || trim($_POST['in_instalacacic'])=='comunica_insucesso')
	{
	require_once('../include/library.php');

	$v_id_ip_estacao = $_SERVER["REMOTE_ADDR"];
	if (trim($_POST['id_ip']) <> '0.0.0.0' && trim($_POST['id_ip']) <> '') $v_id_ip_estacao = trim($_POST['id_ip']);

	if (trim($_POST['in_instalacacic'])=='instala')
		{	
		// Verifica se o IP da estação está na faixa do IP_REDE + SUBNET_MASK
		function IP_Match($subnet_mask, $ip_estacao, $ip_rede) 
			{
		   	$longo_rede = ip2long($ip_rede);
	   		$nm = ip2long($subnet_mask);
	   		$nw = ($longo_rede & $nm);
	   		$bc = $nw | (~$nm);
			$longo_estacao = ip2long($ip_estacao);
			if (($longo_estacao >= ip2long($nw + 1)) && ($longo_estacao <= ip2long($bc - 1))) return true;
			}
	
		conecta_bd_cacic();	
		
		$query_redes  = "SELECT id_ip_rede,
								te_mascara_rede
						 FROM	redes";
		$result_redes = mysql_query($query_redes);
		
		$v_id_ip_rede = '';

		// Percorro cada ID_IP_REDE + TE_MASCARA_REDE para checar se o IP da estação está na faixa de IPs
		while ($v_dados_redes = mysql_fetch_array($result_redes))
			{		
			if (IP_Match($v_dados_redes['te_mascara_rede'],$v_id_ip_estacao,$v_dados_redes['id_ip_rede']))
				{
				$v_id_ip_rede = $v_dados_redes['id_ip_rede'];
				}
			}
	
		$query_ver = "	SELECT 	te_serv_updates,
								nu_porta_serv_updates, 
								te_path_serv_updates, 
								nm_usuario_login_serv_updates,
								te_senha_login_serv_updates
						FROM	redes
						WHERE 	id_ip_rede = '$v_id_ip_rede'";
		$result_ver = mysql_query($query_ver);

		if (!$v_dados_rede = mysql_fetch_array($result_ver))
			{		
			$query_ver = "	SELECT 	redes.te_serv_updates,
									redes.nu_porta_serv_updates, 
									redes.te_path_serv_updates, 
									redes.nm_usuario_login_serv_updates,
									redes.te_senha_login_serv_updates
							FROM	redes,configuracoes conf
							WHERE 	conf.te_serv_updates_padrao = redes.te_serv_updates and
									trim(redes.nu_porta_serv_updates) <> '' and
									trim(redes.nm_usuario_login_serv_updates) <> '' and
									trim(redes.te_senha_login_serv_updates) <> '' 
							LIMIT 1";
			$result_ver 	= mysql_query($query_ver);
			$v_dados_rede	= mysql_fetch_array($result_ver);
			}	

		$retorno_xml  = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><CONFIGS>';			

		// Operações para grupos por subredes	
		// O Post te_fila_ftp conterá número "0" ou o IP da estação...
		$v_te_fila_ftp = '0';	
		if (trim($_POST['te_fila_ftp'])=='1')
			{
			$retorno_xml .= '<TE_SERV_UPDATES>'               . $v_dados_rede['te_serv_updates']               . '</TE_SERV_UPDATES>';		
			$retorno_xml .= '<NU_PORTA_SERV_UPDATES>'         . $v_dados_rede['nu_porta_serv_updates']         . '</NU_PORTA_SERV_UPDATES>';
			$retorno_xml .= '<TE_PATH_SERV_UPDATES>'          . $v_dados_rede['te_path_serv_updates']          . '</TE_PATH_SERV_UPDATES>';			
			$retorno_xml .= '<NM_USUARIO_LOGIN_SERV_UPDATES>' . $v_dados_rede['nm_usuario_login_serv_updates'] . '</NM_USUARIO_LOGIN_SERV_UPDATES>';	
			$retorno_xml .= '<TE_SENHA_LOGIN_SERV_UPDATES>'   . $v_dados_rede['te_senha_login_serv_updates']   . '</TE_SENHA_LOGIN_SERV_UPDATES>';						
			
			// TimeOut definido para 5 minutos, ou seja, tempo máximo para 5 estações efetuarem FTP dos módulos necessários
			// 1 minuto = 60000 milisegundos
			// 5 * 60000 milisegundos = 5 minutos (TimeOut)
			$v_timeout = time() - (5 * 60000);
				
			// Exclusão por timeout
			$query_del = "DELETE 
						  FROM  redes_grupos_ftp
						  WHERE id_ip_rede = '$v_id_ip_rede'
								and nu_hora_inicio < ".$v_timeout." or id_ip_estacao = '".trim($_POST['id_ip_estacao'])."'";
			$result_del = mysql_query($query_del);	
			
			// Contagem por subrede
			$query_grupo = "SELECT count(*) as total_estacoes
						   FROM redes_grupos_ftp
						   WHERE id_ip_rede = '$v_id_ip_rede' FOR UPDATE";
			$result_grupo = mysql_query($query_grupo);
			$total = mysql_fetch_array($result_grupo);
			
			// Caso o grupo de 5 estações esteja cheio, retorno o tempo de 5 minutos para espera e nova tentativa...
			// Posteriormente, poderemos calcular uma média para o intervalo, em função do link da subrede
			if ($total['total_estacoes'] > 0) // Acima de 4... 
				{
				$v_te_fila_ftp = '1'; // 5 minutos de espera
				}
			else
				{
				$queryINS  = "INSERT into redes_grupos_ftp(id_ip_rede,id_ip_estacao,nu_hora_inicio) 
							  VALUES                     ('$v_id_ip_rede','".trim($_POST['id_ip_estacao'])."',".time().")";
				$resultINS = mysql_query($queryINS);			
				}
			$retorno_xml .= '<TE_FILA_FTP>'.$v_te_fila_ftp.'</TE_FILA_FTP>';													
			}		
		elseif (trim($_POST['te_fila_ftp'])=='2' || trim($_POST['te_fila_ftp'])=='9')
			{
			$query_del = "DELETE 
						  FROM  redes_grupos_ftp
						  WHERE id_ip_rede = '$v_id_ip_rede'
								and id_ip_estacao = '".trim($_POST['id_ip_estacao'])."'";
			$result_del = mysql_query($query_del);			
			}
	
		$retorno_xml .= '</CONFIGS>';				
		echo $retorno_xml;	  			
		}
	else
		{
		conecta_bd_cacic();		
		$queryINS = "INSERT INTO insucessos_instalacacic (id_ip, id_so, id_usuario, dt_datahora, nu_insucesso_arquivos, nu_insucesso_chaves)
					 VALUES ('" . $v_id_ip_estacao . "', '" . $_POST['id_so'] . "','" . $_POST['id_usuario'] . "', NOW(),".$_POST['nu_insucesso_arquivos'].",".$_POST['nu_insucesso_chaves'].")";
		$result = mysql_query($queryINS);
		}			
	}		
?>	