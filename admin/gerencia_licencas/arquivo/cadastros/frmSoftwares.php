<?php
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../../../include/library.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../../include/cacic.css">
<title><?=$oTranslator->_('Cadastro de Softwares');?></title>
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
<body bgcolor="#FFFFFF" topmargin="5" onload="document.forms[0].nome.focus()" background="../../../../imgs/linha_v.gif">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Cadastro de Softwares');?></td>
  </tr>
  <tr> 
    <td class="descricao">
     <?=$oTranslator->_('kciq_msg cadastro de softwares help');?>
    </td>
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
  <td><?=$oTranslator->_('Descricao:');?></td>
  <td><input type=text name="descricao" size="50"></td>
<tr>
  <td><?=$oTranslator->_('Quantidade de Licencas:');?></td>
  <td><input type=text name="quantidadeLicenca" size="10"></td>
</tr>
<tr>
  <td><?=$oTranslator->_('Numero da Midia:');?></td>
  <td><input type=text name="numeroMidia" size="10"></td>
</tr>
<tr>
  <td><?=$oTranslator->_('Localizacao da Midia:');?></td>
  <td><input type=text name="localMidia" size="50"></td>
</tr>
<tr>
  <td><?=$oTranslator->_('Observacao:');?></td>
  <td><textarea name="observacao" rows="3" cols="38"></textarea></td>
</tr>
<tr>
  <td></td>
  <td></td>
</tr>
<tr>
  <td align="right"><input type="reset" name="cancelar" value="<?=$oTranslator->_('Cancelar');?>"/></td>
  <td align="left"><input type="submit" name="gravar" value="<?=$oTranslator->_('Gravar');?>" width="100"></td>
</tr>
</table></font>
</form>
<p>&nbsp;</p>
</body>
</html>
