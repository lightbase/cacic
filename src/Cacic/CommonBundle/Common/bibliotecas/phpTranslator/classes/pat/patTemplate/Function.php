<?PHP
/**
 * Base class for patTemplate functions
 *
 * $Id: Function.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * Base class for patTemplate functions
 *
 * $Id: Function.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @abstract
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_Function extends patTemplate_Module
{
   /**
	* reader object
	*
	* @access private
	* @var	  object
	*/
	var $_reader;
	
   /**
	* set the reference to the reader object
	*
	* @access	public
	* @param	object patTemplate_Reader
	*/ 
	function setReader( &$reader )
	{
		$this->_reader = &$reader;
	}

   /**
	* call the function
	*
	* @abstract
	* @access	public
	* @param	array	parameters of the function (= attributes of the tag)
	* @param	string	content of the tag
	* @return	string	content to insert into the template
	*/ 
	function call( $params, $content )
	{
	}
}
?>