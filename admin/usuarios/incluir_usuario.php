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
include_once "../../include/library.php";
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if($submit) {

	$frm_nm_usuario_acesso = $_POST['frm_nm_usuario_acesso'];  	
	$query = "SELECT 	* 
			  FROM 		usuarios 
			  WHERE 	nm_usuario_acesso = '$frm_nm_usuario_acesso'";
	$result = mysql_query($query) or die ('Falha na consulta à tabela Usuários');
	
	if (mysql_num_rows($result) > 0) {
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/usuarios/index.php&tempo=1");									 												
		}
	else {
		$query = "INSERT 
				  INTO 		usuarios
				  			(nm_usuario_acesso, 
							 nm_usuario_completo, 
							 te_senha,  
							 dt_log_in, 
							 id_grupo_usuarios,
							 id_local,
							 te_emails_contato,
							 te_telefones_contato) 
				  VALUES 	('$frm_nm_usuario_acesso', 
				  			'$frm_nm_usuario_completo', 
				  		  	PASSWORD('$frm_nm_usuario_acesso'), now(),
							'$frm_id_grupo_usuarios',
							'$frm_id_local',
							'$frm_te_emails_contato',
							'$frm_te_telefones_contato')";
		$result = mysql_query($query) or die ('Insert falhou');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usuarios');		
		header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 											
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
function SetaDescGrupo(p_descricao,p_destino) 
	{
	document.forms[0].elements[p_destino].value = p_descricao;		
	}
	

function valida_form() {
	if (document.form.frm_id_local.selectedIndex==0) {	
		alert("O local do usuário é obrigatório");
		document.form.frm_id_local.focus();
		return false;
	}

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
    <td class="cabecalho"><div align="left">Inclus&atilde;o 
        de novo usu&aacute;rio</div></td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es 
      que dever&atilde;o ser cadastradas abaixo referem-se aos usu&aacute;rios 
      do sistema, onde ser&aacute; determinado o tipo de acesso. <u><em>A senha 
      inicial ser&aacute; gerada automaticamente em fun&ccedil;&atilde;o da identifica&ccedil;&atilde;o 
      e poder&aacute; ser trocada pelo usu&aacute;rio na op&ccedil;&atilde;o Acesso/Troca 
      de Senha no menu principal</em></u>. Ap&oacute;s o logon, ser&atilde;o exibidas 
      a primeira e &uacute;ltima parte do campo Nome Completo.</td>
  </tr>
</table>
<?
	$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE id_local = '.$_SESSION['id_local']:'');
	$qry_local = "SELECT 		id_local, 
									sg_local 
						FROM 		locais ".
									$where . "
						ORDER BY	sg_local";
	$result_local = mysql_query($qry_local) or die ('Falha na consulta à tabela Locais...');
?>

  <p>&nbsp;</p><form action="incluir_usuario.php"  method="post" ENCTYPE="multipart/form-data" name="form" onsubmit="return valida_form()">
  <table border="0" align="center" cellpadding="2" cellspacing="2">
    <tr> 
      <td class="label">Local:</td>
      <td><select name="frm_id_local" id="frm_id_local" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?"disabled":"");?>>
          <?
			echo '<option value="0">Selecione o Local</option>';		  
		  	while($row = mysql_fetch_row($result_local))
		  		{
				echo '<option value="'.$row[0].'"';
				echo ($_SESSION['cs_nivel_administracao']<>1?" selected ":"");
				echo '>'.$row[1].'</option>';
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
      <td> <input name="frm_nm_usuario_acesso" type="text" id="frm_nm_usuario_acesso" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        &nbsp;&nbsp;<strong>Ex: </strong>d308951</td>
    </tr>
    <tr> 
      <td class="label">Nome Completo:</td>
      <td><input name="frm_nm_usuario_completo" type="text" id="frm_nm_usuario_completo" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <?
	$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE cs_nivel_administracao > '.$_SESSION['cs_nivel_administracao'].' OR cs_nivel_administracao=0':'');
	$qry_grp_usu = "SELECT 		id_grupo_usuarios, 
								te_grupo_usuarios, 
								te_descricao_grupo 
					FROM 		grupo_usuarios ".
								$where . "
					ORDER BY	te_grupo_usuarios";
	$result_qry_grp = mysql_query($qry_grp_usu) or die ('Falha na consulta à tabela Grupo_Usuarios...');
?>
    <tr nowrap>
      <td class="label">Emails para Contato:</td>
      <td><input name="frm_te_emails_contato" type="text" id="frm_te_emails_contato" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr nowrap>
      <td class="label">Telefones para Contato:</td>
      <td><input name="frm_te_telefones_contato" type="text" id="frm_te_telefones_contato" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr nowrap> 
      <td class="label">Tipo de Acesso:</td>
      <td> <select name="frm_id_grupo_usuarios" id="frm_id_grupo_usuarios" onChange="SetaDescGrupo(this.options[selectedIndex].id,'frm_te_descricao_grupo')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?
		  	while($row = mysql_fetch_row($result_qry_grp))
		  		{
				if (!$v_te_descricao_grupo) $v_te_descricao_grupo = $row[2]; 				
				echo '<option value="'.$row[0].'" id="'.$row[2].'">'.$row[1].'</option>';
				}
				?>
        </select></td>
    </tr>
    <tr nowrap> 
      <td class="label">Descri&ccedil;&atilde;o de Acesso:</td>
      <td><textarea name="frm_te_descricao_grupo" cols="50" rows="4" id="frm_te_descricao_grupo" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $v_te_descricao_grupo;?></textarea></td>
    </tr>
  </table>
  <p align="center"> <br>
    <br>
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclusão de Usuário?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
