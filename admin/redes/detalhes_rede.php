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
require_once('../../include/library.php');
anti_spy();
Conecta_bd_cacic();

if ($_REQUEST['ExcluiRede']) 
	{
	$query = "DELETE 
			  FROM 		redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha de deleção na tabela redes...');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'redes');				
	$query = "DELETE 	
			  FROM 		acoes_redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha de deleção na tabela ações_redes');	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');				

	$query = "DELETE 	
			  FROM 		aplicativos_redes 
			  WHERE 	id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha de deleção na tabela aplicativos_redes');	
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');				
	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 				
	}
elseif ($_POST['GravaAlteracoes']) 
	{
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
			  			te_senha_login_serv_updates 			= '".$_POST['frm_te_senha_login_serv_updates']."', 
			  			nu_porta_serv_updates 					= '".$_POST['frm_nu_porta_serv_updates']."',
			  			nm_usuario_login_serv_updates_gerente 	= '".$_POST['frm_nm_usuario_login_serv_updates_gerente']."', 
			  			te_senha_login_serv_updates_gerente 	= '".$_POST['frm_te_senha_login_serv_updates_gerente']."',
			  			id_local 								=  ".$_POST['frm_id_local']."
			  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
			  			id_local = ".$_REQUEST['id_local'];

	mysql_query($query) or die('Falha na atualização da tabela Redes...');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes');

	$query = "UPDATE 	acoes_redes SET 
			  			id_local = ".$_POST['frm_id_local']."
			  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
			  			id_local = ".$_POST['id_local'];
	mysql_query($query) or die('Falha na atualização da tabela Acoes_Redes...');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'acoes_redes');			

	$query = "UPDATE 	redes_grupos_ftp SET 
			  			id_local =  ".$_POST['frm_id_local']."
			  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha na atualização da tabela Redes_Grupos_FTP...');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes_grupos_ftp');			

	$query = "UPDATE 	redes_versoes_modulos SET 
			  			id_local =  ".$_POST['frm_id_local']."
			  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die('Falha na atualização da tabela Redes_Versoes_Modulos...');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'redes_versoes_modulos');			

	// Caso tenha sido alterado o local da subrede, primeiramente atualizarei a informação abaixo:
	if ($_POST['frm_id_local'] <> $_POST['id_local'])
		{
		$query = "UPDATE 	aplicativos_redes SET 
				  			id_local =  ".$_POST['frm_id_local']."
				  WHERE 	trim(id_ip_rede) = '".trim($_REQUEST['id_ip_rede'])."' AND
				  			id_local = ".$_REQUEST['id_local'];
		mysql_query($query) or die('Falha na atualização da tabela Aplicativos_Redes...');
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'Aplicativos_Redes');			
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
				  			id_local = ".$_REQUEST['id_local'];
		mysql_query($query) or die('Falha de deleção na tabela aplicativos_redes');	
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'aplicativos_redes');						
		seta_perfis_rede($_REQUEST['frm_id_local'],trim($_REQUEST['id_ip_rede']), $v_perfis); 					
		}		
		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=1");									 					
	}
