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

require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central

Conecta_bd_cacic();

if ($_POST['ExcluiAplicativo']) 
	{
	$query = "DELETE 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');			
	
	$query = "DELETE 
			  FROM 		aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_monitorados')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_monitorados');			

	$query = "DELETE
			  FROM		aplicativos_redes
			  WHERE		id_aplicativo = ".$_POST['id_aplicativo'];
	$result = mysql_query($query) or die ($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));				
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');			
		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
	}
elseif ($_POST['GravaAlteracoes']) 
	{

	if ($_SESSION['cs_nivel_administracao']==1)
		{		
		$v_nm_aplicativo = $frm_nm_aplicativo;
		if ($frm_in_ativa == 'N')
			{
			$v_nm_aplicativo .= '#DESATIVADO#';
			}
		$query = "UPDATE 	perfis_aplicativos_monitorados 
				  SET 		nm_aplicativo = '$v_nm_aplicativo',  
				  			te_dir_padrao_w9x = '$frm_te_dir_padrao_w9x',
							te_dir_padrao_wnt = '$frm_te_dir_padrao_wnt',			  
			  				cs_car_inst_w9x = '$frm_cs_car_inst_w9x', 
							cs_car_inst_wnt = '$frm_cs_car_inst_wnt', 
			  				te_car_inst_w9x = '$frm_te_car_inst_w9x', 
							te_car_inst_wnt = '$frm_te_car_inst_wnt', 
				  			cs_car_ver_w9x = '$frm_cs_car_ver_w9x', 
							cs_car_ver_wnt = '$frm_cs_car_ver_wnt', 
				  			te_car_ver_w9x = '$frm_te_car_ver_w9x', 
							te_car_ver_wnt = '$frm_te_car_ver_wnt', 
			  				te_arq_ver_eng_w9x = '$frm_te_arq_ver_eng_w9x', 
							te_arq_ver_pat_w9x = '$frm_te_arq_ver_pat_w9x', 			  
				  			te_arq_ver_eng_wnt = '$frm_te_arq_ver_eng_wnt', 
							te_arq_ver_pat_wnt = '$frm_te_arq_ver_pat_wnt', 			  			  
				  			cs_ide_licenca = '$frm_cs_ide_licenca', 
							te_ide_licenca = '$frm_te_ide_licenca', 			  
			  				id_so = '$frm_id_so', 
							te_descritivo = '$frm_te_descritivo', 			  			   			  			  
			  				dt_atualizacao = now(),
				  			in_disponibiliza_info = '$frm_in_disponibiliza_info',
				  			in_disponibiliza_info_usuario_comum = '$frm_in_disponibiliza_info_usuario_comum'			    			  			  
				  WHERE 	id_aplicativo = ".$_POST['id_aplicativo'];

		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');		
		}

	$strDeleteAplicativosRedes_Locais = '';
	$strDeleteAplicativosRedes_Redes  = '';		
	if ($_POST['list1'])
		{
		for ($i=0; $i < count($_POST['list1']);$i++)
			{
			$dado = explode('_',$_POST['list1'][$i]);
			if ($strDeleteAplicativosRedes_Locais)
				$strDeleteAplicativosRedes_Locais .= ',';
			$strDeleteAplicativosRedes_Locais .= $dado[0];		
		
			if ($strDeleteAplicativosRedes_Redes)
				$strDeleteAplicativosRedes_Redes .= ',';
			$strDeleteAplicativosRedes_Redes .= "'".$dado[1]."'";					
			}
		$query = "DELETE
				  FROM		aplicativos_redes
				  WHERE		id_aplicativo = ".$_POST['id_aplicativo']." AND 
				  			id_local in (".$strDeleteAplicativosRedes_Locais.") AND
  							id_ip_rede in (".$strDeleteAplicativosRedes_Redes.")";			
		$result = mysql_query($query) or die ($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));											
		}
		
	if ($_POST['list2'])
		{
		$strInsertAplicativosRedes = '';
		$strDeleteAplicativosRedes = " AND (";
		for ($i=0; $i < count($_POST['list2']);$i++)
			{
			$dado = explode('_',$_POST['list2'][$i]);
			if ($strInsertAplicativosRedes)
				$strInsertAplicativosRedes .= ',';
			$strInsertAplicativosRedes .= "(".$dado[0].",'".$dado[1]."',".$_POST['id_aplicativo'].")";		

			if ($strDeleteAplicativosRedes <> " AND (")
				$strDeleteAplicativosRedes .= ' OR ';
			
			$strDeleteAplicativosRedes .= " (id_local = ".$dado[0]." AND id_ip_rede = '".$dado[1]."')";
			}
		$strDeleteAplicativosRedes .= ") ";			
		$query = "DELETE
				  FROM		aplicativos_redes
				  WHERE		id_aplicativo = ".$_POST['id_aplicativo'].
		$strDeleteAplicativosRedes;
		
		$result = mysql_query($query) or die ($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));								  
			
		$query = "INSERT 
				  INTO 		aplicativos_redes(id_local,id_ip_rede,id_aplicativo)
				  VALUES 	".$strInsertAplicativosRedes;
				  
		$result = mysql_query($query) or die ($oTranslator->_('Falha em inclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));								  
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');				
		}
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = ".$_GET['id_aplicativo'];
	$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('perfis_aplicativos_monitorados')));
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
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

