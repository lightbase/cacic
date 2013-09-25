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
?>

<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body background="../../../imgs/linha_v.gif">
<?php if ($_REQUEST['p_foto'])
	{
	?>
	<script>
	window.open("mostra_screen.php?p_foto=<?php echo $_REQUEST['p_foto'];?>","","resizable=no,menubar=no,statusbar=no,toolsbar=no,scrollbars=yes,width='800',height='600',left='0',top='0'")		
	</script>
	<?php
	}
	?>

	<table border="1" align="center">
  	<tr> 
    
  <td colspan="2"><font color="#FF0000" size="4" face="Verdana, Arial, Helvetica, sans-serif"><b><font size="2">Selecione o screenshot:</font></b></font></td>
</tr>
<?php
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