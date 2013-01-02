<?
// +----------------------------------------------------------------------+
// | PIE Graph Class                                                      |
// | Creating Pie Graphs on the fly                                       |
// | Requirements: GD Library >= 2.0                                      |
// +----------------------------------------------------------------------+
// | Author: Nico Puhlmann <nico@puhlmann.com>                            |
// +----------------------------------------------------------------------+
// $Id: piegraph.class.php,v 1.5 2004/10/15 18:22:29 npuhlmann Exp $

class PieGraph
{
    /*
		 Public vars
	*/
	var $pie;
    var $data;
    var $legends;
    var $img_width;
    var $img_height;
    var $pie_width;
    var $pie_height;
    var $pie_colors;
    var $pie_color_bg;
    var $pie_color_text;
    var $threedee_height;
	var $colors_set;
	var $pre_colors;

	/**
	* Constructor
	*
	* @param width of the piegraph
	* @param height of the piegraph
	* @param array data for the piegraph
	* @param array legends for the legends //AAP	
	* @param int   TriD for the 3D efect //AAP		
	*/
    function PieGraph($w=500, $h=200, $data, $legends, $TriD=25)
    {
		if(!function_exists("imagecreatetruecolor"))
		{
			die("Error. GD Library >= 2 needed.");
		}
//		$this->starttime = microtime();
//		$this->display_creation_time = false;
		asort($data);
        $this->data 		= array_reverse($data, TRUE);
		$this->pie_width 	= $w;
		$this->pie_height	= $h;
		$this->legends 		= $legends;		
		$this->threedee_height = $TriD;		
		$this->pre_colors	= array("#FF0000",
									"#3399FF",
									"#66CC66",
									"#000000",
									"#CCCC99",
									"#FFCCCC",
									"#9933FF",
									"#CC66CC",
									"#3333FF",
									"#DA3600",
									"#0F84D4",
									"#F9A308",
									"#62D038",
									"#FE670F",
									"#2C9232",
									"#7F0B80",
									"#DFDE29",
									"#9F9F9F",
									"#EDEDED",
									"#BAE700");
		$this->init();
        $this->pie = @ImageCreateTrueColor($this->init_img_width, $this->init_img_height);
        $colBG = ImageColorAllocate($this->pie, $this->pie_color_bg[0], $this->pie_color_bg[1], $this->pie_color_bg[2]);
        ImageFill($this->pie, 0, 0, $colBG);
		$TotalArrayValues = array_sum($this->data);		
				
		// The Legends
		$cellpadding=5;
		$max_str=0;
		$items=0;
//		$FontNumber = 5;						
		$FontNumber = 3;				
		foreach($this->legends as $k => $legend)
		{
		$percent = round($legend/$this->total*100, 2);
		$text = $this->legends[$k]  . ': ' . str_pad($legend,6,' ',STR_PAD_LEFT) . ' ('.str_pad(number_format(($legend / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)';
		
		if(strlen($text) > $max_str)
		{
			$max_str = strlen($text);
		}
		$items++;
		}
//		$box_with   = ImageFontHeight($FontNumber);
//		$box_height = ImageFontHeight($FontNumber);
//		$leg_height = ((ImageFontHeight($FontNumber)+10) * $items)   + ($cellpadding * 2);
//		$leg_width  = (ImageFontWidth($FontNumber)  * ($max_str)) + ($cellpadding * 2);// +($box_with * 2);

		$leg_height = (((ImageFontHeight($FontNumber)+10) * $items)   + ($cellpadding * 35))/2;
		$leg_width  = ((ImageFontWidth($FontNumber)  * ($max_str)) + ($cellpadding * 35))/2;// +($box_with * 2);

		$leg_img = ImageCreateTrueColor($leg_width, $leg_height);
		ImageFill($leg_img, 0, 0, $colBG);

		// text color
		$colTEXT   = ImageColorAllocate($leg_img, $this->pie_color_text[0], $this->pie_color_text[1], $this->pie_color_text[2]);
		// text / legends backgroundcolor
		$colTEXTBG = ImageColorAllocate($leg_img, $this->pie_color_text_bg[0], $this->pie_color_text_bg[1], $this->pie_color_text_bg[2]);
		// border color for the legends
		$colTEXTBO = ImageColorAllocate($leg_img, $this->pie_color_border[0], $this->pie_color_border[1], $this->pie_color_border[2]);


		// the table + border for the legend
//		ImageFilledRectangle($leg_img, 0, 0, $leg_width, $leg_height, $colTEXTBG);
//		ImageRectangle($leg_img, 0, 0, $leg_width-1, $leg_height-1, $colTEXTBO);

		reset($this->data);
		$c=0;
		$valuecounter =0;
/*
		$string_pre_colors = implode(',',$this->pre_colors);
		$fillcolorsarray = explode(',', str_replace('#','',$string_pre_colors));
		for ($i = 0; $i < count($fillcolorsarray); $i++) 
		{
			$label_color[] = ImageColorAllocate($leg_img, hexdec(substr($fillcolorsarray["$i"], 0, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 2, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 4, 2)) * 0.8);
		}
*/
		$lx = $box_with + $cellpadding*2;
		$ly = $cellpadding;
		$TamanhoLinha = 255;
//		if (!$CreatePie) //Existir� em piechart... N�o matar...
//			{
			$TamanhoLinha = $leg_width;
//			}

		foreach($this->data as $k => $data)
		{
			// legend text item
			$percent = round($data/$this->total*100, 2);
			$text = $this->legends[$k]  . ': ' . str_pad($data,6,' ',STR_PAD_LEFT) . ' ('.str_pad(number_format(($data / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)';
			$col = $this->get_color($k, "normal");

//			ImageFilledRectangle($leg_img, $cellpadding, $ly+2, $cellpadding+$box_with, $ly+$box_height+2, $col);
//			ImageRectangle($leg_img, $cellpadding, $ly+2, $cellpadding+$box_with, $ly+$box_height+2, $colTEXTBO);
//teste		ImageString($leg_img, 2, $lx, $ly, $text, $colTEXT);
//			ImageString($leg_img, $FontNumber, $lx, $ly, $text, $col);			

   		    $MeuY = round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber)));
			ImageString($leg_img, $FontNumber, 5, $MeuY, $text, $col);	

					$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 2;

					ImageLine($leg_img, 5, $MeuYLinha, $TamanhoLinha, $MeuYLinha, '999999');					
//					ImageLine($im, 5, $MeuYLinha+2, $TamanhoLinha, $MeuYLinha+2, '999999');										



			
			$ly += ($FontNumber + ImageFontHeight($FontNumber));
			$c++;
			$valuecounter++;
		}
		// final setups an image creation

        // Do the 3d effect
		$this->start_3d = $this->cy + $this->init_3d_height;

		for($i=$this->start_3d;$i > $this->cy; $i--)
		{
			reset($this->pie_data);
			$c=0;
			foreach($this->pie_data as $k => $data)
			{
				$col = $this->get_color($k, "3d");		    
				ImageFilledArc($this->pie, 
							   $this->cx, $i, 
							   $this->init_width, $this->init_height,  
							   $data[0], $data[1], 
							   $col, IMG_ARC_NOFILL);
				$c++;
			}
		}


		// Now do the graph
		reset($this->pie_data);
		$c=0;
		foreach($this->pie_data as $k => $data)
		{
			$col = $this->get_color($k, "normal");
			ImageFilledArc($this->pie, 
						   $this->cx, $this->cy, 
						   $this->init_width, $this->init_height,  
						   $data[0], $data[1], 
						   $col, IMG_ARC_PIE);
			$c++;
		}


		$pie_width = ImageSX($this->pie);
		$pie_height = ImageSY($this->pie);
		if($this->img_height < $leg_height)
		{
			$this->img_height = $leg_height;
		}
		$final = ImageCreateTrueColor($this->img_width+$leg_width+$cellpadding, $this->img_height);

		$ly += (2 + ImageFontHeight(2));

		ImageFill($final, 0, 0, $colBG);

		ImageCopyResampled($final, $leg_img, 
						   0, 0, 
						   0, 0, 
						   $this->img_width, $this->img_height, 						   
						   $leg_width, $leg_height);						   
		ImageCopyResampled($final, $this->pie, 
						   $this->img_width+$cellpadding, 0, 						   
						   0, 0, 
						   $leg_width, $leg_height, 
						   $pie_width, $pie_height);						   
						   

		
		// print debugging info, creation time
		/*
		if($this->display_creation_time)
		{
			// add creation time if wanted
			$this->endtime = microtime();
			list($susec, $ssec) = explode(" ",$this->starttime);
			$this->starttime = $susec+$ssec;
			list($eusec, $esec) = explode(" ",$this->endtime);
			$this->endtime = $eusec+$esec;
			$time = round ($this->endtime-$this->starttime, 2);
			$time_text = "creation time: ".$time." sec";
			ImageString($final, 1, ($this->pie_width+$cellpadding), ($this->img_height-ImageFontHeight(1)), $time_text, $colTEXT);
		}
		*/

		header('Content-type: image/png');
		ImagePNG($final);
		ImageDestroy($this->pie);
		ImageDestroy($leg_img);
		ImageDestroy($final);
		return TRUE;
    }

	/*
		Converts Hexcolors to RGB
		@returns array
	*/
	function hex2rgb($hex)
	{
		$color = ereg_replace('^#','',$hex);
		$rgb = array(
					 (16 * hexdec(substr($color,0,1)) + hexdec(substr($color,1,1)) ),
		             (16 * hexdec(substr($color,2,1)) + hexdec(substr($color,3,1)) ),
		             (16 * hexdec(substr($color,4,1)) + hexdec(substr($color,5,1)) )
					);
		return $rgb;
	}

	/**
	* Color Settings
	*
	* @param array colors for the piegraph data
	* @param background color
	* @param legends text color
	* @param legends border color
	*/
	function setColors($colors, $bg="FFFFFF", $text="000000", $border="999999")
	{
        $this->pie_colors		= ($colors) 			? $colors 					: $this->pre_colors;
		$this->pie_color_bg		= (strlen($bg)>5) 		? $this->hex2rgb($bg) 		: $this->hex2rgb("FFFFFF");
		$this->pie_color_text	= (strlen($text)>5)		? $this->hex2rgb($text)		: $this->hex2rgb("000000");
		$this->pie_color_border	= (strlen($border)>5)	? $this->hex2rgb($border)	: $this->hex2rgb("666666");
		$this->pie_color_text_bg	= $this->hex2rgb("F3F3F3");
		$this->colors_set = true;
	}

	/*
		Legend Settings
	*/
	function setLegends($l)
	{
		$this->legends = $l;
	}

	/*
		Legend Settings
	*/
	function set3dHeight($tdh)
	{
		$this->threedee_height = $tdh;
	}
	/*
		Display the creation time?
	*/
	/*
	function DisplayCreationTime()
	{
		$this->display_creation_time = true;
	}
	*/
	/*
		Init the graph and data
	*/
    function init()
    {
		if(!$this->colors_set)
		{
			$this->setColors($this->pre_colors);
		}
        $this->threedee_height = ($this->threedee_height) ? $this->threedee_height : 10; //round($this->pie_width/10);
		$this->img_width  = $this->pie_width;
		$this->img_height = $this->pie_height;
		$this->init_img_width  = $this->pie_width * 3;
		$this->init_img_height = $this->pie_height * 3;
		$this->init_width  = ($this->pie_width) * 3;
		$this->init_height = ($this->pie_height-$this->threedee_height) * 3;
		$this->init_3d_height = $this->threedee_height * 3;
		$this->cx = round ($this->init_width/2);
		$this->cy = round ($this->init_height/2);
		/*
			 init data for the pie
		*/
		$this->pie_data = array();
		$this->total = 0;
		foreach($this->data as $key => $value)
		{
			$this->total += $value;
		}
		reset($this->data);
		$num = count($this->data)-1;
		$start = 0;
		$c=0;
		foreach($this->data as $key => $value)
		{
			$percent = $value/$this->total*100;
			$fill  = ($percent*3.6);
			$fill  = ($fill>360) ? 360 : $fill;
			$end   = round($start+$fill);
			$end   = ($end >360) ? 360 : $end;
			if($num==$c)
			{
				$end   = ($end < 360) ? 360 : $end;
			}
			$this->pie_data[$key] = array($start, $end);
			$start = $end;
			$c++;
		}
    }

    function get_color($num, $mode = "normal")
    {

		$tmp_color = $this->hex2rgb( $this->pie_colors[$num] );
		$tmp_color_3d = array
		(
			( ($tmp_color[0] > 79) ? $tmp_color[0]-80 : 0 ),
			( ($tmp_color[1] > 79) ? $tmp_color[1]-80 : 0 ),
			( ($tmp_color[2] > 79) ? $tmp_color[2]-80 : 0 )
		);
	    if($mode=="3d")
		{
		 	return ImageColorAllocate($this->pie, $tmp_color_3d[0], $tmp_color_3d[1], $tmp_color_3d[2]);
		}
		else
		{
			return ImageColorAllocate($this->pie, $tmp_color[0], $tmp_color[1], $tmp_color[2]);
		}
	}

	/*
		Display the graph
	*/
    function display()
    {
		$this->init();
		
        $this->pie = @ImageCreateTrueColor($this->init_img_width, $this->init_img_height);

        $colBG = ImageColorAllocate($this->pie, $this->pie_color_bg[0], $this->pie_color_bg[1], $this->pie_color_bg[2]);
        ImageFill($this->pie, 0, 0, $colBG);

		$TotalArrayValues = array_sum($this->data);		
				
		// The Legends
		$cellpadding=5;
		$max_str=0;
		$items=0;
//		$FontNumber = 5;						
		$FontNumber = 3;				
		
		foreach($this->legends as $k => $legend)
		{
		$percent = round($legend/$this->total*100, 2);
		$text = $this->legends[$k]  . ': ' . str_pad($legend,6,' ',STR_PAD_LEFT) . ' ('.str_pad(number_format(($legend / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)';
		
		if(strlen($text) > $max_str)
		{
			$max_str = strlen($text);
		}
		$items++;
		}
//		$box_with   = ImageFontHeight($FontNumber);
//		$box_height = ImageFontHeight($FontNumber);
//		$leg_height = ((ImageFontHeight($FontNumber)+10) * $items)   + ($cellpadding * 2);
//		$leg_width  = (ImageFontWidth($FontNumber)  * ($max_str)) + ($cellpadding * 2);// +($box_with * 2);

		$leg_height = (((ImageFontHeight($FontNumber)+10) * $items)   + ($cellpadding * 35))/2;
		$leg_width  = ((ImageFontWidth($FontNumber)  * ($max_str)) + ($cellpadding * 35))/2;// +($box_with * 2);

		$leg_img = ImageCreateTrueColor($leg_width, $leg_height);
		ImageFill($leg_img, 0, 0, $colBG);
		
		// text color
		$colTEXT   = ImageColorAllocate($leg_img, $this->pie_color_text[0], $this->pie_color_text[1], $this->pie_color_text[2]);
		// text / legends backgroundcolor
		$colTEXTBG = ImageColorAllocate($leg_img, $this->pie_color_text_bg[0], $this->pie_color_text_bg[1], $this->pie_color_text_bg[2]);
		// border color for the legends
		$colTEXTBO = ImageColorAllocate($leg_img, $this->pie_color_border[0], $this->pie_color_border[1], $this->pie_color_border[2]);


		// the table + border for the legend
//		ImageFilledRectangle($leg_img, 0, 0, $leg_width, $leg_height, $colTEXTBG);
//		ImageRectangle($leg_img, 0, 0, $leg_width-1, $leg_height-1, $colTEXTBO);

		reset($this->data);
		$c=0;

		$valuecounter =0;
/*
		$string_pre_colors = implode(',',$this->pre_colors);
		$fillcolorsarray = explode(',', str_replace('#','',$string_pre_colors));
		for ($i = 0; $i < count($fillcolorsarray); $i++) 
		{
			$label_color[] = ImageColorAllocate($leg_img, hexdec(substr($fillcolorsarray["$i"], 0, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 2, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 4, 2)) * 0.8);
		}
*/
		$lx = $box_with + $cellpadding*2;
		$ly = $cellpadding;
		$TamanhoLinha = 255;
//		if (!$CreatePie) //Existir� em piechart... N�o matar...
//			{
			$TamanhoLinha = $leg_width;
//			}

		foreach($this->data as $k => $data)
		{
			// legend text item
			$percent = round($data/$this->total*100, 2);
			$text = $this->legends[$k]  . ': ' . str_pad($data,6,' ',STR_PAD_LEFT) . ' ('.str_pad(number_format(($data / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)';
			$col = $this->get_color($k, "normal");

//			ImageFilledRectangle($leg_img, $cellpadding, $ly+2, $cellpadding+$box_with, $ly+$box_height+2, $col);
//			ImageRectangle($leg_img, $cellpadding, $ly+2, $cellpadding+$box_with, $ly+$box_height+2, $colTEXTBO);
//teste		ImageString($leg_img, 2, $lx, $ly, $text, $colTEXT);
//			ImageString($leg_img, $FontNumber, $lx, $ly, $text, $col);			

   		    $MeuY = round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber)));
			ImageString($leg_img, $FontNumber, 5, $MeuY, $text, $col);	

					$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 2;

					ImageLine($leg_img, 5, $MeuYLinha, $TamanhoLinha, $MeuYLinha, '999999');					
//					ImageLine($im, 5, $MeuYLinha+2, $TamanhoLinha, $MeuYLinha+2, '999999');										



			
			$ly += ($FontNumber + ImageFontHeight($FontNumber));
			$c++;
			$valuecounter++;
		}
		// final setups an image creation



        // Do the 3d effect
		$this->start_3d = $this->cy + $this->init_3d_height;

		for($i=$this->start_3d;$i > $this->cy; $i--)
		{
			reset($this->pie_data);
			$c=0;
			foreach($this->pie_data as $k => $data)
			{
				$col = $this->get_color($k, "3d");		    
				ImageFilledArc($this->pie, 
							   $this->cx, $i, 
							   $this->init_width, $this->init_height,  
							   $data[0], $data[1], 
							   $col, IMG_ARC_NOFILL);
				$c++;
			}
		}


		// Now do the graph
		reset($this->pie_data);
		$c=0;
		foreach($this->pie_data as $k => $data)
		{
			$col = $this->get_color($k, "normal");
			ImageFilledArc($this->pie, 
						   $this->cx, $this->cy, 
						   $this->init_width, $this->init_height,  
						   $data[0], $data[1], 
						   $col, IMG_ARC_PIE);
			$c++;
		}


		$pie_width = ImageSX($this->pie);
		$pie_height = ImageSY($this->pie);
		if($this->img_height < $leg_height)
		{
			$this->img_height = $leg_height;
		}
		$final = ImageCreateTrueColor($this->img_width+$leg_width+$cellpadding, $this->img_height);

		$ly += (2 + ImageFontHeight(2));

		ImageFill($final, 0, 0, $colBG);

		ImageCopyResampled($final, $leg_img, 
						   0, 0, 
						   0, 0, 
						   $this->img_width, $this->img_height, 						   
						   $leg_width, $leg_height);						   
		ImageCopyResampled($final, $this->pie, 
						   $this->img_width+$cellpadding, 0, 						   
						   0, 0, 
						   $leg_width, $leg_height, 
						   $pie_width, $pie_height);						   
						   

		
		// print debugging info, creation time
		/*
		if($this->display_creation_time)
		{
			// add creation time if wanted
			$this->endtime = microtime();
			list($susec, $ssec) = explode(" ",$this->starttime);
			$this->starttime = $susec+$ssec;
			list($eusec, $esec) = explode(" ",$this->endtime);
			$this->endtime = $eusec+$esec;
			$time = round ($this->endtime-$this->starttime, 2);
			$time_text = "creation time: ".$time." sec";
			ImageString($final, 1, ($this->pie_width+$cellpadding), ($this->img_height-ImageFontHeight(1)), $time_text, $colTEXT);
		}
		*/
		header('Content-type: image/png');
		ImagePNG($final);
		ImageDestroy($this->pie);
		ImageDestroy($leg_img);
		ImageDestroy($final);
		return TRUE;		
    }
}

?>