<?
 /* 
 */
session_start();

	$v_aquisicao = $_GET['id_aquisicao'];
	$v_processo = $_GET['nr_processo'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Softwares por Aquisi&ccedil;&atilde;o</title>
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
    <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
        - Softwares por Aquisi&ccedil;&atilde;o</strong></font></div></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();

   $query =  "SELECT s.nm_software, ai.qt_licenca, tl.te_tipo_licenca  
		FROM aquisicoes_item ai, softwares s, tipos_licenca tl  
		WHERE (ai.id_software = s.id_software) AND (ai.id_tipo_licenca = tl.id_tipo_licenca) AND  
		      (ai.id_aquisicao = '" . $v_aquisicao . "') ORDER BY nm_software";

	$result = mysql_query($query) or die ('Erro no select');

$cor = 0;
$num_registro = 1;

echo '<table align="center" width="80%"><tr><td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Processo: ' . $v_processo . '</strong></font></td></tr></table>';

$fields=mysql_num_fields($result);
echo '<table align="center" width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">Software</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">Licen&ccedil;as</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">Tipo Licen&ccedil;a</font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
    echo "<td nowrap align='center'><font size='1' face='Verdana, Arial'><a href='softwares_aquisicao.php?id_aquisicao=". $row[0] . "'>" . $row[0] ."</a>&nbsp;</td>"; 
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[1] .'&nbsp;</td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[2] .'&nbsp;</td>';
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';

?></p>
<p></p>
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>
</body>
</html>
