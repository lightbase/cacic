<?
$query = 'SELECT 	count(a.te_node_address) as total,
					c.sg_local
		  FROM		computadores a,
					redes b,
					locais c,
					so d
		  WHERE 	a.te_nome_computador IS NOT NULL AND 
					a.id_ip_rede = b.id_ip_rede AND
					b.id_local = c.id_local AND
					a.id_so = d.id_so AND 
					DATE_FORMAT(dt_hr_ult_acesso, "%d%m%Y") = "'.date("dmY").'"
		  GROUP BY 	c.sg_local
		  ORDER BY  c.sg_local';
conecta_bd_cacic();
$result = mysql_query($query) or die('Falha na consulta (computadores, redes, locais) ou sua sessão expirou!');

$arr_acessos_locais = array();
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$v_row_result = str_pad($row_result['sg_local'],20,'.',STR_PAD_RIGHT);
	$arr_acessos_locais[$v_row_result] = $row_result['total'];			
	} 
?>