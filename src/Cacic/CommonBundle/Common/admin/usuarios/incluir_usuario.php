<?php
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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

include_once "../../include/library.php";

AntiSpy();
Conecta_bd_cacic();

if($_POST['submit']) 
	{	
	$query = "SELECT 	* 
			  FROM 		usuarios 
			  WHERE 	nm_usuario_acesso = '".$_POST['frm_nm_usuario_acesso']."'";
	$result = mysql_query($query) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou', array('usuarios')));
	
	if (mysql_num_rows($result) > 0) 
		{
		header ("Location: ../../include/registro_ja_existente.php?chamador=../admin/usuarios/index.php&tempo=1");									 												
		}
	else 
		{
		$query = "INSERT 
				  INTO 		usuarios
				  			(nm_usuario_acesso, 
							 nm_usuario_completo, 
							 te_senha,  
							 dt_log_in, 
							 id_grupo_usuarios,
							 id_local,
							 id_servidor_autenticacao,							 
							 id_usuario_ldap,
							 te_emails_contato,
							 te_telefones_contato,
							 te_locais_secundarios) 
				  VALUES 	('".$_POST['frm_nm_usuario_acesso']."', 
				  			'".$_POST['frm_nm_usuario_completo']."', 
				  		  	PASSWORD('".$_POST['frm_nm_usuario_acesso']."'), 
							now(),
							'".$_POST['frm_id_grupo_usuarios']."',
							'".$_POST['frm_id_local']."',
							'".$_POST['frm_id_servidor_autenticacao']."',							
							'".$_POST['frm_id_usuario_ldap']."',														
							'".$_POST['frm_te_emails_contato']."',
							'".$_POST['frm_te_telefones_contato']."',
							'".$_POST['frm_te_locais_secundarios']."')";
		$result = mysql_query($query) or die ($oTranslator->_('kciq_msg insert row on table fail', array('usuarios'))."! ".$oTranslator->_('kciq_msg session fail',false,true));
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'usuarios',$_SESSION["id_usuario"]);		
		header ("Location: ../../include/operacao_ok.php?chamador=../admin/usuarios/index.php&tempo=1");									 											
		}
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<?php
	}
