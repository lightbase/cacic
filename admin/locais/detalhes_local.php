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

require_once('../../include/library.php');
AntiSpy('1,2'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central

conecta_bd_cacic();

if ($_POST['ExcluiLocal'] <> '') 
	{
	$result 	= mysql_list_tables($nome_bd); //Retorna a lista de tabelas do BD do CACIC (em config.php)
	while ($row = mysql_fetch_row($result))
		{
		$query_DEL 	= 'DELETE FROM '.$row[0] .' WHERE id_local = "'. $_POST['frm_id_local'] .'"';
		$result_DEL = @mysql_query($query_DEL);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela função MYSQL_QUERY()
		if ($result_DEL)
			GravaLog('DEL',$_SERVER['SCRIPT_NAME'],$row[0]);				
		}					
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/locais/index.php&tempo=1");					
	}
elseif ($_POST['GravaAlteracoes']<>'') 
	{
	$query = "UPDATE 	locais 
			  SET 		sg_local = '".$_POST['frm_sg_local']."', 
			  			nm_local = '".$_POST['frm_nm_local']."',
			  			te_observacao = '".$_POST['frm_te_observacao']."'			  
			  WHERE 	id_local = ".$_POST['frm_id_local'];

	mysql_query($query) or die($oTranslator->_('Falha na atualizacao da tabela (%1) ou sua sessao expirou!',array('locais')));
	GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'locais');		
    header ("Location: ../../include/operacao_ok.php?chamador=../admin/locais/index.php&tempo=1");				
	}
else 
	{
	$query = "SELECT 	* 
			  FROM 		locais ";
	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('locais')));
	
	$v_arr_locais = array();
	while ($row = mysql_fetch_array($result))
		{
		if ($row['id_local']==$_GET['id_local'])
			{
			$v_sg_local = $row['sg_local'];
			$v_nm_local = $row['nm_local'];
			$v_te_observacao = $row['te_observacao'];
			}
		else
			{
			array_push($v_arr_locais,$row['id_local'],$row['sg_local']);
			}
		}
	
	
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Detalhes do Local');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<SCRIPT LANGUAGE="JavaScript">

function valida_form() 
	{

	if ( document.form.frm_sg_local.value == "" ) 
		{	
		alert("<?=$oTranslator->_('A sigla do local e obrigatoria');?>");
		document.form.frm_sg_local.focus();
		return false;
		}
	else if ( document.form.frm_nm_local.value == "" ) 
		{	
		alert("<?=$oTranslator->_('O nome do local e obrigatorio');?>");
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
    <td class="cabecalho"><?=$oTranslator->_('Detalhes do Local');?> "<? echo $v_sg_local;?>"</td>
  </tr>
  <tr> 
    <td class="descricao">
       <?=$oTranslator->_('As informacoes referem-se a um local originario de chamadas ao sistema CACIC');?>
    </td>
  </tr>
</table>
<form action="detalhes_local.php"  method="post" ENCTYPE="multipart/form-data" name="form">
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        <?=$oTranslator->_('Sigla do Local');?></td>
      <td><br> </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="dado_peq_sem_fundo">
        <input name="frm_sg_local" type="text" value="<? echo $v_sg_local; ?>" size="20" maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
        <?=$oTranslator->_('Exemplo');?> - "DTP-URES" 
        <input name="frm_id_local" type="hidden" id="frm_id_local" value="<? echo $_GET['id_local']; ?>"> 
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        <?=$oTranslator->_('Nome do Local');?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td nowrap>&nbsp;</td>
      <td nowrap><input name="frm_nm_local" type="text" id="frm_nm_local" value="<? echo $v_nm_local; ?>" size="100" maxlength="100" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td class="label"><br>
        <?=$oTranslator->_('Observacoes');?></td>
      <td>&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><textarea name="frm_te_observacao" cols="70" id="textarea"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" ><? echo $v_te_observacao; ?></textarea></td>
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
      <td colspan="5" class="label"><?=$oTranslator->_('Redes Associadas ao Local');?></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
    <tr>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela"><?=$oTranslator->_('Endereco IP');?></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela"><?=$oTranslator->_('Nome da Rede');?></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
	
    <?
	$query = "SELECT 	id_local,
						id_ip_rede,
						nm_rede 
			  FROM 		redes a
			  WHERE 	a.id_local = '".$_GET['id_local']."'";
	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('redes')));
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
		echo '<tr><td colspan="5" class="label_vermelho">'.$oTranslator->_('Ainda nao existem redes associadas ao local!').'</td></tr>';		
		?>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="5"></td>
    </tr>
		
		
  </table>
