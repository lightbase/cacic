<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
function FilledArc(&$im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color='none') 
	{
	ImageArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color);
	// To close the arc with 2 lines between the center and the 2 limits of the arc 
	$x = $CenterX + (cos(deg2rad($Start)) * ($DiameterX / 2)); 
	$y = $CenterY + (sin(deg2rad($Start)) * ($DiameterY / 2)); 
	ImageLine($im, $x, $y, $CenterX, $CenterY, $line_color); 
	$x = $CenterX + (cos(deg2rad($End)) * ($DiameterX / 2)); 
	$y = $CenterY + (sin(deg2rad($End)) * ($DiameterY / 2)); 
	ImageLine($im, $x, $y, $CenterX, $CenterY, $line_color); 
	// To fill the arc, the starting point is a point in the middle of the closed space 
	$x = $CenterX + (cos(deg2rad(($Start + $End) / 2)) * ($DiameterX / 4)); 
	$y = $CenterY + (sin(deg2rad(($Start + $End) / 2)) * ($DiameterY / 4)); 
	ImageFillToBorder($im, $x, $y, $line_color, $fill_color);
	}
	
function phPie($data, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, $Legend, $FontNumber, $CreatePie, $Sort) 
	{
	$MinDisplayPct = 0;
	$DisplayColors =   'FF0000;3399FF;66CC66;CCCC99;FFCCCC;9933FF;CC66CC;3333FF;DA3600;0F84D4;F9A308;62D038;FE670F;2C9232;7F0B80;DFDE29;9F9F9F;EDEDED;BAE700;00FFFF;0000FF';
	$BackgroundColor ='FFFFFF';
	$LineColor = 'FFFFFF';		
	if (!$CreatePie) 
		{
		$DisplayColors = '000000';
		$BackgroundColor ='FFFFFF';					
		}
	$CenterX = round($width / 2);
	$CenterY = round($height / 2);
	$DiameterX = round($width * 0.95);
	$DiameterY = round($height * 0.95);
	
	if ($Legend) 
		{
		$DiameterX = $DiameterY;
		$CenterX   = $width - $CenterY;
		}

	if (isset($data) && !is_array($data)) 
		{
		$data = unserialize(stripslashes($data));
		} 
	else if (!isset($data)) 
		{
//		$data = array('Nenhum Registro'=>1);
		$height = 50;
		}
	
	if ($Legend) 
		{
		$DiameterX = $DiameterY;
		$CenterX   = $width - $CenterY;
		}

	if (($width > 8192) || ($height > 8192) || ($width <= 0) || ($height <= 0)) 
		{
		die('Image size limited to 8192 x 8192 for safety reasons');
		}

	if ($im = @ImageCreate($width, $height)) 
		{
		$background_color = ImageColorAllocate($im, hexdec(substr($BackgroundColor, 0, 2)), hexdec(substr($BackgroundColor, 2, 2)), hexdec(substr($BackgroundColor, 4, 2)));
		$line_color       = ImageColorAllocate($im, hexdec(substr($LineColor, 0, 2)), hexdec(substr($LineColor, 2, 2)), hexdec(substr($LineColor, 4, 2)));
		$fillcolorsarray = explode(';', $DisplayColors);
		for ($i = 0; $i < count($fillcolorsarray); $i++) 
			{
			$fill_color[]  = ImageColorAllocate($im, hexdec(substr($fillcolorsarray["$i"], 0, 2)), hexdec(substr($fillcolorsarray["$i"], 2, 2)), hexdec(substr($fillcolorsarray["$i"], 4, 2)));
			$label_color[] = ImageColorAllocate($im, hexdec(substr($fillcolorsarray["$i"], 0, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 2, 2)) * 0.8, hexdec(substr($fillcolorsarray["$i"], 4, 2)) * 0.8);
			}

		if ((count($data) <= 0) and ($Legend)) 
			{
//			ImageString($im, $FontNumber, 5, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), 'Total: 0', '9933FF');
			ImageString($im, $FontNumber, 3, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), '                   ***** Nenhum Registro Encontrado *****', '9933FF');			
			}
		else
			{
			$TotalArrayValues = array_sum($data);				
			if ($Sort)
				{ 
				arsort($data);
				}

			$Start = 0;
			$valuecounter = 0;
			$ValuesSoFar = 0;
			foreach ($data as $key => $value) 
				{
				$ValuesSoFar += $value;
			
				if (($value / $TotalArrayValues) > $MinDisplayPct) 
					{
					$End = ceil(($ValuesSoFar / $TotalArrayValues) * 360);
					if ($CreatePie)
						{
						FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color[$valuecounter % count($fill_color)]);
						}
					if ($Legend) 
						{
						$MeuY = round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber)));
						ImageString($im, $FontNumber, 3, $MeuY, $key . ': ' . str_pad($value,6,' ',STR_PAD_LEFT) . '('.str_pad(number_format(($value / $TotalArrayValues) * 100, 1),5,' ',STR_PAD_LEFT).'%)', $label_color[$valuecounter % count($label_color)]);
						if ($CreatePie)
							{							
							$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 2;
							ImageLine($im, 5, $MeuYLinha, 255, $MeuYLinha, '999999');
							}
						}
					$Start = $End;
					} 
				else 
					{
					// too small to bother drawing - just finish off the arc with no fill and break
					$End = 360;
					if ($CreatePie)
						{
						FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $line_color);
						}						
					if ($Legend) 
						{
						ImageString($im, $FontNumber, 3, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), 'Other ('.str_pad(number_format((($TotalArrayValues - $ValuesSoFar) / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)', $line_color);
						}
					break;
					}
				$valuecounter++;
				}
				
			If ($Legend) 
				{
				$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 2;
				$TamanhoLinha = 255;
				if (!$CreatePie)
					{
					$TamanhoLinha = $width - 24;
					}
				ImageLine($im, 5, $MeuYLinha, $TamanhoLinha, $MeuYLinha, '999999');					
				ImageLine($im, 5, $MeuYLinha+2, $TamanhoLinha, $MeuYLinha+2, '999999');										
				ImageString($im, $FontNumber, 3, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), str_pad('Total',strlen($key),'.',STR_PAD_RIGHT) . ': ' . str_pad($TotalArrayValues,6,' ',STR_PAD_LEFT) . '(100.0%)', '999999');				
				}
			}
		header('Content-type: image/png');
		ImagePNG($im);
		ImageDestroy($im);
		return TRUE;	
		} 
	else 
		{
		 echo 'Cannot Initialize new GD image stream';
		 return FALSE;
		}
	}
?>