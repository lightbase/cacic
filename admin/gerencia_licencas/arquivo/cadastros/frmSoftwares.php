<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="/cacic2/include/cacic.css">
<title>Cadastro de Softwares</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function validaForm() {
	msg="";
	if((document.forms[0].nome.value=="") || (document.forms[0].nome.value.substring(0,1)==" ")){
		msg="- NOME é um campo obrigatório!";
	}
	
	if((document.forms[0].quantidadeLicenca.value=="") || (document.forms[0].quantidadeLicenca.value.substring(0,1)==" ")){
		msg=msg+"\n- QUANTIDADE DE LICENÇA é um campo obrigatório!";
	}
	
	if (msg!="") {
		alert(msg);
		msg="";
		return false;
	}
}
</script>
<link href="../../../../include/cacic.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" topmargin="5" onload="document.forms[0].nome.focus()" background="/cacic2/imgs/linha_v.gif">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de Softwares</td>
  </tr>
  <tr> 
    <td class="descricao">M&oacute;dulo para cadastramento manual de softwares 
      para posterior utiliza&ccedil;&atilde;o referencial junto aos dados de licen&ccedil;as.</td>
  </tr>
  <tr> 
    <td> </td>
  </tr>
</table>
<br><br>
<form action="softwares.php" method="post" name="frmSoftwares" onSubmit="return validaForm()">
<table width="90%" align="center">
<tr>
  <td>Nome:</td>
  <td><input type=text name="nome" size="50"></td>
</tr>
<tr>
  <td>Descrição:</td>
  <td><input type=text name="descricao" size="50"></td>
<tr>
  <td>Quantidade de Licença:</td>
  <td><input type=text name="quantidadeLicenca" size="10"></td>
</tr>
<tr>
  <td>Número da Mídia:</td>
  <td><input type=text name="numeroMidia" size="10"></td>
</tr>
<tr>
  <td>Localização da Mídia:</td>
  <td><input type=text name="localMidia" size="50"></td>
</tr>
<tr>
  <td>Observação:</td>
  <td><textarea name="observacao" rows="3" cols="38"></textarea></td>
</tr>
<tr>
  <td></td>
  <td></td>
</tr>
<tr>
  <td align="right"><input type="reset" name="cancelar" value="Cancelar"/></td>
  <td align="left"><input type="submit" name="gravar" value="Gravar" width="100"></td>
</tr>
</table></font>
</form>
<p>&nbsp;</p>
</body>
</html>
