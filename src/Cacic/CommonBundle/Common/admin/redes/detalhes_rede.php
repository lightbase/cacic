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

AntiSpy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiRede']) 
	{
	$query = "DELETE 
			  FROM 		redes 
			  WHERE 	id_rede = ".$_REQUEST['id_rede']." AND
			  			id_local = ".$_REQUEST['id_local_anterior'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('redes')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'redes',$_SESSION["id_usuario"]);				
	$query = "DELETE 	
			  FROM 		acoes_redes 
			  WHERE 	id_rede = ".$_REQUEST['id_rede'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('acoes_redes')));	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes',$_SESSION["id_usuario"]);				

	$query = "DELETE 	
			  FROM 		aplicativos_redes 
			  WHERE 	id_rede = ".$_REQUEST['id_rede'];
	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes',$_SESSION["id_usuario"]);				
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 				
	}
elseif ($_POST['GravaAlteracoes']) 
	{
	$boolJaExiste = false;
	if ($_POST['frm_id_local'] <> $_REQUEST['id_local_anterior'])
		{
		$query = "SELECT 	* 
				  FROM 		redes 
				  WHERE 	id_rede = ".$_POST['id_rede'];
						
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
				  WHERE 	id_rede = ".$_REQUEST['id_rede']." AND
							id_local = ".$_REQUEST['id_local_anterior'];
	
		mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('redes')));
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes',$_SESSION["id_usuario"]);
						
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
					  WHERE 	id_rede = ".$_REQUEST['id_rede'];
			mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('aplicativos_redes')));	
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes',$_SESSION["id_usuario"]);						
			seta_perfis_rede($_REQUEST['id_rede'], $v_perfis); 					
			}		
			
		header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 					
		}
	}
else 
	{
	$query = "	SELECT 	* 
				FROM 	redes						
				WHERE 	redes.id_rede = ".$_GET['id_rede'];		
	$result = mysql_query($query) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes')));
	?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language=JavaScript>
<!--
function fixItemVersionAndHash(oObj)
	{
	if ($(oObj).hasClass("item_error"))
		{
		var arrDados  = oObj.name.split('_');
		var intIdRede = arrDados[1];
		var strNmItem = arrDados[2];

 		$.get("../update_subrede/processa_update_subrede.php",
			   {pIntIdRede:intIdRede,
				pStrNmItem:strNmItem},
			   function(pStrRetornoUpdateSubrede)
					{
					// pStrRetornoUpdateSubrede deverá conter uma string contendo informações sobre o ítem trabalhado e o status final
					// Esses valores estarão separados por _=_
					var arrRetornoUpdateSubrede = pStrRetornoUpdateSubrede.split("_=_");

					if (arrRetornoUpdateSubrede[3] == 'Ok!')
						{
						var oFrmTeValidVersionTo	= returnObjById("frmTeValidVersionTo_" 	+ arrRetornoUpdateSubrede[1]);
						var oFrmTeValidHashTo		= returnObjById("frmTeValidHashTo_"  	+ arrRetornoUpdateSubrede[1]);				
						var oFrmTdVersionOf			= returnObjById("frmTdVersionOf_" 	 	+ arrRetornoUpdateSubrede[1]);
						var oFrmTdHashOf			= returnObjById("frmTdHashOf_" 	 	 	+ arrRetornoUpdateSubrede[1]);				
						var oFrmImg					= returnObjById("frmImg_" 	 	 	 	+ intIdRede + '_' + strNmItem);										

						$(oFrmTdVersionOf).html($(oFrmTeValidVersionTo).val());
						$(oFrmTdHashOf).html($(oFrmTeValidHashTo).val());

						$(oFrmTdVersionOf).addClass('dado_peq_sem_fundo').removeClass('dado_peq_sem_fundo_vermelho');																						
						$(oFrmTdHashOf).addClass('dado_peq_sem_fundo').removeClass('dado_peq_sem_fundo_vermelho');																												
						
						$(oFrmImg).attr('src','../../imgs/item_tick.png');						
						$(oFrmImg).attr('title','');												
						
						oFrmTeValidVersionTo 	= null;
						oFrmTdVersionOf 	   	= null;						
						oFrmTdHashOf 	   	   	= null;												
						oFrmImg 	   	   	   	= null;																		
						}
					});	
		}
	}		
/*	
function desabilitar()
	{
    return false
	}
document.oncontextmenu=desabilitar
*/
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
		alert("<?php echo $oTranslator->_('O local da rede e obrigatorio');?>");
		document.form.frm_id_local.focus();
		return false;
		}
		
	if ( document.form.frm_nm_rede.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('O local da rede e obrigatorio');?>Digite o nome da rede");
		document.form.frm_nm_rede.focus();
		return false;
		}
	else if ( document.form.frm_te_serv_cacic.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite o Identificador do ServidorAutenticacao de Banco de Dados');?>");
		document.form.frm_te_serv_cacic.focus();
		return false;
		}	
	else if ( document.form.frm_te_serv_updates.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite o Identificador do ServidorAutenticacao de Atualizacoes');?>");
		document.form.frm_te_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nu_porta_serv_updates.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite a Porta FTP do ServidorAutenticacao de Atualizacoes');?>");
		document.form.frm_nu_porta_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite o Nome do Usuario para Login no ServidorAutenticacao de Atualizacoes pelo Modulo Agente');?>");
		document.form.frm_nm_usuario_login_serv_updates.focus();
		return false;
		}		
	else if ( document.form.frm_nm_usuario_login_serv_updates_gerente.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite o Nome do Usuario para Login no ServidorAutenticacao de Atualizacoes pelo Modulo Gerente');?>");
		document.form.frm_nm_usuario_login_serv_updates_gerente.focus();
		return false;
		}			
	else if ( document.form.frm_te_path_serv_updates.value == "" ) 
		{	
		alert("<?php echo $oTranslator->_('Digite o caminho no ServidorAutenticacao de Atualizacoes');?>");
		document.form.frm_te_path_serv_updates.focus();
		return false;
		}		
	return true;
	}
