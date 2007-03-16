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
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if ($ExcluiUsuario) 
	{
	$query = "DELETE 
			  FROM 		usuarios 
			  WHERE 	id_usuario = '". $_POST['frm_id_usuario'] ."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha na deleção na tabela Usuários...');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	}
elseif ($GravaAlteracoes) 
	{
	$query = "UPDATE 	usuarios 
			  SET 		nm_usuario_acesso = '$frm_nm_usuario_acesso',  
			  			nm_usuario_completo = '$frm_nm_usuario_completo', 
						id_grupo_usuarios = '$frm_id_grupo_usuarios',
						id_local = $frm_id_local,
						te_emails_contato = '$frm_te_emails_contato',
						te_telefones_contato = '$frm_te_telefones_contato'
			  WHERE 	id_usuario = ". $_POST['frm_id_usuario'];

	mysql_query($query) or die('Falha na atualização da tabela Usuários...');

	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	
	}
elseif ($ReinicializaSenha) 
	{
	$query = "UPDATE 	usuarios 
			  SET		te_senha = PASSWORD('".$_POST['frm_nm_usuario_acesso']."')
			  WHERE 	id_usuario = ". $_POST['frm_id_usuario'] ." AND
			  			id_local = ".$_POST['frm_id_local'];
	mysql_query($query) or die('Falha na atualização da tabela Usuários...');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	
}
else {
	$query = "SELECT 	a.nm_usuario_acesso, 
						a.nm_usuario_completo, 
						a.id_grupo_usuarios, 
						a.id_local,
						a.te_emails_contato,
						a.te_telefones_contato, 
						loc.sg_local
			  FROM 		usuarios a, 
			  			locais loc
			  WHERE 	a.id_usuario = '".$_GET['id_usuario']."' and 
			  			a.id_local = loc.id_local";

	$result = mysql_query($query) or die ('select falhou');
	$row_usuario = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function SetaDescGrupo(p_descricao,p_destino) 
	{
	document.forms[0].elements[p_destino].value = p_descricao;		
	}
	
function valida_form() {

	if (document.form.frm_nm_usuario_acesso.value == "" ) {	
		alert("A identificação do usuário é obrigatória");
		document.form.frm_nm_usuario_acesso.focus();
		return false;
	}
	if (document.form.frm_nm_usuario_completo.value == "" ) {	
		alert("O nome completo do usuário é obrigatório");
		document.form.frm_nm_usuario_completo.focus();
		return false;
	}

	return true;

}
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_local')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Detalhes 
      de Usuário</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es 
      abaixo referem-se ao usu&aacute;rio cadastrado no sistema. Ap&oacute;s o 
      logon, ser&atilde;o exibidas a primeira e &uacute;ltima parte do campo &quot;Nome 
      Completo&quot;. Ao reinicializar a senha esta assume o valor contido no 
      campo &quot;Identifica&ccedil;&atilde;o&quot;.</td>
  </tr>
</table>
<p>&nbsp;</p><table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 
<form action="detalhes_usuario.php"  method="post" ENCTYPE="multipart/form-data" name="form" onsubmit="return valida_form()">
        <table border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td class="label">Local:</td>
            <td><select name="frm_id_local" id="frm_id_local"" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"
			<?
			echo ($_SESSION['cs_nivel_administracao']>1?'disabled':'');
			?>
			 >
                <? 
			$qry_locais = "SELECT 	id_local,
											sg_local 
								 FROM 		locais 
								 ORDER BY	sg_local";
		    $result_locais = mysql_query($qry_locais) or die ('Select falhou');
			while ($row_qry=mysql_fetch_array($result_locais))
		  		{
				echo '<option value="'.$row_qry[0].'"';
				if ($row_qry['id_local'] == $row_usuario["id_local"]) 
					{
				  	$v_sg_local = $row_qry[1]; 					
					echo 'selected';
					}
					?> id='
                <? 
				echo $row_qry[1];?>'>
                <? echo $row_qry[1];?> 
                <?
				}
						
			?>
              </select>
		<?
		// Se não for nível Administrador então fixa o id_local...
		if ($_SESSION['cs_nivel_administracao']<>1)
			echo '<input name="frm_id_local" type="hidden" id="frm_id_local" value="'.$_SESSION['id_local'].'">';		
		?>
			  
			  </td>
          </tr>
          <tr> 
            <td class="label">Identifica&ccedil;&atilde;o:</td>
            <td><input name="frm_nm_usuario_acesso"  readonly="" type="text" id="frm_nm_usuario_acesso" value="<? echo mysql_result($result, 0, 'nm_usuario_acesso'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr> 
            <td class="label">Nome Completo:</td>
            <td><input name="frm_nm_usuario_completo" type="text" id="frm_nm_usuario_completo" value="<? echo mysql_result($result, 0, 'nm_usuario_completo'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <input name="frm_id_usuario" type="hidden" id="frm_id_usuario" value="<? echo $_GET['id_usuario']; ?>"> 
              <input name="id_local" type="hidden" id="id_usuario" value="<? echo $_GET['id_local']; ?>"></td>
          </tr>
          <tr nowrap>
            <td nowrap class="label">Emails para Contato:</td>
            <td nowrap><input name="frm_te_emails_contato" type="text" id="frm_te_emails_contato" value="<? echo mysql_result($result, 0, 'te_emails_contato'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr nowrap>
            <td nowrap class="label">Telefones para Contato:</td>
            <td nowrap><input name="frm_te_telefones_contato" type="text" id="frm_te_telefones_contato" value="<? echo mysql_result($result, 0, 'te_telefones_contato'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr nowrap> 
            <td nowrap class="label">Tipo de Acesso:</td>
            <td nowrap> <select name="frm_id_grupo_usuarios" id="frm_id_grupo_usuarios" onChange="SetaDescGrupo(this.options[selectedIndex].id,'frm_te_descricao_grupo')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_GET['id_usuario']==$_SESSION['id_usuario'] && $_SESSION['cs_nivel_administracao']<>1?'disabled':'');?>>
                <? 
			$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' WHERE cs_nivel_administracao >= '.$_SESSION['cs_nivel_administracao'].' OR cs_nivel_administracao = 0':'');
			$qry_grp_usu = "SELECT 		id_grupo_usuarios, 
										te_grupo_usuarios,
										te_descricao_grupo 
							FROM 		grupo_usuarios ".
										$where . "
							ORDER BY	te_grupo_usuarios";
		    $result_qry_grp = mysql_query($qry_grp_usu) or die ('Select falhou');
			while ($row_qry=mysql_fetch_array($result_qry_grp))
		  		{
				echo '<option value="'.$row_qry[0].'"';
				if ($row_qry['id_grupo_usuarios'] == $row_usuario["id_grupo_usuarios"]) 
					{
				  	$v_te_descricao_grupo = $row_qry[2]; 					
					echo 'selected';
					}
					?> id="
                <? echo $row_qry[2];?>"><? echo $row_qry[1];?></option> 
                <?
				}
						
			?>
              </select> </td>
          </tr>
          <tr nowrap> 
            <td nowrap class="label">Descri&ccedil;&atilde;o de Acesso:</td>
            <td nowrap><textarea name="frm_te_descricao_grupo" cols="50" rows="4" id="frm_te_descricao_grupo" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $v_te_descricao_grupo;?></textarea></td>
          </tr>
        </table>
        <p align="center"> 
          <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para Usuário?');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
		  &nbsp;&nbsp;
          <input name="ReinicializaSenha" type="submit" id="ReinicializaSenha" value="  Reinicializar Senha  " <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
          &nbsp; &nbsp; 
          <input name="ExcluiUsuario" type="submit" id="ExcluiUsuario" value="  Excluir Usu&aacute;rio" onClick="return Confirma('Confirma Exclusão de Usuário?');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
			<?
			if ($_REQUEST['nm_chamador'])
				{
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                <input name="Retorna" type="button" value="  Retorna para <? echo str_replace("_"," ",$_REQUEST['nm_chamador']);?>  " onClick="history.back()">
				<?
				}
				?>
				</p>
		  
      </form></td>
  </tr>
</table>
</body>
</html>
<?
}
?>
