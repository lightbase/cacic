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
define('CACIC',1);
require_once('include/config.php');
require_once('include/define.php');
require_once "include/library.php";

// IP´s onde serão exibidas mensagens de Debug, para acompanhamento de atualização de scripts.
// Os IP´s devem estar entre "[" e "]". Exemplo: s_SESSION['cIpsDisplayDebugs'] = '[10.71.0.58][10.71.0.52]';
$_SESSION['cIpsDisplayDebugs'] = '[10.71.0.58][10.71.0.52]';

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
	session_start();
	$qry_configs_locais  				= "SELECT *
										   FROM	  configuracoes_locais
										   WHERE  id_local = ".$p_id_local;
	$res_configs_locais  				= mysql_query($qry_configs_locais);
	$row_configs_locais  				= @mysql_fetch_array($res_configs_locais);
	$_SESSION['id_default_body_bgcolor']= $row_configs_locais['id_default_body_bgcolor'];				
	}
// Caso o usuário clique em "logoff" a sua sessão é destruída
if($_POST['logoff'])
     {
	 session_unregister('id_grupo_usuarios');	 
	 session_unregister('cs_nivel_administracao');	 	 
	 session_unregister('te_locais_secundarios');			 	 
	 session_unregister('id_local');			 
	 session_unregister('nm_local');			 	 
	 session_unregister('nm_usuario');
	 session_unregister('menu_usuario');
	 session_unregister('id_usuario');	 
	 session_unregister('te_grupo_usuarios');			 
	 session_unregister('id_default_body_bgcolor');	 
	 session_unregister('cIpsDisplayDebugs');	 
     //Adicionado pela Marisol em 12/06/2006
     session_destroy();
	 
	 ?>
	 <SCRIPT LANGUAGE="Javascript">
	    top.location = 'index.php';
	 </script>
	 <?
	 }
	 
if($_POST['frm_nm_usuario_acesso'] && $_POST['frm_te_senha'])
	{
	// Solução temporária, até total convergência para versões 4.0.2 ou maior de MySQL 
	// Anderson Peterle - Dataprev/ES - 04/09/2006
	$v_AUTH_SHA1 	 = " SHA1('". trim(base64_decode($_POST['frm_te_senha'])) ."')";
	$v_AUTH_PASSWORD = " PASSWORD('". trim(base64_decode($_POST['frm_te_senha'])) ."')";	
	// 
	conecta_bd_cacic();
	$qry_usuario = "SELECT 	a.id_grupo_usuarios,    
							a.nm_usuario_completo,  
							b.te_menu_grupo,
							b.cs_nivel_administracao,
							b.te_grupo_usuarios,		 
							a.id_usuario, 			
							a.id_local,
							a.te_locais_secundarios,
							c.nm_local,
							c.sg_local		
					FROM 	usuarios a, 
							grupo_usuarios b, 
							locais c
					WHERE 	a.id_grupo_usuarios = b.id_grupo_usuarios AND
						  	a.nm_usuario_acesso = '". trim(base64_decode($_POST['frm_nm_usuario_acesso'])) ."' AND 
							a.id_local = c.id_local AND 
							a.te_senha = ";

	// Para MySQL 4.0.2 ou maior	
	// Anderson Peterle - Dataprev/ES - 04/09/2006
	$query = $qry_usuario . $v_AUTH_SHA1; 

	$result_qry_usuario = mysql_query($query);
	if (mysql_num_rows($result_qry_usuario)<=0)
		{
		// Para MySQL até 4.0	
		// Anderson Peterle - Dataprev/ES - 04/09/2006		
		$query = $qry_usuario . $v_AUTH_PASSWORD;
		$result_qry_usuario = mysql_query($query);
		}
	if ($result_qry_usuario)
		{
		while($reg_result = mysql_fetch_array($result_qry_usuario))
			{ 			
			$_SESSION["id_grupo_usuarios"] 		=             $reg_result['id_grupo_usuarios'];			
			$_SESSION["nm_usuario"] 			= PrimUltNome($reg_result['nm_usuario_completo']);
			$_SESSION["menu_usuario"]      		=             getMenu($reg_result['te_menu_grupo']); //'language/menus/'.$reg_result['te_menu_grupo'];			 			 
			$_SESSION["id_usuario"] 			=             $reg_result['id_usuario'];						 
			$_SESSION["id_usuario_crypted"] 	=             EnCrypt($key,$iv,$reg_result['id_usuario'],"1","0","0");
			$_SESSION["te_locais_secundarios"]	=        trim($reg_result['te_locais_secundarios']);			 			 
			$_SESSION["id_local"]				=             $reg_result['id_local'];			 			 			 
			$_SESSION["sg_local"]				=             $reg_result['sg_local'];			 			 			 
			$_SESSION["nm_local"]				=             $reg_result['nm_local'];			 			 			 			 
			$_SESSION["cs_nivel_administracao"]	=             $reg_result['cs_nivel_administracao'];			 			 			 			 
			$_SESSION["te_grupo_usuarios"]		= 			  $reg_result['te_grupo_usuarios'];
			}
	
		GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso');			 
		PegaConfiguracoesLocais($_SESSION['id_local']);		
		?>
		<SCRIPT LANGUAGE="Javascript">
			top.location = 'index.php';
		</script>
		<?										
		}
	else
		{
		session_unregister('id_grupo_usuarios');	 
		session_unregister('cs_nivel_administracao');	 	 
		session_unregister('te_locais_secundarios');			 
		session_unregister('id_local');			 		
		session_unregister('nm_local');			 	 
		session_unregister('nm_usuario');
		session_unregister('menu_usuario');
		session_unregister('id_usuario');
		session_unregister('te_grupo_usuarios');
		session_unregister('id_default_body_bgcolor');	 				
		session_unregister('cIpsDisplayDebugs');	 			 		
		?>
		<SCRIPT LANGUAGE="Javascript">
		alert('Usuário não cadastrado ou senha inválida!');
		</script>
		<?
		break;		
		}					
	}
	?>
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="include/cacic.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<?
	echo '<body bgcolor='.($_SESSION['id_default_body_bgcolor']<>''?$_SESSION['id_default_body_bgcolor']:'#EBEBEB').' leftmargin="1" topmargin="0" ';	
	if (!$_SESSION["id_grupo_usuarios"] || $_SESSION['id_grupo_usuarios']==3)
		{
		echo 'onLoad="SetaCampo('."'frm_nm_usuario_acesso'".');"';	
		}
	else if ($_SESSION["id_usuario"])
		echo 'onLoad="SetaCampo('."'user_logged_in'".');"';	
