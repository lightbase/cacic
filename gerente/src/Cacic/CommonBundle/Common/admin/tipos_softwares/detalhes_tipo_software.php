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
AntiSpy();
Conecta_bd_cacic();

if ($ExcluiTipoSoftware) 
	{
	$query = "DELETE 
			  FROM 		tipos_software 
			  WHERE 	id_tipo_software = '$frm_id_tipo_software'";
	mysql_query($query) or die('Delete falhou ou sua sess�o expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'tipos_software');			

    header ("Location: ../../include/operacao_ok.php?chamador=../admin/tipos_softwares/index.php&tempo=1");					
	}
elseif ($GravaAlteracoes) 
	{
	$query = "UPDATE 	tipos_software 
			  SET 		te_descricao_tipo_software = '".$_REQUEST['frm_te_descricao_tipo_software']."'			  
			  WHERE 	id_tipo_software = ".$_REQUEST['frm_id_tipo_software'];
	mysql_query($query) or die('Update falhou ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'tipos_software');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/tipos_softwares/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		tipos_software 
			  WHERE 	id_tipo_software = '".$_REQUEST['id_tipo_software']."'";
	$result = mysql_query($query) or die ('Select em "tipos_software" falhou ou sua sess�o expirou!');
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Detalhes de Tipo de Software</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_te_descricao_tipo_software.value == "" ) 
		{	
		alert("A descri��o do Tipo de Software � obrigat�ria.");
		document.form.frm_te_descricao_tipo_software.focus();
		return false;
		}
	return true;	
	}
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_te_descricao_tipo_software');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Detalhes do Tipo de Software "<? echo $row['te_descricao_tipo_software'];?>"</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es referem-se a um tipo de 
      software cadastrado no sistema CACIC.</td>
  </tr>
</table>
<form action="detalhes_tipo_software.php"  method="post" ENCTYPE="multipart/form-data" name="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="label">Descri&ccedil;&atilde;o do Tipo de Software:</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
      <td nowrap><input name="frm_te_descricao_tipo_software" type="text" id="frm_te_descricao_tipo_software" value="<? echo $row['te_descricao_tipo_software']; ?>" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
	  <input name="frm_id_tipo_software" type="hidden" id="frm_id_tipo_software" value="<? echo $_REQUEST['id_tipo_software']; ?>"></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
  </table>
  <p align="center"> <br>

    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informa��es para Tipo de Software?');return valida_form();" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="ExcluiTipoSoftware" type="submit" value="  Excluir Tipo de Software" onClick="return Confirma('Confirma Exclus�o de Tipo de Software e TODAS AS DEPEND�NCIAS? (Redes e Usu�rios)');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
      </form>		  
		
</body>
</html>
<?
}
?>
