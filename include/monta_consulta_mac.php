<?	  
$query = 'SELECT 	count(*) as qtd
          FROM 		computadores,
					redes,
					so
		  WHERE 	computadores.te_nome_computador IS NOT NULL AND 
			 	   	computadores.dt_hr_ult_acesso   IS NOT NULL AND
					computadores.id_ip_rede = redes.id_ip_rede AND 
					computadores.id_so = so.id_so '.
					$where .' 
		  GROUP BY 	computadores.te_node_address';

$result = mysql_query($query) or die('Erro no select ou sua sessão expirou!');

$v_row_result = 'Quantidade Real Baseada em Mac-Address';
session_register('arr_mac');
$_SESSION['arr_mac'] = array();
$_SESSION['arr_mac'][$v_row_result] = 0;
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$_SESSION['arr_mac'][$v_row_result] ++ ;
	} 
?>