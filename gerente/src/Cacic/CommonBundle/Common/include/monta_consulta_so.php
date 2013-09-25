<?php
$query = 'SELECT 	count(computadores.id_so) as qtd, 
					so.te_desc_so 
		  FROM		computadores,
		  			so,
					redes
		  WHERE 	computadores.id_so = so.id_so AND 
					computadores.dt_hr_ult_acesso IS NOT NULL AND
					computadores.id_rede = redes.id_rede '.
					$where . ' 
		  GROUP BY 	so.te_desc_so 
		  ORDER BY 	qtd DESC';

conecta_bd_cacic();		  
$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('computadores, redes, so')));

$arr_so = array();
while ($row_result = mysql_fetch_assoc($result))		
	{ 
	$v_row_result = str_pad($row_result['te_desc_so'],35,'.',STR_PAD_RIGHT);
	$arr_so[$v_row_result] = $row_result['qtd'];			
	} 
		  
?>