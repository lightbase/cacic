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
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../../include/library.php');
AntiSpy();
conecta_bd_cacic();

if ($_POST['gravar']) 
	{	
	$query = "UPDATE 	patrimonio_config_interface SET 
						te_etiqueta = '" . $_POST['te_etiqueta'] . "', 
						in_exibir_etiqueta = '" . $_POST['in_exibir_etiqueta'] . "', 
						te_help_etiqueta = '" . $_POST['te_help_etiqueta'] . "', 
						in_destacar_duplicidade = '" . $_POST['in_destacar_duplicidade'] . "', 			  
						te_plural_etiqueta = '" . $_POST['te_plural_etiqueta'] . "'
			  WHERE 	id_etiqueta = '" . $_POST['id_etiqueta'] . "' AND
			  			id_local = ".$_SESSION['id_local']; 
	mysql_query($query);
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface',$_SESSION["id_usuario"]);						
	$query = 'UPDATE	configuracoes_locais SET 
						dt_hr_alteracao_patrim_interface = NOW()
			  WHERE		id_local = '.$_SESSION['id_local']; 
	mysql_query($query);
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais',$_SESSION["id_usuario"]);		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/patrimonio/config_tela_patrimonio.php&tempo=1");									 										
	}
else 
	{
	$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND id_local = '.$_SESSION['id_local']:'');
	$where = ' AND id_local = '.$_SESSION['id_local'];			
	$query = "SELECT 	* 
			  FROM 		patrimonio_config_interface 
			  WHERE 	id_etiqueta = '" . $_GET['id_etiqueta'] . "'".
						$where; 
	$result = mysql_query($query) or die($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface'))); 
	$campos = mysql_fetch_array($result);
	?>
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
	<title><?php echo $oTranslator->_('Configuracao da Tela de Patrimonio');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	</head>
	<body background="../../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
	  </p>
	  </div>
	<form name="form1" method="post" action="<?php echo $PHP_SELF; ?>">
	  <table border="0" align="center">
		<tr>
		  <td nowrap class="label"><div align="right"><?php echo $oTranslator->_('Exibir');?> 
			  &quot;<?php echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
		  <td><select name="in_exibir_etiqueta" id="in_exibir_etiqueta"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
			  <option value="S" <?php if ($campos['in_exibir_etiqueta'] == 'S') echo 'selected'; ?>><?php echo $oTranslator->_('Sim');?></option>
			  <option value="N" <?php if ($campos['in_exibir_etiqueta'] == 'N') echo 'selected'; ?>><?php echo $oTranslator->_('Nao');?></option>
			</select></td>
		  <td>&nbsp;</td>
		</tr>
		<tr> 
		  <td nowrap class="label"><div align="right"> 
			  <input name="id_etiqueta" type="hidden" id="id_etiqueta" value="<?php echo $_GET['id_etiqueta']; ?>">
			  <?php echo $oTranslator->_('Texto da');?> &quot;<?php echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
		  <td class="descricao"><input name="te_etiqueta" type="text" id="te_etiqueta" value="<?php echo $campos['te_etiqueta'];  ?>" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
		  </td>
		  <td>&nbsp;</td>
		</tr>
		<tr> 
		  <td nowrap class="label"><div align="right">
		     <?php echo $oTranslator->_('Texto de ajuda da');?> 
			   &quot;<?php echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
		  <td class="descricao"><input name="te_help_etiqueta" type="text" id="te_help_etiqueta" value="<?php echo $campos['te_help_etiqueta'];  ?>" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"></td>
		  <td>&nbsp;</td>
		</tr>
	
	<tr> 
		  <td nowrap class="label">
		    <div align="right">
		      <?php echo $oTranslator->_('Destacar duplicidades neste campo');?>
			</div>
		  </td>
		  <td><select name="in_destacar_duplicidade" id="in_destacar_duplicidade" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
			  <option value="N" <?php if ($campos['in_destacar_duplicidade'] == 'N') echo 'selected'; ?>><?php echo $oTranslator->_('Nao');?></option>
			  <option value="S" <?php if ($campos['in_destacar_duplicidade'] == 'S') echo 'selected'; ?>><?php echo $oTranslator->_('Sim');?></option>		  
			</select></td>
		  <td>&nbsp;</td>
		</tr>	
		<tr> 
		  <td colspan="3" nowrap> <div align="center"> 
		  <?php 
		  $v_frase = "Confirma('".$oTranslator->_('Confirma Configuracao de')." ".$campos["nm_etiqueta"]."?')";
		  echo '<input name="gravar" type="submit" id="gravar" value="'.$oTranslator->_('Gravar Alteracoes').'" onClick="return '.$v_frase.'" ';
		  echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>3?'disabled':'');
		  echo '>';
		  ?>
		  </div></td>
		</tr>
	  </table>
	</form>
	</body>
	</html>		
	<?php 
	} 
	?>