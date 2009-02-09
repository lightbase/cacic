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

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Detalhes do Computador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js'"></script>
<?
require_once "../../include/library.php";
/*
 * Uma classe para implementar segurança em transações 
 */
 define( 'SECURITY', 1 );
 require_once('security/security.php');
 
AntiSpy();
conecta_bd_cacic();	
$query = "SELECT       *
             FROM      computadores
             LEFT JOIN so ON (computadores.id_so = so.id_so)
             WHERE     te_node_address = '". $_GET['te_node_address'] ."' AND computadores.id_so = ". $_GET['id_so'];

$result = mysql_query($query);

if (@mysql_num_rows($result)) 
	{
		$exibir = Security::read('exibir');  
    require_once('inc_detalhes_computador.php'); 
	$strPreenchimentoPadrao = '#CCCCFF';
	$strCorDaLinha 			= '#E1E1E1';	
	$linha = '<tr><td colspan="5" height="1" bgcolor="'.$strCorDaLinha.'"></td></tr>';
	
	?>

	<table width="100%" border="0" cellpadding="0" cellspacing="2">
	<tr><td>
    <? 
	require_once('inc_tcp_ip.php'); 
	?>
    </td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_hardware.php'); 
	?>
    </td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_software.php'); 
	?>
    </td>
  	</tr>
	<?
	if ($_SESSION["cs_nivel_administracao"] == 1 ||
		$_SESSION["cs_nivel_administracao"] == 2 ||
		$_SESSION["cs_nivel_administracao"] == 3) 		
		{
		?>
	  	<tr> 
    	<td>
	    <? require_once('inc_software_inventariado.php'); ?>
	    </td>
	  	</tr>	
		<?
		}
		?>
  	<tr> 
   	<td>
    <? 
	require_once('inc_sistemas_monitorados.php'); 
	?>
    </td>
  	</tr>	
  	<tr> 
    <td>
    <? 
	require_once('inc_variaveis_ambiente.php'); 
	?>
    </td>
  	</tr>		
  	<tr>
    <td>
	<? 
	require_once('inc_patrimonio.php'); 
	?>
	</td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_officescan.php'); 
	?>
    </td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_compartilhamento.php'); 
	?>
    </td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_unidades_disco.php'); 
	?>
    </td>
  	</tr>
  	<tr> 
    <td>
    <? 
	require_once('inc_ferramentas.php'); 
	?>
    </td>
  	</tr>
	<?  
	if ($_SESSION["cs_nivel_administracao"] == 1 ||
		$_SESSION["cs_nivel_administracao"] == 2 ||
		$_SESSION["cs_nivel_administracao"] == 3) 		
		{
		?>
		<tr>
		<td>
		<? require_once('inc_opcoes_administrativas.php'); ?>
	  	</td>
	  	</tr>
		<?
		}	
	} 
	else 
		{ 
		?>
		<tr>
    	<td align="center" class="destaque">Computador Inexistente!
		</td>	
		</tr>
		<?  
		} 
		?>	
	</table>
</body>
</html>
