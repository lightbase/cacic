<?
require 'library.php';
class textPNG 
	{
	var $font = 'fonts/times.ttf'; //default font. directory relative to script directory.
	var $msg = "undefineds"; // default text to display.
	var $size = 10;
	var $rot = 0; // rotation in degrees.
	var $pad = 0; // padding.
	var $transparent = 1; // transparency set to on.
	var $red = 0; // white text...
	var $grn = 0;
	var $blu = 0;
	var $bg_red = 255; // on black background.
	var $bg_grn = 255;
	var $bg_blu = 255;

	function draw() 
		{
		$width = 0;
		$height = 0;
		$offset_x = 0;
		$offset_y = 0;
		$bounds = array();
		$image = "";

		// determine font height.
		$bounds = ImageTTFBBox($this->size, $this->rot, $this->font, "W");

		if ($this->rot < 0) 
			{
			$font_height = abs($bounds[7]-$bounds[1]);		
			} 
		else if ($this->rot > 0) 
			{
			$font_height = abs($bounds[1]-$bounds[7]);
			} 
		else 
			{
			$font_height = abs($bounds[7]-$bounds[1]);
			}

		// determine bounding box.
		$bounds = ImageTTFBBox($this->size, $this->rot, $this->font, $this->msg);

		if ($this->rot < 0) 
			{
			$width = abs($bounds[4]-$bounds[0]);
			$height = abs($bounds[3]-$bounds[7]);
			$offset_y = $font_height;
			$offset_x = 0;			
			} 
		else if ($this->rot > 0) 
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

		$image = ImageCreate($width+($this->pad*2)+1,$height+($this->pad*2)+1);

		$background = ImageColorAllocate($this->$image, $this->bg_red, $this->bg_grn, $this->bg_blu);

		$foreground = ImageColorAllocate($this->$image, $this->red, $this->grn, $this->blu);

		if ($this->transparent) ImageColorTransparent($this->$image, $background);

		ImageInterlace($this->$image, false);

		// render it.
		ImageTTFText($this->$image, $this->size, $this->rot, $offset_x+$this->pad, $offset_y+$this->pad, $foreground, $this->font, $this->msg);

		// output PNG object.
		ImagePNG($this->$image);

		ImageDestroy($this->$image);

		}
	}
?>	