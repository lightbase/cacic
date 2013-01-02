<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}
require_once('../../../include/library.php');

	$v_aquisicao = $_GET['id_aquisicao'];
	$v_processo = $_GET['nr_processo'];
	$v_proprietario = $_GET['nm_proprietario'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Relatorio de Softwares por Aquisicao');?></title>
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
    <td nowrap bgcolor="#FFFFFF">
      <div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
        <strong><?=$oTranslator->_('Relatorio de Softwares por Aquisicao');?></strong>
        </font></div>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
       <?=$oTranslator->_('Gerado em');?> 
       <? echo date("d/m/Y �\s H:i"); ?></font></p>
    </td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../../include/library.php');
conecta_bd_cacic();

   $query =  "SELECT s.nm_software, ai.qt_licenca, tl.te_tipo_licenca, ai.te_obs, a.nm_proprietario 
		FROM aquisicoes_item ai, softwares s, tipos_licenca tl, aquisicoes a 
		WHERE (ai.id_software = s.id_software) AND (ai.id_tipo_licenca = tl.id_tipo_licenca) AND  
		      (ai.id_aquisicao = '" . $v_aquisicao . "') AND 
		      (ai.id_aquisicao = a.id_aquisicao) ORDER BY nm_software";

	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('aquisicoes_item')));

$cor = 0;
$num_registro = 1;

if ($v_processo) {
	echo '<table align="center" width="80%"><tr><td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('Processo').' ' . $v_processo . '</strong></font></td></tr></table>';
}
if ($v_proprietario) {
	echo '<table align="center" width="80%"><tr><td align="center" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('Proprietario').' ' . $v_proprietario . '</strong></font></td></tr></table>';
}
$fields=mysql_num_fields($result);
echo '<table align="center" width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Software').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Licencas').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Tipo de licenca').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Observacao').'</font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
    echo "<td nowrap align='center'><font size='1' face='Verdana, Arial'>" . $row[0] ."&nbsp;</td>"; 
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[1] .'&nbsp;</td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[2] .'&nbsp;</td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[3] .'&nbsp;</td>';
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';

?></p>
<p></p>
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> 
  <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    <?=$oTranslator->_('Desenvolvido por');?> 
    Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
