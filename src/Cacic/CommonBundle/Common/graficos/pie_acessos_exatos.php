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
		  
   include_once '../include/library.php'; 
   include '../include/piechart.php';
   conecta_bd_cacic();
   $query ='SELECT to_days(curdate()) - to_days(dt_hr_ult_acesso) as nr_dias, count(*)
			         FROM computadores 
											 WHERE  computadores.te_nome_computador IS NOT NULL 
												AND dt_hr_ult_acesso is not null
												GROUP BY nr_dias';

   $result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!');

			function qt_comp($result, $num_dias) 
				{
				mysql_data_seek($result, 0);
				while ($reg = mysql_fetch_array($result)) 
					{
  					if ($reg[0] == $num_dias) return $reg[1]; 
					}
				}

			function ha_mais_de($result, $num_dias) 
				{
   	    		$total_dias = 0;
				mysql_data_seek($result, 0);
				while ($reg = mysql_fetch_array($result)) 
					{
   					if ($reg[0] > $num_dias) 
						$total_dias = $total_dias + $reg[1]; 
					}
					return $total_dias;
				}

		$arr['Hoje................']	= qt_comp($result, 0); 
		$arr['Ontem...............'] 	= qt_comp($result, 1);  
		$arr['H� 2 dias...........'] 	= qt_comp($result, 2); 
		$arr['H� 3 dias...........'] 	= qt_comp($result, 3); 
		$arr['H� 4 dias...........'] 	= qt_comp($result, 4); 
		$arr['H� mais de 4 dias...'] 	= ha_mais_de($result, 4);				

		$CreatePie = 1;
		$Sort = 1;
		phPie($arr, 420 ,	159, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort); 
?>