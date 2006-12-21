<?
/*
To draw the PNG - call this script with a URL like the following:

http://www.yoursite.com/text_png.php3?msg=testing+class&rot=15&size=48&font=fonts/ARIAL.TTF
*/
require_once('text2png_class.php');
require_once('library.php');
$text = new textPNG;
if (isset($_GET['msg'])) $text->msg = $_GET['msg']; // text to display
if (isset($_GET['font'])) $text->font = 'fonts/times.ttf'; // font to use (include directory if needed).
if (isset($_GET['size'])) $text->size = $_GET['size']; // size in points
if (isset($_GET['rot'])) $text->rot = $_GET['rot']; // rotation
if (isset($_GET['pad'])) $text->pad = $_GET['pad']; // padding in pixels around text.
if (isset($red)) $text->red = $red; // text color
if (isset($grn)) $text->grn = $grn; // ..
if (isset($blu)) $text->blu = $blu; // ..
if (isset($bg_red)) $text->bg_red = $bg_red; // background color.
if (isset($bg_grn)) $text->bg_grn = $bg_grn; // ..
if (isset($bg_blu)) $text->bg_blu = $bg_blu; // ..
if (isset($_GET['tr'])) $text->transparent = $_GET['tr']; // transparency flag (boolean).
LimpaTESTES();
GravaTESTES('Antes do header');
header("Content-type: image/png");		
GravaTESTES('Depois do header');
GravaTESTES('Antes do DRAW');
$text->draw();
GravaTESTES('Depois do DRAW');
?>