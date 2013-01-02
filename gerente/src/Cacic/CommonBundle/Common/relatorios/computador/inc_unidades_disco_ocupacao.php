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
// Esse arquivo é um arquivo de include, usado pelo arquivo compuatdor.php. 
/**
 * Gera grafico de taxa de ocupacao de HD
 */
	$usado = '';
	$img = '';
	$txt_ocupado = '';
	$txt_livre = '';
	$usado = $_GET['usado'];
	$img = $_GET['img'];	
	$txt_ocupado = $_GET['ocupado'];
	$txt_livre = $_GET['livre'];
	
    $altura = 15;
    $largura = 150; // tamanho da imagen "grad.png"
    $hd_util = imagecreate($largura,$altura);
    $background = imagecolorallocate($hd_util, 000, 000, 255); // azul
    $borda = imagecolorallocate($hd_util, 000, 000, 000); // preto
    $textcolor = imagecolorallocate($hd_util, 255, 255, 255); // branco
    $string = "100".$txt_livre;
    if ($usado > 0) {
	    $string =  $usado.$txt_ocupado;
        $grad = imagecreatefrompng($img); // imagem gradiente de ocupacao
        $temp = imagecopy($hd_util, $grad, 0, 0, 0, 0, ($usado * 1.5), $altura-1);
        imagerectangle($hd_util, 0, 0, $largura, $altura-1, $borda);
    }
    imagestring($hd_util, 5, 40, 0, $string, $textcolor);
    header("Content-type: image/png");
    imagepng($hd_util);
?>