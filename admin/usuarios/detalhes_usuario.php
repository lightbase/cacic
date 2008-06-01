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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');

AntiSpy();
Conecta_bd_cacic();

if ($_POST['ExcluiUsuario']) 
	{
	$query = "DELETE 
			  FROM 		usuarios 
			  WHERE 	id_usuario = '". $_POST['frm_id_usuario'] ."' AND
			  			id_local = ".$_REQUEST['id_local'];
	mysql_query($query) or die($oTranslator->_('kciq_msg delete row on table fail', array('usuarios'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	}
elseif ($_POST['GravaAlteracoes']) 
	{
	$v_te_locais_secundarios = $_POST['frm_te_locais_secundarios'];
	// Atenção: Caso o nível seja Administração ou Gestão Central ou tenha-se desfeito referência a locais secundários, é necessário "limpar" os locais secundários...
	if ($_POST['frm_id_grupo_usuarios'] == 2 || $_POST['frm_id_grupo_usuarios'] == 5 || $_POST['frm_te_locais_secundarios'] == 0)
		{
		$v_te_locais_secundarios = '';
		}
		
	$query = "UPDATE 	usuarios 
			  SET 		nm_usuario_acesso = '$frm_nm_usuario_acesso',  
			  			nm_usuario_completo = '$frm_nm_usuario_completo', 
						id_grupo_usuarios = '$frm_id_grupo_usuarios',
						id_local = $frm_id_local,
						te_emails_contato = '$frm_te_emails_contato',
						te_telefones_contato = '$frm_te_telefones_contato',
						te_locais_secundarios = '$v_te_locais_secundarios'						
			  WHERE 	id_usuario = ". $_POST['frm_id_usuario'];

	mysql_query($query) or die($oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('usuarios')));

	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	
	}
elseif ($_POST['ReinicializaSenha']) 
	{
	$query = "UPDATE 	usuarios 
			  SET		te_senha = PASSWORD('".$_POST['frm_nm_usuario_acesso']."')
			  WHERE 	id_usuario = ". $_POST['frm_id_usuario'] ." AND
			  			id_local = ".$_POST['frm_id_local'];
	mysql_query($query) or die($oTranslator->_('Ocorreu um erro durante a atualizacao da tabela %1 ou sua sessao expirou', array('usuarios')));
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'usuarios');	
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 							
	
	}
else 
	{
	$query = "SELECT 	a.nm_usuario_acesso, 
						a.nm_usuario_completo, 
						a.id_grupo_usuarios, 
						a.id_local,
						a.te_emails_contato,
						a.te_telefones_contato, 
						loc.sg_local,
						loc.nm_local
			  FROM 		usuarios a, 
			  			locais loc
			  WHERE 	a.id_usuario = ".$_GET['id_usuario']." and 
			  			a.id_local = loc.id_local";

	$result = mysql_query($query) or die ($oTranslator->_('kciq_msg select on table fail', array('usuarios'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
	$row_usuario = mysql_fetch_array($result);
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
		
	function valida_form() {
	
		if (document.form.frm_nm_usuario_acesso.value == "" ) {	
			alert("A identificação do usuário é obrigatória");
			document.form.frm_nm_usuario_acesso.focus();
			return false;
		}
		if (document.form.frm_nm_usuario_completo.value == "" ) {	
			alert("O nome completo do usuário é obrigatório");
			document.form.frm_nm_usuario_completo.focus();
			return false;
		}
		if (document.form.frm_sel_id_locais_secundarios.value != "" ) 
			{
			var intLoop;
			var strIdLocaisSecundarios;
			
			strIdLocaisSecundarios = "";
			
			for (intLoop = 0; intLoop < document.form.frm_sel_id_locais_secundarios.options.length; intLoop++)
				{
				if (document.form.frm_sel_id_locais_secundarios.options[intLoop].selected)
					{
					if (strIdLocaisSecundarios != "")
						strIdLocaisSecundarios += ",";
						
					strIdLocaisSecundarios += document.form.frm_sel_id_locais_secundarios.options[intLoop].value;
					}
				}
			document.form.frm_te_locais_secundarios.value = strIdLocaisSecundarios;
			}
	
		return true;
	
	}
	team = new Array(
	<? 
	$sql='select * from locais ';
	$where = '';
	if ($_SESSION['te_locais_secundarios']<>'')
		{
		$where = ' where id_local = '.$_SESSION['id_local'].' OR id_local in ('.$_SESSION['te_locais_secundarios'].') ';
		}
	$sql .= $where . ' order by sg_local'; 
	conecta_bd_cacic();
	$sql_result=mysql_query($sql); 
	$num=mysql_numrows($sql_result); 
	$contador = 0;
	while ($row=mysql_fetch_array($sql_result))
		{ 
		$contador ++;
		if ($contador > 1)
			echo ",\n";
	
		$v_id_local=$row["id_local"]; 
		$v_sg_local=$row["sg_local"] . ' / '.$row["nm_local"]; 
		echo "new Array(\"$v_id_local\", \"$v_sg_local\")"; 
		} 
	echo ");\n"; 	
	?> 
	
	function fillSelectFromArray(selectCtrl, itemArray, itemAtual) 
		{ 	
		var i, j; 
		selectCtrl.disabled = true;			
		// empty existing items 
		for (i = selectCtrl.options.length; i >= 0; i--) 
			{ 
			selectCtrl.options[i] = null; 
			} 
	
		if (itemArray != null && itemAtual != 0) 
			{ 
			selectCtrl.size = 5;
			selectCtrl.disabled = false;			
			j = 1;
			// add new items 
			selectCtrl.options[0] = new Option("","          "); 		
			
			for (i = 0; i < itemArray.length; i++) 
				{ 
				if (itemArray[i][0] != itemAtual)
					{
					selectCtrl.options[j] = new Option(itemArray[i][0]); 
					if (itemArray[i][0] != null) 
						{ 
						selectCtrl.options[j].value = itemArray[i][0]; 					
						selectCtrl.options[j].text = itemArray[i][1]; 										
						} 
					j++; 
					}				
				} 
			// select first item (prompt) for sub list 
			selectCtrl.options[0].selected = true; 
		   } 
		}
	</script>
	</head>
	
	<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_local')">
	<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
	<table width="90%" border="0" align="center">
	  <tr> 
		<td class="cabecalho"><?=$oTranslator->_('Detalhes de usuario');?></td>
	  </tr>
	  <tr> 
		<td class="descricao"><?=$oTranslator->_('kciq_msg Detalhes de usuario help');?></td>
	  </tr>
	</table>
	<p>&nbsp;</p><table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
	  <tr> 
		<td valign="top"> 
	<form action="detalhes_usuario.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
			<table border="0" cellpadding="2" cellspacing="2">
			  <tr> 
				<td class="label"><?=$oTranslator->_('Local');?>:</td>
				<td>
				<select name="frm_id_local" id="frm_id_local"" class="normal" onChange="fillSelectFromArray(this.form.frm_sel_id_locais_secundarios, team,this.form.frm_id_local.value);" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"
				<?
				echo ($_SESSION['cs_nivel_administracao']>1 && !($_SESSION['cs_nivel_administracao']==3 && $_SESSION['te_locais_secundarios']<>'')?'disabled':'');	?>>
					<? 
				$where = '';
				if ($_SESSION['te_locais_secundarios']<>'' && $_SESSION['cs_nivel_administracao']==3)
					{
					// Faço uma inserção de "(" para ajuste da lógica para consulta	
					$where = 'WHERE id_local = '.$_SESSION['id_local'].' OR id_local IN ('.$_SESSION['te_locais_secundarios'].') ';
					}
				
				$qry_locais = "SELECT 			id_local,
												sg_local,
												nm_local 
									 FROM 		locais ".
									 $where . "
									 ORDER BY	sg_local";
				$result_locais = mysql_query($qry_locais) or die ('Select em "locais" falhou ou sua sessão expirou!');
				while ($row_qry=mysql_fetch_array($result_locais))
					{
					echo '<option value="'.$row_qry[0].'"';
					if ($row_qry['id_local'] == $row_usuario["id_local"]) 
						{
						$v_sg_local = $row_qry[1]; 					
						echo 'selected';
						}
					echo ' id='.$row_qry[0].'>'. $row_qry[1].' ('.$row_qry[2].')'; 
					}						
					?> 
				</select> 
				<?
			// Se não for nível Administrador então fixa o id_local...
			if ($_SESSION['cs_nivel_administracao']<>1 && !($_SESSION['cs_nivel_administracao']==3 && $_SESSION['te_locais_secundarios']<>''))
				echo '<input name="frm_id_local" type="hidden" id="frm_id_local" value="'.$_SESSION['id_local'].'">';		
	
			$qry_locais_secundarios    = "SELECT 	a.te_locais_secundarios
										  FROM 	    usuarios a
										  WHERE 	a.id_usuario = ".$_GET['id_usuario']; 
			$result_locais_secundarios = mysql_query($qry_locais_secundarios) or 
											die ($oTranslator->_('kciq_msg select on table fail', array('usuarios'))."! ".
													$oTranslator->_('kciq_msg session fail',false,true)."!");
			$row_locais_secundarios    = mysql_fetch_array($result_locais_secundarios);		
			$arr_locais_secundarios    = explode(',',$row_locais_secundarios['te_locais_secundarios']);
	
			mysql_data_seek($result_locais,0);
			?> 
			</td>
			  </tr>
			  <tr> 
				<td class="label"><?=$oTranslator->_('Locais secundarios');?>:</td>
				<td><select name="frm_sel_id_locais_secundarios" id="frm_sel_id_locais_secundarios" size="5" multiple class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3 || ($_SESSION['cs_nivel_administracao']== 3 && ($_GET['cs_nivel_administracao'] <> 0 && $_GET['cs_nivel_administracao'] <> 4))?"disabled":"");?>>
					<option value="0"></option>
					<?
				while ($row_locais_secundarios = mysql_fetch_array($result_locais))
					{
					if ($row_locais_secundarios['id_local'] <> $row_usuario['id_local']) 
						{				
						echo '<option value="'.$row_locais_secundarios['id_local'].'" ';
						if (in_array($row_locais_secundarios['id_local'], $arr_locais_secundarios))
							{
							echo 'selected ';
							}
						echo ' id=' . $row_locais_secundarios['id_local'].'>'. $row_locais_secundarios['sg_local'].' / '.$row_locais_secundarios['nm_local'].'</option>';
						}
					}						
					?> 
				</select> <input name="frm_te_locais_secundarios" type="hidden" id="frm_te_locais_secundarios">	
				  <br> <font color="#000080" size="1">
				  	(<?=$oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)
				  </font></td>
			  </tr>
			  <tr>
				<td class="label">&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr> 
				<td class="label"><?=$oTranslator->_('Identificacao');?>:</td>
				<td><input name="frm_nm_usuario_acesso"  readonly="" type="text" id="frm_nm_usuario_acesso" value="<? echo mysql_result($result, 0, 'nm_usuario_acesso'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
			  </tr>
			  <tr> 
				<td class="label"><?=$oTranslator->_('Nome Completo');?>:</td>
				<td><input name="frm_nm_usuario_completo" type="text" id="frm_nm_usuario_completo" value="<? echo mysql_result($result, 0, 'nm_usuario_completo'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
				  <input name="frm_id_usuario" type="hidden" id="frm_id_usuario" value="<? echo $_GET['id_usuario']; ?>"> 
				  <input name="id_local" type="hidden" id="id_usuario" value="<? echo $_GET['id_local']; ?>"></td>
			  </tr>
			  <tr nowrap> 
				<td nowrap class="label"><?=$oTranslator->_('Endereco eletronico');?>:</td>
				<td nowrap><input name="frm_te_emails_contato" type="text" id="frm_te_emails_contato" value="<? echo mysql_result($result, 0, 'te_emails_contato'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
			  </tr>
			  <tr nowrap> 
				<td nowrap class="label"><?=$oTranslator->_('Telefones para Contato');?>:</td>
				<td nowrap><input name="frm_te_telefones_contato" type="text" id="frm_te_telefones_contato" value="<? echo mysql_result($result, 0, 'te_telefones_contato'); ?>" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
			  </tr>
			  <tr nowrap> 
				<td nowrap class="label"><?=$oTranslator->_('Tipo de Acesso');?>:</td>
				<td nowrap> <select name="frm_id_grupo_usuarios" id="frm_id_grupo_usuarios" onChange="SetaDescGrupo(this.options[selectedIndex].id,'frm_te_descricao_grupo')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <? echo ($_GET['id_usuario']==$_SESSION['id_usuario'] && $_SESSION['cs_nivel_administracao']<>1 || ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3 || ($_SESSION['cs_nivel_administracao']== 3 && ($_GET['cs_nivel_administracao'] <> 0 && $_GET['cs_nivel_administracao'] <> 4)))?'disabled':'');?>>
					<? 
				$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' WHERE (cs_nivel_administracao > '.$_SESSION['cs_nivel_administracao'].' OR cs_nivel_administracao = 0)':'');
				$qry_grp_usu = "SELECT 		id_grupo_usuarios, 
											te_grupo_usuarios,
											te_descricao_grupo 
								FROM 		grupo_usuarios ".
											$where . "
								ORDER BY	te_grupo_usuarios";
				$result_qry_grp = mysql_query($qry_grp_usu) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou', array('grupo_usuarios')));
				while ($row_qry=mysql_fetch_array($result_qry_grp))
					{
					echo '<option value="'.$row_qry[0].'"';
					if ($row_qry['id_grupo_usuarios'] == $row_usuario["id_grupo_usuarios"]) 
						{
						$v_te_descricao_grupo = $row_qry[2]; 					
						echo 'selected';
						}
						?> id="
					<? echo $row_qry[2];?>"><? echo $row_qry[1];?></option> 
					<?
					}
							
				?>
				  </select> </td>
			  </tr>
			  <tr nowrap> 
				<td nowrap class="label"><?=$oTranslator->_('Descricao do tipo de acesso');?>:</td>
				<td nowrap><textarea name="frm_te_descricao_grupo" cols="50" rows="4" id="frm_te_descricao_grupo" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $v_te_descricao_grupo;?></textarea></td>
			  </tr>
			</table>
			<p align="center"> 
			  <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="<?=$oTranslator->_('Gravar alteracoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma informacoes para usuario?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3 || ($_SESSION['cs_nivel_administracao']== 3 && ($_GET['cs_nivel_administracao'] <> 0 && $_GET['cs_nivel_administracao'] <> 4))?'disabled':'')?>>
			  &nbsp;&nbsp;
			  <input name="ReinicializaSenha" type="submit" id="ReinicializaSenha" value="<?=$oTranslator->_('Reinicializar senha');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3 || ($_SESSION['cs_nivel_administracao']== 3 && ($_GET['cs_nivel_administracao'] <> 0 && $_GET['cs_nivel_administracao'] <> 4))?'disabled':'')?>>
			  &nbsp; &nbsp; 
			  <input name="ExcluiUsuario" type="submit" id="ExcluiUsuario" value="<?=$oTranslator->_('Excluir usuario');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma exclusao de usuario?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3 || ($_SESSION['cs_nivel_administracao']== 3 && ($_GET['cs_nivel_administracao'] <> 0 && $_GET['cs_nivel_administracao'] <> 4))?'disabled':'')?>>
				<?
				if ($_REQUEST['nm_chamador'])
					{
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					<input name="Retorna" type="button" value="  Retorna para <? echo str_replace("_"," ",$_REQUEST['nm_chamador']);?>  " onClick="history.back()">
					<?
					}
					?>
					</p>
			  
		  </form></td>
	  </tr>
	</table>
	</body>
	</html>
	<?
	}
?>
