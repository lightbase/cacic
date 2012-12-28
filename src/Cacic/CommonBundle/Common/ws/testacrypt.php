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
//echo 'In�cio...<br>';
require_once('../include/library.php');

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher	= (trim($_REQUEST['cs_cipher'])   <> ''?trim($_REQUEST['cs_cipher'])   : '4');

// Defini��o do n�vel de compress�o (Default = 1 => m�nimo)
//$v_compress_level = 1;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 

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