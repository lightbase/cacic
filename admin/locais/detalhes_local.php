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

if ($ExcluiLocal) 
	{
	$query = "DELETE 
			  FROM 		locais 
			  WHERE 	id_local = '$frm_id_local'";
	mysql_query($query) or die('Delete falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'locais');			

	$query = "DELETE 
			  FROM 		redes
			  WHERE 	id_local = '$frm_id_local'";
	mysql_query($query) or die('Delete falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'redes');			

	$query = "DELETE 
			  FROM 		usuarios
			  WHERE 	id_local = '$frm_id_local'";
	mysql_query($query) or die('Delete falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'usuarios');			

	$query = "DELETE 
			  FROM 		patrimonio_config_interface 
			  WHERE 	id_local = '$frm_id_local'";
	mysql_query($query) or die('Delete falhou');
	GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'patrimonio_config_interface');			
		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/locais/index.php&tempo=1");					
	}
elseif ($GravaAlteracoes) 
	{
	$query = "UPDATE 	locais 
			  SET 		sg_local = '".$_REQUEST['frm_sg_local']."', 
			  			nm_local = '".$_REQUEST['frm_nm_local']."',
			  			te_observacao = '".$_REQUEST['frm_te_observacao']."'			  
			  WHERE 	id_local = ".$_REQUEST['frm_id_local'];

	mysql_query($query) or die('Update falhou');
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'locais');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/locais/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		locais 
			  WHERE 	id_local = '".$_REQUEST['id_local']."'";
	$result = mysql_query($query) or die ('select falhou');
	$row = mysql_fetch_array($result);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Detalhes de Local</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_sg_local.value == "" ) 
		{	
		alert("A sigla do local é obrigatória");
		document.form.frm_sg_local.focus();
		return false;
		}
	else if ( document.form.frm_nm_local.value == "" ) 
		{	
		alert("O nome do local é obrigatório.");
		document.form.frm_nm_local.focus();
		return false;
		}
	return true;	
	}
</script>
</head>

<body background="../../imgs/linha_v.gif" onLoad="SetaCampo('frm_sg_local');">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Detalhes 
      do Local "<? echo $row['sg_local'];?>"</td>
  </tr>
  <tr> 
    <td class="descricao">As informa&ccedil;&otilde;es 
      referem-se a um local originário de chamadas ao sistema CACIC.</td>
  </tr>
</table>
<form action="detalhes_local.php"  method="post" ENCTYPE="multipart/form-data" name="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        Sigla do Local:</td>
      <td><br> </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="dado_peq_sem_fundo"> <input name="frm_sg_local" type="text" value="<? echo $row['sg_local']; ?>" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >&nbsp;&nbsp;Ex.: DTP - UAES 
        <input name="frm_id_local" type="hidden" id="frm_id_local" value="<? echo $row['id_local']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        Nome do Local:</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
      <td nowrap><input name="frm_nm_local" type="text" id="frm_nm_local" value="<? echo $row['nm_local']; ?>" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        Observa&ccedil;&otilde;es:</td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><textarea name="frm_te_observacao" cols="70" id="textarea"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $row['te_observacao']; ?></textarea></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
	</table>
	  
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="5" class="label">Redes Associadas ao Local:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
    <tr>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela">IP</td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela">Rede</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
	
    <?
	$query = "SELECT 	id_local,
						id_ip_rede,
						nm_rede 
			  FROM 		redes a
			  WHERE 	a.id_local = '".$_REQUEST['id_local']."'";
	$result = mysql_query($query) or die ('select falhou');
	$seq = 1;
	$Cor = 1;	
	while ($row = mysql_fetch_array($result))
		{
		?>
    <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
      <td width="3%" align="center" nowrap class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $seq; ?></a></td>
      <td width="1%" align="left" nowrap class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="3%" align="left" nowrap class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['id_ip_rede']; ?></a></td>
      <td width="1%" align="left" class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="92%" align="left" class="opcao_tabela"><a href="../redes/detalhes_rede.php?id_ip_rede=<? echo $row['id_ip_rede'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['nm_rede']; ?></a></td>
    </tr>
    <?
		$seq++;
		$Cor=!$Cor;
		}
	if ($seq==1)
		echo '<tr><td colspan="5" class="label_vermelho">Ainda não existem redes associadas ao local!</td></tr>';		
		?>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
		
		
  </table>
<br>        
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="5" class="label">Usu&aacute;rios Associados ao Local:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
    <tr> 
      <td class="cabecalho_tabela">&nbsp;</td>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela">Nome</td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela">N&iacute;vel de Acesso</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
	
    <?
	$query = "SELECT 	a.id_usuario,
						a.nm_usuario_completo,
						a.id_local,
						b.te_grupo_usuarios
			  FROM 		usuarios a,
			  			grupo_usuarios b
			  WHERE 	a.id_local = ".$_REQUEST['id_local']." AND
			            b.id_grupo_usuarios = a.id_grupo_usuarios";

	$result = mysql_query($query) or die ('select falhou');
	$seq = 1;
	$Cor = 1;	
	while ($row = mysql_fetch_array($result))
		{
		?>
    <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
      <td width="3%" align="center" nowrap class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $seq; ?></a></td>
      <td width="1%" align="left" nowrap class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="3%" align="left" nowrap class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['nm_usuario_completo']; ?></a></td>
      <td width="1%" align="left" class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="92%" align="left" class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['te_grupo_usuarios']; ?></a></td>
    </tr>
    <?
		$seq++;
		$Cor=!$Cor;
		}
	if ($seq==1)
		echo '<tr><td colspan="3" class="label_vermelho">Ainda não existem usuários associados ao local!</td></tr>';		
		?>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
				
  </table>
  <p align="center"> <br>
    <br>
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="  Gravar Altera&ccedil;&otilde;es  " onClick="return Confirma('Confirma Informações para Local?');return valida_form();" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="ExcluiLocal" type="submit" value="  Excluir Local" onClick="return Confirma('Confirma Exclusão de Local e TODAS AS DEPENDÊNCIAS? (Redes e Usuários)');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
      </form>		  
		
</body>
</html>
<?
}
?>
