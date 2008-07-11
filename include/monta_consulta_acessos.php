<?
$query ='SELECT 	to_days(curdate()) - to_days(dt_hr_ult_acesso) as nr_dias, count(*)
		 FROM 		computadores,
					redes,
					so
		 WHERE  	computadores.id_so = so.id_so AND
		 			computadores.te_nome_computador IS NOT NULL AND 
		 			computadores.dt_hr_ult_acesso IS NOT NULL AND
					computadores.id_ip_rede = redes.id_ip_rede '.
					$where . ' 
		 GROUP BY 	nr_dias';
conecta_bd_cacic();		 
$result = mysql_query($query) or die('Falha na criação de consulta por acessos de estações ou sua sessão expirou!');

$intSum = 0;
if (!function_exists('qt_comp'))
	{
	function qt_comp($myResult, $num_dias) 
		{
		global $intSum;
		@mysql_data_seek($myResult, 0);
		while ($reg = @mysql_fetch_array($myResult)) 
			{
			if ($reg[0] == $num_dias) 
				{
				$intSum += $reg[1];
				return $reg[1]; 
				}
			}
		return 0;
		}
	}		

if (!function_exists('ha_mais_de'))
	{
	function ha_mais_de($myResult, $num_dias_min, $num_dias_max) 
		{
		global $intSum;
		$total_dias = 0;
		@mysql_data_seek($myResult, 0);
		while ($reg = mysql_fetch_array($myResult)) 
			{
			if (($reg[0] > $num_dias_min) &&
				($reg[0] < $num_dias_max)) 					
				{
				$intSum += $reg[1];
				$total_dias = $total_dias + $reg[1]; 
				}
			}
			return $total_dias;
		}
	}
		
session_register('arr_acessos');		
$arr_acessos = array();
$arr_acessos['Hoje........................'] = qt_comp($result, 0);
$arr_acessos['Há 1 dia....................'] = qt_comp($result, 1);  
$arr_acessos['Há 2 dias...................'] = qt_comp($result, 2); 
$arr_acessos['Há 3 dias...................'] = qt_comp($result, 3); 
$arr_acessos['Há 4 dias...................'] = qt_comp($result, 4); 
$arr_acessos['De 5 a 30 dias..............'] = ha_mais_de($result, 4,30);      // De 4 dias a 1 mês...
$arr_acessos['De 30 a 180 dias............'] = ha_mais_de($result, 29,180);	  // De 1 mês a 6 meses...
$arr_acessos['De 180 a 365 dias...........'] = ha_mais_de($result, 179,365);	  // De 6 meses a 1 ano...		
$arr_acessos['Há mais de 365 dias.........'] = ha_mais_de($result, 364,99999); // De 1 ano em diante...

if ($intSum == 0)
	{
	$arr_acessos = array('a');
	}
?>