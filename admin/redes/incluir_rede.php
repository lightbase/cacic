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

include_once "../../include/library.php";

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if($_POST['submit']) 
	{
	Conecta_bd_cacic();
	
	$query = "SELECT 	* 
			  FROM 		redes 
			  WHERE 	id_ip_rede = '".$_POST['frm_id_ip_rede']."' AND
			  			id_local = ".$_POST['frm_id_local'];
						
	$result = mysql_query($query) or die ('Select falhou ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/redes/index.php&tempo=1");									 							
		}
	else 
		{
		$query = "INSERT 
				  INTO 		redes 
				  			(id_ip_rede, 
							te_mascara_rede, 
							nm_rede, 
							te_observacao, 
							nm_pessoa_contato1, 
							nm_pessoa_contato2, 
				   			nu_telefone1, 
							nu_telefone2, 
							te_email_contato1, 
							te_email_contato2, 
							te_serv_cacic, 
							te_serv_updates, 
							nu_limite_ftp,
							te_path_serv_updates, 
							nm_usuario_login_serv_updates, 
							te_senha_login_serv_updates, 
							nm_usuario_login_serv_updates_gerente, 
							te_senha_login_serv_updates_gerente, 
							nu_porta_serv_updates,
							id_servidor_autenticacao, 
							id_local) 							
				 VALUES 	('".$_POST['frm_id_ip_rede']."',
				  		  	 '".$_POST['frm_te_mascara_rede']."',
						  	 '".$_POST['frm_nm_rede']."',
				  		  	 '".$_POST['frm_te_observacao']."', 						  
				  		  	 '".$_POST['frm_nm_pessoa_contato1']."', 
							 '".$_POST['frm_nm_pessoa_contato2']."', 
						  	 '".$_POST['frm_nu_telefone1']."',  
							 '".$_POST['frm_nu_telefone2']."', 
							 '".$_POST['frm_te_email_contato1']."', 
						  	 '".$_POST['frm_te_email_contato2']."',
						  	 '".$_POST['frm_te_serv_cacic']."',
						  	 '".$_POST['frm_te_serv_updates']."',
							  ".$_POST['frm_nu_limite_ftp'].",
						  	 '".$_POST['frm_te_path_serv_updates']."',						  
						  	 '".$_POST['frm_nm_usuario_login_serv_updates']."',
						  	 '".$_POST['frm_te_senha_login_serv_updates']."',
						  	 '".$_POST['frm_nm_usuario_login_serv_updates_gerente']."',
						  	 '".$_POST['frm_te_senha_login_serv_updates_gerente']."',			  
						  	 '".$_POST['frm_nu_porta_serv_updates']."',
							  ".$_POST['frm_id_servidor_autenticacao'].",								  
							  ".$_POST['frm_id_local'].")";									  							

		$result = mysql_query($query) or die ('Insert falhou ou sua sessão expirou!');
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'redes');

		$v_tripa_acoes = '';
		conecta_bd_cacic();

		$query_del = "DELETE 
					  FROM		acoes_redes 
					  WHERE		id_ip_rede = '".$_POST['frm_id_ip_rede']."' AND
								id_local = ".$_POST['frm_id_local'];
		mysql_query($query_del) or die('Ocorreu um erro durante a exclusão de registros na tabela acoes_redes ou sua sessão expirou!');			
		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes');

		$v_cs_situacao = ($_POST['in_habilita_acoes'] == 'S'?'S':'N');

		$query_acoes = "SELECT 	* 
						FROM 	acoes";
		$result_acoes = mysql_query($query_acoes) or die('Ocorreu um erro durante a consulta à tabela de ações ou sua sessão expirou!'); 
					
		while ($row_acoes = mysql_fetch_array($result_acoes))
			{
			if ($v_tripa_acoes <> '')
				{
				$v_tripa_acoes .= '#';
				}
			$v_tripa_acoes .= $row_acoes['id_acao'];
			$query_ins = "INSERT 
						  INTO 		acoes_redes 
									(id_ip_rede, 
									id_acao, 
									id_local,
									cs_situacao) 
						  VALUES	('".$_POST['frm_id_ip_rede']."', 
									'".$row_acoes['id_acao']."',
									".$_POST['frm_id_local'].",
									'".$v_cs_situacao."')";
			mysql_query($query_ins) or die('Ocorreu um erro durante a inclusão de registros na tabela acoes_redes ou sua sessão expirou!');
			
			}						
			
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes');							
		$v_perfis = '';
		foreach($HTTP_POST_VARS as $i => $v) 
			{
			if ($v && substr($i,0,14)=='id_aplicativo_')
				{
				if ($v_perfis <> '') $v_perfis .= '__';
				$v_perfis .= $v;		
				}
			}

		seta_perfis_rede($_POST['frm_id_local'],$_POST['frm_id_ip_rede'], $v_perfis); 			
		update_subredes($_POST['frm_id_ip_rede'],'', '*' ,$_POST['frm_id_local']); 		

		?>
	 	<SCRIPT LANGUAGE="Javascript">
	    	location = '../../include/operacao_ok.php?chamador=../admin/redes/index.php&tempo=2';
	 	</script>
		<?
		
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<?
}
else 
{
?>
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
	document.form.frm_nu_limite_ftp.value = (v_array_string[4]==""?"30":v_array_string[4])
	document.form.sel_te_serv_updates.options.selectedIndex=0;
	document.form.frm_te_senha_login_serv_updates.value = "";
	document.form.frm_te_senha_login_serv_updates_gerente.value = "";	
	var v_campo_senha = document.form.document.frm_te_senha_login_serv_updates;
	v_campo_senha.document.write('<div style="background-color:#000099;"</div>');
	v_campo_senha.document.close();
	var v_campo_senha_gerente = document.form.document.frm_te_senha_login_serv_updates_gerente;
	v_campo_senha_gerente.document.write('<div style="background-color:#000099;"</div>');
	v_campo_senha_gerente.document.close();
	
	document.form.frm_te_senha_login_serv_updates.select();
	}

