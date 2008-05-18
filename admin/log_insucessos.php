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
include_once "../include/library.php";
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if (!$_REQUEST['date_input1'])
	{	
	$date_input1 = date('d/m/Y');
	$date_input2 = date('d/m/Y');	
	$v_date_input1 = date('Y-m-d 00:00:00');
	$v_date_input2 = date('Y-m-d 23:59:59');		
	}
else
	{
	$date_input1 = $_REQUEST['date_input1'];
	$date_input2 = $_REQUEST['date_input2'];		

	$v_date_input1 = substr($_REQUEST['date_input1'],6,4) . '-' . substr($_REQUEST['date_input1'],3,2) . '-' . substr($_REQUEST['date_input1'],0,2) . ' 00:00:00';
	$v_date_input2 = substr($_REQUEST['date_input2'],6,4) . '-' . substr($_REQUEST['date_input2'],3,2) . '-' . substr($_REQUEST['date_input2'],0,2) . ' 23:59:59';
	}


// ATENÇÃO: devido à possível falta de relacionamento entre o IP e subrede, esta opção
//          será visível somente pelos níveis "Administração" e "Gestão Central".
//			É preciso estudar uma opção que permita o acesso pelo nível "Supervisão".
conecta_bd_cacic();
$query = 'SELECT 	a.te_ip,
					a.te_so,
					a.id_usuario,
					DATE_FORMAT(a.dt_datahora, "%d/%m/%Y %H:%i") as datahora,
					DATE_FORMAT(a.dt_datahora, "%d/%m/%Y") as dt_data,					
					a.dt_datahora,
					a.cs_indicador,
					b.te_desc_so
  		  FROM 		insucessos_instalacao a,
		  			so b
		  WHERE		a.dt_datahora >= "'.$v_date_input1.'" AND
		  			a.dt_datahora <= "'.$v_date_input2.'" AND
					b.te_so = a.te_so
		  ORDER BY  a.dt_datahora DESC';
$result = mysql_query($query);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title><?=$oTranslator->_('Log de Insucessos nas Instalacoes dos Agentes');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

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
    <td class="cabecalho"><div align="left"><?=$oTranslator->_('Log de Insucessos nas Instalacoes dos Agentes');?></div></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('ksiq_msg insuceso help');?></td>
  </tr>
