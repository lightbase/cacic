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

$v_id_software = $_GET['id_software'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Altera&ccedil;&otilde;es de Hardware</title>
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
      <strong><?=$oTranslator->_('Relatorio de Instalacoes de Software');?></strong>
      </font></div>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
       <?=$oTranslator->_('Gerado em');?> 
       <? echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../../include/library.php');
conecta_bd_cacic();
	
	$query = "SELECT s.nm_software, COUNT(se.id_software) 
		FROM softwares s, softwares_estacao se  
		WHERE (s.id_software = " . $v_id_software . ") AND 
		      (se.id_software = s.id_software) AND (se.dt_desinstalacao IS NULL)  
		GROUP BY s.id_software";
	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('softwares')).' '.$query);
	$row = mysql_fetch_row($result);

echo '<table border="0" align="center" width="80%">
<tr><td align="left" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('Software').' '.$row[0].'</strong></font></td>
<td align="right" nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('Instalacoes').' '.$row[1].'</strong></font></td></tr>
</table><br>';

	$query =  "SELECT nm_computador, nr_patrimonio, dt_autorizacao, nr_processo, te_observacao  
		   FROM softwares_estacao se 
		   WHERE (se.id_software = " . $v_id_software . ") AND 
		         (se.dt_desinstalacao IS NULL)  
		   ORDER BY nr_patrimonio";
	
	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('softwares_estacao')));

$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table align="center" width="80%" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Computador').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Patrimonio').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Autorizacao').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Processo').'</font><b></td>';
echo '<td nowrap align="center"><b><font size="1" face="Verdana, Arial">'.$oTranslator->_('Observacao').'</font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[0] . '</td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[1] .'&nbsp;</td>';
    if ($row[2]) { 
    	echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . date("d/m/Y", strtotime($row[2])) .'&nbsp;</td>';
    } else {
	echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">&nbsp;</td>';
    }
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[3] .'&nbsp;</td>';
    echo '<td nowrap align="center"><font size="1" face="Verdana, Arial">' . $row[4] .'&nbsp;</td>';
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
