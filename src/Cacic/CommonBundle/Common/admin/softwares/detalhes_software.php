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

require_once('../../include/library.php');
AntiSpy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiSoftware']) 
	{
	$query = "DELETE 
			  FROM 		softwares 
			  WHERE 	id_software = ".$_REQUEST['frm_id_software'];
	mysql_query($query) or die('Falha de dele��o na tabela softwares ou sua sess�o expirou!');
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
	mysql_query($query) or die('Falha na atualiza��o da tabela Softwares ou sua sess�o expirou!');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'softwares');
			
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/softwares/index.php&tempo=1");									 					
}
else {
        $query = ' SELECT  *
                           FROM  softwares
                           WHERE id_software = '.$_REQUEST['id_software'];

	$result = mysql_query($query) or die ('Falha na consulta �s tabelas Redes, Locais ou sua sess�o expirou!');
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
		alert("O local da rede � obrigat�rio");
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
		alert("A m�scara de rede � obrigat�ria.\nPor favor, informe-a, usando o formato X.X.X.0\nExemplo: 255.255.255.0");
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
		alert("Digite o Nome do Usu�rio para Login no Servidor de Updates pelo M�dulo Agente");
		document.form.frm_nm_usuario_login_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates_gerente.value == "" ) 
		{	
		alert("Digite o Nome do Usu�rio para Login no Servidor de Updates pelo M�dulo Gerente");
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
		    alert("O endere�o TCP/IP da rede foi informado incorretamente.\nPor favor, informe-o, usando o formato X.X.X.0\nExemplo: 10.70.4.0");
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
$data = mysql_fetch_array($result);
?>
<body <? if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('<? echo ($_SESSION['cs_nivel_administracao']<>1?'frm_te_mascara_rede':'frm_id_local')?>')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="detalhes_software.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho">Detalhes do Software <i><? echo mysql_result($result, 0, 'nm_software'); ?></i></td>
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
      <td>
          <input name="frm_id_software" type="hidden" value="<?=$data['id_software'];?>"> 
          <input name="frm_nm_software" type="text" size="50" maxlength="150"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['nm_software'];?>"> 
      </td>
    </tr>
    <tr> 
      <td class="label"><div align="left">Descri&ccedil;&atilde;o:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap>
          <input name="frm_te_descricao_software" type="text" id="frm_te_descricao_software" size="50" maxlength="255" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['te_descricao_software'];?>">
      </td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Quantidade de Licen�as:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap>
         <input name="frm_qt_licenca" type="text" id="frm_qt_licenca" size="11" maxlength="11" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['qt_licenca'];?>">
      </td> 
    </tr>
    <tr> 
      <td nowrap class="label"><div align="left"><br>
          N�mero da M�dia:</div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap>
         <input name="frm_nr_midia" type="text" id="frm_nr_midia"  size="11" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['nr_midia'];?>">
      </td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Localiza��o da M�dia:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td nowrap>
         <input name="frm_te_local_midia" type="text" id="frm_te_local_midia" size="50" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['te_local_midia'];?>"> 
      </td>
    </tr>
    <tr> 
      <td nowrap class="label"><br>
        Observa��o:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
      <td>
         <input name="frm_te_obs" size="50" maxlength="200" id="frm_te_obs" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$data['te_obs'];?>">
      </td>
    </tr>
    <tr> 
      <td>
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return valida_form();return Confirma('Confirma Informa��es para Local?');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="ExcluiSoftware" type="submit" id="ExcluiSO" onClick="return Confirma('Confirma exclusao de software?');" value="Excluir software" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
<?
}
?>
