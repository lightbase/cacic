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

require_once("classes".CACIC_DS."install.tmpl.php");
require_once("classes".CACIC_DS."install.ado.php");

/**
 * Prove a Instala��o pela WEB
 */
class Install {
	
	/**
	 * Objeto de template
	 */
	var $oTmpl;
	
	/**
	 * Objeto AJAX
	 */
	var $oAjax;
	
	/**
	 * Objeto de persistencia
	 */
	var $oDB;
	
	/**
	 * Objeto tradutor
	 */
	var $oLang;
	
	/**
	 * Prove a Instala��o pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP4
	 */
	 function Install() {
		$this->__construct();
	 }
	
	/**
	 * Prove a Instala��o pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP5.
	 */
	 function __construct() {
	 	global $oTranslator;
	 	$this->oLang = &$oTranslator;
    	if(isset($_POST['cacic_config'])) {
    	   $_SESSION['cacic_config'] = $_POST['cacic_config'];
    	   $_SESSION['cacic_config']['path'] = CACIC_PATH;
    	   $_SESSION['cacic_config']['url'] = CACIC_URL;
    	}
    	if(isset($_POST['cacic_cfgftp'])) {
    	   $_SESSION['cacic_cfgftp'] = $_POST['cacic_cfgftp'];
    	}
    	if(isset($_POST['cacic_cfgmail'])) {
    	   $_SESSION['cacic_cfgmail'] = $_POST['cacic_cfgmail'];
    	}
    	
    	if(isset($_POST['cacic_admin']))
    	   $_SESSION['cacic_admin'] = $_POST['cacic_admin'];
	 
	 	/*
	 	 * instacia objetos de classes externas
	 	 */
	 	 $this->oTmpl = new Template($this->oLang);
	 	 $this->oTmpl->header();
	 	 $this->oTmpl->body();
	 }
	 
	 /**
	  * Mostra a barra de navega��o contextualizando o posso da instala��o
	  */
	 function navBar($showNavBar="preInstall") {
	 	 switch (strtolower($showNavBar)) {
	 	     case "checkinstall": {	$this->checkInstall(); break; }
	 	     case "mailverify": {	$this->mailVerify(); break; }
	 	     case "ftpverify": {	$this->ftpVerify(); break; }
	 	     case "configuration": { $this->configInstall(); break; }
	 	     case "adminsetup": { $this->adminSetup(); break; }
	 	     case "finish": { $this->finishInstall(); break; }
	 	 }
	 	 $this->oTmpl->navBar($showNavBar);
	 	 $this->oTmpl->statusBar();
	 }
	 
	 /**
	  * destrutor :(
	  */
	 function end() {
	 	 $this->oTmpl->footer();
	 }
	
	/*
	 * Dump de variavies 
	 */
	 function varDump($arg) {
		echo "<pre>";
		var_dump($arg);
		echo "</pre>";
	 }
	 
