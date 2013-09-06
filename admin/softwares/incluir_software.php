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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include_once "../../include/library.php";
AntiSpy();
if($_REQUEST['submit']) 
{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		softwares 
			  WHERE 	nm_software = '".$_REQUEST['frm_nm_software']."'";
	$result = mysql_query($query) or die ('Select softwares falhou ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0) {
		header ("Location: ../../include/registro_ja_existe.php?chamador=../admin/softwares/incluir_software.php&tempo=1");									 							
		}
	else 
	{
	$v_qt_licenca = ($_REQUEST['frm_qt_licenca']?$_REQUEST['frm_qt_licenca']:'0');
		$query = "INSERT 
				  INTO 		softwares 
				  			(nm_software, 
							te_descricao_software, 
							qt_licenca, 
							nr_midia, 
							te_local_midia, 
							te_obs) 
				 VALUES 	('".
				 			$_REQUEST['frm_nm_software']."','".
				 			$_REQUEST['frm_te_descricao_software']."',".
							$v_qt_licenca.",'".
							$_REQUEST['frm_nr_midia']."','".
							$_REQUEST['frm_te_local_midia']."','".
							$_REQUEST['frm_te_obs']."')"; 

		$result = mysql_query($query) or die ('Insert softwares falhou ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'softwares');

		header ("Location: ../../include/operacao_ok.php?chamador=../admin/softwares/index.php&tempo=1");									 						
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
	
function valida_form() 
	{
	if ( document.form.frm_nm_software.value == "" ) 
		{	
		alert("O nome do Software é obrigatório.");
		document.form.frm_nm_software.focus();
		return false;
		}
	else if ( document.form.frm_te_descricao_software.value == "" ) 
		{	
		alert("A descrição do Software é obrigatória.");
		document.form.frm_te_descricao_software.focus();
		return false;
		}
	return true;
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

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_software')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o de Novo Software</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
      cadastradas abaixo referem-se a software gerenciado pelo CACIC, para posterior 
      utiliza&ccedil;&atilde;o em relat&oacute;rios gerenciais e/ou estat&iacute;sticos, 
      bem como no apoio a decis&otilde;es concernentes &agrave; utiliza&ccedil;&atilde;o 
      de softwares no ambiente gerenciado.</td>
  </tr>
</table>
<form action="incluir_software.php"  method="post" ENCTYPE="multipart/form-data" name="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label"><br>
        Nome:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_nm_software" type="text" size="50" maxlength="150"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
       </td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><input name="frm_te_descricao_software" type="text" id="frm_te_descricao_software" size="50" maxlength="255" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Quantidade de Licenças:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap> <input name="frm_qt_licenca" type="text" id="frm_qt_licenca" size="11" maxlength="11" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
    </tr>
    <tr> 
      <td nowrap class="label"><div align="left"><br>
          Número da Mídia:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><input name="frm_nr_midia" type="text" id="frm_nr_midia"  size="11" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Localização da Mídia:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap> <input name="frm_te_local_midia" type="text" id="frm_te_local_midia" size="50" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Observação:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_te_obs" size="50" maxlength="200" id="frm_te_obs" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
  </table>
  <p align="center"> 
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  "  onClick="return valida_form();return Confirma('Confirma Inclusão de Rede?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>