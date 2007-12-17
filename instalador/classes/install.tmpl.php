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
     
     $this->addVar('tmplPageHeader', 'CACIC_TITLE', $this->oLang->_('kciq_msg web_installer') );
     $this->addVar('tmplPageHeader', 'CACIC_LANG', CACIC_LANGUAGE );
     $this->addVar('tmplPageBody', 'KCIQ_DEF_VERSION', $this->oLang->_('kciq_msg def_version') );
     $this->addVar('tmplPageBody', 'CACIC_VERSION', CACIC_VERSION );
     $this->addVar('tmplPageBody', 'KCIQ_WEB_INSTALLER', $this->oLang->_('kciq_msg web_installer') );
     $this->addVar('tmplPageBody', 'KCIQ_JS_ENABLE', $this->oLang->_('kciq_msg js_enable') );
     $this->addVar('tmplPageHeader', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     $this->addVar('tmplPageHeader', 'CACIC_THEME', CACIC_THEME );
     $this->addVar('tmplPageHeader', 'CACIC_URL', CACIC_URL_INSTALL );
     $this->addVar('tmplNavBar', 'passo', 'first' );
     $this->addVar('tmplNavBarPreInstall',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_PREVIOUS', $this->oLang->_('kciq_msg previous'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_LICENSE_AGREE_MSG', $this->oLang->_('kciq_msg aceito'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_LICENSE_AGREE', $this->oLang->_('kciq_msg aceitar'));
     $this->addVar('tmplIntroducao', 'kciq_installerintrotitle', $this->oLang->_('kciq_installerintrotitle'));
     $this->addVar('tmplIntroducao', 'kciq_installer_introdution', $this->oLang->_('kciq_installer_introdution'));
     $this->addVar('tmplIntroducao', 'KCIQ_DEF_LANGUAGE', $this->oLang->_('kciq_msg def_language'));
     $this->addVar('tmplStatusBar', 'KCIQ_MSG', $this->oLang->_('kciq_msg mensagem'));
     $this->addVar('tmplPageFooter', 'KCIQ_APOIO', $this->oLang->_('kciq_msg apoio'));
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