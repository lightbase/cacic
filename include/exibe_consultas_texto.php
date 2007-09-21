<link href="/cacic2/include/cacic.css" rel="stylesheet" type="text/css"> 
<table bordercolor="#000000" border="1" align="center" cellpadding="0" cellspacing="0">
	<?
	$strCor = '#CCCCCC';
	$intTotalGeral = 0;
	$nomeFileConsulta = 'monta_consulta_'.$_SESSION['in_grafico'].'.php';
	require($nomeFileConsulta);

	$nomeArray = "arr_".$_SESSION['in_grafico'];

	global $$nomeArray;
	$intIndex = 0;
	while ($intIndex < count($$nomeArray))
		{
		$intTotal = current($$nomeArray);
		if ($intTotal > 0)
			{
			?>	
  			<tr> 
    		<td class="dado_med_sem_fundo" bgcolor="<? echo $strCor;?>">&nbsp;<? echo key($$nomeArray);?>&nbsp;</td>
    		<td class="dado_med_sem_fundo" bgcolor="<? echo $strCor;?>"><div align="right">&nbsp;<? echo number_format($intTotal,0,',','.');?>&nbsp;</div></td>
	  		</tr>
			<?
			$strCor = ($strCor=='#CCCCCC'?'#FFFFFF':'#CCCCCC');		

			$intTotalGeral += $intTotal;		
			}
		next($$nomeArray);
		$intIndex ++;
		}
		?>
  		<tr> 
    	<td class="dado">&nbsp;Total&nbsp;</td>
    	<td class="dado"><div align="right">&nbsp;<? echo number_format($intTotalGeral,0,',','.');?>&nbsp;</div></td>
  		</tr>		
</table>
