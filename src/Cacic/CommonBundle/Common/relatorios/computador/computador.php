<?
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
require_once "../../include/library.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Detalhes do Computador');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js'"></script>
<?
/*
 * Uma classe para implementar seguran�a em transa��es 
 */
 define( 'SECURITY', 1 );
 require_once('security/security.php');
 
AntiSpy();
conecta_bd_cacic();	
$query = "SELECT 	* 
		  FROM 		computadores, 
		  			so
		  WHERE 	te_node_address = '". $_GET['te_node_address'] ."' AND
		  			computadores.id_so = ". $_GET['id_so'] ." AND 
		  			computadores.id_so = so.id_so";

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
	require_once('inc_usb_devices_use.php'); 
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
	require_once('inc_suporte_remoto.php'); 
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
		<?
		require_once('inc_opcoes_administrativas.php');} ?>
	  	</td>
	  	</tr>
		<?	
		} 
	else 
		{ 
		?>
		<tr>
    	<td align="center" class="destaque"><?=$oTranslator->_('Computador inexistente');?>
		</td>	
		</tr>
		<?  
		} 
		?>	
	</table>
</body>
</html>
