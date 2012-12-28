<?php
/**
 * @version $Id: softwares_classificar_tipo_gdimage.php 2009-09-14 00:18 harpiain $
 * @package CACIC-Admin
 * @subpackage SoftwaresClassificar
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Gerador de imagem GD para tipos de software a ser classificado
 */

$text = $_GET['texto']; //'Imagem com texto na vertical!';
// Tamanho da fonte
$font = 3;

// Tamanho da imagem para o texto e a fonte
$font_width = ImageFontWidth($font) * strlen($text);;
$font_height = ImageFontHeight($font);
$img_altura = $font_width;
$img_largura = $font_height;

$im = ImageCreate($img_largura, $img_altura);
// Fundo cinza para a imagem (igual ao fundo do cabecalho)
$bg = ImageColorAllocate($im, 207, 207, 205);

// borda cinza
$border = ImageColorAllocate($im, 207, 199, 199);
ImageRectangle($im, 0, 0, $img_largura - 1, $img_altura - 1, $border);

// Cor da fonte em preto
$textcolor = ImageColorAllocate($im, 0, 0, 0);

// coloca o texto na imagem
$image_string = imageStringUp  ( $im, $font, 0, $img_altura-1, $text, $textcolor );

// Escreve a imagem
header("Content-type: image/png");
ImagePNG($im);
ImageDestroy($im);
?>
