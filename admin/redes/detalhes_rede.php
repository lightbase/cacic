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

AntiSpy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiRede']) 
	{
	$query = "DELETE 
			  FROM 		redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local_anterior'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('redes')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'redes');				
	$query = "DELETE 	
			  FROM 		acoes_redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local_anterior'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('acoes_redes')));	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');				

	$query = "DELETE 	
			  FROM 		aplicativos_redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local_anterior'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');				
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 				
	}
elseif ($_POST['GravaAlteracoes']) 
	{
	$boolJaExiste = false;
	if ($_POST['frm_id_local'] <> $_REQUEST['id_local_anterior'])
		{
		$query = "SELECT 	* 
				  FROM 		redes 
				  WHERE 	id_ip_rede = '".$_POST['id_ip_rede']."' AND
			  				id_local = ".$_POST['frm_id_local'];
						
		$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes')));
		if (mysql_num_rows($result) > 0) 		
			$boolJaExiste = true;
		}
	
	if ($boolJaExiste) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/redes/index.php&tempo=2");									 							
		}
	else
		{	
		$senhas = '';
		if ($_SESSION['te_senha_login_serv_updates'] <> $_POST['frm_te_senha_login_serv_updates'] && $_POST['frm_te_senha_login_serv_updates'] <> '**********')
			$senhas = ", te_senha_login_serv_updates = '".$_POST['frm_te_senha_login_serv_updates']."'";	
	
		if ($_SESSION['te_senha_login_serv_updates_gerente'] <> $_POST['frm_te_senha_login_serv_updates_gerente'] && $_POST['frm_te_senha_login_serv_updates_gerente'] <> '**********')
			$senhas .= ", te_senha_login_serv_updates_gerente = '".$_POST['frm_te_senha_login_serv_updates_gerente']."'";	
			
		$query = "UPDATE 	redes SET 
							te_mascara_rede 						= '".$_POST['frm_te_mascara_rede']."',
							nm_rede 								= '".$_POST['frm_nm_rede']."', 
							te_observacao 							= '".$_POST['frm_te_observacao']."', 
							nm_pessoa_contato1 						= '".$_POST['frm_nm_pessoa_contato1']."', 
							nm_pessoa_contato2 						= '".$_POST['frm_nm_pessoa_contato2']."', 
							nu_telefone1 							= '".$_POST['frm_nu_telefone1']."', 
							nu_telefone2 							= '".$_POST['frm_nu_telefone2']."', 
							te_email_contato1 						= '".$_POST['frm_te_email_contato1']."', 
							te_email_contato2 						= '".$_POST['frm_te_email_contato2']."', 
							te_serv_cacic 							= '".$_POST['frm_te_serv_cacic']."', 
							te_serv_updates 						= '".$_POST['frm_te_serv_updates']."', 
							nu_limite_ftp 							=  ".$_POST['frm_nu_limite_ftp'].", 						
							te_path_serv_updates 					= '".$_POST['frm_te_path_serv_updates']."',
							nm_usuario_login_serv_updates 			= '".$_POST['frm_nm_usuario_login_serv_updates']."', 
							nu_porta_serv_updates 					= '".$_POST['frm_nu_porta_serv_updates']."',
							nm_usuario_login_serv_updates_gerente 	= '".$_POST['frm_nm_usuario_login_serv_updates_gerente']."', 
							id_servidor_autenticacao				= '".$_POST['frm_id_servidor_autenticacao']."', 							
							cs_permitir_desativar_srcacic			= '".$_POST['frm_cs_permitir_desativar_srcacic']."', 														
							id_local 								=  ".$_POST['frm_id_local'].
							$senhas . " 
				  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
							id_local = ".$_REQUEST['id_local_anterior'];
	
		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('redes')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes');
	
		$query = "UPDATE 	acoes_redes SET 
							id_local = ".$_POST['frm_id_local']."
				  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
							id_local = ".$_REQUEST['id_local_anterior'];					
		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('acoes_redes')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'acoes_redes');			
	
		$query = "UPDATE 	redes_grupos_ftp SET 
							id_local =  ".$_POST['frm_id_local']."
				  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
							id_local = ".$_REQUEST['id_local_anterior'];
		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('redes_grupos_ftp')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes_grupos_ftp');			
	
		$query = "UPDATE 	redes_versoes_modulos SET 
							id_local =  ".$_POST['frm_id_local']."
				  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
							id_local = ".$_REQUEST['id_local_anterior'];
		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('redes_versoes_modulos')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes_versoes_modulos');			
	
		// Caso tenha sido alterado o local da subrede, primeiramente atualizarei a informação abaixo:
		if ($_POST['frm_id_local'] <> $_POST['id_local'])
			{
			$query = "UPDATE 	aplicativos_redes SET 
								id_local =  ".$_POST['frm_id_local']."
					  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
								id_local = ".$_REQUEST['id_local_anterior'];
			mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));
			GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'aplicativos_Redes');			
			}
		
		$v_perfis = '';
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			if ($v && substr($i,0,14)=='id_aplicativo_')
				{
				if ($v_perfis <> '') $v_perfis .= '__';
				$v_perfis .= $v;		
				}
			}
		if ($v_perfis <> '')
			{
			$query = "DELETE 	
					  FROM 		aplicativos_redes 
					  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
								id_local = ".$_REQUEST['id_local_anterior'];
			mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));	
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');						
			seta_perfis_rede($_REQUEST['frm_id_local'],trim($_REQUEST['id_ip_rede']), $v_perfis); 					
			}		
			
		header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 					
		}
	}
