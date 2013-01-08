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
*/
GravaTESTES('=================== DEBUG ==================');
GravaTESTES('PHP_SELF: '    . $_SERVER['PHP_SELF']);
GravaTESTES('REQUEST_URI: ' . $_SERVER['REQUEST_URI']);
GravaTESTES('SCRIPT_NAME: ' . $_SERVER['SCRIPT_NAME']); 
GravaTESTES('Entrada 1 : POST');
foreach($HTTP_POST_VARS as $i => $v)
        GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('Entrada 1.1 : GET');
foreach($HTTP_GET_VARS as $i => $v)
        GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('=============================================');
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
$boolAgenteLinux 				 = (trim(DeCrypt($key,$iv,$_POST['AgenteLinux'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) <> ''?true:false);

// Autenticação da chamada:
autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$arrDadosComputador 			 = getDadosComputador(DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
											 		  DeCrypt($key,$iv,$_POST['te_so']		   		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
										 			  DeCrypt($key,$iv,$_POST['te_ip_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
										 			  DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
										 			  DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
										 			  DeCrypt($key,$iv,$_POST['te_dominio_windows']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey),										 
										 			  DeCrypt($key,$iv,$_POST['te_user_name']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey),
										 			  DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
										 
$arrDadosRede 					 = getDadosRede($arrDadosComputador['id_rede']);
$strTePalavraChave				 = DeCrypt($key,$iv,$_POST['te_palavra_chave'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);				 

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

$strXML_Begin  	 = '<?php xml version="1.0" encoding="iso-8859-1" ?><CONFIGS>';

$strXML_Values 	 = '<dt_debug_station>' 	 . EnCrypt($key,$iv,$arrDadosComputador['dt_debug']		, $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' 	.	'/dt_debug_station>';	
$strXML_Values  .= '<dt_debug_subnet>'  	 . EnCrypt($key,$iv,$arrDadosRede['dt_debug_subnet']	, $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' 	. 	'/dt_debug_subnet>';	
$strXML_Values  .= '<dt_debug_local>'   	 . EnCrypt($key,$iv,$arrDadosRede['dt_debug_local']		, $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<' 	. 	'/dt_debug_local>';		
$strXML_Values  .= '<WebServicesFolderName>' . EnCrypt($key,$iv,CACIC_WEB_SERVICES_FOLDER_NAME		, $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	. '<' 	. 	'/WebServicesFolderName>';			
$strXML_Values  .= '<IdComputador>' 		 . EnCrypt($key,$iv,$arrDadosComputador['id_computador'], $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '<'	.	'/IdComputador>';

$strXML_End 	 = '<cs_compress>'			 . $v_cs_compress	. '</cs_compress>';
$strXML_End 	.= '<cs_cipher>'			 . $v_cs_cipher		. '</cs_cipher>';		
$strXML_End		.= '</CONFIGS>';
