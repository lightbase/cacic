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
// Esse arquivo é um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['usb_device_use'])
	$_SESSION['usb_device_use'] = false;
if ($exibir == 'usb_device_use')
	{
	$_SESSION['usb_device_use'] = !($_SESSION['usb_device_use']);
	}
else	
	{
	$_SESSION['usb_device_use'] = false;
	}
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="6" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td class="cabecalho_tabela" colspan="6">&nbsp;<a href="computador.php?exibir=usb_device_use&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['usb_device_use'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">
   			 <?=$oTranslator->_('Informações Sobre Utilização de Dispositivos USB');?></a></td>
  </tr>
  <tr>
    <td colspan="6" height="1" bgcolor="#333333"></td>
  </tr>
  <?
	if ($_SESSION['usb_device_use'] == true) 
		{
		$linha = '	<tr bgcolor="'.$strCorDaLinha.'"> 
					<td height="1" colspan="4"></td>
					</tr>';		
		?>
		<tr> 
		<td class="cabecalho_tabela">&nbsp;<u><?=$oTranslator->_('Fabricante');?></u></td>
		<td class="cabecalho_tabela"><u><?=$oTranslator->_('Dispositivo');?></u></td>		
		<td class="cabecalho_tabela"><u><?=$oTranslator->_('Data/Hora');?></u></td>	
		<td class="cabecalho_tabela"><u><?=$oTranslator->_('Evento');?></u></td>	
		</tr>
		<?		
		echo $linha;
		$query = "	SELECT 		usb_vendors.id_vendor,
								usb_vendors.nm_vendor,
								usb_devices.id_device,
								usb_devices.nm_device,
								usb_logs.dt_event,
								usb_logs.cs_event
					FROM 		usb_logs
								LEFT JOIN usb_vendors ON usb_logs.id_vendor = usb_vendors.id_vendor
				 				LEFT JOIN usb_devices ON usb_logs.id_vendor = usb_devices.id_vendor AND usb_logs.id_device = usb_devices.id_device
					WHERE 		usb_logs.te_node_address = '".$_GET['te_node_address']."' AND
								usb_logs.id_so = '". $_GET['id_so'] ."' 
					ORDER BY	usb_logs.dt_event DESC";
		//echo $query.'<br>';					
		$result_usb_use = mysql_query($query);
		$v_achei = 0;
		$intContaItem = 0;
		$strCor = '';  					
		while ($row = mysql_fetch_array($result_usb_use)) 
			{
			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
		
			$v_achei = 1;
			$intContaItem ++;
			?>
			<tr bgcolor="<? echo $strCor;?>"> 
			<td class="descricao">&nbsp;<? echo $row['id_vendor'] . ($row['nm_vendor'] <> ''?' - ' . $row['nm_vendor']:'Fabricante Desconhecido'); ?></td>
			<td class="descricao"><? echo $row['id_device'] . ($row['nm_device'] <> ''?' - ' . $row['nm_device']:'Dispositivo Desconhecido'); ?></td>
			<td class="descricao"><? echo substr($row['dt_event'],6,2).'/'.substr($row['dt_event'],4,2).'/'.substr($row['dt_event'],0,4).' às '.substr($row['dt_event'],8,2).':'.substr($row['dt_event'],10,2).'h'; ?></td>	
			<td class="descricao"><? echo ($row['cs_event']=='I'?'Inserção':'Remoção'); ?></td>	
			</tr>
			<?
			echo $linha;
			}
		if (!$v_achei)
			{
			echo '<tr><td> 
					<p>
					<div align="center">
					<br>
					<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
					'.$oTranslator->_('Nao há informações sobre utilização de dispositivos USB referente a esta maquina').'
					</font></div>
					</p>
				  </td></tr>';
			}

		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE UTILIZAÇÃO DE DISPOSITIVOS USB
		?>
</table>
