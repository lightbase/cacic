<?
session_start();
$sqlContador = 'SELECT  DATE_FORMAT(min(dt_acao), "%d/%m/%Y") as MenorData,
						count(cs_acao) as TotalAcessos
				FROM	log
				WHERE	cs_acao="ACE" AND 
				 		nm_script = "menu_esq.php"';
conecta_bd_cacic();				
$resultContador = mysql_query($sqlContador);				
$rowContador = mysql_fetch_array($resultContador);
$_SESSION['TotalAcessos']=$rowContador['TotalAcessos'];
$_SESSION['MenorData']=$rowContador['MenorData'];


?>
<script>
	function MontaContador(strTotalAcessos)
		{
		//declare variables
		var output="";
		var position=0;
		var chr="";
		var estrutura_esquerda = '<img border="0" src="imgs/';
		var estrutura_direita  = '.png" width="10" height="25">';
		//loop
		for (var position=0; position < strTotalAcessos.length; position++)
			{
			chr=strTotalAcessos.substring(position,position+1);
			//replace
			chr = (chr=='.'?'dotch':chr);
			output = output + estrutura_esquerda + chr + estrutura_direita;
			}
		return estrutura_esquerda + 'borda_esquerda' + estrutura_direita + output + estrutura_esquerda + 'borda_direita' + estrutura_direita;
		}

</script>
		<tr><td class="label_peq_sem_fundo" colspan="2" align="center"><br><BR><BR><br></td></tr>		
		<tr><td colspan="2" align="center"><script>document.write(MontaContador('<? echo number_format($_SESSION['TotalAcessos'],0,',','.');?>'));</script></td></tr>
		<tr><td class="label_peq_sem_fundo" colspan="2" align="center">Acessos desde<br><b><? echo $_SESSION['MenorData'];?></b></td></tr>
		
