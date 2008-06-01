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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include_once "../../../include/library.php";
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if ($_POST['exclui_uon2']) 
	{
	$where = ($_SESSION['cs_nivel_administracao']<>1?' AND id_local = '.$_SESSION['id_local']:'');
	if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta	
		$where = str_replace(' id_local = ',' (id_local = ',$where);
		$where .= ' OR id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
		}
	
	$query = "	DELETE 
				FROM 	unid_organizacional_nivel2 
				WHERE 	id_unid_organizacional_nivel2 = ".$_POST['frm_id_unid_organizacional_nivel2']." and
						id_unid_organizacional_nivel1 = ".$_POST['frm_id_unid_organizacional_nivel1']. $where;
	mysql_query($query) or die('1-Delete falhou ou sua sessão expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');			
	if (!atualiza_configuracoes_uonx('2'))
		{
		echo mensagem('Falha na atualização de configurações');
		}
	else
		{
		header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel2/index.php&tempo=1");								
		}
	
	}
elseif ($_POST['grava_alteracao_uon2']) 
	{
	$rowSEL = explode('#',$result_sel);

	if ($rowSEL[2]	<>	$selectUON1 						||
		$rowSEL[5]	<>	$frm_nm_unid_organizacional_nivel2 	||
		$rowSEL[7]	<>	$frm_te_endereco_uon2				||
		$rowSEL[9]	<>	$frm_te_bairro_uon2					||
		$rowSEL[11]	<>	$frm_te_cidade_uon2					||
		$rowSEL[13]	<>	$frm_te_uf_uon2						||
		$rowSEL[15]	<>	$frm_nm_responsavel_uon2			||
		$rowSEL[17]	<>	$frm_te_email_responsavel_uon2		||
		$rowSEL[19]	<>	$frm_nu_tel1_responsavel_uon2		||
		$rowSEL[21]	<>	$frm_nu_tel2_responsavel_uon2){

		$where = ($_SESSION['cs_nivel_administracao']<>1?' AND id_local='.$_SESSION['id_local']:'');				
		$query = "	UPDATE  unid_organizacional_nivel2 
					SET		id_unid_organizacional_nivel1 	= '$selectUON1',				
							nm_unid_organizacional_nivel2 	= '$frm_nm_unid_organizacional_nivel2',
				   		  	te_endereco_uon2 				= '$frm_te_endereco_uon2',
				   		  	te_bairro_uon2 					= '$frm_te_bairro_uon2',
				   		  	te_cidade_uon2 					= '$frm_te_cidade_uon2',
				   		  	te_uf_uon2 						= '$frm_te_uf_uon2',
				   		  	nm_responsavel_uon2 			= '$frm_nm_responsavel_uon2',
				   		  	te_email_responsavel_uon2 		= '$frm_te_email_responsavel_uon2',
				   		  	nu_tel1_responsavel_uon2 		= '$frm_nu_tel1_responsavel_uon2',
				   		  	nu_tel2_responsavel_uon2 		= '$frm_nu_tel2_responsavel_uon2',
							id_local					= '$frm_id_local' 
					WHERE 	id_unid_organizacional_nivel2 	= $frm_id_unid_organizacional_nivel2 and
							id_unid_organizacional_nivel1   = $frm_id_unid_organizacional_nivel1 ".
							$where;
			mysql_query($query) or die('2-Update falhou ou sua sessão expirou!');
			GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');		
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
}
else 
{
	$query = "	SELECT 	* 
				FROM 	unid_organizacional_nivel2 uo2 
				WHERE 	uo2.id_unid_organizacional_nivel2 = $id_unid_organizacional_nivel2 and
						uo2.id_unid_organizacional_nivel1 = $id_unid_organizacional_nivel1";

	$result 		= mysql_query($query) or die ('3-Select Falhou ou sua sessão expirou!');
	$fetch_result_sel = mysql_fetch_array($result);
	$result_sel		= implode('#',$fetch_result_sel);
	
	$querySEL1 = 'SELECT 	uo1.id_unid_organizacional_nivel1,
							uo1.nm_unid_organizacional_nivel1
			  	  FROM 		unid_organizacional_nivel1 uo1
				  ORDER BY	uo1.nm_unid_organizacional_nivel1';

	$result_sel1 = mysql_query($querySEL1);			
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">
<body background="../../../imgs/linha_v.gif"  onLoad="SetaCampo('frm_id_local');">
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
function ConfirmaExclusao() 
{
	if (confirm ("Confirma exclusão de "+ document.form.etiqueta2.value+"?")) 
		{
		return true;
		} 
	return false;
}
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

<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Detalhes de <? echo $_SESSION['etiqueta2'];?> (U. O. N&iacute;vel 
      2)</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>

<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
  <table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
          <tr> 
            <td class="label">Local:</td>
            <td><select name="frm_id_local" id="frm_id_local"" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <? 
			$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' WHERE id_local = '.$_SESSION['id_local']:'');				
			if ($_SESSION['te_locais_secundarios']<>'' && $where <>'')
				{
				// Faço uma inserção de "(" para ajuste da lógica para consulta	
				$where = str_replace(' id_local = ',' (id_local = ',$where);
				$where .= ' OR id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
				}
			
			$qry_locais = "SELECT 	id_local,
											sg_local 
								 FROM 		locais ".
								 			$where ."
								 ORDER BY	sg_local";

		    $result_locais = mysql_query($qry_locais) or die ('4-Select falhou ou sua sessão expirou!');
			while ($row_qry=mysql_fetch_array($result_locais))
		  		{
				echo '<option value="'.$row_qry[0].'"';
				if ($row_qry['id_local'] == $_GET["id_local"]) 
					{
				  	$v_sg_local = $row_qry[1]; 					
					echo 'selected';
					}
					?> id='
                <? 
				echo $row_qry[1];?>'><? echo $row_qry[1];?> 
                <?
				}
						
			?>
              </select></td>
          </tr>
  
  <tr> 
      <td width="20%" nowrap class="label"><? echo $_SESSION['etiqueta1']; ?>:</td>
	  <td colspan="3"><select name="selectUON1"  class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
<option value="0">Selecione <? echo $_SESSION['etiqueta1']; ?></option>
<?
if(mysql_num_rows($result_sel1))
	{	              
	while($row = mysql_fetch_array($result_sel1)){
		echo "<option value='". $row['id_unid_organizacional_nivel1'] . "'";
		if ($row['id_unid_organizacional_nivel1'] == $id_unid_organizacional_nivel1)
			{
			echo ' selected';
			}
		echo ">".$row['nm_unid_organizacional_nivel1'].'</option>';
		} 		
	}
	?>
</select>
<tr> 
            <td class="label"><div align="left"> 
              <? echo $_SESSION['etiqueta2'];?>:</td>
            <td colspan="3"> <div align="left"> 
                <input name="frm_id_unid_organizacional_nivel1" type="hidden" id="id_unid_organizacional_nivel1" value="<? echo mysql_result($result, 0, 'id_unid_organizacional_nivel1'); ?>">			
                <input name="etiqueta1" type="hidden" id="etiqueta1" value="<? echo $_SESSION['etiqueta1']; ?>">							
                <input name="etiqueta2" type="hidden" id="etiqueta2" value="<? echo $_SESSION['etiqueta2']; ?>">											
                <input name="frm_id_unid_organizacional_nivel2" type="hidden" id="id_unid_organizacional_nivel2" value="<? echo mysql_result($result, 0, 'id_unid_organizacional_nivel2'); ?>">							
                <input name="result_sel" type="hidden" id="result_sel" value="<? echo $result_sel; ?>">							
                <input name="frm_nm_unid_organizacional_nivel2" type="text"   id="frm_nm_unid_organizacional_nivel2" size="60" maxlength="50" value="<? echo mysql_result($result, 0, 'nm_unid_organizacional_nivel2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
				
              </div></td>
          </tr>
          <tr> 
            <td class="label"><div align="left">Endere&ccedil;o:</div></td>
            <td colspan="3"> <div align="left"> 
                <input name="frm_te_endereco_uon2" type="text" id="frm_te_endereco_uon2" size="60" maxlength="80" value="<? echo mysql_result($result, 0, 'te_endereco_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </div></td>
          </tr>
          <tr> 
            <td class="label"><div align="left">Bairro:</div></td>
            <td colspan="3"> <div align="left"> 
                <input name="frm_te_bairro_uon2" type="text" id="frm_te_bairro_uon2" size="60" maxlength="30" value="<? echo mysql_result($result, 0, 'te_bairro_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </div></td>
          </tr>
          <tr> 
            <td class="label">Cidade:</td>
            <td><input name="frm_te_cidade_uon2" type="text" id="frm_te_cidade_uon2" size="20" maxlength="50" value="<? echo mysql_result($result, 0, 'te_cidade_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </td>
            <td>&nbsp;</td>
            <td class="label"><div align="right">UF: 
                <input name="frm_te_uf_uon2" type="text" id="frm_te_uf_uon2" size="2" maxlength="2" value="<? echo mysql_result($result, 0, 'te_uf_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </div></td>
          </tr>
          <tr> 
            <td class="label">Respons&aacute;vel:</td>
            <td colspan="3"><div align="left">
                <input name="frm_nm_responsavel_uon2" type="text" id="frm_nm_responsavel_uon2" size="60" maxlength="80" value="<? echo mysql_result($result, 0, 'nm_responsavel_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </div></td>
          </tr>
          <tr> 
            <td class="label">E-mail:</td>
            <td colspan="3"><div align="left"> 
                <input name="frm_te_email_responsavel_uon2" type="text" id="frm_te_email_responsavel_uon2" size="60" maxlength="50" value="<? echo mysql_result($result, 0, 'te_email_responsavel_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </div></td>
          </tr>
          <tr> 
            <td class="label">Tel. 1:</td>
            <td><div align="left"> 
                <input name="frm_nu_tel1_responsavel_uon2" type="text" id="frm_nu_tel1_responsavel_uon2" size="20" maxlength="10" value="<? echo mysql_result($result, 0, 'nu_tel1_responsavel_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                </div></td>
            <td nowrap class="label"><div align="right">Tel. 2:</div></td>
            <td><div align="right"> 
                <input name="frm_nu_tel2_responsavel_uon2" type="text" id="frm_nu_telefone2" size="20" maxlength="10" value="<? echo mysql_result($result, 0, 'nu_tel2_responsavel_uon2'); ?>" class="normal"  onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
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
	$v_frase = "Confirma('Confirma Informações para ".$_SESSION['etiqueta2']."?')";
    echo '<input name="grava_alteracao_uon2" type="submit" id="grava_alteracao_uon2" value="  Gravar Altera&ccedil;&otilde;es" onClick="return '.$v_frase.'"; '.($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'').'>';
	?>
&nbsp;&nbsp;		
    <input name="exclui_uon2" type="submit" onClick="return ConfirmaExclusao()" id="exclui_uon2" value="  Excluir <? echo $_SESSION['etiqueta2'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 &&  $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>		  
  </p>
</form>
</td>
  </tr>
</body>
</html>
<?
}
?>
