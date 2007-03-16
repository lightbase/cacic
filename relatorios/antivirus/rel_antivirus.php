<? session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}

if($_POST['submit']) {
	$_SESSION["list2"] 	= $_POST['list2'];
	$_SESSION["list4"] 	= $_POST['list4'];
	$_SESSION["list6"] 	= $_POST['list6'];
	$_SESSION["list8"] 	= $_POST['list8'];	
	$_SESSION["list10"]	= $_POST['list10'];		
	$_SESSION["list12"]	= $_POST['list12'];			
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es do Antiv&iacute;rus OfficeScan</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
	
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Configura&ccedil;&otilde;es do Antiv&iacute;rus OfficeScan</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i\h"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	if($_SESSION["cs_situacao"] == 'S') 
		{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes	
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			{
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
			}
		}
		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		{
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";
		}
	$query_redes = 'AND computadores.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';	
	}	

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}

// Aqui pego todas as configurações de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$campos_software = $campos_software . $_SESSION["list6"][$i];
}
// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_software = str_replace('\\', '', $campos_software);

if ($_GET['orderby']) { $orderby = $_GET['orderby']; }

else { $orderby = 'te_nome_computador'; }

$query = 'SELECT 	distinct computadores.te_node_address, 
					so.id_so, 
					te_nome_computador as "Nome Comp.", 
					sg_so as "S.O.", 
					te_ip as "IP"' .
          			$campos_software . 
					$select . ' 
		  FROM 		computadores,
					so LEFT JOIN officescan ON (computadores.te_node_address = officescan.te_node_address and computadores.id_so = officescan.id_so) '.
					$from. ' 		 
		  WHERE  	TRIM(computadores.te_nome_computador) <> "" AND 
		  			computadores.id_so = so.id_so AND 
					computadores.id_so IN ('. $so_selecionados .')'. 
		  			$query_redes .' 
		  ORDER BY ' . $orderby; 
$result = mysql_query($query) or die('Erro no select');

$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

for ($i=2; $i < mysql_num_fields($result); $i++) { //Table Header
   print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
}
echo '</tr>';

while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) {
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial" ';
		if 		($row[$i] == 'N') 
			{echo 'color="#FF0000"><strong>N</strong>';}
		else 	
			{echo '>'.$row[$i]; }
		echo '&nbsp;</td>'; 		
	}
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table><br><br>';
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'antivirus'; // Nome do pie que será chamado por tabela_estatisticas
	require_once('../../include/tabela_estatisticas.php');
	}
?></p>

<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>
</body>
</html>