else 
	{
	$query = "	SELECT 	* 
				FROM 	redes						
				WHERE 	redes.id_ip_rede = '".$_GET['id_ip_rede']."' AND 
				        " .( 
						array_key_exists('id_local', $_GET)
						? "redes.id_local = ". (int) $_GET['id_local']
						: ''
					);		
	$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes')));
	?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language=JavaScript>
<!--

function desabilitar()
	{
    return false
	}
document.oncontextmenu=desabilitar

// -->
</script>
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
	document.form.frm_nu_limite_ftp.value = (v_array_string[4]==""?"5":v_array_string[4]);					
	document.form.sel_te_serv_updates.options.selectedIndex=0;
	document.form.frm_te_senha_login_serv_updates.value = "";
	document.form.frm_te_senha_login_serv_updates_gerente.value = "";	
	document.form.frm_te_senha_login_serv_updates.select();
	}

function valida_form() 
	{	
	if ( document.form.frm_nu_limite_ftp.value == "" ) 
		{	
		document.form.frm_nu_limite_ftp.value = "5";
		}					
	
	if (document.form.frm_id_local.selectedIndex==0 && document.form.frm_id_local.value==-1) 
		{	
		alert("<?=$oTranslator->_('O local da rede e obrigatorio');?>");
		document.form.frm_id_local.focus();
		return false;
		}
		
	if ( document.form.frm_nm_rede.value == "" ) 
		{	
		alert("<?=$oTranslator->_('O local da rede e obrigatorio');?>Digite o nome da rede");
		document.form.frm_nm_rede.focus();
		return false;
		}
	else if ( document.form.frm_te_serv_cacic.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite o Identificador do Servidor de Banco de Dados');?>");
		document.form.frm_te_serv_cacic.focus();
		return false;
		}	
	else if ( document.form.frm_te_serv_updates.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite o Identificador do Servidor de Atualizacoes');?>");
		document.form.frm_te_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nu_porta_serv_updates.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite a Porta FTP do Servidor de Atualizacoes');?>");
		document.form.frm_nu_porta_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite o Nome do Usuario para Login no Servidor de Atualizacoes pelo Modulo Agente');?>");
		document.form.frm_nm_usuario_login_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates_gerente.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite o Nome do Usuario para Login no Servidor de Atualizacoes pelo Modulo Gerente');?>");
		document.form.frm_nm_usuario_login_serv_updates_gerente.focus();
		return false;
		}			
	else if ( document.form.frm_te_path_serv_updates.value == "" ) 
		{	
		alert("<?=$oTranslator->_('Digite o caminho no Servidor de Atualizacoes');?>");
		document.form.frm_te_path_serv_updates.focus();
		return false;
		}		
	return true;
	}
