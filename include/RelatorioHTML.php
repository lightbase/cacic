<?php
require_once('Relatorio.php');

class RelatorioHTML extends Relatorio
{
	public function rgb2html($r, $g=-1, $b=-1)
	{
		if (is_array($r) && sizeof($r) == 3)
		    list($r, $g, $b) = $r;

		$r = intval($r); $g = intval($g);
		$b = intval($b);

		$r = dechex($r<0?0:($r>255?255:$r));
		$g = dechex($g<0?0:($g>255?255:$g));
		$b = dechex($b<0?0:($b>255?255:$b));

		$color = (strlen($r) < 2?'0':'').$r;
		$color .= (strlen($g) < 2?'0':'').$g;
		$color .= (strlen($b) < 2?'0':'').$b;
		return '#'.$color;
	}

	public function output()
	{
		$cor = FALSE;
		$titulo = $this->getTitulo();
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<?php
			echo "<title>$titulo</title>";
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function MM_openBrWindow(theURL,winName,features) { //v2.0
		  window.open(theURL,winName,features);
		}
		//-->
		</script>
		</head>

		<body bgcolor="#FFFFFF" topmargin="5">
		<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
		  <tr bgcolor="#E1E1E1"> 
			<td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
			<td rowspan="5" bgcolor="#FFFFFF"></td>
			<td bgcolor="#FFFFFF"> </td>
		  </tr>
		  <tr width="100%" bgcolor="#E1E1E1">
			<td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong><?=$titulo;?></strong></font></td>
			
		  </tr>
		  <tr> 
			<td height="1" bgcolor="#333333"></td>

		  <tr> 
			<td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
				em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
		  </tr>
		  <tr cellpadding="10"	> 
			<td></td>
		  </tr>
		</table>
		<br>
		<br>
		<br>
		<font size="1" face="Verdana, Arial, Helvetica, sans-serif"></tr>Exportar: <a href="?formato=pdf">PDF</a> | <a href="?formato=ods">ODS</a> | <a href="?formato=csv">CSV</a></font>
		<br>
		<br>
		<?php
		echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">';
		echo '<tr bgcolor="#E1E1E1" >';
		foreach ($this->getHeader() as $cell)
		{
			echo '<td nowrap align="left">';
			echo $cell;
			echo '</td>';
		}
		echo '</tr>';

		//Data
		$i = 0;
		foreach ($this->getBody() as $row)
		{
			$attr = $this->getRowColor($i++);
			if (!$attr)
			{
				if ($cor)
				{
					$attr = 'bgcolor="#E1E1E1"';
				}
			}
			else
			{
				$attr = 'bgcolor="'.$this->rgb2html($attr[0], $attr[1], $attr[2]).'"';
			}
			echo "<tr $attr>";
			
			foreach ($row as $cell)
			{
				echo '<td nowrap align="left">';
				echo $cell;
				echo '&nbsp;</td>';
			}
			echo '</tr>';
			$cor = !$cor;
		}
		?>
		</table>		
		<br><br>		
		<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relatório 
		gerado pelo <strong>CACIC</strong> - Configurador Automático e Coletor 
		de Informações Computacionais</font><br>
		<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido pela Dataprev - Unidade Regional Espírito Santo</font></p>
		</body>
		</html>
		<?php
	}
}
?>
