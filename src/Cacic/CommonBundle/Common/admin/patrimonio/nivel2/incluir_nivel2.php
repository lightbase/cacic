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
AntiSpy();
if($_POST['gravainformacaoUON2']) 
	{
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
					 id_unid_organizacional_nivel1a,
					 id_local) 
				  	VALUES ('".
					 $_POST['frm_nm_unid_organizacional_nivel2']."','". 
				     $_POST['frm_te_endereco_uon2']."','".
				     $_POST['frm_te_bairro_uon2']."','".
				     $_POST['frm_te_cidade_uon2']."','".
				     $_POST['frm_te_uf_uon2']."','".
				     $_POST['frm_nm_responsavel_uon2']."','".
				     $_POST['frm_te_email_responsavel_uon2']."','".
				     $_POST['frm_nu_tel1_responsavel_uon2']."','".
				     $_POST['frm_nu_tel2_responsavel_uon2']."',".
					 $_POST['frm_id_unid_organizacional_nivel1a'].",".
				     $_POST['frm_id_local'].")";
		$result = mysql_query($query) or die ($oTranslator->_('falha na insercao em (%1) ou sua sessao expirou!',array('unid_organizacional_nivel2')));
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');		
		if (!atualiza_configuracoes_uonx('2'))
			{
			echo mensagem($oTranslator->_('Falha na atualizacao de configuracoes'));
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
function ListarUON1a(ObjLocal)
	{
	var frm_id_unid_organizacional_nivel1a =window.document.forms[0].frm_id_unid_organizacional_nivel1a;	
	var contaUON1a = 0;

	frm_id_unid_organizacional_nivel1a.options.length = 0;
	for (j=0;j<document.all.listaUON1a.options.length;j++)
		{
		if (document.all.listaUON1a.options[j].id == ObjLocal.options[ObjLocal.options.selectedIndex].value)
			{
			frm_id_unid_organizacional_nivel1a.options[contaUON1a]       = new Option(document.all.listaUON1a.options[j].text);
			frm_id_unid_organizacional_nivel1a.options[contaUON1a].id    = 		   document.all.listaUON1a.options[j].id;
			frm_id_unid_organizacional_nivel1a.options[contaUON1a].value = 		   document.all.listaUON1a.options[j].value;
			contaUON1a ++;			
			}		
		}
	if (frm_id_unid_organizacional_nivel1a.options.length > 0)
		document.getElementById("frm_id_unid_organizacional_nivel1a").disabled = false;
		
	return true;

	}

function valida_form() 
	{
		if (document.form.frm_id_unid_organizacional_nivel1a.value == 0)
			{
			alert("<?=$oTranslator->_('Por favor, selecione');?> "+ document.form.etiqueta1a.value+".");
			document.form.frm_id_unid_organizacional_nivel1a.focus();
			return false;
			} 
		if (document.form.frm_nm_unid_organizacional_nivel2.value == "")
			{
			alert("<?=$oTranslator->_('Por favor, preencha campo');?> "+ document.form.etiqueta2.value+".");
			document.form.frm_nm_unid_organizacional_nivel2.focus();
			return false;
			} 
		return true;
	}
</script>
</head>
<?
$querySEL1 = 'SELECT 	uo1a.id_unid_organizacional_nivel1a,
						uo1a.nm_unid_organizacional_nivel1a
		  	  FROM 		unid_organizacional_nivel1 uo1a
			  ORDER BY	uo1a.nm_unid_organizacional_nivel1a';

Conecta_bd_cacic();			  
$result_sel1 = mysql_query($querySEL1);			

?>
<body background="../../../imgs/linha_v.gif" onLoad="Javascript: SetaCampo('frm_id_local');">
<div id="LayerDados" style="position:absolute; width:200px; height:115px; z-index:1; left: 100px; top: 0px; visibility: hidden">
<?
$queryUON1a = "SELECT		UON1a.id_unid_organizacional_nivel1,
							UON1a.id_unid_organizacional_nivel1a,
							UON1a.nm_unid_organizacional_nivel1a
			   FROM 		unid_organizacional_nivel1a UON1a
			   ORDER BY		UON1a.nm_unid_organizacional_nivel1a";
$resultUON1a = mysql_query($queryUON1a) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('unid_organizacional_nivel1a')));

$intIdUON1a  = 0;

while ($rowUON1a = mysql_fetch_array($resultUON1a)) 
	$strUON1a .= '<option id="'.$rowUON1a['id_unid_organizacional_nivel1'].'" value="'.$rowUON1a['id_unid_organizacional_nivel1a'].'">'.$rowUON1a['nm_unid_organizacional_nivel1a'].'</option>';			

$arrUON1a = explode('#',$strUON1a);	

echo '<select id="listaUON1a" name="listaUON1a">';
for ($i=0; $i < count($arrUON1a);$i++)
	{
	echo $arrUON1a[$i];
	}
echo '</select>';		
	
