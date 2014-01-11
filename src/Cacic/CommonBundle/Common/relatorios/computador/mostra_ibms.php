<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usuário
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Softwares Inventariados por M&aacute;quinas</title>
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
    <td rowspan="5" bgcolor="#FFFFFF"><img src="/cacic2/imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de M&aacute;quinas IBM</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?php
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<?php if ($_GET['disco'] == '') {
	 $query = "SELECT CASE WHEN ((te_cpu_freq >= 2390) AND (te_cpu_freq <= 2533)) THEN 2400  
			       WHEN ((te_cpu_freq >= 2790) AND (te_cpu_freq <= 2800)) THEN 2800 
			       ELSE te_cpu_freq 
	                  END AS CPUFreq,
			  CASE WHEN ((qt_mem_ram >= 228) AND (qt_mem_ram <= 261)) THEN 256 
			       WHEN ((qt_mem_ram >= 490) AND (qt_mem_ram <= 523)) THEN 512 
			       WHEN ((qt_mem_ram >= 1024) AND (qt_mem_ram <= 1047)) THEN 1024 
			       ELSE qt_mem_ram 
			  END AS Memoria, 
			  CASE WHEN (te_cpu_fabricante LIKE '%Intel%') THEN 'Intel' 
			       ELSE te_cpu_fabricante 
			  END AS CPUFabricante,
			  te_nome_computador, te_node_address, id_so,
   			  te_ip_computador, dt_hr_ult_acesso
		FROM computadores  
		WHERE (te_placa_mae_fabricante LIKE 'IBM') 
		HAVING (CPUFreq = " . $_GET['CPUFreq'] . ") AND (Memoria = " . $_GET['Memoria'] . ") AND 
			(CPUFabricante = '" . $_GET['CPUFabricante'] . "')  
		ORDER BY te_nome_computador"; 
	} else {
		$query = "SELECT CASE WHEN ((tbl.capacidade < 80000) AND (tbl.capacidade > 76200)) THEN 80000 
				      WHEN ((tbl.capacidade < 40000) AND (tbl.capacidade > 38120)) THEN 40000 
				      WHEN ((tbl.capacidade < 120000) AND (tbl.capacidade > 114400)) THEN 120000 
				      WHEN ((tbl.capacidade < 160000) AND (tbl.capacidade > 152500)) THEN 160000 
				      ELSE tbl.capacidade 
				END AS disco, te_nome_computador, te_node_address, id_so, te_ip_computador, dt_hr_ult_acesso 
			FROM (SELECT SUM(nu_capacidade) AS capacidade, te_nome_computador, c.te_node_address, c.id_so, te_ip_computador, dt_hr_ult_acesso  
				FROM unidades_disco u, computadores c 
				WHERE (id_tipo_unid_disco = 2) AND 
					(c.id_computador = u.id_computador) AND 
					(c.te_placa_mae_fabricante LIKE '%IBM%') 
				GROUP BY u.id_computador 
				ORDER BY capacidade) AS tbl 
			HAVING (disco = " . $_GET['disco'] . ") 
			ORDER BY te_nome_computador";
        } 	
	$result = mysql_query($query) or die('erro no select');
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <?php if ($_GET['disco'] == '') { 
  	echo '<tr>';
  	echo '<td align="center" nowrap><strong>Frequ&ecirc;ncia da CPU: ' .  $_GET['CPUFreq'] . '</strong></td>';
	echo '</tr>';
  	echo '<tr>';
    	echo '<td align="center" nowrap><strong>Mem&oacute;ria RAM: ' . $_GET['Memoria'] . '</strong></td>';
  	echo '</tr>';
  	echo '<tr>';
    	echo '<td align="center" nowrap><strong>Fabricante da CPU: ' . $_GET['CPUFabricante'] . '</strong></td>';
  	echo '</tr>';
     } else {
  	echo '<tr>';
  	echo '<td align="center" nowrap><strong>Tamanho do Disco: ' .  $_GET['disco'] . ' (MB)</strong></td>';
	echo '</tr>';
     }
  ?>
 
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nome
              da M&aacute;quina</font></strong></div></td>
          <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&Uacute;ltima Coleta</a></font></strong></div></td>
	  <td nowrap >&nbsp;</td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['te_nome_computador']; ?></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['te_ip_computador']; ?></font></td>
	  <td nowrap>&nbsp;</td>
	  <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo date("d/m/Y H:i", strtotime( $row['dt_hr_ult_acesso'] )); ?></font></div></td>
	  <td nowrap>&nbsp;</td>
          <?php 
	$Cor=!$Cor;
	$NumRegistro++;
	}
?>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Clique 
      sobre o nome da m&aacute;quina para ver os detalhes da mesma</font> </td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
