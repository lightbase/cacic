<?php
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
function FilledArc(&$im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color='none',$key='') 
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
	
	// Ainda faltam vários ajustes para escrita do label na vertical... Anderson Peterle => 08/08/2007
	if ($key)
		imagestringup($im,2,$x,$y,$key,$x);
	}
	
function phPie($data, $width, $height, $CenterX, $CenterY, $newDiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, $Legend, $FontNumber, $CreatePie, $Sort, $DisplaySequence, $ShowText, $ImgType='PNG',$newLineSize=215) 
	{
	// Ajustes necessários ao redimencionamento das pizzas...
	$width 		   += 95;
	$height 	   += 95;
	$CenterX 	   += 80;
	 	 
	$FontNumber	    = 2; // Fixo o código da fonte em 2, reduzindo-a
						 // devido à saturação gerada pelas legendas nas estatísticas
						 // Acho legal tornar esse código flexível no futuro!... Anderson Peterle 07/11/2007 12:42PM
	
	$MinDisplayPct  = 0;
	$LineSize		= $newLineSize;

	/*
	$DisplayColors .= 'FF0000;'; 
	$DisplayColors .= '0000FF;'; 	
	$DisplayColors .= '99CC33;'; 
	$DisplayColors .= '0000CC;'; 
	$DisplayColors .= 'FFCC00;'; 
	$DisplayColors .= '3333FF;'; 
	$DisplayColors .= '660066;'; 
	$DisplayColors .= '9966CC;'; 
	$DisplayColors .= 'DA3600;'; 
	$DisplayColors .= '0F84D4;'; 
	$DisplayColors .= 'F9A308;'; 
	$DisplayColors .= '62D038;'; 
	$DisplayColors .= 'FE670F;'; 
	$DisplayColors .= '2C9232;'; 
	$DisplayColors .= '7F0B80;'; 
	$DisplayColors .= 'DFDE29;'; 
	$DisplayColors .= '9F9F9F;'; 
	$DisplayColors .= 'EDEDED;'; 
	$DisplayColors .= 'BAE700;'; 
	$DisplayColors .= '00FFFF;'; 
	$DisplayColors .= '009933;'; 
	$DisplayColors .= '99FF66;'; 
	$DisplayColors .= 'FFCCCC;'; 	
	$DisplayColors .= '9933FF;'; 
	$DisplayColors .= 'CC66CC;'; 	
	$DisplayColors .= 'FF99FF;'; 
	$DisplayColors .= 'FFFF99;'; 
	$DisplayColors .= '3399FF;'; 
	$DisplayColors .= '66CC66;'; 
	$DisplayColors .= 'CCCC99;'; 	
 	$DisplayColors .= '0099FF;';
	$DisplayColors .= 'CD6AC0;';
	$DisplayColors .= 'FF5904;';
	$DisplayColors .= '0099CC;;';  // Variant of brown
	$DisplayColors .= '006F00;;';  // Grey
	$DisplayColors .= '996600;;';  // Dark Green
	$DisplayColors .= '669966;;';  // Blue (Light)
	$DisplayColors .= 'FF66CC;;';  // Dark Pink
	$DisplayColors .= 'FF9933;;';  // Dirty green
	$DisplayColors .= 'CCCC00;;';  // Violet shade of blue
	$DisplayColors .= '9900FF;;';  // Orange
	$DisplayColors .= '999999;;';  // Violet
	$DisplayColors .= '99FFCC;;';  // Blue+Green Light
	$DisplayColors .= 'CCCCFF;;';  // Light violet
	$DisplayColors .= '1941A5;;';  // Dark Blue	
	$DisplayColors .= '669900;;';  // Shade of green
	*/

    $DisplayColors .= 'FF0000;';  // Red				1
    $DisplayColors .= '0000CC;';  // Blue
    $DisplayColors .= '99CC33;';  // LightGreen1
    $DisplayColors .= 'CDB79E;';  // Bisque3
    $DisplayColors .= '8B7D6B;';  // Bisque4
    $DisplayColors .= 'A52A2A;';  // Brown
    $DisplayColors .= '458B00;';  // Chartreuse4
    $DisplayColors .= 'FF7F24;';  // Chocolate1
    $DisplayColors .= '6495ED;';  // CornflowerBlue
    $DisplayColors .= '00008B;';  // DarkBlue
    $DisplayColors .= '008B8B;';  // DarkCyan
    $DisplayColors .= 'B8860B;';  // DarkGoldenrod
    $DisplayColors .= 'EEAD0E;';  // DarkGoldenrod2
    $DisplayColors .= 'CD950C;';  // DarkGoldenrod3
    $DisplayColors .= 'A9A9A9;';  // DarkGray
    $DisplayColors .= '006400;';  // DarkGreen
    $DisplayColors .= 'BDB76B;';  // DarkKhaki
    $DisplayColors .= '8B008B;';  // DarkMagenta
    $DisplayColors .= '556B2F;';  // DarkOliveGreen
    $DisplayColors .= 'FF7F00;';  // DarkOrange1
    $DisplayColors .= 'EE7600;';  // DarkOrange2
    $DisplayColors .= 'CD6600;';  // DarkOrange3
    $DisplayColors .= '8B4500;';  // DarkOrange4
    $DisplayColors .= '9932CC;';  // DarkOrchid
    $DisplayColors .= 'BF3EFF;';  // DarkOrchid1
    $DisplayColors .= 'B23AEE;';  // DarkOrchid2
    $DisplayColors .= '9A32CD;';  // DarkOrchid3
    $DisplayColors .= '68228B;';  // DarkOrchid4
    $DisplayColors .= '8B0000;';  // DarkRed
    $DisplayColors .= 'E9967A;';  // DarkSalmon
    $DisplayColors .= '8FBC8F;';  // DarkSeaGreen
    $DisplayColors .= 'C1FFC1;';  // DarkSeaGreen1
    $DisplayColors .= 'B4EEB4;';  // DarkSeaGreen2
    $DisplayColors .= '9BCD9B;';  // DarkSeaGreen3
    $DisplayColors .= '698B69;';  // DarkSeaGreen4
    $DisplayColors .= '483D8B;';  // DarkSlateBlue
    $DisplayColors .= '97FFFF;';  // DarkSlateGray1
    $DisplayColors .= '8DEEEE;';  // DarkSlateGray2
    $DisplayColors .= '79CDCD;';  // DarkSlateGray3
    $DisplayColors .= '528B8B;';  // DarkSlateGray4
    $DisplayColors .= '00CED1;';  // DarkTurquoise
    $DisplayColors .= '9400D3;';  // DarkViolet
    $DisplayColors .= 'FF1493;';  // DeepPink
    $DisplayColors .= 'EE1289;';  // DeepPink2
    $DisplayColors .= 'CD1076;';  // DeepPink3
    $DisplayColors .= '8B0A50;';  // DeepPink4
    $DisplayColors .= '00BFFF;';  // DeepSkyBlue
    $DisplayColors .= '00B2EE;';  // DeepSkyBlue2
    $DisplayColors .= '009ACD;';  // DeepSkyBlue3
    $DisplayColors .= '00688B;';  // DeepSkyBlue4
    $DisplayColors .= '1E90FF;';  // DodgerBlue
    $DisplayColors .= '1C86EE;';  // DodgerBlue2
    $DisplayColors .= '1874CD;';  // DodgerBlue3
    $DisplayColors .= '104E8B;';  // DodgerBlue4
    $DisplayColors .= 'B22222;';  // Firebrick
    $DisplayColors .= 'FF3030;';  // Firebrick1
    $DisplayColors .= 'EE2C2C;';  // Firebrick2
    $DisplayColors .= 'CD2626;';  // Firebrick3
    $DisplayColors .= '8B1A1A;';  // Firebrick4
    $DisplayColors .= '228B22;';  // ForestGreen
    $DisplayColors .= 'FFD700;';  // Gold
    $DisplayColors .= 'EEC900;';  // Gold2
    $DisplayColors .= 'CDAD00;';  // Gold3
    $DisplayColors .= '8B7500;';  // Gold4
    $DisplayColors .= 'DAA520;';  // Goldenrod
    $DisplayColors .= 'FFC125;';  // Goldenrod1
    $DisplayColors .= 'EEB422;';  // Goldenrod2
    $DisplayColors .= 'CD9B1D;';  // Goldenrod3
    $DisplayColors .= '8B6914;';  // Goldenrod4
    $DisplayColors .= '1C1C1C;';  // Gray11
    $DisplayColors .= '363636;';  // Gray21
    $DisplayColors .= '4F4F4F;';  // Gray31
    $DisplayColors .= '696969;';  // Gray41
    $DisplayColors .= '828282;';  // Gray51
    $DisplayColors .= '9C9C9C;';  // Gray61
    $DisplayColors .= 'B5B5B5;';  // Gray71
    $DisplayColors .= 'CFCFCF;';  // Gray81
    $DisplayColors .= 'E8E8E8;';  // Gray91
    $DisplayColors .= '00FF00;';  // Green
    $DisplayColors .= '00EE00;';  // Green2
    $DisplayColors .= '00CD00;';  // Green3
    $DisplayColors .= '008B00;';  // Green4
    $DisplayColors .= 'ADFF2F;';  // GreenYellow
    $DisplayColors .= 'F0FFF0;';  // Honeydew1
    $DisplayColors .= 'E0EEE0;';  // Honeydew2
    $DisplayColors .= 'C1CDC1;';  // Honeydew3
    $DisplayColors .= '838B83;';  // Honeydew4
    $DisplayColors .= 'FF69B4;';  // HotPink
    $DisplayColors .= 'FF6EB4;';  // HotPink1
    $DisplayColors .= 'EE6AA7;';  // HotPink2
    $DisplayColors .= 'CD6090;';  // HotPink3
    $DisplayColors .= '8B3A62;';  // HotPink4
    $DisplayColors .= 'CD5C5C;';  // IndianRed
    $DisplayColors .= 'FF6A6A;';  // IndianRed1
    $DisplayColors .= 'EE6363;';  // IndianRed2
    $DisplayColors .= 'CD5555;';  // IndianRed3
    $DisplayColors .= '8B3A3A;';  // IndianRed4
    $DisplayColors .= 'FFFFF0;';  // Ivory1
    $DisplayColors .= 'EEEEE0;';  // Ivory2
    $DisplayColors .= 'CDCDC1;';  // Ivory3
    $DisplayColors .= '8B8B83;';  // Ivory4
    $DisplayColors .= 'FFF68F;';  // Khaki1
    $DisplayColors .= 'EEE685;';  // Khaki2
    $DisplayColors .= 'CDC673;';  // Khaki3
    $DisplayColors .= '8B864E;';  // Khaki4
    $DisplayColors .= 'FFF0F5;';  // LavenderBlush1
    $DisplayColors .= 'EEE0E5;';  // LavenderBlush2
    $DisplayColors .= 'CDC1C5;';  // LavenderBlush3
    $DisplayColors .= '8B8386;';  // LavenderBlush4
    $DisplayColors .= '7CFC00;';  // LawnGreen
    $DisplayColors .= 'FFFACD;';  // LemonChiffon1
    $DisplayColors .= 'EEE9BF;';  // LemonChiffon2
    $DisplayColors .= 'CDC9A5;';  // LemonChiffon3
    $DisplayColors .= '8B8970;';  // LemonChiffon4
    $DisplayColors .= 'FFAEB9;';  // Light Pink1
    $DisplayColors .= 'ADD8E6;';  // LightBlue
    $DisplayColors .= 'BFEFFF;';  // LightBlue1
    $DisplayColors .= 'B2DFEE;';  // LightBlue2
    $DisplayColors .= '9AC0CD;';  // LightBlue3
    $DisplayColors .= '68838B;';  // LightBlue4
    $DisplayColors .= 'F08080;';  // LightCoral
    $DisplayColors .= 'E0FFFF;';  // LightCyan
    $DisplayColors .= 'D1EEEE;';  // LightCyan2
    $DisplayColors .= 'B4CDCD;';  // LightCyan3
    $DisplayColors .= '7A8B8B;';  // LightCyan4
    $DisplayColors .= 'EEDD82;';  // LightGoldenrod
    $DisplayColors .= 'FFEC8B;';  // LightGoldenrod1
    $DisplayColors .= 'EEDC82;';  // LightGoldenrod2
    $DisplayColors .= 'CDBE70;';  // LightGoldenrod3
    $DisplayColors .= '8B814C;';  // LightGoldenrod4
    $DisplayColors .= '90EE90;';  // LightGreen2
    $DisplayColors .= 'FFB6C1;';  // LightPink
    $DisplayColors .= 'EEA2AD;';  // LightPink2
    $DisplayColors .= 'CD8C95;';  // LightPink3
    $DisplayColors .= '8B5F65;';  // LightPink4
    $DisplayColors .= 'FFA07A;';  // LightSalmon
    $DisplayColors .= 'EE9572;';  // LightSalmon2
    $DisplayColors .= 'CD8162;';  // LightSalmon3
    $DisplayColors .= '8B5742;';  // LightSalmon4
    $DisplayColors .= '20B2AA;';  // LightSeaGreen
    $DisplayColors .= '87CEFA;';  // LightSkyBlue
    $DisplayColors .= 'B0E2FF;';  // LightSkyBlue1
    $DisplayColors .= 'A4D3EE;';  // LightSkyBlue2
    $DisplayColors .= '8DB6CD;';  // LightSkyBlue3
    $DisplayColors .= '607B8B;';  // LightSkyBlue4
    $DisplayColors .= '8470FF;';  // LightSlateBlue
    $DisplayColors .= 'B0C4DE;';  // LightSteelBlue
    $DisplayColors .= 'CAE1FF;';  // LightSteelBlue1
    $DisplayColors .= 'BCD2EE;';  // LightSteelBlue2
    $DisplayColors .= 'A2B5CD;';  // LightSteelBlue3
    $DisplayColors .= '6E7B8B;';  // LightSteelBlue4
    $DisplayColors .= 'FFFFE0;';  // LightYellow
    $DisplayColors .= 'EEEED1;';  // LightYellow2
    $DisplayColors .= 'CDCDB4;';  // LightYellow3
    $DisplayColors .= '8B8B7A;';  // LightYellow4
    $DisplayColors .= '32CD32;';  // LimeGreen
    $DisplayColors .= 'FAFAD2;';  // LtGoldenrodYellow
    $DisplayColors .= 'FF00FF;';  // Magenta
    $DisplayColors .= 'EE00EE;';  // Magenta2
    $DisplayColors .= 'CD00CD;';  // Magenta3
    $DisplayColors .= 'B03060;';  // Maroon
    $DisplayColors .= 'FF34B3;';  // Maroon1
    $DisplayColors .= 'EE30A7;';  // Maroon2
    $DisplayColors .= 'CD2990;';  // Maroon3
    $DisplayColors .= '8B1C62;';  // Maroon4
    $DisplayColors .= '66CDAA;';  // MediumAquamarine
    $DisplayColors .= '0000CD;';  // MediumBlue
    $DisplayColors .= 'BA55D3;';  // MediumOrchid
    $DisplayColors .= 'E066FF;';  // MediumOrchid1
    $DisplayColors .= 'D15FEE;';  // MediumOrchid2
    $DisplayColors .= 'B452CD;';  // MediumOrchid3
    $DisplayColors .= '7A378B;';  // MediumOrchid4
    $DisplayColors .= '9370DB;';  // MediumPurple
    $DisplayColors .= 'AB82FF;';  // MediumPurple1
    $DisplayColors .= '9F79EE;';  // MediumPurple2
    $DisplayColors .= '8968CD;';  // MediumPurple3
    $DisplayColors .= '5D478B;';  // MediumPurple4
    $DisplayColors .= '3CB371;';  // MediumSeaGreen
    $DisplayColors .= '7B68EE;';  // MediumSlateBlue
    $DisplayColors .= '00FA9A;';  // MediumSpringGreen
    $DisplayColors .= '48D1CC;';  // MediumTurquoise
    $DisplayColors .= 'C71585;';  // MediumVioletRed
    $DisplayColors .= '191970;';  // MidnightBlue
    $DisplayColors .= 'FFE4E1;';  // MistyRose1
    $DisplayColors .= 'EED5D2;';  // MistyRose2
    $DisplayColors .= 'CDB7B5;';  // MistyRose3
    $DisplayColors .= '8B7D7B;';  // MistyRose4
    $DisplayColors .= 'FFDEAD;';  // NavajoWhite1
    $DisplayColors .= 'EECFA1;';  // NavajoWhite2
    $DisplayColors .= 'CDB38B;';  // NavajoWhite3
    $DisplayColors .= '8B795E;';  // NavajoWhite4
    $DisplayColors .= '000080;';  // NavyBlue
    $DisplayColors .= '6B8E23;';  // OliveDrab
    $DisplayColors .= 'C0FF3E;';  // OliveDrab1
    $DisplayColors .= 'B3EE3A;';  // OliveDrab2
    $DisplayColors .= '698B22;';  // OliveDrab4
    $DisplayColors .= 'FFA500;';  // Orange
    $DisplayColors .= 'EE9A00;';  // Orange2
    $DisplayColors .= 'CD8500;';  // Orange3
    $DisplayColors .= '8B5A00;';  // Orange4
    $DisplayColors .= 'FF4500;';  // OrangeRed
    $DisplayColors .= 'EE4000;';  // OrangeRed2
    $DisplayColors .= 'CD3700;';  // OrangeRed3
    $DisplayColors .= '8B2500;';  // OrangeRed4
    $DisplayColors .= 'DA70D6;';  // Orchid
    $DisplayColors .= 'FF83FA;';  // Orchid1
    $DisplayColors .= 'EE7AE9;';  // Orchid2
    $DisplayColors .= 'CD69C9;';  // Orchid3
    $DisplayColors .= '8B4789;';  // Orchid4
    $DisplayColors .= 'EEE8AA;';  // PaleGoldenrod
    $DisplayColors .= '98FB98;';  // PaleGreen
    $DisplayColors .= '9AFF9A;';  // PaleGreen1
    $DisplayColors .= '7CCD7C;';  // PaleGreen3
    $DisplayColors .= '548B54;';  // PaleGreen4
    $DisplayColors .= 'AFEEEE;';  // PaleTurquoise
    $DisplayColors .= 'BBFFFF;';  // PaleTurquoise1
    $DisplayColors .= '96CDCD;';  // PaleTurquoise3
    $DisplayColors .= '668B8B;';  // PaleTurquoise4
    $DisplayColors .= 'DB7093;';  // PaleVioletRed
    $DisplayColors .= 'FF82AB;';  // PaleVioletRed1
    $DisplayColors .= 'EE799F;';  // PaleVioletRed2
    $DisplayColors .= 'CD6889;';  // PaleVioletRed3
    $DisplayColors .= '8B475D;';  // PaleVioletRed4
    $DisplayColors .= 'FFDAB9;';  // PeachPuff1
    $DisplayColors .= 'EECBAD;';  // PeachPuff2
    $DisplayColors .= 'CDAF95;';  // PeachPuff3
    $DisplayColors .= '8B7765;';  // PeachPuff4
    $DisplayColors .= 'CD853F;';  // Peru
    $DisplayColors .= 'FFC0CB;';  // Pink
    $DisplayColors .= 'FFB5C5;';  // Pink1
    $DisplayColors .= 'EEA9B8;';  // Pink2
    $DisplayColors .= 'CD919E;';  // Pink3
    $DisplayColors .= '8B636C;';  // Pink4
    $DisplayColors .= 'DDA0DD;';  // Plum
    $DisplayColors .= 'FFBBFF;';  // Plum1
    $DisplayColors .= 'EEAEEE;';  // Plum2
    $DisplayColors .= 'CD96CD;';  // Plum3
    $DisplayColors .= '8B668B;';  // Plum4
    $DisplayColors .= 'B0E0E6;';  // PowderBlue
    $DisplayColors .= 'A020F0;';  // Purple
    $DisplayColors .= '9B30FF;';  // Purple1
    $DisplayColors .= '912CEE;';  // Purple2
    $DisplayColors .= '7D26CD;';  // Purple3
    $DisplayColors .= '551A8B;';  // Purple4
    $DisplayColors .= 'BC8F8F;';  // RosyBrown
    $DisplayColors .= 'FFC1C1;';  // RosyBrown1
    $DisplayColors .= 'EEB4B4;';  // RosyBrown2
    $DisplayColors .= 'CD9B9B;';  // RosyBrown3
    $DisplayColors .= '8B6969;';  // RosyBrown4
    $DisplayColors .= '4169E1;';  // RoyalBlue
    $DisplayColors .= '4876FF;';  // RoyalBlue1
    $DisplayColors .= '436EEE;';  // RoyalBlue2
    $DisplayColors .= '3A5FCD;';  // RoyalBlue3
    $DisplayColors .= '27408B;';  // RoyalBlue4
    $DisplayColors .= 'FA8072;';  // Salmon
    $DisplayColors .= 'FF8C69;';  // Salmon1
    $DisplayColors .= 'EE8262;';  // Salmon2
    $DisplayColors .= 'CD7054;';  // Salmon3
    $DisplayColors .= '8B4C39;';  // Salmon4
    $DisplayColors .= 'F4A460;';  // SandyBrown
    $DisplayColors .= '2E8B57;';  // SeaGreen
    $DisplayColors .= '54FF9F;';  // SeaGreen1
    $DisplayColors .= '4EEE94;';  // SeaGreen2
    $DisplayColors .= '43CD80;';  // SeaGreen3
    $DisplayColors .= '2E8B57;';  // SeaGreen4
    $DisplayColors .= 'FFF5EE;';  // Seashell1
    $DisplayColors .= 'EEE5DE;';  // Seashell2
    $DisplayColors .= 'CDC5BF;';  // Seashell3
    $DisplayColors .= '8B8682;';  // Seashell4
    $DisplayColors .= 'A0522D;';  // Sienna
    $DisplayColors .= 'FF8247;';  // Sienna1
    $DisplayColors .= 'EE7942;';  // Sienna2
    $DisplayColors .= 'CD6839;';  // Sienna3
    $DisplayColors .= '8B4726;';  // Sienna4
    $DisplayColors .= '87CEEB;';  // SkyBlue
    $DisplayColors .= '87CEFF;';  // SkyBlue1
    $DisplayColors .= '7EC0EE;';  // SkyBlue2
    $DisplayColors .= '6CA6CD;';  // SkyBlue3
    $DisplayColors .= '4A708B;';  // SkyBlue4
    $DisplayColors .= '6A5ACD;';  // SlateBlue
    $DisplayColors .= '836FFF;';  // SlateBlue1
    $DisplayColors .= '7A67EE;';  // SlateBlue2
    $DisplayColors .= '6959CD;';  // SlateBlue3
    $DisplayColors .= '473C8B;';  // SlateBlue4
    $DisplayColors .= 'C6E2FF;';  // SlateGray1
    $DisplayColors .= 'B9D3EE;';  // SlateGray2
    $DisplayColors .= '9FB6CD;';  // SlateGray3
    $DisplayColors .= '6C7B8B;';  // SlateGray4
    $DisplayColors .= 'FFFAFA;';  // Snow1
    $DisplayColors .= 'EEE9E9;';  // Snow2
    $DisplayColors .= 'CDC9C9;';  // Snow3
    $DisplayColors .= '8B8989;';  // Snow4
    $DisplayColors .= '00FF7F;';  // SpringGreen
    $DisplayColors .= '00EE76;';  // SpringGreen2
    $DisplayColors .= '00CD66;';  // SpringGreen3
    $DisplayColors .= '008B45;';  // SpringGreen4
    $DisplayColors .= '4682B4;';  // SteelBlue
    $DisplayColors .= '63B8FF;';  // SteelBlue1
    $DisplayColors .= '5CACEE;';  // SteelBlue2
    $DisplayColors .= '4F94CD;';  // SteelBlue3
    $DisplayColors .= '36648B;';  // SteelBlue4
    $DisplayColors .= 'D2B48C;';  // Tan
    $DisplayColors .= 'FFA54F;';  // Tan1
    $DisplayColors .= 'EE9A49;';  // Tan2
    $DisplayColors .= 'CD853F;';  // Tan3
    $DisplayColors .= '8B5A2B;';  // Tan4
    $DisplayColors .= 'D8BFD8;';  // Thistle
    $DisplayColors .= 'FFE1FF;';  // Thistle1
    $DisplayColors .= 'EED2EE;';  // Thistle2
    $DisplayColors .= 'CDB5CD;';  // Thistle3
    $DisplayColors .= '8B7B8B;';  // Thistle4
    $DisplayColors .= 'FF6347;';  // Tomato
    $DisplayColors .= 'EE5C42;';  // Tomato2
    $DisplayColors .= 'CD4F39;';  // Tomato3
    $DisplayColors .= '8B3626;';  // Tomato4
    $DisplayColors .= '40E0D0;';  // Turquoise
    $DisplayColors .= '00F5FF;';  // Turquoise1
    $DisplayColors .= '00E5EE;';  // Turquoise2
    $DisplayColors .= '00C5CD;';  // Turquoise3
    $DisplayColors .= '00868B;';  // Turquoise4
    $DisplayColors .= 'EE82EE;';  // Violet
    $DisplayColors .= 'D02090;';  // VioletRed
    $DisplayColors .= 'FF3E96;';  // VioletRed1
    $DisplayColors .= 'EE3A8C;';  // VioletRed2
    $DisplayColors .= 'CD3278;';  // VioletRed3
    $DisplayColors .= '8B2252;';  // VioletRed4
    $DisplayColors .= 'F5DEB3;';  // Wheat
    $DisplayColors .= 'FFE7BA;';  // Wheat1
    $DisplayColors .= 'EED8AE;';  // Wheat2
    $DisplayColors .= 'CDBA96;';  // Wheat3
    $DisplayColors .= '8B7E66;';  // Wheat4
    $DisplayColors .= 'FFFF00;';  // Yellow
    $DisplayColors .= 'EEEE00;';  // Yellow2
    $DisplayColors .= 'CDCD00;';  // Yellow3
    $DisplayColors .= '8B8B00;';  // Yellow4
    $DisplayColors .= '9ACD32;';  // YellowGreen
	

			
	$BackgroundColor 	= 'FFFFFF';
	$LineColor 			= 'FFFFFF';		
	if (!$CreatePie) 
		{
		$DisplayColors = '000000';
		$BackgroundColor ='FFFFFF';					
		}

//	$CenterX = round($width  / 2);
	$CenterY = round($height / 2);
	$DiameterX = ($newDiameterX==''?round($width * 0.85):$newDiameterX);
	$DiameterY = ($newDiameterX==''?round($height * 0.85):$newDiameterX);

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
		$DiameterX = ($newDiameterX==''?$DiameterY:$newDiameterX);
		//$CenterX   = ($width) - ($CenterY);
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
			ImageString($im, $FontNumber, 1, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.1 * ImageFontHeight($FontNumber))), '                   ***** Nenhum Registro Encontrado *****', '9933FF');			
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
				if ($DisplaySequence)
					{
					$key = str_pad(($valuecounter + 1),$DisplaySequence,' ',STR_PAD_LEFT).') '.$key;					
					$LineSize = $newLineSize + 25;
					}
				$ValuesSoFar += $value;
			
				if (($value / $TotalArrayValues) > $MinDisplayPct) 
					{
					$End = ceil(($ValuesSoFar / $TotalArrayValues) * 360);
					if ($CreatePie)
						{
						FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $fill_color[$valuecounter % count($fill_color)],($ShowText?$key:''));
						}
					if ($Legend) 
						{
//						$MeuY = round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber)));
						$MeuY = round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.1 * ImageFontHeight($FontNumber)));						
						ImageString($im, $FontNumber, 1, $MeuY, $key . ': ' . str_pad($value,6,' ',STR_PAD_LEFT) . '('.str_pad(number_format(($value / $TotalArrayValues) * 100, 1),5,' ',STR_PAD_LEFT).'%)', $label_color[$valuecounter % count($label_color)]);
						if ($CreatePie)
							{							
							$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 1;
							ImageLine($im, 1, $MeuYLinha, $LineSize, $MeuYLinha, '009999');
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
						FilledArc($im, $CenterX, $CenterY, $DiameterX, $DiameterY, $Start, $End, $line_color, $line_color, ($ShowText?$key:''));
						}						
					if ($Legend) 
						{
//						ImageString($im, $FontNumber, 1, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), 'Other ('.str_pad(number_format((($TotalArrayValues - $ValuesSoFar) / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)', $line_color);
						ImageString($im, $FontNumber, 1, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.1 * ImageFontHeight($FontNumber))), 'Other ('.str_pad(number_format((($TotalArrayValues - $ValuesSoFar) / $TotalArrayValues) * 100, 1),4,' ',STR_PAD_LEFT).'%)', $line_color);						
						}
					break;
					}
				$valuecounter++;
				}
				
			if ($Legend) 
				{
				$MeuYLinha = $MeuY + ImageFontHeight($FontNumber) + 1;

				if (!$CreatePie)
					{
					$LineSize = $width - 24;
					}
				ImageLine($im, 1, $MeuYLinha, $LineSize, $MeuYLinha, '009999');					
				ImageLine($im, 1, $MeuYLinha+1, $LineSize, $MeuYLinha+1, '009999');										
//				ImageString($im, $FontNumber, 1, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.5 * ImageFontHeight($FontNumber))), str_pad('Total',strlen($key),'.',STR_PAD_RIGHT) . ': ' . str_pad($TotalArrayValues,6,' ',STR_PAD_LEFT) . '(100.0%)', '999999');				
				ImageString($im, $FontNumber, 1, round((ImageFontHeight($FontNumber) * .5) + ($valuecounter * 1.1 * ImageFontHeight($FontNumber))), str_pad('Total',strlen($key),'.',STR_PAD_RIGHT) . ': ' . str_pad($TotalArrayValues,6,' ',STR_PAD_LEFT) . '(100.0%)', '009999');								
				}
			}
		if ($ImgType=='PNG')
			{
			header('Content-type: image/png');
			ImagePNG($im);
			}
		elseif ($ImgType=='GIF')
			{
			header('Content-type: image/gif');
			ImageGIF($im);
			}
		ImageDestroy($im);
		return true;	
		} 
	else 
		{
		echo 'Cannot Initialize new GD image stream';
		return false;
		}
	}
?>