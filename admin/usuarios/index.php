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

if ($_POST['submit']) 
	{
  	header ("Location: incluir_usuario.php");
	}

include_once "../../include/library.php";
require '../../include/piechart.php';
AntiSpy('1,2,3');
Conecta_bd_cacic();

$where = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND (trim(g_usu.cs_nivel_administracao) <> "") AND usu.id_local = '.$_SESSION['id_local']:'');

if ($_SESSION['te_locais_secundarios'] <> '' && $where <> '')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta
	$where = str_replace(' AND usu.id_local = ',' AND (usu.id_local = ',$where);
	$where .= ' OR usu.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
	}
	
$ordem = ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:'usu.nm_usuario_completo');

$query = 'SELECT 	usu.id_usuario, 
					usu.nm_usuario_acesso,  
					usu.nm_usuario_completo,
					usu.te_locais_secundarios,  
					g_usu.cs_nivel_administracao, 					
					g_usu.id_grupo_usuarios, 									
					loc.sg_local,
					loc.id_local
		  FROM 		grupo_usuarios g_usu, usuarios usu 
		  LEFT JOIN     locais loc ON (loc.id_local=usu.id_local)
		  WHERE 	usu.id_grupo_usuarios=g_usu.id_grupo_usuarios '. $where . ' 
		  ORDER BY 	'.$ordem;
$result = mysql_query($query) or die(mysql_error(). " " . $query);

$where = ' WHERE g_usu.cs_nivel_administracao <> 0 or 
			  	 g_usu.id_grupo_usuarios = 1 or
			  	 g_usu.id_grupo_usuarios = 7 ';
				 
$where = ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2?'':$where);

$query_grp = 'SELECT	g_usu.te_grupo_usuarios,
						g_usu.id_grupo_usuarios
		  	  FROM 		grupo_usuarios g_usu '.
			  $where . '
		  	  ORDER BY 	g_usu.te_grupo_usuarios';
$result_grp = mysql_query($query_grp) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou', array('grupo_usuarios')));
$msg = '<div align="center">
		<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		Clique nas Colunas para Ordenar</font><br><br></div>';				

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Cadastro de usuarios');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
    	<div align="left"><?=$oTranslator->_('Cadastro de usuarios');?></div>
    </td>
  </tr>
  <tr> 
    <td class="descricao">
    	<?=$oTranslator->_('Neste modulo deverao ser cadastrados os usuarios que acessarao o sistema.');?>
    </td>
  </tr>
</table>
<p><br></p><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td>
      <div align="center">
        <input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir novo usuario');?>"
          <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      </div>
    </td>
  </tr>
  <tr> 
    <td height="12">&nbsp;</td>
  </tr>
  <tr> 
    <td height="12"><? echo $msg;?></td>
  </tr>

  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="left">
            		<a href="index.php?cs_ordem=usu.nm_usuario_acesso"><?=$oTranslator->_('Acesso');?></a>
            	</div>
            </td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="cabecalho_tabela">
            	<div align="left">
            		<a href="index.php?cs_ordem=usu.nm_usuario_completo"><?=$oTranslator->_('Nome');?></a>
            	</div>
            </td>
            <td nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center">
            		<a href="index.php?cs_ordem=loc.sg_local"><?=$oTranslator->_('Local');?></a>
            	</div>
            </td>
            <td nowrap>&nbsp;</td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center"><?=$oTranslator->_('Locais secundarios');?></div>
            </td>
            <td nowrap>&nbsp;</td>
            <?
			$intColunasExtras = 0;
			while ($row_grp = mysql_fetch_array($result_grp))
				{				
				echo '<td nowrap class="cabecalho_tabela"><div align="center">';
				echo Abrevia($row_grp['te_grupo_usuarios']);
				echo '</div></td>';
	            echo '<td nowrap class="cabecalho_tabela">&nbsp;</td>';
				$intColunasExtras ++;
				}
			?>
          </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="<? echo ($intColunasExtras + ($intColunasExtras*3) + 1);?>"></td>
  	</tr>
		  
          <?  
if(mysql_num_rows($result)==0) 
	{
	$msg = '<div align="center">
			<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				'.$oTranslator->_('Nenhum usuario cadastrado ou sua sessao expirou!').'</font><br><br></div>';
			
	}
