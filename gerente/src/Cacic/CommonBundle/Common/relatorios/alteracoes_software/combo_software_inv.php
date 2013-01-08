<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include_once "../../include/library.php";
Conecta_bd_cacic();
$texto 		   = "";
$v_data_fim    = $_REQUEST['v_data_fim'];
$v_data_ini    = $_REQUEST['v_data_ini'];
$id_computador = $_REQUEST['id_computador'];

$queryResult = "SELECT b.nm_software_inventariado,h.data,h.ind_acao from softwares_inventariados b ,historico_softwares_inventariados_estacoes h";
$queryResult .= " where h.id_software_inventariado = b.id_software_inventariado and h.id_computador = ".$id_computador;	

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
parent.document.getElementById('texto_<?php echo $_REQUEST['te_nome_computador']?>').innerHTML= "<?php echo $texto?>";
parent.mostratab('<?php echo $_REQUEST['te_nome_computador']?>');
</script>