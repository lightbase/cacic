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
require_once('include/config.php');
?>
<html>
<head>
<title>Estat&iacute;sticas</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet"   type="text/css" href="include/cacic.css">
</head>

<body bgcolor="#FFFFFF" background="imgs/linha_v.gif">
<table width="90%" border="0" align="center">
<tr>
<td class="cabecalho">Estat&iacute;sticas do CACIC</td>
</tr>
<tr> 
<td>&nbsp;&nbsp;</td>
</tr>
<? 
if (!session_is_registered('cs_nivel_administracao'))
	{	
	?>
	<tr><td>
	<img src="graficos/pie_locais.php" border="no">
	</td>
	</tr>
	<tr>
	<td class="descricao"><div align="center">Totais de Estações Monitoradas por 
        Local</div></td>
	</tr>  
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<?
	}	
else
	{
	?>
	<tr><td>
	<?
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		{
		echo '<a href="relatorios/software/rel_software.php?orderby=4&principal=1">';
		}
	echo '<img src="graficos/pie_so.php?cs_nivel_administracao='.$_SESSION["cs_nivel_administracao"].'&id_local='.$_SESSION['id_local'].'" border="no">';
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		{
		echo '</a>';
		}		
	?>
	</td>	
	</tr>
	<tr> 
	<td class="descricao"><div align="center">Distribui&ccedil;&atilde;o por sistemas operacionais dos computadores gerenciados</div></td>
	</tr>  
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<tr> 
	<td>
	<? 
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		{
		echo '<a href="relatorios/software/rel_software.php?orderby=6&principal=1">';
		}	
	echo '<img src="graficos/pie_acessos.php?cs_nivel_administracao='.$_SESSION["cs_nivel_administracao"].'&id_local='.$_SESSION['id_local'].'" border="no">';
	if ($_SESSION["cs_nivel_administracao"] <> 0)
		{
		echo '</a>';
		}		
	?>	
	</td>
	</tr>
	<tr> 
	<td class="descricao"><div align="center">Distribui&ccedil;&atilde;o por &uacute;ltimo acesso dos agentes</div></td>
	</tr>
	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<?
	if ($_SESSION["cs_nivel_administracao"] == 1 ||
		$_SESSION["cs_nivel_administracao"] == 2)
		{
		?>
		<tr><td>
		<img src="graficos/pie_locais.php" border="no">
		</td>
		</tr>
		<tr>
		<td class="descricao"><div align="center">Totais de Estações Monitoradas por Local</div></td>
		</tr>  
		<tr> 
		<td height="1"  bgcolor="#e7e7e7"></td>
		</tr>
		<tr> 
		<td>&nbsp;</td>
		</tr>
		<?
		}
		?>
	
	<tr> 
	<td>
	<? 
	echo '<img src="graficos/pie_mac.php?cs_nivel_administracao='.$_SESSION["cs_nivel_administracao"].'&id_local='.$_SESSION['id_local'].'" border="no">';
	?>	
	</td>
	</tr>
	<tr> 
	<td class="descricao"><div align="center">Total de computadores baseado no Mac-Address</div></td>
	</tr>

	<tr> 
	<td height="1"  bgcolor="#e7e7e7"></td>
	</tr>
	</table>
	<?
	}
	?>
<table width="90%">
<tr><td height="30"></td></tr>
<tr><td class="descricao">
<p align="center">Desenvolvido pela Dataprev - Unidade de Atendimento do Esp&iacute;rito Santo 
<p align="center"><a href="http://www.anybrowser.org/campaign/anybrowser_br.html" target="_blank"><img src="imgs/anybrowser.gif" alt="Vis&iacute;vel por qualquer browser" width="88" height="31" border="0"></a>
</td></tr>
</table>
</body>
</html>