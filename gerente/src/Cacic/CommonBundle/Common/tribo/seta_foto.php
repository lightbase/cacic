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
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../../imgs/linha_v.gif">
<?
if ($_REQUEST['p_foto'])
	{
	?>
	<script>
	window.open("mostra_screen.php?p_foto=<?echo $_REQUEST['p_foto'];?>","","resizable=no,menubar=no,statusbar=no,toolsbar=no,scrollbars=yes,width='800',height='600',left='0',top='0'")		
	</script>
	<?
	}
	?>

	<table border="1" align="center">
  	<tr> 
    
  <td colspan="2"><font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><b><font size="2">Selecione o screenshot:</font></b></font></td>
</tr>
<?
	$v_path_screenshots = 'imgs/screenshots/';
		
	$handle=opendir($v_path_screenshots);
	while ($file = readdir($handle)) 
		{
		if ($file <> '.' && $file <> '..')
			{		
			echo "<tr><td>";
			echo "<a href=seta_foto.php?p_foto=".$file."><img src='". $v_path_screenshots . $file . "' width='100' height='100'><br></a>";
			echo "</td><td>";
	       	echo "<a href=seta_foto.php?p_foto=".$file.">".$file."</a>";
			echo "</td></tr>";
	   		}
		}

?>
</table>
</font> 
</body>
</html>