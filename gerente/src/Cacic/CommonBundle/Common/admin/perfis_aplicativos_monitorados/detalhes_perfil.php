<?php
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

require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central

/*
GravaTESTES('SESSION[list1]: '.$_SESSION['list1[]']);
GravaTESTES('SESSION[list2]: '.$_SESSION['list2[]']);

GravaTESTES('DETALHES - HTTP_POST_VARS');
foreach($HTTP_POST_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);

GravaTESTES('DETALHES - HTTP_GET_VARS');
foreach($HTTP_GET_VARS as $i => $v) 
	GravaTESTES('I: '.$i.' V: '.$v);
*/

Conecta_bd_cacic();

if ($_POST['ExcluiAplicativo'] && ($_SESSION['cs_nivel_administracao']==1))
	{
	$query = "DELETE 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados',$_SESSION["id_usuario"]);			
	
	$query = "DELETE 
			  FROM 		aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_monitorados')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_monitorados',$_SESSION["id_usuario"]);			

	$query = "DELETE
			  FROM		aplicativos_redes
			  WHERE		id_aplicativo = ".$_POST['id_aplicativo'];
	$result = mysql_query($query) or die ($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));				
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes',$_SESSION["id_usuario"]);			
		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
	}
elseif ($_POST['GravaAlteracoes'] && ($_SESSION['cs_nivel_administracao']==1))
	{		
	$strWhereRedes = '';
	if ($_POST['frmRedes_NaoSelecionadas'])
		$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_NaoSelecionadas'].') ' . ($_POST['frmRedes_Selecionadas'] ? ' OR ' : '');

	if ($_POST['frmRedes_Selecionadas'])
		$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_Selecionadas'].') ';
	
	$query = "DELETE
			  FROM		aplicativos_redes
			  WHERE		id_aplicativo = ".$_POST['id_aplicativo']." AND " . $strWhereRedes;
	$result = mysql_query($query) or die ($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));											
			
	$arrRedes_Selecionadas    = explode(',',$_POST['frmRedes_Selecionadas']);
	$arrRedes_NaoSelecionadas = explode(',',$_POST['frmRedes_NaoSelecionadas']);	
	$strValues = '';
	for ($i = 0; $i < count($arrRedes_Selecionadas); $i ++)
		{
		$strValues .= ($strValues ? ',' : '');		
		$strValues .= '(' . $arrRedes_Selecionadas[$i] . ',' . $_POST['id_aplicativo'] . ')';
		}
	if ($strValues)
		{
		$query = "INSERT 
				  INTO 		aplicativos_redes(id_rede,id_aplicativo)
				  VALUES 	".$strValues;
		$result = mysql_query($query) or die ($oTranslator->_('Falha em inclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));								  
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'aplicativos_redes',$_SESSION["id_usuario"]);				
		}

	$v_nm_aplicativo = $_POST['frm_nm_aplicativo'];
	if ($_POST['frm_in_ativa'] == 'N')
		$v_nm_aplicativo .= '#DESATIVADO#';
			
	$query = "UPDATE 	perfis_aplicativos_monitorados 
			  SET 		nm_aplicativo 						= '" . $v_nm_aplicativo									."',  
			  			te_dir_padrao_w9x 					= '" . $_POST['frm_te_dir_padrao_w9x']					."',  
						te_dir_padrao_wnt 					= '" . $_POST['frm_te_dir_padrao_wnt']					."',  
		  				cs_car_inst_w9x 					= '" . $_POST['frm_cs_car_inst_w9x']					."',   
						cs_car_inst_wnt 					= '" . $_POST['frm_cs_car_inst_wnt']					."',  
		  				te_car_inst_w9x 					= '" . $_POST['frm_te_car_inst_w9x']					."',  
						te_car_inst_wnt 					= '" . $_POST['frm_te_car_inst_wnt']					."',  
			  			cs_car_ver_w9x 						= '" . $_POST['frm_cs_car_ver_w9x']						."',  
						cs_car_ver_wnt 						= '" . $_POST['frm_cs_car_ver_wnt']						."',  
			  			te_car_ver_w9x 						= '" . $_POST['frm_te_car_ver_w9x']						."',  
						te_car_ver_wnt 						= '" . $_POST['frm_te_car_ver_wnt']						."',  
		  				te_arq_ver_eng_w9x 					= '" . $_POST['frm_te_arq_ver_eng_w9x']					."',  
						te_arq_ver_pat_w9x 					= '" . $_POST['frm_te_arq_ver_pat_w9x']					."',  	  
			  			te_arq_ver_eng_wnt 					= '" . $_POST['frm_te_arq_ver_eng_wnt']					."',  
						te_arq_ver_pat_wnt 					= '" . $_POST['frm_te_arq_ver_pat_wnt']					."',  	  			  
			  			cs_ide_licenca 						= '" . $_POST['frm_cs_ide_licenca']						."',  
						te_ide_licenca 						= '" . $_POST['frm_te_ide_licenca']						."',  	  
		  				id_so 								= '" . $_POST['frm_id_so']								."',  
						te_descritivo 						= '" . $_POST['frm_te_descritivo']						."',  
		  				dt_atualizacao 						= now(),
			  			in_disponibiliza_info 				= '" . $_POST['frm_in_disponibiliza_info']				. "',  
			  			in_disponibiliza_info_usuario_comum = '" . $_POST['frm_in_disponibiliza_info_usuario_comum']. "' 
			  WHERE 	id_aplicativo 						=  " . $_POST['id_aplicativo'];
	mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados',$_SESSION["id_usuario"]);		

	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 			
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_GET['id_aplicativo'];
	$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	$row = mysql_fetch_array($result);
	
	$querySelecaoRedes = "SELECT 	re.id_rede
					  	  FROM 		aplicativos_redes ar,
					  				redes re
					  	  WHERE		id_aplicativo = ".$_GET['id_aplicativo']." AND 
						  			ar.id_rede = re.id_rede";
	if ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 2)			 
		$querySelecaoRedes .= " AND re.id_local = ".$_SESSION['id_local'];

	if ($_SESSION['te_locais_secundarios']<>'')
		{
		// Faço uma inserção de "(" para ajuste da lógica para consulta
		$querySelecaoRedes = str_replace('re.id_local = ','(re.id_local = ',$querySelecaoRedes);
		$querySelecaoRedes .= ' OR re.id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
		}

	$resultSelecaoRedes= mysql_query($querySelecaoRedes) or die('1-'.$oTranslator->_('kciq_msg select on table fail', array('aplicativos_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
	$arrSelecaoRedes = array();	
	while ($rowSelecaoRedes = mysql_fetch_array($resultSelecaoRedes))
		$arrSelecaoRedes[$rowSelecaoRedes['id_rede']] = 1;		
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
require_once('../../include/js/selecao_listbox.js');  
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
	
function valida_form() 
	{
	if ( document.forma.frm_nm_aplicativo.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('O campo nome do aplicativo e obrigatorio.');?>");
		document.forma.frm_nm_aplicativo.focus();
		return false;
		}
	PreencheFormChecksRedes();			
	}

</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_aplicativo')">
<?php require_once('../../include/js/opcoes_avancadas_combos.js');  ?>
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<table width="85%" border="0" align="center">
<tr><td colspan="5" class="cabecalho"><?php echo $oTranslator->_('Detalhes de Perfil de Sistema Monitorado');?></td></tr>
<tr><td colspan="5" class="descricao"><?php echo $oTranslator->_('As informacoes abaixo referem-se as caracteristicas de instalacao de sistema a serem monitorados');?>
<?php echo $oTranslator->_('Deve-se ter o cuidado quanto a sensibilidade no uso de letras maiusculas e minusculas.');?></td></tr>
<form method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
<input type="hidden" name="id_aplicativo" value="<?php echo $_GET['id_aplicativo'];?>">	
<tr><td align="center" colspan="5"><div align="center"><br>
<tr><td nowrap class="label" colspan="5" align="left"><?php echo $oTranslator->_('Verificacao Ativa?');?> 
<select name="frm_in_ativa" id="select16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
<option value="N" <?php if (strpos($row['nm_aplicativo'], "#DESATIVADO#")>0) echo " selected ";?>><?php echo $oTranslator->_('Nao');?></option>
<option value="S" <?php if (strpos($row['nm_aplicativo'], "#DESATIVADO#")==0) echo " selected ";?>><?php echo $oTranslator->_('Sim');?></option>
</select></td></tr>
<tr><td nowrap class="label" colspan="5">&nbsp;</td></tr>
<tr><td nowrap class="label" colspan="5" align="left"><?php echo $oTranslator->_('Nome do sistema:');?><br> 
<?php $v_nm_aplicativo = $row['nm_aplicativo']; 
if (strpos($v_nm_aplicativo, "#DESATIVADO#")>0) 
	{
	$v_nm_aplicativo = substr($row['nm_aplicativo'], 0, strpos($row['nm_aplicativo'], "#DESATIVADO#"));
	}
?>
<input name="frm_nm_aplicativo" type="text" id="frm_nm_aplicativo3" size="80" maxlength="150" value="<?php echo $v_nm_aplicativo;?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> > 
</td></tr>
<tr><td nowrap class="label" colspan="5">&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Eh um Sistema Operacional?');?> 
Qual?<br> <select name="frm_id_so" id="select13" onChange="SetaNomeSistema();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
<option value="0"></option>
<?php
Conecta_bd_cacic();
$query = "SELECT id_so,te_desc_so
FROM   so
WHERE  id_so <> '0'
ORDER  BY te_desc_so";
mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('so')));
$sql_result=mysql_query($query);			
while ($row_so=mysql_fetch_array($sql_result))
	{ 
	echo "<option value=\"" . $row_so["id_so"] . "\"";
	if ($row_so['id_so'] == $row["id_so"]) echo " selected ";
	echo ">" . $row_so["te_desc_so"] . "</option>";
	} 			
?>
</select></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="label">
<?php echo $oTranslator->_('Disponibilizar informacoes no Systray?');?>
<?php echo $oTranslator->_('(icone na bandeja da estacao):');?><br> 
<select name="frm_in_disponibiliza_info" id="frm_in_disponibiliza_info" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
<option value="N" <?php if ($row['in_disponibiliza_info'] == "N") echo " selected ";?>><?php echo $oTranslator->_('Nao');?></option>
<option value="S" <?php if ($row['in_disponibiliza_info'] == "S") echo " selected ";?>><?php echo $oTranslator->_('Sim');?></option>
</select></td></tr>
<tr><td colspan="5">&nbsp;</td></tr>
<tr><td nowrap class="label" colspan="5" align="left"><?php echo $oTranslator->_('Disponibilizar informacoes ao usuario comum?');?><?php echo $oTranslator->_('(diferente de administrador):');?><br> 
<select name="frm_in_disponibiliza_info_usuario_comum" id="frm_in_disponibiliza_info_usuario_comum" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
<option value="N" <?php if ($row['in_disponibiliza_info_usuario_comum'] == "N") echo " selected ";?>><?php echo $oTranslator->_('Nao');?></option>
<option value="S" <?php if ($row['in_disponibiliza_info_usuario_comum'] == "S") echo " selected ";?>><?php echo $oTranslator->_('Sim');?></option>
</select> </td></tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Descricao');?></td></tr>
<tr><td nowrap colspan="5" align="left"><textarea name="frm_te_descritivo" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> cols="60" rows="3" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><?php echo $row['te_descritivo'];?></textarea></td></tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Identificador de licenca');?></td></tr>
<tr><td colspan="5" align="left" nowrap><select name="frm_cs_ide_licenca" id="select6" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda1')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
<option value="0" id=""></option>
<option value="1" <?php if ($row['cs_ide_licenca']=='1') echo 'selected';?>  id="<?php echo $oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>"><?php echo $oTranslator->_('Caminho\Chave\Valor em Registry');?></option>
<option value="2" <?php if ($row['cs_ide_licenca']=='2') echo 'selected';?>  id="<?php echo $oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>"><?php echo $oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?></option>
</select>
<br><input name="frm_te_ide_licenca" type="text" id="frm_te_ide_licenca" value="<?php echo $row['te_ide_licenca'];?>" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
<br><input name="Ajuda1" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
<br><input name="Ajuda11" type="text" style="border:0" size="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
<div align="left"><br></div></td></tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="cabecalho_secao"><u><?php echo $oTranslator->_('Caracteristicas em ambientes Windows 9x/Me');?></u></td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Identificador de instalacao');?></td></tr>
<tr><td colspan="5" align="left" nowrap><select name="frm_cs_car_inst_w9x" id="select17" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda2')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
<option value="0" id=""></option>
<option value="1" <?php if ($row['cs_car_inst_w9x']=='1') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\modulos\gercols.exe');?>"><?php echo $oTranslator->_('Nome de executavel');?></option>
<option value="2" <?php if ($row['cs_car_inst_w9x']=='2') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>"><?php echo $oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?></option>
<option value="3" <?php if ($row['cs_car_inst_w9x']=='3') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>"><?php echo $oTranslator->_('Caminho\Chave\Valor em Registry');?></option>
</select>
<br><input name="frm_te_car_inst_w9x" type="text" id="frm_te_car_inst_w9x3" size="80" maxlength="150" value="<?php echo $row['te_car_inst_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>>
<br><input name="Ajuda2" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200">
<br><input name="Ajuda22" type="text" style="border:0" size="80"></td></tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Identificador de versao/configuracao');?></td></tr>
<tr><td colspan="5" align="left" nowrap><select name="frm_cs_car_ver_w9x" id="select18" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda3')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
<option value="0" id=""></option>
<option value="1" <?php if ($row['cs_car_ver_w9x']=='1') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\modulos\gercols.exe');?>"><?php echo $oTranslator->_('Data de arquivo');?></option>
<option value="2" <?php if ($row['cs_car_ver_w9x']=='2') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>"><?php echo $oTranslator->_('Caminho\Chave\Valor em Registry');?></option>
<option value="3" <?php if ($row['cs_car_ver_w9x']=='3') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>"><?php echo $oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?></option>
<option value="4" <?php if ($row['cs_car_ver_w9x']=='4') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\cacic.exe');?>"><?php echo $oTranslator->_('Versao de executavel');?></option>
</select> 
<br>
<input name="frm_te_car_ver_w9x" type="text" id="frm_te_car_ver_w9x3" size="80" maxlength="150" value="<?php echo $row['te_car_ver_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
<br> <input name="Ajuda3" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
<br> <input name="Ajuda33" type="text" style="border:0" size="80"></td>
</tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td colspan="5" align="left" nowrap class="cabecalho_secao"><u><?php echo $oTranslator->_('Caracteristicas em ambientes Windows NT/2000/XP/2003');?></u></td></tr>
<tr><td colspan="5" align="left" nowrap class="label"><?php echo $oTranslator->_('Identificador de instalacao');?></td></tr>
<tr><td nowrap><select name="frm_cs_car_inst_wnt" id="select19" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda4')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
<option value="0" id=""></option>
<option value="1" <?php if ($row['cs_car_inst_wnt']=='1') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\modulos\gercols.exe');?>"><?php echo $oTranslator->_('Nome de executavel');?></option>
<option value="2" <?php if ($row['cs_car_inst_wnt']=='2') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>"><?php echo $oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?></option>
<option value="3" <?php if ($row['cs_car_inst_wnt']=='3') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>"><?php echo $oTranslator->_('Caminho\Chave\Valor em Registry');?></option>
</select>
<br><input name="frm_te_car_inst_wnt" type="text" id="frm_te_car_inst_wnt3" size="80" maxlength="150" value="<?php echo $row['te_car_inst_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
<br><input name="Ajuda4" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
<br><input name="Ajuda44" type="text" style="border:0" size="80"></td>
</tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td nowrap class="label"><?php echo $oTranslator->_('Identificador de versao/configuracao');?></td></tr>
<tr><td nowrap> <select name="frm_cs_car_ver_wnt" id="select20" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda5')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
<option value="0" id=""></option>
<option value="1" <?php if ($row['cs_car_ver_wnt']=='1') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\modulos\gercols.exe');?>"><?php echo $oTranslator->_('Data de arquivo');?></option>
<option value="2" <?php if ($row['cs_car_ver_wnt']=='2') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>"><?php echo $oTranslator->_('Caminho\Chave\Valor em Registry');?></option>
<option value="3" <?php if ($row['cs_car_ver_wnt']=='3') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>"><?php echo $oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?></option>
<option value="4" <?php if ($row['cs_car_ver_wnt']=='4') echo 'selected';?> id="<?php echo $oTranslator->_('Exemplo: Cacic\cacic.exe');?>"><?php echo $oTranslator->_('Versao de executavel');?></option>
</select>
<br><input name="frm_te_car_ver_wnt" type="text" id="frm_te_car_ver_wnt3" size="80" maxlength="150" value="<?php echo $row['te_car_ver_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
<br><input name="Ajuda5" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
<br><input name="Ajuda55" type="text" style="border:0" size="80">
</td></tr>
<tr><td colspan="5" nowrap>&nbsp;</td></tr>
<tr><td nowrap class="cabecalho_secao"><u><?php echo $oTranslator->_('Selecao de redes para aplicacao desta coleta de informacoes');?></u></td></tr>
<tr><td align="center"><?php $boolDetalhes = 'OK'; include_once "../../include/selecao_redes_perfil_inc.php";?></td></tr>
</table>
<br>
</div></td></tr></table>
<p align="center">
<?php if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 3)
	{
	?>
	<input name="GravaAlteracoes" type="submit" value="  <?php echo $oTranslator->_('Gravar alteracoes');?>  " onClick="return Confirma('<?php echo $oTranslator->_('Confirma Informacoes para perfil de sistema monitorado?');?>'))">
	&nbsp; &nbsp;
	<input name="ExcluiAplicativo" type="submit" value="<?php echo $oTranslator->_('Excluir perfil de sistema monitorado');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma exclusao de perfil de sistema monitorado?');?>');" <?php echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
    <?php
	}
	?>
</p>
</form>
</body>
</html>
<?php
}
?>