
<?
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

include_once "../../include/library.php";
Conecta_bd_cacic();
$texto = "";
echo "Rede: ".$_REQUEST['rede'];
$queryResult = "SELECT * from softwares_inventariados_estacoes b, computadores c,so s, historico_softwares_inventariados_estacoes h ";
$queryResult .= "where h.ind_acao=1 and b.id_software_inventariado = h.id_software_inventariado and b.te_node_address = h.te_node_address and c.te_node_address=h.te_node_address and c.id_so = s.id_so and  b.te_node_address  = c.te_node_address AND b.id_so = c.id_so and  b.id_software_inventariado=".$_REQUEST['id_software_inventariado'];	

if ($_REQUEST['rede']!="0"){
	$queryResult .=" and c.id_ip_rede='".$_REQUEST['rede']."'";
}
echo $queryResult;
$RResult = mysql_query($queryResult);
$today=date('m-d-Y');	

while($row = mysql_fetch_array($RResult)) {		
	$access_day = explode('-',$row['dt_hr_ult_acesso']);	
	$diference = date_difference(trim(substr($access_day[1],0,2)).'-'.$access_day[2].'-'.$access_day[0],$today);	
	
	if ($diference > 4) // Acima de 5 dias
		$img_date = '<img src=http://' . $_SERVER['HTTP_HOST'] . '/cacic2/imgs/arvore/tree_computer_red.gif title=�ltimo_acesso_realizado_h�_mais_de_5_dias_(120_horas) width=16 height=16 hspace=5>';
	else if($diference > 0) // At� 5 dias
		$img_date = '<img src=http://' . $_SERVER['HTTP_HOST'] . '/cacic2/imgs/arvore/tree_computer_yellow.gif title=�ltimo_acesso_realizado_h�_at�_5_dias_(120_horas) width=16 height=16 hspace=5>';
	else // At� 1 dia
		$img_date = '<img src=http://' . $_SERVER['HTTP_HOST'] . '/cacic2/imgs/arvore/tree_computer_green.gif title=�ltimo_acesso_realizado_h�_at�_1_dia_(at�_24_horas) width=16 height=16 hspace=5>';

	$texto .= "&nbsp;&nbsp;&nbsp;&nbsp;".$img_date."";
	$texto .= "<a href=../computador/computador.php?te_node_address=".$row['te_node_address']."&id_so=".$row['id_so']." target=_blank title=Atualizado:".date('d/m/Y',strtotime($row['dt_hr_ult_acesso']))."  >";
	$texto .= date('d/m/Y - h:m:i',strtotime($row['data']))." - ".$row['te_nome_computador']." - ".$row['te_desc_so']." - ".$row['te_workgroup']."(".$row['te_dominio_windows'].")";
	$texto .="</a><br><img src=linha.gif width=600><br>";
}
echo $texto;
?>
<script>
parent.document.getElementById('texto_<? echo $_REQUEST['id_software_inventariado']?>').innerHTML= "<? echo $texto?>";
parent.mostratab('<? echo $_REQUEST['id_software_inventariado']?>');
//alert (parent.document.all["texto_<? echo $_REQUEST['id_software_inventariado']?>"].innerHTML);
</script>
