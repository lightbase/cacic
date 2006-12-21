
<?
/*session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/cacic2/verificar.php';
if ($_POST['submit']) {
  header ("Location: incluir_grupos.php");
}*/
include_once "../../include/library.php";
Conecta_bd_cacic();
$texto = "";
$v_data_fim = $_REQUEST['v_data_fim'];
$v_data_ini = $_REQUEST['v_data_ini'];
$te_node_address = $_REQUEST['te_node_address'];

$queryResult = "SELECT b.nm_software_inventariado,h.data,h.ind_acao from softwares_inventariados b ,historico_softwares_inventariados_estacoes h";
$queryResult .= " where h.id_software_inventariado = b.id_software_inventariado and h.te_node_address = '".$te_node_address."'";	

if ($v_data_ini!=""){
	$queryResult .=" and  DATE_FORMAT(h.data, '%Y/%m/%d') between '".$v_data_ini."' and '".$v_data_fim."'";
}
$queryResult .= " order by nm_software_inventariado,data";
$RResult = mysql_query($queryResult);
$today=date('m-d-Y');	

while($row = mysql_fetch_array($RResult)) {		
	if($row['ind_acao'] == 1){
		$tipo = "Inserido";	
		$cor = "#006600";	
	} else {
		$tipo = "Removido";		
		$cor = "#FF0000";
	}
	$texto .= "<font color=".$cor.">".$tipo." em ".date('d/m/Y - h:m:i',strtotime($row['data']))." - ".$row['nm_software_inventariado']."</font>";
	$texto .="<br><img src=../inventario_softwares/linha.gif width=600><br>";
}
echo $texto."<br>";
echo "--".$te_node_address
?>
<script>
parent.document.getElementById('texto_<? echo $_REQUEST['te_nome_computador']?>').innerHTML= "<? echo $texto?>";
parent.mostratab('<? echo $_REQUEST['te_nome_computador']?>');
</script>