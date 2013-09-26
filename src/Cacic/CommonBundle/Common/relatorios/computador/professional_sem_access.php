<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
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
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC - Relat&oacute;rio de M&aacute;quinas com Office Professional sem Access</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
  <tr><td></td></tr>
  <tr><td height="1"></td></tr>
</table>
<br>
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
<?php
	 $query = "SELECT c.te_nome_computador as nm_maquina, 
			  c.te_node_address, c.id_so, c.te_ip_computador, 
			  c.dt_hr_ult_acesso,c.id_computador  
		FROM computadores c, aplicativos_monitorados am, 
		     softwares_inventariados_estacoes sie 
		WHERE (sie.id_software_inventariado = 105) AND 
		      (am.id_computador = sie.id_computador) AND
		      (am.id_aplicativo = 59) AND
		      (am.te_versao = '?') AND 
		      (c.id_computador = sie.id_computador)
		ORDER BY c.te_nome_computador"; 
	$result = mysql_query($query) or die('Erro no acesso às tabelas "computadores", "aplicativos_monitorados" e "softwares_inventariados_estacoes" ou sua sessão expirou!');
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
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Nome
              da M&aacute;quina</font></strong></div></td>
          <td nowrap >&nbsp;&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">IP</font></strong></div></td>
          <td nowrap >&nbsp;&nbsp;</td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">&Uacute;ltima Coleta</font></strong></div></td>
	  <td nowrap >&nbsp;&nbsp;</td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $NumRegistro; ?></font></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="../../../relatorios/computador/computador.php?id_computador=<?php echo $row['id_computador'];?>" target="_blank"><?php echo $row['nm_maquina']; ?></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['te_ip_computador']; ?></font></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo date("d/m/Y H:i", strtotime($row['dt_hr_ult_acesso'])); ?></font></td>
	  <td nowrap>&nbsp;&nbsp;</td>
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
</table>
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
