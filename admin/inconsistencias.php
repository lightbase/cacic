<?
session_start();
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
      - Relat&oacute;rio de Cadastros Inconsistentes</strong></font></td>
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
	 $query = "SELECT nr_patrimonio 
		   FROM (SELECT nr_patrimonio, nm_computador, count(*) 
			 FROM softwares_estacao 
			 WHERE (dt_desinstalacao IS NULL) 
			 GROUP BY nr_patrimonio, nm_computador) as temp 
		   GROUP BY nr_patrimonio HAVING count(*) > 1";

	$result = mysql_query($query) or die();

	 $query2 = "SELECT nm_computador 
		   FROM (SELECT nr_patrimonio, nm_computador, count(*) 
			 FROM softwares_estacao 
			 WHERE (dt_desinstalacao IS NULL) 
			 GROUP BY nr_patrimonio, nm_computador) as temp 
		   GROUP BY nm_computador HAVING count(*) > 1";

	$result2 = mysql_query($query2) or die();

	 $queryEmBranco = "SELECT DISTINCT nr_patrimonio 
			   FROM softwares_estacao 
			   WHERE (dt_desinstalacao IS NULL) AND 
				 (nm_computador IS NULL) 
			   ORDER BY nr_patrimonio 
			   LIMIT 500";

	$resultEmBranco = mysql_query($queryEmBranco) or die();

?>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td align="center" nowrap></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
        <tr bgcolor="#E1E1E1"> 
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap><div align="left"><strong></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Patrim&ocirc;nio</font></strong></div></td>
          <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nr_patrimonio']; ?></font></div></td>
	  <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
	while($row = mysql_fetch_array($result2)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nm_computador']; ?></font></div></td>
	  <td nowrap>&nbsp;</td>
          <? 
	$Cor=!$Cor;
	$NumRegistro++;
	}
	
	while($row = mysql_fetch_array($resultEmBranco)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nr_patrimonio']; ?></font></div></td>
	  <td nowrap>&nbsp;</td>
          <? 
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
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
  </tr>
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>	

</body>
</html>
