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
session_start(); 
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include '../include/library.php'; 
include '../include/piechart.php';
	
if ($_GET['where']=='')
	{
	$where 	= ($_SESSION['cs_nivel_administracao'] <> 1 &&
			   $_SESSION['cs_nivel_administracao'] <> 2 ? ' AND redes.id_local = '.$_SESSION['id_local']:'');

	// Caso hajam locais secundários associados ao usuário, incluo-os na cláusula Where
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
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
$DisplaySequence= 2; // Quantidade de posições para o sequencial
$ImgType		= 'PNG';
$myLineSize		= 263; //185;
$CenterX		= 450;
$DiameterX		= 480;

phPie($arr_locais, $width, $height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort,$DisplaySequence, $ShowText, $ImgType, $myLineSize);
?>