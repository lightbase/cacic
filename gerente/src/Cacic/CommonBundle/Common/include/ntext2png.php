<?php
// Cria a imagem (150px por 15px)
$im = ImageCreateTrueColor(150, 15);

// Cria a cor branca para o fundo
$white = ImageColorAllocate($im, 255, 255, 255);

// Desenha um retângulo branco para ser o fundo da imagem
ImageFilledRectangle($im, 0, 0, 149, 14, $white);

// Cria a cor preta para o texto
$black = ImageColorAllocate($im, 0, 0, 0);

// O Texto
$text = $_GET['msg'];

// Esta é a fonte que será usada para escrever o texto
$font = 'times.ttf';

// Adiciona o texto na imagem
ImageTTFText($im, 6, 0, 5, 10, $black, $font, $text);

// Informa ao browser que o documento é uma imagem PNG
//header('Content-type: image/png');

// Joga a imagem para a saída
ImagePNG($im);
?>