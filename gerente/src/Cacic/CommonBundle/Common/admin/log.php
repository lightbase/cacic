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
include_once "../include/library.php";
AntiSpy();
if (!$_POST['date_input1'])
	{
	conecta_bd_cacic();
	$query_minmax = 'SELECT 	DATE_FORMAT(min(a.dt_acao), "%d/%m/%Y") as minima,
								DATE_FORMAT(max(a.dt_acao), "%d/%m/%Y") as maxima
			  		 FROM 		log a';
	//echo $query_minmax . '<br>';
	$result_minmax = mysql_query($query_minmax);
	$row_minmax = mysql_fetch_array($result_minmax);
//	$date_input1 = $row_minmax['minima'];
	$date_input1 = date('d/m/Y');
	$date_input2 = $row_minmax['maxima'];	
	}
else
	{
	$date_input1 = $_POST['date_input1'];
	$date_input2 = $_POST['date_input2'];		
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Log de Atividades');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><div align="left"><?=$oTranslator->_('Log de Atividades');?></div></td>
  </tr>
  <tr> 
      <td class="descricao">
        <?=$oTranslator->_('Visualizacao das ocorrencias com as operacoes de atualizacao, inclusao e exclusao no sistema.');?>
      </td>
  </tr>
</table>
	<p><br></p>
	
  <table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="3">
        <?=$oTranslator->_('Selecione o periodo em que devera ser realizada a consulta');?>
      </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
    <tr valign="middle"> 
      <td width="33%" height="1" nowrap valign="middle">
<input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input1;?>"> 
        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar1Callback(date, month, year)	
		{
		document.forms['form1'].date_input1.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
	//-->
	</script> &nbsp; <font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
        &nbsp;&nbsp; <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input2;?>"> 
        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar2Callback(date, month, year)	
		{
		document.forms['form1'].date_input2.value = date + '/' + month + '/' + year;
		}
  	calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
	//-->
	</script> </td>
      <td align="left" class="descricao">&nbsp;&nbsp;(<?=$oTranslator->_('Formato da data');?> <?=$oTranslator->_('dd/mm/aaaa');?>)</td>
      <td align="left" class="descricao" valign="middle"><div align="center">
          <input name="consultar" type="submit" value="   Filtrar   ">
        </div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
  </table>
	<?
	if ($_POST['date_input1'])
		{
		conecta_bd_cacic();
		$query = 'SELECT 	DATE_FORMAT(a.dt_acao, "%y-%m-%d %h:%i") as dt_acao,
							a.cs_acao,
							a.nm_script,
							a.nm_tabela,
							a.te_ip_origem,
							b.nm_usuario_completo,
							c.sg_local
				  FROM 		log a,
				  			usuarios b, 
							locais c
				  WHERE 	a.id_usuario = b.id_usuario AND
				  			b.id_local = c.id_local AND
							a.dt_acao between "' . substr($_POST['date_input1'],-4)."/".substr($_POST['date_input1'],3,2)."/".substr($_POST['date_input1'],0,2).' 00:00" AND "' . substr($_POST['date_input2'],-4)."/".substr($_POST['date_input2'],3,2)."/".substr($_POST['date_input2'],0,2). ' 23:59" 
				  ORDER BY 	a.dt_acao DESC,
				  			b.nm_usuario_completo';
//echo $query . '<br>';
		$result = mysql_query($query);
		
		?>	
	<p></p>				
	 	<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    	<tr> 
      	<td height="10" colspan="3">&nbsp;</td>
    	</tr>
    	<tr> 
      	<td height="10" colspan="3"></td>
    	</tr>
    	<tr> 
      	<td height="1" colspan="3" bgcolor="#333333"></td>
    	</tr>
    	<tr> 
      	<td colspan="3"> 
		<table width="90%" border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
          <tr bgcolor="#E1E1E1"> 
            <td colspan="3"></td>
            <td align="center" nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Data');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Operacao');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Tabela');?></div></td>
            <td></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Script');?>
                (.php)</div></td>
            <td></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Usuario');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><?=$oTranslator->_('IP Origem');?></td>
            <td></td>
          </tr>
          <tr> 
            <td height="1" colspan="15" bgcolor="#333333"></td>
          </tr>
          <?  
		$msg = '<div align="center">
		       <font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">'.
		       $oTranslator->_('Nenhuma acao realizada no periodo informado.') .
		       '</font><br><br></div>';
		
		$Cor = 0;
		$NumRegistro = mysql_num_rows($result);
		$msg = ($NumRegistro > 1?'':$msg);	
		$arr_cs_acao 	= array();
		$arr_nm_script	= array();
		$arr_nm_tabela	= array();	
		$arr_nm_usuario	= array();		
		while($row = mysql_fetch_array($result)) 
			{
			list($year, $month, $day) = explode("-", $row['dt_acao']);
			list($day,$hour) = explode(" ",$day); 
			$nm_usuario = PrimUltNome($row['nm_usuario_completo']).'/'.$row['sg_local'];		  
		
			if (array_search($row['cs_acao'],$arr_cs_acao))
				$arr_cs_acao[$row['cs_acao']]=1;
			else
				$arr_cs_acao[$row['cs_acao']]++;

			$nm_script =  str_replace('.php','',$row['nm_script']);			
			if (array_search($nm_script,$arr_nm_script))
				$arr_nm_script[$nm_script]=1;
			else
				$arr_nm_script[$nm_script]++;
		
			if (array_search($row['nm_tabela'],$arr_nm_tabela))
				$arr_nm_tabela[$row['nm_tabela']]=1;
			else
				$arr_nm_tabela[$row['nm_tabela']]++;
		
			if (array_search($nm_usuario,$arr_nm_usuario))
				$arr_nm_usuario[$nm_usuario]=1;
			else
				$arr_nm_usuario[$nm_usuario]++;
		
			?>
          <tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            <td nowrap></td>
            <td align="left" nowrap class="opcao_tabela"><? echo $NumRegistro; ?></td>
            <td nowrap></td>
            <td nowrap class="opcao_tabela"><? echo $day.'/'.$month.'/'.$year. ' '. substr($hour,0,5);?></td>
            <td nowrap></td>
            <td nowrap class="opcao_tabela"><? echo $row['cs_acao'];?></td>
            <td nowrap></td>
            <td nowrap class="opcao_tabela"><? echo $row['nm_tabela'];?></td>
            <td nowrap></td>
				<td nowrap class="opcao_tabela"><? echo $nm_script;?></td>
            <td nowrap></td>
            <td nowrap class="opcao_tabela"><? echo $nm_usuario;?></td>
            <td nowrap></td>
            <td nowrap class="opcao_tabela"><? echo $row['te_ip_origem'];?></td>
            <td nowrap></td>
            <? 
			$Cor=!$Cor;
			$NumRegistro--;
			}
		arsort($arr_cs_acao,1);
		arsort($arr_nm_tabela,1);
		arsort($arr_nm_script,1);
		arsort($arr_nm_usuario,1);			
		
		?>
        </table></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr>
		<td height="10" colspan="3">&nbsp;</td>
		</tr>
		<tr> 
		<td height="10" colspan="3">&nbsp;</td>
		</tr>
		</table>
		<table width="293" border="0" align="center" cellpadding="0" cellspacing="1">
		<tr> 
		<td colspan="3"><div align="center"><font color="#004080" size="4">
		  <?=$oTranslator->_('Resumo das Operacoes');?>
          </font></div></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr bgcolor="#CCCCCC"> 
		<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Operacao');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual', T_SIGLA);?></strong></font></div></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<?
		$total_key = 0;
		foreach ($arr_cs_acao as $key => $value) 
			{
			$total_key += $value;
			}
		
		
		$Cor='1';
		foreach ($arr_cs_acao as $key => $value) 
			{
			?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			<?
			echo '<td nowrap><div align="left">'.$key.'</div></td>';
			echo '<td nowrap><div align="right">'.$value.'</div></td>';
			echo '<td nowrap><div align="right">'.number_format(($value/$total_key)*100,2).'</div></td>';		
			echo '</tr>';
			$Cor=!$Cor;
			}
		?>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td nowrap>&nbsp;</td>
		<td align="right"><? echo $total_key;?></td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr bgcolor="#CCCCCC"> 
		<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Tabela');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual', T_SIGLA);?></strong></font></div></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<?
		$total_key = 0;
		foreach ($arr_nm_tabela as $key => $value) 
			{
			$total_key += $value;
			}
		
		
		$Cor='1';
		foreach ($arr_nm_tabela as $key => $value) 
			{
			?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			<?
			echo '<td nowrap><div align="left">'.$key.'</div></td>';
			echo '<td nowrap><div align="right">'.$value.'</div></td>';
			echo '<td nowrap><div align="right">'.number_format(($value/$total_key)*100,2).'</div></td>';		
			echo '</tr>';
			$Cor=!$Cor;
			}
		?>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td nowrap>&nbsp;</td>
		<td align="right"><? echo $total_key;?></td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr bgcolor="#CCCCCC"> 
		<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Script');?> (.php)</strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual', T_SIGLA);?></strong></font></div></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<?
		$total_key = 0;
		foreach ($arr_nm_script as $key => $value) 
			{
			$total_key += $value;
			}
		
		
		$Cor='1';
		foreach ($arr_nm_script as $key => $value) 
			{
			?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			<?
			echo '<td nowrap><div align="left">'.$key.'</div></td>';
			echo '<td nowrap><div align="right">'.$value.'</div></td>';
			echo '<td nowrap><div align="right">'.number_format(($value/$total_key)*100,2).'%</div></td>';		
			echo '</tr>';
			$Cor=!$Cor;
			}
		?>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td nowrap>&nbsp;</td>
		<td align="right"><? echo $total_key;?></td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr bgcolor="#CCCCCC"> 
		<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Usuario');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
		<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual', T_SIGLA);?></strong></font></div></td>
		</tr>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<?
		$total_key = 0;
		foreach ($arr_nm_usuario as $key => $value) 
			{
			$total_key += $value;
			}
		
		
		$Cor='1';
		foreach ($arr_nm_usuario as $key => $value) 
			{
			?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
			<?
			echo '<td nowrap><div align="left">'.$key.'</div></td>';
			echo '<td nowrap><div align="right">'.$value.'</div></td>';
			echo '<td nowrap><div align="right">'.number_format(($value/$total_key)*100,2).'%</div></td>';		
			echo '</tr>';
			$Cor=!$Cor;
			}
		?>
		<tr> 
		<td height="1" colspan="3" bgcolor="#333333"></td>
		</tr>
		<tr> 
		<td nowrap>&nbsp;</td>
		<td align="right"><? echo $total_key;?></td>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
	    <tr> 
	    <td height="10" colspan="3"><? echo $msg;?></td>
	    </tr>
		
		<?
		}
		?>
    <tr> 
    <td colspan="3" height="3">&nbsp;</td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