?>
</div>
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
      <?=$oTranslator->_('Inclusao de');?> <? echo $_SESSION['etiqueta2'];?> 
      (<?=$oTranslator->_('Unidade Organizacional Nivel 2');?>)
    </td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
</table>
<?
Conecta_bd_cacic();
				
$qry_locais = "SELECT 	id_local,
						sg_local 
			   FROM 	locais
			   ORDER BY	sg_local";
					
$result_locais = mysql_query($qry_locais) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('locais')));

$qry_UON1 = "SELECT 	id_unid_organizacional_nivel1,
			 			nm_unid_organizacional_nivel1
			 FROM 		unid_organizacional_nivel1
			 ORDER BY	nm_unid_organizacional_nivel1";
					
$result_UON1 = mysql_query($qry_UON1) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('unid_organizacional_nivel1')));

?>

<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
  <table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
    <tr>
      <td nowrap class="label"><?=$oTranslator->_('Local');?></td>
      <td colspan="3"><select name="frm_id_local" id="frm_id_local" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          <?
			echo '<option value="0" ';
			echo ($_SESSION['cs_nivel_administracao']<>1?' selected ':'');			
			echo '>'.$oTranslator->_('Selecione').'</option>';		  
		  	while($row = mysql_fetch_row($result_locais))
				echo '<option value="'.$row[0].'">'.$row[1].'</option>';
				?>
        </select></td></tr>  
    <tr> 
      <td nowrap class="label"><? echo $_SESSION['etiqueta1']; ?>:</td>
      <td colspan="3"> <div align="left"> 
          <select name="frm_id_unid_organizacional_nivel1" id="frm_id_unid_organizacional_nivel1"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="ListarUON1a(this)">
            <option value="0" selected><?=$oTranslator->_('Selecione');?> <? echo $_SESSION['etiqueta1']; ?></option>
            <?
if(mysql_num_rows($result_UON1))
	{	              
	while($row = mysql_fetch_array($result_UON1))
		{
		echo "<option value='". $row['id_unid_organizacional_nivel1'] . "'>".$row['nm_unid_organizacional_nivel1'].'</option>';
		} 		
	}
	?>
          </select>
        </div></td>
		</tr>  

    <tr> 
      <td nowrap class="label"><? echo $_SESSION['etiqueta1a']; ?>:</td>
      <td colspan="3"> <div align="left"> 
          <select name="frm_id_unid_organizacional_nivel1a" id="frm_id_unid_organizacional_nivel1a"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" disabled="disabled">
            <option value="0" selected><?=$oTranslator->_('Selecione');?> <? echo $_SESSION['etiqueta1a']; ?></option>
            <?
if(mysql_num_rows($result_sel1))
	{	              
	while($row = mysql_fetch_array($result_sel1))
		{
		echo "<option value='". $row['id_unid_organizacional_nivel1a'] . "'>".$row['nm_unid_organizacional_nivel1a'].'</option>';
		} 		
	}
	?>
          </select>
        </div></td>
	</tr>
    <tr> 
      <td nowrap class="label"><? echo $_SESSION['etiqueta2']; ?>:</td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_nm_unid_organizacional_nivel2"  type="text" id="frm_nm_unid_organizacional_nivel2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><?=$oTranslator->_('Endereco');?>:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_endereco_uon2" type="text"  id="frm_te_endereco_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><div align="left"><?=$oTranslator->_('Bairro');?>:</div></td>
      <td colspan="3"> <div align="left"> 
          <input name="frm_te_bairro_uon2" type="text"  id="frm_te_bairro_uon2" size="60" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Cidade');?>:</td>
      <td><input name="frm_te_cidade_uon2" type="text"  id="frm_te_cidade_uon2" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
      </td>
      <td>&nbsp;</td>
      <td class="label"><div align="right"><?=$oTranslator->_('Unidade da Federacao',T_SIGLA);?>: 
          <input name="frm_te_uf_uon2" type="text"  id="frm_te_uf_uon2" size="2" maxlength="2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Responsavel');?>:</td>
      <td colspan="3"><div align="left">
          <input name="frm_nm_responsavel_uon2" type="text"  id="frm_nm_responsavel_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Endereco eletronico');?>:</td>
      <td colspan="3"><div align="left"> 
          <input name="frm_te_email_responsavel_uon2" type="text"  id="frm_te_email_responsavel_uon2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
    </tr>
    <tr> 
      <td class="label"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Um',T_SIGLA);?>:</td>
      <td><div align="left"> 
          <input name="frm_nu_tel1_responsavel_uon2" type="text"  id="frm_nu_tel1_responsavel_uon2" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
        </div></td>
      <td nowrap class="label"><div align="right"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Dois',T_SIGLA);?>:</div></td>
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
  $v_frase = "Confirma('".$oTranslator->_('Confirma Informacoes para')." ".$_SESSION['etiqueta2']."?')";
  echo '<input name="gravainformacaoUON2" type="submit" value="'.$oTranslator->_('Gravar Informacoes').'" onClick="return '.$v_frase.'";>';
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