else 
	{
	$Cor = 0;
	$NumRegistro = 1;
	$arrTotaisNiveis = array();	
	while($row = mysql_fetch_array($result)) 
		{		  
 	 	?>
          <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            <td nowrap>&nbsp;</td>
            <td align="left" nowrap class="opcao_tabela"><? echo $NumRegistro; ?></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left">
			<? if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))
					{
					?>
					<a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&cs_nivel_administracao=<? echo $row['cs_nivel_administracao'];?>"><? echo $row['nm_usuario_acesso']; ?></a>
					<?
					}
			   else
			   		{
					echo $row['nm_usuario_acesso'];
					}
					?>
			</div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="left">
			<? if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))
					{
					?>			
					<a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&cs_nivel_administracao=<? echo $row['cs_nivel_administracao'];?>"><? echo PrimUltNome($row['nm_usuario_completo']); ?></a>
					<?
					}
			    else
					{
					echo PrimUltNome($row['nm_usuario_completo']);
					}
					?>
					</div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="center">
			<? if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))			
					{
					?>
					<a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&cs_nivel_administracao=<? echo $row['cs_nivel_administracao'];?>"><? echo ($row['sg_local']?$row['sg_local']:"???"); ?></a>
					<?
					}
			   else
			   		{
					echo ($row['sg_local']?$row['sg_local']:"???");
					}
					?>
			</div></td>
            <td nowrap>&nbsp;</td>
            <td nowrap class="opcao_tabela"><div align="center">
			<? 
			$v_nu_total_locais_secundarios = '';
			if (trim($row['te_locais_secundarios'])<>'')
				{
				$v_arr_locais_secundarios = explode(',',trim($row['te_locais_secundarios']));
				$v_nu_total_locais_secundarios = count($v_arr_locais_secundarios);
				}
			if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			     $_SESSION['cs_nivel_administracao'] == 2 || 
				($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))			
					{
					?>			
					<a href="detalhes_usuario.php?id_usuario=<? echo $row['id_usuario'];?>&id_local=<? echo $row['id_local'];?>&cs_nivel_administracao=<? echo $row['cs_nivel_administracao'];?>"><? echo $v_nu_total_locais_secundarios; ?></a>
        		    <?
					}
				else
					{
					echo $v_nu_total_locais_secundarios;
					}
					?>
			</div></td>					
			<?
			mysql_data_seek($result_grp,0);			

			while ($row_grp = mysql_fetch_array($result_grp))
				{
            	echo '<td nowrap>&nbsp;</td>';
            	echo '<td nowrap class="opcao_tabela"><div align="center">';
				/*
				if ($row['cs_nivel_administracao']<> 0 && $row['id_grupo_usuarios']==$row_grp['id_grupo_usuarios'] ||
				    $row['cs_nivel_administracao']== 0 && $row_grp['id_grupo_usuarios'] == 7 ||  
				    $row['cs_nivel_administracao']== 0 && $row_grp['id_grupo_usuarios'] == 1) 					
				*/
				if ($row['id_grupo_usuarios']==$row_grp['id_grupo_usuarios']) 	
					{
					$UserGroup = 'usergroup_'.($row['id_grupo_usuarios'] == 1?'CO':
					                          ($row['id_grupo_usuarios'] == 2?'AD':
											  ($row['id_grupo_usuarios'] == 5?'GC':
											  ($row['id_grupo_usuarios'] == 6?'SU':
											  ($row['id_grupo_usuarios'] == 7?'TE':''))))).'.gif';
					?>
					<img src="<? echo '../../imgs/'.$UserGroup;?>" width="17" height="14" border="0" title="Nível: '<? echo $row_grp['te_grupo_usuarios'];?>'">
					<?
					$arrTotaisNiveis[$row_grp['te_grupo_usuarios']] ++; 
					}
				echo '</div></td>';
				}
			?>
            <td nowrap>&nbsp;</td>
            <? 
		$Cor=!$Cor;
		$NumRegistro++;
		}
	}
?>
  <tr> 
    <td colspan="<? echo ($intColunasExtras + ($intColunasExtras*3) + 1);?>" height="1" bgcolor="#333333"></td>
  </tr>
	
  <tr> 
  <td colspan="11" class="dado_med_sem_fundo" align="right">Totais por Nível:</td>  
  <?
  ksort($arrTotaisNiveis);
  while (list($UserGroup,$Total) = each($arrTotaisNiveis))
  	{
	?>
	<td nowrap class="opcao_tabela" title="Total no Nível '<? echo $UserGroup;?>'"><div align="center"><? echo $Total; ?></div></td>
	<td></td>
	<?
	}  
	
  ?>
  </tr>

 </table></td>
  </tr>
  
  
  <tr> 
    <td>&nbsp;</td>
  </tr>
  
  <tr> 
    <td height="10"><? echo $msg;?></td>
  </tr>
  <tr> 
    <td>
    	<div align="center">
    		<input name="submit" type="submit" id="submit" value="<?=$oTranslator->_('Incluir novo usuario');?>"
    		 <? echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      	</div>
    </td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
</body>
</html>
