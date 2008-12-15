<?
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
require_once('security/security.php');

conecta_bd_cacic();

if($_POST['submit']) {

// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",Security::read('date_aquisicao'));
	$v_numero_processo = Security::read('numero_processo');
	$v_nm_empresa = Security::read('nm_empresa');
	$v_nm_proprietario = Security::read('nm_proprietario');
	$v_nr_notafiscal = Security::read('nr_notafiscal');
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
 	$_SESSION["data_ini"] = $v_data_ini;
 	
 	$sql_insert = "INSERT INTO aquisicoes (dt_aquisicao, nr_processo, nm_empresa, nm_proprietario, nr_notafiscal)
                          VALUES ('$v_data_ini', '$v_numero_processo','$v_nm_empresa','$v_nm_proprietario','$v_nr_notafiscal');
 			      ";
 	
 	$result = mysql_query($sql_insert) or die ($oTranslator->_('falha na insercao em (%1) ou sua sessao expirou!', array('aquisicoes')));
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Cadastro de Aquisicao');?></title>
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
    <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
        - <?=$oTranslator->_('Cadastro de Aquisicao');?></strong></font></div></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      <?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y à\s H:i"); ?></font></p>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<form action="aquisicoes.php" method="post" ENCTYPE="multipart/form-data" name="form_aquisicao"   onsubmit="return valida_form_cadastro_aquisicao(this)">
  <table align="right">
      <tr> 
        <td> 
            <input name="submit" type="submit" value="<?=$oTranslator->_('Incluir Aquisicao');?>">
        </td>
      </tr>
  </table>
</form>
<? 

   $query =  "SELECT id_aquisicao, dt_aquisicao, nr_processo 
		FROM aquisicoes ORDER BY nr_processo desc";

	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('aquisicoes')));


$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table align="center" width="100%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">Processo</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">Aquisi&ccedil;&atilde;o</font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial"><a href="softwares_aquisicao.php?id_aquisicao=' . $row[0] . '&nr_processo=' . $row[2] . '" target="_blank"">' . $row[2] . '</a></td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[1] .'&nbsp;</td>';
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';

?></p>
<p></p>
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Relatorio gerado pelo');?>
  <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('desenvolvido por');?> Dataprev - Unidade Regional Esp&iacute;rito Santo
  </font></p>
</body>
</html>
