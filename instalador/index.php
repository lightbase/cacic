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
	 * Verifica se o gerente está instalado e sai do instalador caso esteja
	 */
	/*
	if( is_readable('../include/config.php') )
	{
	   header("Location: ..");
	   die("CACIC já instalado (CACIC already installed)!");
	}*/
	
	/*
	 * atribuições para o ambiente
	 */
	if( ! @include("../include/define.php") )
	{
	   die("Install mal definido (Install miss-defined)!");
	}
	
	/*
	 * classe para instanciar a instalação
	 */
	if( ! @include("classes/install.php") )
	{
	   die("Install mal construído (Install miss-built)!");
	}

   if(!@include_once( TRANSLATOR_PATH.'/Translator.php'))
     die ("<h1>There is a trouble with phpTranslator package. It isn't found.</h1>");

   // exemplo de uso do tradutor
   define('CACIC_LANGUAGE', 'pt-br');
   define('CACIC_LANGUAGE_STANDARD', 'en-us');
   $_objTranslator = new Translator( CACIC_LANGUAGE, CACIC_PATH."/language/", CACIC_LANGUAGE_STANDARD );
   $_objTranslator->setURLPath(TRANSLATOR_PATH_URL);
   $_objTranslator->initStdLanguages();
   $_objTranslator->setLangFilesInSubDirs(true);
   //echo $_objTranslator->getText('kciq_mnt_tradutor');
   // FIM de exemplo de uso do tradutor
   
	/**
	 * Prove a instanciação da Instalação pela WEB
	 */
	 $objInstall = new Install();
	 
	 if(isset($_POST['step']))
	   $objInstall->navBar($_POST['step']);
	 else
	   $objInstall->navBar();
	   
	 $objInstall->end();
	 
?>