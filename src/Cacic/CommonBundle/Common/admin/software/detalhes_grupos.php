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
require_once('../../include/library.php');
Conecta_bd_cacic();


if ($_POST['GravaAlteracoes']) 
	{
	
	$frm_nm_si_grupo = $_POST['frm_nm_si_grupo']; 
	$frm_desc_si_grupo = $_POST['frm_desc_si_grupo'];  
	$frm_email_si_grupo = $_POST['frm_email_si_grupo']; 
	
	$query = "UPDATE softwares_inventariados_grupos 
			  SET 
			  nm_si_grupo = '".$_POST['frm_nm_si_grupo']."',
			  desc_si_grupo = '".$_POST['frm_desc_si_grupo']."', 
			  email_si_grupo = '".$_POST['frm_email_si_grupo']."'
			  WHERE trim(id_si_grupo) = '".trim($_POST['id_si_grupo'])."'";

	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/software/index_grupos.php&tempo=1");									 				
	
}
else {

	$query = "SELECT * FROM softwares_inventariados_grupos WHERE id_si_grupo  = '".$_GET['id_si_grupo']."'";
	$result = mysql_query($query) or die ('Select em "softwares_inventariados_grupos" falhou ou sua sess�o expirou!');
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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
	document.form.frm_te_senha_login_serv_updates.select();
	}*/
	
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
</head>
<?
$pos = substr_count($_SERVER['HTTP_REFERER'],'navegacao');
?>
<body <? if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('frm_nm_si_grupo')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="detalhes_grupos.php"  method="post" ENCTYPE="multipart/form-data" name="form">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Detalhes 
      do Grupo <? echo mysql_result($result, 0, 'nm_si_grupo'); ?>
   </td>
  </tr>
</table>

<table width="60%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 

        <table width="100%" border="0" cellspacing="1" cellpadding="0">
          <tr>
            <td class="label"><br>
Grupo:</span></td>
          </tr>
		   <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr>
            <td><input name="frm_nm_si_grupo" type="text" class="normal" id="frm_nm_si_grupo" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo mysql_result($result, 0, 'nm_si_grupo'); ?>" size="70" maxlength="16">
              <input name="id_si_grupo"  type="hidden" id="id_ip_rede2" value="<? echo mysql_result($result, 0, 'id_si_grupo'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr>
            <td class="label"><br>
Descri&ccedil;&atilde;o:</span></td>
          </tr>
		   <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr>
            <td><textarea name="frm_desc_si_grupo" cols="50" wrap="virtual" class="normal" id="frm_nm_rede2" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"><? echo mysql_result($result, 0, 'desc_si_grupo'); ?></textarea></td>
          </tr>
          <tr>
            <td class="label"><br>
            E-mail Respons&aacute;veis pelo grupo: (Separar por v&iacute;rgula):</span></td>
          </tr>
		   <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr>
            <td><textarea name="frm_email_si_grupo" cols="50" wrap="virtual" class="normal" id="textarea2" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"><? echo mysql_result($result, 0, 'email_si_grupo'); ?></textarea></td>
          </tr>
          <tr>
            <td><p>&nbsp;
  </p>
              <p>
  <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return valida_form();return Confirma('Confirma Informa��es para Esta Rede?');">
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>
          </tr>
        </table>
        <p>&nbsp;</p>
      </table>
</form>
</body>
</html>
<?
}
?>
