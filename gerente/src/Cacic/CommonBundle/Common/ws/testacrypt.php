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
//echo 'Início...<br>';
require_once('../include/library.php');

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_REQUEST['cs_cipher'])   <> ''?trim($_REQUEST['cs_cipher'])   : '4');

// Definição do nível de compressão (Default = 1 => mínimo)
//$v_compress_level = 1;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 

$retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values	 = '<STATUS>OK</STATUS><CS_OPERACAO>'.trim($_REQUEST['cs_operacao']).'</CS_OPERACAO>';

if (trim($_REQUEST['cs_operacao']) == 'TestaCrypt')
	{
//echo 'Meio 1...<br>';	
	$retorno_xml_values .= '<CipheredTextRecepted>'.trim($_REQUEST['te_CipheredText']).'</CipheredTextRecepted>';	
//echo 'Meio 2... KEY='.$key.' IV='.$iv.'<br>';		
	$retorno_xml_values .= '<cs_Cipher>'.$v_cs_cipher.'</cs_Cipher>';				
//echo 'Meio 3...<br>';		
	$retorno_xml_values .= '<IVServer>'.$iv.'</IVServer>';		
//echo 'Meio 4...<br>';		
	$retorno_xml_values .= '<CipherKeyServer>'.$key.'</CipherKeyServer>';			
//echo 'Meio 5...<br>';		
	$v_UnCipheredText = trim(@DeCrypt($key,$iv,$_REQUEST['te_CipheredText'],$v_cs_cipher,$v_cs_compress));
	$retorno_xml_values .= '<UnCipheredText>'.$v_UnCipheredText.'</UnCipheredText>';		
//echo 'Meio 6...<br>';		
	}	
$retorno_xml = $retorno_xml_header . $retorno_xml_values;  
//echo 'Fim...<br>';
echo $retorno_xml;	  
?>