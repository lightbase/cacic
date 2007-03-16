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
/*
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}

include_once "../include/library.php";
// Comentado temporariamente - AntiSpy();
if (!$_REQUEST['date_input1'])
	{
	$from_usuarios = '';
	$where_usuarios = '';
	
	if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
		{
		$from_usuarios = ', usuarios b';
		$where_usuarios = ' AND a.id_usuario = b.id_usuario AND b.id_local = '.$_SESSION['id_local'];
		}
	
	conecta_bd_cacic();
	$query_minmax = 'SELECT 	DATE_FORMAT(min(a.dt_acao), "%d/%m/%Y") as minima,
								DATE_FORMAT(max(a.dt_acao), "%d/%m/%Y") as maxima
			  		 FROM 		log a '.
					 			$from_usuarios .' 
					 WHERE		cs_acao <> "ACE" '.
					 			$where_usuarios;
	$result_minmax = mysql_query($query_minmax);
	$row_minmax = mysql_fetch_array($result_minmax);
	$date_input1 = date('d/m/Y');
	$date_input2 = date('d/m/Y');	
	}
else
	{
	$date_input1 = $_REQUEST['date_input1'];
	$date_input2 = $_REQUEST['date_input2'];		
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title>Log de Atividades</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>
<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><div align="left">Log de Atividades</div></td>
  </tr>
  <tr> 
      <td class="descricao">Este m&oacute;dulo permite a visualiza&ccedil;&atilde;o 
        das atividades realizados com o uso das opera&ccedil;&otilde;es de INSERT/UPDATE/DELETE 
        ocorridas no Sistema CACIC. A ordena&ccedil;&atilde;o das colunas poder&aacute; 
        ser definida clicando-se em seus nomes.</td>
  </tr>
</table>
	<p><br></p>
	
  <table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="3">Selecione o per&iacute;odo em que dever&aacute; 
        ser realizada a consulta:</td>
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
      <td align="left" class="descricao">&nbsp;&nbsp;(formato: dd/mm/aaaa)</td>
      <td align="left" class="descricao" valign="middle"><div align="center">
          <input name="consultar" type="submit" value="   Filtrar   ">
        </div></td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="3"></td>
    </tr>
  </table>
	<?
	if ($_REQUEST['date_input1'])
		{
		$msg = '<div align="center">
		<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		Nenhuma ação realizada no período informado.</font><br><br></div>';
		
		$where = ($_REQUEST['nm_chamador']<>''?' AND b.id_usuario = '.$_REQUEST['id_usuario']:'');
		
		$OrderBy = ($_GET['OrderBy']<>''?$_GET['OrderBy']:'1');
		$OrderBy = ($OrderBy=='1'?$OrderBy . ' DESC ':$OrderBy);		

		$where_usuarios = '';
	
		if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
			{
			$where_usuarios = ' AND b.id_local = '.$_SESSION['id_local'];
			}

		conecta_bd_cacic();
		$query = 'SELECT 	DATE_FORMAT(a.dt_acao, "%y-%m-%d %H:%i") as dt_acao,
							a.cs_acao,
							a.nm_tabela,
							a.nm_script,
							b.nm_usuario_completo,														
							a.te_ip_origem,							
							b.id_usuario,							
							c.sg_local
				  FROM 		log a,
				  			usuarios b, 
							locais c
				  WHERE 	a.id_usuario = b.id_usuario AND
				  			b.id_local = c.id_local AND
							a.dt_acao between "' . substr($_REQUEST['date_input1'],-4)."/".substr($_REQUEST['date_input1'],3,2)."/".substr($_REQUEST['date_input1'],0,2).' 00:00" AND "' . substr($_REQUEST['date_input2'],-4)."/".substr($_REQUEST['date_input2'],3,2)."/".substr($_REQUEST['date_input2'],0,2). ' 23:59" AND
							a.cs_acao <> "ACE" ' .
							$where . 
							$where_usuarios . '    
				  ORDER BY 	'.$OrderBy;
		$result = mysql_query($query);
		$NumRegistro = mysql_num_rows($result);
		if ($NumRegistro)
			{
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
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=1&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">Data</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=2&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">Op.</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=3&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">Tabela</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=4&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">Script (.php)</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=5&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">Usu&aacute;rio</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=6&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>">IP Origem</a></div></td>
            <td></td>
          	</tr>
          	<tr> 
            <td height="1" colspan="15" bgcolor="#333333"></td>
          	</tr>
          	<?  
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
				$nm_usuario_atividades = PrimUltNome($row['nm_usuario_completo']).'/'.$row['sg_local'];		  
		
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
		
				if (array_search($nm_usuario_atividades.'#'.$row['id_usuario'],$arr_nm_usuario))
					$arr_nm_usuario[$nm_usuario_atividades.'#'.$row['id_usuario']]=1;
				else
					$arr_nm_usuario[$nm_usuario_atividades.'#'.$row['id_usuario']]++;
		
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
            	<td nowrap class="opcao_tabela"><a href="usuarios/detalhes_usuario.php?nm_chamador=Log_de_Atividades&id_usuario=<? echo $row['id_usuario'];?>"><? echo $nm_usuario_atividades;?></a></td>
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
			<td colspan="3"><div align="center"><font color="#004080" size="4">Resumo 
          	das Opera&ccedil;&otilde;es</font></div></td>
			</tr>
			<tr> 
			<td height="1" colspan="3" bgcolor="#333333"></td>
			</tr>
			<tr bgcolor="#CCCCCC"> 
			<td><div align="left"><font size="2"><strong>Opera&ccedil;&atilde;o</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>Quant.</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
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
			<td><div align="left"><font size="2"><strong>Tabela</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>Quant.</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
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
			<td><div align="left"><font size="2"><strong>Script (.php)</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>Quant.</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
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
			<td><div align="left"><font size="2"><strong>Usu&aacute;rio</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>Quant.</strong></font></div></td>
			<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
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
				$v_arr_nm_usuario = explode('#',$key);			
				echo '<td nowrap><div align="left"><a href="usuarios/detalhes_usuario.php?nm_chamador=Log_de_Atividades&id_usuario='. $v_arr_nm_usuario[1].'">'.$v_arr_nm_usuario[0].'</a></div></td>';
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
			<?
			$msg = '';
			}
			?>
	    <tr> 
	    <td height="10" colspan="3"><? echo $msg;?></td>
	    </tr>		
		<?
		}		
		?>
    <tr> 
    <td colspan="3" height="3" align="center">
	<?
	if ($_REQUEST['nm_chamador'])
		{
		?>
	    <input name="Retorna" type="button" value="  Retorna para <? echo str_replace("_"," ",$_REQUEST['nm_chamador']);?>  " onClick="history.back()">
		<?
		}
	?>

	</td>
    </tr>
  </table>
  <br>
</form>
<p>&nbsp;</p>
</body>
</html>
