<?
session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}

require_once($_SERVER['DOCUMENT_ROOT'] . 'include/library.php');
conecta_bd_cacic();

if ($_GET['principal'])
	{
	$query = ' SELECT 	id_so, 
			   FROM   	so';	   
	$result = mysql_query($query) or die('Erro no select');
	$_SESSION["list4"] = '';				
	while ($row = mysql_fetch_array($result))
		{
		if ($_SESSION["list4"] <> '') $_SESSION["list4"] .= '#';
		$_SESSION["list4"] .= $row['id_so'];
		}
	$_SESSION["list4"] = explode('#',$_SESSION["list4"]);					
	if ($_GET['orderby']=='6')
		{
		$_SESSION["list6"] = explode('#',', dt_hr_ult_acesso as "Último Acesso"');
//		$_SESSION["list6"] = explode('#',', DATE_FORMAT(dt_hr_ult_acesso,"%d-%m-%Y %h:%i:%s") as "Último Acesso"');		
		}
	else
		{
		$_SESSION["list6"] = explode('#','');							
		}	
	$_SESSION["cs_situacao"] 	= 'T';
	}
elseif($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es de Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<?
if ($_GET['principal'])
	{
	echo '<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif">';
	}
else
	{
	echo '<body bgcolor="#FFFFFF" topmargin="5">';
	}
	?>

<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF"><? if (!$_GET['principal']) echo '<img src="../../imgs/cacic_logo.png" width="50" height="50">'; ?> </td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
	<?
	if (!$_GET['orderby'])
		{
		echo 'Relat&oacute;rio de Configura&ccedil;&otilde;es de Software';
		}
	elseif ($_GET['orderby'] == 4)
		{
		echo 'Distribui&ccedil;&atilde;o de sistemas operacionais dos computadores gerenciados';
		}
	elseif ($_GET['orderby'] == 6)
		{
		echo 'Distribui&ccedil;&atilde;o do último acesso dos agentes';
		}
		
	?>
	</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<? 
	if (!$_GET['principal'])
		{
		echo 'Gerado em ' . date("d/m/Y à\s H:i"); 
		}
	?>
	</font></p></td>
  </tr>
</table>
<br>
<? 
$redes_selecionadas = '';
if($_SESSION["cs_situacao"] == 'S') 
	{
	// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
		{
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
		}
	if (!$_GET['principal']) $query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}

// Aqui pego todas as configurações de software que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	$campos_software = $campos_software . $_SESSION["list6"][$i];
	}
// Aqui substitui todas as strings \ por vazio que a variável $campos_software retorna
$campos_software = str_replace('\\', '', $campos_software);


if ($_GET['orderby']) 
	{ 
	$orderby = $_GET['orderby']; 
	}
//else { $orderby = '1'; }
else 
	{ 
	$orderby = '3'; 
	} //por Nome de Computador
/*
$query = ' SELECT computadores.te_node_address, so.id_so, te_nome_computador as "Nome Comp.", sg_so as "S.O.", te_ip as "IP"' .
          $campos_software .
		 ' FROM computadores, so, versoes_softwares
		   WHERE  computadores.id_so = so.id_so AND computadores.id_so IN ('. $so_selecionados .')  
					and computadores.te_node_address = versoes_softwares.te_node_address and computadores.id_so = versoes_softwares.id_so 
		   '. $query_redes .' 
		   ORDER BY ' . $orderby; 
*/

$query = ' SELECT 	distinct computadores.te_node_address, 
					so.id_so, 
					te_nome_computador as "Nome Comp.", 
					sg_so as "S.O.", 
					te_ip as "IP"' . $campos_software .
		 ' FROM   	computadores,so LEFT JOIN versoes_softwares ON (so.id_so = versoes_softwares.id_so and
		  															computadores.id_so = versoes_softwares.id_so and
																	versoes_softwares.te_node_address = computadores.te_node_address)		 		 
		   WHERE  	trim(computadores.te_nome_computador) <> ""  and
		   			computadores.id_so = so.id_so and
					computadores.id_so IN ('. $so_selecionados .') '. $query_redes .' 
		   ORDER BY ' . $orderby;

	if ($_GET['orderby'] == 6)
		{
		$query .= ' desc';
		}

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
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
	}
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'software'; // Nome do pie que será chamado por tabela_estatisticas
	require_once($_SERVER['DOCUMENT_ROOT'] . 'include/tabela_estatisticas.php');
	}

?></p>
<?
if (!$_GET['principal']) 
	{
	?>
	<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
	gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  	de Informa&ccedil;&otilde;es Computacionais</font><br>
  	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  	pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>
	<?
	}
	?>
</body>
</html>
