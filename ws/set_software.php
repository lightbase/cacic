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

$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address'],$v_cs_cipher,$v_cs_compress); 
$id_so           			= DeCrypt($key,$iv,$_POST['id_so']			,$v_cs_cipher,$v_cs_compress); 

conecta_bd_cacic();

// Verifico se o computador em questão já foi inserido anteriormente, e se não foi, insiro.
$query = "SELECT count(*) as num_registros
          FROM versoes_softwares
										WHERE te_node_address = '" . $te_node_address . "'
										AND id_so = '" . $id_so . "'";
$result = mysql_query($query);
if (mysql_result($result, 0, "num_registros") == 0) {
					$query = "INSERT INTO versoes_softwares
															(te_node_address, id_so)
															VALUES ('" . $te_node_address . "', '" . $id_so . "'  )";
					$result = mysql_query($query);
} 

$query = "UPDATE versoes_softwares 
 									SET	te_versao_bde            = '" . DeCrypt($key,$iv,$_POST['te_versao_bde']			,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_dao            = '" . DeCrypt($key,$iv,$_POST['te_versao_dao']			,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_ado            = '" . DeCrypt($key,$iv,$_POST['te_versao_ado']			,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_odbc           = '" . DeCrypt($key,$iv,$_POST['te_versao_odbc']			,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_directx        = '" . DeCrypt($key,$iv,$_POST['te_versao_directx']		,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_acrobat_reader = '" . DeCrypt($key,$iv,$_POST['te_versao_acrobat_reader']	,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_ie             = '" . DeCrypt($key,$iv,$_POST['te_versao_ie']				,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_mozilla        = '" . DeCrypt($key,$iv,$_POST['te_versao_mozilla']		,$v_cs_cipher,$v_cs_compress) . "', 
										te_versao_jre            = '" . DeCrypt($key,$iv,$_POST['te_versao_jre']			,$v_cs_cipher,$v_cs_compress) . "' 
	  							WHERE 	te_node_address    		 = '" . $te_node_address . "' and
										id_so                	 = '" . $id_so . "'";
$result = mysql_query($query);


$v_tripa_inventariados = str_replace("&quot;","'",DeCrypt($key,$iv,$_POST['te_inventario_softwares'],$v_cs_cipher,$v_cs_compress));
$v_tripa_inventariados = str_replace("&apos;","^",$v_tripa_inventariados);
if ($v_tripa_inventariados<>'')
	{
	$queryDEL = "DELETE FROM softwares_inventariados_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                  
	$result = mysql_query($queryDEL);									
	
	$v_array_te_inventario_softwares = explode('#',$v_tripa_inventariados);	

	$query_inv = "SELECT *
				  FROM softwares_inventariados";
	$result_inv = mysql_query($query_inv );

	$v_array_te_softwares_inventariados = array ();
	while ($v_reg_inv = mysql_fetch_array($result_inv))
		{	
		array_push($v_array_te_softwares_inventariados,$v_reg_inv['id_software_inventariado']);
		array_push($v_array_te_softwares_inventariados,trim($v_reg_inv['nm_software_inventariado']));		
		}	
	for ($v1=0; $v1 < count($v_array_te_inventario_softwares)-1; $v1 ++)
		{
		$v_posicao = array_search(trim($v_array_te_inventario_softwares[$v1]), $v_array_te_softwares_inventariados);
		if ($v_posicao)
			{
			$v_achei = $v_array_te_softwares_inventariados[$v_posicao-1];
			}
		else
			{			
			$query = "INSERT INTO softwares_inventariados 
								  (nm_software_inventariado)											
					  VALUES 	  ('".trim($v_array_te_inventario_softwares[$v1])."')";
			$result = mysql_query($query);

			$v_achei = mysql_insert_id()+1;
			}
			$query = "INSERT INTO softwares_inventariados_estacoes 
								  (te_node_address,
								   id_so,
								   id_software_inventariado)											
					  VALUES 	  ('".$te_node_address."',
					  			   '".$id_so."',
								   '".$v_achei."')";					                  
			$result = mysql_query($query);									
		}		
	}

$v_tripa_variaveis_coletadas = DeCrypt($key,$iv,$_POST['te_variaveis_ambiente'],$v_cs_cipher,$v_cs_compress);	
while (substr(trim($v_tripa_variaveis_coletadas),0,1)=='=')	
	{
	$v_tripa_variaveis_coletadas = substr(trim($v_tripa_variaveis_coletadas),1);
	}

if ($v_tripa_variaveis_coletadas<>'')
	{
	$queryDEL = "DELETE FROM variaveis_ambiente_estacoes 
				 WHERE 	te_node_address = '".$te_node_address."' AND
						id_so = '".$id_so."'";					                  
	$result = mysql_query($queryDEL);									
	
	$v_array_te_variaveis_coletadas = explode('#',$v_tripa_variaveis_coletadas);	

	$query_var = "SELECT *
				  FROM   variaveis_ambiente";
	$result_var = mysql_query($query_var );

	$v_array_te_variaveis_ambiente_na_base = array ();
	while ($v_reg_var = mysql_fetch_array($result_var))
		{	
		array_push($v_array_te_variaveis_ambiente_na_base,$v_reg_var['id_variavel_ambiente']);
		array_push($v_array_te_variaveis_ambiente_na_base,strtolower(trim($v_reg_var['nm_variavel_ambiente'])));		
		}	
	for ($v1=0; $v1 < count($v_array_te_variaveis_coletadas)-1; $v1 ++)
		{
		$v_array_variavel_ambiente_tmp = explode('=',$v_array_te_variaveis_coletadas[$v1]);
		if (trim($v_array_variavel_ambiente_tmp[0])<>'')
			{			
			$v_posicao = array_search(strtolower(trim($v_array_variavel_ambiente_tmp[0])), $v_array_te_variaveis_ambiente_na_base);
			if ($v_posicao)
				{
				$v_achei = $v_array_te_variaveis_ambiente_na_base[$v_posicao-1];
				}
			else
				{			
				$query = "INSERT INTO variaveis_ambiente 
									  (nm_variavel_ambiente)											
						  VALUES 	  ('".strtolower(trim($v_array_variavel_ambiente_tmp[0]))."')";
				$result = mysql_query($query);

				$v_achei = mysql_insert_id();
				}
				$query = "INSERT INTO variaveis_ambiente_estacoes 
									  (te_node_address,
									   id_so,
									   id_variavel_ambiente,
									   vl_variavel_ambiente)											
						  VALUES 	  ('".$te_node_address."',
						  			   '".$id_so."',
									   '".$v_achei."',
									   '".trim($v_array_variavel_ambiente_tmp[1])."')";					                  
				$result = mysql_query($query);									
			}
		}		
	}
	
	
echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
?>