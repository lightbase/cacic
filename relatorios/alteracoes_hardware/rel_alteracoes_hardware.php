<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];

	// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",$_POST['date_input1']);
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
 	$_SESSION["data_ini"] = $v_data_ini;
	$v_elementos = explode("/",$_POST['date_input2']);
	$v_data_fim = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];
 	$_SESSION["data_fim"] = $v_data_fim;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Altera&ccedil;&otilde;es de Hardware</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) 
	{
  	window.open(theURL,winName,features); //v2.0
	}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
        - Relat&oacute;rio de Altera&ccedil;&otilde;es de Hardware</strong></font></div></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../include/library.php');
AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		//}	
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND comp.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';			
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";

// Aqui pego todas as configurações de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	$campos_hardware = $campos_hardware . $_SESSION["list6"][$i];

// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_hardware = str_replace('\\', '', $campos_hardware);

// Aqui inclui o "hist." devido à origem das informações sobre o hardware ser a tabela de históricos
$campos_hardware = str_replace(', ', ', hist.', $campos_hardware);

if ($_GET['orderby']) 
	$orderby = $_GET['orderby'];
else 
	$orderby = '1';

		
   $query =  "SELECT 
   			  distinct 		comp.te_nome_computador,
							comp.id_so, 
							comp.te_node_address " . 
							$campos_hardware .
							$select . " 
			  FROM 			historico_hardware hist, 
			  				computadores comp ".
							$from . " 
			  WHERE 		DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('" . $_SESSION["data_ini"] . "', '%Y%m%d') AND 
							DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('" . $_SESSION["data_fim"] . "', '%Y%m%d') AND 
							comp.te_node_address = hist.te_node_address AND 
							comp.id_so = hist.id_so ".
							$query_redes. " 
			  ORDER BY 		$orderby ";
//echo $query . '<br>';
	$result = mysql_query($query) or die ('Erro no select ou sua sessão expirou!');


$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

for ($i=2; $i < $fields; $i++) //Table Header
   	print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) //Table body
	{ 
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[2] ."&id_so=". $row[1] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) 
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 

    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
	}
echo '</table>';
echo '<br><br>';
if (count($_SESSION["list8"])>0)
	{
	$v_opcao = 'alteracoes_hardware'; // Nome do pie que será chamado por tabela_estatisticas
	$query_redes .= " AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('".$v_data_ini."', '%Y%m%d')) 
		  		AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('".$v_data_fim."', '%Y%m%d')) ";
	// Os sinais -=- acima são propositais em substituição aos "'" e sofrerão replace no pie.
	require_once('../../include/tabela_estatisticas.php');
	}

?></p>
<p></p>
<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