else 
	{
	$query = "	SELECT 	* 
				FROM 	redes
						LEFT JOIN locais ON (redes.id_local = locais.id_local AND redes.id_local = ".$_GET['id_local'].") 
				WHERE 	redes.id_ip_rede = '".$_GET['id_ip_rede']."'";
	$result = mysql_query($query) or die ('Falha na consulta às tabelas Redes, Locais...');
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
      <td class="cabecalho">Detalhes da Subrede <? echo mysql_result($result, 0, 'id_ip_rede'); ?></td>
  </tr>
  <tr> 
    <td class="descricao">As op&ccedil;&otilde;es 
      abaixo determinam qual ser&aacute; o comportamento dos agentes oper&aacute;rios 
      do CACIC.</td>
  </tr>
</table>

<table width="60%" border="0" align="center" cellpadding="5" cellspacing="1">
  <tr> 
    <td valign="top"> 

        <table width="90%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>Local:</td>
            <td class="label" colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <select name="frm_id_local" id="frm_id_local"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"  
			<?
			echo ($_SESSION['cs_nivel_administracao']>1?'disabled':'');
			?>
			>
                <?
			$qry_locais = "SELECT 	id_local,
									sg_local 
						   FROM 	locais 
						   ORDER BY	sg_local";
		    $result_locais = mysql_query($qry_locais) or die ('Select falhou');
		if (mysql_result($result, 0, 'nm_local')=='')
			echo "<option value='-1' selected>Selecione Local</option>";
							
		while ($row=mysql_fetch_array($result_locais))
			{ 
			echo "<option value=\"" . $row["id_local"] . "\"";
			if ($_GET['id_local'] == $row["id_local"]) echo " selected";
			echo ">" . $row["sg_local"] . "</option>";
		   	} 
			?>
              </select> 
			<?
			// Caso o usuário não seja privilegiado, o valor abaixo deverá ser fixado...
			if ($_SESSION['cs_nivel_administracao']>1)
				echo '<input name="frm_id_local"  type="hidden" id="frm_id_local" value="'.$_GET['id_local'].'">'; 				
			?>
			  
			  <input name="id_local"  type="hidden" id="id_local" value="<? echo $_GET['id_local']; ?>"> 
            </td>
            <td>&nbsp; </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              Subrede:</td>
            <td class="label"><br>
              M&aacute;scara:</td>
            <td class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_id_ip_rede" readonly="" type="text" value="<? echo mysql_result($result, 0, 'id_ip_rede'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <input name="id_ip_rede"  type="hidden" id="id_ip_rede2" value="<? echo mysql_result($result, 0, 'id_ip_rede'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td> <input name="frm_te_mascara_rede" type="text" id="frm_te_mascara_rede2" value="<? echo mysql_result($result, 0, 'te_mascara_rede'); ?>" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              Descri&ccedil;&atilde;o:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td nowrap><input name="frm_nm_rede" type="text" id="frm_nm_rede2" value="<? echo mysql_result($result, 0, 'nm_rede'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td nowrap>&nbsp;</td>
            <td nowrap class="label"><br>
              Servidor de Aplica&ccedil;&atilde;o:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td rowspan="6" nowrap><font color="#000000"> 
              <? 
			if (TentaPing(mysql_result($result, 0, 'te_serv_updates')))
				{

		  		$v_conexao_ftp = conecta_ftp(mysql_result($result, 0, 'te_serv_updates'),
				                             mysql_result($result, 0, 'nm_usuario_login_serv_updates'),
											 mysql_result($result, 0, 'te_senha_login_serv_updates'),
											 mysql_result($result, 0, 'nu_porta_serv_updates')
											);
				}
					
			if ($v_conexao_ftp && (@ftp_chdir($v_conexao_ftp,mysql_result($result, 0, 'te_path_serv_updates').'/')))
				{
				echo '<img src="../../imgs/ftp_conectado.gif" height="55" width="55">';
				$v_classe = "label";
				// ftp_close($v_conexao_ftp);
				// Ainda será usado abaixo...
				}
			else
				{
				echo '<img src="../../imgs/ftp_desconectado.gif" height="55" width="55">';
				$v_classe = "label_vermelho";
				}

		  ?>
              </font> <div align="center"></div></td>
            <td nowrap><input name="frm_te_serv_cacic" type="text" id="frm_te_serv_cacic" value="<? echo mysql_result($result, 0, 'te_serv_cacic'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <select name="sel_te_serv_cacic" id="sel_te_serv_cacic" onChange="SetaServidorBancoDados();Fecha_Combo_serv_cacic();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="">===> Selecione <===</option>
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
            <td class="<? echo $v_classe; ?>"><br>
              Servidor de Updates(FTP):</td>
            <td nowrap class="<? echo $v_classe; ?>"> <br>
              Porta:</td>
            <td valign="bottom" nowrap class="<? echo $v_classe; ?>">Limite FTP:</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td nowrap> <input name="frm_te_serv_updates" type="text" id="frm_te_serv_updates" value="<? echo mysql_result($result, 0, 'te_serv_updates'); ?>" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <select name="sel_te_serv_updates" id="sel_te_serv_updates" onChange="SetaServidorUpdates();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <option value="">===> Selecione <===</option>
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
              </select></td>
            <td> <input name="frm_nu_porta_serv_updates" type="text" id="frm_nu_porta_serv_updates" value="<? echo mysql_result($result, 0, 'nu_porta_serv_updates'); ?>" size="15" maxlength="4" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td><input name="frm_nu_limite_ftp" type="text" id="frm_nu_limite_ftp" value="<? echo mysql_result($result, 0, 'nu_limite_ftp'); ?>" size="5" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
          </tr>
          <tr> 
            <td nowrap class="<? echo $v_classe; ?>"><br>
              Path no Servidor de Updates:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap> <input name="frm_te_path_serv_updates" type="text" id="frm_te_path_serv_updates" value="<? echo mysql_result($result, 0, 'te_path_serv_updates'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap align="center" colspan="4" class="<? echo $v_classe; ?>"><br>
              Conteúdo do Servidor de Updates:</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td></td>
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap colspan="3"><table border="1" align="center" cellpadding="2" bordercolor="#999999"><font style="bold" size="1" face="Verdana">
                <tr bgcolor="#FFFFCC"> 
                  <td bgcolor="#EBEBEB">&nbsp;</td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela">Arquivo</td>
                  <td bgcolor="#EBEBEB" class="cabecalho_tabela">Tamanho(Kb)</td>
                  <td colspan="3" align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela">Vers&atilde;o</td>
                </tr>
                <? 
				if ($v_conexao_ftp)
					{
					
				echo lista_updates(mysql_result($result, 0, 'te_serv_updates'),
		 													  mysql_result($result, 0, 'nm_usuario_login_serv_updates'),
															  mysql_result($result, 0, 'te_senha_login_serv_updates'),
															  mysql_result($result, 0, 'nu_porta_serv_updates'),
															  mysql_result($result, 0, 'te_path_serv_updates').'/'); 
															  

				 
				 }?>
              </table></td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap class="<? echo $v_classe; ?>"> <br>
              Usu&aacute;rio do Servidor de Updates: (para AGENTE)</td>
            <td nowrap class="<? echo $v_classe; ?>"><br>
              Senha para Login:</td>
            <td nowrap class="<? echo $v_classe; ?>">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_nm_usuario_login_serv_updates" type="text" id="frm_nm_usuario_login_serv_updates" value="<? echo mysql_result($result, 0, 'nm_usuario_login_serv_updates'); ?>"  size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td> <input name="frm_te_senha_login_serv_updates" type="password" id="frm_te_senha_login_serv_updates" value="<? echo mysql_result($result, 0, 'te_senha_login_serv_updates'); ?>"  size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td nowrap class="<? echo $v_classe; ?>"> <br>
              Usu&aacute;rio do Servidor de Updates: (para GERENTE)</td>
            <td nowrap class="<? echo $v_classe; ?>"><br>
              Senha para Login:</td>
            <td nowrap class="<? echo $v_classe; ?>">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_nm_usuario_login_serv_updates_gerente" type="text" id="frm_nm_usuario_login_serv_updates_gerente" value="<? echo mysql_result($result, 0, 'nm_usuario_login_serv_updates_gerente'); ?>"  size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td> <input name="frm_te_senha_login_serv_updates_gerente" type="password" id="frm_te_senha_login_serv_updates_gerente" value="<? echo mysql_result($result, 0, 'te_senha_login_serv_updates_gerente'); ?>"  size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              Observa&ccedil;&otilde;es:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><textarea name="frm_te_observacao" cols="38" id="textarea"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo mysql_result($result, 0, 'te_observacao'); ?></textarea></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              Contato 1:</td>
            <td class="label"><br>
              Telefone:</td>
            <td class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_nm_pessoa_contato1" type="text" id="frm_nm_pessoa_contato12" value="<? echo mysql_result($result, 0, 'nm_pessoa_contato1'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td> <input name="frm_nu_telefone1" type="text" id="frm_nu_telefone12" value="<? echo mysql_result($result, 0, 'nu_telefone1'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              E-mail:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_te_email_contato1" type="text" id="frm_te_email_contato12" value="<? echo mysql_result($result, 0, 'te_email_contato1'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              Contato 2:</td>
            <td class="label"><br>
              Telefone:</td>
            <td class="label">&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_nm_pessoa_contato2" type="text" id="frm_nm_pessoa_contato2" value="<? echo mysql_result($result, 0, 'nm_pessoa_contato2'); ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td> <input name="frm_nu_telefone2" type="text" id="frm_nu_telefone22" value="<? echo mysql_result($result, 0, 'nu_telefone2'); ?>" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td class="label"><br>
              E-mail:</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4" height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td> <input name="frm_te_email_contato2" type="text" id="frm_te_email_contato2" value="<? echo mysql_result($result, 0, 'te_email_contato2'); ?>" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td colspan="4">&nbsp;</td>
          </tr>
          <?		  
		$query_acoes_redes = "	SELECT	te_descricao_breve 
								FROM 	acoes,
										acoes_redes ac_re 
								WHERE 	acoes.id_acao = ac_re.id_acao and
										ac_re.id_ip_rede = '".$_REQUEST['id_ip_rede']."' AND
										ac_re.id_local = ".$_REQUEST['id_local'];
//echo $query_acoes_redes . '<br>';
		conecta_bd_cacic();
		$result_acoes_redes = mysql_query($query_acoes_redes);
		?>
          <tr> 
            <td>&nbsp;</td>
            <? 		
		if (mysql_num_rows($result_acoes_redes)>0)		
            echo '<td class="label"><br>Ações selecionadas para essa rede:</td>';
		else
			echo '<td class="label_vermelho"><br>Nenhuma ação selecionada para essa rede!</td>';
		?>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td height="1" colspan="4" bgcolor="#333333"></td>
          </tr>
          <?
		
		$v_contador = 0;
		while ($row = mysql_fetch_array($result_acoes_redes))
			{
			$v_contador ++;
			?>
          <tr> 
            <td></td>
            <td colspan="3" nowrap class="descricao"> <? echo $v_contador . ')&nbsp;' . $row['te_descricao_breve']; ?> 
            </td>
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
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp; 
                </p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">&nbsp;</p></td>
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">Atualizando 
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
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">Não 
                  Atualizado: <? echo $v_array_objetos_nao_atualizados[$cnt_objetos];?>! 
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
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">Enviando 
                  <? echo $v_array_objetos_enviados[$cnt_objetos];?>... 
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
              <td valign="center" bgcolor="<? echo $v_cor_zebra;?>"> <p align="left">Não 
                  Enviado <? echo $v_array_objetos_nao_enviados[$cnt_objetos];?>! 
                  <?					
						}						
					}										
				}									
			else if($_SESSION['v_status_conexao'] == 'NC')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>FTP não configurado!</strong></a></font>';					
				}
			else if($_SESSION['v_status_conexao'] == 'OFF')
				{
					echo '<font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><a href="redes/detalhes_rede.php?id_ip_rede='. $row['id_ip_rede'] .'" style="color: red"><strong>Servidor OffLine!</strong></a></font>';															
				}

		?>
              </td>
            </tr>
            <?
		}
		?>
          </table>
          <p align="center"> <br>
            <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para Esta Rede?'); " <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="ExcluiRede" type="submit" value="  Excluir Rede  " onClick="return Confirma('Confirma Exclusão de Rede?');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>
            <?
			if ($_REQUEST['nm_chamador']=='locais')
				{
				?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input name="RetornaLocais" type="button" value="  Retorna aos Detalhes de Local  " onClick="history.back()">
            <?
				}
				?>
          </p>
        </table>  
</table>
</form>
</body>
</html>
<?
}
?>
