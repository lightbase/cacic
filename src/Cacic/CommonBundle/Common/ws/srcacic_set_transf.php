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
 Esse script tem como objetivo receber as informações referentes às transferências de arquivos efetuadas durante a sessão/conexão de suporte remoto.
*/
require_once('../include/library.php');

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level   = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						  // Há necessidade de testes para Análise de Viabilidade Técnica 

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values = '';

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher		= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress		= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$v_cs_cipher		= '1';

// Algumas estações enviarão uma string para extensão de bloco
$strPaddingKey  	= '';

// Autenticação da Estação Visitada
$te_node_address   	= DeCrypt($key,$iv,$_POST['te_node_address']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so             	= DeCrypt($key,$iv,$_POST['te_so']							,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_palavra_chave  	= DeCrypt($key,$iv,urldecode($_POST['te_palavra_chave'])	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_conexao 		= DeCrypt($key,$iv,$_POST['id_conexao']						,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 								
			
$arrComputadores 	= getValores('computadores', 'te_palavra_chave,id_ip_rede' , 'te_node_address = "'.$te_node_address.'" and id_so = '.$arrSO['id_so']);
$arrRedes 			= getValores('redes'       , 'id_local'   				   , 'id_ip_rede= "'.$arrComputadores['id_ip_rede'].'"'); 
$strTePalavraChave	= $arrComputadores['te_palavra_chave'];

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores de autenticação
if ($te_palavra_chave == $strTePalavraChave)
	{
	conecta_bd_cacic();	

	$query_INSERT = "INSERT INTO srcacic_transfs (	dt_systemtime,
													nu_duracao,
													te_path_origem,
													te_path_destino,
													nm_arquivo,
													nu_tamanho_arquivo,
													cs_status,
													cs_operacao,
													id_conexao)
					 VALUES						  '".DeCrypt($key,$iv,$_POST['dt_systemtime'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',
												   ".DeCrypt($key,$iv,$_POST['nu_duracao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey).",
												  '".DeCrypt($key,$iv,$_POST['te_path_origem'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',								
												  '".DeCrypt($key,$iv,$_POST['te_path_destino'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																
												  '".DeCrypt($key,$iv,$_POST['nm_arquivo'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																
												   ".DeCrypt($key,$iv,$_POST['nu_tamanho_arquivo'],$v_cs_cipher,$v_cs_compress,$strPaddingKey).",
												  '".DeCrypt($key,$iv,$_POST['cs_status'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',																								
												  '".DeCrypt($key,$iv,$_POST['cs_operacao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."',"
												  . $id_conexao;
	$result_INSERT = mysql_query($query_INSERT);
	$retorno_xml_values .= '<OK>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</OK>';		
	}

if ($retorno_xml_values <> '')
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;
else
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'SetTransf ERRO! '.ldap_error($ldap),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';

$retorno_xml = $retorno_xml_header . $retorno_xml_values; 
echo $retorno_xml;	
?>