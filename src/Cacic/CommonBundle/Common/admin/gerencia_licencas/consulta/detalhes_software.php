<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

//Mostrar computadores com nomes repetidos na base
require_once('../../../include/library.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/css/cacic.css">
<title><?php echo $oTranslator->_('Relatorio de Softwares Inventariados por Maquinas');?></title>
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
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong><?php echo $oTranslator->_('Relatorio de Softwares Inventariados por Maquinas');?></strong></font>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td>
    	<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    	  <?php echo $oTranslator->_('Gerado em');?> <?php echo date("d/m/Y à\s H:i"); ?>
    	</font></p>
    </td>
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
	$query = "SELECT nm_software, te_descricao_software, qt_licenca, nr_midia, te_local_midia, 
			 te_obs, id_software   
                  FROM softwares  
		  WHERE id_software = '" . $_GET['id_software'] . "'";
	$result = mysql_query($query) or die($oTranslator->_('Erro no select ou sua sessao expirou!'));
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
          <td align="center" nowrap colspan=3><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Detalhes do Software');?></font></strong></div></td>
        </tr>
        <?php  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Ide');?></font></strong></div></td>
	  <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['id_software']; ?></font></div></td>
	  </tr>
	  <tr <?php if (!$Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Software');?></font></strong></div></td>
	  <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nm_software']; ?></font></div></td>
	  </tr>
	  <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Descricao');?></font></strong></div></td>
	  <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['te_descricao_software']; ?></font></div></td>
	  </tr>
	  <tr <?php if (!$Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Quantidade de Licencas');?></font></strong></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['qt_licenca']+0; ?></font></td>
	  </tr>
	  <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Numero da Midia');?></font></strong></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['nr_midia']; ?></font></td>
	  </tr>
	  <tr <?php if (!$Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Local da Midia');?></font></strong></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['te_local_midia']; ?></font></td>
	  </tr>
	  <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
          <td nowrap ><div align="center"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Observacao');?></font></strong></div></td>
          <td nowrap>&nbsp;&nbsp;</td>
	  <td align="center" wrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $row['te_obs']; ?></font></td>
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
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?php echo $oTranslator->_('Gerado por');?> 
  <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?php echo $oTranslator->_('Desenvolvido por');?> 
  Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
