<link href="../include/css/cacic.css" rel="stylesheet" type="text/css"> 
<table bordercolor="#000000" border="1" align="center" cellpadding="0" cellspacing="0">
	<?php
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
    		<td class="dado_med_sem_fundo" bgcolor="<?php echo $strCor;?>">&nbsp;<?php echo key($$nomeArray);?>&nbsp;</td>
    		<td class="dado_med_sem_fundo" bgcolor="<?php echo $strCor;?>"><div align="right">&nbsp;<?php echo number_format($intTotal,0,',','.');?>&nbsp;</div></td>
	  		</tr>
			<?php
			$strCor = ($strCor=='#CCCCCC'?'#FFFFFF':'#CCCCCC');		

			$intTotalGeral += $intTotal;		
			}
		next($$nomeArray);
		$intIndex ++;
		}
		?>
  		<tr> 
    	<td class="dado"><?php echo $oTranslator->_('Total');?></td>
    	<td class="dado"><div align="right">&nbsp;<?php echo number_format($intTotalGeral,0,',','.');?>&nbsp;</div></td>
  		</tr>		
</table>
