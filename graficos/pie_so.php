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
include_once '../include/library.php'; 
include_once '../include/piechart.php';
//// Comentado temporariamente - AntiSpy();

conecta_bd_cacic();
$where 	= ($_REQUEST['cs_nivel_administracao'] <> 1 &&
		   $_REQUEST['cs_nivel_administracao'] <> 2 ? ' AND c.id_local = '.$_REQUEST['id_local']:'');

if ($_SESSION['te_locais_secundarios'] && $where)
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta
	$where = str_replace('c.id_local = ','(c.id_local = ',$where);
	$where .= ' OR c.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}
	
if ($_GET['in_detalhe'])
	$where = ' AND c.id_local = '.$_GET['in_detalhe'];
			  
$query = 'SELECT 	count(a.id_so) as qtd, 
					b.te_desc_so 
		  FROM		computadores a,
		  			so b,
					redes c
		  WHERE 	a.id_so = b.id_so AND 
		  			a.te_nome_computador IS NOT NULL AND 
					a.dt_hr_ult_acesso IS NOT NULL AND
					a.id_ip_rede = c.id_ip_rede '.
					$where . ' 
		  GROUP BY 	a.id_so 
		  ORDER BY 	a.id_so';
$result = mysql_query($query) or die('Falha na consulta (computadores, so, redes, locais)');
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$v_row_result = str_pad($row_result['te_desc_so'],20,'.',STR_PAD_RIGHT);
	$arr[$v_row_result] = $row_result['qtd'];			
	} 

$width = 420;
$height = 159;
$CreatePie = 1;
$Sort      = 1;		
phPie($arr, $width,$height, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);
?>