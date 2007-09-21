<?
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();


if ($_POST['consultar']) {
					
	$_SESSION['sigla_orgao_consulta'] = $_POST['sigla_orgao'];		
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="/cacic2/include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="/cacic2/imgs/linha_v.gif" onLoad="SetaCampo('sigla_orgao')">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'/cacic2/include/cacic.js';?>"></script>

<?

	$queryOrgaos = "SELECT DISTINCT siglaOrgao  
		        FROM nome_computador_orgao 
			ORDER BY siglaOrgao";
	$resultOrgaos = mysql_query($queryOrgaos) or die('Erro! '.$queryOrgaos.' ou sua sessão expirou!');

	while ($campos_orgaos=mysql_fetch_array($resultOrgaos)) { 
		$valor_combo_orgaos = $valor_combo_orgaos . 
		'<option value=' . $campos_orgaos['siglaOrgao'] . '>' . $campos_orgaos['siglaOrgao'] . '</option>';
	}

?>  

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Consulta de softwares por &oacute;rg&atilde;o</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label">Selecione o &oacute;rg&atilde;o:</td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>
	<select name="sigla_orgao" style="width:250px" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<? echo $valor_combo_orgaos;?>
	</select>
</td> 
            <td><input name="consultar" type="submit" id="consultar2" value="Consultar"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
  </table>
  </form>
<?

if ($_POST['consultar']) {

	$queryOrgao = "SELECT nomeComputador 
		       FROM nome_computador_orgao 
		       WHERE siglaOrgao = '" . $_SESSION['sigla_orgao_consulta'] . "'";
	$resultOrgao = mysql_query($queryOrgao) or die('Erro de acesso ou sua sessão expirou!');
	$restricao = "";
	while ($campos_resultOrgao=mysql_fetch_array($resultOrgao)) {
		if ($restricao) {
		$restricao = $restricao . " OR (c.te_nome_computador LIKE '" . $campos_resultOrgao['nomeComputador'] . "')";
		} else {
		$restricao = $restricao . " AND ((c.te_nome_computador LIKE '" . $campos_resultOrgao['nomeComputador'] . "')";
		}
	}

	$query = "SELECT ss.id_software_inventariado as id_soft, 
	ss.nm_software_inventariado as nm_soft, 
	count(*) as qtde 
	FROM softwares_inventariados_estacoes s, computadores c,
	softwares_inventariados ss 
	WHERE (s.te_node_address = c.te_node_address) 
	AND (s.id_software_inventariado = ss.id_software_inventariado)
	" . $restricao . ") 
	GROUP BY ss.nm_software_inventariado"; 

	$result = mysql_query($query) or die('Erro no select ou sua sessão expirou!');
	
	if(($nu_reg= mysql_num_rows($result))==0) {
		echo $mensagem = mensagem('Nenhum registro encontrado!');
	}
	else
	{

?>
<p align="center" class="descricao">Clique 
  sobre o nome do software para ver os detalhes do mesmo</p>
<table border="0" width="90%" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
	<tr bgcolor="#AAAAAA">
	  <td align="center" colspan=7><b>&Oacute;rg&atilde;o: <? echo $_SESSION['sigla_orgao_consulta'];?></b></td>
	</tr>
        <tr bgcolor="#E1E1E1">
	  <td align="center" nowrap>&nbsp;</td>
	  <td align="center" nowrap><div align="left"><strong></strong></div></td>
	  <td align="center" nowrap >&nbsp; </td>
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Softwares Inventariados</font></strong></div></td>
	  <td nowrap >&nbsp; </td>
          <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">M&aacute;quinas</font></strong></div></td>
	  <td nowrap >&nbsp;</td>
        </tr>
        <?  
	$Cor = 0;
	$NumRegistro = 1;
	
	while($row = mysql_fetch_array($result)) {
		  
	 ?>
        <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
          <td nowrap>&nbsp;</td>
          <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
          <td nowrap>&nbsp;</td>
          <td class="opcao_tabela"><a href="rel_completo_orgao.php?id_software_inventariado=<? echo $row['id_soft'];?>&nm_software_inventariado=<? echo $row['nm_soft'];?>&nm_maquina=<? echo $_SESSION['sigla_orgao_consulta'];?>" target="_blank"><? echo $row['nm_soft']; ?></a></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['qtde']; ?></a></div></td>
          <td nowrap>&nbsp;</td>
          <? 
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
    <td height="10">&nbsp;</td>
  </tr>
</table>
<?
		}
}
?>
</body>
</html>
