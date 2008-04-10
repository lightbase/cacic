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
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) {
	$_SESSION["filtro_ts"] = $_POST['filtro_ts']; //Redes selecionadas
	$_SESSION["string_siglas"] = $_POST['string_siglas']; //SO selecionados
	$_SESSION["filtro_tr"] = $_POST['filtro_tr']; //Softwares selecionados
}

require_once('../../include/library.php');

conecta_bd_cacic();
$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1"></td>
			  <td height="1"></td>
         </tr>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Softwares Inventariados</title>
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
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_novo.gif" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Invent&aacute;rio de Softwares Gen&eacute;rico</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br><?

$array_siglas = explode(';',$_SESSION["string_siglas"]);
for ($i=0; $i < count($array_siglas); $i ++) {
	if ($condicaoSigla) {
 		$condicaoSigla = $condicaoSigla . " OR (te_nome_computador LIKE '%" . $array_siglas[$i] . "%')";
	} else {
		$condicaoSigla = "(te_nome_computador LIKE '%" . $array_siglas[$i] . "%')";
	}
}

if ($_SESSION["filtro_ts"] == 'licenciados') {
	$condicaoTipoSoftware = " AND (si.id_tipo_software = 4) ";
} else if ($_SESSION["filtro_ts"] == 'suspeitos') {
	$condicaoTipoSoftware = " AND (si.id_tipo_software = 5) ";
}

if ($_SESSION["filtro_tr"] == 'estacao') {

	$queryComputadores = "SELECT te_nome_computador 
						  FROM computadores 
						  WHERE $condicaoSigla 
						  ORDER BY te_nome_computador";
	$resultComputadores = mysql_query($queryComputadores);
	?>
	<table width=100%>
	<?
	while ($reg_computador = @mysql_fetch_row($resultComputadores)) {
		?>
		<tr bgcolor='#CECECE' align=center>
		<td colspan=2>
		<b><? echo $reg_computador[0];?></b>
		</td>
		</tr>
		<?
		$querySoftwares = "SELECT si.nm_software_inventariado, ts.te_descricao_tipo_software 
						   FROM softwares_inventariados si, softwares_inventariados_estacoes sie, 
							    computadores c, tipos_software ts 
						   WHERE (sie.te_node_address = c.te_node_address) AND 
								 (si.id_software_inventariado = sie.id_software_inventariado) AND 
								 (c.te_nome_computador = '" . $reg_computador[0] . "') 
								 $condicaoTipoSoftware AND 
								 (si.id_tipo_software = ts.id_tipo_software) 
						   ORDER BY si.nm_software_inventariado";
		$resultSoftwares = mysql_query($querySoftwares);
		while ($reg_software = @mysql_fetch_row($resultSoftwares)) {
			?>
			<tr>
			<td align=left><? echo $reg_software[0];?></td>
			<td align=right><? echo $reg_software[1];?></td>
			</tr>
			<?
		}
	}
	?>
	</table>
	<?
}

?>
 
<p align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>	

</body>
</html>
