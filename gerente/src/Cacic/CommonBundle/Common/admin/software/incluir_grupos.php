<?
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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

include_once "../../include/library.php";
if($_REQUEST['submit']) 
{
	Conecta_bd_cacic();
	
	$frm_nm_si_grupo = $_POST['frm_nm_si_grupo']; 
	$frm_desc_si_grupo = $_POST['frm_desc_si_grupo'];  
	$frm_email_si_grupo = $_POST['frm_email_si_grupo'];   
	
	$query = "SELECT * FROM softwares_inventariados_grupos WHERE nm_si_grupo = '$frm_nm_si_grupo'";
	$result = mysql_query($query) or die ('Select falhou ou sua sess�o expirou!');
	
	if (mysql_num_rows($result) > 0) {
		echo '<p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p><body background="../../imgs/linha_v.gif">
			  <table border="0" align="center" cellpadding="20" cellspacing="1" bgcolor="#666666">
			    <tr> 
				  <td valign="top" bgcolor="#EEEEEE"><div align="center">
					<div align="center">
				    <font size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>O Grupo ' . $frm_id_ip_rede . '<br>j&aacute; est&aacute; cadastrado</strong></font>
				   </div>
				</td>
				</tr>
			  </table>';
	}
	else 
	{
		$query = "INSERT INTO softwares_inventariados_grupos 
				  (nm_si_grupo, desc_si_grupo, email_si_grupo) 
				  VALUES ('$frm_nm_si_grupo', 
				  		  '$frm_desc_si_grupo', 											  
						  '$frm_email_si_grupo')";									  

		$result = mysql_query($query) or die ('Insert falhou ou sua sess�o expirou!');		
	}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
}
else 
{
?>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
/*function SetaServidorBancoDados()	
	{
	document.form.frm_te_serv_cacic.value = document.form.sel_te_serv_cacic.value;	
	document.form.sel_te_serv_cacic.options.selectedIndex=0;		
	}
function SetaServidorUpdates()	
	{
	var v_string = document.form.sel_te_serv_updates.value;
	var v_array_string = v_string.split("#");
	document.form.frm_te_serv_updates.value = v_array_string[0];
	document.form.frm_nu_porta_serv_updates.value = v_array_string[1];	
	document.form.frm_nm_usuario_login_serv_updates.value = v_array_string[2];		
	document.form.frm_nm_usuario_login_serv_updates_gerente.value = v_array_string[2];			
	document.form.frm_te_path_serv_updates.value = v_array_string[3];				
	document.form.sel_te_serv_updates.options.selectedIndex=0;
	document.form.frm_te_senha_login_serv_updates.value = "";
	document.form.frm_te_senha_login_serv_updates_gerente.value = "";	
	var v_campo_senha = document.form.document.frm_te_senha_login_serv_updates;
	v_campo_senha.document.write('<div style="background-color:#000099;"</div>');
	v_campo_senha.document.close();
	var v_campo_senha_gerente = document.form.document.frm_te_senha_login_serv_updates_gerente;
	v_campo_senha_gerente.document.write('<div style="background-color:#000099;"</div>');
	v_campo_senha_gerente.document.close();
	
	document.form.frm_te_senha_login_serv_updates.select();
	}
	*/
function valida_form() 
	{
	if ( document.form.frm_nm_si_grupo.value == "" ) 
		{	
		alert("O campo grupo � obrigat�rio.\nPor favor, informe-o.");
		document.form.frm_nm_si_grupo.focus();
		return false;
		}
	}
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_si_grupo')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o de novo Grupo </td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es 
      que dever&atilde;o ser cadastradas abaixo referem-se a um grupo onde ser&atilde;o 
      listados os softwares do CACIC. O campo &quot;Grupo&quot; &eacute; obrigat&oacute;rio.</td>
  </tr>
</table>
<form action="incluir_grupos.php"  method="post" ENCTYPE="multipart/form-data" name="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label"><br>
      Grupo:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_nm_si_grupo" type="text"  class="normal" id="frm_nm_si_grupo" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" size="70" maxlength="16" ></td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><br>
      Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><textarea name="frm_desc_si_grupo" cols="50" wrap="virtual" class="normal" id="frm_nm_rede2" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"></textarea></td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
      E-mail Respons&aacute;veis pelo grupo: (Separar por v&iacute;rgula) </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><textarea name="frm_email_si_grupo" cols="50" wrap="virtual" class="normal" id="textarea2" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"></textarea></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <p align="center"> 
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  "  onClick="return valida_form();return Confirma('Confirma Inclus�o de Rede?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
