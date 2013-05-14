<?php
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
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es do TCP/IP</title>
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
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC - Relat&oacute;rio 
      de Configura&ccedil;&otilde;es do TCP/IP</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <?php echo date("d/m/Y �\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<?php 
require_once('../../include/library.php');
AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S')  // Apenas Redes Selecionadas
		//{
		// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$query_redes = 'AND computadores.id_rede IN ('. $redes_selecionadas .')';		
		//}	
	//else // Todas as Redes
		//{
		//$query_redes = 'AND computadores.te_ip = redes.te_ip AND 
		//					redes.id_local = '. $_SESSION['id_local'].' AND
		//					redes.id_local = locais.id_local ';
		//$select = ' ,sg_local as Local ';	
		//$from = ' ,redes,locais ';					
		//}
	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND computadores.id_rede = redes.id_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';			
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) {
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
}

// Aqui pego todas as configura��es de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) {
	$campos_tcpip = $campos_tcpip . $_SESSION["list6"][$i];
}
// Aqui substitui todas as strings \ por vazio que a vari�vel $campos_hardware retorna
$campos_tcpip = str_replace('\\', '', $campos_tcpip);

if ($_GET['orderby']) { $orderby = $_GET['orderby']; }
else { $orderby = '3'; } //Por Nome de Computador
$query = ' SELECT 	computadores.te_node_address, 
					so.id_so, 
					te_nome_computador as "Nome Comp.",
					te_nome_host as "Nome Host", 
					sg_so as "S.O.", 
					computadores.id_computador, 					
					te_ip_computador as "IP"' .
          			$campos_tcpip .
					$select.'  
		   FROM 	computadores LEFT JOIN so ON (computadores.id_so = so.id_so) '.
		   			$from . ' 
		   WHERE  	TRIM(te_nome_computador)<>""  and computadores.id_so IN ('. $so_selecionados .') ' . 
					$query_redes . ' 
		   ORDER BY ' . $orderby; echo $query;
$result = mysql_query($query) or die('Erro na consulta � tabela computadores ou sua sess�o expirou!');

$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="2" face="Verdana, Arial">&nbsp;</font></td>';

for ($i=2; $i < mysql_num_fields($result); $i++) { //Table Header
   print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
}
echo '</tr>';

while ($row = mysql_fetch_row($result)) { //Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?id_computador=". $row[5] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) {
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
	}
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
}
echo '</table>';
echo '<br><br>';
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'tcpip'; // Nome do pie que ser� chamado por tabela_estatisticas
	require_once('../../include/tabela_estatisticas.php');
	}

?></p>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
