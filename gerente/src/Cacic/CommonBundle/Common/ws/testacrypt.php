<?php 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//echo 'In�cio...<br>';
require_once('../include/common_top.php');

if (trim($_REQUEST['cs_operacao']) == 'TestaCrypt')
	{
	$strXML_Values .= '<CipheredTextRecepted>'.trim($_REQUEST['te_CipheredText']).'</CipheredTextRecepted>';	
	$strXML_Values .= '<cs_Cipher>'.$v_cs_cipher.'</cs_Cipher>';				
	$strXML_Values .= '<IVServer>'.$iv.'</IVServer>';		
	$strXML_Values .= '<CipherKeyServer>'.$key.'</CipherKeyServer>';			

	$v_UnCipheredText = trim(@DeCrypt($_REQUEST['te_CipheredText'],$v_cs_cipher,$v_cs_compress));
	$strXML_Values .= '<UnCipheredText>'.$v_UnCipheredText.'</UnCipheredText>';		
	}	

$strXML_Values	 .= '<STATUS>OK</STATUS><CS_OPERACAO>'.trim($_REQUEST['cs_operacao']).'</CS_OPERACAO>';
require_once('../include/common_bottom.php');
?>