	 /*
	  * Verifica os requisitos e as recomenda��es para instala��o
	  */
	 function checkInstall() {
	 	/*
	 	 * por padrao poder� continuar o processo de installa��o
	 	 */
	 	$lCouldContinue = true;
	 	$msgLegenda = '';
	 	
	 	/*
	 	 * verifica a versao do PHP para o CACIC
	 	 */
	 	$this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPVERSION', CACIC_PHPVERSION);
	 	if(version_compare(phpversion(),CACIC_PHPVERSION,'>=')) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPVERSION_STATUS', $this->oLang->_('kciq_msg yes')." (".phpversion().")");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPVERSION_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPVERSION_STATUS', $this->oLang->_('kciq_msg no')." (".phpversion().")");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPVERSION_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPVERSION_HELP', $this->oLang->_('kciq_msg phpversion_help'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica se o biblioteca GD est� instalada; testando se a fun��o imagegd existe
	 	 */
		if (extension_loaded('gd') || function_exists('imagegd')) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPGD_STATUS', $this->oLang->_('kciq_msg yes'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPGD_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPGD_STATUS', $this->oLang->_('kciq_msg no'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPGD_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPGD_HELP', $this->oLang->_('kciq_msg phpgd_help'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica suporte a mCrypt; testando se a fun��o mcrypt_generic existe
	 	 */
		if (function_exists('mcrypt_generic')) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMCRYPT_STATUS', $this->oLang->_('kciq_msg yes'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMCRYPT_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMCRYPT_STATUS', $this->oLang->_('kciq_msg no'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMCRYPT_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMCRYPT_HELP', $this->oLang->_('kciq_msg phpmcrypt_help'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica suporte a SMTP; testando se a fun��o mail existe
	 	 */
		if (function_exists('mail')) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPEMAIL_STATUS', $this->oLang->_('kciq_msg yes'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPEMAIL_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPEMAIL_STATUS', $this->oLang->_('kciq_msg no'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPEMAIL_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPEMAIL_HELP', $this->oLang->_('kciq_msg phpemail_help'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica suporte a FTP; testando se a fun��o ftp_connect existe
	 	 */
		if (function_exists('ftp_connect')) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPFTP_STATUS', $this->oLang->_('kciq_msg yes'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPFTP_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPFTP_STATUS', $this->oLang->_('kciq_msg no'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPFTP_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPFTP_HELP', $this->oLang->_('kciq_msg phpftp_help'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica se o biblioteca MYSQL est� instalada; testando se a fun��o mysql_connect existe
	 	 */
	 	$this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_MYSQLVERSION', CACIC_DBVERSION);
		if (function_exists('mysql_get_client_info') and (version_compare(mysql_get_client_info(),CACIC_DBVERSION,'>='))) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMYSQL_STATUS', $this->oLang->_('kciq_msg yes')." ".mysql_get_client_info());
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMYSQL_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $mysql_version = function_exists('mysql_get_client_info') ? mysql_get_client_info() : "< 4";
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMYSQL_STATUS', $this->oLang->_('kciq_msg no')." ".$mysql_version);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMYSQL_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMYSQL_HELP', $this->oLang->_('kciq_msg phpmysql_help'));
	 	  $lCouldContinue = false;
	 	}

                /*
                 * verifica se há suporte ao LDAP; testando se a fun��o ldap_connect existe
                 */
                if (function_exists('ldap_connect')) {
                  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPLDAP_STATUS', $this->oLang->_('kciq_msg yes'));
                  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPLDAP_CLASS', "SimImg");
                }
                else {
                  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPLDAP_STATUS', $this->oLang->_('kciq_msg no'));
                  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPLDAP_CLASS', "NaoImg");
                  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPLDAP_HELP', $this->oLang->_('kciq_msg phpldap_help'));
                  $lCouldContinue = false;
                }
	 	
	 	/*
	 	 * verifica se � possivel escrever o arquivo de configura��es para o CACIC
	 	 */
		if ((is_writable(CACIC_CFGFILE_PATH) and !file_exists(CACIC_CFGFILE_PATH.CACIC_DS."config.php")) or 
		    (file_exists(CACIC_CFGFILE_PATH.CACIC_DS."config.php") and is_writable(CACIC_CFGFILE_PATH.CACIC_DS."config.php"))) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CFGFILE_STATUS', $this->oLang->_('kciq_msg yes'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CFGFILE_CLASS', "SimImg");
	 	  $_SESSION['saveCfgFile'] = true;
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CFGFILE_STATUS', $this->oLang->_('kciq_msg no'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CFGFILE_CLASS', "AvisoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CFGFILE_HELP', $this->oLang->_('kciq_msg phpcfgfile_help'));
	 	  $_SESSION['saveCfgFile'] = false;
	 	}

	 	/*
	 	 * verifica se short_open_tag est� ativa
	 	 */
	 	 $phpShortOpenTag = ((strtoupper(ini_get('short_open_tag')) == 'ON') or (ini_get('short_open_tag') == 1)) ? "ON" : "OFF";
	 	 $cacicSOT = (strtoupper(CACIC_PHPSOT) == 'ON' or CACIC_PHPSOT == 1) ? "ON" : "OFF";
	 	  
