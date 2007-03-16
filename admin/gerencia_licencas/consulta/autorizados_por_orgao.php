<?
session_start();
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once($_SERVER['DOCUMENT_ROOT'] . '../../../include/library.php');
conecta_bd_cacic();

if ($_POST['consultar']) {
					
	$_SESSION['str_autorizado_orgao'] = $_POST['string_autorizado_orgao'];	
	$_SESSION['ftr_autorizado_orgao'] = $_POST['filtro_autorizado_orgao'];		
	}
	
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">

<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" background="../../../imgs/linha_v.gif" onLoad="SetaCampo('string_autorizado_orgao')">
<script language="JavaScript" type="text/javascript" src="http://<? echo $_SERVER['HTTP_HOST'].'../../../include/cacic.js';?>"></script>

<form action="<? echo $PHP_SELF; ?>" method="post" name="form1">
<table width="90%" border="0" align="center">
<tr> 
<td class="cabecalho">Consulta de softwares autorizados por &oacute;rg&atilde;o</td>
</tr>
<tr> 
<td>&nbsp;</td>
</tr>
</table>
<tr><td height="1" colspan="2" bgcolor="#333333"></td></tr>
<tr><td height="30" colspan="2"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td height="28"><table width="96%" border="0" align="center" cellpadding="1" cellspacing="1">
<tr>
<td>Informe o &oacute;rg&atilde;o: 
</td> 
            <td> 
              <input name="string_autorizado_orgao" type="text" id="string_autorizado_orgao2" value="<? echo $_REQUEST['string_autorizado_orgao'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
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

	$query = "SELECT s.nm_software, s.id_software, count(*) as qtde 
		  FROM softwares_estacao se, softwares s  
		  WHERE (nm_computador LIKE '%" . $_SESSION['str_autorizado_orgao'] . "%') AND 
			(s.id_software = se.id_software) AND 
			(se.dt_desinstalacao IS NULL)  
		  GROUP BY s.id_software  
		  ORDER BY nm_software";

	$result = mysql_query($query) or die('Erro no select');
	
	if (strlen($_SESSION['str_autorizado_orgao']) < 3) {
		echo $mensagem = mensagem('Digite pelo menos 03 caracteres...');
		}
		else
		{
			if(($nu_reg= mysql_num_rows($result))==0){
			echo $mensagem = mensagem('Nenhum registro encontrado!');
				}
				else
				{

?>
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
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Software</font></strong></div></td>
	  <td align="center" nowrap >&nbsp; </td>
	  <td align="left" nowrap bgcolor="#E1E1E1"><div align="left"><strong><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">M&aacute;quinas</font></strong></div></td>
	  <td nowrap >&nbsp; </td> 
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
          <td nowrap class="opcao_tabela"><a href="rel_autorizados_por_orgao.php?id_software_inventariado=<? echo $row['id_software'];?>&nm_software_inventariado=<? echo $row['nm_software'];?>&nm_maquina=<? echo $_SESSION['str_autorizado_orgao'];?> " target="_blank"><? echo $row['nm_software']; ?></a></td>
          <td nowrap>&nbsp;</td>
          <td align="center" nowrap class="opcao_tabela"><? echo $row['qtde']; ?></td>
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
}
?>
</body>
</html>