</script>
<script language="javascript" type="text/javascript" src="../../include/js/cacic.js"></script>
<script language="JavaScript" type="text/javascript" src="../../include/js/jquery.js"></script>
<script language="JavaScript" type="text/javascript" src="../../include/js/ajax/common.js"></script>	            
<script language="JavaScript" type="text/javascript" src="../../include/js/ajax/test_serv_updates.js"></script>	            

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
            <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="<?php echo $oTranslator->_('Gravar alteracoes');?>" onClick="return Confirma('Confirma Informações para Esta Rede?'); " <?php echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="ExcluiRede" type="submit" value="<?php echo $oTranslator->_('Excluir rede');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma exclusao de Rede');?>');" <?php echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            <?php if ($_REQUEST['nm_chamador']=='locais')
				{
				?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="RetornaLocais" type="button" value="<?php echo $oTranslator->_('Retorna aos detalhes de Local');?>" onClick="history.back()">
            <?php
				}
				?>
          </p>
<?php }?>
<body <?php if (!$pos) echo 'background="../../imgs/linha_v.gif"';?> onLoad="SetaCampo('<?php echo ($_SESSION['cs_nivel_administracao']<>1?'frm_te_mascara_rede':'frm_id_local')?>')">

<form action="detalhes_rede.php"  method="post" ENCTYPE="multipart/form-data" name="form" id="form" onSubmit="return valida_form()">
<table width="85%" border="0" align="center">
  <tr> 
      <td class="cabecalho">
        <?php echo $oTranslator->_('Detalhes da Subrede');?> <?php echo mysql_result($result, 0, 'te_ip_rede'); ?>
      </td>
  </tr>
  <tr> 
    <td class="descricao">
      <?php echo $oTranslator->_('As opcoes abaixo determinam qual sera o comportamento dos agentes do');?> CACIC
    </td>
  </tr>
</table>

<?php formButtons()?>

