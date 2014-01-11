<?php
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

$query = 'SELECT 	count(id_computador) as qtd
          FROM 		computadores,
					redes,
					so
		  WHERE 	computadores.dt_hr_ult_acesso   IS NOT NULL AND
					computadores.id_rede = redes.id_rede AND 
					computadores.id_so = so.id_so '.
					$where .' 
		  GROUP BY 	computadores.te_node_address';
$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('computadores, redes, so')));

$v_row_result = $oTranslator->_('Quantidade real baseada em MAC-Address');

$arr_mac = array();
$arr_mac[$v_row_result] = 0;
while ($row_result = mysql_fetch_assoc($result))		
	$arr_mac[$v_row_result] ++ ;

?>