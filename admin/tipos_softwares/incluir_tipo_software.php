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
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include_once "../../include/library.php";
// Comentado temporariamente - AntiSpy();
if($submit) 
{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		tipos_software 
			  WHERE 	te_descricao_tipo_software = '$frm_te_descricao_tipo_software'";
	$result = mysql_query($query) or die ('Select em "tipos_software" falhou ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/tipos_software/index.php&tempo=1");									 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		tipos_software
				  			(te_descricao_tipo_software) 
				  VALUES 	('$frm_te_descricao_tipo_software')";									  						  
		$result = mysql_query($query) or die ('Falha na Inserção em Tipos Softwares ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'tipos_software');		
		
		// Provavelmente uma solução temporária!...
		// Probaly a temporary solution...
		$query = "SELECT 	max(id_tipo_software) as max_id_tipo_software
				  FROM		tipos_software";
		$result = mysql_query($query) or die ('Falha na Consulta à tabela Tipos Software ou sua sessão expirou!');
		$row_max_id_tipo_software = mysql_fetch_array($result);
		
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'tipos_software');
	
	    header ("Location: index.php");		
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
<title>Inclus&atilde;o de Tipo de Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_te_descricao_tipo_software.value == "" ) 
		{	
		alert("A descrição do Tipo de Software é obrigatória.");
		document.form.frm_te_descricao_tipo_software.focus();
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

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_te_descricao_tipo_software');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o 
      de Tipo de Software</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
      cadastradas abaixo referem-se a um tipo de software gerenciado pelo CACIC, 
      para posterior utiliza&ccedil;&atilde;o em relat&oacute;rios gerenciais 
      e/ou estat&iacute;sticos, bem como no apoio a decis&otilde;es concernentes 
      &agrave; utiliza&ccedil;&atilde;o de softwares no ambiente gerenciado.</td>
  </tr>
</table>
<form action="incluir_tipo_software.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td class="label" colspan="2"><div align="left"> Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td nowrap colspan="2"><input name="frm_te_descricao_tipo_software" type="text" id="frm_te_descricao_tipo_software" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <p align="center"> 
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclusão de Tipo de Software?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
