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
require_once('../../../include/library.php');
// Comentado temporariamente - AntiSpy();
if($grava_alteracao_uon1) 
	{
	Conecta_bd_cacic();
	
	$frm_nm_unid_organizacional_nivel1 = $_POST['frm_nm_unid_organizacional_nivel1'];  
	if ($frm_nm_unid_organizacional_nivel1)
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
				  VALUES ('$frm_nm_unid_organizacional_nivel1', 
				   		  '$frm_te_endereco_uon1',
				   		  '$frm_te_bairro_uon1',
				   		  '$frm_te_cidade_uon1',
				   		  '$frm_te_uf_uon1',
				   		  '$frm_nm_responsavel_uon1',
				   		  '$frm_te_email_responsavel_uon1',
				   		  '$frm_nu_tel1_responsavel_uon1',
				   		  '$frm_nu_tel2_responsavel_uon1')";
		$result = mysql_query($query) or die ('Insert falhou ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel1');
		if (!atualiza_configuracoes_uonx('1'))
			{
			echo mensagem('Falha na atualização de configurações');
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
		alert("Por favor, preencha campo "+ document.form.etiqueta1.value+".");
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
    <td class="cabecalho">Inclus&atilde;o de <? echo $_SESSION['etiqueta1'];?> (U. O. N&iacute;vel 
      1)</td>
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
      <td class="label"><div align="left">Endere&ccedil;o:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_endereco_uon1" type="text" id="frm_te_endereco_uon1" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Bairro:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_bairro_uon1" type="text" id="frm_te_bairro_uon1" size="60" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label">Cidade:</td>
      <td><input name="frm_te_cidade_uon1" type="text" id="frm_te_cidade_uon1" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </td>
      <td>&nbsp;</td>
      <td class="label"><div align="right">UF: 
          <input name="frm_te_uf_uon1" type="text" id="frm_te_uf_uon1" size="2" maxlength="2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label">Respons&aacute;vel:</td>
      <td colspan="3"><div align="left"><input name="frm_nm_responsavel_uon1" type="text" id="frm_nm_responsavel_uon1" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label">E-mail:</td>
      <td colspan="3"><div align="left"><input name="frm_te_email_responsavel_uon1" type="text" id="frm_te_email_responsavel_uon1" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
    </tr>
    <tr> 
      <td class="label">Tel. 1:</td>
      <td><div align="left"><input name="frm_nu_tel1_responsavel_uon1" type="text" id="frm_nu_tel1_responsavel_uon1" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          </div></td>
      <td nowrap class="label"><div align="right">Tel. 2:</div></td>
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
  $v_frase = "Confirma('Confirma Inclusão de ".$_SESSION['etiqueta1']."?')";
  echo '<input name="grava_alteracao_uon1" type="submit" id="grava_alteracao_uon1" value="  Gravar Informa&ccedil;&otilde;es  "  onClick="return '.$v_frase.'";>';
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