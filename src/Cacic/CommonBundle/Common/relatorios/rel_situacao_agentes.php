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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if($_POST['submit']) {
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es de Hardware</title>
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
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="/cacic2/imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>Relat&oacute;rio 
      de Configura&ccedil;&otilde;es de Hardware</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<? 
require_once($_SERVER['DOCUMENT_ROOT'] . 'include/library.php');
AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if($_SESSION["cs_situacao"] == 'S') {
	// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) {
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
	}
	$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}

// Aqui pego todas as configura��es de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$campos_hardware = $campos_hardware . $_SESSION["list6"][$i];
}
// Aqui substitui todas as strings \ por vazio que a vari�vel $campos_hardware retorna
$campos_hardware = str_replace('\\', '', $campos_hardware);

if ($_GET['orderby']) { $orderby = $_GET['orderby']; }
else { $orderby = '3'; } //por Nome Comp.
$query = ' SELECT te_node_address, so.id_so, te_nome_computador as "Nome Comp.", sg_so as "S.O.", te_ip as "IP"' .
          $campos_hardware .
		  'FROM so LEFT JOIN computadores ON (computadores.id_so = so.id_so)		 		 
		   WHERE  TRIM(te_nome_computador) <> "" AND computadores.id_so IN ('. $so_selecionados .')  
		   '. $query_redes .' 
		   ORDER BY ' . $orderby; 

$result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!');

$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

for ($i=2; $i < mysql_num_fields($result); $i++) { //Table Header
   print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
}
echo '</tr>';

while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) {
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
	}
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
?></p>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
