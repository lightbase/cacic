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
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if($grava_alteracao_uon1a) 
	{
	Conecta_bd_cacic();
	
	if ($_POST['frm_nm_unid_organizacional_nivel1a'])
		{
		$query = "INSERT INTO unid_organizacional_nivel1a 
				  		 (id_unid_organizacional_nivel1, 
				  		  nm_unid_organizacional_nivel1a) 
				  VALUES (".$_POST['selectUON1'].", 
				  		  '$frm_nm_unid_organizacional_nivel1a')";
		$result = mysql_query($query) or die ('Insert em UON1a falhou ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel1a');
		if (!atualiza_configuracoes_uonx('1a'))
			{
			echo mensagem('Falha na atualização de configurações');
			}
		else
			{			
		    header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel1a/index.php&tempo=1");											
			}
		}
	else
		{
	    header ("Location: ../../../include/nenhuma_operacao_realizada.php?chamador=../admin/patrimonio/nivel1a/index.php&tempo=1");										
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
	if (document.form.frm_nm_unid_organizacional_nivel1a.value == "")
		{
		alert("Por favor, preencha campo "+ document.form.etiqueta1a.value+".");
		document.form.frm_nm_unid_organizacional_nivel1a.focus();
		return false;
		} 
	return true;	
	}
function SetaCampo() 
	{
    document.form.frm_nm_unid_organizacional_nivel1a.focus();
	}

</script>
</head>

<body background="../../../imgs/linha_v.gif" onLoad="Javascript: SetaCampo('selectUON1')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o de <? echo $_SESSION['etiqueta1a'];?> (U. O. N&iacute;vel 
      1a)</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
  <table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr> 
      <td nowrap class="label"><? echo $_SESSION['etiqueta1']; ?>:</td>
      <td colspan="3"> <div align="left"> 
          <input name="etiqueta1" type="hidden" id="etiqueta1" value="<? echo $_SESSION['etiqueta1']; ?>">
          <input name="etiqueta1a" type="hidden" id="etiqueta1a" value="<? echo $_SESSION['etiqueta1a']; ?>">
          <select name="selectUON1" id="selectUON1"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
            <option value="0" selected>Selecione <? echo $_SESSION['etiqueta1']; ?></option>
            <?
$querySEL1 = 'SELECT 	uo1.id_unid_organizacional_nivel1,
						uo1.nm_unid_organizacional_nivel1
		  	  FROM 		unid_organizacional_nivel1 uo1
			  ORDER BY	uo1.nm_unid_organizacional_nivel1';

Conecta_bd_cacic();			  
$result_sel1 = mysql_query($querySEL1);			
			
if(mysql_num_rows($result_sel1))
	{	              
	while($row = mysql_fetch_array($result_sel1))
		{
		echo "<option value='". $row['id_unid_organizacional_nivel1'] . "'>".$row['nm_unid_organizacional_nivel1'].'</option>';
		} 		
	}
	?>
          </select>
        </div></td>
    </tr> 
    <tr> 
      <td class="label"><div align="left"><? echo $_SESSION['etiqueta1a'];?>:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_nm_unid_organizacional_nivel1a" type="text" id="frm_nm_unid_organizacional_nivel1a" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
  </table>
  <p align="center"> 
  <?
  $v_frase = "Confirma('Confirma Inclusão de ".$_SESSION['etiqueta1a']."?')";
  echo '<input name="grava_alteracao_uon1a" type="submit" id="grava_alteracao_uon1a" value="  Gravar Informa&ccedil;&otilde;es  "'. ($_SESSION['cs_nivel_administracao']<>1?'disabled':'') .' onClick="return '.$v_frase.'";>';
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