function valida_form(frmForm) 
	{
	VerRedeMascara(frmForm.name,true,false);
	if ( document.form.frm_nu_limite_ftp.value == "" ) 
		{	
		document.form.frm_nu_limite_ftp.value = "30";
		}					
	
	if (document.form.frm_id_local.selectedIndex==0) {	
		alert("O local da rede é obrigatório");
		document.form.frm_id_local.focus();
		return false;
	}

	/*	
	var ip = document.form.frm_id_ip_rede.value;
	var ipSplit = ip.split(/\./);
	
	if ( document.form.frm_id_ip_rede.value == "" ) 
		{	
		alert("O endereço TCP/IP da rede é obrigatório.\nPor favor, informe-o, usando o formato X.X.X.0\nExemplo: 10.70.4.0");
		document.form.frm_id_ip_rede.focus();
		return false;
		}
	*/
	if ( document.form.frm_te_mascara_rede.value == "" ) 
		{	
		alert("A máscara de rede é obrigatória.\nPor favor, informe-a, usando o formato X.X.X.0\nExemplo: 255.255.255.0");
		document.form.frm_te_mascara_rede.focus();
		return false;
		}		
	else if ( document.form.frm_nm_rede.value == "" ) 
		{	
		alert("O nome da rede é obrigatório. Por favor, informe-o.");
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
	else if ( document.form.frm_te_path_serv_updates.value == "" ) 
		{	
		alert("Digite o Path no Servidor de Updates");
		document.form.frm_te_path_serv_updates.focus();
		return false;
		}			
	else if ( document.form.frm_nm_usuario_login_serv_updates.value == "" ) 
		{	
		alert("Digite o Nome do Usuário para Login no Servidor de Updates pelo Módulo Agente");
		document.form.frm_nm_usuario_login_serv_updates.focus();
		return false;
		}			
	else if ( document.form.frm_te_senha_login_serv_updates.value == "" ) 
		{	
		alert("Digite a Senha para Login no Servidor de Updates pelo Módulo Agente");
		document.form.frm_te_senha_login_serv_updates.focus();
		return false;
		}				
	else if ( document.form.frm_nm_usuario_login_serv_updates_gerente.value == "" ) 
		{	
		alert("Digite o Nome do Usuário para Login no Servidor de Updates pelo Módulo Gerente");
		document.form.frm_nm_usuario_login_serv_updates_gerente.focus();
		return false;
		}		
	else if ( document.form.frm_te_senha_login_serv_updates_gerente.value == "" ) 
		{	
		alert("Digite a Senha para Login no Servidor de Updates pelo Módulo Gerente");
		document.form.frm_te_senha_login_serv_updates_gerente.focus();
		return false;
		}					
	return true;
	}
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_local')">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Inclus&atilde;o de Nova Subrede</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es que dever&atilde;o ser 
      cadastradas abaixo referem-se a uma subrede onde ser&atilde;o instalados 
      os agentes oper&aacute;rios do CACIC. Os campos &quot;Subrede&quot; e &quot;Descri&ccedil;&atilde;o&quot; 
      s&atilde;o obrigat&oacute;rios.</td>
  </tr>
</table>
<form action="incluir_rede.php"  method="post" ENCTYPE="multipart/form-data" name="form" id="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
		<td>&nbsp;</td>
      <td class="label"><br>Local:</td>
      <td class="label" colspan="2"><br>Servidor para Autentica&ccedil;&atilde;o:</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td> <select name="frm_id_local" id="frm_id_local"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          <?
			$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE id_local = '.$_SESSION['id_local']:'');
			if (trim($_SESSION['te_locais_secundarios'])<>'' && $where <> '')
				{
				// Faço uma inserção de "(" para ajuste da lógica para consulta
				$where = str_replace(' id_local = ','(id_local = ',$where);
				$where .= ' OR id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
				}
			
			Conecta_bd_cacic();				
			$qry_locais = "SELECT 	id_local,
											sg_local 
								 FROM 		locais ".
								 			$where." 
								 ORDER BY	sg_local";

	    $result_locais = mysql_query($qry_locais) or die ('Select falhou ou sua sessão expirou!');
		echo '<option value="0">Selecione Local</option>';		  				
		while ($row=mysql_fetch_array($result_locais))
			{ 
			echo "<option value=\"" . $row["id_local"] . "\"";			
			if ($row['id_local']==$_SESSION['id_local'])
				echo ($_SESSION['cs_nivel_administracao']<>1?" selected":"");
			echo ">" . $row["sg_local"] . "</option>";
		   	} 
			?>
        </select>
		<?
		//if ($_SESSION['cs_nivel_administracao']<>1)
		//	echo '<input name="frm_id_local" id="frm_id_local" type="hidden" value="'.$_SESSION['id_local'].'">';
		?> </td>
      <td nowrap><select name="frm_id_servidor_autenticacao" id="frm_id_servidor_autenticacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="" selected></option>
          <?
			  
		$qry_servidor_autenticacao = "SELECT 		id_servidor_autenticacao, 
									nm_servidor_autenticacao
						FROM 		servidores_autenticacao
						ORDER BY	nm_servidor_autenticacao";

		$result_servidor_autenticacao = mysql_query($qry_servidor_autenticacao) or die ('Falha na consulta &agrave; tabela Servidores de Autenticação ou sua sess&atilde;o expirou!');
			  
				while($row = mysql_fetch_array($result_servidor_autenticacao))
					echo '<option value="'.$row['id_servidor_autenticacao'].'">'.$row['nm_servidor_autenticacao'].'</option>';
					
					?>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td class="label"><br>Subrede:</td>
      <td nowrap class="label"><br>M&aacute;scara:</td>
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td><input name="frm_id_ip_rede" id="frm_id_ip_rede" type="text"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" size="16" maxlength="16" > 
        <font color="#000099" size="1">Ex.: 10.71.0.0</font></font></td>
      <td><input name="frm_te_mascara_rede" id="frm_te_mascara_rede" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="return VerRedeMascara(this.form.name,false,true);SetaClassNormal(this);" value="255.255.255.0" size="15" maxlength="15" > 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td class="label"><div align="left">Descri&ccedil;&atilde;o:</div></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
		<td>&nbsp;</td>
      <td nowrap><input name="frm_nm_rede" type="text" id="frm_nm_rede" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap class="label"><br>
        Servidor de Aplica&ccedil;&atilde;o:</td>
      <td>&nbsp;&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
	<?
    	$sql = "select * from configuracoes_padrao";
    	$db_result = mysql_query($sql);
    	$cfgStdData = (!mysql_errno())?mysql_fetch_assoc($db_result):'';
	?>
      <td nowrap> <input name="frm_te_serv_cacic" type="text" id="frm_te_serv_cacic" size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$cfgStdData['te_serv_cacic_padrao']?>"> 
        <select name="sel_te_serv_cacic" id="sel_te_serv_cacic" onChange="SetaServidorBancoDados();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="">===> Selecione <===</option>
          <?
			Conecta_bd_cacic();
			$query = "SELECT DISTINCT 	configuracoes_locais.te_serv_cacic_padrao, 
										redes.te_serv_cacic
			          FROM   			redes LEFT JOIN configuracoes_locais on (configuracoes_locais.te_serv_cacic_padrao = redes.te_serv_cacic)
					  WHERE  			redes.id_local = ".$_SESSION['id_local'] . "  
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
	<td>&nbsp;</td>
      <td nowrap class="label"><div align="left"><br>
          Servidor de Updates (FTP):</div></td>
      <td class="label"><div align="left"><br>
          Porta:</div></td>
      <td valign="bottom" class="label"><br>
	  Limite FTP:</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap><input name="frm_te_serv_updates" type="text" id="frm_te_serv_updates"  size="16" maxlength="16" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?=$cfgStdData['te_serv_updates_padrao']?>"> 
        <select name="sel_te_serv_updates" id="sel_te_serv_updates" onChange="SetaServidorUpdates();" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          <option value="">===> Selecione <===</option>
          <?
			Conecta_bd_cacic();
			$query = "SELECT DISTINCT 	redes.te_serv_updates, 
										redes.te_path_serv_updates,
										redes.nm_usuario_login_serv_updates,
										redes.nu_porta_serv_updates
			          FROM   redes
					  WHERE  redes.id_local = ".$_SESSION['id_local'] ."  
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
      <td><input name="frm_nu_porta_serv_updates" type="text" class="normal" id="frm_nu_porta_serv_updates" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="21" size="15" maxlength="4" > 
      </td>
      <td><input name="frm_nu_limite_ftp" type="text" id="frm_nu_limite_ftp" size="5" maxlength="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="100"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap class="label"><br>
        Usu&aacute;rio do Servidor de Updates: (para AGENTE)</td>
      <td nowrap class="label"><div align="left"><br>
          Senha para Login:</div></td>
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap> <input name="frm_nm_usuario_login_serv_updates" type="text" id="frm_nm_usuario_login_serv_updates" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td> <input name="frm_te_senha_login_serv_updates" type="password" id="frm_te_senha_login_serv_updates" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap class="label"><br>
        Usu&aacute;rio do Servidor de Updates: (para GERENTE)</td>
      <td nowrap class="label"><div align="left"><br>
          Senha para Login:</div></td>
      <td nowrap class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap> <input name="frm_nm_usuario_login_serv_updates_gerente" type="text" id="frm_nm_usuario_login_serv_updates_gerente" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td> <input name="frm_te_senha_login_serv_updates_gerente" type="password" id="frm_te_senha_login_serv_updates_gerente" size="15" maxlength="15" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td nowrap class="label"><br>
        Path no Servidor de Updates:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td><input name="frm_te_path_serv_updates" type="text" id="frm_te_path_serv_updates" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
      <td>&nbsp;</td>
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
      <td><textarea name="frm_te_observacao" cols="38" id="textarea" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></textarea></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td class="label"><br>
        Contato 1:</td>
      <td width="144" class="label"><br>
        Telefone: </td>
      <td width="144" class="label">&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td> <input name="frm_nm_pessoa_contato1" type="text" id="frm_nm_pessoa_contato12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td> <input name="frm_nu_telefone1" type="text" id="frm_nu_telefone12" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
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
      <td><input name="frm_te_email_contato1" type="text" id="frm_te_email_contato1" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
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
      <td> <input name="frm_nm_pessoa_contato2" type="text" id="frm_nm_pessoa_contato2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td> <input name="frm_nu_telefone2" type="text" id="frm_nu_telefone2" size="12" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
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
      <td> <input name="frm_te_email_contato2" type="text" id="frm_te_email_contato22" size="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td colspan="3" class="label">Marcar todas as a&ccedil;&otilde;es para essa 
        rede:</td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#333333"></td>
    </tr>
    <tr> 
		<td>&nbsp;</td>
      <td colspan="3" class="descricao"><div align="justify"> Essa op&ccedil;&atilde;o 
          habilitar&aacute; as a&ccedil;&otilde;es de auto-update e coletas nas 
          esta&ccedil;&otilde;es situadas nesta rede. Caso seja necess&aacute;rio 
          algum ajuste, este poder&aacute; ser feito em Administra&ccedil;&atilde;o/M&oacute;dulos.</div></td>
    </tr>
    <tr> 
      <td colspan="4" height="1" bgcolor="#CCCCCC"></td>
    </tr>
    <tr> 
	<td>&nbsp;</td>
      <td> <input name="in_habilita_acoes" type="radio" value="S" checked class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        Sim<br> <input type="radio" name="in_habilita_acoes" value="N" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        N&atilde;o</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	<?
	include "../../include/opcoes_sistemas_monitorados.php";
	?>
  </table>
  <p align="center"> 
    <input name="submit" type="submit" value="  Gravar Informa&ccedil;&otilde;es  "  onClick="return valida_form(this);return Confirma('Confirma Inclusão de Rede?');">
  </p>
</form>
<p>
  <?
}
?>
</p>
</body>
</html>
