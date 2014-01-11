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
else 
	{ 
	// Inserir regras para outras verificações (ex: permissões do usuário)!
	}
require_once "../../include/library.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title><?php echo $oTranslator->_('Detalhes do Computador');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js'"></script>
<?php
/*
 * Uma classe para implementar segurança em transações 
 */
//define( 'SECURITY', 1 );
require_once('security/security.php');
 
AntiSpy();
$boolIsAdminOrSupervisor = ($_SESSION["cs_nivel_administracao"] == 1 || $_SESSION["cs_nivel_administracao"] == 2 || $_SESSION["cs_nivel_administracao"] == 3);

$arrDadosComputador = getArrFromSelect('computadores', '*', 'id_computador = ' . $_GET['id_computador']);		
?>
<table width="100%" border="0" cellpadding="0" cellspacing="2">
<?php    
if ($arrDadosComputador[0]['id_computador'])
	{
	$intIdComputador = $arrDadosComputador[0]['id_computador'];
	//$exibir = Security::read('exibir');  
    require_once('inc_basic_informations.php'); 
	$strPreenchimentoPadrao = '#CCCCFF';
	$strCorDaLinha 			= '#E1E1E1';	
		
	$linha 		= '<tr><td colspan="5" height="1" bgcolor="'.$strCorDaLinha.'"></td></tr>';	
	$arrActions = getArrFromSelect('acoes_redes, acoes', 'acoes_redes.id_acao,acoes.te_descricao_breve', 'id_rede = ' . $arrDadosComputador[0]['id_rede'] . ' AND acoes.id_acao = acoes_redes.id_acao ORDER BY acoes.te_descricao_breve');
		
	for ($intLoopActions = 0; $intLoopActions < count($arrActions); $intLoopActions++)
		{
		if (substr($arrActions[$intLoopActions]['id_acao'],0,4) == 'col_')
			{		
			$strCor = $strPreenchimentoPadrao;						  		
			$strCollectType = $arrActions[$intLoopActions]['id_acao'];

	
			$strScriptToRequire = 'inc_' . $strCollectType . '.php';
			?>    
			<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
			<tr><td>
			<?php
												
			$arrClassesNames 		= array();
			$arrCollectsDefClasses 	= array();
												
			// Chamo o procedimento (function) que atribuirá os devidos valores aos arrays acima												
			getClassesDefinitions($strCollectType);
/*			
	echo '<pre>';
	print_r($arrClassesNames);
	echo '</pre>';	
	print_r($arrCollectsDefClasses);
	echo '</pre>';
*/
			if ($_GET['exibir'] == $strCollectType)		$_SESSION[$strCollectType] = !($_SESSION[$strCollectType]);
			else										$_SESSION[$strCollectType] = false;
			?>
			<tr><td height="1" bgcolor="#333333" colspan="4"></td></tr>
			<tr><td bgcolor="#E1E1E1" class="cabecalho_tabela" colspan="4">&nbsp;<a href="computador.php?exibir=<?php echo $strCollectType;?>&id_computador=<?php echo $arrDadosComputador[0]['id_computador'];?>"><img src="../../imgs/<?php 
			if($_SESSION[$strCollectType]) 	echo 'menos';
			else 							echo 'mais'; 
			?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_($arrCollectsDefClasses[$strCollectType]);?></a></td></tr>
			<tr><td colspan="4" height="1" bgcolor="#333333"></td></tr>
			<?php
			if ($_SESSION[$strCollectType]) 
				require_once('inc_show_data.php');
			?>        
			</td></tr>
			</table>
			<?php
			}
        }
		?>
	<table width="94%" border="0" align="center" cellpadding="0" cellspacing="0">        
	<tr><td height="3" bgcolor="#333333" colspan="4"></td></tr>
	<tr><td><?php require_once('inc_patrimonio.php'); 				?></td></tr>       		
  	<tr><td><?php require_once('inc_usb_devices_use.php'); 			?></td></tr>        
  	<tr><td><?php require_once('inc_suporte_remoto.php'); 			?></td></tr>        
	<?php if ($boolIsAdminOrSupervisor)
		{
		?>
  		<tr><td><?php require_once('inc_ferramentas.php'); 				?></td></tr>
		<tr><td><?php require_once('inc_opcoes_administrativas.php'); 	?></td></tr>
		<?php 
		}
	?>
    </table>
	<?php	
	}
else 
	{ 
	?>
	<tr>
   	<td align="center" class="destaque"><?php echo $oTranslator->_('Computador inexistente');?>
	</td>	
	</tr>
	<?php  
	} 
	?>	
</table>
</body>
</html>
