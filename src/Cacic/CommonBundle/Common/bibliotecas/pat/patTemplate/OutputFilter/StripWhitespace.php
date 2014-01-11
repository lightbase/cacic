<?PHP
/**
 * patTemplate StripWhitespace output filter
 *
 * $Id: StripWhitespace.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * Will remove all whitespace and replace it with a single space.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate StripWhitespace output filter
 *
 * $Id: StripWhitespace.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * Will remove all whitespace and replace it with a single space.
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_OutputFilter_StripWhitespace extends patTemplate_OutputFilter
{
   /**
    * filter name
	*
	* @access	protected
	* @var	string
	*/
	var	$_name	=	'StripWhitespace';

   /**
	* remove all whitespace from the output
	*
	* @access	public
	* @param	string		data
	* @return	string		data without whitespace
	*/
	function apply( $data )
	{
		$data = str_replace("\n", ' ', $data);
		$data = str_replace("\r", ' ', $data);
		$data = str_replace("\t", ' ', $data);
        $data = preg_replace('/  +/', ' ', $data);
		return $data;
	}
}
?>