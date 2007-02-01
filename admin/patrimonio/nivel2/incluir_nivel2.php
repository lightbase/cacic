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
include_once "../../../include/library.php";
// Comentado temporariamente - AntiSpy();
if($_POST['gravainformacaoUON2']) {
	Conecta_bd_cacic();
	
	$frm_nm_unid_organizacional_nivel2 = $_POST['frm_nm_unid_organizacional_nivel2'];  
	if ($frm_nm_unid_organizacional_nivel2)
		{
		$query = "	INSERT 
					INTO unid_organizacional_nivel2 
				  	(nm_unid_organizacional_nivel2, 
				   	 te_endereco_uon2,
				   	 te_bairro_uon2,
				   	 te_cidade_uon2,
				   	 te_uf_uon2,
				   	 nm_responsavel_uon2,
				   	 te_email_responsavel_uon2,
				   	 nu_tel1_responsavel_uon2,
				   	 nu_tel2_responsavel_uon2,
					 id_unid_organizacional_nivel1,
					 id_local) 
				  	VALUES 
					('$frm_nm_unid_organizacional_nivel2', 
				     '$frm_te_endereco_uon2',
				     '$frm_te_bairro_uon2',
				     '$frm_te_cidade_uon2',
				     '$frm_te_uf_uon2',
				     '$frm_nm_responsavel_uon2',
				     '$frm_te_email_responsavel_uon2',
				     '$frm_nu_tel1_responsavel_uon2',
				     '$frm_nu_tel2_responsavel_uon2',
					 '$selectUON1',
				     '$frm_id_local')";
		$result = mysql_query($query) or die ('Insert falhou');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');		
		if (!atualiza_configuracoes_uonx('2'))
			{
			echo mensagem('Falha na atualização de configurações');
			}
		else
			{
			header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel2/index.php&tempo=1");											
			}
		}
	else
		{
		header ("Location: ../../../include/nenhuma_operacao_realizada.php?chamador=../admin/patrimonio/nivel2/index.php&tempo=1");										
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
		if (document.form.selectUON1.value == 0)
			{
			alert("Por favor, selecione "+ document.form.etiqueta1.value+".");
			document.form.selectUON1.focus();
			return false;
			} 
		if (document.form.frm_nm_unid_organizacional_nivel2.value == "")
			{
			alert("Por favor, preencha campo "+ document.form.etiqueta2.value+".");
			document.form.frm_nm_unid_organizacional_nivel2.focus();
			return false;
			} 
		return true;
	}
</script>
</head>
<?
$querySEL1 = 'SELECT 	uo1.id_unid_organizacional_nivel1,
						uo1.nm_unid_organizacional_nivel1
		  	  FROM 		unid_organizacional_nivel1 uo1
			  ORDER BY	uo1.nm_unid_organizacional_nivel1';

Conecta_bd_cacic();			  
$result_sel1 = mysql_query($querySEL1);			

?>
<body background="../../../imgs/linha_v.gif" onLoad="Javascript: SetaCampo('frm_id_local');">
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o de <? echo $_SESSION['etiqueta2'];?> (U. O. N&iacute;vel 
      2)</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<?
Conecta_bd_cacic();
$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE id_local = '.$_SESSION['id_local']:'');				
$qry_locais = "SELECT 	id_local,
								sg_local 
					 FROM 		locais ".
					 			$where." 
					 ORDER BY	sg_local";
					
$result_locais = mysql_query($qry_locais) or die ('Select falhou');
?>

<form method="post" ENCTYPE="multipart/form-data" name="form" onsubmit="return valida_form()">
  <table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td nowrap class="label">Local:</td>
      <td colspan="3"><select name="frm_id_local" id="frm_id_local" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <?
			echo '<option value="0">Selecione Local</option>';		  
		  	while($row = mysql_fetch_row($result_locais))
		  		{
				echo '<option value="'.$row[0].'"';
				echo ($_SESSION['cs_nivel_administracao']<>1?' selected ':'');
				echo '>'.$row[1].'</option>';
				}
				?>
        </select></td>
    <tr> 
      <td nowrap class="label"><? echo $_SESSION['etiqueta1']; ?>:</td>
      <td colspan="3"> <div align="left"> 
          <input name="etiqueta1" type="hidden" id="etiqueta1" value="<? echo $_SESSION['etiqueta1']; ?>">
          <input name="etiqueta2" type="hidden" id="etiqueta2" value="<? echo $_SESSION['etiqueta2']; ?>">
          <select name="selectUON1" id="selectUON1"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
            <option value="0" selected>Selecione <? echo $_SESSION['etiqueta1']; ?></option>
            <?
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
    <tr> 
      <td nowrap class="label"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $_SESSION['etiqueta2']; ?>:</font></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_nm_unid_organizacional_nivel2"  type="text" id="frm_nm_unid_organizacional_nivel2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Endere&ccedil;o:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_endereco_uon2" type="text"  id="frm_te_endereco_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Bairro:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_bairro_uon2" type="text"  id="frm_te_bairro_uon2" size="60" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label">Cidade:</td>
      <td><input name="frm_te_cidade_uon2" type="text"  id="frm_te_cidade_uon2" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
      </td>
      <td>&nbsp;</td>
      <td class="label"><div align="right">UF: 
          <input name="frm_te_uf_uon2" type="text"  id="frm_te_uf_uon2" size="2" maxlength="2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label">Respons&aacute;vel:</td>
      <td colspan="3"><div align="left">
          <input name="frm_nm_responsavel_uon2" type="text"  id="frm_nm_responsavel_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label">E-mail:</td>
      <td colspan="3"><div align="left"> 
          <input name="frm_te_email_responsavel_uon2" type="text"  id="frm_te_email_responsavel_uon2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label">Tel. 1:</td>
      <td><div align="left"> 
          <input name="frm_nu_tel1_responsavel_uon2" type="text"  id="frm_nu_tel1_responsavel_uon2" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
      <td nowrap class="label"><div align="right">Tel. 2:</div></td>
      <td><div align="right"> 
          <input name="frm_nu_tel2_responsavel_uon2" type="text"  id="frm_nu_telefone2" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td><div align="left"></div></td>
      <td>&nbsp;</td>
      <td><div align="right"></div></td>
    </tr>
  </table>
  <p align="center"> 
    
  <?
  $v_frase = "Confirma('Confirma Inclusão de ".$_SESSION['etiqueta2']."?')";
  echo '<input name="gravainformacaoUON2" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return '.$v_frase.'";>';
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
