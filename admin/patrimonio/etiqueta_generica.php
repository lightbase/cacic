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
// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

	if ($_POST['gravar']) {	
   	$query = "UPDATE 	patrimonio_config_interface SET 
				        te_etiqueta = '" . $_POST['te_etiqueta'] . "', 
				        in_exibir_etiqueta = '" . $_POST['in_exibir_etiqueta'] . "', 
              			te_help_etiqueta = '" . $_POST['te_help_etiqueta'] . "', 
			  			in_destacar_duplicidade = '" . $_POST['in_destacar_duplicidade'] . "', 			  
	          			te_plural_etiqueta = '" . $_POST['te_plural_etiqueta'] . "'
			  WHERE 	id_etiqueta = '" . $_POST['id_etiqueta'] . "' 
			  			id_local = ".$_SESSION['id_local']; 
			mysql_query($query);
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface');						
   	$query = 'UPDATE	configuracoes_locais SET 
				        dt_hr_alteracao_patrim_interface = NOW()
			  WHERE		id_local = '.$_SESSION['id_local']; 
				mysql_query($query);
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
	header ("Location: ../../include/operacao_ok.php?chamador=../admin/patrimonio/config_tela_patrimonio.php&tempo=1");									 										
	}
	else 
	{
	$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND id_local = '.$_SESSION['id_local']:'');
	$query = "SELECT 	* 
			  FROM 		patrimonio_config_interface 
			  WHERE 	id_etiqueta = '" . $_GET['id_etiqueta'] . "'".
			  			$where; 
	$result = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela de configurações de patrimônio.'); 
	$campos = mysql_fetch_array($result);
?>
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Configura&ccedil;&atilde;o da Tela de Patrim&ocirc;nio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
  </p>
  </div>
<form name="form1" method="post" action="<? echo $PHP_SELF; ?>">
  <table border="0" align="center">
    <tr>
      <td nowrap class="label"><div align="right">Exibir 
          &quot;<? echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
      <td><select name="in_exibir_etiqueta" id="in_exibir_etiqueta"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          <option value="S" <? if ($campos['in_exibir_etiqueta'] == 'S') echo 'selected'; ?>>Sim</option>
          <option value="N" <? if ($campos['in_exibir_etiqueta'] == 'N') echo 'selected'; ?>>N&atilde;o</option>
        </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label"><div align="right"> 
          <input name="id_etiqueta" type="hidden" id="id_etiqueta" value="<? echo $_GET['id_etiqueta']; ?>">
          Texto da &quot;<? echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
      <td class="descricao"><input name="te_etiqueta" type="text" id="te_etiqueta" value="<? echo $campos['te_etiqueta'];  ?>" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td nowrap class="label"><div align="right">Texto 
          de ajuda da &quot;<? echo $campos['nm_etiqueta'];  ?>&quot;:</div></td>
      <td class="descricao"><input name="te_help_etiqueta" type="text" id="te_help_etiqueta" value="<? echo $campos['te_help_etiqueta'];  ?>" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);"></td>
      <td>&nbsp;</td>
    </tr>

<tr> 
      <td nowrap class="label"><div align="right">Destacar 
          duplicidades neste campo:</div></td>
      <td><select name="in_destacar_duplicidade" id="in_destacar_duplicidade" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);">
          <option value="N" <? if ($campos['in_destacar_duplicidade'] == 'N') echo 'selected'; ?>>N&atilde;o</option>
          <option value="S" <? if ($campos['in_destacar_duplicidade'] == 'S') echo 'selected'; ?>>Sim</option>		  
        </select></td>
      <td>&nbsp;</td>
    </tr>	
    <tr> 
      <td colspan="3" nowrap> <div align="center"> 
	  <? 
	  $v_frase = "Confirma('Confirma Configuração de ".$campos["nm_etiqueta"]."?')";
	  echo '<input name="gravar" type="submit" id="gravar" value="   Gravar   " onClick="return '.$v_frase.'" ';
	  echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'');
	  echo '>';
	  ?>
      </div></td>
    </tr>
  </table>
</form>
<? } ?>
</body>
</html>