//		echo 'onLoad="SetaCampo('."'string_consulta'".');"';			
		
	echo '>';
	?>
	<script language="JavaScript" type="text/javascript" src="include/cacic.js"></script>
	<script language="JavaScript" type="text/javascript" src="include/crypt.js"></script>
	<p> 
	<?
if (!$_SESSION["id_usuario"])
	{
	$v_dados_rede = getDadosRede(); // _SERVER["REMOTE_ADDR"]...
	PegaConfiguracoesLocais($v_dados_rede['id_local']);		
	if ($v_dados_rede['id_ip_rede'] && $v_dados_rede['nm_local'])
		{
	 	$_SESSION["id_grupo_usuarios"] 		=             3; // Convidado
		$_SESSION["nm_usuario"] 			= 			  '';
		$_SESSION["menu_usuario"]      		=             getMenu("menu_con.txt"); //'language/menus/menu_con.txt';			 			 
		$_SESSION["id_usuario"] 			=             1;
		$_SESSION["id_local"]				=             $v_dados_rede['id_local'];			 			 
		$_SESSION["nm_local"]				=             $v_dados_rede['nm_local'];			 			 			 
		$_SESSION["sg_local"]				=             $v_dados_rede['sg_local'];			 			 			 		
		$_SESSION["cs_nivel_administracao"]	=             0;

		?>
		<SCRIPT LANGUAGE="Javascript">
		top.location = 'index.php';
	 	</script>
		<?		
		}
	else
		{
		$treefile = getMenu("menu_ini.txt"); //"language/menus/menu_ini.txt";
		require "include/treemenu.php";
		?>
		<p>
		<form name="form1" method="post" action="menu_esq.php">
	  	<table width="24%" border="0" align="center">
	    <tr> 
	    <td valign="middle" class="label_peq_sem_fundo"><div align="right">Usu&aacute;rio:</div></td>
	    <td class="dado_peq_sem_fundo"><div align="left"><input name="frm_nm_usuario_acesso" type="text" id="frm_nm_usuario_acesso" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></div></td>
	    </tr>
	    <tr> 
	    <td valign="middle" class="label_peq_sem_fundo"><div align="right">Senha:</div></td>
	    <td class="dado_peq_sem_fundo"><div align="left"><input name="frm_te_senha" type="password" id="frm_te_senha" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></div></td>
	    </tr>
		<tr> 
		<td colspan="2" class="dado_peq_sem_fundo">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; layer-background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="1">
        <tr> 
        <td><div align="justify"><font color="#FF0000" size="2"><strong>Aten&ccedil;&atilde;o:</strong></font><font color="#0000FF" size="1"> 
        O Javascript &eacute; indispens&aacute;vel ao perfeito funcionamento do Sistema</font></div></td>
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
		<?
		}
	}
