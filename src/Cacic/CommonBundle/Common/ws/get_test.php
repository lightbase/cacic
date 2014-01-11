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

 Objetivo:
 ---------
 Esse script tem como objetivo responder a uma solicitação de teste de comunicação.
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