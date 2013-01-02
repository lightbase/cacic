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
include_once("../include/library.php");

Conecta_bd_cacic();

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if ($exclui_uon2) 
	{	
	$query = "	DELETE 
				FROM 	unid_organizacional_nivel2 
				WHERE 	id_unid_organizacional_nivel2 = '$frm_id_unid_organizacional_nivel2_anterior'";

	mysql_query($query) or die($oTranslator->_('Falha em exclusao na tabela (%1) ou sua sessao expirou!',array('unid_organizacional_nivel2')));
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');			
	if (!atualiza_configuracoes_uonx('2'))
		{
		echo mensagem($oTranslator->_('Falha na atualizacao de configuracoes'));
		}
	else
		{
	    header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel2/index.php&tempo=1");								
		}	
	}
else if($_POST['gravainformacaoUON2']) 
	{
	
	$query = "	UPDATE unid_organizacional_nivel2 
				SET nm_unid_organizacional_nivel2 	= '".$_POST['frm_nm_unid_organizacional_nivel2']."',
			   	 	te_endereco_uon2 				= '".$_POST['frm_te_endereco_uon2']."',
			   	 	te_bairro_uon2 					= '".$_POST['frm_te_bairro_uon2']."',
			   	 	te_cidade_uon2 					= '".$_POST['frm_te_cidade_uon2']."',
			   	 	te_uf_uon2 						= '".$_POST['frm_te_uf_uon2']."',
			   	 	nm_responsavel_uon2 			= '".$_POST['frm_nm_responsavel_uon2']."',
			   	 	te_email_responsavel_uon2 		= '".$_POST['frm_te_email_responsavel_uon2']."',
			   	 	nu_tel1_responsavel_uon2 		= '".$_POST['frm_nu_tel1_responsavel_uon2']."',
			   	 	nu_tel2_responsavel_uon2 		= '".$_POST['frm_nu_tel2_responsavel_uon2']."',
				 	id_unid_organizacional_nivel1a 	= '".$_POST['frm_id_unid_organizacional_nivel1a']."',
				 	id_local 						= '".$_POST['frm_id_local']."' 
			  	WHERE id_unid_organizacional_nivel2 = ".$_POST['frm_id_unid_organizacional_nivel2_anterior'];
								
	$result = mysql_query($query) or die ($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('unid_organizacional_nivel2')));
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'unid_organizacional_nivel2');		
	if (!atualiza_configuracoes_uonx('2'))
		{
		echo mensagem($oTranslator->_('Falha na atualizacao de configuracoes'));
		}
	else
		{
		header ("Location: ../../../include/operacao_ok.php?chamador=../admin/patrimonio/nivel2/index.php&tempo=1");											
		}
	}
