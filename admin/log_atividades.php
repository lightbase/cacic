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
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

include_once "../include/library.php";
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if (!$_REQUEST['date_input1'])
	{
	$from_usuarios = '';
	$where_usuarios = '';
	
	if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
		{
		$from_usuarios  = ', usuarios b';
		$where_usuarios = ' AND a.id_usuario = b.id_usuario AND b.id_local = '.$_SESSION['id_local'];
		if ($_SESSION['te_locais_secundarios'])
			{
			$whereLocais = 'WHERE id_local = '.$_SESSION['id_local'].' OR id_local IN ('.$_SESSION['te_locais_secundarios'].') ';
			}		
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
<title><?=$oTranslator->_('Log de Atividades');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
// JavaScripts para fazer a selecao entre os listbox, movendo itens entre eles.
require_once('../include/selecao_listbox.js');
?>

<style type="text/css">
<!--
.style2 {font-size: large}
-->
</style>
</head>

<body background="../imgs/linha_v.gif">
<script language="JavaScript" type="text/javascript" src="../include/cacic.js"></script>

<form name="form1" method="post" action="">
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><div align="left"><?=$oTranslator->_('Log de Atividades');?></div></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('kciq_msg Log de atividades help');?></td>
  </tr>
</table>
	<p><br></p>
	
  <table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="3"><?=$oTranslator->_('Selecione o periodo no qual devera ser realizada a consulta');?>:</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr valign="middle"> 
      <td width="33%" height="1" nowrap valign="middle">
	<input name="whereLocais" type="hidden" value="<? echo $whereLocais;?>"> 	  
<input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input1;?>"> 
<? /*
        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar1Callback(date, month, year)	
		{
		document.forms['form1'].date_input1.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
	//-->
	</script>*/?> &nbsp; <font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
        &nbsp;&nbsp; <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input2;?>"> 
<? /*        <script type="text/javascript" language="JavaScript">
	<!--
	function calendar2Callback(date, month, year)	
		{
		document.forms['form1'].date_input2.value = date + '/' + month + '/' + year;
		}
  	calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
	//-->
	</script>*/?> </td>
      <td align="left" class="descricao">(<?=$oTranslator->_('Formato da data');?>: dd/mm/aaaa)</td>
      <td rowspan="4" align="left" valign="middle" class="descricao"><div align="center">
          <input name="consultar" type="submit" value="   Filtrar   " onClick="SelectAll(this.form.elements['list12[]'])">
        </div></td>
    </tr>
    
	<?
	if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2 || ($_SESSION['cs_nivel_administracao'] == 3 && $_SESSION['te_locais_secundarios']<>''))
		{
		?>
		<TR><td height="20"></td></TR>
    	<tr valign="middle">
      	<td height="1" colspan="2" valign="middle" nowrap><div align="left">
	  	<?
		include_once "../include/selecao_locais_inc.php";	  
	  	?>
    	</div></td></tr>
		<?
		}
		?>
  <tr> 
  <td height="1" bgcolor="#333333" colspan="2"></td>
  </tr>
	
  </table>
	<?
	if ($_REQUEST['date_input1'])
		{
		$msg = '<div align="center">
		<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">'.
		$oTranslator->_('Nenhuma atividade realizada no período informado').'.</font><br><br></div>';
		
		$where = ($_REQUEST['nm_chamador']<>''?' AND b.id_usuario = '.$_REQUEST['id_usuario']:'');
		
		$OrderBy = ($_GET['OrderBy']<>''?$_GET['OrderBy']:'1');
		$OrderBy = ($OrderBy=='1'?$OrderBy . ' DESC ':$OrderBy);		

		$where_usuarios = '';
	
		if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
			{
			$where_usuarios = ' AND b.id_local = '.$_SESSION['id_local'];
			}
		else
			{
			$itens_locais = '';
			for ($i =0; $i < count($_POST['list12']);$i++)
				{
				if ($itens_locais)
					$itens_locais .= ',';
				$itens_locais .= $_POST['list12'][$i];
				}
			$where_usuarios = ' AND b.id_local IN ('.$itens_locais.')';
			if (count($_POST['list12'])==0)
				$msg = '<div align="center">
				<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
				Nenhum acesso realizado no <u>período informado</u> ou nenhum <u>local selecionado</u>.</font><br><br></div>';				
			
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
      		<td height="10" colspan="3" bgcolor="#CCCCCC" class="destaque"><div align="center" class="style2"><?=$oTranslator->_('Resultado da consulta');?></div></td>
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
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=1&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Data');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=2&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Operacao');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=3&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Tabela');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=4&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Programa');?> (.php)</a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=5&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Usuario');?></a></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><a href="log_atividades.php?OrderBy=6&date_input1=<? echo $date_input1;?>&date_input2=<? echo $date_input2;?>"><?=$oTranslator->_('Endereco IP origem');?></a></div></td>
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
		
				$arr_cs_acao[$row['cs_acao']]++;

				$nm_script =  str_replace('.php','',$row['nm_script']);			
				$arr_nm_script[$nm_script]++;
		
				$arr_nm_tabela[$row['nm_tabela']]++;
		
				$arr_nm_usuario[$nm_usuario_atividades.'#'.$row['id_usuario']]++;
		
				?>
          		<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
            	<td nowrap></td>
            	<td align="left" nowrap class="opcao_tabela"><? echo $NumRegistro; ?></td>
            	<td nowrap></td>
            	<td nowrap class="opcao_tabela"><? echo $day.'/'.$month.'/'.$year. ' '. substr($hour,0,5);?></td>
            	<td nowrap></td>
            	<td nowrap class="opcao_tabela_destaque"><font color="<? echo ($row['cs_acao']=='INS'?'#006600':($row['cs_acao']=='UPD'?'#FF9933':'#FF0000'));?>"><? echo $row['cs_acao'];?></font></td>
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
			<td colspan="3"><div align="center"><font color="#004080" size="4">
			<?=$oTranslator->_('Resumo das Operacoes');?></font></div></td>
			</tr>
			<tr> 
			<td height="1" colspan="3" bgcolor="#333333"></td>
			</tr>
			<tr bgcolor="#CCCCCC"> 
			<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Operacao');?></strong></font></div></td>
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual');?></strong></font></div></td>
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
				<td nowrap class="opcao_tabela_destaque"><div align="left"><font color="<? echo ($key=='INS'?'#006600':($key=='UPD'?'#FF9933':'#FF0000'));?>"><? echo $key;?></font></div></td>
				<td nowrap><div align="right"><? echo $value;?></div></td>
				<td nowrap><div align="right"><? echo number_format(($value/$total_key)*100,2);?></div></td>
				</tr>
				<?
				$Cor=!$Cor;
				}
			?>
			<tr> 
			<td height="1" colspan="3" bgcolor="#333333"></td>
			</tr>
			<tr bgcolor="#CCCCCC"> 
			<td nowrap class="opcao_tabela_destaque"><?=$oTranslator->_('Total');?></td>
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
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual');?></strong></font></div></td>
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
			<tr bgcolor="#CCCCCC"> 
			<td nowrap class="opcao_tabela_destaque"><?=$oTranslator->_('Total');?></td>
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
			<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Programa');?> (.php)</strong></font></div></td>
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quantidade');?></strong></font></div></td>
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual');?></strong></font></div></td>
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
			<tr bgcolor="#CCCCCC"> 
			<td nowrap class="opcao_tabela_destaque"><?=$oTranslator->_('Total');?></td>
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
			<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Percentual');?></strong></font></div></td>
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
			<tr bgcolor="#CCCCCC"> 
			<td nowrap class="opcao_tabela_destaque"><?=$oTranslator->_('Total');?></td>
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
	    <input name="Retorna" type="button" value="  <?=$oTranslator->_('Retorna para');?> <? echo str_replace("_"," ",$_REQUEST['nm_chamador']);?>  " onClick="history.back()">
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
