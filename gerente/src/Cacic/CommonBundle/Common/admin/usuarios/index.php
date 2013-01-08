<?php 
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
	
$ordem = ($_GET['cs_ordem']<>''?$_GET['cs_ordem']:($_SESSION['cs_nivel_administracao']==1?'usu.dt_log_in DESC':'usu.nm_usuario_completo'));

$query = 'SELECT 	usu.id_usuario, 
					usu.nm_usuario_acesso,  
					usu.nm_usuario_completo,
                                        usu.dt_log_in,
					usu.te_locais_secundarios,  
					g_usu.cs_nivel_administracao, 					
					g_usu.id_grupo_usuarios, 									
					loc.sg_local,
					loc.id_local,
					usu.id_servidor_autenticacao
		  FROM 		usuarios usu, 
		  			grupo_usuarios g_usu, 
					locais loc
		  WHERE 	usu.id_grupo_usuarios=g_usu.id_grupo_usuarios and 
		  			usu.id_local=loc.id_local '.
					$where . ' 
		  ORDER BY 	'.$ordem;
$result = mysql_query($query);

$where = ' WHERE g_usu.cs_nivel_administracao <> 0 or 
			  	 g_usu.id_grupo_usuarios = 1 or
			  	 g_usu.id_grupo_usuarios = 7 ';
				 
$where = ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2?'':$where);

$query_grp = 'SELECT	g_usu.te_grupo_usuarios,
						g_usu.id_grupo_usuarios
		  	  FROM 		grupo_usuarios g_usu '.
			  $where . '
		  	  ORDER BY 	g_usu.te_grupo_usuarios';
$result_grp = mysql_query($query_grp) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!', array('grupo_usuarios')));

// Consulto e monto um array com os identificadores e nomes do Servidores de Autentica��o para consultas e exibi��o de nomes 
// -------------------------------------------------------------------------------------------------------------------------
$qry_IdServidorAutenticacao = 'SELECT	id_servidor_autenticacao,
										nm_servidor_autenticacao
		  	  					 FROM 	servidores_autenticacao';
$res_IdServidorAutenticacao = mysql_query($qry_IdServidorAutenticacao) or die ($oTranslator->_('Falha na consulta a tabela (%1) ou sua sessao expirou!', array('servidores_autenticacao')));
$arr_IdServidorAutenticacao = array();
while ($row_IdServidorAutenticacao = mysql_fetch_assoc($res_IdServidorAutenticacao)) 
    $arr_IdServidorAutenticacao[$row_IdServidorAutenticacao["id_servidor_autenticacao"]] = $row_IdServidorAutenticacao["nm_servidor_autenticacao"];
// --------------------------------------------------------------------------------------------------------------------------

$msg = '<div align="center">
		<font color="#c0c0c0" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		Clique nas Colunas para Ordenar</font><br><br></div>';				

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title><?php echo $oTranslator->_('Cadastro de usuarios');?></title>
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body background="../../imgs/linha_v.gif">

<form name="form1" method="post" action="">
<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
    	<div align="left"><?php echo $oTranslator->_('Cadastro de usuarios');?></div>
    </td>
  </tr>
  <tr> 
    <td class="descricao">
    	<?php echo $oTranslator->_('Neste modulo deverao ser cadastrados os usuarios que acessarao o sistema.');?>
    </td>
  </tr>
</table>
<p><br></p><table border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td>
      <div align="center">
        <input name="submit" type="submit" id="submit" value="<?php echo $oTranslator->_('Incluir novo usuario');?>"
          <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      </div>
    </td>
  </tr>
  <tr> 
    <td height="12"> </td>
  </tr>
  <tr> 
    <td height="12"><?php echo $msg;?></td>
  </tr>

  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
          <tr bgcolor="#E1E1E1"> 
            <td align="center"  nowrap> </td>
            <td align="center"  nowrap> </td>
            <td align="center"  nowrap> </td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="left">
            		<a href="index.php?cs_ordem=usu.nm_usuario_acesso"><?php echo $oTranslator->_('Acesso');?></a>
            	</div>
            </td>
            <td nowrap> </td>
            <td nowrap class="cabecalho_tabela">
            	<div align="left">
            		<a href="index.php?cs_ordem=usu.nm_usuario_completo"><?php echo $oTranslator->_('Nome');?></a>
            	</div>
            </td>
            <td nowrap> </td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center">
            		<a href="index.php?cs_ordem=loc.sg_local"><?php echo $oTranslator->_('Local');?></a>
            	</div>
            </td>
            <td nowrap> </td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center"><?php echo $oTranslator->_('Locais secundarios');?></div>
            </td>
            
<td nowrap> </td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center"><a href="index.php?cs_ordem=usu.dt_log_in DESC"><?php echo $oTranslator->_('�ltimo Login');?></a></div>
            </td>            
<td nowrap> </td>
            <td align="center"  nowrap class="cabecalho_tabela">
            	<div align="center"><?php echo $oTranslator->_('Modo Autentica��o');?></div>
            </td>            