</script>
<style type="text/css">
<!--
.style4 {
	color: #FF0000;
	font-weight: bold;
}
.style5 {
	font-size: 7pt;
	color: #FF9900;
}
.style7 {color: #0000FF; font-weight: bold; }
.style9 {font-size: 7pt; color: #006600; }
-->
</style>
</head>
<?php
$pos = substr_count($_SERVER['HTTP_REFERER'],'navegacao');

function formButtons() {
	global $oTranslator; ?>
          <p align="center"> <br>
            <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="<?=$oTranslator->_('Gravar alteracoes');?>" onClick="return Confirma('Confirma Informações para Esta Rede?'); " <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="ExcluiRede" type="submit" value="<?=$oTranslator->_('Excluir rede');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao de Rede');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            <?
			if ($_REQUEST['nm_chamador']=='locais')
				{
				?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="RetornaLocais" type="button" value="<?=$oTranslator->_('Retorna aos detalhes de Local');?>" onClick="history.back()">
            <?
				}
				?>
          </p>
<?}?>
<body <? if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('<? echo ($_SESSION['cs_nivel_administracao']<>1?'frm_te_mascara_rede':'frm_id_local')?>')">
<script language="javascript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="detalhes_rede.php"  method="post" ENCTYPE="multipart/form-data" name="form" id="form" onSubmit="return valida_form()">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho">
        <?=$oTranslator->_('Detalhes da Subrede');?> <? echo mysql_result($result, 0, 'id_ip_rede'); ?>
      </td>
  </tr>
  <tr> 
    <td class="descricao">
      <?=$oTranslator->_('As opcoes abaixo determinam qual sera o comportamento dos agentes do');?> CACIC
    </td>
  </tr>
</table>

<?php formButtons()?>

<table width="70%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 

        <table width="90%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><?=$oTranslator->_('Local');?></td>
            <td class="label" colspan="2"><br>
            Servidor para Autentica&ccedil;&atilde;o:</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <select name="frm_id_local" id="frm_id_local"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"  
			<?
			echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'');
			?>
			>
                <?
			$qry_locais = "SELECT 	id_local,
									sg_local 
						   FROM 	locais 
						   ORDER BY	sg_local";

			if ($_SESSION['te_locais_secundarios']<>'')
				{
				// Faço uma inserção de "(" para ajuste da lógica para consulta
				$qry_locais = str_replace('locais','locais WHERE (locais.id_local = '.$_SESSION["id_local"].' OR locais.id_local in('.$_SESSION['te_locais_secundarios'].')) ',$qry_locais);
				}

		    $result_locais = mysql_query($qry_locais) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('locais')));
		if (mysql_result($result, 0, 'nm_local')=='')
			echo "<option value='-1' selected>".$oTranslator->_('Selecione Local')."</option>";
		$id_local_anterior = 0;							
		while ($row=mysql_fetch_array($result_locais))
			{ 
			echo "<option value=\"" . $row["id_local"] . "\"";
			if ($_GET['id_local'] == $row["id_local"])
				{
				$id_local_anterior = $row["id_local"];
				echo " selected";
				}
			echo ">" . $row["sg_local"] . "</option>";
		   	} 
			?>
              </select> 
			  <input name="id_local_anterior"  type="hidden" id="id_local_anterior" value="<? echo $id_local_anterior; ?>">
			  <input name="id_local"  type="hidden" id="id_local" value="<? echo $_GET['id_local']; ?>">            </td>
            <td nowrap><select name="frm_id_servidor_autenticacao" id="frm_id_servidor_autenticacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" selected></option>
                <?
			  
		$qry_servidor_autenticacao = "SELECT 		id_servidor_autenticacao, 
									nm_servidor_autenticacao
						FROM 		servidores_autenticacao
						ORDER BY	nm_servidor_autenticacao";

		$result_servidor_autenticacao = mysql_query($qry_servidor_autenticacao) or die ('Falha na consulta &agrave; tabela Servidores de Autenticação ou sua sess&atilde;o expirou!');
			  
				while($row = mysql_fetch_array($result_servidor_autenticacao))
					echo '<option value="'.$row['id_servidor_autenticacao'].'" '.(mysql_result($result, 0, 'id_servidor_autenticacao')==$row['id_servidor_autenticacao']?'selected':'').'>'.$row['nm_servidor_autenticacao'].'</option>';
					
					?>
            </select></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><?=$oTranslator->_('Subrede');?></td>
            <td class="label"><?=$oTranslator->_('Mascara');?></td>
            <td class="label"><?=$oTranslator->_('Abrangencia');?></td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_id_ip_rede" id="frm_id_ip_rede" readonly type="text" value="<? echo mysql_result($result, 0, 'id_ip_rede'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <input name="id_ip_rede"  type="hidden" id="id_ip_rede" value="<? echo mysql_result($result, 0, 'id_ip_rede'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_te_mascara_rede" type="text" id="frm_te_mascara_rede" value="<? echo mysql_result($result, 0, 'te_mascara_rede'); ?>" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="return VerRedeMascara(this.form.name,false,true);SetaClassNormal(this);" >            </td>
            <td nowrap="nowrap"><input name="frm_id_ip_inicio" id="frm_id_ip_inicio" disabled="disabled" type="text" class="normal">
								&nbsp;a&nbsp;
								<input name="frm_id_ip_fim" id="frm_id_ip_fim" disabled="disabled" type="text" class="normal">			</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><?=$oTranslator->_('Descricao');?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td nowrap><input name="frm_nm_rede" type="text" id="frm_nm_rede" value="<? echo mysql_result($result, 0, 'nm_rede'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >			</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td nowrap class="label"><?=$oTranslator->_('Servidor de aplicacao (gerente)');?>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td width="50" rowspan="10" align="center" nowrap><font color="#000000"> 
              <? 
			$intStatusFTP_AGENTE = CheckFtpLogin(mysql_result($result, 0, 'te_serv_updates'),
    							  		  		 mysql_result($result, 0, 'nm_usuario_login_serv_updates'),
							  			  		 mysql_result($result, 0, 'te_senha_login_serv_updates'),
							  			  		 mysql_result($result, 0, 'nu_porta_serv_updates'));
			if ($intStatusFTP_AGENTE == 1)
				{
		  		$v_conexao_ftp_AGENTE = conecta_ftp(mysql_result($result, 0, 'te_serv_updates'),
				                      			    mysql_result($result, 0, 'nm_usuario_login_serv_updates'),
											 		mysql_result($result, 0, 'te_senha_login_serv_updates'),
											 		mysql_result($result, 0, 'nu_porta_serv_updates'),
													false
											       );
				}
			  
			$intStatusFTP_GERENTE = CheckFtpLogin(mysql_result($result, 0, 'te_serv_updates'),
    							  		  		  mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'),
							  			  		  mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'),
							  			  		  mysql_result($result, 0, 'nu_porta_serv_updates'));
			if ($intStatusFTP_GERENTE == 1)
				{
		  		$v_conexao_ftp_GERENTE = conecta_ftp(mysql_result($result, 0, 'te_serv_updates'),
				                      			     mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'),
											 		 mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'),
											 		 mysql_result($result, 0, 'nu_porta_serv_updates'),
													 false
											        );
				}
			// Como são 4 campos críticos para a conexão FTP, separo 2 conjuntos de variáveis para classificação de erro
			$v_classe_campo_user_pass_AGENTE  = "normal";									
			$v_classe_campo_user_pass_GERENTE = "normal";							
			$v_classe_campo_path  			  = "normal";										
					
			if ($v_conexao_ftp_AGENTE && (@ftp_chdir($v_conexao_ftp_AGENTE,mysql_result($result, 0, 'te_path_serv_updates').'/')) &&
			    $v_conexao_ftp_GERENTE && (@ftp_chdir($v_conexao_ftp_GERENTE,mysql_result($result, 0, 'te_path_serv_updates').'/')))			
				{
				echo '<img src="../../imgs/comp.gif" height="55" width="55">';
				?>
              	</font>
				<br>
              	<span class="style7"><?=$oTranslator->_('OK');?></span>
              	<span class="style9"><?=$oTranslator->_('Sucesso na conexao FTP');?></span>
				<?							
				}
			else
				{
				echo '<img src="../../imgs/ftp_desconectado.gif" height="55" width="55">';

				if ($intStatusFTP_AGENTE <> 1)
					$v_classe_campo_user_pass_AGENTE = "anormal";												
				elseif (!@ftp_chdir($v_conexao_ftp_AGENTE,mysql_result($result, 0, 'te_path_serv_updates').'/'))					
					$v_classe_campo_path  			  = "anormal";														

				if ($intStatusFTP_GERENTE <> 1)
					$v_classe_campo_user_pass_GERENTE = "anormal";												
				elseif (!@ftp_chdir($v_conexao_ftp_GERENTE,mysql_result($result, 0, 'te_path_serv_updates').'/'))					
					$v_classe_campo_path  			  = "anormal";														
					
				?>
				<br>
              	</font>
              	<span class="style4"><?=$oTranslator->_('Atencao');?></span>
              	<font color="#000000"><br></font>
              	<span class="style5"><?=$oTranslator->_('Verifique os campos destacados em amarelo');?></span>
				<?			
				}
		  ?>
          </font>          </td>
          <td nowrap>
              <input name="frm_te_serv_cacic" type="text" id="frm_te_serv_cacic" value="<? echo mysql_result($result, 0, 'te_serv_cacic'); ?>" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >&nbsp;<font color="#000099" size="1">Ex.: 10.71.0.204/cacic26b2</font> 
              <select name="sel_te_serv_cacic" id="sel_te_serv_cacic" onChange="SetaServidorBancoDados();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value=""><?=$oTranslator->_('--- Selecione ---');?></option>
                <?
			Conecta_bd_cacic();
			$query = "SELECT DISTINCT 	configuracoes_locais.te_serv_cacic_padrao, 
										redes.te_serv_cacic
			          FROM   			redes LEFT JOIN configuracoes_locais on (configuracoes_locais.te_serv_cacic_padrao = redes.te_serv_cacic)
					  WHERE  			redes.id_local = ".$_REQUEST['id_local']." AND					  				     
					  					configuracoes_locais.id_local = redes.id_local
					  ORDER  BY 		configuracoes_locais.te_serv_cacic_padrao";
			mysql_query($query);
		    $sql_result=mysql_query($query);			
		while ($row=mysql_fetch_array($sql_result))
			{ 
			echo "<option value=\"" . $row["te_serv_cacic"] . "\"";
			echo ">" . $row["te_serv_cacic"] . "</option>";
		   	} 
			?>
              </select></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr> 
            <td class="label"><?=$oTranslator->_('Servidor de Atualizacoes (FTP)');?></td>
            <td nowrap class="label"><?=$oTranslator->_('Porta');?></td>
            <td nowrap class="label"><?=$oTranslator->_('Limite de conexoes FTP');?></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>
              <input name="frm_te_serv_updates" type="text" id="frm_te_serv_updates" value="<? echo mysql_result($result, 0, 'te_serv_updates'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <select name="sel_te_serv_updates" id="sel_te_serv_updates" onChange="SetaServidorUpdates();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value=""><?=$oTranslator->_('--- Selecione ---');?></option>
                <?
			Conecta_bd_cacic();
			$query = "SELECT DISTINCT 	redes.te_serv_updates, 
										redes.te_path_serv_updates,
										redes.nm_usuario_login_serv_updates,
										redes.nu_porta_serv_updates,
										redes.nu_limite_ftp
			          FROM   redes
					  WHERE  redes.id_local = ".$_REQUEST['id_local']. "  
					  ORDER  BY redes.te_serv_updates";
			mysql_query($query);
		    $sql_result=mysql_query($query);			
		while ($row=mysql_fetch_array($sql_result))
			{ 
			echo "<option value=\"" . 
			$row["te_serv_updates"] . '#' . 
			$row["nu_porta_serv_updates"] . '#' . 
			$row["nm_usuario_login_serv_updates"] . '#' . 			
			$row["te_path_serv_updates"] . '#' .
			$row["nu_limite_ftp"] . "\"";												
			echo ">" . $row["te_serv_updates"] . "</option>";
		   	} 			
			?>
            </select>            </td>
            <td><input name="frm_nu_porta_serv_updates" type="text" id="frm_nu_porta_serv_updates" value="<? echo mysql_result($result, 0, 'nu_porta_serv_updates'); ?>" size="15" maxlength="4" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
            <td><input name="frm_nu_limite_ftp" type="text" id="frm_nu_limite_ftp" value="<? echo mysql_result($result, 0, 'nu_limite_ftp'); ?>" size="5" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr> 
            <td nowrap class="label"><?=$oTranslator->_('Caminho no servidor de atualizacoes');?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>
               <input name="frm_te_path_serv_updates" type="text" id="frm_te_path_serv_updates" value="<? echo mysql_result($result, 0, 'te_path_serv_updates'); ?>" size="50" maxlength="100" class="<? echo $v_classe_campo_path;?>" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap align="center" colspan="3" class="label"><?=$oTranslator->_('Conteudo do servidor de atualizacoes');?></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap colspan="3">
              <table border="1" align="center" cellpadding="2" bordercolor="#999999"><font style="bold" size="1" face="Verdana">
                <tr bgcolor="#FFFFCC"> 
                  <td bgcolor="#EBEBEB">&nbsp;</td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Arquivo');?></td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Tamanho (KB)');?></td>
                  <td colspan="3" align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?=$oTranslator->_('Versao');?></td>
                </tr>
                <? 
				if ($v_conexao_ftp_GERENTE)
					{
					
					echo lista_updates(mysql_result($result, 0, 'te_serv_updates'),
		 							   mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'),
									   mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'),
									   mysql_result($result, 0, 'nu_porta_serv_updates'),
									   mysql_result($result, 0, 'te_path_serv_updates').'/'); 
															  

				 
				 }?>
              </table>
              <br>              </td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td colspan="4" nowrap class="label">
                <?=$oTranslator->_('Servidor de Atualizacoes (FTP)') ." <i> ". $oTranslator->_('para acesso do agente');?></i>            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap class="label">
              <?=$oTranslator->_('Usuario');?>            </td>
            <td nowrap class="label">
              <?=$oTranslator->_('Senha');?>            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>
              <input name="frm_nm_usuario_login_serv_updates" type="text" id="frm_nm_usuario_login_serv_updates" value="<? echo mysql_result($result, 0, 'nm_usuario_login_serv_updates'); ?>"  size="20" maxlength="20" class="<? echo $v_classe_campo_user_pass_AGENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_senha_login_serv_updates" type="password" id="frm_te_senha_login_serv_updates" value="**********"  size="15" maxlength="15" class="<? echo $v_classe_campo_user_pass_AGENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
				<?
				$_SESSION['te_senha_login_serv_updates'] = mysql_result($result, 0, 'te_senha_login_serv_updates'); 
				$_SESSION['te_senha_login_serv_updates_gerente'] = mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'); 			
				?>            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td colspan="4" nowrap class="label">
                <?=$oTranslator->_('Servidor de Atualizacoes (FTP)') ." <i> ". $oTranslator->_('para acesso do gerente');?></i>            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap class="label">
              <?=$oTranslator->_('Usuario');?>            </td>
            <td nowrap class="label">
              <?=$oTranslator->_('Senha');?>            </td>
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>
              <input name="frm_nm_usuario_login_serv_updates_gerente" type="text" id="frm_nm_usuario_login_serv_updates_gerente" value="<? echo mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'); ?>"  size="20" maxlength="20" class="<? echo $v_classe_campo_user_pass_GERENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_senha_login_serv_updates_gerente" type="password" id="frm_te_senha_login_serv_updates_gerente" value="**********"  size="15" maxlength="15" class="<? echo $v_classe_campo_user_pass_GERENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label">
               <?=$oTranslator->_('Observacoes');?>            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>
               <textarea name="frm_te_observacao" cols="38" id="textarea"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                 <? echo mysql_result($result, 0, 'te_observacao'); ?>
               </textarea>            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label">
               <?=$oTranslator->_('Contatos para a subrede');?>            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label">
               <?=$oTranslator->_('Contato'). ' '.$oTranslator->_('um');?>            </td>
            <td class="label">
              <?=$oTranslator->_('Telefone');?>            </td>
            <td class="label">
              <?=$oTranslator->_('Endereco eletronico');?>            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>
              <input name="frm_nm_pessoa_contato1" type="text" id="frm_nm_pessoa_contato12" value="<? echo mysql_result($result, 0, 'nm_pessoa_contato1'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_nu_telefone1" type="text" id="frm_nu_telefone12" value="<? echo mysql_result($result, 0, 'nu_telefone1'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_email_contato1" type="text" id="frm_te_email_contato12" value="<? echo mysql_result($result, 0, 'te_email_contato1'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label">
               <?=$oTranslator->_('Contato'). ' '.$oTranslator->_('dois');?>            </td>
            <td class="label">
              <?=$oTranslator->_('Telefone');?>            </td>
            <td class="label">
              <?=$oTranslator->_('Endereco eletronico');?>            </td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_nm_pessoa_contato2" type="text" id="frm_nm_pessoa_contato2" value="<? echo mysql_result($result, 0, 'nm_pessoa_contato2'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_nu_telefone2" type="text" id="frm_nu_telefone22" value="<? echo mysql_result($result, 0, 'nu_telefone2'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_te_email_contato2" type="text" id="frm_te_email_contato2" value="<? echo mysql_result($result, 0, 'te_email_contato2'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
          </tr>
          <tr> 
            <td colspan="4">&nbsp;</td>
          </tr>
          <tr>
			<td></td>          
            <td colspan="3" class="label">Permitir finaliza&ccedil;&atilde;o de sess&otilde;es do m&oacute;dulo srCACIC:</td>
          </tr>
    		<tr> 
      		<td colspan="4" height="1" bgcolor="#333333"></td>
    		</tr>          
          <tr>
		<td>&nbsp;</td>
      <td colspan="3" class="descricao"><div align="justify"> Essa op&ccedil;&atilde;o 
          define se o usu&aacute;rio final poder&aacute; ou n&atilde;o finalizar execu&ccedil;&otilde;es do m&oacute;dulo srCACIC (Suporte Remoto Seguro). Caso seja marcado &quot;N&atilde;o&quot;, a finalização não será possível de modo interativo, através do menu de contexto do Agente Principal (&iacute;ndio da bandeja).</div></td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
        <tr> 
        <td>&nbsp;</td>
          <td> <input name="frm_cs_permitir_desativar_srcacic" type="radio" value="S" checked class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo (mysql_result($result, 0, 'cs_permitir_desativar_srcacic')=='S'?'checked':''); ?>>Sim<br>
          <input type="radio" name="frm_cs_permitir_desativar_srcacic" value="N" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo (mysql_result($result, 0, 'cs_permitir_desativar_srcacic')=='N'?'checked':'');?>>N&atilde;o</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
          <tr> 
            <td></td>
          </tr>
          <tr> 
            <td></td>
          </tr>
          
          <tr> 
            <td>&nbsp;</td>
            <td class="label">
	            <?=$oTranslator->_('Acoes selecionadas para essa rede:');?>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
                
	            <br>
        <?
			$query_acoes_redes = "	SELECT	te_descricao_breve 
									FROM 	acoes,
											acoes_redes ac_re 
									WHERE 	acoes.id_acao = ac_re.id_acao and
											ac_re.id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
											ac_re.id_local = ".(int) $_REQUEST['id_local'];
	
			conecta_bd_cacic();
			$result_acoes_redes = mysql_query($query_acoes_redes);
			if (!mysql_num_rows($result_acoes_redes)>0)		
				echo '<br><span class="necessario">'.$oTranslator->_('Nenhuma acao selecionada para essa rede!').'</span>';
		?>		    </td>
            <td></td>
          </tr>
		<?
			$v_contador = 0;
			while ($row = mysql_fetch_array($result_acoes_redes))
			{
				$v_contador ++;
				?>
          <tr> 
            <td></td>
	        <td colspan="3" nowrap class="descricao">
	           <? echo $v_contador . ')&nbsp;' . $row['te_descricao_breve']; ?>	        </td>
          </tr>
             <?
            }
             ?>
          <tr> 
            <td height="1" colspan="4" bgcolor="#333333"></td>
          </tr>
			<?
			include "../../include/opcoes_sistemas_monitorados.php";
			?>
		  
          <tr> 
            <td height="1" colspan="4" bgcolor="#333333"></td>
          </tr>
		  
		<?	
		if ($_POST['VerificaUpdates']) 
		{		  
		?>
          <table>
            <tr> 
              <td> 
                <?
			if ($_SESSION['v_efetua_conexao_ftp'] > 0)
				{	
				echo '<font color="#000099" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Verificação Efetuada!</strong></font>';
							
				if ($_SESSION['v_conta_objetos_atualizados'] > 0)
					{
					$v_array_objetos_atualizados = explode('#',$_SESSION['v_tripa_objetos_atualizados']);
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_atualizados']; $cnt_objetos++)
						{
						?>
            <tr> 
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left"><?=$oTranslator->_('Atualizando');?> 
                  <? echo $v_array_objetos_atualizados[$cnt_objetos];?>... 
                  <?					
						}						
					}
				if ($_SESSION['v_conta_objetos_nao_atualizados'] > 0)
					{
					$v_array_objetos_nao_atualizados = explode('#',$_SESSION['v_tripa_objetos_nao_atualizados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_nao_atualizados']; $cnt_objetos++) 					
						{

						?>
            <tr> 
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">
                  <?=$oTranslator->_('Nao atualizado');?> <? echo $v_array_objetos_nao_atualizados[$cnt_objetos];?> 
                  <?					
						}						
					}
				if ($_SESSION['v_conta_objetos_enviados'] > 0)
					{
					$v_array_objetos_enviados = explode('#',$_SESSION['v_tripa_objetos_enviados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_enviados']; $cnt_objetos++) 					
						{
						?>
            <tr> 
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">
                  <?=$oTranslator->_('Enviando...');?> <? echo $v_array_objetos_enviados[$cnt_objetos];?> 
                  <?					
						}						
					 }
				if ($_SESSION['v_conta_objetos_nao_enviados'] > 0)
					{
					$v_array_objetos_nao_enviados = explode('#',$_SESSION['v_tripa_objetos_nao_enviados']);					
					for ($cnt_objetos = 0; $cnt_objetos < $_SESSION['v_conta_objetos_nao_enviados']; $cnt_objetos++) 					
						{
						?>
            <tr> 
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">
                  <?=$oTranslator->_('Nao enviado');?> <? echo $v_array_objetos_nao_enviados[$cnt_objetos];?> 
                  <?					
						}						
					}										
				}									
			else if($_SESSION['v_status_conexao'] == 'NC')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>'.$oTranslator->_('FTP nao configurado').'</strong></a></font>';					
				}
			else if($_SESSION['v_status_conexao'] == 'OFF')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>'.$oTranslator->_('Servidor nao encontrado').'</strong></a></font>';															
				}

		?>
              </td>
            </tr>
            <?
		}
		?>
          </table>
        </table>  
</table>
<script language="javascript" type="text/javascript">
VerRedeMascara('form',true,false);
</script>
<?php formButtons()?>
</form>
</body>
</html>
<?
}
?>
