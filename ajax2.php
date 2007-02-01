<? 
//require_once('include/library.php');
//conecta_bd_cacic();

/* Seleciona todos os perfis de aplicativos cadastrados para tratamento posterior */
/*
$query_monitorado = "	SELECT 		*
						FROM 		usuarios
						WHERE       id_aplicativo = b.id_aplicativo AND
									a.nm_aplicativo NOT LIKE '%#DESATIVADO#%' AND
									b.id_ip_rede = '".$v_dados_rede['id_ip_rede']."' AND
									b.id_local = ".$v_dados_rede['id_local']." 										
						ORDER BY	a.id_aplicativo";

	$result_monitorado 	= mysql_query($query_monitorado);
	$v_tripa_perfis1 = explode('#',$te_tripa_perfis);
*/	
$retorno_xml_values	 = 'Valor1,1;Valor2,2;Valor3,3';
echo $retorno_xml_values;
?>
