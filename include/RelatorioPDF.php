<?php
require_once('Relatorio.php');
require_once('fpdf.php');

class PDFMTable extends FPDF
{
	private $widths;
	private $aligns;
	
	public function PDFMTable()
	{
		parent::__construct('L', 'mm', 'a3');
	}	

	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
		$fullwidth = 0;
		foreach ($w as $wh)
		{
			$fullwidth += $wh;
		}
		$this->fhPt = ($fullwidth + $this->lMargin + $this->rMargin) * $this->k;
	}

	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}

	function Row($data, $fillcolor, $fill, $cellColors)
	{
		$this->SetFillColor($fillcolor[0], $fillcolor[1], $fillcolor[2]);
		//Calculate the height of the row
		$nb=0;
		$i = 0;
		foreach ($data as $value)
		{
			$nb=max($nb,$this->NbLines($this->widths[$i],$value));
			$i++;
		}
		$h=5*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);

		$i = 0;		
		foreach ($data as $value)
		{
			$this->SetTextColor($cellColors[$i][0], $cellColors[$i][1], $cellColors[$i][2]);
		    $w=$this->widths[$i];
		    $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		    //Save the current position
		    $x=$this->GetX();
		    $y=$this->GetY();
			if ($fill)
			{
				$style = 'DF';
			}
			else
			{
				$style = 'D';
			}
		    //Draw the border
		    $this->Rect($x,$y,$w,$h, $style);
		    //Print the text
		    $this->MultiCell($w,5,$value,0,$a);
		    //Put the position to the right of the cell
		    $this->SetXY($x+$w,$y);
			$i++;
		}
		//Go to the next line
		$this->Ln($h);
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
		    $this->AddPage($this->CurOrientation);
	}

	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
		    $w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
		    $nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
		    $c=$s[$i];
		    if($c=="\n")
		    {
		        $i++;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		        continue;
		    }
		    if($c==' ')
		        $sep=$i;
		    $l+=$cw[$c];
		    if($l>$wmax)
		    {
		        if($sep==-1)
		        {
		            if($i==$j)
		                $i++;
		        }
		        else
		            $i=$sep+1;
		        $sep=-1;
		        $j=$i;
		        $l=0;
		        $nl++;
		    }
		    else
		        $i++;
		}
		return $nl;
	}
}

class RelatorioPDF extends Relatorio
{

	private function getMaxTextWidth($fpdf)
	{
		$maxWidths = array();
		$mstr = array();
		$arrays = array(array($this->getHeader()), $this->getBody());
		foreach ($arrays as $array)
		{
			foreach ($array as $row)
			{
				$i = 0;
				foreach ($row as $cell)
				{
					$w = 0;
					$cell = $this->relstrip($cell);
					if (strpos($cell, "\n") !== FALSE)
					{
						$lines = explode("\n", $cell);
						foreach ($lines as $ln)
						{
							$tmp =  $fpdf->GetStringWidth($ln);
							if ($tmp > $w)
							{
								$w = $tmp;
								$lol = $ln;
							}
						}
					}
					else
					{
						$w =  $fpdf->GetStringWidth((string) $cell);
					}

					if (!isset($maxWidths[$i]))
					{
						$maxWidths[$i] = $w + 10;
						$mstr[$i] = $cell;
					}
					else if ($w > $maxWidths[$i])
					{
						$maxWidths[$i] = $w + 10;
						$mstr[$i] = $cell;
					}
					$i++;
				} 
			}
		}
		return $maxWidths;
	}

	public function html2rgb($color)
	{
		if ($color[0] == '#')
		    $color = substr($color, 1);

		if (strlen($color) == 6)
		    list($r, $g, $b) = array($color[0].$color[1],
		                             $color[2].$color[3],
		                             $color[4].$color[5]);
		elseif (strlen($color) == 3)
		    list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
		    return false;

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return array($r, $g, $b);
	}

    public function output()
	{
		$pdf = new PDFMTable();
		

		//Colors, line width and bold font
		$pdf->SetFillColor(90, 90, 200);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(128, 0, 0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('Arial');
		//Header
		$w = $this->getMaxTextWidth($pdf);
		$fullwidth = array_sum($w);
		$pdf->AddPage('L');
		$pdf->SetWidths($w);

		$header = $this->getHeader();
		$size = count($header);
		$i = 0;
		foreach ($header as $cell)
		{
			$x = $pdf->GetX();
			$y = $pdf->GetY();
		    $pdf->MultiCell($w[$i], 7, $this->relstrip($cell), 1, 'C', 1);
			$pdf->SetXY($x + $w[$i], $y);
			$i++;
		}
		$pdf->Ln();
		//Color and font restoration
		$pdf->SetFillColor(224, 235, 255);
		$defaultFillColor = array(224, 235, 255);
		$defaultTextColor = 0;
		$pdf->SetTextColor($defaultTextColor);
		$pdf->SetFont('Arial');
		//Data
		$fill = 0;
		#foreach ($this->getBody() as $row)
		$body = &$this->getBody();
		$size = count($body);
		for ($i = 0; $i < $size; $i++)
		{
			$row = &$body[$i];
			$fillcolor = $defaultFillColor;
			$color = $this->getRowColor($i);
			if ($color)
			{
				$fillcolor = $color;
				$fill = 1;
			}
			$count = count($row);
			$j = 0;
			$cellColors = array();			
			foreach ($row as $key => $value)
			{
				$cellColors[$j] = $this->getCellColor($i, $j);
				$row[$key] = $this->relstrip($value);// iconv('utf-8' ,'iso-8859-1', 'AAA'/*strip_tags($row[$j])*/);
				$j++;
			}
			$pdf->Row($row, $fillcolor, $fill, $cellColors);
		    $fill = !$fill;
		}

		$pdf->Output('relatorio.pdf', 'D');
	}
}
?>
