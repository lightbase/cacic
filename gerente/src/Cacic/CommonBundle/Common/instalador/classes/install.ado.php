<?php
/**
 * @version $Id: install.ado.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
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

class Ftp {
	var $conn;
	var $server = 'localhost';
	var $port = '21';
	var $timeout = 20; // tempo em segundos
	var $user;
	var $user_pass;
	var $subdir = "/";
	
	var $error = "";
	var $message = "";
	
	function Ftp($_server="",$_port="",$_user="",$_user_pass="",$_subdir="", $_timeout = 0) {
		if(!empty($_server)) $this->server = $_server;
		if(!empty($_port)) $this->port = $_port;
		if(!empty($_timeout)) $this->timeout = $_timeout;
		if(!empty($_subdir)) $this->subdir = $_subdir;
		$this->user = $_user;
		$this->user_pass = $_user_pass;
		$this->conecta();
	}
	
	function conecta($_server="",$_port="", $_timeout = 05) {
		$this->error = false;
		if(!empty($_server)) $this->server = $_server;
		if(!empty($_port)) $this->port = $_port;
		if(!empty($_timeout)) $this->timeout = $_timeout;
		// Cria a conexгo
		$this->conn = ftp_connect($this->server, $this->port, $this->timeout);
		// confere a conexгo
		if ((!$this->conn)) {
			$this->error = true;
			$this->message = "Conexгo ao servidor (".$this->server.":".$this->port.") FTP falhou!";
		}
		return !$this->error;
	}
	
	function login($_user="",$_user_pass="") {
		$this->error = false;
		if(!empty($_user)) $this->user = $_user;
		if(!empty($_user_pass)) $this->user_pass = $_user_pass;
		// login com o nome de usuбrio e senha
		$_result = ftp_login($this->conn, $this->user, $this->user_pass);
		// confere a conexгo
		if ((!$_result)) {
			$this->error = true;
			$this->message = "Login no FTP falhou! Verifique usuбrio ou senha.";
		}
		return !$this->error; 
	}
	
	function upload($_arquivo,$_subdir="") {
		$this->error = false;
		if(!empty($_subdir)) $this->subdir = $_subdir;
		// upload de arquivo
		$_result = ftp_put($this->conn, $this->subdir, $_arquivo, FTP_BINARY);
		// verifica o upload do arquivo
		if (!$_result) {
			$this->error = true;
			$this->message = "Upload de arquivo ($_arquivo) no FTP falhou!";
		}
		return !$this->error; 
	}
	
	function changeDir($_subdir="/") {
		$this->error = false;
		if(!empty($_subdir)) $this->subdir = $_subdir;
		// upload de arquivo
		$_result = ftp_chdir($this->conn, $this->subdir);
		// verifica o a mudanзa de diretуrio
		if (!$_result) {
			$this->error = true;
			$this->message = "Mudanзa de diretуrio (".$this->subdir.") no FTP falhou!";
		}
		return !$this->error; 
	}
	
	function close() {
		// fecha a conexгo FTP
		return ftp_close($this->conn);
	}
	
	function isError() {
		return $this->error;
	}
	
	function getMessage() {
		return $this->message;
	}
	
}

class Mysql {
	
}

class ADO {

  var $db_server;
  var $db_type = "mysql";
  var $db_user;
  var $db_user_pass;
  var $db_name;

  var $conn;
  var $error;
  var $message;
  var $queryResult;
  var $insertResult;
  var $numRows = 100;
  var $debug = false;
  
  /**
   * Objeto para abstraзгo do banco de dados
   * @param String Type - Tipo de servidor do banco de dados
   * @example mysql conecta ao servidor de banco de dados MySQL (somente, por enquanto)
   */
  function ADO( $type = "mysql", $debug=false ) {
  	$this->db_type = $type;
  	$this->debug = $debug;
  }

  function debug( $debug=true ) {
  	$this->debug = $debug;
  }

  function setDsn( $db_server, $db_user, $db_user_pass, $db_name ) {
  	$this->db_server = $db_server;
  	$this->db_name = $db_name;
  	$this->db_user = $db_user;
  	$this->db_user_pass = $db_user_pass;
  }
  
  /**
   * 
   */
  function getMessage() {
    return "Error: [".$this->error."] -".$this->message;
  }
  
  function getUserInfo() {
  	return $this->getMessage();
  } 
   
  function conecta( $db_server="", $db_user="", $db_user_pass="", $db_name="" ) {
  	if($db_server) $this->db_server = $db_server;
  	if($db_name) $this->db_name = $db_name;
  	if($db_user) $this->db_user = $db_user;
  	if($db_user_pass) $this->db_user_pass = $db_user_pass;
  	if($this->debug)
  	  $this->conn = mysql_connect($this->db_server, $this->db_user, $this->db_user_pass );
  	else
      $this->conn = @mysql_connect($this->db_server, $this->db_user, $this->db_user_pass );
    $this->error = mysql_errno();
    $this->message = mysql_error();
    if (!$this->conn) 
       return false; // "Erro de conexгo com o banco dados!
    else
       return true; // Conectou
  }

  function checkConn() {
     if (!$this->conn) 
       return false; // "Erro de conexгo com o banco dados!
     else
       return true; // Conectou
  }

  function query($sql) {
  	if($this->debug)
      $this->queryResult = mysql_query($sql, $this->conn);
  	else
      $this->queryResult = @mysql_query($sql, $this->conn);
    $this->error = mysql_errno();
    $this->message = mysql_error();
    
   if($this->debug)
     $this->message .= " - SQL: [".$sql."]";
    
   return $this->queryResult;
  }
  
  function selectDB() {
  	if($this->debug)
      $db_selected = mysql_select_db($this->db_name, $this->conn);
    else
      $db_selected = @mysql_select_db($this->db_name, $this->conn);
    $this->error = mysql_errno();
    $this->message = mysql_error();
    return $db_selected;
  }
  
  function createDB($db_name="") {
  	if(!$db_name) 
  	  $db_name = $this->db_name;
  	$sql = 'CREATE DATABASE '.$db_name;
    $this->query($sql);
    return $this->queryResult;
  }
  
  function dumpDB() {
	/*
	 * $backupFile = $dbname . date("Y-m-d-H-i-s") . '.gz';
	 * $command = "mysqldump --opt -h $dbhost -u $dbuser -p $dbpass $dbname | gzip > $backupFile";
	 * system($command);
	 */
	$sys_result = ''; //system($command);
	return $sys_result;
  }
  
  function insertQuery($sql) {
    $this->insertResult = query($sql);
    return $this->insertResult;
  }
  
  function getQueryResult() {
    return $this->queryResult;
  }
  
  function numRows() {
  	if($this->debug)
  	  $numRows = mysql_num_rows($this->queryResult);
  	else
  	  $numRows = @mysql_num_rows($this->queryResult);
    $this->error = mysql_errno();
    $this->message = mysql_error();
    return $numRows;
  }
  
  function fetchAssoc() {
  	if($this->debug)
  	  $rows = mysql_fetch_assoc($this->queryResult);
  	else
  	  $rows = @mysql_fetch_assoc($this->queryResult);
    $this->error = mysql_errno();
    $this->message = mysql_error();
    return $rows;
  }
  
  /**
   * 
   */
  function setNumRows($num) {
	$this->numRows = $num;
  }

  /**
   * 
   */
  function close() {
  	if($this->debug)
	  mysql_close($this->conn);
	else
	  @mysql_close($this->conn);
    $this->error = mysql_errno();
    $this->message = mysql_error();
  }
  
  /**
   * 
   */
  function version() {
  	if($this->debug)
  	  $version = mysql_get_server_info();
  	else
  	  $version = @mysql_get_server_info();
    $this->error = mysql_errno();
    $this->message = mysql_error();
    return $version;
  }
  
  /**
   * Adicao de usuario no banco de dados
   */
  function addDBUser($db_user, $db_user_pass) {
    $sql_grant_user_access = "grant all on ".
                                $this->db_name.".* to '".
                                $db_user."'@'".$this->db_server.
                                "' identified by '".$db_user_pass."';";
    $this->query($sql_grant_user_access);
    return $this->queryResult;
  }

  function parse_mysql_dump($url, $ignoreerrors = false) {
   $file_content = file($url);
   $query = "";
   foreach($file_content as $sql_line) {
     $tsl = trim($sql_line);
     if (($sql_line != "") && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != "#")) {
       $query .= $sql_line;
       if(preg_match("/;\s*$/", $sql_line)) {
         $query = str_replace(";", "", "$query");
         $result = $this->query($query);
         if (!$result && !$ignoreerrors) { return $result;};
         $query = "";
       }
     }
   }
   return true;
  }  
}
?>