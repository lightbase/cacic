<?php 
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e InformaÃ§Ãµes da PrevidÃªncia Social, Brasil

 Este arquivo Ã© parte do programa CACIC - Configurador AutomÃ¡tico e Coletor de InformaÃ§Ãµes Computacionais

 O CACIC Ã© um software livre; vocÃª pode redistribui-lo e/ou modifica-lo dentro dos termos da LicenÃ§a PÃºblica Geral GNU como 
 publicada pela FundaÃ§Ã£o do Software Livre (FSF); na versÃ£o 2 da LicenÃ§a, ou (na sua opniÃ£o) qualquer versÃ£o.

 Este programa Ã© distribuido na esperanÃ§a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÃ‡Ã‚O a qualquer
 MERCADO ou APLICAÃ‡ÃƒO EM PARTICULAR. Veja a LicenÃ§a PÃºblica Geral GNU para maiores detalhes.

 VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral GNU, sob o tÃ­tulo "LICENCA.txt", junto com este programa, se nÃ£o, escreva para a FundaÃ§Ã£o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
session_start();
define('CACIC',1);
require_once('include/config.php');
require_once('include/define.php');
require_once "include/library.php";

conecta_bd_cacic();

if (!session_is_registered('id_default_body_bgcolor'))
	{
	$qry_default_configs = "SELECT 	*
							FROM	configuracoes_padrao";
	$res_default_configs = mysql_query($qry_default_configs);
	$row_default_configs = mysql_fetch_array($res_default_configs);
	session_register('id_default_body_bgcolor');
	
	$_SESSION['id_default_body_bgcolor'] = $row_default_configs['id_default_body_bgcolor'];
	}

function PegaConfiguracoesLocais($p_id_local)
	{
	$qry_configs_locais  				= "SELECT *
										   FROM	  configuracoes_locais
										   WHERE  id_local = ".$p_id_local;

	$res_configs_locais  				= mysql_query($qry_configs_locais);
	$row_configs_locais  				= @mysql_fetch_array($res_configs_locais);
	$_SESSION['id_default_body_bgcolor']= $row_configs_locais['id_default_body_bgcolor'];				
	}
	
function UsuarioInvalido()
	{
	LiberaVariaveisSessao();
	?>
	<SCRIPT LANGUAGE="Javascript">
		alert('Usuário Não Autenticado!');
	   top.location = 'index.php';
	</script>
	<?php
	}	
	
function LiberaVariaveisSessao()
	{
	session_unregister('id_grupo_usuarios');	 
	session_unregister('cs_nivel_administracao');	 	 
	session_unregister('te_locais_secundarios');			 
	session_unregister('id_local');			 		
	session_unregister('nm_local');			 	 
	session_unregister('nm_usuario');
	session_unregister('menu_usuario');
	session_unregister('id_usuario');
	session_unregister('id_usuario_crypted');
	session_unregister('te_grupo_usuarios');
	session_unregister('id_default_body_bgcolor');	 				
	session_unregister('cIpsDisplayDebugs');
	session_unregister('etiqueta1');
	session_unregister('plural_etiqueta1');
	session_unregister('etiqueta1a');
	session_unregister('plural_etiqueta1a');
	session_unregister('etiqueta2');
	session_unregister('plural_etiqueta2');

    //Adicionado pela Marisol em 12/06/2006
    session_destroy();
	}	
// Caso o usuario clique em "logoff" a sua sessao eh destruida
if($_POST['logoff'])
     {
	 LiberaVariaveisSessao();		 
	 ?>
	 <SCRIPT LANGUAGE="Javascript">
	    top.location = 'index.php';
	 </script>
	 <?php
	 }
	 
