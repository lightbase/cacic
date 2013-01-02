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

include_once("classes".CACIC_DS."install.tmpl.php");
include_once("classes".CACIC_DS."install.ado.php");

/**
 * Prove a metodos para recursos AJAX na Instala��o pela WEB
 */
class InstallAjax {
	/**
	 * Objeto de template
	 */
	var $oTmpl;
	
	/**
	 * Objeto de persistencia
	 */
	var $oDB;
	
	/**
	 * Objeto tradutor
	 */
	var $oLang;
	
	/**
	 * Prove a metodos para recursos AJAX na Instala��o pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP4
	 */
	 function InstallAjax() {
		$this->__construct();
	 }
	
	/**
	 * Prove a metodos para recursos AJAX na Instala��o pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP5.
	 */
	 function __construct() {
	 	/*
	 	 * instacie objetos de classes externas se necess�rio
	 	 */
	 }
	  
	 /**
	  * Processa as requisi��es AJAX
	  */
	  function processAjax($task) {
	  	switch (strtolower($task)) {
	  		case 'testconnftp' : InstallAjax::checkFtpServer($_SESSION['cacic_cfgftp']); break;
	  		case 'testconn' : InstallAjax::checkDBConnection($_SESSION['cacic_config']); break;
	  		case 'showcfgfile' : InstallAjax::showCFGFile($_SESSION['cacic_config']); break;
	  		case 'savecfgfile' : InstallAjax::saveCFGFile($_SESSION['cacic_config']); break;
	  		case 'dbbuild' : InstallAjax::buildDB($_SESSION['cacic_config']); break;
	  		case 'salvaadminsetup' : InstallAjax::salvaAdminSetup($_SESSION['cacic_admin'], $_SESSION['cacic_config']); break;
	  		default: die( 'Acesso restrito (Restricted access)!' );
	  	}
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
	  * Cria arquivo de configura��o para o CACIC
	  * @access private
	  */
	  function buildCFGFile($cacic_config) {
		$oTmpl = new patTemplate();
		$oTmpl->setNamespace('cacicInstall');
		$oTmpl->setRoot('templates');
		$oTmpl->readTemplatesFromInput('install_navbar.tmpl');
		$oTmpl->addVar('tmplCFGFile', 'cacic_path', addslashes(CACIC_PATH) );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_URL', CACIC_URL );
		$oTmpl->addVar('tmplCFGFile', 'DB_SERVER', $cacic_config['db_host'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_PORT', $cacic_config['db_port'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_NAME', $cacic_config['db_name'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_USER', $cacic_config['db_user'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_USER_PASS', $cacic_config['db_pass'] );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_KEY', CACIC_KEY );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_IV', CACIC_IV );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_LANGUAGE', $_SESSION['cacic_language']);
		
		$oTmpl->addVar('tmplCFGFileCab', 'show_path', CACIC_PATH );
		$oTmpl->addVar('tmplCFGFileCab', 'cacic_ds', CACIC_DS );
		$oTmpl->displayParsedTemplate('tmplCFGFile');
	  }
	  
	 /*
	  * Tradu��o de mensagens em Ajax - copia da implementa��o original de "getText"
	  * 
	  * @see Translator::getText()
	  * @access private
	  */
	  function _($_msg_code, $_sigla=false, $_text_case=0, $_args=array()) {
	 	global $oTranslator;
	 	return $oTranslator->_($_msg_code, $_sigla, $_text_case, $_args);
	  }
	    
	 /*
	  * Verifica dados de conex�o com o banco de dados
	  * @access private
	  */
	  function checkCFGFileData($cacic_config) {
	    
	    $dadosOK = true;
	    $msg = "";
  		$path = $cacic_config['path'];
	  	if(!is_readable($path) and !is_executable($path)) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst path not executable')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['url'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst url not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_type'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database type not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_host'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database server not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_port'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database server port not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_name'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database name not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_user'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database user not defined')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['install']['type'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst type not defined')."</span><br>";
	  	}
	  	
	  	// Instala��o nova
	  	if($cacic_config['install']['type'] == 'createDB') {
	  	    if(empty($cacic_config['db_admin'])) {
    	        $dadosOK = false;
    	        $msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database admin not defined').'</span><br>';
	        }

    		$fileName = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.CACIC_SQLFILE_CREATEDB;
    		if(!is_readable($fileName)) {
    	        $dadosOK = false;
				$msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database sqlbuild not defined',array(CACIC_SQLFILE_CREATEDB)).'</span><br>';
			}
			$fileNameTgt = str_replace("LANGUAGE",$cacic_config['cacic_language'],CACIC_SQLFILE_STDDATA);
    		$fileNamePath = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.$fileNameTgt;
    		if(!is_readable($fileNamePath)) {
    			$fileName = str_replace("LANGUAGE",CACIC_LANGUAGE_STANDARD,CACIC_SQLFILE_STDDATA);
	    		$fileNamePath = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.$fileName;
	    		if(!is_readable($fileNamePath)) {
	    	        $dadosOK = false;
					$msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database sqldata not defined',array($fileNameTgt,$fileName)).'</span><br>';
	    		}
	    		else
				   $msg .= '<span class="Aviso">['.InstallAjax::_('kciq_msg advise', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database standard sqldata should be used',array($fileNameTgt,$fileName)).'</span><br>';
			}
    		if($cacic_config['dbdet']['demo'] == 'demo') {
    			$fileName = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.CACIC_SQLFILE_DEMODATA;
    			if(!is_readable($fileName)) {
					$msg .= '<span class="AvisoImg">['.InstallAjax::_('kciq_msg advise', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database sqldemodata not defined',array(CACIC_SQLFILE_DEMODATA)).'</span> '.InstallAjax::_('kciq_msg check_advise').'<br>';
    			}
    		}		
			
	  	} // Atualiza��o de vers�o
	  	elseif($cacic_config['install']['type'] == 'updateDB') {
	  	    if(empty($cacic_config['install']['updateFromVersion'])) {
    	        $dadosOK = false;
    	        $msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst version to update').'</span><br>';
	        }
	        else {
        		$fileName = CACIC_SQLFILE_PREFIX.strtolower($cacic_config['install']['updateFromVersion']).'.sql';
        		$fileNamePath = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.$fileName;
        		if(!is_readable($fileNamePath)) {
        	        $dadosOK = false;
    				$msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database sqlupdatedata not defined',array($fileName)).'</span><br>';
    			}
			}
	  	}

        echo $msg;
	  	
	  	return $dadosOK;
        
	  }
	 /**
	  * Mostra arquivo de configura��o para o CACIC
	  */
	  function showCFGFile($cacic_config) {
	    $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('Retorne aos passos anteriores e configure adequadamente').'</span><br>';
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se n�o conectar para o processo
	 	  die($msg);
	 	  
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	 	  die($msg);
	 	
	 	InstallAjax::buildCFGFile($cacic_config); // dados informados adequadamente
	  }
	  
	 /**
	  * Grava arquivo de configura��o para o CACIC
	  */
	  function saveCFGFile($cacic_config) {
	    $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('Retorne aos passos anteriores e configure adequadamente').'</span><br>';
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se n�o conectar para o processo
	 	  die($msg);
	      
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	      die($msg); // se dados incorretos
	      
		$fileName = $cacic_config['path'].CACIC_DS.'include'.CACIC_DS.'config.php';
		$fileContent = '<?php
    /*
     * Arquivo de configuracoes para o CACIC
     * @version $Id: config.php 2007-02-08 22:20 harpiain $
     * @package Cacic
     * @license GNU/GPL, see LICENSE.php
     */

    /*
     * Nome do banco de dados
     */
    $nome_bd = "'.$cacic_config['db_name'].'";
    
    /*
     * Nome/IP do servidor de banco de dados
     */
    $ip_servidor = "'.$cacic_config['db_host'].'";
    
    /*
     * Porta no servidor de banco de dados
     */
    $porta = "'.$cacic_config['db_port'].'";
    
    /*
     * Usuario de conexao do servidor de banco de dados
     */
    $usuario_bd = "'.$cacic_config['db_user'].'";
    
    /*
     * Senha do usuario de conexao do servidor de banco de dados
     */
    $senha_usuario_bd = "'.$cacic_config['db_pass'].'";
    
    /*
     * URL da aplicacao "CACIC"
     */
    $url_aplicacao = "'.CACIC_URL.'";

    /*
     * CACIC application language
     */
    $cacic_language = "'.$cacic_config['cacic_language'].'";

    /*
     * CACIC application theme
     */
    $cacic_theme = "default";

    /*
     * Caminho fisico da aplicacao "CACIC"
     */
    $path_aplicacao = "'.addslashes(CACIC_PATH).'";

   /*
     * Caminho fisico para componentes de instala��o, coleta avulsa de informa��es patrimoniais e cliente de Suporte Remoto
     */
    $path_relativo_repositorio_instalacao  = "'.addslashes(CACIC_PATH_RELATIVO_REPOSITORIO_INSTALACAO).'";

    /*
     * Atencao:
     * As chaves abaixo, ate a presente versao, sao assimeticas, ou seja, 
     * caso seja necessario altera-las, os agentes "Cacic2.exe", "ChkCacic.exe" e   
     * "ChkSis.exe" tambem deverao ser alterados, via programacao Delphi 7.
     */
    $key = "'.CACIC_KEY.'";
    $iv = "'.CACIC_IV.'";
?>';
		
		$msg = "";
		if(@fwrite(fopen($fileName,"w+"),$fileContent)) {
		    $msg .= "<br><span class='OkImg'>".InstallAjax::_('kciq_msg file saved', array($fileName));
		    $_SESSION['configFileSaved'] = true;
		}
		else
		  $msg .= "<br><span class='Erro'>[".InstallAjax::_('kciq_msg error', '',2)."! ] - ".InstallAjax::_('kciq_msg inst check dir perm', array($fileName)) . "</span>";
		echo $msg;
	  }
	  	
	  /**
	   * Verifica conexao com o banco de dados
	   */
	  function checkDBConnection($cacic_config) {
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	      return false; // se dados incorretos
	    
     	$connOk = true;
     	$oDB = new ADO($cacic_config['db_type']);
		$msg = "[".InstallAjax::_('kciq_msg ok', '',2)."! ] - ".InstallAjax::_('kciq_msg inst database connect ok') . "<span class='OkImg'></span>";
		if($cacic_config['install']['type'] == 'createDB') {// instala��o nova
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_admin'], 
		                  $cacic_config['db_admin_pass'], $cacic_config['db_name'] );
		}
		else // atualiza��o da base
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], 
		                  $cacic_config['db_pass'], $cacic_config['db_name'] );
		    
