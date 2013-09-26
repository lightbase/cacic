<?PHP
/**
 * patTemplate output filter that creates PDF files from latex
 *
 * $Id: PdfLatex.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate output filter that creates PDF files from latex
 *
 * $Id: PdfLatex.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 *
 * @package		patTemplate
 * @subpackage	Filters
 * @author		Stephan Schmidt <schst@php.net>
 */
class patTemplate_OutputFilter_PdfLatex extends patTemplate_OutputFilter
{
   /**
    * filter name
	*
	* This has to be set in the final
	* filter classes.
	*
	* @var	string
	*/
	var	$_name	=	'PdfLatex';

	var $_params = array(
	                       'cacheFolder' => './'
	                   );

   /**
	* tidy the data
	*
	* @access	public
	* @param	string		data
	* @return	string		compressed data
	*/
	function apply( $data )
	{
	    $cacheFolder = $this->getParam('cacheFolder');
	    $texFile     = tempnam($cacheFolder, 'pt_tex_') . '.tex';
	    $fp = fopen($texFile, 'w');
	    fwrite($fp, $data);
	    fclose($fp);

	    $command = 'pdflatex --interaction=nonstopmode '.$texFile;
	    exec($command);
	    exec($command);

	    $pdf = $texFile . '.pdf';
	    $pdf = file_get_contents($pdf);

	    return $pdf;
	}
}
?>