</table>
	<p><br></p>
	
  <table width="90%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="3"><?=$oTranslator->_('Selecione o periodo em que devera ser realizada a consulta:');?></td>
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
      <td align="left" class="descricao">&nbsp;&nbsp;(formato: dd/mm/aaaa)</td>
      <td rowspan="4" align="left" valign="middle" class="descricao"><div align="center">
          <input name="consultar" type="submit" value="   Filtrar   " onClick="SelectAll(this.form.elements['list12[]'])">
        </div></td>
    </tr>
    
  <tr> 
  <td height="1" bgcolor="#333333" colspan="2"></td>
  </tr>
	
  </table>
	<?
	if ($_REQUEST['date_input1'])
		{
		$msg = '<div align="center">
		<font color="red" size="1" face="Verdana, Arial, Helvetica, sans-serif">
		Nenhuma tentativa de instalação realizada no período informado.</font><br><br></div>';
		
		$NumRegistro = mysql_num_rows($result);
		if ($NumRegistro)
			{
			?>	
			<p></p>				
	 		<table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
    		<tr> 
      		<td height="10" colspan="3" bgcolor="#CCCCCC" class="destaque"><div align="center" class="style2">RESULTADO DA CONSULTA </div></td>
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
            <td></td>
            <td></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Data');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Local/Rede');?> </div></td>
            <td nowrap class="cabecalho_tabela">&nbsp;</td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Estacao');?> </div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Sistema Operacional');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Usuario');?></div></td>
            <td></td>
            <td nowrap class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Motivo');?></div></td>
            <td></td>
          	</tr>
          	<tr> 
            <td height="1" colspan="15" bgcolor="#333333"></td>
          	</tr>
          	<?  
			$Cor = 0;
			$NumRegistro = mysql_num_rows($result);

			$arr_dt_data		= array();
			$arr_id_ip_rede		= array();			
			$arr_te_ip	 		= array();
			$arr_te_so	 		= array();	
			$arr_id_usuario	 	= array();		
			$arr_cs_indicador 	= array();					

			$arr_DadosRede		= array();
			$arr_te_ip_rede		= array();
			while($row = mysql_fetch_array($result)) 
				{	
				$arr_DadosRede = GetDadosRede($row['te_ip'],0); // Verifico se o IP "pertence" a alguma rede

				$str_te_locais_secundarios = ','.$_SESSION['te_locais_secundarios'].',';
				$intPos2 = stripos2($str_te_locais_secundarios,$arr_DadosRede['id_local']);
				if ($_SESSION['cs_nivel_administracao'] == 1 || 
				    $_SESSION['cs_nivel_administracao'] == 2 ||
					$arr_DadosRede['id_local'] == $_SESSION['id_local'] || 
					$intPos2 || 
					$arr_DadosRede['Alternative'] <> '')
					{
					if ($arr_DadosRede['Alternative']=='')
						{
						$strNmIpRede = $arr_DadosRede['sg_local'].'/'.$arr_DadosRede['id_ip_rede'].' ('.$arr_DadosRede['nm_rede'].')';
						$strIdIpRede = $arr_DadosRede['id_ip_rede'];
						}
					else
						{
						$strNmIpRede = 'Rede Não Cadastrada';
						$strIdIpRede = 'Rede Não Cadastrada';
						}

					$arr_id_ip_rede[$strNmIpRede]++;

					$arr_dt_data[$row['dt_data']]++;			
					$arr_te_ip[$row['te_ip']]++;
				
					$arr_te_so[$row['te_so'].' => '.$row['te_desc_so']]++;
					$arr_id_usuario[$row['id_usuario']]++;

					$v_cs_indicador = ($row['cs_indicador']=='0'?'Sem Privilégios':'FTP/Cópia Impossível');
					$arr_cs_indicador[$v_cs_indicador]++;		
		
					?>
    	      		<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
        	    	<td nowrap></td>
            		<td align="left" nowrap class="opcao_tabela"><? echo $NumRegistro; ?></td>
	            	<td nowrap></td>
    	        	<td nowrap class="opcao_tabela"><? echo $row['datahora'];?></td>
        	    	<td nowrap></td>
            		<td nowrap class="opcao_tabela"><? echo $strNmIpRede;?></td>
            		<td nowrap class="opcao_tabela"></td>
    	        	<td nowrap class="opcao_tabela"><? echo $row['te_ip'];?></td>
	            	<td nowrap></td>
        	    	<td nowrap class="opcao_tabela"><? echo $row['te_so'].' => '.$row['te_desc_so'];?></td>
            		<td nowrap></td>
            		<td nowrap class="opcao_tabela"><? echo $row['id_usuario'];?></td>
	            	<td nowrap></td>
    	        	<td nowrap class="opcao_tabela"><? echo ($row['cs_indicador']=='0'?'Sem Privilégios':'FTP/Cópia Impossível');?></td>
        	    	<td nowrap></td>
            		<? 
					$Cor=!$Cor;
					$NumRegistro--;					
					}
				}

			if (count($arr_dt_data)>0)
				{
				arsort($arr_te_ip,SORT_REGULAR);
//				arsort($arr_dt_data,SORT_REGULAR);			
				arsort($arr_te_so,SORT_REGULAR);			
				arsort($arr_id_usuario,SORT_REGULAR);
				arsort($arr_id_ip_rede,SORT_REGULAR);			
				arsort($arr_cs_indicador,SORT_REGULAR);					
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
				<td colspan="3" nowrap><div align="center"><font color="#004080" size="4">
				<?=$oTranslator->_('Resumo das Tentativas de Instalacao');?></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<tr bgcolor="#CCCCCC"> 
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Data');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_dt_data as $key => $value) 
					{
					$total_key += $value;
					}
			
			
				$Cor='1';
				foreach ($arr_dt_data as $key => $value) 
					{
					?>
					<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
					<td nowrap class="opcao_tabela"><div align="left"><? echo $key;?></div></td>
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
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Rede');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_id_ip_rede as $key => $value) 
					{
					$total_key += $value;
					}
			
			
				$Cor='1';
				foreach ($arr_id_ip_rede as $key => $value) 
					{
					?>
					<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
					<td nowrap class="opcao_tabela"><div align="left"><? echo $key;?></div></td>
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
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Estacao');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_te_ip as $key => $value) 
					{
					$total_key += $value;
					}
			
			
				$Cor='1';
				foreach ($arr_te_ip as $key => $value) 
					{
					?>
					<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>>
					<td nowrap class="opcao_tabela"><div align="left"><? echo $key;?></div></td>
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
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Sistema Operacional');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_te_so as $key => $value) 
					{	
					$total_key += $value;
					}
			
			
				$Cor='1';
				foreach ($arr_te_so as $key => $value) 
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
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Usuario');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_id_usuario as $key => $value) 
					{
					$total_key += $value;
					}
				
				
				$Cor='1';
				foreach ($arr_id_usuario as $key => $value) 
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
				<td><div align="left"><font size="2"><strong><?=$oTranslator->_('Motivo');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong><?=$oTranslator->_('Quant.');?></strong></font></div></td>
				<td><div align="right"><font size="2"><strong>%</strong></font></div></td>
				</tr>
				<tr> 
				<td height="1" colspan="3" bgcolor="#333333"></td>
				</tr>
				<?
				$total_key = 0;
				foreach ($arr_cs_indicador as $key => $value) 
					{
					$total_key += $value;
					}
				
				
				$Cor='1';
				foreach ($arr_cs_indicador as $key => $value) 
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
				
				<?
				$msg = '';
				}
			}
			?>
	    <tr> 
	    <td height="10" colspan="12"><? echo $msg;?></td>
	    </tr>		
		<?
		}		
		?>
    <tr> 
    <td colspan="3" height="3" align="center">
	</td>
    </tr>
  </table>
  <br>
</form>
<p>&nbsp;</p>
</body>
</html>
