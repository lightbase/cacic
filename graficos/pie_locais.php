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
include_once '../include/library.php'; 
include 	 '../include/piechart.php';
conecta_bd_cacic();
	  
$query = 'SELECT 	count(a.te_node_address) as total,
					c.sg_local
		  FROM		computadores a,
					redes b,
					locais c
		  WHERE 	a.te_nome_computador IS NOT NULL AND 
					a.id_ip_rede = b.id_ip_rede AND
					b.id_local = c.id_local
		  GROUP BY 	c.sg_local
		  ORDER BY  c.sg_local';

   $result = mysql_query($query) or die('Falha na consulta (computadores, redes, locais)');

 		while ($row_result = mysql_fetch_assoc($result))		
			{ 
			$v_row_result = str_pad($row_result['sg_local'],20,'.',STR_PAD_RIGHT);
		    $arr[$v_row_result] = $row_result['total'];			
	 		} 
/*			
    $arr['Local Extra 1.......'] = 1;						
    $arr['Local Extra 2.......'] = 2;						
    $arr['Local Extra 3.......'] = 3;						
    $arr['Local Extra 4.......'] = 4;							
    $arr['Local Extra 5.......'] = 5;						
    $arr['Local Extra 6.......'] = 6;						
    $arr['Local Extra 7.......'] = 7;						
    $arr['Local Extra 8.......'] = 8;							
*/	
   	$CreatePie = 1;
   	$Sort      = 1;
	$PieSize   = 30*count($arr);
	$PieSize   = 170;	
//LimpaTESTES();
//GravaTESTES('Com ' . count($arr) . ' LOCAIS: '.$PieSize);
	
	phPie($arr, 420 , $PieSize, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);

?>