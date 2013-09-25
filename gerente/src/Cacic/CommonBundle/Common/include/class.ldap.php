<?php
/**
 * Handle ldap authentication functionality
 *
 *
 * LICENSE: This source file is subject to version 3.01 of the GPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.html.  If you did not receive a copy of
 * the GPL License and are unable to obtain it through the web, please
 *

 * @category   authentication
 * @package    phpMyFramework
 * @author     Original Author <jason.gerfen@gmail.com>
 * @copyright  2010 Jason Gerfen
 * @license    http://www.gnu.org/licenses/gpl.html  GPL License 3
 * @version    0.1
 */

class openLDAP
	{
	protected static $instance;
	private $handle;
	private $bound;

 	private function __construct($configuration, $username, $password)
 		{
  		if (function_exists('ldap_connect')) 
			{
   			$this->main($configuration);
  			} 
		else 
			{
   			echo 'A extensão LDAP não está disponível ao PHP!!';
   			unset($instance);
   			exit;
  			}
  		$this->main($configuration, $username, $password);
 		}

 	public static function instance($configuration, $username=NULL, $password=NULL)
 		{
  		if (!isset(self::$instance)) 
			{
   			$c = __CLASS__;
   			self::$instance = new self($configuration, $username, $password);
  			}
  		return self::$instance;
 		}

 	private function main($configuration, $username, $password)
 		{
  		$this->handle = $this->connect($configuration);
  		$this->setoptions($this->handle, $configuration);
  		$this->bound = $this->bind($this->handle, $configuration['username'], $configuration['password']);
 		}

 	private function connect($configuration)
 		{
  		return ldap_connect($configuration['servers'], $configuration['port']);
 		}

 	private function setoptions($handle, $configuration)
 		{
  		ldap_set_option($handle, LDAP_OPT_PROTOCOL_VERSION, $configuration['protocol']);
  		ldap_set_option($handle, LDAP_OPT_REFERRALS, $configuration['referrals']);
  		ldap_set_option($handle, LDAP_OPT_TIMELIMIT, $configuration['timelimit']);
  		ldap_set_option($handle, LDAP_OPT_NETWORK_TIMEOUT, $configuration['timeout']);
 		}

 	private function bind($handle, $username, $password)
 		{
  		return ldap_bind($handle, $username, $password);
 		}

 	public function authenticate($configuration, $username, $password)
 		{
  		if ((!empty($username))&&(!empty($password))) 
			{
   			return $this->bind($this->handle, $username, $password);
  			} 
		else 
			{
   			return $this->bind($this->handle, $configuration['username'], $configuration['password']);
  			}
 		}

 	public function search($base, $filter)
 		{
  		return ldap_search($this->handle, $base, $filter);
 		}

 	public function results($data)
 		{
  		return ldap_get_entries($this->handle, $data);
 		}

 	private function errors($handle)
 		{
  		return ldap_error($resource)." => ".ldap_errno($resource);
 		}

 	private function __destruct()
 		{
  		if (isset($this->handle)) 
			{
   			ldap_free_result($this->handle);
   			ldap_unbind($this->handle);
  			}
  		return;
 		}
	}
?>