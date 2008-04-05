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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');

// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if ($GravaAlteracoes) {
	$query = "UPDATE usuarios SET 
			  te_senha = PASSWORD('$frm_te_nova_senha')
			  WHERE id_usuario = ". $_SESSION['id_usuario'];
	mysql_query($query) or die('Update falhou ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usuarios');		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/troca_senha.php&tempo=1");									 										
	
}
else {
	$query = "SELECT a.nm_usuario_acesso, a.nm_usuario_completo, a.te_senha 
			  FROM usuarios a
			  WHERE a.id_usuario = ".$_SESSION['id_usuario'];

	$result = mysql_query($query) or die ($oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('usuarios')));
	$row_usuario = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Troca de Senha de Acesso');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function valida_nova_senha() 
	{
	if (document.forma.frm_te_nova_senha.value != document.forma.frm_te_verifica_senha.value ) 
		{	
		alert("Senhas não conferem!");
		document.forma.frm_te_nova_senha.focus();
		return false;
		}
	return true;
	}


</script>

</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_te_senha_atual')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Troca de Senha de Acesso');?></td>
  </tr>
  <tr> 
    <td class="descricao">
    <?=$oTranslator->_('Informe a senha anterior, caso nao se lembre, solicite a um Administrador que a reinicialize.');?>
    </td>
  </tr>
</table>
<p>&nbsp;</p><table width="60%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 
<form action="troca_senha.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
        <table width="453" border="0" cellpadding="2" cellspacing="2">
          <tr> 
            <td class="label"><?=$oTranslator->_('Identificacao');?>:</td>
            <td class="normal"><? echo mysql_result($result, 0, 'nm_usuario_acesso'); ?></td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Nome Completo');?>:</td>
            <td class="normal"><? echo mysql_result($result, 0, 'nm_usuario_completo'); ?></td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Senha Atual');?>:</td>
            <td> 
              <input name="frm_te_senha_atual" type="password" id="frm_te_senha_atual" size="10" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </td>
          </tr>
          <tr>
            <td nowrap class="label"><?=$oTranslator->_('Nova Senha');?>:</td>
            <td>
              <input name="frm_te_nova_senha" type="password" id="frm_te_nova_senha" size="10" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('kciq_msg verify');?>:</td>
            <td> 
              <input name="frm_te_verifica_senha" type="password" id="frm_te_verifica_senha" size="10" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);return valida_nova_senha();" >
              </td>
          </tr>
        </table>
        <p align="center"> 
          <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="<?=$oTranslator->_('Gravar alteracoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma troca de senha?');?>');">
        </p>
      </form></td>
  </tr>
</table>
</body>
</html>
<?
}
?>
