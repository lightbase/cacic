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

include_once("classes/install.tmpl.php");
include_once("classes/install.ado.php");

/**
 * Prove a metodos para recursos AJAX na Instalação pela WEB
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
	 * Prove a metodos para recursos AJAX na Instalação pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP4
	 */
	 function InstallAjax() {
		$this->__construct();
	 }
	
	/**
	 * Prove a metodos para recursos AJAX na Instalação pela WEB
	 * @internal Construtor para a classe Install compatibilizado com PHP5.
	 */
	 function __construct() {
	 	/*
	 	 * instacie objetos de classes externas se necessário
	 	 */
	 }
	  
	 /**
	  * Processa as requisições AJAX
	  */
	  function processAjax() {
    	if(isset($_POST['cacic_config']))
    	   $_SESSION['cacic_config'] = $_POST['cacic_config'];
    	
    	if(isset($_POST['cacic_admin']))
    	   $_SESSION['cacic_admin'] = $_POST['cacic_admin'];
    	 
     	$task = $_POST['task'];
	  	switch (strtolower($task)) { 
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
	  * Cria arquivo de configuração para o CACIC
	  * @access private
	  */
	  function buildCFGFile($show=true) {
		$cacic_config = $_POST['cacic_config'];
		$oTmpl = new patTemplate();
		$oTmpl->setNamespace('cacicInstall');
		$oTmpl->setRoot('templates');
		$oTmpl->readTemplatesFromInput('install_navbar.tmpl');
		$oTmpl->addVar('tmplCFGFile', 'CACIC_PATH', $cacic_config['path'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_SERVER', $cacic_config['db_host'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_PORT', $cacic_config['db_port'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_NAME', $cacic_config['db_name'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_USER', $cacic_config['db_user'] );
		$oTmpl->addVar('tmplCFGFile', 'DB_USER_PASS', $cacic_config['db_pass'] );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_KEY', CACIC_KEY );
		$oTmpl->addVar('tmplCFGFile', 'CACIC_IV', CACIC_IV );
		
		if($show) {
			$oTmpl->addVar('tmplCFGFileCab', 'show_path', $cacic_config['path'] );
			$oTmpl->displayParsedTemplate('tmplCFGFile');
		}
		else {
			$tmpl = $oTmpl->getParsedTemplate('tmplCFGFile');
			return $tmpl;
		}
	  }
	  
	 /*
	  * Verifica dados de conexão com o banco de dados
	  * @access private
	  */
	  function checkCFGFileData($cacic_config) {
	    
	    $dadosOK = true;
	    $msg = "";
	  	if(empty($cacic_config['path'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Caminho físico da aplicação deve ser informado!</span><br>";
	  	}
	  	else { $path = $cacic_config['path'];
    	  	if(!is_dir($path)) {
    	        $dadosOK = false;
    	        $msg .= "<span class='Erro'>Caminho físico informado não é um diretório!</span><br>";
    	  	}
    	  	else {
        	  	if(!is_readable($path) and !is_executable($path)) {
        	        $dadosOK = false;
        	        $msg .= "<span class='Erro'>Verifique as permissões leitura e execução do caminho físico informado!</span><br>";
        	  	}
    	  	}
	  	}
	  	
	  	if(empty($cacic_config['url'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>URL da aplicação deve ser informada!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_type'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Tipo de banco de dados deve ser informado!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_host'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Servidor de banco de dados deve ser informado!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_port'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Porta no servidor de banco de dados deve ser informada!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_name'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Nome do banco de dados deve ser informado!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['db_user'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Usuário de conexão com o banco de dados deve ser informado!</span><br>";
	  	}
	  	
	  	if(empty($cacic_config['install']['type'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Selecione um dos tipos de instalação!</span><br>";
	  	}
	  	
	  	// Instalação nova
	  	if($cacic_config['install']['type'] == 'createDB') {
	  	    if(empty($cacic_config['db_admin'])) {
    	        $dadosOK = false;
    	        $msg .= '<span class="Erro">Para instalação nova, informe o usuário administrador do banco de dados!</span><br>';
	        }

    		$fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_CREATEDB;
    		if(!is_readable($fileName)) {
    	        $dadosOK = false;
				$msg .= '<span class="Erro">Não há instruções SQL ('.CACIC_SQLFILE_CREATEDB.') para criação das tabelas do banco de dados!</span><br>';
			}
    		$fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_STDDATA;
    		if(!is_readable($fileName)) {
    	        $dadosOK = false;
				$msg .= '<span class="Erro">Não há instruções SQL ('.CACIC_SQLFILE_STDDATA.') referente aos dados base para o CACIC!</span><br>';
			}
    		if($cacic_config['dbdet']['demo'] == 'demo') {
    			$fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_DEMODATA;
    			if(!is_readable($fileName)) {
					$msg .= '<span class="AvisoImg">Não há dados ('.CACIC_SQLFILE_DEMODATA.') disponíveis para demonstração!</span> Porém, o processo de instalação pode prosseguir.<br>';
    			}
    		}		
			
	  	} // Atualização de versão
	  	elseif($cacic_config['install']['type'] == 'atualizar') {
	  	    if(empty($cacic_config['install']['updateFromVersion'])) {
    	        $dadosOK = false;
    	        $msg .= '<span class="Erro">Selecione a versão a ser atualizada!</span><br>';
	        }
	        else {
        		$fileName = CACIC_SQLFILE_PREFIX.strtolower($cacic_config['install']['updateFromVersion']).'.sql';
        		$fileNamePath = $cacic_config['path'].'instalador/sql/'.$fileName;
        		if(!is_readable($fileNamePath)) {
        	        $dadosOK = false;
    				$msg .= '<span class="Erro">Não há instruções SQL ('.$fileName.') para atualização do banco de dados do CACIC!</span><br>';
    			}
			}
	  	}

        echo $msg;
	  	
	  	return $dadosOK;
        
	  }
	 /**
	  * Mostra arquivo de configuração para o CACIC
	  */
	  function showCFGFile($cacic_config) {
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se não conectar para o processo
	 	  die();
	 	  
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if($dadosOK)
	  	  InstallAjax::buildCFGFile(); // dados informados adequadamente
	  }
	  
	 /**
	  * Grava arquivo de configuração para o CACIC
	  */
	  function saveCFGFile($cacic_config) {
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se não conectar para o processo
	 	  die();
	      
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	      die(); // se dados incorretos
	      
		$fileName = $cacic_config['path'].'/include/config.php';
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
     * Caminho da aplicao "CACIC"
     */
    $path_aplicacao = "'.$cacic_config['path'].'";

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
		    $msg .= "<br><span class='OkImg'>O Arquivo foi gravado em: ";
		    $_SESSION['configFileSaved'] = true;
		}
		else
		  $msg .= "<br><span class='Erro'>"."Cheque diretório e permissões! Erro ao tentar gravar o arquivo: ";
		echo $msg . $fileName."</span>";
	  }
	  	
	  /**
	   * Verifica conexao com o banco de dados
	   */
	  function checkDBConnection($cacic_config) {
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	      die(); // se dados incorretos
	    
     	$connOk = true;
     	$oDB = new ADO($cacic_config['db_type']);
		$msg = "[ OK! ] - Teste de conexão realizado com sucesso!<span class='OkImg'></span>";
		if($cacic_config['install']['type'] == 'createDB') {// instalação nova
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_admin'], 
		                  $cacic_config['db_admin_pass'], $cacic_config['db_name'] );
		}
		else // atualização da base
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], 
		                  $cacic_config['db_pass'], $cacic_config['db_name'] );
		    
		if (!$oDB->conecta()) {
			$msg = '<span class="Erro">'."[ ERRO! ] - ";
		    $msg .= 'Erro de conexão ao servidor do banco de dados!</span>'.
					'<br>Mensagem do servidor:';
			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
			$connOk = false;
		}
		
		if($connOk) {
			$versao = $oDB->version();
			if(!(version_compare($versao,CACIC_DBVERSION,'>='))) {
			  $connOk = false;
			  $msg .= '<br><span class="Erro">Versão do Servidor MySQL-Server inválida!</span>';
			  $msg .= '<br>Requerida: <span class="Aviso">'.CACIC_DBVERSION.'</span>';
			  $msg .= '<br>Instalada: <span class="Nao">'.$versao.'</span>';
			}
		}
		echo $msg;
		return $connOk;  
	  }
	  
	 /*
	  * Verifica conexão com o banco de dados
	  */
	 function buildDB($cacic_config) {
	    $builDBOK = false;
     	$dadosOK = InstallAjax::checkCFGFileData($cacic_config);
	    if(!$dadosOK)
	      die(); // se dados incorretos
	      
	 	$connOk = InstallAjax::checkDBConnection($cacic_config);
	 	if(!$connOk) // Se não conectar para o processo
	 	  die();
	 		
     	$cacic_dbdet = $cacic_config['dbdet'];
     	$installType = $cacic_config['install']['type'];
     	
		/*
		 * processo de criação do banco e tabelas
		 */
		
		/*
		 * Conexao ao banco de dados
		 */		     	
		echo "<br>Conectando ao servidor de banco de dados... ";
     	$oDB = new ADO($cacic_config['db_type']);
		if($installType == 'createDB') {// instalação nova
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_admin'], 
		                  $cacic_config['db_admin_pass'], $cacic_config['db_name'] );
		    $oDB->addDBUser($cacic_config['db_user'], $cacic_config['db_pass']);
		}
		else // atualização da base
		    $oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], 
		                  $cacic_config['db_pass'], $cacic_config['db_name'] );
		                  
		if (!$oDB->conecta()) {
			$msg = '<span class="Erro">'."[ ERRO! ] - ";
		    $msg .= 'Erro de conexão ao servidor do banco de dados!</span>'.
					'<br>Mensagem do servidor:';
			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
		    die($msg);
		}
		else
			echo "[ OK! ]";

		if($installType == 'createDB') {// instalação nova
         	$oDB_result = $oDB->addDBUser($cacic_config['db_user'], $cacic_config['db_pass']);
         	echo "<br>Concedendo permissões ao usuário (" .$cacic_config['db_user']. ") no servidor de banco de dados... ";
    		if (!$oDB_result) {
    			$msg = '<span class="Erro">'."[ ERRO! ] - ";
    		    $msg .= 'Erro ao tentar inserir o usuário ('.$cacic_config['db_user'].')!</span>'.
    					'<br>Mensagem do servidor:';
    			$msg .= '<pre>'.$oDB->getMessage().'</pre>';
    		    die($msg);
    		}
    		else
    			echo "[ OK! ]";
		}
			
		/*
		 * Cria banco de dados
		 */
		if($installType == 'createDB') {
			echo "<br>Criando o banco [".$cacic_config['db_name']."]... ";
			if (!$oDB->selectDB($cacic_config['db_name'])) {
				if (!$oDB->createDB()) {
					$msg = '<span class="Erro">'."[ ERRO! ] - ";
				    $msg .= 'Erro na criação do banco de dados!</span>'.
							'<br>Mensagem do servidor:';
					$msg .= '<pre>'.$oDB->getMessage().'</pre>';
				    die($msg);
				}
				else
					echo "[ OK! ]";
			}
			else {
				$msg = '<span class="Erro">'."[ ERRO! ] - ";
			    $msg .= 'Erro o banco de dados já existe!</span>';
			    die($msg);
			}
		}
		
		if (!$oDB->selectDB()) {
			$msg = '<span class="Erro">'."[ ERRO! ] - ";
		    $msg .= 'Banco de dados não existe!</span>'.
					'<br>Mensagem do servidor:';
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
		
			   echo "<br>Criando as tabelas no banco [".$cacic_config['db_name']."]... ";
	     	   $oDB_result = $oDB->parse_mysql_dump($cacic_sql_create_tables);
			   if (!$oDB_result) {
				   $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Erro na criação das tabelas do banco de dados!</span>'.
						   '<br>Mensagem do servidor:';
				   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				   echo "[ OK! ]";
		   }
		   else {
				   $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Não há instruções SQL para criação das tabelas do banco de dados!</span>';
			      die($msg);
			}
		}
		else {
		   /*
		    * Atualiza as tabelas do banco de dados
		    */  
		   $fileName = $cacic_config['path'].'instalador/sql/cacic_'.strtolower($cacic_config['install']['updateFromVersion']).'.sql';
		   if(is_readable($fileName)) {
   			$cacic_sql_create_tables = $fileName;
		
			   echo "<br>Atualizando as tabelas no banco [".$cacic_config['db_name']."]... ";
	     	   $oDB_result = $oDB->parse_mysql_dump($cacic_sql_create_tables);
			   if (!$oDB_result) {
				   $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Erro na atualização das tabelas do banco de dados!</span>'.
						   '<br>Mensagem do servidor:';
				   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				   echo "[ OK! ]";
		   }
		   else {
				   $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Não há instruções SQL para atualização das tabelas do banco de dados!</span>';
			      die($msg);
			}
		}
					  
		if($installType == 'createDB') {
		   /*
		    * Inclui dados básicos para CACIC
		    */
		   $fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_STDDATA;
		   if(is_readable($fileName)) {
			 $cacic_sql_dadosbase = $fileName;
			 echo "<br>Incluindo dados básicos nas tabelas do banco [".$cacic_config['db_name']."]... ";
	     	 $oDB_result = $oDB->parse_mysql_dump($cacic_sql_dadosbase);
			 if (!$oDB_result) {
				$msg = '<span class="Erro">'."[ ERRO! ] - ";
			    $msg .= 'Erro na inserção de dados base nas tabelas do banco de dados!</span>'.
						'<br>Mensagem do servidor:';
				$msg .= '<pre>'.$oDB->getMessage().'</pre>';
			    die($msg);
			 }
			 else
				echo "[ OK! ]";
		   }
		   else {
				$msg = '<span class="Erro">'."[ ERRO! ] - ";
			    $msg .= 'Não há instruções SQL para inserção de dados base nas tabelas do banco de dados!</span>';
			    die($msg);
		   }
					  
		   /*
		    * Inclui dados nas tabelas para demonstração do cacic
		    */			  
		   if($cacic_dbdet['demo'] == 'demo') {
			 echo "<br>Inclui de dados para demonstração [".$cacic_config['db_name']."]... ";
			 $fileName = $cacic_config['path'].'instalador/sql/'.CACIC_SQLFILE_DEMODATA;
			 if(is_readable($fileName)) {
				$cacic_sql_demonstracao = $fileName;
				$oDB_result = $oDB->parse_mysql_dump($cacic_sql_demonstracao);
				if (!$oDB_result) {
					$msg = '<span class="Erro">'."[ ERRO! ] - ";
				    $msg .= 'Erro na inclusão de dados para demonstração!</span>'.
							'<br>Mensagem do servidor:';
					$msg .= '<pre>'.$oDB->getMessage().'</pre>';
				    die($msg);
				}
				else
					echo "[ OK! ]";
			 }
			 else {
					$msg = '<span class="Erro">'."[ ERRO! ] - ";
				    $msg .= 'Não há dados disponíveis para demonstração!</span>';
				    die($msg);
			 }
		  }
		}		
		echo "<br><span class='OkImg'>Processo de construção do banco de dados [".$cacic_config['db_name']."] finalizado com sucesso!</span>";
		$_SESSION['buildDBOK'] = true;
	 }
	 
	 /*
	  * Verifica dados de administração para o CACIC
	  * @access private
	  */
	  function checkAdminSetupData($cacic_admin) {
	    $dadosOK = true;
	    $msg = "";
	  	if(empty($cacic_admin['local_sigla'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Sigla do local deve ser informada!</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['local_nome'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Nome do local deve ser informado!</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_login'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Login do administrador deve ser informado!</span><br>";
	  	}
	  	
	  	if($cacic_admin['admin_senha'] != $cacic_admin['admin_senha_check']) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Senhas não são iguais!</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_senha']) or empty($cacic_admin['admin_senha_check'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Senhas devem ser informadas e confirmadas!</span><br>";
	  	}
	  	
	  	if(empty($cacic_admin['admin_nome'])) {
	        $dadosOK = false;
	        $msg .= "<span class='Erro'>Nome do administrador deve ser informado!</span><br>";
	  	}
	  	
        echo $msg;
	  	
	  	return $dadosOK;
        
	  }
 
	 /*
	  * Salva dados de administração para o CACIC
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
    		echo "<br>Conectando ao servidor de banco de dados... ";
         	$oDB = new ADO($cacic_config['db_type']);
         	$oDB->debug();
    		$oDB->setDsn( $cacic_config['db_host'], $cacic_config['db_user'], $cacic_config['db_pass'], $cacic_config['db_name'] );
    		if (!$oDB->conecta()) {
      		   $msg = '<span class="Erro">'."[ ERRO! ] - ";
    		   $msg .= 'Erro de conexão ao servidor do banco de dados!</span>'.
    					'<br>Mensagem do servidor:';
    		   $msg .= '<pre>'.$oDB->getMessage().'</pre>';
    		   die($msg);
    		}
    		else
    		   echo "[ OK! ]";

    		/*
    		 * Verifica banco de dados
    		 */
			echo "<br>Verificando existência do banco de dados [".$cacic_config['db_name']."]... ";
			if (!$oDB->selectDB()) {
			  $msg = '<span class="Erro">'."[ ERRO! ] - ";
			  $msg .= 'Banco de dados não exite!</span>'.
					  '<br>Mensagem do servidor:';
			  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			  die($msg);
			}
		    else
			  echo "[ OK! ]";
    		
	       if ($cacic_config['install']['type'] == 'createDB') { // Cria dados de local e administrador
    		   /*
    		    * Verifica a não existência do local informado
    		    */
    		   $localOK = true;
	  	       $sql_get_local_id = "select id_local from locais where sg_local = '".$cacic_admin['local_sigla']."'";
			   $msg ="<br>Verificando local [".$cacic_admin['local_sigla']."]... ";
			   $oDB->query($sql_get_local_id);
			   if ($oDB->numRows() > 0) {
				  $msg .= '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Local já está cadastrado!</span>';
			      $localOK = false;
			   }
			   else
				  $msg .= "[ OK! ]";
			
    		   /*
    		    * Verifica a não existência do administrador informado
    		    */
    		   $adminOK = true;
	  	       $sql_check_admin = "select nm_usuario_acesso from usuarios where nm_usuario_acesso = '".$cacic_admin['admin_login']."'";
			   $msg .= "<br>Verificando administrador [".$cacic_admin['admin_login']."]... ";
			   $oDB->query($sql_check_admin);
			   if ($oDB->numRows() > 0) {
				  $msg .= '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Login para administrador já está cadastrado!</span>';
			      $adminOK = false;
			   }
			   else
				  $msg .= "[ OK! ]";
			
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
        		  $localObservacao = utf8_decode(trim($cacic_admin['local_nome']));
	  	          $adminNome = utf8_decode(trim($cacic_admin['admin_nome']));
    		   }
    		
    		   /*
    		    * Insere o local informado
    		    */
	  	        $sql_insert_local = "INSERT INTO locais VALUES (0,'".
	  	                            $localNome."','".
	  	                            $cacic_admin['local_sigla']."','".
	  	                            $localObservacao.
	  	                        "')";
			   echo "<br>Inserindo local [".$cacic_admin['local_sigla']."]... ";
			   if (!$oDB->query($sql_insert_local)) {
				  $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Erro ao tentar inserir dados do Local!</span>'.
						  '<br>Mensagem do servidor:';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ OK! ]";
    		
    		   /*
    		    * Busca ID do local recem incluído
    		    */
			   if (!$oDB->query($sql_get_local_id)) {
				  $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Local não encontrado!</span>'.
						  '<br>Mensagem do servidor:';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
    		
	  	      $row = $oDB->fetchAssoc();
	  	      $cod_local = $row['id_local']; 
	  	      $sql_insert_admin = "INSERT INTO usuarios 
	  	                                       (id_local, id_usuario, nm_usuario_acesso, nm_usuario_completo, 
	  	                                        te_senha, dt_log_in, id_grupo_usuarios, te_emails_contato, 
	  	                                        te_telefones_contato) 
	  	                           VALUES (".$cod_local.", 0, '".$cacic_admin['admin_login']."', '".
	  	                                     $adminNome."', PASSWORD('".$cacic_admin['admin_senha']."'), 
	  	                                     NOW(), 2,'".$cacic_admin['admin_email']."', '".
	  	                                     $cacic_admin['admin_fone'].
	  	                                   "' )";
	  	                        
			   echo "<br>Inserindo dados do administrador [".$cacic_admin['admin_login']."]... ";
			   if (!$oDB->query($sql_insert_admin)) {
				  $msg = '<span class="Erro">'."[ ERRO! ] - ";
			      $msg .= 'Erro ao tentar inserir dados do Administrador!</span>'.
						  '<br>Mensagem do servidor:';
				  $msg .= '<pre>'.$oDB->getMessage().'</pre>';
			      die($msg);
			   }
			   else
				  echo "[ OK! ]";
				
			   $msg = '<br><span class="Sim">Dados administrativos inseridos com sucesso!</span>';
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
