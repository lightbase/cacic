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
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

if( ! @include("pat/patErrorManager.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patError.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patTemplate.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}

Class Template extends patTemplate {
   var $patTmpl;
   
	/**
	 * Objeto tradutor
	 */
	var $oLang;   
   

   function Template() {
	 global $oTranslator;
	 $this->oLang = $oTranslator;
     parent::patTemplate();
     $this->setNamespace('cacicInstall');
     $this->setRoot('templates');
     $this->readTemplatesFromInput('install_header.tmpl');
     $this->readTemplatesFromInput('install_body.tmpl');
     $this->readTemplatesFromInput('install_footer.tmpl');
     $this->readTemplatesFromInput('install_navbar.tmpl');
     
     $this->addVar('tmplPageHeader', 'CACIC_TITLE', 'CACIC - Instalador' );
     $this->addVar('tmplPageHeader', 'CACIC_LANG', CACIC_LANGUAGE );
     $this->addVar('tmplPageBody', 'CACIC_VERSION', CACIC_VERSION );
     $this->addVar('tmplPageHeader', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     $this->addVar('tmplPageHeader', 'CACIC_THEME', CACIC_THEME );
     $this->addVar('tmplPageHeader', 'CACIC_URL', CACIC_URL_INSTALL );
     $this->addVar('tmplNavBar', 'passo', 'first' );
     $this->addVar('tmplIntroducao', 'kciq_installerintrotitle', $this->oLang->_('kciq_installerintrotitle'));
     $this->addVar('tmplIntroducao', 'kciq_installer_introdution', $this->oLang->_('kciq_installer_introdution'));
   }

   function header() {
     $this->displayParsedTemplate('tmplPageHeader');
   }

   function navBar($type="preInstall") {
     $this->displayParsedTemplate('tmplNavBar'.$type);
   }

   function body() {
     $this->displayParsedTemplate('tmplPageBody');
   }
   
   function statusBar() {
     $this->displayParsedTemplate('tmplStatusBar');
   }
  
   function footer() {
     $this->displayParsedTemplate('tmplPageFooter');
   }
}
?>