<?PHP
/**
 * patTemplate modifier that quotes LaTeX special chars
 *
 * $Id: QuoteLatex.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate modifier that quotes LaTeX special chars
 *
 * This is useful when creating PDF documents with patTemplate
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 * @link		http://www.php.net/manual/en/function.strftime.php
 */
class patTemplate_Modifier_QuoteLatex extends patTemplate_Modifier
{
    /**
     *
     *
     */
    var $_chars = array(
                        '%' => '\%',
                        '&' => '\&',
                        '_' => '\_',
                        '$' => '\$'
                    );
    
   /**
	* modify the value
	*
	* @access	public
	* @param	string		value
	* @return	string		modified value
	*/
	function modify( $value, $params = array() )
	{
	    return strtr($value, $this->_chars);
	}
}
?>