<?PHP
/**
 * patTemplate function that highlights PHP code in your templates
 *
 * $Id: Phphighlight.php,v 1.1 2004/05/18 20:10:45 schst Exp $
 *
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate function that highlights PHP code in your templates
 *
 * $Id: Phphighlight.php,v 1.1 2004/05/18 20:10:45 schst Exp $
 *
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_Function_Phphighlight extends patTemplate_Function
{
   /**
	* name of the function
	* @access	private
	* @var		string
	*/
	var $_name	=	'Phphighlight';

   /**
	* call the function
	*
	* @access	public
	* @param	array	parameters of the function (= attributes of the tag)
	* @param	string	content of the tag
	* @return	string	content to insert into the template
	*/ 
	function call( $params, $content )
	{
		ob_start();
		highlight_string( $content );
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}
?>