	 	if ($cacicSOT == $phpShortOpenTag) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPSOT', $cacicSOT );
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPSOT_STATUS', $phpShortOpenTag);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPSOT_CLASS', "SimImg");
	 	}
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPSOT', $cacicSOT);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPSOT_STATUS', $phpShortOpenTag);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPSOT_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPSOT_HELP', $this->oLang->_('kciq_msg php_flag_on'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	
	 	/*
	 	 * verifica se Register_globals est� ativa
	 	 */
	 	 $phpRGStatus = ((strtoupper(ini_get('register_globals')) == 'ON') or (ini_get('register_globals') == 1)) ? "ON" : "OFF";
	 	 $cacicRG = (strtoupper(CACIC_PHPRG) == 'ON' or CACIC_PHPRG == 1) ? "ON" : "OFF";
	 	  
	 	if ($cacicRG == $phpRGStatus) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPRG', $cacicRG );
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRG_STATUS', $phpRGStatus);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRG_CLASS', "SimImg");
	 	}
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPRG', $cacicRG);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRG_STATUS', $phpRGStatus);
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRG_CLASS', "NaoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRG_HELP', $this->oLang->_('kciq_msg php_flag_on'));
	 	  $lCouldContinue = false;
	 	}
	 	
	 	/*
	 	 * verifica se Register_long_arrays est� ativa
	 	 */
	 	 $phpRLAStatus = ((strtoupper(ini_get('register_long_arrays')) == 'ON') or (ini_get('register_long_arrays') == 1)) ? "ON" : "OFF";
	 	 $cacicRLA = (strtoupper(CACIC_PHPRLA) == 'ON' or CACIC_PHPRLA == 1) ? "ON" : "OFF";
	 	if(version_compare(phpversion(),'5.0.0','>=') and version_compare(phpversion(),'6.0.0','<')) {
		 	if ($cacicRLA == $phpRLAStatus) {
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPRLA', $cacicRLA );
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_STATUS', $phpRLAStatus);
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_CLASS', "SimImg");
		 	}
		 	else {
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPRLA', $cacicRLA);
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_STATUS', $phpRLAStatus);
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_CLASS', "NaoImg");
		 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_HELP', $this->oLang->_('kciq_msg php_flag_on'));
		 	  $lCouldContinue = false;
		 	}
	 	}
	 	else {
		  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_CLASS', "AvisoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPRLA_HELP', $this->oLang->_('kciq_msg php_flag_on_advise'));
	 	}
	 	
	 	/*
	 	 * verifica se Memoria Limite de execu��o de programas PHP
	 	 */
	 	$this->oTmpl->addVar('tmplNavBarCheckInstall', 'CACIC_PHPMEM', CACIC_PHPMEM);
		if ((integer)(ini_get('memory_limit'))>=(integer)(CACIC_PHPMEM)) {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMEM_STATUS', ini_get('memory_limit'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMEM_CLASS', "SimImg");
	 	}  
	 	else {
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMEM_STATUS', ini_get('memory_limit'));
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMEM_CLASS', "AvisoImg");
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstall', 'PHPMEM_HELP', $this->oLang->_('kciq_msg php_memory_help'));
	 	}
	 	$msgLegenda .= $this->oLang->_('kciq_msg legenda').': <span class="SimImg"> </span> &nbsp;- '.
	 	               $this->oLang->_('kciq_msg check_ok').' &nbsp;&nbsp;';
	 	$msgLegenda .= '<span class="NaoImg"> </span> &nbsp;- '.$this->oLang->_('kciq_msg check_notok').' &nbsp;&nbsp;';
	 	$msgLegenda .= '<span class="AvisoImg"> </span> &nbsp;- '.$this->oLang->_('kciq_msg check_advise');
	 	
	 	/*
	 	 * caso possa continuar o processo mostra botao proximo
	 	 */
	 	if($lCouldContinue)
	 	  $this->oTmpl->addVar('tmplNavBarCheckInstallContinue', 'continuar', true);
	 	else
	 	  $msgLegenda .= '<br /><br /><span class="erro">'.$this->oLang->_('kciq_msg fix_requiriment_help').'</span>';
	 	  
	 	$this->oTmpl->addVar('tmplStatusBar', 'MSG_STATUS', $msgLegenda);
	 	  
	 }
	 
	 /*
	  * Atribui dados padrao ao formul�rio de configura��es
	  */
	  function configInstall() {
	    global $cacic_updateFromVersion, $cacic_config;
        $_versionList = array();
        foreach($cacic_updateFromVersion as $_versionId => $_versionName) {
            $selected = $cacic_config['install']['updateFromVersion'] == $_versionId ? '' : "selected";
            $_arrAux = array( 
                            array( 
                                    'VERSION_SELECTED' => $selected,
                                    'VERSION_ID' => $_versionId,
                                    'VERSION_NAME' => $_versionName
                                )
                            );
            $_versionList = array_merge($_versionList,$_arrAux);
        }
     	// Atribui os dados de configura��o de banco para inser��o dos dados administrativos
        $this->oTmpl->addRows('cacic_updateFromVersion', $_versionList );
    	$this->setInputFields('tmplNavBarConfiguration');
	  } 
	  
	 /*
	  * Atribui dados padrao ao formul�rio de administra��o
	  */
	  function adminSetup() {
     	// Atribui dados padrao ao formul�rio de administra��o
	  	$this->setInputFields('tmplNavBarAdminSetup');
	  } 
	  
	 /*
	  * Atribui dados padrao ao formul�rio de FTP
	  */
	  function ftpVerify() {
     	// Atribui os dados do formulario FTP
	  	$this->setInputFields('tmplNavBarFtpVerify');
	  } 
	  
	 /*
	  * Atribui dados padrao ao formul�rio de FTP
	  */
	  function mailVerify() {
     	// Atribui os dados do formulario Mail
	  	$this->setInputFields('tmplNavBarMailVerify');
	  } 
	  
	 /*
	  * Atribui dados padrao aos campos do formulario
	  */
	  function setInputFields($template) {
     	
    	 // Campos padrao
         $this->oTmpl->addVar($template, 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
         	
	  	if(isset($_POST['step']))
    	  	$this->oTmpl->addVar($template, 'TASK', $_POST['task']);
	  	if(isset($_POST['step']))
    	  	$this->oTmpl->addVar($template, 'STEP', $_POST['step']);
	  	if(isset($_SESSION['saveCfgFile']) and $_SESSION['saveCfgFile'])
	  	    $this->oTmpl->addVar('tmplNavBarCouldSaveCFGFile', 'salvar', 'sim');
    	  	
     	// coloca os dados de configura��o de banco para inser��o dos dados administrativos
	  	if(isset($_SESSION['cacic_config'])) {
    	  	$cacic_config = $_SESSION['cacic_config'];
    	  	$this->oTmpl->addVar($template, 'CACIC_PATH', $cacic_config['path']);
    	  	$this->oTmpl->addVar($template, 'CACIC_URL', $cacic_config['url']);
    	  	$this->oTmpl->addVar($template, 'DB_TYPE', $cacic_config['db_type']);
    	  	$this->oTmpl->addVar($template, 'DB_HOST', $cacic_config['db_host']);
    	  	$this->oTmpl->addVar($template, 'DB_PORT', $cacic_config['db_port']);
    	  	$this->oTmpl->addVar($template, 'DB_NAME', $cacic_config['db_name']);
    	  	$this->oTmpl->addVar($template, 'DB_USER', $cacic_config['db_user']);
    	  	$this->oTmpl->addVar($template, 'DB_PASS', $cacic_config['db_pass']);
    	  	$this->oTmpl->addVar($template, 'DB_ADMIN', $cacic_config['db_admin']);
    	  	$this->oTmpl->addVar($template, 'DB_ADMIN_PASS', $cacic_config['db_admin_pass']);
    	  	$this->oTmpl->addVar($template, 'INSTALL_TYPE', $cacic_config['install']['type']);
    	  	if($cacic_config['install']['type'] == 'createDB')
    	  	  $this->oTmpl->addVar($template, 'INSTALL_NEW', 'checked');
    	  	if($cacic_config['install']['type'] == 'atualizar')
    	  	  $this->oTmpl->addVar($template, 'INSTALL_UPDATE', 'checked');
	  	}
	  	else {
    	  	$this->oTmpl->addVar($template, 'CACIC_PATH', CACIC_PATH);
    	  	$this->oTmpl->addVar($template, 'CACIC_URL', CACIC_URL);
    	  	$this->oTmpl->addVar($template, 'DB_HOST', "localhost");
    	  	$this->oTmpl->addVar($template, 'DB_PORT', "3306");
    	  	$this->oTmpl->addVar($template, 'DB_NAME', "cacic");
    	  	$this->oTmpl->addVar($template, 'DB_USER', "cacic_db_user");
	  	}
	  	
     	// coloca os dados de FTP
	  	if(isset($_SESSION['cacic_cfgftp'])) {
    	  	$cacic_cfgftp = $_SESSION['cacic_cfgftp'];
    	  	$this->oTmpl->addVar($template, 'FTP_HOST', $cacic_cfgftp['ftp_host']);
    	  	$this->oTmpl->addVar($template, 'FTP_PORT', $cacic_cfgftp['ftp_port']);
    	  	$this->oTmpl->addVar($template, 'FTP_SUBDIR', $cacic_cfgftp['ftp_subdir']);
    	  	$this->oTmpl->addVar($template, 'FTP_USER', $cacic_cfgftp['ftp_user']);
    	  	$this->oTmpl->addVar($template, 'FTP_PASS', $cacic_cfgftp['ftp_pass']);
	  	}
	  	else {
    	  	$this->oTmpl->addVar($template, 'FTP_HOST', "localhost");
    	  	$this->oTmpl->addVar($template, 'FTP_PORT', "21");
    	  	$this->oTmpl->addVar($template, 'FTP_SUBDIR', "agentes");
    	  	$this->oTmpl->addVar($template, 'FTP_USER', 'ftpcacic');
    	  	$this->oTmpl->addVar($template, 'FTP_PASS', 'ftpcacic');
	  	}
	  	
     	// coloca os dados de Mail
	  	if(isset($_SESSION['cacic_cfgmail'])) {
    	  	$cacic_cfgmail = $_SESSION['cacic_cfgmail'];
    	  	$this->oTmpl->addVar($template, 'mail_HOST', $cacic_cfgmail['ftp_host']);
    	  	$this->oTmpl->addVar($template, 'mail_PORT', $cacic_cfgmail['mail_port']);
    	  	$this->oTmpl->addVar($template, 'mail_SUBDIR', $cacic_cfgmail['mail_subdir']);
    	  	$this->oTmpl->addVar($template, 'mail_USER', $cacic_cfgmail['mail_user']);
    	  	$this->oTmpl->addVar($template, 'mail_PASS', $cacic_cfgmail['mail_pass']);
	  	}
	  	else {
    	  	$this->oTmpl->addVar($template, 'MAIL_HOST', "localhost");
    	  	$this->oTmpl->addVar($template, 'MAIL_PORT', "25");
    	  	$this->oTmpl->addVar($template, 'MAIL_SUBDIR', "agentes");
    	  	$this->oTmpl->addVar($template, 'MAIL_USER', 'mailcacic');
    	  	$this->oTmpl->addVar($template, 'MAIL_PASS', 'mailcacic');
	  	}
	  	
	  	// Dados administrativos
	  	if(isset($_SESSION['cacic_admin'])) {
    	  	$cacic_admin = $_SESSION['cacic_admin'];
    	  	$this->oTmpl->addVar($template, 'ORG_NAME', $cacic_admin['org_name']);
    	  	$this->oTmpl->addVar($template, 'LOCAL_SIGLA', $cacic_admin['local_sigla']);
    	  	$this->oTmpl->addVar($template, 'LOCAL_NOME', $cacic_admin['local_nome']);
    	  	$this->oTmpl->addVar($template, 'LOCAL_OBSERVACAO', $cacic_admin['local_observacao']);
    	  	$this->oTmpl->addVar($template, 'ADMIN_LOGIN', $cacic_admin['admin_login']);
    	  	$this->oTmpl->addVar($template, 'ADMIN_SENHA', $cacic_admin['admin_senha']);
    	  	$this->oTmpl->addVar($template, 'ADMIN_NOME', $cacic_admin['admin_nome']);
    	  	$this->oTmpl->addVar($template, 'ADMIN_EMAIL', $cacic_admin['admin_email']);
    	  	$this->oTmpl->addVar($template, 'ADMIN_FORNE', $cacic_admin['admin_fone']);
     	}
    	  	
	  } 
	  
	 /*
	  * Finalizacao da instala��o e gravacao do arquivo "config.php"
	  */
	  function finishInstall() {
	  
	    $msg = "";
	    $ip_servidor = ""; 
	    $usuario_bd = ""; 
	    $senha_usuario_bd = "";
	    $nome_bd = "";
	    $cacic_config = $_SESSION['cacic_config'];
	    $cacic_admin = $_SESSION['cacic_admin'];
	    
    	$this->oTmpl->addVar('tmplNavBarFinish', 'CACIC_URL', $cacic_config['url']);
    	
	  	if(isset($_SESSION['saveCfgFile']) and $_SESSION['saveCfgFile']) {
	  	    $this->oTmpl->addVar('tmplNavBarCouldSaveCFGFile', 'salvar', 'sim'); }
	  }
	  
	 /*
	  * Verifica a instala��o
	  */
	  function checkInstallation() {
	  
	    $msg = "";
	    $ip_servidor = ""; 
	    $usuario_bd = ""; 
	    $senha_usuario_bd = "";
	    $nome_bd = "";
	    $cacic_config = $_SESSION['cacic_config'];
	    $cacic_admin = $_SESSION['cacic_admin'];
	    
	    $cfgFileName = $cacic_config['path'].CACIC_DS.'include'.CACIC_DS.'config.php';

	    $cfgFileOk = false;
	    $dbConected = false;
	    $buildDBOK = false;

	    if(!isset($_SESSION['configFileSaved']) or !($_SESSION['configFileSaved']))
	        $msg .= "<span class='Erro'>[ ERRO! ] - ".$this->oLang->_('kciq_msg inst config file saved')."</span><br>";
	    else {
    	    if(!is_readable($cfgFileName) or ! @include($cfgFileName))
    	        $msg .= "<span class='Erro'>[ ERRO! ] - ".$this->oLang->_('kciq_msg inst config file read',array($cfgFileName)).".</span><br>";
    	    else
    	        $cfgFileOk = true;
	    }

	    if( isset($_SESSION['buildDBOK']) )
	        $buildDBOK = $_SESSION['buildDBOK'];
	    if(!$buildDBOK)
	        	$msg .= "<span class='Erro'>[ ERRO! ] - ".$this->oLang->_('kciq_msg inst database build fail')."</span><br>";
	    
	    if($cfgFileOk) {
         	$oDB = new ADO();
    		if (!$oDB->conecta( $ip_servidor, $usuario_bd, $senha_usuario_bd, $nome_bd ))
    	        $msg .= "<span class='Erro'>[ ERRO! ] - ".$this->oLang->_('kciq_msg database connect fail').":</span>".$this->oLang->_('kciq_msg check cacic configfile data',array($cfgFileName))."<br>";
    	    else
    	        $dbConected = true;
	    }

	    if($dbConected) {
    	    if(!isset($_SESSION['adminSetupSaved']) or !($_SESSION['adminSetupSaved']))
    	        $msg .= "<span class='Erro'>[ ERRO! ] - ".$this->oLang->_('kciq_msg inst database data save fail')."</span><br>";
    	    else {
    	  	    $sql_get_local_id = "select id_local from locais where sg_local = '".$cacic_admin['local_sigla']."'";
    	  	    $oDB->selectDB();
    			$oDB->query($sql_get_local_id);
    			if (!($oDB->numRows() > 0))
    				$msg .= '<span class="Erro">[ ERRO! ] - '.$this->oLang->_('kciq_msg local not reg').'</span><br>';
    			
    	  	    $sql_check_admin = "select nm_usuario_acesso from usuarios where nm_usuario_acesso = '".$cacic_admin['admin_login']."'";
    			$oDB->query($sql_check_admin);
    			if (!($oDB->numRows() > 0)) 
    				$msg .= '<span class="Erro">[ ERRO! ] - '.$this->oLang->_('kciq_msg admin not reg').'</span><br>';
    	    }
	    }
	    
	    if(empty($msg))
	        $msg = "<span class='OkImg'>".$this->oLang->_('kciq_msg inst finished and verified')."</span><br>";

    	$this->oTmpl->addVar('tmplStatusBar', 'MSG_STATUS', $msg);
	    
	  }
}
?>
