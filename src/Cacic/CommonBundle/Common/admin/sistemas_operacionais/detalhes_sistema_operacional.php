<?php
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

require_once('../../include/library.php');
AntiSpy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiSO'] && $_SESSION['cs_nivel_administracao']==1) 
	{
	$query = "DELETE 
			  FROM 		so 
			  WHERE 	id_so = ".$_REQUEST['frm_id_so'];
	mysql_query($query) or die('Falha de deleção na tabela so ou sua sessão expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'so',$_SESSION["id_usuario"]);				
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/sistemas_operacionais/index.php&tempo=1");									 				
	}
elseif ($_POST['GravaAlteracoes']  && $_SESSION['cs_nivel_administracao']==1) 
	{
	$strMsWindows = ($_POST['frm_in_mswindows']=='S'?'S':'N');
	$query = "UPDATE 	so SET 
			  			te_desc_so 	 = '".$_POST['frm_te_desc_so']."',
			  			sg_so		 = '".$_POST['frm_sg_so']."', 
			  			te_so		 = '".$_POST['frm_te_so']."',
			  			in_mswindows = '".$strMsWindows."'						
			  WHERE 	id_so 		 = ".$_REQUEST['frm_id_so'];
	mysql_query($query) or die('Falha na atualização da tabela SO ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'SO',$_SESSION["id_usuario"]);
			
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/sistemas_operacionais/index.php&tempo=1");									 					
	}
else 
	{
	$query = "	SELECT 	* 
				FROM 	so
				WHERE id_so = ".$_GET['id_so'];
	$result = mysql_query($query) or die ('Falha na consulta à tabela SO ou sua sessão expirou!');
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
	
function valida_form() 
	{	
	if (document.form.frm_te_desc_so == "") 
		{	
		alert("A descrição do Sistema Operacional é obrigatória");
		document.form.frm_te_desc_so.focus();
		return false;
		}
	else if ( document.form.frm_sg_so.value == "" ) 
		{	
		alert("A sigla do Sistema Operacional é obrigatória.");
		document.form.frm_sg_so.focus();
		return false;
		}
		
	else if ( document.form.frm_te_so.value == "" ) 
		{	
		alert("A Identificação Interna do Sistema Operacional é obrigatória.");
		document.form.frm_te_so.focus();
		return false;
		}
		
	// Se chegou até aqui, posso habilitar o campo frm_te_so!		
	document.form.frm_te_so.disabled = false;
		
	return true;
	}
</script>
</head>
<body <?php if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('frm_te_desc_so')">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<form method="post" ENCTYPE="multipart/form-data" name="form">
<table width="85%" border="0" align="center">
  <tr> 
      <td class="cabecalho">Detalhes do Sistema Operacional <?php echo $row['te_desc_so']; ?></td>
  </tr>
  <tr> 
      <td class="descricao">As informa&ccedil;&otilde;es abaixo referem-se a um 
        sistema operacional instalado em parque computacional inventariado pelo 
        CACIC, onde a ID Interna refere-se ao valor enviado pelo Gerente de Coletas (gercols.exe), composto por <strong>platformId</strong>, <strong>majorVer</strong>, <strong>minorVer</strong> e <strong>CSDVersion</strong>. </td>
  </tr>
</table>

  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label"><br>
        Descri&ccedil;&atilde;o:</td>
      <td class="label">Sigla:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
      <td bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_te_desc_so" type="text"  class="normal" id="frm_te_desc_so" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" size="50" maxlength="50" value="<?php echo $row['te_desc_so'];?>" > 
      </td>
      <td><input name="frm_sg_so" type="text" id="frm_sg_so" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $row['sg_so'];?>" ></td>
    </tr>
    <tr> 
      <td class="label">&nbsp;</td>
      <td class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"><div align="left">ID Interna:</div></td>
      <td class="label">ID Externa:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
      <td bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><input name="frm_te_so" type="text"          class="normal" id="frm_te_so" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $row['te_so'];?>" size="50" maxlength="50" <?php if (trim($row['te_so']) <> '') echo 'disabled readonly="true"';?>></td>
      <td nowrap><input name="frm_id_so" type="text" disabled class="normal" id="frm_id_so" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $row['id_so'];?>" size="50" maxlength="11" readonly="true"></td>
    </tr>
    <tr>
      <td nowrap>&nbsp;</td>
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
      <td bgcolor="#333333"></td>
    </tr>
	
    <tr>
      <td nowrap class="label"><div align="left"><input type="checkbox" name="frm_in_mswindows" id="frm_in_mswindows" value="S" <?php if ($row['in_mswindows']=='S') echo 'checked';?>>
      Sistema Operacional MS-Windows</div></td>
      <td nowrap>&nbsp;</td>
    </tr>
  </table>
  <p align="center"> <br>
    <br>
	<?php if ($_SESSION['cs_nivel_administracao']==1)
		{
		?>
	    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Alterações  " onClick="return valida_form();return Confirma('Confirma Informações para Local?');" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
	     
    	<input name="ExcluiSO" type="submit" id="ExcluiSO" onClick="return Confirma('Confirma Exclusão de Sistema Operacional?');" value="  Excluir Sistema Operacional" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
		<?php
		}
		?>
  </p>
<input type="hidden" id="frm_id_so" name="frm_id_so" value="<?php echo $_GET['id_so'];?>">  
</form>
</body>
</html>
<?php
}
?>