<br>        
<?
$where = ' AND id_local = '.$_GET['id_local'];
$queryCONFIG = "SELECT 		DISTINCT 
							id_etiqueta,
							te_etiqueta,
							te_plural_etiqueta
		  		FROM 		patrimonio_config_interface patcon
				WHERE		patcon.id_etiqueta in ('".'etiqueta1'."','".'etiqueta1a'."','".'etiqueta2'."') ".
							$where. "
		  		ORDER BY 	id_etiqueta
				LIMIT       3";

$resultCONFIG 	= mysql_query($queryCONFIG) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('patrimonio_config_interface')));

session_register('etiqueta1');
session_register('etiqueta1a');
session_register('etiqueta2');

$_SESSION['etiqueta1'] 	= mysql_result($resultCONFIG,0,'te_etiqueta');
$_SESSION['etiqueta1a'] 	= mysql_result($resultCONFIG,1,'te_etiqueta');
$_SESSION['etiqueta2'] 	= mysql_result($resultCONFIG,2,'te_etiqueta');
?>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="8" class="label"><?=$oTranslator->_('Informacoes de Patrimonio Associadas ao Local');?></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="8"></td>
    </tr>
    <tr>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela"><? echo $_SESSION['etiqueta1']; ?></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela"><? echo $_SESSION['etiqueta1a']; ?></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela"><? echo $_SESSION['etiqueta2']; ?></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="8"></td>
    </tr>
	
    <?
	$query = 'SELECT 	uo1.id_unid_organizacional_nivel1   as uo1_id_unid_organizacional_nivel1,
						uo1.nm_unid_organizacional_nivel1   as uo1_nm_unid_organizacional_nivel1,
						uo1a.id_unid_organizacional_nivel1a as uo1a_id_unid_organizacional_nivel1a,
						uo1a.nm_unid_organizacional_nivel1a as uo1a_nm_unid_organizacional_nivel1a,
						uo2.id_unid_organizacional_nivel2   as uo2_id_unid_organizacional_nivel2,
						uo2.nm_unid_organizacional_nivel2   as uo2_nm_unid_organizacional_nivel2
			  FROM 		unid_organizacional_nivel1 uo1,
			       		unid_organizacional_nivel1a uo1a,			  
						unid_organizacional_nivel2 uo2
			  WHERE		uo2.id_unid_organizacional_nivel1a = uo1a.id_unid_organizacional_nivel1a and
						uo2.id_local = '.$_GET['id_local'].' and
						uo1a.id_unid_organizacional_nivel1 = uo1.id_unid_organizacional_nivel1 			
			  ORDER BY 	uo1_nm_unid_organizacional_nivel1, 
						uo1a_nm_unid_organizacional_nivel1a, 
						uo2_nm_unid_organizacional_nivel2';		  

	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('unidades organizacionais')));
	
	$seq = 1;
	$Cor = 1;	
	while ($row = mysql_fetch_array($result))
		{
		?>
    <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
      <td align="center" nowrap class="opcao_tabela"><? echo $seq; ?></td>	
      <td align="left" nowrap class="opcao_tabela">&nbsp;&nbsp;</td>	  
      <td align="center" nowrap class="opcao_tabela"><div align="left"><? echo $row['uo1_nm_unid_organizacional_nivel1'];?></div></td>
      <td align="left" nowrap class="opcao_tabela">&nbsp;&nbsp;</td>
      <td align="center" nowrap class="opcao_tabela"><div align="left"><? echo $row['uo1a_nm_unid_organizacional_nivel1a'];?></div></td>
      <td align="center" nowrap class="opcao_tabela">&nbsp;</td>
      <td align="center" nowrap class="opcao_tabela"><div align="left"><? echo $row['uo2_nm_unid_organizacional_nivel2'];?></div></td>
      <td align="center" nowrap class="opcao_tabela">&nbsp;</td>
    </tr>
    <?
		$seq++;
		$Cor=!$Cor;
		}
	if ($seq==1)
		echo '<tr><td colspan="5" class="label_vermelho">'.$oTranslator->_('Ainda nao existem informacoes de patrimonio associadas ao local!').'</td></tr>';		
		?>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="8"></td>
    </tr>
  </table>
