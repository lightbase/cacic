<?php
 /* 
 */
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}
require_once('../../../include/library.php');

	$v_aquisicao = $_GET['id_aquisicao'];
	$v_processo = $_GET['nr_processo'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $oTranslator->_('Softwares por Aquisicao');?></title>
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
    <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong><?php echo $oTranslator->_('Softwares por Aquisicao');?></strong></font></div></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><?php echo $oTranslator->_('Gerado em');?> <?php echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<?php 
require_once('../../../include/library.php');
conecta_bd_cacic();

   $query =  "SELECT s.nm_software, ai.qt_licenca, tl.te_tipo_licenca  
		FROM aquisicoes_item ai, softwares s, tipos_licenca tl  
		WHERE (ai.id_software = s.id_software) AND (ai.id_tipo_licenca = tl.id_tipo_licenca) AND  
		      (ai.id_aquisicao = '" . $v_aquisicao . "') ORDER BY nm_software";

	$result = mysql_query($query) or die ($oTranslator->_('Erro no select ou sua sessao expirou!'));

$cor = 0;
$num_registro = 1;

echo '<table align="center" width="80%"><tr><td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('Processo:').' ' . $v_processo . '</strong></font></td></tr></table>';

$fields=mysql_num_fields($result);
echo '<table align="center" width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Software').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Licencas').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Tipo de licenca').'</font><b></td>';

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
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?php echo $oTranslator->_('Gerado por');?>
  <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?php echo $oTranslator->_('desenvolvido por');?> Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
