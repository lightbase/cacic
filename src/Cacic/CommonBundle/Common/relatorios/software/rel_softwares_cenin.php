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
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic3/include/library.php');
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
    <td rowspan="5" bgcolor="#FFFFFF"><img src="/cacic3/imgs/cacic_novo.gif" width="50" height="50"></td>
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
<table border="0" align="center" width="300" >
  <tr> 
    <td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><?php echo stripslashes($_GET['nm_software_inventariado']); ?></strong></font></td>
  </tr>
</table>
<?php
	$queryCENIN = " ((a.te_nome_computador LIKE '%CEACE%') OR 
			(a.te_nome_computador LIKE '%CENIN%') OR 
			(a.te_nome_computador LIKE '%CESAN%') OR 
			(a.te_nome_computador LIKE '%COAUS%') OR 
			(a.te_nome_computador LIKE '%CODIS%') OR 
			(a.te_nome_computador LIKE '%COINF%') OR 
			(a.te_nome_computador LIKE '%CORED%') OR 
			(a.te_nome_computador LIKE '%COREL%') OR 
			(a.te_nome_computador LIKE '%COSERV%') OR 
			(a.te_nome_computador LIKE '%COSEV%') OR 
			(a.te_nome_computador LIKE '%SATUS%') OR 
			(a.te_nome_computador LIKE '%SAUTE%') OR 
			(a.te_nome_computador LIKE '%SEADI%') OR 
			(a.te_nome_computador LIKE '%SEANS%') OR 
			(a.te_nome_computador LIKE '%SECONS%') OR 
			(a.te_nome_computador LIKE '%SEGAB%') OR 
			(a.te_nome_computador LIKE '%SEGER%') OR 
			(a.te_nome_computador LIKE '%SEIAD%') OR 
			(a.te_nome_computador LIKE '%SEMAD%') OR 
			(a.te_nome_computador LIKE '%SEMIR%') OR 
			(a.te_nome_computador LIKE '%SEOPR%') OR 
			(a.te_nome_computador LIKE '%SEPAS%') OR 
			(a.te_nome_computador LIKE '%SERAN%') OR 
			(a.te_nome_computador LIKE '%SESAP%') OR 
			(a.te_nome_computador LIKE '%SESAT%') OR 
			(a.te_nome_computador LIKE '%SESEG%') OR 
			(a.te_nome_computador LIKE '%SESEN%') OR 
			(a.te_nome_computador LIKE '%SESUP%') OR 
			(a.te_nome_computador LIKE '%SETES%') OR 
			(a.te_nome_computador LIKE '%SINCO%')) ";
	 $query = "SELECT a.te_nome_computador, a.te_node_address, a.id_so,
   			a.te_ip_computador, a.dt_hr_ult_acesso, b.nm_software_inventariado, a.id_computador  
		FROM computadores a, softwares_inventariados b,
		     softwares_inventariados_estacoes c
		WHERE a.id_computador = c.id_computador  
	 	  AND b.id_software_inventariado = " 
			. $_GET['id_software_inventariado'] . " 
		  AND b.id_software_inventariado = 
			c.id_software_inventariado 
		  AND $queryCENIN
		ORDER BY a.te_nome_computador"; 
	$result = mysql_query($query) or die();
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