<table width="70%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 

        <table width="85%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br><?php echo $oTranslator->_('Local');?>:</td>
            <td class="label" colspan="2"><br><?php echo $oTranslator->_('ServidorAutenticacao para Autentica&ccedil;&atilde;o');?>:</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <select name="frm_id_local" id="frm_id_local"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"  
			<?php echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'');
			?>
			>
                <?php
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
			  <input name="id_local_anterior"  type="hidden" id="id_local_anterior" value="<?php echo $id_local_anterior; ?>">
			  <input name="id_local"  type="hidden" id="id_local" value="<?php echo $_GET['id_local']; ?>">            </td>
            <td nowrap><select name="frm_id_servidor_autenticacao" id="frm_id_servidor_autenticacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="0" selected></option>
                <?php
			  
		$qry_servidor_autenticacao = "SELECT 		id_servidor_autenticacao, 
									nm_servidor_autenticacao
						FROM 		servidores_autenticacao
						ORDER BY	nm_servidor_autenticacao";

		$result_servidor_autenticacao = mysql_query($qry_servidor_autenticacao) or die ('Falha na consulta &agrave; tabela Servidores de Autenticação ou sua sess&atilde;o expirou!');
			  
				while($row = mysql_fetch_array($result_servidor_autenticacao))
					echo '<option value="'.$row['id_servidor_autenticacao'].'" '.(mysql_result($result, 0, 'id_servidor_autenticacao')==$row['id_servidor_autenticacao']?'selected':'').'>'.$row['nm_servidor_autenticacao'].'</option>';
					
					?>
            </select></td>
          </tr>
          <tr> 
            <td class="label"><br><?php echo $oTranslator->_('Subrede');?></td>
            <td class="label"><?php echo $oTranslator->_('Mascara');?></td>
            <td class="label"><?php echo $oTranslator->_('Abrangencia');?></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td> <input name="frm_te_ip_rede" id="frm_te_ip_rede" readonly type="text" value="<?php echo mysql_result($result, 0, 'te_ip_rede'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <input name="id_rede"  type="hidden" id="id_rede" value="<?php echo mysql_result($result, 0, 'id_rede'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_te_mascara_rede" type="text" id="frm_te_mascara_rede" value="<?php echo mysql_result($result, 0, 'te_mascara_rede'); ?>" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="return VerRedeMascara(this.form.name,false,true);SetaClassNormal(this);" >            </td>
            <td nowrap="nowrap"><input name="frm_id_ip_inicio" id="frm_id_ip_inicio" disabled="disabled" type="text" class="normal">
								&nbsp;a&nbsp;
								<input name="frm_id_ip_fim" id="frm_id_ip_fim" disabled="disabled" type="text" class="normal">			</td>
          </tr>
          <tr> 
            <td class="label"><br><?php echo $oTranslator->_('Descricao');?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap><input name="frm_nm_rede" type="text" id="frm_nm_rede" value="<?php echo mysql_result($result, 0, 'nm_rede'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >			</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap class="label"><br><?php echo $oTranslator->_('ServidorAutenticacao de aplicacao (gerente)');?>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
          <td nowrap>
              <input name="frm_te_serv_cacic" type="text" id="frm_te_serv_cacic" value="<?php echo mysql_result($result, 0, 'te_serv_cacic'); ?>" size="60" maxlength="60" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >&nbsp;<font color="#000099" size="1"></font> 
              <select name="sel_te_serv_cacic" id="sel_te_serv_cacic" onChange="SetaServidorBancoDados();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value=""><?php echo $oTranslator->_('--- Selecione ---');?></option>
                <?php
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
            <td class="label"><br><?php echo $oTranslator->_('ServidorAutenticacao de Atualizacoes (FTP)');?><div id="divMsgTeServUpdates" name="divMsgTeServUpdates"></div></td>
            <td nowrap class="label"><?php echo $oTranslator->_('Porta');?></td>
            <td nowrap class="label"><?php echo $oTranslator->_('Limite de conexoes FTP');?></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>
              <input name="frm_te_serv_updates" type="text" id="frm_te_serv_updates" value="<?php echo mysql_result($result, 0, 'te_serv_updates'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" >               
              <select name="sel_te_serv_updates" id="sel_te_serv_updates" onChange="SetaServidorUpdates();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value=""><?php echo $oTranslator->_('--- Selecione ---');?></option>
                <?php
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
            <td><input name="frm_nu_porta_serv_updates" type="text" id="frm_nu_porta_serv_updates" value="<?php echo mysql_result($result, 0, 'nu_porta_serv_updates'); ?>" size="15" maxlength="4" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" ></td>
            <td><input name="frm_nu_limite_ftp" type="text" id="frm_nu_limite_ftp" value="<?php echo mysql_result($result, 0, 'nu_limite_ftp'); ?>" size="5" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr> 
            <td nowrap class="label"><br><?php echo $oTranslator->_('Caminho no servidor de atualizacoes');?>:<div id="divMsgTePathServUpdates" name="divMsgTePathServUpdates"></div></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>
               <input name="frm_te_path_serv_updates" type="text" id="frm_te_path_serv_updates" value="<?php echo mysql_result($result, 0, 'te_path_serv_updates'); ?>" size="50" maxlength="100" class="<?php echo $v_classe_campo_path;?>" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" >
               </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr> 
            <td nowrap class="label"><br><?php echo $oTranslator->_('Usuario do ServidorAutenticacao de Updates: (para AGENTES)');?><div id="divMsgNmUsuarioLoginServUpdates" name="divMsgNmUsuarioLoginServUpdates"></div></td>
            <td nowrap class="label"><?php echo $oTranslator->_('Senha');?><div id="divMsgTeSenhaLoginServUpdates" name="divMsgTeSenhaLoginServUpdates"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>
              <input name="frm_nm_usuario_login_serv_updates" type="text" id="frm_nm_usuario_login_serv_updates" value="<?php echo mysql_result($result, 0, 'nm_usuario_login_serv_updates'); ?>"  size="20" maxlength="20" class="<?php echo $v_classe_campo_user_pass_AGENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_senha_login_serv_updates" type="password" id="frm_te_senha_login_serv_updates" value="<?php echo mysql_result($result, 0, 'te_senha_login_serv_updates'); ?>"  size="15" maxlength="15" class="<?php echo $v_classe_campo_user_pass_AGENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" > 
				<?php
				$_SESSION['te_senha_login_serv_updates'] = mysql_result($result, 0, 'te_senha_login_serv_updates'); 
				$_SESSION['te_senha_login_serv_updates_gerente'] = mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'); 			
				?>            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap class="label"><br><?php echo $oTranslator->_('Usuario do ServidorAutenticacao de Updates: (para GERENTE)');?><div id="divMsgNmUsuarioLoginServUpdatesGerente" name="divMsgNmUsuarioLoginServUpdatesGerente"></div></td>
            <td nowrap class="label"><?php echo $oTranslator->_('Senha');?><div id="divMsgTeSenhaLoginServUpdatesGerente" name="divMsgTeSenhaLoginServUpdatesGerente"></div></td>
            <td nowrap class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td>
              <input name="frm_nm_usuario_login_serv_updates_gerente" type="text" id="frm_nm_usuario_login_serv_updates_gerente" value="<?php echo mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'); ?>"  size="20" maxlength="20" class="<?php echo $v_classe_campo_user_pass_GERENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_senha_login_serv_updates_gerente" type="password" id="frm_te_senha_login_serv_updates_gerente" value="<?php echo mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'); ?>"  size="15" maxlength="15" class="<?php echo $v_classe_campo_user_pass_GERENTE;?>" onFocus="SetaClassDigitacao(this);" onBlur="return testServUpdates(); SetaClassNormal(this);" >            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          
          <tr> 
            <td nowrap align="center" colspan="3" class="label"><br><?php echo $oTranslator->_('Comparativo entre Itens do Repositorio Central e Itens Enviados');?></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap colspan="3">
              <table border="0" align="center" cellpadding="2" cellspacing="2" bordercolor="#999999">
                <tr bgcolor="#FFFFCC"> 
                  <td bgcolor="#EBEBEB">&nbsp;</td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela" align="left"					  ><?php echo $oTranslator->_('Item no Repositorio Central');?></td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela" align="center"					  ><?php echo $oTranslator->_('Versao Enviada');?></td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela" align="center"					  ><?php echo $oTranslator->_('Versao Atual');?></td>                  
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela" align="center" colspan="3" nowrap><?php echo $oTranslator->_('Hash-Code Enviado');?></td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela" align="center" colspan="4" nowrap><?php echo $oTranslator->_('Hash-Code Atual');?></td>                  
                </tr>
                <?php 

				conecta_bd_cacic();
				$querySEL 		= 'SELECT 	nm_modulo,
											te_versao_modulo,
											te_hash
								   FROM		redes_versoes_modulos
								   WHERE	id_rede = ' . $_GET['id_rede'];
				$resultSEL 		= mysql_query($querySEL);
				
				$arrVersoesEnviadas = array();
				$arrHashsEnviados	= array();				
				while ($rowSEL = mysql_fetch_array($resultSEL))
					{
					$arrVersoesEnviadas[$rowSEL['nm_modulo']] = $rowSEL['te_versao_modulo'];
					$arrHashsEnviados[$rowSEL['nm_modulo']]   = $rowSEL['te_hash'];
					}
				
				if(file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') && is_readable(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))		
					$_SESSION['sessArrVersionsIni'] = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');

								
				$intLoopSEL 		= 1;												
				$intLoopVersionsIni = 0;
				while ($intLoopVersionsIni >= 0)
					{
					$intLoopVersionsIni ++;
					$arrItemDefinitions = explode(',',$_SESSION['sessArrVersionsIni']['Item_' . $intLoopVersionsIni]);
					if (($arrItemDefinitions[0] <> '') && ($arrItemDefinitions[1] <> 'S'))
						{										
						$strItemName = getOnlyFileName(trim($arrItemDefinitions[0]));							
						$boolEqualVersions = ($arrVersoesEnviadas[$strItemName]  == $_SESSION['sessArrVersionsIni'][$strItemName . '_VER'] );
						$boolEqualHashs	   = ($arrHashsEnviados[$strItemName]    == $_SESSION['sessArrVersionsIni'][$strItemName . '_HASH']);
						?>
						<tr>
						<td align="right" 	class="dado_peq_sem_fundo"			  																		 ><?php echo $intLoopSEL;				  ?></td>
						<td align="left"  	class="dado_peq_sem_fundo"			  																		 ><?php echo $strItemName;		  ?></td>
						<td align="center"  class="<?php echo ($boolEqualVersions ? 'dado_peq_sem_fundo' : 'dado_peq_sem_fundo_vermelho');?>"			  id="frmTdVersionOf_<?php echo $strItemName;?>" name="frmTdVersionOf_<?php echo $strItemName;?>"><?php echo $arrVersoesEnviadas[$strItemName];?>						</td>
						<td align="center"  class="dado_peq_sem_fundo"			  																		 ><?php echo $_SESSION['sessArrVersionsIni'][$strItemName.'_VER'];?>	</td>
						<td align="center"  class="<?php echo ($boolEqualHashs    ? 'dado_peq_sem_fundo' : 'dado_peq_sem_fundo_vermelho');?>" colspan="3" id="frmTdHashOf_<?php echo $strItemName;?>" name="frmTdHashOf_<?php echo $strItemName;?>"><?php echo $arrHashsEnviados[$strItemName];?>		 						</td>
						<td align="center"  class="dado_peq_sem_fundo" 																		  colspan="3"><?php echo $_SESSION['sessArrVersionsIni'][$strItemName.'_HASH'];?>	</td>
						<td align="center"																												 ><img height="16" width="16" style="float:center" id="frmImg_<?php echo $_GET['id_rede'].'_'. $strItemName;?>" name="frmImg_<?php echo $_GET['id_rede'].'_'. $strItemName;?>" src="../../imgs/<?php echo ($boolEqualVersions && $boolEqualHashs ? 'item_tick.png" class="item_ok' : 'item_error.png" class="item_error" title="Clique para corrigir a diferença');?>" onClick="fixItemVersionAndHash(this);"></td>
						</tr>
						<input type="hidden" id="frmTeValidVersionTo_<?php echo $strItemName;?>" name="frmTeValidVersionTo_<?php echo $strItemName;?>" value="<?php echo $_SESSION['sessArrVersionsIni'][$strItemName.'_VER'];?>">
						<input type="hidden" id="frmTeValidHashTo_<?php    echo $strItemName;?>" name="frmTeValidHashTo_<?php    echo $strItemName;?>" value="<?php echo $_SESSION['sessArrVersionsIni'][$strItemName.'_HASH'];?>">                                                        
						<?php
						$intLoopSEL++;
						}									
					else
						$intLoopVersionsIni = -1;													
					}
							
				if ($intLoopSEL == 1) 
					{
					?>
					<tr><td colspan="12" align="center" class="Erro">SEM REGISTROS DE ITENS ENVIADOS PARA O REPOSITORIO DA SUBREDE!</td></tr>	
                    <?php
					}
					?>
              </table>
              <br>              </td>
          </tr>
          <tr> 
            <td class="label"><br><?php echo $oTranslator->_('Observacoes');?>            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>
               <textarea name="frm_te_observacao" cols="38" id="textarea"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                 <?php echo mysql_result($result, 0, 'te_observacao'); ?>
               </textarea>            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td colspan="3" class="label">
               <?php echo $oTranslator->_('Contatos para a subrede');?>            </td>
          </tr>
          <tr> 
            <td class="label"><br><?php echo $oTranslator->_('Contato'). ' '.$oTranslator->_('um');?>            </td>
            <td class="label"><?php echo $oTranslator->_('Telefone');?>            </td>
            <td class="label"><?php echo $oTranslator->_('Endereco eletronico');?>            </td>
          </tr>
          <tr> 
            <td>
              <input name="frm_nm_pessoa_contato1" type="text" id="frm_nm_pessoa_contato12" value="<?php echo mysql_result($result, 0, 'nm_pessoa_contato1'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_nu_telefone1" type="text" id="frm_nu_telefone12" value="<?php echo mysql_result($result, 0, 'nu_telefone1'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td>
              <input name="frm_te_email_contato1" type="text" id="frm_te_email_contato12" value="<?php echo mysql_result($result, 0, 'te_email_contato1'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
          </tr>
          <tr> 
            <td class="label"><br><?php echo $oTranslator->_('Contato'). ' '.$oTranslator->_('dois');?>            </td>
            <td class="label"><?php echo $oTranslator->_('Telefone');?>            </td>
            <td class="label"><?php echo $oTranslator->_('Endereco eletronico');?>            </td>
          </tr>
          <tr> 
            <td> <input name="frm_nm_pessoa_contato2" type="text" id="frm_nm_pessoa_contato2" value="<?php echo mysql_result($result, 0, 'nm_pessoa_contato2'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_nu_telefone2" type="text" id="frm_nu_telefone22" value="<?php echo mysql_result($result, 0, 'nu_telefone2'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
            <td> <input name="frm_te_email_contato2" type="text" id="frm_te_email_contato2" value="<?php echo mysql_result($result, 0, 'te_email_contato2'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >            </td>
          </tr>
          <tr> 
            <td colspan="3">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" class="label">Permitir finaliza&ccedil;&atilde;o de sess&otilde;es do m&oacute;dulo srCACIC:</td>
          </tr>
    		<tr> 
      		<td colspan="3" height="1" bgcolor="#333333"></td>
    		</tr>          
          <tr>
      		<td colspan="3" class="descricao"><div align="justify"> Essa op&ccedil;&atilde;o 
          define se o usu&aacute;rio final poder&aacute; ou n&atilde;o finalizar execu&ccedil;&otilde;es do m&oacute;dulo srCACIC (Suporte Remoto Seguro). Caso seja marcado &quot;N&atilde;o&quot;, a finalização não será possível de modo interativo, através do menu de contexto do Agente Principal (&iacute;ndio da bandeja).</div></td>
          </tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
        <tr> 
          <td> <input name="frm_cs_permitir_desativar_srcacic" type="radio" value="S" checked class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo (mysql_result($result, 0, 'cs_permitir_desativar_srcacic')=='S'?'checked':''); ?>>Sim<br>
          <input type="radio" name="frm_cs_permitir_desativar_srcacic" value="N" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo (mysql_result($result, 0, 'cs_permitir_desativar_srcacic')=='N'?'checked':'');?>>N&atilde;o</td>
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
            <td colspan="3" class="label"><br><?php echo $oTranslator->_('Acoes selecionadas para essa rede:');?>
                </td></tr>
          <tr> 
            <td colspan="3" height="1" bgcolor="#333333"></td>
          </tr>
                	            <br>
        <?php
			$query_acoes_redes = "	SELECT	te_descricao_breve 
									FROM 	acoes,
											acoes_redes ac_re 
									WHERE 	acoes.id_acao = ac_re.id_acao and
											ac_re.id_rede = ".$_REQUEST['id_rede'];
	
			conecta_bd_cacic();
			$result_acoes_redes = mysql_query($query_acoes_redes);
			if (!mysql_num_rows($result_acoes_redes)>0)		
				echo '<br><span class="necessario">'.$oTranslator->_('Nenhuma acao selecionada para essa rede!').'</span>';
		?>		    </td>
            <td></td>
          </tr>
		<?php
			$v_contador = 0;
			while ($row = mysql_fetch_array($result_acoes_redes))
			{
				$v_contador ++;
				?>
          <tr> 
	        <td colspan="3" nowrap class="descricao">
	           <?php echo str_pad($v_contador,2,'0',STR_PAD_LEFT) . ')&nbsp;' . $row['te_descricao_breve']; ?>	        </td>
          </tr>
             <?php
            }
             ?>
          <tr> 
            <td height="1" colspan="3" bgcolor="#333333"></td>
          </tr>
          <table align="center">
		<?php
		include "../../include/opcoes_sistemas_monitorados.php";
		?>
          </table>
        </table>  
</table>
<script language="javascript" type="text/javascript">
testServUpdates();
VerRedeMascara('form',true,false);
</script>
<?php formButtons()?>
</form>
</body>
</html>
<?php
}
?>