else 
	{
	?>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
	<title></title>
	<script language="JavaScript" type="text/javascript" src="../../include/js/jquery.js"></script>            
	<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>    
	<script language="JavaScript" type="text/javascript" src="../../include/js/ajax/usuarios.js"></script>                    
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php
	include_once "../../include/selecao_locais_usuarios_inc.php";
	?>	
	</head>
	
	<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_id_local')">

	<table width="85%" border="0" align="center">
	  <tr> 
		<td class="cabecalho"><div align="left"><?php echo $oTranslator->_('Inclusao de novo usuario');?></div></td>
	  </tr>
	  <tr> 
		<td class="descricao">
			<?php echo $oTranslator->_('kciq_msg Inclusao de novo usuario help');?>
		</td>
	  </tr>
	</table>
	<?php
	$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE id_local = '.$_SESSION['id_local']:'');
	if (trim($_SESSION['te_locais_secundarios'])<>'' && $where <> '')
		{
		// Fa�o uma inser��o de "(" para ajuste da l�gica para consulta
		$where = str_replace('id_local = ','(id_local = ',$where);
		$where .= ' OR id_local IN ('.$_SESSION['te_locais_secundarios'].'))';	
		}

	$qry_local = "SELECT 		id_local, 
									sg_local,
									nm_local 
						FROM 		locais ".
									$where . "
						ORDER BY	sg_local";
	
	$result_local = mysql_query($qry_local) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou', array('locais')));
	?>
	
	  <p>&nbsp;</p><form action="incluir_usuario.php"  method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
	  <table border="0" align="center" cellpadding="2" cellspacing="2">
		<tr> 
		  <td class="label"><?php echo $oTranslator->_('Local primario');?>:</td>
		  <td><select name="frm_id_local" id="frm_id_local" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="fillSecundariosFromPrimarios(this.form.frm_sel_id_locais_secundarios, arrayLocais,this.form.frm_id_local.value);fillModoAutenticacao(this.form.frm_id_local.value,this.form.frm_id_servidor_autenticacao);" <?php echo ($_SESSION['cs_nivel_administracao']<>1 && ($_SESSION['cs_nivel_administracao']<>3 && !$_SESSION['cs_nivel_administracao'])?"disabled":"");?>>
			  <?php
				echo '<option value="0">Selecione Local Primario</option>';		  
				while($row = mysql_fetch_row($result_local))
					{
					echo '<option value="'.$row[0].'"';
					echo ($_SESSION['cs_nivel_administracao']<>1?" selected ":"");
					echo '>'.$row[1].' / '.$row[2].'</option>';
					}
					?>
			</select> 
			<?php
			// Se n�o for n�vel Administrador ent�o fixa o id_local...
			if ($_SESSION['cs_nivel_administracao']<>1 && ($_SESSION['cs_nivel_administracao']<>3 && !$_SESSION['cs_nivel_administracao']))
				echo '<input name="frm_id_local" type="hidden" id="frm_id_local" value="'.$_SESSION['id_local'].'">';		
			?>
		  </td>
		</tr>
		<tr valign="top"> 
		  <td align="left" valign="top" nowrap class="label">
		  	<?php echo $oTranslator->_('Locais secundarios');?>:
		  </td>
		  <td>
	<select name="frm_sel_id_locais_secundarios" id="frm_sel_id_locais_secundarios" multiple disabled size="5" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" <?php echo ($_SESSION['cs_nivel_administracao']<>1?"disabled":"");?>>
			  <option value=""></option>
			</select>
			<input name="frm_te_locais_secundarios" type="hidden" id="frm_te_locais_secundarios">		
			<br>
			<font color="#000080" size="1">
				(<?php echo $oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)
			</font></td>
		</tr>
		<tr>
		  <td class="label">&nbsp;</td>
		  <td>&nbsp;</td>
		</tr>
        
        
		<tr nowrap>
          <td nowrap class="label"><?php echo $oTranslator->_('ServidorAutenticacao de Autenticacao');?>:</td>
		  <td nowrap><select name="frm_id_servidor_autenticacao" id="frm_id_servidor_autenticacao" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
			<option value="0">Base CACIC</option>          
          </select></td>
	    </tr>
		<tr>
		  <td class="label">&nbsp;</td>
		  <td>&nbsp;</td>
	    </tr>

		<tr> 		          
		  <td class="label"><?php echo $oTranslator->_('Identificacao');?>:</td>
		  <td> <input name="frm_nm_usuario_acesso" type="text"   id="frm_nm_usuario_acesso" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
               <input name="frm_id_usuario_ldap"   type="hidden" id="frm_id_usuario_ldap">
            </td>
		</tr>
		</div>                    
		<tr> 

		  <td class="label"><?php echo $oTranslator->_('Nome completo');?>:</td>
		  <td><input name="frm_nm_usuario_completo" type="text" id="frm_nm_usuario_completo" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
          &nbsp;&nbsp;</td>
		</tr>
		<?php
		$where = ($_SESSION['cs_nivel_administracao']<>1?' WHERE (cs_nivel_administracao > '.$_SESSION['cs_nivel_administracao'].' OR cs_nivel_administracao=0)':'');
		$qry_grp_usu = "SELECT 		id_grupo_usuarios, 
									te_grupo_usuarios, 
									te_descricao_grupo 
						FROM 		grupo_usuarios ".
									$where . "
						ORDER BY	te_grupo_usuarios";
		$result_qry_grp = mysql_query($qry_grp_usu) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou', array('grupo_usuarios')));
	?>
		<tr nowrap> 
		  <td class="label"><?php echo $oTranslator->_('Endereco eletronico');?>:</td>
		  <td><input name="frm_te_emails_contato" type="text" id="frm_te_emails_contato" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
		</tr>
		<tr nowrap> 
		  <td class="label"><?php echo $oTranslator->_('Telefones para contato');?>:</td>
		  <td><input name="frm_te_telefones_contato" type="text" id="frm_te_telefones_contato" size="50" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
		</tr>
		<tr nowrap> 
		  <td class="label"><?php echo $oTranslator->_('Tipo de acesso');?>:</td>
		  <td> <select name="frm_id_grupo_usuarios" id="frm_id_grupo_usuarios" onChange="SetaDescGrupo(this.options[selectedIndex].id,'frm_te_descricao_grupo')" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			  <?php
				while($row = mysql_fetch_row($result_qry_grp))
					{
					if (!$v_te_descricao_grupo) $v_te_descricao_grupo = $row[2]; 				
					echo '<option value="'.$row[0].'" id="'.$row[2].'">'.$row[1].'</option>';
					}
					?>
			</select></td>
		</tr>
		<tr nowrap> 
		  <td class="label"><?php echo $oTranslator->_('Descricao do tipo de acesso');?>:</td>
		  <td><textarea name="frm_te_descricao_grupo" cols="50" rows="4" id="frm_te_descricao_grupo" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><?php echo $v_te_descricao_grupo;?></textarea></td>
		</tr>
		<tr>
          <div style="text-align:left;">        
		  <td><div class="dado_peq_sem_fundo_sem_bordas"   			name="te_origem_label" id="te_origem_label"></div></td>
          <td><div class="dado_peq_sem_fundo_sem_bordas_destacado" 	name="te_origem_value" id="te_origem_value"></div></td>
          </div>          
        </tr>        
	  </table>
	  <p align="center"> <br>
		<br>
		<input name="submit" type="submit" value="<?php echo $oTranslator->_('Gravar informacoes');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma inclusao de usuario?');?>');">
	  </p>
	</form>
	<p>
	<?php
	}
?>
</p>
</body>
</html>
