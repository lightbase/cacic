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
require_once('../include/library.php');

/*
Bloco para DEBUG - COMENTAR AO FIM DO USO!!!

GravaTESTES('=================== DEBUG ==================');
GravaTESTES('Common_Top');
GravaTESTES('============================================');

foreach($HTTP_POST_VARS as $i => $v)
        GravaTESTES('Index: '.$i.' Value: '.$v);
/*
foreach($HTTP_GET_VARS as $i => $v)
        GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('=============================================');
*/

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level 				 = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
									   // Há necessidade de testes para Análise de Viabilidade Técnica 
									   
// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher					 = (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress					 = (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
// Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
// A versão inicial do agente em Python exige esse complemento na chave...
$strPaddingKey 					 = ($_POST['padding_key'] ? $_POST['padding_key'] : '');
$boolAgenteLinux 				 = (trim($_POST['AgenteLinux']) <> ''?true:false);

// Autenticação da chamada:
autentica_agente($strPaddingKey);

$strNetworkAdapterConfiguration  = DeCrypt($_POST['NetworkAdapterConfiguration'], $v_cs_cipher,$v_cs_compress,$strPaddingKey);
$strComputerSystem  			 = DeCrypt($_POST['ComputerSystem']				, $v_cs_cipher,$v_cs_compress,$strPaddingKey);
$strOperatingSystem  			 = DeCrypt($_POST['OperatingSystem']			, $v_cs_cipher,$v_cs_compress,$strPaddingKey);

$arrDadosComputador 			 = getDadosComputador(getValueFromTags('MACAddress', $strNetworkAdapterConfiguration), 
													  $_POST['te_so'],												 
													  getValueFromTags('UserName'  , $strComputerSystem));
										 
$arrDadosRede 					 = getDadosRede($arrDadosComputador[0]['id_rede']);

$strTePalavraChave				 = '';
if ($_POST['te_palavra_chave'])
	$strTePalavraChave = DeCrypt($_POST['te_palavra_chave'], $v_cs_cipher,$v_cs_compress,$strPaddingKey);

// --------------- Retorno de Classificador de CRIPTOGRAFIA --------------------------------------------- //
if ($v_cs_cipher <> '1') $v_cs_cipher --;
// Comente/Descomente a linha abaixo para habilitar/desabilitar a criptografia de informações trafegadas 
//$v_cs_cipher = '0'; 
// ----------------------------------------------------------------------------------------------------- //

// --------------- Retorno de Classificador de COMPRESSÃO ---------------------------------------------- //
$pos = strpos($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate");
if ($pos <> -1 && $v_cs_compress <>'1') $v_cs_compress -= 1;

// Caso o nível de compressão sera setado para 0(zero) o indicador deve retornar 0(zero)
if ($v_compress_level == '0') $v_cs_compress = '0';

// Comente/Descomente a linha abaixo para habilitar/desabilitar a compactação de informações trafegadas 
//$v_cs_compress = '0'; 
// ----------------------------------------------------------------------------------------------------- //

$strXML_Begin  	 = 	'<?php xml version="1.0" encoding="iso-8859-1" ?><CONFIGS>';
$strXML_Values 	 = 	'';

$strTeDebugging	 = 	(getValueFromTags('DateToDebugging',$arrDadosComputador[0]['te_debugging'])  == date("Ymd") ? $arrDadosComputador[0]['te_debugging']  	:
					(getValueFromTags('DateToDebugging',$arrDadosRede[0]['te_debugging_local'])  == date("Ymd") ? $arrDadosRede[0]['te_debugging_local']  	:
					(getValueFromTags('DateToDebugging',$arrDadosRede[0]['te_debugging_subnet']) == date("Ymd") ? $arrDadosRede[0]['te_debugging_subnet'] 	: 	'')));
					
$strXML_Values  .= 	($strTeDebugging ? '<TeDebugging>' 																										: 	'');
$strXML_Values  .= 	($strTeDebugging ? getValueFromTags('DetailsToDebugging',$strTeDebugging)																:	'');
$strXML_Values  .= 	($strTeDebugging ? '</TeDebugging>' 																									: 	'');

$strXML_Values  .= 	'<IdComputador>' 		 . 	$arrDadosComputador[0]['id_computador']	. '<'	.	'/IdComputador>';
$strXML_Values  .= 	'<WebManagerAddress>'     .	$arrDadosRede[0]['te_serv_cacic']		. '<' 	. 	'/WebManagerAddress>';			
$strXML_Values  .= 	'<WebServicesFolderName>' . 	CACIC_WEB_SERVICES_FOLDER_NAME			. '<' 	. 	'/WebServicesFolderName>';			

$strXML_End 	 = 	'<cs_compress>'			 . 	$v_cs_compress							. '<' 	.	'/cs_compress>';
$strXML_End 	.= 	'<cs_cipher>'			 . 	$v_cs_cipher							. '<'	.	'/cs_cipher>';		
$strXML_End		.= 	'</CONFIGS>';
