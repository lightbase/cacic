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
 Esse script tem como objetivo responder a uma solicita��o de teste de comunica��o.
*/

require_once('../include/library.php');

$retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values	 = '';

// Esta condi��o responde TRUE para o teste de comunica��o efetuado pelo chkCACIC
if (trim($_POST['in_chkcacic'])=='chkcacic_GetTest')
	$retorno_xml_values .= '<STATUS>OK</STATUS>';

$retorno_xml = $retorno_xml_header . $retorno_xml_values;  

echo $retorno_xml;	  
?>