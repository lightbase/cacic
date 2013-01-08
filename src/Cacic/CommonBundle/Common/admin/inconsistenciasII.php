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
require_once('../include/library.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $oTranslator->_('Relatorio de softwares inventariados por maquinas');?></title>
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
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>
    CACIC - <?php echo $oTranslator->_('Relatorio de cadastros inconsistentes II');?></strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    	<?php echo $oTranslator->_('Gerado em');?> <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
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
<?php
	 $query = "SELECT DISTINCT nr_patrimonio, nm_computador  
	           FROM softwares_estacao 
		   WHERE (nm_computador NOT REGEXP '[0-9]$') AND 
			 (nm_computador NOT LIKE 'GAB____') AND 
			 (nm_computador NOT LIKE '*%') AND 
			 (nm_computador NOT LIKE 'BP-GAB____') AND 
			 (dt_desinstalacao IS NULL)  
		   ORDER BY nm_computador";

	$result = mysql_query($query) or die($oTranslator->_('kciq_msg select on table fail', array('softwares_estacao'))."! ".$oTranslator->_('kciq_msg session fail',false,true));

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
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Patrimonio');?></font></strong></div></td>
          <td align="center"  nowrap>&nbsp;</td>
          <td align="center"  nowrap bgcolor="#E1E1E1"><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Nome de rede');?></font></strong></div></td>
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
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nr_patrimonio']; ?></font></div></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nm_computador']; ?></font></div></td>
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
    <td height="10"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"></font></td>
  </tr>
</table>
<p align="center">
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $oTranslator->_('Gerado por');?> 
	<strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais
  </font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  	Software desenvolvido pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo
  </font></p>
</body>
</html>
