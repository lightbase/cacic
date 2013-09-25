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

 Objetivo:
 ---------
 Esse script tem como objetivo responder a uma solicita��o de teste de comunica��o.
*/
require_once('../include/common_top.php');
	
if (file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))
	{
	$arrVersionsAndHashes = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');
	$strXML_Values .= '<INSTALLCACIC.EXE_HASH>'	. 	EnCrypt($arrVersionsAndHashes['installcacic.exe_HASH'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey,true) 	. '<' 	. 	'/INSTALLCACIC.EXE_HASH>';	
	$strXML_Values .= '<MainProgramName>'  		. 	CACIC_MAIN_PROGRAM_NAME.'.exe'																								. '<' 	. 	'/MainProgramName>';
	$strXML_Values .= '<LocalFolderName>' 		. 	CACIC_LOCAL_FOLDER_NAME																										. '<' 	. 	'/LocalFolderName>';											
	}		

require_once('../include/common_bottom.php');
?>