else
	{	
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
		campo.value = "Nome da Estação, IP ou MAC";
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
	
	<?
	if ($_SESSION['id_grupo_usuarios']<>3) // Caso não seja usuário "Convidado" (atribuído automaticamente quando rede e local são identificados)
		{
		?>
		<!-- Inicio Marisol 24-07-06 --> 
		<form name="form0" method="post" action="relatorios/computadores.php?campo=te_nome_computador" target=mainFrame>  	
  		<table border="0" align="center">
    	<tr> 
      	<td height="49" class="dado_peq_sem_fundo"><?= $oTranslator->_('kciq_menu fast search'); ?>:<br> 
        <input type="hidden" name="consultar" id=consultar2 value="Consultar"> 
        <input type="hidden" name="tipo_consulta" value="consulta_rapida"> 
		<input size=16 name="string_consulta" type="text" id="string_consulta" value="" class="normal" onFocus="SetaClassDigitacao(this);LigaHelp();" onBlur="SetaClassNormal(this);DesligaHelp();" onKeyUp="VerDesligaHelp();"><a href="javascript:document.forms[0].submit()"><img src=imgs/arvore/totals.gif  width="25" height="25" border="0" align="top"></a>
        <br>
        <input type="text" name="mensagem_pesquisa" id="mensagem_pesquisa" value="" size="25" class="texto_pesquisa" readonly="yes"><br>		
      	</td>
      	<td class="dado_peq_sem_fundo"></td>
    	</tr>
  		</table>
		</form>
		<!-- Final Marisol 24-07-06 --> 
		<?
		}
		?>
		<form name="form1" method="post" action="menu_esq.php">	

  		<table border="0" align="center">
		<tr nowrap> 
		<?
	if ($_SESSION['id_grupo_usuarios']<>3) // Caso não seja usuário "Convidado" (atribuído automaticamente quando rede e local são identificados)		
		{	
		?>  			
      	<td nowrap class="label_peq_sem_fundo" valign="bottom"><?= $oTranslator->_('kciq_msg user'); ?>:</td>		
      	<td nowrap class="dado_peq_sem_fundo"><div align="left"></div></td>
    	</tr>
    	<tr>
      	<td colspan="2" class="dado_peq_sem_fundo" align="center" valign="top">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; layer-background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="1">
        <tr> 
        <td><div align="justify"><font color="#FF0000" size="2"><strong>Aten&ccedil;&atilde;o:</strong></font><font color="#0000FF" size="1"> 
        O Javascript &eacute; indispens&aacute;vel ao perfeito funcionamento do Sistema</font></div></td>
        </tr>
        </table>
        </div>
        </div>
        <input name="Submit" align="middle" type="hidden" disabled="true">		
	  	<input name="user_logged_in" type="text" id="user_logged_in" value="<? echo $_SESSION['nm_usuario']; ?>" readonly="yes" class="dado_peq_sem_fundo" size="24%">
	  	</td>
      	<td class="dado_peq_sem_fundo">&nbsp;</td>
    	</tr>
		<tr>
      	<td colspan="2" class="dado_peq_sem_fundo" align="center"><?= $oTranslator->_('kciq_msg access level');?>: "<? echo $_SESSION['te_grupo_usuarios'];?>"</td>
    	</tr>		
    	<tr> 
      	<td colspan="2"><div align="center"></div>
        <div align="center">
        <input name="logoff" type="submit" id="logoff" value="Logoff">
        </div></td>
      	<?
		}
	else
		{
		?>
      	<td nowrap class="label_peq_sem_fundo" valign="middle"><?= $oTranslator->_('kciq_msg user'); ?>:</td>				
      	<td class="dado_peq_sem_fundo"><div align="left">
        <input name="frm_nm_usuario_acesso" type="text" id="frm_nm_usuario_acesso" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        </div></td>
    	</tr>
   	 	<tr> 
      	<td class="label_peq_sem_fundo" valign="middle"><div align="right">Senha:</div></td>
      	<td class="dado_peq_sem_fundo"><div align="left">
        <input name="frm_te_senha" type="password" id="frm_te_senha" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        </div></td>
    	</tr>
    	<tr> 
      	<td colspan="2" class="dado_peq_sem_fundo">
        <div id="Layer1" style="position:absolute; width:168px; height:55px; z-index:1; left: 12px; top: 270px; background-color: #FFFFCC; layer-background-color: #FFFFCC; border: 1px none #000000; overflow: visible; visibility: visible;">
        <div align="justify">
        <table width="99%" height="99%" border="1">
        <tr> 
        <td><div align="justify"><font color="#FF0000" size="2"><strong>Aten&ccedil;&atilde;o:</strong></font><font color="#0000FF" size="1"> 
        O Javascript &eacute; indispens&aacute;vel ao perfeito funcionamento do Sistema</font></div></td>
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
    	<?
		}
		?>
		</tr>
  	</table>
  	</form> 
	<?
	}
	?>   
	<SCRIPT LANGUAGE="JavaScript">
	document.getElementById('Layer1').style.visibility = "hidden";
	document.form1.Submit.disabled = "";
	</SCRIPT>		
</body>
</html>