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
   

   function Template(&$_oTranslator) {
	 $this->oLang = &$_oTranslator;
     parent::patTemplate();
     $this->setNamespace('cacicInstall');
     $this->setRoot('templates');
     $this->readTemplatesFromInput('install_header.tmpl');
     $this->readTemplatesFromInput('install_body.tmpl');
     $this->readTemplatesFromInput('install_footer.tmpl');
     $this->readTemplatesFromInput('install_navbar.tmpl');
    
   }

   function header() {
     $this->addVar('tmplPageHeader', 'CACIC_TITLE', $this->oLang->_('kciq_msg web_installer') );
     $this->addVar('tmplPageHeader', 'CACIC_LANG', CACIC_LANGUAGE );
     $this->addVar('tmplPageHeader', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     $this->addVar('tmplPageHeader', 'CACIC_THEME', CACIC_THEME );
     $this->addVar('tmplPageHeader', 'CACIC_URL', CACIC_URL_INSTALL );
     
     $this->displayParsedTemplate('tmplPageHeader');
   }

   function navBar($type="preInstall") {
	 
     /*
      * Mosta idiomas diponíveis
      */
     $lang_available = $_SESSION['cacic_language_available'];
     $lang_active = $_SESSION['cacic_language'];
     $_list = array ();
	 foreach ($lang_available as $_lang_key => $_lang_value) {
		$_lang_selected = '';
		if ($_lang_key == $lang_active)
			$_lang_selected = 'selected';
		$_arrAux = array (
					array (
						'LANG_SELECTED' => $_lang_selected,
						'LANG_ABBR' => $_lang_key,
						'LANG_DESCR' => $_lang_value['descr']
					)
		);
		$_list = array_merge($_list, $_arrAux);
	 }
	 $this->addRows('tmplLang_list', $_list);
     $this->addVar('tmplNavBarPreInstall',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     $this->addVar('tmplIntroducao', 'kciq_installerintrotitle', $this->oLang->_('kciq_installerintrotitle'));
     $this->addVar('tmplIntroducao', 'kciq_installer_introdution', $this->oLang->_('kciq_installer_introdution'));
     $this->addVar('tmplIntroducao', 'KCIQ_DEF_LANGUAGE', $this->oLang->_('kciq_msg def_language'));
     
     $this->addVar('tmplNavBar', 'passo', 'first' );
     $this->addVar('tmplNavBarLicenca',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_PREVIOUS', $this->oLang->_('kciq_msg previous'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_LICENSE_AGREE_MSG', $this->oLang->_('kciq_msg aceito'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_LICENSE_AGREE', $this->oLang->_('kciq_msg aceitar'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_MSG_LICENSE_ADVISE', $this->oLang->_('kciq_msg license advise'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_MSG_LICENSE_TITLE', $this->oLang->_('kciq_msg license title'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_MSG_LICENSE_EN-READ', $this->oLang->_('kciq_msg license en_read'));
     $this->addVar('tmplNavBarLicenca',  'KCIQ_MSG_LICENSE_PT-READ', $this->oLang->_('kciq_msg license pt_read'));
     
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_CHECK', $this->oLang->_('kciq_msg check'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_REQUISITOS', $this->oLang->_('kciq_msg requisitos'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_PHPVERSION', $this->oLang->_('kciq_msg phpversion'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_MYSQL_SUPORTE', $this->oLang->_('kciq_msg mysql_suporte'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_FTP_SUPORTE', $this->oLang->_('kciq_msg ftp_suporte'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_MCRYPT_SUPORTE', $this->oLang->_('kciq_msg mcrypt_suporte'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_GD_SUPORTE', $this->oLang->_('kciq_msg gd_suporte'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_CFGFILE_WRITEABLE', $this->oLang->_('kciq_msg cfgfile_writeable'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_ADVISE_TITLE', $this->oLang->_('kciq_msg advise_title'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_IDEAL', $this->oLang->_('kciq_msg ideal'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_REAL', $this->oLang->_('kciq_msg real'));
     $this->addVar('tmplNavBarCheckInstall',  'KCIQ_PHP_MEMORY', $this->oLang->_('kciq_msg php_memory'));
     
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_PREVIOUS', $this->oLang->_('kciq_msg previous'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     
     $this->addVar('tmplNavBarFinish',  'KCIQ_PREVIOUS', $this->oLang->_('kciq_msg previous'));
     $this->addVar('tmplNavBarFinish',  'KCIQ_FINISH_TITLE', $this->oLang->_('kciq_msg finish title'));
     $this->addVar('tmplNavBarFinish',  'KCIQ_FINISH', $this->oLang->_('kciq_msg finish'));
     
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_PREVIOUS', $this->oLang->_('kciq_msg previous'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_NEXT', $this->oLang->_('kciq_msg next'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_TESTCONN_HELP', $this->oLang->_('kciq_msg test conn help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_TESTCONN', $this->oLang->_('kciq_msg test conn'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_BUILDBD_HELP', $this->oLang->_('kciq_msg build bd help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_BUILDBD', $this->oLang->_('kciq_msg build bd'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_SHOWCFGFILE_HELP', $this->oLang->_('kciq_msg showcfgfile help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_SHOWCFGFILE', $this->oLang->_('kciq_msg showcfgfile'));
     $this->addVar('tmplNavBarCouldSaveCFGFile',  'KCIQ_SAVECFGFILE_HELP', $this->oLang->_('kciq_msg savecfgfile help'));
     $this->addVar('tmplNavBarCouldSaveCFGFile',  'KCIQ_SAVECFGFILE', $this->oLang->_('kciq_msg savecfgfile'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_CONFIGURATIONS', $this->oLang->_('kciq_msg configurations'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INSTALL_TYPE', $this->oLang->_('kciq_msg install type'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_NEW', $this->oLang->_('kciq_msg new'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_NEW_HELP', $this->oLang->_('kciq_msg new help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_ADMIN_HELP', $this->oLang->_('kciq_msg inst admin help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_USER', $this->oLang->_('kciq_msg user'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_USER_HELP', $this->oLang->_('kciq_msg user help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_PASS', $this->oLang->_('kciq_msg password'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_PASS_HELP', $this->oLang->_('kciq_msg password help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DEMO', $this->oLang->_('kciq_msg demo'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DEMO_HELP', $this->oLang->_('kciq_msg demo help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_UPDATE', $this->oLang->_('kciq_msg update'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_UPDATE_HELP', $this->oLang->_('kciq_msg update help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_VERSION_HEADER', $this->oLang->_('kciq_msg version header'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_VERSION', $this->oLang->_('kciq_msg version'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_VERSION_HELP', $this->oLang->_('kciq_msg version help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_BKP', $this->oLang->_('kciq_msg backup'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_BKP_HELP', $this->oLang->_('kciq_msg backup help'));     
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB', $this->oLang->_('kciq_msg database'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_TYPE', $this->oLang->_('kciq_msg database type'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_TYPE_HELP', $this->oLang->_('kciq_msg database type help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_HOST', $this->oLang->_('kciq_msg database host'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_HOST_HELP', $this->oLang->_('kciq_msg database host help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_PORT', $this->oLang->_('kciq_msg database port'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_PORT_HELP', $this->oLang->_('kciq_msg database port help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_NAME', $this->oLang->_('kciq_msg database name'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DB_NAME_HELP', $this->oLang->_('kciq_msg database name help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DBUSER_HELP', $this->oLang->_('kciq_msg dbuser help'));
     $this->addVar('tmplNavBarConfiguration',  'KCIQ_INST_DBPASS_HELP', $this->oLang->_('kciq_msg dbpass help'));
     
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_SAVE', $this->oLang->_('kciq_msg save'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_SAVE_TITLE', $this->oLang->_('kciq_msg save title'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_TITLE', $this->oLang->_('kciq_msg admin mgm title'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_LOCAL', $this->oLang->_('kciq_msg admin mgm local'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_ABBR', $this->oLang->_('kciq_msg abbr'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_ABBR_HELP', $this->oLang->_('kciq_msg admin mgm abbr help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_NAME', $this->oLang->_('kciq_msg name'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_LOCAL_NAME_HELP', $this->oLang->_('kciq_msg admin mgm localname help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PS', $this->oLang->_('kciq_msg obs'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PS_HELP', $this->oLang->_('kciq_msg admin mgm obs help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_DATA_TITLE', $this->oLang->_('kciq_msg admin mgm data title'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_USER', $this->oLang->_('kciq_msg user'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_USER_HELP', $this->oLang->_('kciq_msg admin mgm user help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PASS', $this->oLang->_('kciq_msg password'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PASS_HELP', $this->oLang->_('kciq_msg admin mgm pass help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PASS_CHECK', $this->oLang->_('kciq_msg verify'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PASS_CHECK_HELP', $this->oLang->_('kciq_msg admin mgm verify pass help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_ADMINNAME_HELP', $this->oLang->_('kciq_msg admin mgm adminname help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_EMAIL', $this->oLang->_('kciq_msg email'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_EMAIL_HELP', $this->oLang->_('kciq_msg admin mgm email help'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PHONE', $this->oLang->_('kciq_msg phone'));
     $this->addVar('tmplNavBarAdminSetup',  'KCIQ_INST_ADMMGM_PHONE_HELP', $this->oLang->_('kciq_msg admin mgm phone help'));
     
     $this->addVar('tmplNavBarFinish',  'KCIQ_INST_END_TITLE', $this->oLang->_('kciq_msg inst end title'));
     $this->addVar('tmplNavBarFinish',  'KCIQ_INST_END_ADVISE', $this->oLang->_('kciq_msg inst end advise'));
     $this->addVar('tmplNavBarFinish',  'KCIQ_INST_END_ADVISE_TITLE', $this->oLang->_('kciq_msg inst end advise title'));
     $this->addVar('tmplNavBarFinish',  'KCIQ_INST_END_ADVISE_FILE', $this->oLang->_('kciq_msg inst end advise file'));
     
     $this->displayParsedTemplate('tmplNavBar'.$type);
   }

   function body() {
     $this->addVar('tmplPageBody', 'KCIQ_DEF_VERSION', $this->oLang->_('kciq_msg def_version') );
     $this->addVar('tmplPageBody', 'CACIC_VERSION', CACIC_VERSION );
     $this->addVar('tmplPageBody', 'KCIQ_WEB_INSTALLER', $this->oLang->_('kciq_msg web_installer') );
     $this->addVar('tmplPageBody', 'KCIQ_JS_ENABLE', $this->oLang->_('kciq_msg js_enable') );
     
     $this->displayParsedTemplate('tmplPageBody');
   }
   
   function statusBar() {
     $this->addVar('tmplStatusBar', 'KCIQ_MSG', $this->oLang->_('kciq_msg mensagem'));
     
     $this->displayParsedTemplate('tmplStatusBar');
   }
  
   function footer() {
     $this->addVar('tmplPageFooter', 'KCIQ_APOIO', $this->oLang->_('kciq_msg apoio'));
     
     $this->displayParsedTemplate('tmplPageFooter');
   }
   
   /*
	* Dump de variavies 
	*/
	function varDump($arg) {
	  echo "<pre>";
	  var_dump($arg);
	  echo "</pre>";
	}
}
?>