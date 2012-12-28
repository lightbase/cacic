<? 
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
// Esse arquivo � um arquivo de include, usado pelo arquivo compuatdor.php. 
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