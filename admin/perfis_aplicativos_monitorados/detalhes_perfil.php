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
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if ($ExcluiAplicativo) {
	$query = "DELETE 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = $id_aplicativo";
	mysql_query($query) or die('Delete PERFIS_APLICATIVOS_MONITORADOS falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');			
	$query = "DELETE 
			  FROM 		aplicativos_monitorados 
			  WHERE 	id_aplicativo = $id_aplicativo";
	mysql_query($query) or die('Delete APLICATIVOS_MONITORADOS falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_monitorados');			
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
}
elseif ($GravaAlteracoes) {
//te_dir_pad_w9x = '$frm_te_dir_pad_w9x', te_dir_pad_wnt = '$frm_te_dir_pad_wnt', 
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
			  WHERE 	id_aplicativo = $id_aplicativo";

	mysql_query($query) or die('Update falhou');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'perfis_aplicativos_monitorados');		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/perfis_aplicativos_monitorados/index.php&tempo=1");									 		
	
}
else {
	$query = "SELECT 	* 
			  FROM 		perfis_aplicativos_monitorados 
			  WHERE 	id_aplicativo = $id_aplicativo";
	$result = mysql_query($query) or die ('select falhou');
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
		alert("O campo Nome do Aplicativo é obrigatório.");
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
    	<td class="cabecalho">Detalhes de Perfil de Sistema Monitorado
		</td>
	</tr>
  	<tr> 
    	<td class="descricao">As informa&ccedil;&otilde;es 
      	abaixo referem-se &agrave;s caracter&iacute;sticas de instala&ccedil;&atilde;o 
      	do sistema a ser monitorado pelos agentes CACIC. &Eacute; necess&aacute;rio 
      	o cuidado especial quanto ao uso de letras mai&uacute;sculas e min&uacute;sculas.
		</td>
  	</tr>
</table>

	<form method="post" ENCTYPE="multipart/form-data" name="forma" onsubmit="return valida_form()">
	
  <tr> 
    <td align="center">
<div align="center"><br>
        <table width="90%" border="0" align="center">
          <tr> 
            <td nowrap class="label">Verifica&ccedil;&atilde;o Ativa?: 
              <select name="frm_in_ativa" id="select16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="N" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")>0) echo " selected ";?>>Não</option>
                <option value="S" <? if (strpos($row['nm_aplicativo'], "#DESATIVADO#")==0) echo " selected ";?>>Sim</option>
              </select></td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Nome do sistema:<br> 
              <? $v_nm_aplicativo = $row['nm_aplicativo']; 
			if (strpos($v_nm_aplicativo, "#DESATIVADO#")>0) 
					{
					$v_nm_aplicativo = substr($row['nm_aplicativo'], 0, strpos($row['nm_aplicativo'], "#DESATIVADO#"));
					}
			?>
              <input name="frm_nm_aplicativo" type="text" id="frm_nm_aplicativo3" size="80" maxlength="100" value="<? echo $v_nm_aplicativo;?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
          </tr>
          <tr> 
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td width="58%" nowrap class="label">&Eacute; um Sistema Operacional? 
              Qual?<br> <select name="frm_id_so" id="select13" onChange="SetaNomeSistema();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0"></option>
                <?
			Conecta_bd_cacic();
			$query = "SELECT id_so,te_desc_so
			          FROM   so
					  WHERE  id_so <> '0'
					  ORDER  BY te_desc_so";
			mysql_query($query) or die('Select falhou');
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
            <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es 
              no Systray? (&iacute;cone na bandeja da esta&ccedil;&atilde;o):<br> 
              <select name="frm_in_disponibiliza_info" id="select14" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="N" <? if ($row['in_disponibiliza_info'] == "N") echo " selected ";?>>Não</option>
                <option value="S" <? if ($row['in_disponibiliza_info'] == "S") echo " selected ";?>>Sim</option>
              </select> </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Disponibilizar Informa&ccedil;&otilde;es 
              ao Usu&aacute;rio Comum? (diferente de Administrador):<br> <select name="frm_in_disponibiliza_info_usuario_comum" id="select15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="N" <? if ($row['in_disponibiliza_info_comum'] == "N") echo " selected ";?>>Não</option>
                <option value="S" <? if ($row['in_disponibiliza_info_comum'] == "S") echo " selected ";?>>Sim</option>
              </select> </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"> Descri&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <textarea name="frm_te_descritivo" cols="60" rows="3" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $row['te_descritivo'];?></textarea> 
            </td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Licen&ccedil;a:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_ide_licenca" id="select6" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda1')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_ide_licenca']=='1') echo 'selected';?>  id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="2" <? if ($row['cs_ide_licenca']=='2') echo 'selected';?>  id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o\Chave 
                de Arquivo INI</option>
              </select> <br> <input name="frm_te_ide_licenca" type="text" id="frm_te_ide_licenca" value="<? echo $row['te_ide_licenca'];?>" size="80" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda1" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda11" type="text" style="border:0" size="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <div align="left"> <br>
              </div></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes 
              Windows 9x/Me</u></td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap><select name="frm_cs_car_inst_w9x" id="select17" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda2')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_w9x']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Programas\cacic.exe">Nome 
                de Execut&aacute;vel</option>
                <option value="2" <? if ($row['cs_car_inst_w9x']=='2') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
                de Arquivo de Configura&ccedil;&atilde;o</option>
                <option value="3" <? if ($row['cs_car_inst_w9x']=='3') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
              </select> <br> <input name="frm_te_car_inst_w9x" type="text" id="frm_te_car_inst_w9x3" size="80" maxlength="100" value="<? echo $row['te_car_inst_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda2" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda22" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_w9x" id="select18" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda3')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_w9x']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
                de Arquivo</option>
                <option value="2"<? if ($row['cs_car_ver_w9x']=='2') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="3"<? if ($row['cs_car_ver_w9x']=='3') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
                de Arquivo INI</option>
                <option value="4"<? if ($row['cs_car_ver_w9x']=='4') echo 'selected';?> id="Ex.:  Cacic\modulos\col_moni.exe">Versão 
                de Executável</option>
              </select> <br> <input name="frm_te_car_ver_w9x" type="text" id="frm_te_car_ver_w9x3" size="80" maxlength="100" value="<? echo $row['te_car_ver_w9x'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda3" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda33" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="cabecalho_secao"><u>Caracter&iacute;sticas em ambientes 
              Windows NT/2000/XP/2003</u></td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Instala&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_inst_wnt" id="select19" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda4')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" id=""></option>
                <option value="1" <? if ($row['cs_car_inst_wnt']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Nome 
                de Execut&aacute;vel</option>
                <option value="2" <? if ($row['cs_car_inst_wnt']=='2') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Dados\config.ini">Nome 
                de Arquivo de Configura&ccedil;&atilde;o</option>
                <option value="3" <? if ($row['cs_car_inst_wnt']=='3') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
              </select> <br> <input name="frm_te_car_inst_wnt" type="text" id="frm_te_car_inst_wnt3" size="80" maxlength="100" value="<? echo $row['te_car_inst_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda4" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda44" type="text" style="border:0" size="80"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label">Identificador de Vers&atilde;o/Configura&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td nowrap> <select name="frm_cs_car_ver_wnt" id="select20" onChange="SetaDescGrupo(this.options[selectedIndex].id,'Ajuda5')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" id=""></option>
                <option value="1"<? if ($row['cs_car_ver_wnt']=='1') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic2\Programas\ger_cols.exe">Data 
                de Arquivo</option>
                <option value="2"<? if ($row['cs_car_ver_wnt']=='2') echo 'selected';?> id="Ex.:  HKEY_LOCAL_MACHINE\Software\Dataprev\Cacic2\id_versao">Caminho\Chave\Valor 
                em Registry</option>
                <option value="3"<? if ($row['cs_car_ver_wnt']=='3') echo 'selected';?> id="Ex.:  Arquivos de Programas\Cacic\Cacic2.ini/Patrimonio/nu_CPU">Nome/Se&ccedil;&atilde;o/Chave 
                de Arquivo INI</option>
                <option value="4"<? if ($row['cs_car_ver_wnt']=='4') echo 'selected';?> id="Ex.:  Cacic\modulos\col_moni.exe">Versão 
                de Executável</option>
              </select> <br> <input name="frm_te_car_ver_wnt" type="text" id="frm_te_car_ver_wnt3" size="80" maxlength="100" value="<? echo $row['te_car_ver_wnt'];?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <br> <input name="Ajuda5" type="text" style="border:0;font-size:9;color:#000099" size="80" maxlength="200"> 
              <br> <input name="Ajuda55" type="text" style="border:0" size="80"></td>
          </tr>
        </table>
          
        <br>
      </div></td>
    </tr>
  </table>


  <p align="center"> 
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para Perfil de Sistema Monitorado?');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
    &nbsp; &nbsp; 
    <input name="ExcluiAplicativo" type="submit" value="Excluir Perfil de Sistema Monitorado" onClick="return Confirma('Confirma Exclusão de Perfil de Sistema Monitorado?');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
  </form>
</body>
</html>
<?
}
?>