</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_nm_aplicativo')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
	<tr> 
    	<td class="cabecalho">
    	  <?=$oTranslator->_('Detalhes de Perfil de Sistema Monitorado');?>
		</td>
	</tr>
  	<tr> 
    	<td class="descricao">
    	  <?=$oTranslator->_('As informacoes abaixo referem-se as caracteristicas de instalacao de sistema a serem monitorados');?>
    	  <?=$oTranslator->_('Deve-se ter o cuidado quanto a sensibilidade no uso de letras maiusculas e minusculas.');?>
		</td>
  	</tr>
</table>

	<form method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="return valida_form()">
	<input type="hidden" name="id_aplicativo" value="<? echo $_GET['id_aplicativo'];?>">	
  <tr> 
    <td align="center">
<div align="center"><br>
        <table width="90%" border="0" align="center">
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Verificacao Ativa?');?> 
              <select name="frm_in_ativa" id="select16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")>0) echo " selected ";?>><?=$oTranslator->_('Nao');?></option>
                <option value="S" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")==0) echo " selected ";?>><?=$oTranslator->_('Sim');?></option>
              </select></td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Nome do sistema:');?><br> 
              <? $v_nm_aplicativo = $row['nm_aplicativo']; 
			if (strpos($v_nm_aplicativo, "#DESATIVADO#")>0) 
					{
					$v_nm_aplicativo = substr($row['nm_aplicativo'], 0, strpos($row['nm_aplicativo'], "#DESATIVADO#"));
					}
			?>
              <input name="frm_nm_aplicativo" type="text" id="frm_nm_aplicativo3" size="80" maxlength="150" value="<? echo $v_nm_aplicativo;?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> > 
            </td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td width="58%" nowrap class="label"><?=$oTranslator->_('Eh um Sistema Operacional?');?> 
              Qual?<br> <select name="frm_id_so" id="select13" onChange="SetaNomeSistema();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="0"></option>
                <?
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
              </select> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">
              <?=$oTranslator->_('Disponibilizar informacoes no Systray?');?>
              <?=$oTranslator->_('(icone na bandeja da estacao):');?><br> 
              <select name="frm_in_disponibiliza_info" id="frm_in_disponibiliza_info" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if ($row['in_disponibiliza_info'] == "N") echo " selected ";?>><?=$oTranslator->_('Nao');?></option>
                <option value="S" <? if ($row['in_disponibiliza_info'] == "S") echo " selected ";?>><?=$oTranslator->_('Sim');?></option>
              </select> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">
              <?=$oTranslator->_('Disponibilizar informacoes ao usuario comum?');?>
              <?=$oTranslator->_('(diferente de administrador):');?><br> 
              <select name="frm_in_disponibiliza_info_usuario_comum" id="frm_in_disponibiliza_info_usuario_comum" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"<? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?> >
                <option value="N" <? if ($row['in_disponibiliza_info_usuario_comum'] == "N") echo " selected ";?>><?=$oTranslator->_('Nao');?></option>
                <option value="S" <? if ($row['in_disponibiliza_info_usuario_comum'] == "S") echo " selected ";?>><?=$oTranslator->_('Sim');?></option>
              </select> </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Descricao');?></td>
          </tr>
          <tr> 
            <td nowrap> <textarea name="frm_te_descritivo" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?> cols="60" rows="3" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $row['te_descritivo'];?></textarea> 
            </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Identificador de licenca');?></td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_ide_licenca" id="select6" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda1')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_ide_licenca']=='1') echo 'selected';?>  id="<?=$oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>">
                <?=$oTranslator->_('Caminho\Chave\Valor em Registry');?>
                </option>
                <option value="2" <? if ($row['cs_ide_licenca']=='2') echo 'selected';?>  id="<?=$oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>">
                 <?=$oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?>
                </option>
              </select> <br> <input name="frm_te_ide_licenca" type="text" id="frm_te_ide_licenca" value="<? echo $row['te_ide_licenca'];?>" size="80" maxlength="150" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda1" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda11" type="text" style="border:0" size="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <div align="left"> <br>
              </div></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao">
              <u><?=$oTranslator->_('Caracteristicas em ambientes Windows 9x/Me');?></u>
            </td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Identificador de instalacao');?></td>
          </tr>
          <tr> 
            <td nowrap><select name="frm_cs_car_inst_w9x" id="select17" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda2')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_w9x']=='1') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\modulos\ger_cols.exe">
                  <?=$oTranslator->_('Nome de executavel');?>
                </option>
                <option value="2" <? if ($row['cs_car_inst_w9x']=='2') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>">
                 <?=$oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?>
                </option>
                <option value="3" <? if ($row['cs_car_inst_w9x']=='3') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>">
                 <?=$oTranslator->_('Caminho\Chave\Valor em Registry');?>
                </option>
              </select> <br> <input name="frm_te_car_inst_w9x" type="text" id="frm_te_car_inst_w9x3" size="80" maxlength="150" value="<? echo $row['te_car_inst_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda2" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda22" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Identificador de versao/configuracao');?></td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_w9x" id="select18" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda3')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_w9x']=='1') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\modulos\ger_cols.exe">
                  <?=$oTranslator->_('Data de arquivo');?>
                </option>
                <option value="2"<? if ($row['cs_car_ver_w9x']=='2') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>">
                 <?=$oTranslator->_('Caminho\Chave\Valor em Registry');?>
                </option>
                <option value="3"<? if ($row['cs_car_ver_w9x']=='3') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>">
                 <?=$oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?>
                </option>
                <option value="4"<? if ($row['cs_car_ver_w9x']=='4') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\cacic.exe">
                 <?=$oTranslator->_('Versao de executavel');?>
                </option>
              </select> <br> <input name="frm_te_car_ver_w9x" type="text" id="frm_te_car_ver_w9x3" size="80" maxlength="150" value="<? echo $row['te_car_ver_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda3" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda33" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao">
             <u><?=$oTranslator->_('Caracteristicas em ambientes Windows NT/2000/XP/2003');?></u>
            </td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Identificador de instalacao');?></td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_inst_wnt" id="select19" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda4')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_wnt']=='1') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\modulos\ger_cols.exe">
                  <?=$oTranslator->_('Nome de executavel');?>
                </option>
                <option value="2" <? if ($row['cs_car_inst_wnt']=='2') echo 'selected';?> id=<?=$oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>">
                 <?=$oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?>
                </option>
                <option value="3" <? if ($row['cs_car_inst_wnt']=='3') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>">
                 <?=$oTranslator->_('Caminho\Chave\Valor em Registry');?>
                </option>
              </select> <br> <input name="frm_te_car_inst_wnt" type="text" id="frm_te_car_inst_wnt3" size="80" maxlength="150" value="<? echo $row['te_car_inst_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda4" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda44" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Identificador de versao/configuracao');?></td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_wnt" id="select20" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda5')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_wnt']=='1') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\modulos\ger_cols.exe">
                  <?=$oTranslator->_('Data de arquivo');?>
                </option>
                <option value="2"<? if ($row['cs_car_ver_wnt']=='2') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao');?>">
                 <?=$oTranslator->_('Caminho\Chave\Valor em Registry');?>
                </option>
                <option value="3"<? if ($row['cs_car_ver_wnt']=='3') echo 'selected';?> id="<?=$oTranslator->_('Exemplo: Arquivos de Programas\Cacic\Dados\config.ini/Patrimonio/nu_CPU');?>">
                 <?=$oTranslator->_('Caminho\nome do arquivo/Secao/Chave de Arquivo INI');?>
                </option>
                <option value="4"<? if ($row['cs_car_ver_wnt']=='4') echo 'selected';?> id="<?=$oTranslator->_('Exemplo:');?> Cacic\cacic.exe">
                 <?=$oTranslator->_('Versao de executavel');?>
                </option>
              </select> <br> <input name="frm_te_car_ver_wnt" type="text" id="frm_te_car_ver_wnt3" size="80" maxlength="150" value="<? echo $row['te_car_ver_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1?'readonly':'')?>> 
              <br> <input name="Ajuda5" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda55" type="text" style="border:0" size="80"></td>
          </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
    </tr>
	
    <tr> 
      <td nowrap class="cabecalho_secao">
        <u><?=$oTranslator->_('Selecao de redes para aplicacao desta coleta de informacoes');?></u>
      </td>
    </tr>
	
	<tr>
	<td>
	<?
	$boolDetalhes = 'OK';
	include_once "../../include/selecao_redes_perfil_inc.php";	
	?>
	</td>	
	</tr>
		  
        </table>
          
        <br>
      </div></td>
	  
    </tr>
	
  </table>


  <p align="center"> 
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  <?=$oTranslator->_('Gravar alteracoes?');?>  " onClick="return Confirma('<?=$oTranslator->_('Confirma Informacoes para perfil de sistema monitorado?');?>'),SelectAll(this.form.elements['list1[]']),SelectAll(this.form.elements['list2[]']) " <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
    &nbsp; &nbsp; 
    <input name="ExcluiAplicativo" type="submit" value="<?=$oTranslator->_('Excluir perfil de sistema monitorado');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao de perfil de sistema monitorado?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
  </form>
</body>
</html>
<?
}
?>