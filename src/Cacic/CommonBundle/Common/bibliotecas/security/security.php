<?php
/**
 * @version $Id: security.php 2008-06-20 22:20 harpiain $
 * @package Security
 * @subpackage n.a.
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC-Integration is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * @todo  The package TODO/Roadmap are: {@example TODO}
 */

// direct access is denied
defined( 'SECURITY' ) or die( 'Acesso restrito (Restricted access)!' );

 /**
  * Classe para tratar dados de formulario de forma segura
  */
 class Security {
 	
 	/**
 	 * Read form data and check security on its value
 	 * @access public
 	 * @param key_index $var2read index key on POST or GET vars
 	 * @param array $_POST_GET array data to read value on
 	 * @return mixed the required variable
 	 */
 	function read($_var2read, $_POST_GET='') {
 		if(!empty($_POST_GET) and is_array($_POST_GET))
 			$read_var = @$_POST_GET[$_var2read];
 		else
 			$read_var = @$_REQUEST[$_var2read];
 		
 		/*
 		 * tratar segurança na variavel lida
 		 */

 		return $read_var;
 	} // end func read
 	
 	/**
 	 * Read integer value from form data
 	 * @access public
 	 * @param key_index $var2read index key on POST or GET vars
 	 * @return int a typecasted integer value
 	 */
 	function getInt($_var2read) {
 		$int = (int)(Security::read($_var2read));
 		return $int;
 	} // end func getInt
 	
 	/**
 	 * Read float value from form data
 	 * @access public
 	 * @param key_index $var2read index key on POST or GET vars
 	 * @return float a typecasted float value
 	 */
 	function getFloat($_var2read) {
 		$float = (float)(Security::read($_var2read));
 		return $float;
 	} // end func getFloat
 	
 	/**
 	 * Read string value from form data
 	 * @access public
 	 * @param key_index $var2read index key on POST or GET vars
 	 * @return string a typecasted string value
 	 */
 	function getString($_var2read) {
 		$string = (string)(Security::read($_var2read));
 		return $string;
 	} // end func getString :P
 	
 	function getDate($_var2read) {
 		$date = (string)(Security::read($_var2read));
 		// validar data
 		return $date;
 	} // end func getString :P
 	
 	/**
 	 * Read escaped string value from form data to MySQL DBMS
 	 * @access public
 	 * @param key_index $var2read index key on POST or GET vars
 	 * @return string a escaped typecasted string value
 	 */
 	function getStringEscaped($_var2read) {
 		$string = (string)(Security::read($_var2read));
 		// esse função mysql está obsoleta no php-5.3 e será removida no php-6
 		$string = mysql_escape_string($string);
 		return $string;
 	} // end func getStringEscaped
 	
 } // end class Security
