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
session_start(); 
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

include '../include/library.php'; 
include '../include/piechart.php';
	
if ($_GET['where']=='')
	{
	$where 	= ($_SESSION['cs_nivel_administracao'] <> 1 &&
			   $_SESSION['cs_nivel_administracao'] <> 2 ? ' AND redes.id_local = '.$_SESSION['id_local']:'');

	// Caso hajam locais secund�rios associados ao usu�rio, incluo-os na cl�usula Where
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
		$where = str_replace('redes.id_local = ','(redes.id_local = ',$where);
		$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}					   
	}
else
	$where = $_GET['where'];
	
require '../include/monta_consulta_locais.php';
	
$CreatePie 		= 1;
$Sort      		= 1;
$ShowText		= 0;
$DisplaySequence= 0;
$width	   		= 700;
$height    		= (count($arr_locais)*13.5);//855; //Cabem 65 linhas
$height    		= ($height < 400?400:$height);
$DisplaySequence= 2; // Quantidade de posi��es para o sequencial
$ImgType		= 'PNG';
$myLineSize		= 263; //185;
$CenterX		= 450;
$DiameterX		= 480;

phPie($arr_locais, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort,$DisplaySequence, $ShowText, $ImgType, $myLineSize);
?>