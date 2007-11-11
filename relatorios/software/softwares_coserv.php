<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/library.php');
conecta_bd_cacic();

if ($_POST['consultar']) {
					
	$_SESSION['ftr_consulta_cenin'] = $_POST['filtro_consulta'];		
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="/cacic2/include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="/cacic2/imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'/cacic2/include/cacic.js';?>"></script>

<?

if (($_SESSION['ftr_consulta_cenin'] == 'todos') or ($_SESSION['ftr_consulta_cenin'] == '')) {
	$valor_padrao_software_orgao = '<option value="todos">Mostrar todos</option>
			<option value="nao_correcao">Não mostrar atualizações/correções</option>
			<option value="suspeitos">Mostrar somente softwares suspeitos</option>
			<option value="suspeitos_lixo_jogos">Mostrar softwares suspeitos, lixos e jogos</option>';
}

if (($_SESSION['ftr_consulta_cenin'] == 'nao_correcao')) {
	$valor_padrao_software_orgao = '<option value="nao_correcao">Não mostrar atualizações/correções</option>
			<option value="todos">Mostrar todos</option>
			<option value="suspeitos">Mostrar somente softwares suspeitos</option>
			<option value="suspeitos_lixo_jogos">Mostrar softwares suspeitos, lixos e jogos</option>';
}

if (($_SESSION['ftr_consulta_cenin'] == 'suspeitos')) {
	$valor_padrao_software_orgao = '<option value="suspeitos">Mostrar somente softwares suspeitos</option>
			<option value="todos">Mostrar todos</option>
			<option value="nao_correcao">Não mostrar atualizações/correções</option>
			<option value="suspeitos_lixo_jogos">Mostrar softwares suspeitos, lixos e jogos</option>';
}

if (($_SESSION['ftr_consulta_cenin'] == 'suspeitos_lixo_jogos')) {
	$valor_padrao_software_orgao = '<option value="suspeitos_lixo_jogos">Mostrar softwares suspeitos, lixos e jogos</option>
			<option value="todos">Mostrar todos</option>
			<option value="suspeitos">Mostrar somente softwares suspeitos</option>
			<option value="nao_correcao">Não mostrar atualizações/correções</option>';
}

?>  

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Softwares inventariados no CENIN</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label">Selecione os filtros da consulta:</td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>
	<select name="filtro_consulta" id="select" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<? echo $valor_padrao_software_orgao ;?>
	</select>
</td> 
            <td><input name="consultar" type="submit" id="consultar2" value="Consultar"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr><td class="label">Siglas: CORED, COSERV, SEGER, SEGES, SEMAD, SEMIR, SEOPR, SEPAS, SESAP, SESEG, SESUP</td>
    </tr>
  </table>
  </form>
<?
$queryCENIN = " ((c.te_nome_computador LIKE '%CORED%') OR 
	       (c.te_nome_computador LIKE '%COSERV%') OR 
	       (c.te_nome_computador LIKE '%SEGER-%') OR 
	       (c.te_nome_computador LIKE '%SEGES%') OR 
	       (c.te_nome_computador LIKE '%SEMAD%') OR 
	       (c.te_nome_computador LIKE '%SEMIR%') OR 
	       (c.te_nome_computador LIKE '%SEOPR%') OR 
	       (c.te_nome_computador LIKE '%SEPAS%') OR 
	       (c.te_nome_computador LIKE '%SESAP%') OR 
	       (c.te_nome_computador LIKE '%SESEG%') OR 
	       (c.te_nome_computador LIKE '%SESUP%')) ";

if ($_POST['consultar']) {
	if ($_SESSION['ftr_consulta_cenin'] == 'todos') {
		$query = "SELECT ss.id_software_inventariado as id_soft, 
		ss.nm_software_inventariado as nm_soft, count(*) as qtde 
		FROM softwares_inventariados_estacoes s, computadores c, 
		softwares_inventariados ss 
		WHERE (s.te_node_address = c.te_node_address) AND 
		$queryCENIN  
		AND (s.id_software_inventariado = ss.id_software_inventariado) 
		GROUP BY ss.nm_software_inventariado"; 
	}
	
	if ($_SESSION['ftr_consulta_cenin'] == 'nao_correcao') {
		$query = "SELECT ss.id_software_inventariado as id_soft, 
		ss.nm_software_inventariado as nm_soft, count(*) as qtde 
		FROM softwares_inventariados_estacoes s, computadores c,
		softwares_inventariados ss
		WHERE (s.te_node_address = c.te_node_address) AND
		$queryCENIN 	
		AND (s.id_software_inventariado = ss.id_software_inventariado)
		AND (ss.id_tipo_software <> 1) 
		GROUP BY ss.nm_software_inventariado";
	}

	if ($_SESSION['ftr_consulta_cenin'] == 'suspeitos') {
		$query = "SELECT ss.id_software_inventariado as id_soft,
		ss.nm_software_inventariado as nm_soft, count(*) as qtde
		FROM softwares_inventariados_estacoes s, computadores c,
		softwares_inventariados ss
		WHERE (s.te_node_address = c.te_node_address) AND
		$queryCENIN
		AND (s.id_software_inventariado = ss.id_software_inventariado)
		AND (ss.id_tipo_software = 5) 
		GROUP BY ss.nm_software_inventariado";
	}

	if ($_SESSION['ftr_consulta_cenin'] == 'suspeitos_lixo_jogos') {
		$query = "SELECT ss.id_software_inventariado as id_soft,
		ss.nm_software_inventariado as nm_soft, count(*) as qtde
		FROM softwares_inventariados_estacoes s, computadores c,
		softwares_inventariados ss
		WHERE (s.te_node_address = c.te_node_address) AND
		$queryCENIN
		AND (s.id_software_inventariado = ss.id_software_inventariado)
		AND ((ss.id_tipo_software = 5) OR (ss.id_tipo_software = 6) OR
		    (ss.id_tipo_software = 7)) 
		GROUP BY ss.nm_software_inventariado";
	}

	$result = mysql_query($query) or die('Erro no select');
	
	if(($nu_reg= mysql_num_rows($result))==0){
		echo $mensagem = mensagem('Nenhum registro encontrado!');
	}
	else
		{

?>
<p align="center" class="descricao">Clique 
  sobre o nome do software para ver em quais m&aacute;quinas est&aacute; instalado</p>
<table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
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
          <td nowrap class="opcao_tabela"><a href="rel_softwares_coserv.php?id_software_inventariado=<? echo $row['id_soft'];?>&nm_software_inventariado=<? echo $row['nm_soft'];?>" target="_blank"><? echo $row['nm_soft']; ?></a></td>
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
