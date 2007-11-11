<?
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');
conecta_bd_cacic();


if ($_POST['consultar']) 
	{					
	$_SESSION['ses_id_tipo_software'] = $_POST['id_tipo_software'];		
	}
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="/cacic2/imgs/linha_v.gif" onLoad="SetaCampo('tipo_consulta')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Consulta de softwares por tipo</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="2" class="label">Informe o tipo de software:</td></tr>
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>
<select name="id_tipo_software" id="id_tipo_software" class="normal">
<option value="0">Todos</option>

<?
	$query = "SELECT 	* 
			  FROM 		tipos_software 
			  ORDER BY	te_descricao_tipo_software";
	$result = mysql_query($query) or die ('Select em "tipos_software" falhou ou sua sessão expirou!');
	while ($row = mysql_fetch_array($result))
		{
		?>
		<option value="<? echo $row['id_tipo_software'];?>" 
		<?
		if ($_SESSION['ses_id_tipo_software']==$row['id_tipo_software']) 
			echo 'selected';
		?>
		><? echo $row['te_descricao_tipo_software'];?></option>
		<?
		}
	?>
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
	$query = "SELECT 	ss.id_software_inventariado as id_soft, 
						ss.nm_software_inventariado as nm_soft, 
						ts.te_descricao_tipo_software,
						count(*) as qtde 
			  FROM 		softwares_inventariados_estacoes s, 
						computadores c,
						softwares_inventariados ss, 
						tipos_software ts
			  WHERE 	(s.te_node_address = c.te_node_address) AND 
			  			(s.id_software_inventariado = ss.id_software_inventariado) AND
						(ss.id_tipo_software = ts.id_tipo_software)";
	if ($_REQUEST['id_tipo_software'] > 0) // Se diferente de "Todos"
		$query .= " AND (ss.id_tipo_software = ".$_REQUEST['id_tipo_software'].")";
		
	$query .= " GROUP BY 	ss.nm_software_inventariado"; 
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
        <tr bgcolor="#E1E1E1">
	  <td align="center" nowrap>&nbsp;</td>
	  <td align="center" nowrap><div align="left"><strong></strong></div></td>
	  <td align="center" nowrap >&nbsp; </td>
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Softwares Inventariados</font></strong></div></td>
	  <td nowrap >&nbsp; </td>
	  <td nowrap ><div align="center"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Tipo de Software</font></strong></div></td>
	  <td nowrap >&nbsp;</td> 
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
          <td class="opcao_tabela"><a href="rel_softwares_orgao.php?id_software_inventariado=<? echo $row['id_soft'];?>&nm_software_inventariado=<? echo $row['nm_soft'];?>&nm_maquina=<? echo '';?>" target="_blank"><? echo $row['nm_soft']; ?></a></td>
          <td nowrap>&nbsp;</td>
	  <td nowrap class="opcao_tabela"><div align="center"><? echo $row['te_descricao_tipo_software']; ?></a></div></td>
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
