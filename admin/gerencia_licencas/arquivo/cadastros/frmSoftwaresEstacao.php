<?
session_start();
require_once('../../../../include/library.php');
conecta_bd_cacic();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet" type="text/css" href="/cacic2/include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript">
function validaForm() {
	msg="";
	if((document.forms[0].patrimonio.value=="") || (document.forms[0].patrimonio.value.substring(0,1)==" ")){
		msg="- PATRIMÔNIO é um campo obrigatório!";
	}
	
	if((document.forms[0].software.value==-1) || (document.forms[0].software.value.substring(0,1)==" ")){
		msg=msg+"\n- SOFTWARE é um campo obrigatório!";
	}
	
	if((document.forms[0].dataAutorizacao.value=="") || (document.forms[0].dataAutorizacao.value.substring(0,1)==" ")){
		msg=msg+"\n- DATA DE AUTORIZAÇÃO é um campo obrigatório!";
	}
	
	if (msg!="") {
		alert(msg);
		msg="";
		return false;
	}
}
function formatar(src, mask) 
{
  var i = src.value.length;
  var saida = mask.substring(0,1);
  var texto = mask.substring(i)
if (texto.substring(0,1) != saida) 
  {
	src.value += texto.substring(0,1);
  }
}
</script>
<link href="../../../../include/cacic.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" topmargin="5" onload="document.forms[0].patrimonio.focus()" background="/cacic2/imgs/linha_v.gif">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Cadastro de Softwares por Esta&ccedil;&atilde;o</td>
  </tr>
  <tr> 
    <td class="descricao">M&oacute;dulo para cadastramento manual de softwares 
      por esta&ccedil;&atilde;o</td>
  </tr>
  <tr> 
    <td> </td>
  </tr>
</table>
<br><br>
<form action="softwaresEstacao.php" method="post" name="softwaresEstacao" onSubmit="return validaForm()">
<table width="90%" align="center">
    <tr>
      <td>Patrim&ocirc;nio:</td>
      <td><input type=text name="patrimonio" size="20"></td>
    </tr>
    <tr>
      <td>Software:</td>
      <td>
        <select name="software" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          <? 
          	$query = "SELECT id_software, nm_software FROM softwares ORDER BY nm_software";
		    $result = mysql_query($query) or die('Ocorreu um erro no select');
		    echo '<option value=-1></option>';
		    while ($softwares=mysql_fetch_array($result)) {
				echo '<option value=' . $softwares['id_software'] . '>' . $softwares['nm_software'] . '</option>';
		    }
	      ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Computador:</td>
      <td><input type=text name="computador" size="30"></td>
    </tr>
    <tr>
      <td>Data de Autoriza&ccedil;&atilde;o:</td>
      <td><input type=text name="dataAutorizacao" size="30" maxlength="10" onKeypress="return formatar(this,'##/##/####')";></td>
    </tr>
    <tr>
      <td>N&ordm; do Processo:</td>
      <td><input type=text name="numeroProcesso" size="30" maxlength="11" onKeypress="return formatar(this,'####/######')"></td>
    </tr>
    <tr>
      <td>Data de Expira&ccedil;&atilde;o:</td>
      <td><input type=text name="dataExpiracao" size="30" maxlength="10" onKeypress="return formatar(this,'##/##/####')";></td>
    </tr>
    <tr>
      <td>Aquisi&ccedil;&atilde;o:</td>
      <td>
        <select name="aquisicao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	        <? 
	          	$query = "SELECT id_aquisicao, nm_proprietario FROM aquisicoes ORDER BY nm_proprietario";
			    $result = mysql_query($query) or die('Ocorreu um erro no select');
			    echo '<option value=-1></option>';
			    while ($aquisicoes=mysql_fetch_array($result)) {
					echo '<option value=' . $aquisicoes['id_aquisicao'] . '>' . $aquisicoes['nm_proprietario'] . '</option>';
			    }
		    ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Data de Desinstala&ccedil;&atilde;o:</td>
      <td><input type=text name="dataDesinstalacao" size="30" maxlength="10" onKeypress="return formatar(this,'##/##/####')";></td>
    </tr>
    <tr>
      <td>Observa&ccedil;&atilde;o:</td>
      <td><textarea cols="23" rows="2" name="observacao"></textarea></td>
    </tr>
    <tr>
      <td>Patrim&ocirc;nio de Destino:</td>
      <td><input type=text name="patrimonioDestino"></td>
    </tr>
	<tr>
	  <td></td>
	  <td></td>
	</tr>
	<tr>
	  <td align="right"><input type="reset" name="cancelar" value="Cancelar"/></td>
	  <td align="left"><input type="submit" name="gravar" value="Gravar" width="100"></td>
	</tr>
</table>
<p>&nbsp;</p>
</body>
</html>