<td nowrap> </td>
            <?php
			$intColunasExtras = 0;
			while ($row_grp = mysql_fetch_array($result_grp))
				{				
				echo '<td nowrap class="cabecalho_tabela"><div align="center">';
				echo Abrevia($row_grp['te_grupo_usuarios']);
				echo '</div></td>';
	            echo '<td nowrap class="cabecalho_tabela"> </td>';
				$intColunasExtras ++;
				}
			?>
          </tr>
  	<tr> 
    <td height="1" bgcolor="#333333" colspan="<?php echo ($intColunasExtras + ($intColunasExtras*3) + 6);?>"></td>
  	</tr>
		  
          <?php  
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
          <tr <?php if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            <td nowrap> </td>
            <td align="left" nowrap class="opcao_tabela"><?php echo $NumRegistro; ?></td>
            <td nowrap> </td>
            <td nowrap class="opcao_tabela"><div align="left">
			<?php if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))
					{
					?>
					<a href="detalhes_usuario.php?id_usuario=<?php echo $row['id_usuario'];?>&id_local=<?php echo $row['id_local'];?>&cs_nivel_administracao=<?php echo $row['cs_nivel_administracao'];?>"><?php echo $row['nm_usuario_acesso']; ?></a>
					<?php
					}
			   else
			   		{
					echo $row['nm_usuario_acesso'];
					}
					?>
			</div></td>
            <td nowrap> </td>
            <td nowrap class="opcao_tabela"><div align="left">
			<?php if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))
					{
					?>			
					<a href="detalhes_usuario.php?id_usuario=<?php echo $row['id_usuario'];?>&id_local=<?php echo $row['id_local'];?>&cs_nivel_administracao=<?php echo $row['cs_nivel_administracao'];?>"><?php echo PrimUltNome($row['nm_usuario_completo']); ?></a>
					<?php
					}
			    else
					{
					echo PrimUltNome($row['nm_usuario_completo']);
					}
					?>
					</div></td>
            <td nowrap> </td>
            <td nowrap class="opcao_tabela"><div align="center">
			<?php if ( $_SESSION['cs_nivel_administracao'] == 1 || 
			        $_SESSION['cs_nivel_administracao'] == 2 || 
				   ($_SESSION['cs_nivel_administracao'] == 3 && ($row['cs_nivel_administracao']==0 || $row['cs_nivel_administracao']==4)))			
					{
					?>
					<a href="detalhes_usuario.php?id_usuario=<?php echo $row['id_usuario'];?>&id_local=<?php echo $row['id_local'];?>&cs_nivel_administracao=<?php echo $row['cs_nivel_administracao'];?>"><?php echo $row['sg_local']; ?></a>
					<?php
					}
			   else
			   		{
					echo $row['sg_local'];
					}
					?>
			</div></td>
            <td nowrap> </td>
            <td nowrap class="opcao_tabela"><div align="center">
			<?php 
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
					<a href="detalhes_usuario.php?id_usuario=<?php echo $row['id_usuario'];?>&id_local=<?php echo $row['id_local'];?>&cs_nivel_administracao=<?php echo $row['cs_nivel_administracao'];?>"><?php echo $v_nu_total_locais_secundarios; ?></a>
        		    <?php
					}
				else
					{
					echo $v_nu_total_locais_secundarios;
					}
					?>
			</div></td>	
            <td nowrap> </td>
                        <td nowrap class="opcao_tabela"><div align="center"><?php echo substr($row['dt_log_in'],8,2).'/'.substr($row['dt_log_in'],5,2).'/'.substr($row['dt_log_in'],0,4).' �s '.substr($row['dt_log_in'],11,2).':'.substr($row['dt_log_in'],14,2);?>h</div></td>
            <td nowrap> </td>

                        <td nowrap class="opcao_tabela"><div align="center"><?php echo (($row['id_servidor_autenticacao'] > 0) ? $arr_IdServidorAutenticacao[$row['id_servidor_autenticacao']] : 'Base CACIC');?></div></td>            
			<?php
			mysql_data_seek($result_grp,0);			

			while ($row_grp = mysql_fetch_array($result_grp))
				{
            	echo '<td nowrap> </td>';
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
					<img src="<?php echo '../../imgs/'.$UserGroup;?>" width="17" height="14" border="0" title="N�vel: '<?php echo $row_grp['te_grupo_usuarios'];?>'">
					<?php
					$arrTotaisNiveis[$row_grp['te_grupo_usuarios']] ++; 
					}
				echo '</div></td>';
				}
			?>
            <td nowrap> </td>
            <?php 
		$Cor=!$Cor;
		$NumRegistro++;
		}
	}
?>
  <tr> 
    <td colspan="<?php echo ($intColunasExtras + ($intColunasExtras*3) + 6);?>" height="1" bgcolor="#333333"></td>
  </tr>
	
  <tr> 
  <td colspan="<?php echo ($intColunasExtras + ($intColunasExtras*2));?>" class="dado_med_sem_fundo" align="right">Totais por N�vel:</td>  
  <?php
  ksort($arrTotaisNiveis);
  while (list($UserGroup,$Total) = each($arrTotaisNiveis))
  	{
	?>
	<td nowrap class="opcao_tabela" title="Total no N�vel '<?php echo $UserGroup;?>'"><div align="center"><?php echo $Total; ?></div></td>
	<td></td>
	<?php
	}  
	
  ?>
  </tr>

 </table></td>
  </tr>
  
  
  <tr> 
    <td> </td>
  </tr>
  
  <tr> 
    <td height="10"><?php echo $msg;?></td>
  </tr>
  <tr> 
    <td>
    	<div align="center">
    		<input name="submit" type="submit" id="submit" value="<?php echo $oTranslator->_('Incluir novo usuario');?>"
    		 <?php echo ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>3?'disabled':'')?>>        
      	</div>
    </td>
  </tr>
</table>
</form>
<p> </p>
</body>
</html>