<br>        

  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    <tr> 
      <td colspan="7" class="label"><?=$oTranslator->_('Usuarios Associados ao Local');?></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="9"></td>
    </tr>
    <tr> 
      <td class="cabecalho_tabela">&nbsp;</td>
      <td class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela"><?=$oTranslator->_('Nome');?></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Nivel de Acesso');?></div></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" nowrap class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Tipo de Acesso');?></div></td>
      <td align="left" class="cabecalho_tabela">&nbsp;</td>
      <td align="left" class="cabecalho_tabela"><?=$oTranslator->_('Endereco eletronico');?></td>	  
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="9"></td>
    </tr>
    <?
	$query = "SELECT 	a.id_usuario,
						a.nm_usuario_completo,
						a.id_local,
						a.te_locais_secundarios,
						a.te_emails_contato,
						b.te_grupo_usuarios,
						b.id_grupo_usuarios
			  FROM 		usuarios a,
			  			grupo_usuarios b
			  WHERE 	(a.id_local = ".$_GET['id_local']." OR 
			             TRIM(a.te_locais_secundarios)='".$_GET['id_local']."' OR 
						 a.te_locais_secundarios like '%,".$_GET['id_local']."' OR 
						 a.te_locais_secundarios like '".$_GET['id_local'].",%' OR
						 a.te_locais_secundarios like '%,".$_GET['id_local'].",%') AND
			            b.id_grupo_usuarios = a.id_grupo_usuarios
			  ORDER BY  a.nm_usuario_completo";

	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('usuarios/grupo_usuarios')));
	$seq = 1;
	$Cor = 1;	
	while ($row = mysql_fetch_array($result))
		{
		?>
    <tr <? if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
      <td width="2%" align="center" nowrap class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $seq; ?></a></td>
      <td width="1%" align="left" nowrap class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="3%" align="left" nowrap class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['nm_usuario_completo']; 
	  if ($row['te_locais_secundarios']<>'' && $row['id_local'] <> $_GET['id_local'])
	  	{
		echo ' ('.$v_arr_locais[array_search($row['id_local'],$v_arr_locais)+1] . ')';
		}
	  $UserGroup = 'usergroup_'.$row['id_grupo_usuarios'].'.gif';
	  $UserGroup = 'usergroup_'.($row['id_grupo_usuarios'] == 1?'CO':
	                            ($row['id_grupo_usuarios'] == 2?'AD':
	  						    ($row['id_grupo_usuarios'] == 5?'GC':
	  						    ($row['id_grupo_usuarios'] == 6?'SU':
	  						    ($row['id_grupo_usuarios'] == 7?'TE':''))))).'.gif';
	  
	  ?></a></td>
      <td width="1%" align="left" class="opcao_tabela">&nbsp;&nbsp;</td>
      <td width="30%" align="left" class="opcao_tabela"><div align="center"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><img src="<? echo '../../imgs/'.$UserGroup;?>" width="17" height="14" border="0" title="Nível: '<? echo $row['te_grupo_usuarios'];?>'"></a></div></td>
      <td width="1%" align="left" class="opcao_tabela">&nbsp;</td>
      <td width="62%" align="left" class="opcao_tabela"><div align="center"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo ($row['id_local']==$_REQUEST['id_local']?'Primário':'Secundário'); ?></a></div></td>
      <td width="1%" align="left" class="opcao_tabela">&nbsp;</td>
      <td width="62%" align="left" class="opcao_tabela"><a href="../usuarios/detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&nm_chamador=Locais"><? echo $row['te_emails_contato']; ?></a></td>	  
    </tr>
    <?
		$seq++;
		$Cor=!$Cor;
		}
	if ($seq==1)
		echo '<tr><td colspan="3" class="label_vermelho">'.$oTranslator->_('Ainda nao existem usuarios associados ao local!').'</td></tr>';		
		?>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="9"></td>
    </tr>
  </table>
  <p align="center"> <br>
    <br>
    <input name="GravaAlteracoes" type="submit" id="GravaAlteracoes" value="<?=$oTranslator->_('Gravar Alteracoes');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma Informacoes para o Local?');?>');return valida_form();" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="ExcluiLocal" type="submit" value="<?=$oTranslator->_('Excluir Local');?>" onClick="return Confirma('<?=$oTranslator->_('Confirma Exclusao do Local E TODAS AS SUAS DEPENDENCIAS?');?>');" <? echo ($_SESSION['cs_nivel_administracao']<>1?'disabled':'')?>>
  </p>
</form>		  
		
</body>
</html>
<?
}
?>
