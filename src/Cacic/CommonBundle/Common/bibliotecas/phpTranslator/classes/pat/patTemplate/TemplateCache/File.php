<?PHP
/**
 * patTemplate Template cache that stores data on filesystem
 *
 * $Id: File.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @package		patTemplate
 * @subpackage	Caches
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate Template cache that stores data on filesystem
 *
 * $Id: File.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * Possible parameters for the cache are:
 * - cacheFolder : set the folder from which to load the cache
 * - lifetime : seconds for which the cache is valid, if set to auto, it will check
 *   whether the cache is older than the original file (if the reader supports this)
 *
 * @package		patTemplate
 * @subpackage	Caches
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_TemplateCache_File extends patTemplate_TemplateCache
{
   /**
	* parameters of the cache
	*
	* @access	private
	* @var		array
	*/
	var $_params = array(
						 'cacheFolder' => './cache',
						 'lifetime'	   => 'auto'
						);


   /**
	* load template from cache
	*
	* @access	public
	* @param	string			cache key
	* @param	integer			modification time of original template
	* @return	array|boolean	either an array containing the templates or false cache could not be loaded
	*/
	function load( $key, $modTime = -1 )
	{
		$filename = $this->_getCachefileName( $key );
		if( !file_exists( $filename ) || !is_readable( $filename ) )
			return false;

		$generatedOn = filemtime( $filename );
		$ttl		 = $this->getParam( 'lifetime' );
		if( $ttl == 'auto' )
		{
			if( $modTime < 1 )
				return false;
			if( $modTime > $generatedOn )
				return false;
			return unserialize( file_get_contents( $filename ) );
		}
		elseif( is_int( $ttl ) )
		{
			if( $generatedOn + $ttl < time() )
				return false;
			return unserialize( file_get_contents( $filename ) );
		}
		
		return false;
	}
	
   /**
	* write template to cache
	*
	* @access	public
	* @param	string		cache key
	* @param	array		templates to store
	* @return	boolean		true on success
	*/
	function write( $key, $templates )
	{
		$fp = @fopen( $this->_getCachefileName( $key ), 'w' );
		if( !$fp )
			return false;
		flock( $fp, LOCK_EX );
		fputs( $fp, serialize( $templates ) );
		flock( $fp, LOCK_UN );
		return true;
	}
	
   /**
	* get the cache filename
	*
	* @access	private
	* @param	string		cache key
	* @return	string		cache file name
	*/
	function _getCachefileName( $key )
	{
		return $this->getParam( 'cacheFolder' ) . '/' . $key . '.cache';
	}
}
?>