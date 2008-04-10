<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores com nomes repetidos na base
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
        em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?
conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';
?>
<?
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
		          count(*) as QTDE 
		FROM computadores 
		WHERE te_placa_mae_desc LIKE '%IBM%' 
		GROUP BY Memoria, CPUFabricante, CPUFreq 
		ORDER BY qt_mem_ram"; 
	$result = mysql_query($query) or die();

	$queryDisco = "SELECT CASE WHEN (tbl.capacidade < 80000) AND (tbl.capacidade > 76200) THEN 80000  
				   WHEN (tbl.capacidade < 40000) AND (tbl.capacidade > 38120) THEN 40000 
				   WHEN (tbl.capacidade < 120000) AND (tbl.capacidade > 114400) THEN 120000 
				   WHEN (tbl.capacidade < 160000) AND (tbl.capacidade > 152500) THEN 160000 
				   ELSE tbl.capacidade 
			      END AS disco,
			      count(*) AS QTDE 
			FROM (SELECT SUM(nu_capacidade) AS capacidade 
	      			FROM unidades_disco u, computadores c 
	      			WHERE (u.id_tipo_unid_disco = 2) AND 
		    			(c.te_node_address = u.te_node_address) AND 
		    			(c.te_placa_mae_fabricante LIKE '%IBM%') 
	      			GROUP BY u.te_node_address, u.id_so 
	      			ORDER BY capacidade) as tbl 
			GROUP BY disco 
			ORDER BY disco";
	$resultDisco = mysql_query($queryDisco) or die();
?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="6" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Frequ&ecirc;ncia da CPU</font></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Mem&oacute;ria RAM</font></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fabricante da CPU</font></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Quantidade</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	$TotalMaquinas = 0;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['CPUFreq']; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['Memoria']; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['CPUFabricante']; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="mostra_ibms.php?CPUFreq=<? echo $row['CPUFreq'];?>&Memoria=<? echo $row['Memoria'];?>&CPUFabricante=<? echo $row['CPUFabricante'];?>" target="_blank"><? echo $row['QTDE']; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	$TotalMaquinas += $row['QTDE'];
	}
?>
	</tr><tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
	<td align="center" nowrap colspan=9><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Total de M&aacute;quinas</font></strong></div></td>
	<td align="center" nowrap><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $TotalMaquinas; ?></font></strong></div></td>
	<td align="center" nowrap>&nbsp;&nbsp;</td></tr>
      </table></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="30"></td>
  </tr>
  <tr>
    <td><table border="0" cellpadding="6" cellspacing="0" bordercolor="#333333" align="center">
	<tr><td colspan=7 height="1" bgcolor="#333333"></td></tr>
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Tamanho do Disco</font></strong></div></td>
          <td align="center"  nowrap>&nbsp;&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Quantidade</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	$TotalMaquinas = 0;
	
	while($row = mysql_fetch_array($resultDisco)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['disco']; ?> (MB)</font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="mostra_ibms.php?disco=<? echo $row['disco'];?>" target="_blank"><? echo $row['QTDE']; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	$TotalMaquinas += $row['QTDE'];
	}
?>
	</tr><tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
	<td align="center" nowrap colspan=5><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Total de M&aacute;quinas</font></strong></div></td>
	<td align="center" nowrap><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $TotalMaquinas; ?></font></strong></div></td>
	<td align="center" nowrap>&nbsp;&nbsp;</td></tr>
	<tr><td colspan=7 heigth="1" bgcolor="#333333"></td></tr>
      </table></td>
  </tr>


</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
