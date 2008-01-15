<?php
/**
 * @version $Id: index.php 2007-02-08 22:20 harpiain $
 * @package Cacic-Installer
 * @subpackage Instalador
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC-Install is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// direct access is denied
//defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

session_start();

if( ! defined( 'CACIC' ) )
    define( 'CACIC', 1 );
    
// define Path para o CACIC
$cacic_path = str_replace("instalador", '', dirname(__FILE__));
define('CACIC_PATH', $cacic_path);

/*
 * atribuiчѕes para o ambiente
 */
if( ! @include("../include/library.php") )
{
   die("Install mal definido (Install miss-defined)!");
}

/*
 * Idioma selecionado para o CACIC
 */ 
if(!empty($_POST['translate_lang']))
   $_SESSION['cacic_language'] = $_POST['translate_lang'];
elseif(!isset($_SESSION['cacic_language']))
   $_SESSION['cacic_language'] = CACIC_LANGUAGE;
   
/*
 * Idioma para os quais o CACIC estс traduzido 
 */
$_SESSION['cacic_language_available'] = $oTranslator->getLanguagesSetup();
	
/*
 * classe para instanciar a instalaчуo
 */
if( ! @include_once("classes/install.ajax.php") )
{
   die("Install mal construэ­do (Install miss-built)!");
}

InstallAjax::processAjax();

?>