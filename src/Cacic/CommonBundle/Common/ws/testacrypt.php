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
*/
//echo 'Início...<br>';
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