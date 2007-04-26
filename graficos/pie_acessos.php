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
include '../include/piechart.php';
conecta_bd_cacic();
$where 	= ($_REQUEST['cs_nivel_administracao'] <> 1 &&
		   $_REQUEST['cs_nivel_administracao'] <> 2 ? ' AND b.id_local = '.$_REQUEST['id_local']:'');
   
$query ='SELECT 	to_days(curdate()) - to_days(dt_hr_ult_acesso) as nr_dias, count(*)
		 FROM 		computadores a,
					redes b
		 WHERE  	a.te_nome_computador IS NOT NULL AND 
		 			a.dt_hr_ult_acesso IS NOT NULL AND
					a.id_ip_rede = b.id_ip_rede '.
					$where . ' 
		 GROUP BY 	nr_dias';

   $result = mysql_query($query) or die('Erro no select');

			function qt_comp($result, $num_dias) 
				{
				mysql_data_seek($result, 0);
				while ($reg = mysql_fetch_array($result)) 
					{
  					if ($reg[0] == $num_dias) return $reg[1]; 
					}
				}

			function ha_mais_de($result, $num_dias_min, $num_dias_max) 
				{
   	    		$total_dias = 0;
				mysql_data_seek($result, 0);
				while ($reg = mysql_fetch_array($result)) 
					{
   					if (($reg[0] > $num_dias_min) &&
   					    ($reg[0] < $num_dias_max)) 					
						$total_dias = $total_dias + $reg[1]; 
					}
					return $total_dias;
				}

		$arr['Hoje................']	= qt_comp($result, 0);
		$arr['Ontem...............'] 	= qt_comp($result, 1);  
		$arr['Há 2 dias...........'] 	= qt_comp($result, 2); 
		$arr['Há 3 dias...........'] 	= qt_comp($result, 3); 
		$arr['Há 4 dias...........'] 	= qt_comp($result, 4); 
		$arr['Há mais de 4 dias...'] 	= ha_mais_de($result, 4,30);      // De 4 dias a 1 mês...
		$arr['Há mais de 1 mes....'] 	= ha_mais_de($result, 29,180);	  // De 1 mês a 6 meses...
		$arr['Há mais de 6 meses..'] 	= ha_mais_de($result, 179,365);	  // De 6 meses a 1 ano...		
		$arr['Há mais de 1 ano....'] 	= ha_mais_de($result, 364,99999); // De 1 ano em diante...

		$CreatePie = 1;
		$Sort      = 1;
		$PieSize   = 159;			
		phPie($arr, 420 , $PieSize, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort); 
?>