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