		if (!$oDB->conecta()) {
			$msg = '<span class="Erro">'."[".InstallAjax::_('kciq_msg error', '',2)."! ] - ";
		    $msg .= InstallAjax::_('kciq_msg database connect fail').'!</span>'.
					'<br>'.InstallAjax::_('kciq_msg server msg').':';
			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
			$connOk = false;
		}
		
		if($connOk) {
			$versao = $oDB->version();
			if(!(version_compare($versao,CACIC_DBVERSION,'>='))) {
			  $connOk = false;
			  $msg = '<br><span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ".
			         InstallAjax::_('kciq_msg database server version invalid').'!</span>';
			  $msg .= '<br>'.InstallAjax::_('kciq_msg requerida').': <span class="Aviso">'.CACIC_DBVERSION.'</span>';
			  $msg .= '<br>'.InstallAjax::_('kciq_msg instalada').': <span class="Nao">'.$versao.'</span>';
			}
		}
		echo $msg;
		return $connOk;  
	  }
	  
	  /**
	   * Verifica conexao com o servi�o FTP
	   */
	  function checkFtpServer($cacic_config) {
     	$_connOk = true;
     	$_server = $cacic_config['ftp_host'];
     	$_port = $cacic_config['ftp_port'];
     	$_user = $cacic_config['ftp_user'];
     	$_user_pass = $cacic_config['ftp_pass'];
     	$_subdir = $cacic_config['ftp_subdir'];
     	$oFtp = new Ftp($_server,$_port,$_user,$_user_pass,$_subdir);
		$msg = "[".InstallAjax::_('kciq_msg ok', '',2)."! ] - ".InstallAjax::_('kciq_msg connected ok') . "<span class='OkImg'></span><br>";
     	if($oFtp->isError()) {
			$msg = '<span class="Erro">'."[".InstallAjax::_('kciq_msg error', '',2)."! ] - ";
		    $msg .= InstallAjax::_('kciq_msg ftp connect fail').'!</span>'.
					'<br>'.InstallAjax::_('kciq_msg server msg').':';
			$msg .= '<pre>'.$oFtp->getMessage().'</pre>';
     		$_connOk = false;
     	}
		echo $msg;
     	
     	if($_connOk ) {  
			$msg = "[".InstallAjax::_('kciq_msg ok', '',2)."! ] - ".InstallAjax::_('kciq_msg ftp login ok') . "<span class='OkImg'></span><br>";
	     	if(!$oFtp->login()) {
				$msg = '<span class="Erro">'."[".InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			    $msg .= InstallAjax::_('kciq_msg ftp login connect fail').'!</span>'.
						'<br>'.InstallAjax::_('kciq_msg server msg').':';
				$msg .= '<pre>'.$oFtp->getMessage().'</pre>';
	     		$_connOk = false;
	     	}
			echo $msg;
	     	if($_connOk ) {
				$msg = "[".InstallAjax::_('kciq_msg ok', '',2)."! ] - ".InstallAjax::_('kciq_msg ftp change dir ok') . "<span class='OkImg'></span><br>";
		     	if(!$oFtp->changeDir($_subdir)) {
					$msg = '<span class="Erro">'."[".InstallAjax::_('kciq_msg error', '',2)."! ] - ";
				    $msg .= InstallAjax::_('kciq_msg ftp change dir fail').'!</span>'.
							'<br>'.InstallAjax::_('kciq_msg server msg').':';
					$msg .= '<pre>'.$oFtp->getMessage().'</pre>';
		     		$_connOk = false;
		     	}
				echo $msg;
	     	}
     	}
		
		return $_connOk;  
	  }
	  
	 /*
	  * Verifica conex�o com o banco de dados
	  */
	 function buildDB($cacic_config) {
	    $builDBOK = false;
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se n�o conectar para o processo
	 	  die();
	 		
     	$cacic_dbdet = $cacic_config['dbdet'];
     	$installType = $cacic_config['install']['type'];
     	
		/*
		 * processo de cria��o do banco e tabelas
		 */
		
		/*
		 * Conexao ao banco de dados
		 */		     	
		echo "<br>".InstallAjax::_('kciq_msg inst connecting to database server');
     	$oDB = new ADO($cacic_config['db_type']);
		if($installType == 'createDB') {// instala��o nova
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_admin'], 
		                  $cacic_config['db_admin_pass'], $cacic_config['db_name'] );
		    $oDB->addDBUser($cacic_config['db_user'], $cacic_config['db_pass']);
		}
		else // atualiza��o da base
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], 
		                  $cacic_config['db_pass'], $cacic_config['db_name'] );
		                  
		if (!$oDB->conecta()) {
			$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
		    $msg .= InstallAjax::_('kciq_msg database connect fail').'!</span>'.
					'<br>'.InstallAjax::_('kciq_msg server msg').':';
			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
		    die($msg);
		}
		else
			echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";

		if($installType == 'createDB') {// instala��o nova
         	$oDB_result = $oDB->addDBUser($cacic_config['db_user'], $cacic_config['db_pass']);
         	echo "<br>".InstallAjax::_('kciq_msg inst make database permissions',array($cacic_config['db_user']));
    		if (!$oDB_result) {
    			$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
    		    $msg .= InstallAjax::_('kciq_msg user insert error',array($cacic_config['db_user'])).'</span>'.
    					'<br>'.InstallAjax::_('kciq_msg server msg').':';
    			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
    		    die($msg);
    		}
    		else
    			echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
		}
			
		/*
		 * Cria banco de dados
		 */
		if($installType == 'createDB') {
			echo "<br>".InstallAjax::_('kciq_msg inst build database',array($cacic_config['db_name']));
			if (!$oDB->selectDB($cacic_config['db_name'])) {
				if (!$oDB->createDB()) {
					$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
				    $msg .= InstallAjax::_('kciq_msg inst build database error',array($cacic_config['db_name'])).'</span>'.
							'<br>'.InstallAjax::_('kciq_msg server msg').':';
					$msg .= '<pre>'.$oDB->getMessage().'</pre>';
				    die($msg);
				}
				else
					echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
			}
			else {
				$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			    $msg .= InstallAjax::_('kciq_msg inst database exist',array($cacic_config['db_name'])).'</span>';
			    die($msg);
			}
		}
		
		if (!$oDB->selectDB()) {
			$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
		    $msg .= InstallAjax::_('kciq_msg database not exist',array($cacic_config['db_name'])).'</span>'.
					'<br>'.InstallAjax::_('kciq_msg server msg').':';
			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
		    die($msg);
		}
		
		if($installType == 'createDB') {
		   /*
		    * Cria as tabelas do banco de dados
		    */  
		   $fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_CREATEDB;
		   if(is_readable($fileName)) {
   			$cacic_sql_create_tables = $fileName;
		
			   echo "<br>".InstallAjax::_('kciq_msg inst building tables on database',array($cacic_config['db_name']));
	     	   $oDB_result = $oDB->parse_mysql_dump($cacic_sql_create_tables);
			   if (!$oDB_result) {
				   $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg inst database build fail').'</span>'.
						   '<br>'.InstallAjax::_('kciq_msg server msg').':';
				   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				   echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
		   }
		   else {
				   $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg inst tables build error').'</span>';
			      die($msg);
			}
		}
		else {
		   /*
		    * Atualiza as tabelas do banco de dados
		    */  
		   $fileName = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.'cacic_'.strtolower($cacic_config['install']['updateFromVersion']).'.sql';
		   if(is_readable($fileName)) {
   			$cacic_sql_create_tables = $fileName;
		
			   echo "<br>".InstallAjax::_('kciq_msg inst update tables on database', array($cacic_config['db_name']));
	     	   $oDB_result = $oDB->parse_mysql_dump($cacic_sql_create_tables);
			   if (!$oDB_result) {
				   $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg inst update tables on database', array($cacic_config['db_name'])).'!</span>'.
						   '<br>'.InstallAjax::_('kciq_msg server msg').':';
				   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				   echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
		   }
		   else {
				   $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg inst tables update error').'</span>';
			      die($msg);
			}
		}
					  
		if($installType == 'createDB') {
		   /*
		    * Inclui dados b�sicos para CACIC, do idioma selecionado ou, caso n�o exista, do idioma padr�o
		    */
			$fileNameTgt = str_replace("LANGUAGE",$cacic_config['cacic_language'],CACIC_SQLFILE_STDDATA);
    		$fileName = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.$fileNameTgt;
    		if(!is_readable($fileName)) {
    			$fileName = str_replace("LANGUAGE",CACIC_LANGUAGE_STANDARD,CACIC_SQLFILE_STDDATA);
	    		$fileName = $cacic_config['path'].'instalador'.CACIC_DS.'sql'.CACIC_DS.$fileName;
    		}
	    		
		   if(is_readable($fileName)) {
			 $cacic_sql_dadosbase = $fileName;
			 echo "<br>".InstallAjax::_('kciq_msg inst insert basic data',array($cacic_config['db_name']));
	     	 $oDB_result = $oDB->parse_mysql_dump($cacic_sql_dadosbase);
			 if (!$oDB_result) {
				$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			    $msg .= InstallAjax::_('kciq_msg inst insert basic data',array($cacic_config['db_name'])).'!</span>'.
						'<br>'.InstallAjax::_('kciq_msg server msg').':';
				$msg .= '<pre>'.$oDB->getMessage().'</pre>';
			    die($msg);
			 }
			 else
				echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
		   }
		   else {
				$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			    $msg .= InstallAjax::_('kciq_msg inst insert basic data error').'</span>';
			    die($msg);
		   }
					  
		   /*
		    * Inclui dados nas tabelas para demonstra��o do cacic
		    */			  
		   if($cacic_dbdet['demo'] == 'demo') {
			 echo "<br>".InstallAjax::_('kciq_msg inst insert demo data');
			 $fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_DEMODATA;
			 if(is_readable($fileName)) {
				$cacic_sql_demonstracao = $fileName;
				$oDB_result = $oDB->parse_mysql_dump($cacic_sql_demonstracao);
				if (!$oDB_result) {
					$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
				    $msg .= InstallAjax::_('kciq_msg inst insert demo data').'!</span>'.
							'<br>'.InstallAjax::_('kciq_msg server msg').':';
					$msg .= '<pre>'.$oDB->getMessage().'</pre>';
				    die($msg);
				}
				else
					echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
			 }
			 else {
					$msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
				    $msg .= InstallAjax::_('kciq_msg inst insert demo data error').'</span>';
				    die($msg);
			 }
		  }
		}		
		echo "<br><span class='OkImg'>".InstallAjax::_('kciq_msg inst database build process ok',array($cacic_config['db_name']))."</span>";
		$_SESSION['buildDBOK'] = true;
	 }
	 
	 /*
	  * Verifica dados de administra��o para o CACIC
	  * @access private
	  */
	  function checkAdminSetupData($cacic_admin) {
	    $dadosOK = true;
	    $msg = "";
	  	if(empty($cacic_admin['org_name'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg org label needed')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['local_sigla'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg local abbr needed')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['local_nome'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg local name needed')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_login'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg admin login needed')."</span><br>";
	  	}
	  	
	  	if($cacic_admin['admin_senha'] != $cacic_admin['admin_senha_check']) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg password not same')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_senha']) or empty($cacic_admin['admin_senha_check'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg password needed')."</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_nome'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>".InstallAjax::_('kciq_msg admin name needed')."</span><br>";
	  	}
	  	
        echo $msg;
	  	
	  	return $dadosOK;
        
	  }
 
	 /*
	  * Salva dados de administra��o para o CACIC
	  * @access private
	  */
	  function salvaAdminSetup($cacic_admin, $cacic_config) {
	    $msg = "";
	    $adminSetupSaved = false;
	    
	    $dadosOK = InstallAjax::checkAdminSetupData($cacic_admin);
	  	if($dadosOK) { // Se dadosOK cria ou atualiza dados de local e administrador
       		/*
    		 * Conexao ao banco de dados
    		 */		     	
    		echo "<br>".InstallAjax::_('kciq_msg inst connecting to database server');
         	$oDB = new ADO($cacic_config['db_type']);
         	$oDB->debug();
    		$oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], $cacic_config['db_pass'], $cacic_config['db_name'] );
    		if (!$oDB->conecta()) {
      		   $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
    		   $msg .= InstallAjax::_('kciq_msg database connect fail').'!</span>'.
    					'<br>'.InstallAjax::_('kciq_msg server msg').':';
    		   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
    		   die($msg);
    		}
    		else
    		   echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";

    		/*
    		 * Verifica banco de dados
    		 */
			echo "<br>".InstallAjax::_('kciq_msg inst verify database existence', array($cacic_config['db_name']));
			if (!$oDB->selectDB()) {
			  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			  $msg .= InstallAjax::_('kciq_msg database not exist', array($cacic_config['db_name'])).'!</span>'.
					  '<br>'.InstallAjax::_('kciq_msg server msg').':';
			  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			  die($msg);
			}
		    else
			  echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
    		
	       if ($cacic_config['install']['type'] == 'createDB') { // Cria dados de local e administrador
    		   /*
    		    * Verifica a n�o exist�ncia do local informado
    		    */
    		   $localOK = true;
	  	       $sql_get_local_id = "select id_local from locais where sg_local = '".$cacic_admin['local_sigla']."'";
			   $msg ="<br>".InstallAjax::_('kciq_msg inst verify local', array($cacic_admin['local_sigla']));
			   $oDB->query($sql_get_local_id);
			   if ($oDB->numRows() > 0) {
				  $msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg local already exist', array($cacic_admin['local_sigla'])).'</span>';
			      $localOK = false;
			   }
			   else
				  $msg .= "[ ".InstallAjax::_('kciq_msg ok')."! ]";
			
    		   /*
    		    * Verifica a n�o exist�ncia do administrador informado
    		    */
    		   $adminOK = true;
	  	       $sql_check_admin = "select nm_usuario_acesso from usuarios where nm_usuario_acesso = '".$cacic_admin['admin_login']."'";
			   $msg .= "<br>".InstallAjax::_('kciq_msg inst verify admin', array($cacic_admin['admin_login']));
			   $oDB->query($sql_check_admin);
			   if ($oDB->numRows() > 0) {
				  $msg .= '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg login already exist', array($cacic_admin['admin_login'])).'</span>';
			      $adminOK = false;
			   }
			   else
				  $msg .= "[ ".InstallAjax::_('kciq_msg ok')."! ]";
			
			   echo $msg;
			
			   if(!($localOK and $adminOK))
			      die();
			
    		   /*
    		    * Caso tenha PHP XML compilado/instalado usa a funcao UTF8
    		    */
    		   $localNome = $cacic_admin['local_nome'];
    		   $localObservacao = $cacic_admin['local_observacao'];
	  	       $adminNome = $cacic_admin['admin_nome'];
    		   if(function_exists('utf8_decode')) {
        		  $localNome = utf8_decode(trim($cacic_admin['local_nome']));
        		  $localObservacao = utf8_decode(trim($cacic_admin['local_observacao']));
	  	          $adminNome = utf8_decode(trim($cacic_admin['admin_nome']));
    		   }
    		
    		   /*
    		    * Insere o empresa informada
    		    */
	  	        $sql_update_orgname = "UPDATE configuracoes_padrao SET nm_organizacao='".
	  	                                                               $cacic_admin['org_name']."'";
			   echo "<br>".InstallAjax::_('kciq_msg inst org name update', array($cacic_admin['org_name']));
			   if (!$oDB->query($sql_update_orgname)) {
				  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg org name update error').'</span>'.
						  '<br>'.InstallAjax::_('kciq_msg server msg').':';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
    		
    		   /*
    		    * Insere o local informado
    		    */
	  	        $sql_insert_local = "INSERT INTO locais VALUES (0,'".
	  	                            $localNome."','".
	  	                            $cacic_admin['local_sigla']."','".
	  	                            $localObservacao.
	  	                        "')";
			   echo "<br>".InstallAjax::_('kciq_msg inst local insert', array($cacic_admin['local_sigla']));
			   if (!$oDB->query($sql_insert_local)) {
				  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg local insert error', array($cacic_admin['local_sigla'])).'</span>'.
						  '<br>'.InstallAjax::_('kciq_msg server msg').':';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
    		
    		   /*
    		    * Busca ID do local recem inclu�do
    		    */
			   if (!$oDB->query($sql_get_local_id)) {
				  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg login not exist', array($cacic_admin['admin_login'])).'</span>'.
						  '<br>'.InstallAjax::_('kciq_msg server msg').':';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
    		
	  	      $row = $oDB->fetchAssoc();
	  	      $cod_local = $row['id_local']; 
			  
    		   /*
    		    * Insere as configura��es para o local informado
    		    */
	  	        $sql_insert_local = "INSERT INTO configuracoes_locais(id_local) VALUES (".$cod_local.")";
			   echo "<br>".InstallAjax::_('kciq_msg inst config local insert', array($cacic_admin['local_sigla']));
			   if (!$oDB->query($sql_insert_local)) {
				  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg config local insert error', array($cacic_admin['local_sigla'])).'</span>'.
						  '<br>'.InstallAjax::_('kciq_msg server msg').':';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
			  
			  
	  	      $sql_insert_admin = "INSERT INTO usuarios 
	  	                                       (id_local, id_usuario, nm_usuario_acesso, nm_usuario_completo, 
	  	                                        te_senha, dt_log_in, id_grupo_usuarios, te_emails_contato, 
	  	                                        te_telefones_contato, te_locais_secundarios) 
	  	                           VALUES (".$cod_local.", 0, '".$cacic_admin['admin_login']."', '".
	  	                                     $adminNome."', PASSWORD('".$cacic_admin['admin_senha']."'), 
	  	                                     NOW(), 2,'".$cacic_admin['admin_email']."', '".
	  	                                     $cacic_admin['admin_fone'].
	  	                                   "',1 )";
	  	                        
			   echo "<br>".InstallAjax::_('kciq_msg inst login insert', array($cacic_admin['admin_login']));
			   if (!$oDB->query($sql_insert_admin)) {
				  $msg = '<span class="Erro">['.InstallAjax::_('kciq_msg error', '',2)."! ] - ";
			      $msg .= InstallAjax::_('kciq_msg login insert error', array($cacic_admin['admin_login'])).'</span>'.
						  '<br>'.InstallAjax::_('kciq_msg server msg').':';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ ".InstallAjax::_('kciq_msg ok')."! ]";
				
			   $msg = '<br><span class="Sim">'.InstallAjax::_('kciq_msg inst admin data succesfuly created').'</span>';
			}
			else { // atualiza dados de local e administrador
			   $msg = "<br>Falta processo para atualizar dados de local e administrador.";
			}
			
			$_SESSION['adminSetupSaved'] = true;
			
        }
        
        echo $msg;
	  	
	  }
 
}
?>