else 
	{	
	?>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">	
	<title></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<SCRIPT LANGUAGE="JavaScript">
	function ConfirmaExclusao() 
		{
		if (confirm ("<?=$oTranslator->_('Confirma exclusao de');?> "+ window.document.forms[0].frm_etiqueta2.value+"?")) 
			{
			return true;
			} 
		return false;
		}
	
	function ListarUON1a(ObjLocal)
		{
		var frm_id_unid_organizacional_nivel1a =window.document.forms[0].frm_id_unid_organizacional_nivel1a;	
		var contaUON1a = 0;
	
		frm_id_unid_organizacional_nivel1a.options.length = 0;
		for (j=0;j<document.all.listaUON1a.options.length;j++)
			{
			if (document.all.listaUON1a.options[j].id == ObjLocal.options[ObjLocal.options.selectedIndex].value)
				{
				frm_id_unid_organizacional_nivel1a.options[contaUON1a]       = new Option(document.all.listaUON1a.options[j].text);
				frm_id_unid_organizacional_nivel1a.options[contaUON1a].id    = 		   document.all.listaUON1a.options[j].id;
				frm_id_unid_organizacional_nivel1a.options[contaUON1a].value = 		   document.all.listaUON1a.options[j].value;
				contaUON1a ++;			
				}		
			}
			
		return true;
	
		}
	
	function valida_form() 
		{
			if (document.form.frm_id_unid_organizacional_nivel1a.value == 0)
				{
				alert("<?=$oTranslator->_('Por favor, selecione');?> "+ window.document.forms[0].frm_etiqueta1a.value+".");
				document.form.frm_id_unid_organizacional_nivel1a.focus();
				return false;
				} 
			if (document.form.frm_nm_unid_organizacional_nivel2.value == "")
				{
				alert("<?=$oTranslator->_('Por favor, preencha campo');?> "+ window.document.forms[0].frm_etiqueta2.value+".");
				document.form.frm_nm_unid_organizacional_nivel2.focus();
				return false;
				} 
			return true;
		}
	</script>
	</head>
	<?
	$queryUON1  = 'SELECT 	uo1.id_unid_organizacional_nivel1,
							uo1.nm_unid_organizacional_nivel1
				   FROM 	unid_organizacional_nivel1 uo1
				   ORDER BY	uo1.nm_unid_organizacional_nivel1';	

	$queryUON1a = 'SELECT 	uo1a.id_unid_organizacional_nivel1a,
							uo1a.nm_unid_organizacional_nivel1a,
							uo1a.id_unid_organizacional_nivel1
				   FROM 	unid_organizacional_nivel1a uo1a
				   ORDER BY	uo1a.nm_unid_organizacional_nivel1a';	

	$queryUON2 	= 'SELECT 	uo2.id_unid_organizacional_nivel2,
							uo2.nm_unid_organizacional_nivel2,
							uo2.id_unid_organizacional_nivel1a,
							uo2.id_local
				   FROM 	unid_organizacional_nivel2 uo2 
				   WHERE    uo2.id_unid_organizacional_nivel2 = '.$_GET['id_uon2'].' 
				   ORDER BY	uo2.nm_unid_organizacional_nivel2';	
				   
	$queryLOCAIS= "SELECT 	id_local,
							sg_local 
				   FROM 	locais
				   ORDER BY	sg_local";

	Conecta_bd_cacic();			  
	
	$result_UON1   = mysql_query($queryUON1);			
	$result_UON1a  = mysql_query($queryUON1a);			
	$result_UON2   = mysql_query($queryUON2);	
	$result_LOCAIS = mysql_query($queryLOCAIS);					
		
	$row_UON2      = mysql_fetch_array($result_UON2);			

	$id_UON1  = '';
	if(mysql_num_rows($result_UON1a))
		{	              
		while($row_UON1a = mysql_fetch_array($result_UON1a))
			{
			if ($row_UON1a['id_unid_organizacional_nivel1a'] == $row_UON2['id_unid_organizacional_nivel1a'])
				{
				$id_UON1  = $row_UON1a['id_unid_organizacional_nivel1'];
				break;
				}
			} 		
		}

	?>
	<body background="../../../imgs/linha_v.gif" onLoad="Javascript: SetaCampo('frm_id_local');">
	<div id="LayerDados" style="position:absolute; width:200px; height:115px; z-index:1; left: 100px; top: 0px; visibility: hidden">
	<?
	$queryLayerUON1a = "SELECT		UON1a.id_unid_organizacional_nivel1,
									UON1a.id_unid_organizacional_nivel1a,
									UON1a.nm_unid_organizacional_nivel1a
					   FROM 		unid_organizacional_nivel1a UON1a
					   ORDER BY		UON1a.nm_unid_organizacional_nivel1a";
	$resultLayerUON1a = mysql_query($queryLayerUON1a) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('unid_organizacional_nivel1a')));
	
	$intIdUON1a  = 0;
	
	while ($rowUON1a = mysql_fetch_array($resultLayerUON1a)) 
		$strUON1a .= '<option id="'.$rowUON1a['id_unid_organizacional_nivel1'].'" value="'.$rowUON1a['id_unid_organizacional_nivel1a'].'">'.$rowUON1a['nm_unid_organizacional_nivel1a'].'</option>';			
	
	$arrUON1a = explode('#',$strUON1a);	
	
	echo '<select name="listaUON1a">';
	for ($i=0; $i < count($arrUON1a);$i++)
		{
		echo $arrUON1a[$i];
		}
	echo '</select>';		
		
	?>
	</div>
	<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
	<table width="90%" border="0" align="center">
	  <tr> 
		<td class="cabecalho">
		 <?=$oTranslator->_('Detalhes de');?> <? echo $_SESSION['etiqueta2'];?>
		 (<?=$oTranslator->_('Unidade Organizacional Nivel 2');?>)
		</td>
	  </tr>
	  <tr> 
		<td>&nbsp;</td>
	  </tr>
	</table>
	
	<form method="post" ENCTYPE="multipart/form-data" name="form" onSubmit="return valida_form()">
	<table width="61%" border="0" align="center" cellpadding="2" cellspacing="2">
	<tr>
	<td nowrap class="label"><?=$oTranslator->_('Local');?></td>
	<td colspan="3"><select name="frm_id_local" id="frm_id_local" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
	<?			  
	while($row_LOCAIS = mysql_fetch_array($result_LOCAIS))
		{
		echo '<option value="'.$row_LOCAIS['id_local'].'" '.($row_LOCAIS['id_local']==$row_UON2['id_local']?'selected':'').'>'.$row_LOCAIS['sg_local'].'</option>';
		}
		?>
	</select></td></tr>  
	<tr> 
  	<td nowrap class="label"><? echo $_SESSION['etiqueta1']; ?>:</td>
	<td colspan="3"> <div align="left"> 
  	<select name="frm_id_unid_organizacional_nivel1" id="frm_id_unid_organizacional_nivel1"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" onChange="ListarUON1a(this)">
	<option value="0"><?=$oTranslator->_('Selecione');?> <? echo $_SESSION['etiqueta1']; ?></option>
				<?
				
	if(mysql_num_rows($result_UON1))
		{	              
		while($row_UON1 = mysql_fetch_array($result_UON1))
			{
			echo "<option value='". $row_UON1['id_unid_organizacional_nivel1']."' ".($row_UON1['id_unid_organizacional_nivel1']==$id_UON1?'selected':'').'>'.$row_UON1['nm_unid_organizacional_nivel1'].'</option>';
			} 		
		}
		?>
			  </select>
			</div></td>
			</tr>  
	
		<tr> 
		  <td nowrap class="label"><? echo $_SESSION['etiqueta1a']; ?>:</td>
		  <td colspan="3"> <div align="left"> 
			  <select name="frm_id_unid_organizacional_nivel1a" id="frm_id_unid_organizacional_nivel1a"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
				<option value="0" selected><?=$oTranslator->_('Selecione');?> <? echo $_SESSION['etiqueta1a']; ?></option>
				<?
	mysql_data_seek($result_UON1a,0);
	if(mysql_num_rows($result_UON1a))
		{	              
		while($row_UON1a = mysql_fetch_array($result_UON1a))
			{
			echo "<option value='". $row_UON1a['id_unid_organizacional_nivel1a'] . "' " . ($row_UON1a['id_unid_organizacional_nivel1a'] == $row_UON2['id_unid_organizacional_nivel1a']?'selected':'').">".$row_UON1a['nm_unid_organizacional_nivel1a'].'</option>';
			} 		
		}
		?>
			  </select>
			</div></td>
		</tr>
		<tr> 
		  <td nowrap class="label"><? echo $_SESSION['etiqueta2']; ?>:</td>
		  <td colspan="3"> <div align="left"> 
			  <input name="frm_etiqueta1a"  type="hidden" id="frm_etiqueta1a" value="<? echo $_SESSION['etiqueta1a'];?>">
			  <input name="frm_etiqueta2"  type="hidden" id="frm_etiqueta2" value="<? echo $_SESSION['etiqueta2'];?>">			  
			  <input name="frm_id_unid_organizacional_nivel2_anterior"  type="hidden" id="frm_id_unid_organizacional_nivel2_anterior" value="<? echo $row_UON2['id_unid_organizacional_nivel2'];?>">			  
			  <input name="frm_nm_unid_organizacional_nivel2"  type="text" id="frm_nm_unid_organizacional_nivel2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['nm_unid_organizacional_nivel2'];?>">			  
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><div align="left"><?=$oTranslator->_('Endereco');?></div></td>
		  <td colspan="3"> <div align="left"> 
			  <input name="frm_te_endereco_uon2" type="text"  id="frm_te_endereco_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['te_endereco_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><div align="left"><?=$oTranslator->_('Bairro');?></div></td>
		  <td colspan="3"> <div align="left"> 
			  <input name="frm_te_bairro_uon2" type="text"  id="frm_te_bairro_uon2" size="60" maxlength="30" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['te_bairro_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><?=$oTranslator->_('Cidade');?></td>
		  <td><input name="frm_te_cidade_uon2" type="text"  id="frm_te_cidade_uon2" size="20" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['te_cidade_uon2'];?>"> 
		  </td>
		  <td>&nbsp;</td>
		  <td class="label"><div align="right"><?=$oTranslator->_('Unidade da Federacao',T_SIGLA);?> 
			  <input name="frm_te_uf_uon2" type="text"  id="frm_te_uf_uon2" size="2" maxlength="2" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['te_uf_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><?=$oTranslator->_('Responsavel');?></td>
		  <td colspan="3"><div align="left">
			  <input name="frm_nm_responsavel_uon2" type="text"  id="frm_nm_responsavel_uon2" size="60" maxlength="80" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['nm_responsavel_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><?=$oTranslator->_('Endereco eletronico');?></td>
		  <td colspan="3"><div align="left"> 
			  <input name="frm_te_email_responsavel_uon2" type="text"  id="frm_te_email_responsavel_uon2" size="60" maxlength="50" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['te_email_responsavel_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td class="label"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Um',T_SIGLA);?></td>
		  <td><div align="left"> 
			  <input name="frm_nu_tel1_responsavel_uon2" type="text"  id="frm_nu_tel1_responsavel_uon2" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['nu_tel1_responsavel_uon2'];?>">
			</div></td>
		  <td nowrap class="label"><div align="right"><?=$oTranslator->_('Telefone').' '.$oTranslator->_('Dois',T_SIGLA);?></div></td>
		  <td><div align="right"> 
			  <input name="frm_nu_tel2_responsavel_uon2" type="text"  id="frm_nu_telefone2" size="20" maxlength="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $row_UON2['nu_tel2_responsavel_uon2'];?>">
			</div></td>
		</tr>
		<tr> 
		  <td><div align="left"></div></td>
		  <td>&nbsp;</td>
		  <td><div align="right"></div></td>
		</tr>
	  </table>
	  <p align="center"> 
		
	  <?
	  $v_frase = "Confirma('".$oTranslator->_('Confirma informacoes para')." ".$_SESSION['etiqueta2']."?')";
	  ?>
	  <input name="gravainformacaoUON2" type="submit" value="<?=$oTranslator->_('Gravar Alteracoes');?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'');?>  onClick="return <? echo $v_frase;?>">
	&nbsp; &nbsp; 		  
      <input name="exclui_uon2" type="submit" onClick="return ConfirmaExclusao()" id="exclui_uon2" value="<?=$oTranslator->_('Excluir');?> <? echo $_SESSION['etiqueta2'];?>" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'');?>>
		
	  </p>
	</form>
	<p>
  	<?
	}
?>
</p>
</body>
</html>
