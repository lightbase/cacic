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

require_once('../../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central
// 3 - Supervis�o

if($_POST['grava_alteracao_uon1']) 
	{
	Conecta_bd_cacic();
	
	$frm_nm_unid_organizacional_nivel1 = $_POST['frm_nm_unid_organizacional_nivel1'];  
	if ($_POST['frm_nm_unid_organizacional_nivel1'])
		{
		$query = "INSERT INTO unid_organizacional_nivel1 
				  		 (nm_unid_organizacional_nivel1, 
				   		  te_endereco_uon1,
				   		  te_bairro_uon1,
				   		  te_cidade_uon1,
				   		  te_uf_uon1,
				   		  nm_responsavel_uon1,
				   		  te_email_responsavel_uon1,
				   		  nu_tel1_responsavel_uon1,
				   		  nu_tel2_responsavel_uon1) 
				  VALUES ('".$_POST['frm_nm_unid_organizacional_nivel1']."', 
				   		  '".$_POST['frm_te_endereco_uon1']."',
				   		  '".$_POST['frm_te_bairro_uon1']."',
				   		  '".$_POST['frm_te_cidade_uon1']."',
				   		  '".$_POST['frm_te_uf_uon1']."',
				   		  '".$_POST['frm_nm_responsavel_uon1']."',
				   		  '".$_POST['frm_te_email_responsavel_uon1']."',
				   		  '".$_POST['frm_nu_tel1_responsavel_uon1']."',
				   		  '".$_POST['frm_nu_tel2_responsavel_uon1']."')";
		$result = mysql_query($query) or die ($oTranslator->_('falha na insercao em (%1) ou sua sessao expirou!',array('unid_organizacional_nivel1')));
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel1');
		if (!atualiza_configuracoes_uonx('1'))
			{
			echo mensagem($oTranslator->_('Falha na atualizacao de configuracoes'));
			}
		else
			{			
		    header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel1/index.php&tempo=1");											
			}
		}
	else
		{
	    header ("Location: ../../../include/nenhuma_operacao_realizada.php?chamador=../admin/patrimonio/nivel1/index.php&tempo=1");										
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
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function valida_form() 
	{
	if (document.form.frm_nm_unid_organizacional_nivel1.value == "")
		{
		alert("<?=$oTranslator->_('Por favor, preencha campo');?> "+ document.form.etiqueta1.value+".");
		document.form.frm_nm_unid_organizacional_nivel1.focus();
		return false;
		} 
	return true;	
	}
function SetaCampo() 
	{
    document.form.frm_nm_unid_organizacional_nivel1.focus();
	}

</script>
</head>

<body background="../../../imgs/linha_v.gif" onLoad="Javascript: SetaCampo('frm_nm_unid_organizacional_nivel1')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
      <?=$oTranslator->_('Inclusao de');?> <? echo $_SESSION['etiqueta1'];?> 
      (<?=$oTranslator->_('Unidade Organizacional Nivel 1');?>)
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
  <table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr> 
      <td nowrap>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><? echo $_SESSION['etiqueta1'];?>:</td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_nm_unid_organizacional_nivel1" type="text" id="frm_nm_unid_organizacional_nivel1" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><?=$oTranslator->_('Endereco');?>:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_endereco_uon1" type="text" id="frm_te_endereco_uon1" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><?=$oTranslator->_('Bairro');?>:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_bairro_uon1" type="text" id="frm_te_bairro_uon1" size="60" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Cidade');?>:</td>
      <td><input name="frm_te_cidade_uon1" type="text" id="frm_te_cidade_uon1" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </td>
      <td>&nbsp;</td>
      <td class="label"><div align="right"><?=$oTranslator->_('Unidade da Federacao',T_SIGLA);?>: 
          <input name="frm_te_uf_uon1" type="text" id="frm_te_uf_uon1" size="2" maxlength="2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Responsavel');?>:</td>
      <td colspan="3"><div align="left"><input name="frm_nm_responsavel_uon1" type="text" id="frm_nm_responsavel_uon1" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Endereco eletronico');?>:</td>
      <td colspan="3"><div align="left"><input name="frm_te_email_responsavel_uon1" type="text" id="frm_te_email_responsavel_uon1" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Um',T_SIGLA);?>:</td>
      <td><div align="left"><input name="frm_nu_tel1_responsavel_uon1" type="text" id="frm_nu_tel1_responsavel_uon1" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
      <td nowrap class="label"><div align="right"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Dois',T_SIGLA);?>:</div></td>
      <td><div align="right"><input name="frm_nu_tel2_responsavel_uon1" type="text" id="frm_nu_telefone2" size="20" maxlength="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><div align="left"></div></td>
      <td>&nbsp;</td>
      <td><div align="right"></div></td>
    </tr>
  </table>
  <p align="center"> 
  <?
  $v_frase = "Confirma('".$oTranslator->_('Confirma Informacoes para')." ".$_SESSION['etiqueta1']."?')";
  echo '<input name="grava_alteracao_uon1" type="submit" id="grava_alteracao_uon1" value="'.$oTranslator->_('Gravar Informacoes').'"  onClick="return '.$v_frase.'";>';
  ?>
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>