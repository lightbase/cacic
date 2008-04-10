<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores baseados no tipo de pesquisa solicitada pelo usuário
require_once('../../../include/library.php');
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
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Invent&aacute;rio de Softwares</strong></font></td>
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
<table border="0" align="center" width="300" >
  <tr> 
    <td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><? echo stripslashes($_GET['nm_software_inventariado']); ?></strong></font></td>
  </tr>
</table>
<?
	 $query = "SELECT se.nr_patrimonio, se.nm_computador, se.dt_autorizacao, se.nr_processo, se.id_aquisicao_particular 
		FROM softwares_estacao se 
		WHERE (se.id_software = " . $_GET['id_software_inventariado'] . ") 
		  AND (se.nm_computador LIKE '%" . $_GET['nm_maquina'] . "%') AND 
		      (se.dt_desinstalacao IS NULL)  
		ORDER BY se.nm_computador, se.nr_patrimonio";
	$result = mysql_query($query) or die('Erro ao acessar tabela softwares_estacao ou sua sessão expirou!');
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
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Computador</font></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Patrim&ocirc;nio</font></strong></div></td>
          <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Autoriza&ccedil;&atilde;o</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Processo</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Particular</font></strong></div></td>
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
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nm_computador']; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nr_patrimonio']; ?></div></td>
          <td nowrap>&nbsp;</td>
          <? if ($row['dt_autorizacao']) { ?>
	  <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo date("d/m/Y", strtotime($row['dt_autorizacao'])); ?></font></td>
          <? } else { ?>
          <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
	  <? } ?>
	  <td nowrap>&nbsp;</td>
	  <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nr_processo']; ?></font></td>
	  <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center">
		<font size="2" face="Verdana, Arial, Helvetica, sans-serif">
		<? if ($row['id_aquisicao_particular']) 
			echo "<a href='softwares_aquisicao.php?id_aquisicao=" . $row['id_aquisicao_particular'] . "'>SIM</a>";
		   else 
			echo 'N&Atilde;O';
		?>		
		</font></div></td>
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
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
