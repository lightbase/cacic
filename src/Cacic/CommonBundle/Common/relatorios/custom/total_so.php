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
require_once('../../include/library.php');
conecta_bd_cacic();

$total_so = true;

if ($total_so) {
	$query = " SELECT a.nm_rede as  REDE , b.te_desc_so as Sistema_Operacional, count(*) as Total
				FROM redes a, so b, computadores c
				WHERE a.id_ip_rede = c.id_ip_rede 
				AND b.id_so = c.id_so
				GROUP BY a.nm_rede, b.te_desc_so";

//	echo $query;

	$result = mysql_query($query) or die ('Erro no select ou sua sess�o expirou!');
	$tipo_historico = 'por rede';
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Total de S.O <? echo $tipo_historico?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Total de S.O <? echo $tipo_historico?>
      </strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<p><br>
  <? 
$cor = 0;
$num_registro = 1;
$rede = '';

$fields=mysql_num_fields($result);
if (mysql_num_rows($result) > 0) {
    
	echo '<table cellpadding="1" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
		 <tr bgcolor="#E1E1E1" >
		  <td nowrap align="left"><font size="2" face="Verdana, Arial">&nbsp;</font></td>';
	
	for ($i=0; $i < mysql_num_fields($result); $i++) { //Table Header
	   print '<td nowrap align="center"><b><font size="2" face="Verdana, Arial">'. mysql_field_name($result, $i) .'</font><b></td>';
	}
	echo '</tr>';

	while ($row = mysql_fetch_row($result)) { //Table body
          if ($rede != $row[0]) {
                echo '<tr ';
                echo '>';
                echo '<td colspan=4 align="center"><font size="3" face="Verdana, Arial"> Rede -> ' . $row[0] . '</font></td>';
                echo '</tr>';
                $num_registro = 1;
          }
          $rede = $row[0];	

	   echo '<tr ';
		if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
		echo '>';
		echo '<td nowrap align="left"><font size="2" face="Verdana, Arial">' . $num_registro . '</font></td>';
	
		for ($i=0; $i < $fields; $i++) {
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
		}
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
	}
	echo '</table>';
}
else {
	echo '</table>';
	echo mensagem('N�o foi encontrado nenhum registro');
}
