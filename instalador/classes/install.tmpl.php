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

   function Template() {
     parent::patTemplate();
     $this->setNamespace('cacicInstall');
     $this->setRoot('templates');
     $this->readTemplatesFromInput('install_header.tmpl');
     $this->readTemplatesFromInput('install_body.tmpl');
     $this->readTemplatesFromInput('install_footer.tmpl');
     $this->readTemplatesFromInput('install_navbar.tmpl');
     
     $this->addVar('tmplPageHeader', 'CACIC_TITLE', 'CACIC - Instalador' );
     $this->addVar('tmplPageHeader', 'CACIC_LANG', CACIC_LANG );
     $this->addVar('tmplPageBody', 'CACIC_VERSION', CACIC_VERSION );
     $this->addVar('tmplPageHeader', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     $this->addVar('tmplPageHeader', 'CACIC_THEME', CACIC_THEME );
     $this->addVar('tmplPageHeader', 'CACIC_URL', CACIC_URL_INSTALL );
     $this->addVar('tmplNavBar', 'passo', 'first' );
   }
   
   function form2ImportFile() {
   }

   function header() {
     $this->displayParsedTemplate('tmplPageHeader');
   }

   function navBar($type="preInstall") {
     $this->displayParsedTemplate('tmplNavBar'.$type);
   }

   function setVars( $vars = array() ) {
     $this->addVar('tmplPipeline', 'hilight_time', $vars['hora_max'] );
     $this->addVar('tmplPipeline', 'periodo_ini', $vars['periodo_ini'] );
     $this->addVar('tmplPipeline', 'periodo_fim', $vars['periodo_fim'] );
     $this->addVar('tmplPipeline', 'unidade', $vars['unidade'] );
   }

   function body() {
     $this->displayParsedTemplate('tmplPageBody');
   }
   
   function reportHeader() {
     $this->displayParsedTemplate('tmplRelatorioHeader');
   }
   
   function reportBody($dados) {
     $this->clearTemplate('tmplRelatorioRows', true);
     if(!empty($dados))
       $this->addRows('tmplRelatorioRows', $dados);
     $this->displayParsedTemplate('tmplRelatorioRows');
   }

   function reportFooter() {
     $this->displayParsedTemplate('tmplRelatorioFooter');
   }
  
   function reportDWHeader() {
     $this->displayParsedTemplate('tmplRelatorioDWHeader');
   }
   
   function reportDWBody($dados) {
     $this->clearTemplate('tmplRelatorioDWRows', true);
     if(!empty($dados))
       $this->addRows('tmplRelatorioDWRows', $dados);
     $this->displayParsedTemplate('tmplRelatorioDWRows');
   }

   function reportDWFooter() {
     $this->displayParsedTemplate('tmplRelatorioDWFooter');
   }
  
   function statusBar() {
     $this->displayParsedTemplate('tmplStatusBar');
   }
  
   function footer() {
     $this->displayParsedTemplate('tmplPageFooter');
   }
}
?>