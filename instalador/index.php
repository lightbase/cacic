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

	/*
	 * Obtem o caminho da aplicação gerente
	 */
	$path_aplicacao = str_replace("instalador", '', dirname(__FILE__));
	    
	/*
	 * atribuições para o ambiente
	 */
	if( ! @include("..".DIRECTORY_SEPARATOR."include".DIRECTORY_SEPARATOR."library.php") )
	{
	   die("Instalador mal definido (Installer miss-defined)!");
	}
	
	/*
	 * classe para instanciar a instalação
	 */
	if( ! @include("classes".CACIC_DS."install.php") )
	{
	   die("Install mal construído (Install miss-built)!");
	}

	if(!@include_once( TRANSLATOR_PATH.CACIC_DS.'Translator.php'))
	  die ("<h1>There is a trouble with phpTranslator package. It isn't found.</h1>");
	  
    /*
     * Idioma selecionado para o CACIC
     */ 
	if(!empty($_POST['translate_lang']))
	   $_SESSION['cacic_language'] = $_POST['translate_lang'];
	elseif(!isset($_SESSION['cacic_language']))
	   $_SESSION['cacic_language'] = CACIC_LANGUAGE;
	   
	/*
	 * Idioma para os quais o CACIC está traduzido 
	 */
	$_SESSION['cacic_language_available'] = $oTranslator->getLanguagesSetup();
	
	/*
	 * Inicia tradução para o idioma selecionado
	 */
    $oTranslator->setLangTgt($_SESSION['cacic_language']);
    $oTranslator->initStdLanguages();
   
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
