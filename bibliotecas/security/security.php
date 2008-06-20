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
 	function read($var2read, $_POST_GET='') {
 		if(!empty($_POST_GET) and is_array($_POST_GET))
 			$read_var = @$_POST_GET[$var2read];
 		else
 			$read_var = @$_REQUEST[$var2read];
 		
 		/*
 		 * tratar segurança na variavel lida
 		 */

 		return $read_var;
 	} // end func read
 } // end class Security
