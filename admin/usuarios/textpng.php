<?
/*
To draw the PNG - call this script with a URL like the following:

http://www.yoursite.com/text_png.php3?msg=testing+class&rot=15&size=48&font=fonts/ARIAL.TTF
*/
require_once('../../include/library.php');
function draw(	$p_msg="undefined",
				$p_font="times.ttf",
				$p_size=10,
				$p_rot=-90,
				$p_pad=0,
				$p_transparent=1,
				$p_red=0,
				$p_grn=0,
				$p_blu=0,
				$p_bg_red=255,
				$p_bg_grn=255,
				$p_bg_blu=255) 
	{
	GravaTESTES('1 - Entrei em DRAW - p_msg='.$p_msg);	
	//$font 			= 'times.ttf'; //default font. directory relative to script directory.
	//$msg 			= "undefined"; // default text to display.
	//$size 			= 10;
	//$rot 			= -90; // rotation in degrees.
	//$pad 			= 0; // padding.
	//$transparent 	= 1; // transparency set to on.
	//$red 			= 0; // white text...
	//$grn 			= 0;
	//$blu 			= 0;
	//$bg_red 		= 255; // on black background.
	//$bg_grn 		= 255;
	//$bg_blu 		= 255;
	$width 			= 0;
	$height 		= 0;
	$offset_x 		= 0;
	$offset_y 		= 0;
	$bounds 		= array();
	$image 			= "";
	
	// determine font height.
	header("Content-type: image/png");
	$path = '../../admin/usuarios/TIMES.TTF';
	$bounds = imagettfbbox($p_size, $p_rot, $path, "W");
	GravaTESTES('2 - Caminho = '.$path);		
	GravaTESTES('2 - ImageTTFBBox 1...');	

	if ($p_rot < 0) 
		{
		$font_height = abs($bounds[7]-$bounds[1]);		
		} 
	else if ($p_rot > 0) 
		{
		$font_height = abs($bounds[1]-$bounds[7]);
		} 
	else 
		{
		$font_height = abs($bounds[7]-$bounds[1]);
		}

	// determine bounding box.
	$bounds = imagettfbbox($p_size, $p_rot, $path, $p_msg);
	GravaTESTES('3 - ImageTTFBBox 2...');
		
	if ($p_rot < 0) 
		{
		$width = abs($bounds[4]-$bounds[0]);
		$height = abs($bounds[3]-$bounds[7]);
		$offset_y = $font_height;
		$offset_x = 0;		
		} 
	else if ($p_rot > 0) 
		{
		$width = abs($bounds[2]-$bounds[6]);
		$height = abs($bounds[1]-$bounds[5]);
		$offset_y = abs($bounds[7]-$bounds[5])+$font_height;
		$offset_x = abs($bounds[0]-$bounds[6]);		
		} 
	else 
		{
		$width = abs($bounds[4]-$bounds[6]);
		$height = abs($bounds[7]-$bounds[1]);
		$offset_y = $font_height;;
		$offset_x = 0;
		}
	
	$image = imagecreate($width+($p_pad*2)+1,$height+($p_pad*2)+1);
	GravaTESTES('4 - ImageCreate...');
			
	$background = imagecolorallocate($image, $p_bg_red, $p_bg_grn, $p_bg_blu);
	GravaTESTES('5 - ImageColorAllocate 1...');
				
	$foreground = imagecolorallocate($image, $p_red, $p_grn, $p_blu);
	GravaTESTES('6 - ImageColorAllocate 2...');
					
	if ($p_transparent) imagecolortransparent($image, $background);
	GravaTESTES('7 - ImageColorTransparent...');
					
	imageinterlace($image, false);
	GravaTESTES('8 - ImageInterlace...');
						
	// render it.
	imagettftext($image, $p_size, $p_rot, $offset_x+$p_pad, $offset_y+$p_pad, $foreground, "times.ttf", $p_msg);
	GravaTESTES('9 - ImageTTFText...');
							

	GravaTESTES('10 - header...');
							
	// output PNG object.
	imagepng($image);
	GravaTESTES('11 - ImagePNG...');
							
	// Destroying PNG object.
//	ImageDestroy($image);
//	GravaTESTES('12 - ImageDestroy...');								
	return TRUE;
	}
	
draw($_GET['msg'],
	 $_GET['font'],
	 $_GET['size'],
	 $_GET['rot'],
	 $_GET['pad'],
	 $_GET['transparent'],
	 $_GET['red'],
	 $_GET['grn'],
	 $_GET['blu'],
	 $_GET['bg_red'],
	 $_GET['bg_grn'],
	 $_GET['bg_blu']);
?>