<?PHP
/**
 * patTemplate modfifier Expression
 *
 * $Id: Expression.php,v 1.1 2004/06/29 21:12:57 schst Exp $
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate modfifier Expression
 *
 * Evaluates an expression and returns one of
 * the defined values for true and false.
 *
 * Possible attributes are:
 * - expression (string)
 * - true (string)
 * - false (string)
 *
 * @package		patTemplate
 * @subpackage	Modifiers
 * @author		Stephan Schmidt <schst@php.net>
 * @link		http://www.php.net/manual/en/function.wordwrap.php
 */
class patTemplate_Modifier_Expression extends patTemplate_Modifier
{
   /**
	* modify the value
	*
	* @access	public
	* @param	string		value
	* @return	string		modified value
	*/
	function modify( $value, $params = array() )
	{
		/*
		 * true and false
		 */
		if( !isset( $params['true'] ) )
			$params['true']	=	'true';
		if( !isset( $params['false'] ) )
			$params['false']=	'false';

        /*
         * replace the value in the expression
         */
        $params['expression'] = str_replace( '$self', "'$value'", $params['expression'] );

        @eval( '$result = '.$params['expression'].';' );
        if( $result === true )
            return $params['true'];
        return $params['false'];
	}
}
?>