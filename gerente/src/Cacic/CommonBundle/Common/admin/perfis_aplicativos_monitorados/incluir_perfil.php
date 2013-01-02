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

include_once "../../include/library.php";
AntiSpy('1'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração

/*
GravaTESTES('INCLUIR - HTTP_POST_VARS');
foreach($HTTP_POST_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('INCLUIR - HTTP_GET_VARS');
foreach($HTTP_GET_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);
*/

if($_POST['submit']) 
{
	conecta_bd_cacic();
	
	$frm_nm_aplicativo = $_POST['frm_nm_aplicativo'];  
	
	$query = "SELECT * FROM perfis_aplicativos_monitorados WHERE nm_aplicativo = '".$frm_nm_aplicativo."'";
	$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	
	if (mysql_num_rows($result) > 0) 
		{
		echo '<p>&nbsp;</p>
			  <p>&nbsp;</p>
			  <p>&nbsp;</p><body background="../../imgs/linha_v.gif">
			  <table border="0" align="center" cellpadding="20" cellspacing="1" bgcolor="#666666">
			    <tr> 
				  <td valign="top" bgcolor="#EEEEEE"><div align="center">
					<div align="center">
				    <font size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>'.$oTranslator->_('O aplicativo (%1) ja esta cadastrado',array($frm_nm_aplicativo)).'</strong></font>
				   </div>
				</td>
				</tr>
			  </table>';
		}
	else 
		{
//				  te_dir_pad_w9x,
//				  te_dir_pad_wnt,
//				  		  '$frm_te_dir_pad_w9x',
//						  '$frm_te_dir_pad_wnt', 

	
		$query = "INSERT INTO perfis_aplicativos_monitorados
				  (nm_aplicativo,
				  te_dir_padrao_w9x,
				  cs_car_inst_w9x,				  
				  te_car_inst_w9x,
				  te_dir_padrao_wnt,				  
				  cs_car_inst_wnt,
				  te_car_inst_wnt,				  
				  cs_car_ver_w9x,
				  te_car_ver_w9x,
				  cs_car_ver_wnt,
				  te_car_ver_wnt,				  				  
				  cs_ide_licenca,				  
				  te_ide_licenca,
				  te_arq_ver_eng_w9x,
				  te_arq_ver_pat_w9x,				  
				  te_arq_ver_eng_wnt,
				  te_arq_ver_pat_wnt,
				  id_so,
				  te_descritivo,
				  dt_atualizacao,
				  in_disponibiliza_info,
				  in_disponibiliza_info_usuario_comum)				  
				  VALUES ('".mysql_real_escape_string($_POST['frm_nm_aplicativo'])."',
				  		  '".mysql_real_escape_string($_POST['frm_te_dir_padrao_w9x'])."',
				  		  '".$_POST['frm_cs_car_inst_w9x']."',						  
				  		  '".mysql_real_escape_string($_POST['frm_te_car_inst_w9x'])."',
				  		  '".mysql_real_escape_string($_POST['frm_te_dir_padrao_wnt'])."',						  						
				  		  '".$_POST['frm_cs_car_inst_wnt']."',
				  		  '".mysql_real_escape_string($_POST['frm_te_car_inst_wnt'])."',						  						
				  		  '".$_POST['frm_cs_car_ver_w9x']."',
				  		  '".mysql_real_escape_string($_POST['frm_te_car_ver_w9x'])."',						  
				  		  '".$_POST['frm_cs_car_ver_wnt']."',
						  '".mysql_real_escape_string($_POST['frm_te_car_ver_wnt'])."',				  						  
				  		  '".$_POST['frm_cs_ide_licenca']."',
				  		  '".mysql_real_escape_string($_POST['frm_te_ide_licenca'])."',
						  '".mysql_real_escape_string($_POST['frm_te_arq_ver_eng_w9x'])."',
				  		  '".mysql_real_escape_string($_POST['frm_te_arq_ver_pat_w9x'])."',				  
				  		  '".mysql_real_escape_string($_POST['frm_te_arq_ver_eng_wnt'])."',
				  		  '".mysql_real_escape_string($_POST['frm_te_arq_ver_pat_wnt'])."',	  				  						
				  		  '".$_POST['frm_id_so']."',	  				  												  
				  		  '".mysql_real_escape_string($_POST['frm_te_descritivo'])."',	  				  												
						  '".now()."',
						  '".$_POST['frm_in_disponibiliza_info']."',
						  '".$_POST['frm_in_disponibiliza_info_usuario_comum']."')";
		$result = mysql_query($query) or die ($oTranslator->_('falha na insercao em (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
		$id_aplicativo = mysql_insert_id(); // Não tirar daqui...
				
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');		
				

		
		$strInsertAplicativosRedes = '';
		for ($i=0; $i < count($_POST['list2']);$i++)
			{
			$dado = explode('_',$_POST['list2'][$i]);
			if ($strInsertAplicativosRedes)
				$strInsertAplicativosRedes .= ',';
			$strInsertAplicativosRedes .= "(".$dado[0].",'".$dado[1]."',".$id_aplicativo.")";		
			}
			
		if ($strInsertAplicativosRedes)
			{
			$query = "INSERT 
					  INTO 		aplicativos_redes
					  VALUES 	".$strInsertAplicativosRedes;

			$result = mysql_query($query) or die ($oTranslator->_('falha na insercao em (%1) ou sua sessao expirou!',array('aplicativos_redes')));								  
			GravaLog('INS',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');				
			}
		
		header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 				
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
<?
require_once('../../include/selecao_listbox.js');  
?>

<SCRIPT LANGUAGE="JavaScript">
function SetaDescGrupo(p_descricao,p_destino) 
	{
	document.forms[0].elements[p_destino].value = p_descricao;		
	}

function SetaNomeSistema()
	{
	document.forma.frm_nm_aplicativo.value = document.forma.frm_id_so.options[document.forma.frm_id_so.options.selectedIndex].text;
	}

function valida_form() {
	
	if ( document.forma.frm_nm_aplicativo.value == "" ) 
	{	
		alert("<?=$oTranslator->_('O campo nome do aplicativo e obrigatorio.');?>");
		document.forma.frm_nm_aplicativo.focus();
		return false;
	}
}
function SetaAjuda(p_index, p_texto) 
	{
	// Melhorar isto depois...
	if (p_index == "Ajuda1")
		{
		document.forma.Ajuda1.value = p_texto;
		}
	else if (p_index == "Ajuda2")
		{
		document.forma.Ajuda2.value = p_texto;
		}
	else if (p_index == "Ajuda3")
		{
		document.forma.Ajuda3.value = p_texto;
		}
	else if (p_index == "Ajuda4")
		{
		document.forma.Ajuda4.value = p_texto;
		}
	else if (p_index == "Ajuda5")
		{
		document.forma.Ajuda5.value = p_texto;
		}
		
	return true;
	}

</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_aplicativo')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td nowrap class="cabecalho">Inclus&atilde;o 
      de novo perfil de sistema monitorado</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es 
      que dever&atilde;o ser cadastradas abaixo referem-se &agrave;s caracter&iacute;sticas 
      de instala&ccedil;&atilde;o do sistema a ser monitorado pelos agentes CACIC. 
      &Eacute; necess&aacute;rio o cuidado especial quanto ao uso de letras mai&uacute;sculas 
      e min&uacute;sculas.</td>
  </tr>
</table>
<form method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
  <br>
  <table width="90%" border="0" align="center">
    <tr> 
      <td nowrap class="label">Nome do sistema:<br> <input name="frm_nm_aplicativo" type="text" id="frm_nm_aplicativo2" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td nowrap> <div align="left"> </div></td>
    </tr>
    <tr> 
      <td nowrap class="label">&Eacute; um Sistema Operacional? &nbsp;Qual?<br> 
        <select name="frm_id_so" id="select3" onChange="SetaNomeSistema();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0"></option>
          <?
			Conecta_bd_cacic();
			$query = "SELECT id_so,te_desc_so
			          FROM   so
					  WHERE  id_so <> '0'
					  ORDER  BY te_desc_so";
			mysql_query($query) or die('4-Select falhou ou sua sessão expirou!');
		    $sql_result=mysql_query($query);			
		while ($row=mysql_fetch_array($sql_result))
			{ 
			echo "<option value=\"" . $row["id_so"] . "\">" . $row["te_desc_so"] . "</option>";
		   	} 			
			?>
        </select></td>
    </tr>
    <tr> 
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es no Systray? 
        (&iacute;cone na bandeja da esta&ccedil;&atilde;o):<br> <select name="frm_in_disponibiliza_info" id="select4" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="N">Não</option>
          <option value="S">Sim</option>
        </select> </td>
    </tr>
    <tr> 
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es ao Usu&aacute;rio 
        Comum? (diferente de Administrador):<br> <select name="frm_in_disponibiliza_info_usuario_comum" id="select5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="N">Não</option>
          <option value="S">Sim</option>
        </select></td>
    </tr>
    <tr> 
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Descri&ccedil;&atilde;o:<br> <textarea name="frm_te_descritivo" cols="50" rows="3" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></textarea> 
      </td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Identificador de licen&ccedil;a:<br> <select name="frm_cs_ide_licenca" id="select6" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda1')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0" id=""></option>
          <option value="1" id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
          em Registry</option>
          <option value="2" id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
          de Arquivo INI</option>
        </select> <br> <input name="frm_te_ide_licenca" type="text" id="frm_te_ide_licenca2" size="80" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        <br> <input name="Ajuda1" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
        <br> <input name="Ajuda11" type="text" style="border:0" size="80"> </td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes Windows 9x/Me</u></td>
    </tr>
    <tr> 
      <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:<br> <select name="frm_cs_car_inst_w9x" id="select7" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda2')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0" id=""></option>
          <option value="1" id="Ex.:  Arquivos de Programas\Cacic\Programas\cacic.exe">Nome 
          de Execut&aacute;vel</option>
          <option value="2" id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
          de Arquivo de Configura&ccedil;&atilde;o</option>
          <option value="3" id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
          em Registry</option>
        </select> <br> <input name="frm_te_car_inst_w9x" type="text" id="frm_te_car_inst_w9x" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        <br> <input name="Ajuda2" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
        <br> <input name="Ajuda22" type="text" style="border:0" size="80"> </td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:<br> 
        <select name="frm_cs_car_ver_w9x" id="select8" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda3')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0" id=""></option>
          <option value="1" id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
          de Arquivo</option>
          <option value="2" id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
          em Registry</option>
          <option value="3" id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
          de Arquivo INI</option>
          <option value="4" id="Ex.:  C:\Cacic\modulos\col_moni.exe">Vers&atilde;o 
          de Executável</option>
        </select> <br> <input name="frm_te_car_ver_w9x" type="text" id="frm_te_car_ver_w9x" size="80" maxlength="150"> 
        <br> <input name="Ajuda3" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
        <br> <input name="Ajuda33" type="text" style="border:0" size="80"> </td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes 
        Windows NT/2000/XP/2003</u></td>
    </tr>
    <tr> 
      <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:<br> <select name="frm_cs_car_inst_wnt" id="select9" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda4')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0" id=""></option>
          <option value="1" id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Nome 
          de Execut&aacute;vel</option>
          <option value="2" id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
          de Arquivo de Configura&ccedil;&atilde;o</option>
          <option value="3" id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
          em Registry</option>
        </select> <br> <input name="frm_te_car_inst_wnt" type="text" id="frm_te_car_inst_wnt" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        <br> <input name="Ajuda4" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
        <br> <input name="Ajuda44" type="text" style="border:0" size="80"> </td>
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:<br> 
        <select name="frm_cs_car_ver_wnt" id="select10" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda5')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="0" id=""></option>
          <option value="1" id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
          de Arquivo</option>
          <option value="2" id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
          em Registry</option>
          <option value="3" id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
          de Arquivo INI</option>
          <option value="4" id="Ex.:  C:\Cacic\modulos\col_moni.exe">Vers&atilde;o 
          de Executável</option>
        </select> <br> <input name="frm_te_car_ver_wnt" type="text" id="frm_te_car_ver_wnt" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        <br> <input name="Ajuda5" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
        <br> <input name="Ajuda55" type="text" style="border:0" size="80"> </td>		
    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
	
    <tr> 
      <td nowrap class="cabecalho_secao"><u>Sele&ccedil;&atilde;o de redes para  aplica&ccedil;&atilde;o desta coleta de informa&ccedil;&otilde;es </u></td>
    </tr>
	
	<tr>
	<td>
	<?
	include_once "../../include/selecao_redes_perfil_inc.php";	
	?>
	</td>	
	</tr>
  </table>
<?

	/*
    <table width="90%" border="0" align="center" cellspacing="1">
      <tr>
        <td colspan="3" nowrap>&nbsp;</td>
      </tr>
      <tr> 
        <td colspan="3" nowrap><div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#333333"><u>Caracter&iacute;sticas 
            de sistemas Anti-V&iacute;rus em Windows 9x/Me</u></font></strong></font> 
            <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><br>
            <br>
            </strong></font></div></td>
      </tr>
    </table>
  </div>
  <table width="90%" border="0" align="center" cellspacing="1">
    <tr> 
      <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <strong><font color="#333333">Arquivo p/Vers&atilde;o de Engine:</font></strong></font></td>
    </tr>
	<tr> 
      <td height="1" bgcolor="#FFFFFF"></td>
    </tr>
    <tr> 
      <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          </font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="frm_te_arq_ver_eng_w9x" type="text" id="frm_te_arq_ver_eng_w9x" size="101" maxlength="100">
  		<br>
           <input name="Ajuda6" type="text" style="border:0;font-size:9;color:#000099" size="90" value="Ex.:  Trend\engine32.vxd">			
		  
          </font></div></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellspacing="1">
    <tr> 
      <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <strong><font color="#333333">Arquivo p/Vers&atilde;o de Pattern:</font></strong></font></td>
    </tr>
	<tr> 
      <td height="1" bgcolor="#FFFFFF"></td>
    </tr>
    <tr> 
      <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          </font><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="frm_te_arq_ver_pat_w9x" type="text" id="frm_te_arq_ver_pat_w9x" size="101" maxlength="150">
  		<br>
           <input name="Ajuda7" type="text" style="border:0;font-size:9;color:#000099" size="90" value="Ex.:  Trend\pattern32.vxd">			
		  
          </font></div></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellspacing="1">
    <tr>
      <td colspan="3" nowrap>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="3" nowrap><div align="center"><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong><font color="#333333"><u>Caracter&iacute;sticas 
          de sistemas Anti-V&iacute;rus em Windows NT/2000/XP</u></font></strong></font> 
          <font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><br>
          <br>
          </strong></font></div></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellspacing="1">
    <tr> 
      <td nowrap>
        <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <strong><font color="#333333">Arquivo p/Vers&atilde;o de Engine:</font></strong></font></div>
      </td>
    </tr>
	<tr> 
      <td height="1" bgcolor="#FFFFFF"></td>
    </tr>
    <tr> 
      <td nowrap><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
        <input name="frm_te_arq_ver_eng_wnt" type="text" id="frm_te_arq_ver_eng_wnt" size="101" maxlength="100">
  		<br>
           <input name="Ajuda7" type="text" style="border:0;font-size:9;color:#000099" size="90" value="Ex.:  Trend\engine-nt.vxd">			
		
        </font></td>
    </tr>
  </table>
  <table width="90%" border="0" align="center" cellspacing="1">
    <tr> 
      <td nowrap>
        <div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <strong><font color="#333333">Arquivo p/Vers&atilde;o de Pattern:</font></strong></font></div>
      </td>
    </tr>
    <tr> 
      <td nowrap><div align="left"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
          <input name="frm_te_arq_ver_pat_wnt" type="text" id="frm_te_arq_ver_pat_wnt" size="101" maxlength="150">
  		<br>
           <input name="Ajuda8" type="text" style="border:0;font-size:9;color:#000099" size="90" value="Ex.:  Trend\pattern-nt.vxd">			
		  
          </font></div></td>
    </tr>
  </table>
  */
  ?>
  <br>
  <p align="center">   
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  " onClick="return Confirma('Confirma Inclusão de Perfil de Sistema Monitorado?'),SelectAll(this.form.elements['list2[]']) ">
  </p>
  </form>
<p>
<?
}
?>
</p>
</body>
</html>
