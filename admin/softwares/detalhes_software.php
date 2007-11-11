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
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');
// Comentado temporariamente - AntiSpy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiSoftware']) 
	{
	$query = "DELETE 
			  FROM 		softwares 
			  WHERE 	id_software = ".$_REQUEST['frm_id_software'];
	mysql_query($query) or die('Falha de deleção na tabela softwares ou sua sessão expirou!');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'softwares');				
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/softwares/index.php&tempo=1");									 				
	}
elseif ($_POST['GravaAlteracoes']) 
	{
	$query = "UPDATE 	softwares SET 
			  			nm_software 			= '".$_POST['frm_nm_software']."',
			  			te_descricao_software	= '".$_POST['frm_te_descricao_software']."', 
			  			qt_licenca 				=  ".$_POST['frm_qt_licenca'].", 
			  			nr_midia 				= '".$_POST['frm_nr_midia']."', 
			  			te_local_midia			= '".$_POST['frm_te_local_midia']."', 
			  			te_obs					= '".$_POST['frm_te_obs']."'
			  WHERE 	id_software = ".$_REQUEST['frm_id_software'];
	mysql_query($query) or die('Falha na atualização da tabela Softwares ou sua sessão expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'softwares');
			
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/softwares/index.php&tempo=1");									 					
}
else {
	$query = "	SELECT 	* 
				FROM 	redes
						LEFT JOIN locais ON (redes.id_local = locais.id_local AND redes.id_local = ".$_GET['id_local'].") 
				WHERE 	redes.id_ip_rede = '".$_GET['id_ip_rede']."'";
	$result = mysql_query($query) or die ('Falha na consulta às tabelas Redes, Locais ou sua sessão expirou!');
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">
function SetaServidorBancoDados()	
	{
	document.form.frm_te_serv_cacic.value = document.form.sel_te_serv_cacic.value;	
	document.form.sel_te_serv_cacic.options.selectedIndex=0;		
	}
function SetaServidorUpdates()	
	{
	var v_string = document.form.sel_te_serv_updates.value;
	var v_array_string = v_string.split("#");
	document.form.frm_te_serv_updates.value = v_array_string[0];
	document.form.frm_nu_porta_serv_updates.value = v_array_string[1];	
	document.form.frm_nm_usuario_login_serv_updates.value = v_array_string[2];		
	document.form.frm_nm_usuario_login_serv_updates_gerente.value = v_array_string[2];			
	document.form.frm_te_path_serv_updates.value = v_array_string[3];				
	document.form.frm_nu_limite_ftp.value = v_array_string[4];					
	document.form.sel_te_serv_updates.options.selectedIndex=0;
	document.form.frm_te_senha_login_serv_updates.value = "";
	document.form.frm_te_senha_login_serv_updates_gerente.value = "";	
	document.form.frm_te_senha_login_serv_updates.select();
	}
	
function valida_form() 
	{	
	if (document.form.frm_id_local.selectedIndex==0 && document.form.frm_id_local.value==-1) 
		{	
		alert("O local da rede é obrigatório");
		document.form.frm_id_local.focus();
		return false;
		}
	
	var ip = document.form.frm_id_ip_rede.value;
	var ipSplit = ip.split(/\./);
	
	if ( ip == "" ) 
		{	
		alert("Digite o IP da rede");
		document.form.frm_id_ip_rede.focus();
		return false;
		}
	else if ( document.form.frm_te_mascara_rede.value == "" ) 
		{	
		alert("A máscara de rede é obrigatória.\nPor favor, informe-a, usando o formato X.X.X.0\nExemplo: 255.255.255.0");
		document.form.frm_te_mascara_rede.focus();
		return false;
		}
		
	else if ( document.form.frm_nm_rede.value == "" ) 
		{	
		alert("Digite o nome da rede");
		document.form.frm_nm_rede.focus();
		return false;
		}
	else if ( document.form.frm_te_serv_cacic.value == "" ) 
		{	
		alert("Digite o Identificador do Servidor de Banco de Dados");
		document.form.frm_te_serv_cacic.focus();
		return false;
		}	
	else if ( document.form.frm_te_serv_updates.value == "" ) 
		{	
		alert("Digite o Identificador do Servidor de Updates");
		document.form.frm_te_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nu_porta_serv_updates.value == "" ) 
		{	
		alert("Digite a Porta FTP do Servidor de Updates");
		document.form.frm_nu_porta_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates.value == "" ) 
		{	
		alert("Digite o Nome do Usuário para Login no Servidor de Updates pelo Módulo Agente");
		document.form.frm_nm_usuario_login_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates_gerente.value == "" ) 
		{	
		alert("Digite o Nome do Usuário para Login no Servidor de Updates pelo Módulo Gerente");
		document.form.frm_nm_usuario_login_serv_updates_gerente.focus();
		return false;
		}			
	else if ( document.form.frm_te_path_serv_updates.value == "" ) 
		{	
		alert("Digite o Path no Servidor de Updates");
		document.form.frm_te_path_serv_updates.focus();
		return false;
		}		
	else
		{
		if((ip.search(/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/) != -1) && (ipSplit[3] == 0)) 
			{
			return true;
			}
		else 
			{
		    alert("O endereço TCP/IP da rede foi informado incorretamente.\nPor favor, informe-o, usando o formato X.X.X.0\nExemplo: 10.70.4.0");
			document.form.frm_id_ip_rede.focus();
			return false;
			}
		}
	return true;
	}
</script>
</head>
<?
$pos = substr_count($_SERVER['HTTP_REFERER'],'navegacao');
?>
<body <? if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('<? echo ($_SESSION['cs_nivel_administracao']<>1?'frm_te_mascara_rede':'frm_id_local')?>')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="detalhes_rede.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho">Detalhes do Software<? echo mysql_result($result, 0, 'id_ip_rede'); ?></td>
  </tr>
  <tr> 
      <td class="descricao">As informa&ccedil;&otilde;es abaixo referem-se a um 
        software previamente cadastrado no sistema.</td>
  </tr>
</table>

  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td class="label"><br>
        Nome:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_nm_software" type="text" size="50" maxlength="150"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><input name="frm_te_descricao_software" type="text" id="frm_te_descricao_software" size="50" maxlength="255" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Quantidade de Licenças:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap> <input name="frm_qt_licenca" type="text" id="frm_qt_licenca" size="11" maxlength="11" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
    </tr>
    <tr> 
      <td nowrap class="label"><div align="left"><br>
          Número da Mídia:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap><input name="frm_nr_midia" type="text" id="frm_nr_midia"  size="11" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Localização da Mídia:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap> <input name="frm_te_local_midia" type="text" id="frm_te_local_midia" size="50" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Observação:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td><input name="frm_te_obs" size="50" maxlength="200" id="frm_te_obs" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
    </tr>
  </table>
</form>
</body>
</html>
<?
}
?>
