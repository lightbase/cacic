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
// Definição do nível de compressão (Default=máximo)
//$v_compress_level = '9';
$v_compress_level = '0';
 
require_once('../include/library.php');

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress);

$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher, $v_cs_compress); 
$id_so_new         			= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher, $v_cs_compress); 
$te_so           			= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher, $v_cs_compress); 
$id_ip_rede     			= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher, $v_cs_compress);
$te_ip 						= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher, $v_cs_compress); 
$te_nome_computador			= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher, $v_cs_compress); 
$te_workgroup 				= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher, $v_cs_compress); 

conecta_bd_cacic();

/* Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
o computador deste agente no BD, caso ainda não esteja inserido. */
if ($te_node_address <> '')
	{
	$id_so = inclui_computador_caso_nao_exista(	$te_node_address, 
												$id_so_new, 
												$te_so, 										
												$id_ip_rede, 
												$te_ip, 
												$te_nome_computador,
												$te_workgroup);									
	$v_te_workgroup = $te_workgroup;
	if ($v_te_workgroup == '')
		{
		$v_array_te_dominio_windows	= explode('\\',DeCrypt($key,$iv,$_POST['te_dominio_windows'],$v_cs_cipher, $v_cs_compress));
		$v_te_workgroup = $v_array_te_dominio_windows[0];
		}
	
	$query = "INSERT INTO historico_tcp_ip
										(te_node_address,
											id_so,
											te_nome_computador,
											dt_hr_alteracao,
											te_ip,
											te_mascara,
											id_ip_rede,
											te_gateway,
											te_serv_dhcp,
											te_nome_host,
											te_dominio_dns,
											te_dominio_windows,										
											te_dns_primario,
											te_dns_secundario,
											te_wins_primario,
											te_wins_secundario, 
											te_workgroup,
											te_origem_mac)
							VALUES ('" . $te_node_address . "', '" .
															$id_so . "', '" .
															$te_nome_computador . "', 
															NOW(), '" .
															$te_ip . "', '" .
															DeCrypt($key,$iv,$_POST['te_mascara']			,$v_cs_cipher, $v_cs_compress) . "', '" .
															$id_ip_rede  . "', '" .
															DeCrypt($key,$iv,$_POST['te_gateway']			,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_serv_dhcp']			,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_nome_host']			,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_dominio_windows']	,$v_cs_cipher, $v_cs_compress) . "', '" .														
															DeCrypt($key,$iv,$_POST['te_dns_primario']		,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_dns_secundario']	,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_wins_primario']		,$v_cs_cipher, $v_cs_compress) . "', '" .
															DeCrypt($key,$iv,$_POST['te_wins_secundario']	,$v_cs_cipher, $v_cs_compress) . "', '" .
															$v_te_workgroup . "', '" .
															DeCrypt($key,$iv,$_POST['te_origem_mac'],$v_cs_cipher, $v_cs_compress) . "')";
	
	$result = mysql_query($query);
	
	//echo $query;
	//exit;
	
	// Lembre-se de que o computador já existe. Ele é criado durante a obtenção das configurações, no arquivo get_config.php.
	$query = "	UPDATE 	computadores 
				SET		te_ip              	= '" . $te_ip . "', 
						te_nome_computador 	= '" . $te_nome_computador . "', 
						te_mascara         	= '" . DeCrypt($key,$iv,$_POST['te_mascara']			,$v_cs_cipher, $v_cs_compress) . "', 
						id_ip_rede         	= '" . $id_ip_rede . "',
						te_gateway         	= '" . DeCrypt($key,$iv,$_POST['te_gateway']			,$v_cs_cipher, $v_cs_compress) . "',
						te_serv_dhcp       	= '" . DeCrypt($key,$iv,$_POST['te_serv_dhcp']			,$v_cs_cipher, $v_cs_compress) . "',
						te_nome_host       	= '" . DeCrypt($key,$iv,$_POST['te_nome_host']			,$v_cs_cipher, $v_cs_compress) . "',
						te_dominio_windows 	= '" . DeCrypt($key,$iv,$_POST['te_dominio_windows']	,$v_cs_cipher, $v_cs_compress) . "',														
						te_dominio_dns     	= '" . DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher, $v_cs_compress) . "',
						te_dns_primario    	= '" . DeCrypt($key,$iv,$_POST['te_dns_primario']		,$v_cs_cipher, $v_cs_compress) . "',
						te_dns_secundario  	= '" . DeCrypt($key,$iv,$_POST['te_dns_secundario']		,$v_cs_cipher, $v_cs_compress) . "',
						te_wins_primario   	= '" . DeCrypt($key,$iv,$_POST['te_wins_primario']		,$v_cs_cipher, $v_cs_compress) . "',
						te_wins_secundario 	= '" . DeCrypt($key,$iv,$_POST['te_wins_secundario']	,$v_cs_cipher, $v_cs_compress) .  "',
						te_workgroup 	   	= '" . $v_te_workgroup .  "',														
						te_origem_mac	 	= '" . DeCrypt($key,$iv,$_POST['te_origem_mac']			,$v_cs_cipher, $v_cs_compress) .  "'
				WHERE 	te_node_address  	= '" . $te_node_address . "' and
						id_so              	= '" . $id_so . "'";
	$result = mysql_query($query);
	
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inválida</STATUS>';					

?>