if($_POST['frm_nm_usuario_acesso'] && $_POST['frm_te_senha'])
	{
	function ConcluiAcesso($arrRowSel, $pNmUsuarioCompleto)
		{
		global $key,$iv;
		include_once 'include/config.php'; // Incluo o config.php para pegar as chaves de criptografia	
		$_SESSION["id_grupo_usuarios"] 		=             $arrRowSel['id_grupo_usuarios'];			
		$_SESSION["nm_usuario"] 			= 			  $pNmUsuarioCompleto;
		$_SESSION["menu_usuario"]      		=             getMenu($arrRowSel['te_menu_grupo']);
		$_SESSION["id_usuario"] 			=             $arrRowSel['id_usuario'];						 
		$_SESSION["id_usuario_crypted"] 	=             EnCrypt($key,$iv,$arrRowSel['id_usuario'],"1","0","0");
		$_SESSION["id_local"]				=             $arrRowSel['id_local'];			 			 			 
		$_SESSION["sg_local"]				=             $arrRowSel['sg_local'];			 			 			 
		$_SESSION["nm_local"]				=             $arrRowSel['nm_local'];			 			 			 			 
		$_SESSION["cs_nivel_administracao"]	=             $arrRowSel['cs_nivel_administracao'];			 			 			 			 
		$_SESSION["te_locais_secundarios"]	=             ($_SESSION["cs_nivel_administracao"] <> '1' && $_SESSION["cs_nivel_administracao"] <> '2'?trim($arrRowSel['te_locais_secundarios']):'');
		$_SESSION["te_grupo_usuarios"]		= 			  $arrRowSel['te_grupo_usuarios'];

		$qry_upd = "UPDATE usuarios SET dt_log_in = now() WHERE id_usuario = ".$_SESSION["id_usuario"];
		$res_upd = mysql_query($qry_upd) or die ($oTranslator->_('kciq_msg update row on table fail', array('usuarios'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
		GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso',$arrRowSel['id_usuario']);			 
		PegaConfiguracoesLocais($_SESSION['id_local']);		
		?>
		<SCRIPT LANGUAGE="Javascript">
			top.location = 'index.php';
		</script>
		<?php			
		}
	// *********************************************************************************************
	// ** VERIFICAR EXISTÊNCIA DO USUÁRIO NA BASE DO CACIC COM O NOME FORNECIDO
	// ** 		Se Existe
	// **			Verificar se senha confere
	// ** 				Se Confere
	// **					OK - Concluir acesso ao CACIC
	// **				Se Não Confere
	// **					Verificar se usuário e senha fazem BIND no Servidor de Autenticação 
	// **						Se Fazem BIND
	// **							Informar que as senhas estão diferentes e oferecer sincronização
	// **								Se Sincronização Aceita
	// **									Sincronizar e Concluir acesso ao CACIC
	// **								Se Sincronização Não Aceita
	// **									Informar USUÁRIO INEXISTENTE OU SENHA INVÁLIDA
	// **									FIM
	// **		Se Não Existe
	// **			Verificar se usuário e senha fazem BIND no Servidor de Autenticação
	// **				Se Fazem BIND
	// **					Informar que o usuário não tem conta no CACIC e que deverá solicitar seu cadastramento 
	// **					FIM
	// **				Se Não Fazem BIND
	// **					Informar USUÁRIO INEXISTENTE OU SENHA INVÁLIDA
	// *********************************************************************************************
	
	$qry_sel = "SELECT 	u.id_grupo_usuarios,    
						u.nm_usuario_completo,  
						g.te_menu_grupo,
						g.cs_nivel_administracao,
						g.te_grupo_usuarios,		 
						u.id_usuario, 			
						u.id_local,
						u.te_locais_secundarios,
						u.te_senha,
						u.id_usuario_ldap,
						u.id_servidor_autenticacao,
						l.nm_local,
						l.sg_local,
						PASSWORD('". trim(base64_decode($_POST['frm_te_senha'])) ."') as frm_te_senha 
				FROM 	usuarios u, 
						grupo_usuarios g, 
						locais l
				WHERE 	'". trim(base64_decode($_POST['frm_nm_usuario_acesso'])) ."' IN (u.nm_usuario_acesso, u.id_usuario_ldap) AND 
						 u.id_grupo_usuarios = g.id_grupo_usuarios AND						  	
						 u.id_local = l.id_local ";
	$res_sel = mysql_query($qry_sel);							
	$row_sel = mysql_fetch_array($res_sel);

	if ($row_sel['id_usuario']) // Verifico se o usuário existe na base CACIC com o identificador informado
		{
		if ($row_sel['id_servidor_autenticacao'] <> '0') // Caso tenha sido informado um servidor de autenticação LDAP para o usuário
			{
			$arrAutenticaLDAP = AutenticaLDAP($row_sel['id_servidor_autenticacao'], trim(base64_decode($_POST['frm_nm_usuario_acesso'])), trim(base64_decode($_POST['frm_te_senha'])));
			if ($arrAutenticaLDAP['nm_nome_completo'] <> '')
				{
				LimpaTESTES();
				GravaTESTES('AutenticaLDAP-OK!');							
				$qry_upd = "UPDATE usuarios SET nm_usuario_completo_ldap = '".$arrAutenticaLDAP['nm_nome_completo']."',te_senha = PASSWORD('". trim(base64_decode($_POST['frm_te_senha'])) ."') WHERE id_usuario = " . $row_sel['id_usuario'];
				$res_upd = mysql_query($qry_upd);											
				ConcluiAcesso($row_sel,$arrAutenticaLDAP['nm_nome_completo']);					
				}
			else
				UsuarioInvalido();
			}
		elseif ($row_sel['te_senha'] == $row_sel['frm_te_senha'] ) // Verifico se a senha informada bate com a cadastrada na base CACIC
			ConcluiAcesso($row_sel, PrimUltNome($row_sel['nm_usuario_completo']));
		else
			UsuarioInvalido();
		}
	else
		UsuarioInvalido();
	}
	?>
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="include/css/cacic.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<?php
	echo '<body bgcolor='.($_SESSION['id_default_body_bgcolor']<>''?$_SESSION['id_default_body_bgcolor']:'#EBEBEB').' leftmargin="1" topmargin="0" ';	
	if (!$_SESSION["id_grupo_usuarios"] || $_SESSION['id_grupo_usuarios']==3)
		{
		echo 'onLoad="SetaCampo('."'frm_nm_usuario_acesso'".');"';	
		}
	else if ($_SESSION["id_usuario"])
		echo 'onLoad="SetaCampo('."'user_logged_in'".');"';
		echo '>';
	?>
	<script language="JavaScript" type="text/javascript" src="include/js/cacic.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/js/crypt.js"></script>
	<p class="menu"> 
	<?php
if (!$_SESSION["id_usuario"])
	{	
	$arrDadosRede = getDadosRede(); // _SERVER["REMOTE_ADDR"]...
	PegaConfiguracoesLocais($arrDadosRede['id_local']);		
	if ($arrDadosRede['id_rede'] && $arrDadosRede['nm_local'])
		{
	 	$_SESSION["id_grupo_usuarios"] 		=             3; // Convidado
		$_SESSION["nm_usuario"] 			= 			  '';
		$_SESSION["menu_usuario"]      		=             getMenu("menu_con.txt");
		$_SESSION["id_usuario"] 			=             1;
		$_SESSION["id_local"]				=             $arrDadosRede['id_local'];			 			 
		$_SESSION["id_rede"]				=             $arrDadosRede['id_rede'];			 			 		
		$_SESSION["nm_local"]				=             $arrDadosRede['nm_local'];			 			 			 
		$_SESSION["sg_local"]				=             $arrDadosRede['sg_local'];			 			 			 		
		$_SESSION["cs_nivel_administracao"]	=             0;

		?>
		<SCRIPT LANGUAGE="Javascript">
		top.location = 'index.php';
	 	</script>
		<?php		
		}
	else
		{
		$treefile = getMenu("menu_ini.txt"); //"language/menus/menu_ini.txt";
		require "include/treemenu.php";
		?>
		<p>
		<form name="form1" method="post" action="menu_esq.php">
	  	<table width="24%" border="0" align="center" cellpadding="0" cellspacing="0">
	    <tr> 
	    <td valign="middle" class="label_peq_sem_fundo"><div align="right"><?php echo $oTranslator->_('kciq_msg user'); ?>:</div><br></td>
	    <td class="dado_peq_sem_fundo"><div align="left"><input name="frm_nm_usuario_acesso" type="text" id="frm_nm_usuario_acesso" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></div></td>
	    </tr>
	    <tr> 
	    <td valign="middle" class="label_peq_sem_fundo"><div align="right"><?php echo $oTranslator->_('kciq_msg password'); ?>:</div><br></td>
	    <td class="dado_peq_sem_fundo"><div align="left"><input name="frm_te_senha" type="password" id="frm_te_senha" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></div></td>
	    </tr>
		<tr> 
		<td colspan="2" class="dado_peq_sem_fundo">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
        <td><noscript><div align="justify"><font color="#FF0000" size="2"><strong><?php echo $oTranslator->_('kciq_msg attention');?>:</strong></font><font color="#0000FF" size="1"> 
        <?php echo $oTranslator->_('kciq_msg javascript not enabled');?></font></div></noscript></td>
        </tr>
        </table>
        </div>
        </div>
        <br>
        <br>
        <div align="center">		  
        <input name="Submit" align="middle" type="submit" class="botao" value="LogOn" disabled="true" onClick="preparaEnvio();">
		</div>
        </td>
	    </tr>
	  	</table>
	  	</form> 
		<?php 
		}
	}
else
	{	
	//
	$queryCONFIG = "SELECT 		DISTINCT 
								id_etiqueta,
								te_etiqueta,
								te_plural_etiqueta
			  		FROM 		patrimonio_config_interface patcon
					WHERE		patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta1a'."','".'etiqueta2'."') ".
							  " AND id_local = ".$_SESSION['id_local']." 
			  		ORDER BY 	id_etiqueta
					LIMIT       3";

	$resultCONFIG 	= mysql_query($queryCONFIG);

	session_register('etiqueta1');
	session_register('plural_etiqueta1');
	session_register('etiqueta1a');
	session_register('plural_etiqueta1a');
	session_register('etiqueta2');
	session_register('plural_etiqueta2');

	$_SESSION['etiqueta1'] 			= mysql_result($resultCONFIG,0,'te_etiqueta');
	$_SESSION['plural_etiqueta1'] 	= mysql_result($resultCONFIG,0,'te_plural_etiqueta');
	$_SESSION['etiqueta1a'] 		= mysql_result($resultCONFIG,1,'te_etiqueta');
	$_SESSION['plural_etiqueta1a'] 	= mysql_result($resultCONFIG,1,'te_plural_etiqueta');
	$_SESSION['etiqueta2'] 			= mysql_result($resultCONFIG,2,'te_etiqueta');
	$_SESSION['plural_etiqueta2'] 	= mysql_result($resultCONFIG,2,'te_plural_etiqueta');
	
	$treefile = $_SESSION["menu_usuario"];
	require "include/treemenu.php";
	?>
	<SCRIPT LANGUAGE="JavaScript">
	function trimAll(sString)
		{
		while (sString.substring(0,1) == ' ')
			{
			sString = sString.substring(1, sString.length);
			}
		while (sString.substring(sString.length-1, sString.length) == ' ')
			{
			sString = sString.substring(0,sString.length-1);
			}
			return sString;				
		}
	
	function LigaHelp()
		{
		var campo = document.getElementById('mensagem_pesquisa');
   		campo.style.backgroundColor = "#ebebeb";		
		campo.value = "Nome da EstaÃ§Ã£o, IP ou MAC";
		}
	function DesligaHelp()
		{
		var campo = document.getElementById('mensagem_pesquisa');
   		campo.style.color.backgroundcolor = "#cccccc";
		campo.value = "";
		}
	function VerDesligaHelp()
		{
		var campo_string = document.getElementById('string_consulta');
		var campo_help   = document.getElementById('mensagem_pesquisa');		
		var conteudo = trimAll(campo_string.value);
   		if (conteudo.length > 2)
			{
			campo_help.value = "";
			}
		}
	</SCRIPT>		
	
	<?php 
	if ($_SESSION['id_grupo_usuarios']<>3) // Caso nao seja usuario "Convidado" (atribuido automaticamente quando rede e local sao identificados)
		{
		?>
		<!-- Inicio Marisol 24-07-06 --> 
		<form name="form0" method="post" action="relatorios/computadores.php?campo=te_nome_computador" target=mainFrame>  	
  		<table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr> 
      	<td height="49" class="dado_peq_sem_fundo"><?php echo $oTranslator->_('kciq_menu fast search'); ?>:<br> 
        <input type="hidden" name="consultar" id=consultar2 value="Consultar"> 
        <input type="hidden" name="tipo_consulta" value="consulta_rapida"> 
		<input size=16 name="string_consulta" type="text" id="string_consulta" value="" class="normal" onFocus="SetaClassDigitacao(this);LigaHelp();" onBlur="SetaClassNormal(this);DesligaHelp();" onKeyUp="VerDesligaHelp();"><a href="javascript:document.forms[0].submit()"><img src=imgs/arvore/totals.gif  width="25" height="25" border="0" align="top"></a>
        <br>
        <input type="text" name="mensagem_pesquisa" id="mensagem_pesquisa" value="" size="25" class="texto_pesquisa" readonly="yes"><br>		
      	</td>
    	</tr>
  		</table>
		</form>
		<!-- Final Marisol 24-07-06 --> 
		<?php 
		}
		?>
		<form name="form1" method="post" action="menu_esq.php">	

  		<table border="0" align="center" cellpadding="0" cellspacing="0">
		<tr nowrap> 
		<?php 
	if ($_SESSION['id_grupo_usuarios']<>3) // Caso nao seja usuario "Convidado" (atribuido automaticamente quando rede e local sao identificados)		
		{	
		?>  			
      	<td nowrap class="label_peq_sem_fundo" valign="bottom" colspan="2"></td>		
    	</tr>
    	<tr>
      	<td colspan="2" class="dado_peq_sem_fundo" align="center" valign="top">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
        <td><noscript><div align="justify"><font color="#FF0000" size="2"><strong><?php echo $oTranslator->_('kciq_msg attention');?>:</strong></font><font color="#0000FF" size="1"> 
        <?php echo $oTranslator->_('kciq_msg javascript not enabled');?></font></div></noscript></td>
        </tr>
        </table>
        </div>
        </div>
        <input name="Submit" align="middle" type="hidden" disabled="true">		
	  	</td>
    	</tr>
    	<tr> 
      	<td colspan="2"><div align="center"></div>
        <div align="center">
        <input name="logoff" type="submit" id="logoff" value="Logoff">
        </div></td>
      	<?php 
		}
	else
		{
		?>
      	<td nowrap class="label_peq_sem_fundo" valign="bottom" colspan="2"><?php echo $oTranslator->_('kciq_msg user'); ?>:</td>				
    	</tr>
	  <tr> 
	  <td height="1" bgcolor="#cococo" colspan="2"></td>
	  </tr>
      <tr>
      	<td nowrap class="dado_peq_sem_fundo" valign="top" colspan="2"><div align="left">
        <input name="frm_nm_usuario_acesso" type="text" id="frm_nm_usuario_acesso" size="25" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        </div></td>
    	</tr>
        
   	 	<tr> 
      	<td class="label_peq_sem_fundo" valign="bottom" colspan="2"><div align="left"><?php echo $oTranslator->_('kciq_msg password'); ?>:</div></td>
    	</tr>
		  <tr> 
		  <td height="1" bgcolor="#cococo" colspan="2"></td>
	  	</tr>
   	 	<tr> 
      	<td class="dado_peq_sem_fundo" valign="top" colspan="2"><div align="left">
        <input name="frm_te_senha" type="password" id="frm_te_senha" size="25" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        </div></td>
    	</tr>
        
    	<tr> 
      	<td colspan="2" class="dado_peq_sem_fundo">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
        <td><noscript><div align="justify"><font color="#FF0000" size="2"><strong><?php echo $oTranslator->_('kciq_msg attention');?>:</strong></font><font color="#0000FF" size="1"> 
        <?php echo $oTranslator->_('kciq_msg javascript not enabled');?></font></div></noscript></td>
        </tr>
        </table>
        </div>
        </div>
        <br>
        <br>
        <div align="center">		  		  
        <input name="Submit" align="middle" type="submit" class="botao" value="LogOn" disabled="true" onClick="preparaEnvio();">
		</div>
        </td>
    	</tr>
    	<?php
		}
		?>
		</tr>
		<?php		
		include 'include/contador.php';		
		?>
  	</table>
  	</form> 
	<?php
	}
	?>   
	<SCRIPT LANGUAGE="JavaScript">
	document.getElementById('Layer1').style.visibility = "hidden";
	document.form1.Submit.disabled = "";
	</SCRIPT>		
</body>
</html>
