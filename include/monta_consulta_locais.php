<?
session_start();
// ===========================================================================================================
// Passei a restringir a visão das estatísticas na página principal ao nível de acesso atual
// ===========================================================================================================
	$where 	= ($_SESSION['cs_nivel_administracao'] <> 1 &&
			   $_SESSION['cs_nivel_administracao'] <> 2 ? ' AND redes.id_local = '.$_SESSION['id_local']:'');

	// Caso hajam locais secundários associados ao usuário, incluo-os na cláusula Where
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
		$where = str_replace('redes.id_local = ','(redes.id_local = ',$where);
		$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}					   
// ==========================================================================================================

$query = 'SELECT 	count(computadores.te_node_address) as total,
					locais.sg_local
		  FROM		computadores,
					redes,
					locais,
					so
		  WHERE 	computadores.te_nome_computador IS NOT NULL AND 
					computadores.id_ip_rede = redes.id_ip_rede AND
					computadores.id_so = so.id_so AND
					redes.id_local = locais.id_local '.$where.' 
		  GROUP BY 	locais.sg_local
		  ORDER BY  locais.sg_local';
conecta_bd_cacic();
$result = mysql_query($query) or die('Falha na consulta (computadores, redes, locais) ou sua sessão expirou!');

$arr_locais = array();
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$v_row_result = str_pad($row_result['sg_local'],15,'.',STR_PAD_RIGHT);
	$arr_locais[$v_row_result] = $row_result['total'];			
	} 

/* Para testes de redimensionamento da pizza (Anderson Peterle - FEV2008)
$arr_locais = array();
$intLinhas = 0;
while ($intLinhas <= 100)		
	{ 
	$v_row_result = str_pad($intLinhas,15,'.',STR_PAD_RIGHT);
	$arr_locais[$v_row_result] = $intLinhas;			
	$intLinhas ++;
	} 
*/
	
?>