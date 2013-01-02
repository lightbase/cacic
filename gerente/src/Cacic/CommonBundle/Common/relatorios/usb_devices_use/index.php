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

// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}
require_once('../../include/inicio_relatorios_inc.php');
if($_POST['submitGerarRelatorio']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list12"] = $_POST['list12'];		

	// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",$_POST['date_input1']);
	$v_data_ini = $v_elementos[2] . sprintf("%02d", $v_elementos[1]) . sprintf("%02d", $v_elementos[0]) . '000000';	
 	$_SESSION["data_ini"] = $v_data_ini;
	$v_elementos = explode("/",$_POST['date_input2']);
	$v_data_fim = $v_elementos[2] . sprintf("%02d", $v_elementos[1]) . sprintf("%02d", $v_elementos[0]) . '235959';	
 	$_SESSION["data_fim"] = $v_data_fim;
 
	require_once('../../include/library.php');
	AntiSpy();
	conecta_bd_cacic();
	
	$redes_selecionadas = '';
	if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
		{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
	
		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		}
	else
		{
		// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
		$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
			$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";
	
		$query_redes = 'AND comp.id_ip_rede = redes.id_ip_rede AND 
							redes.id_local IN ('. $locais_selecionados .') AND
							redes.id_local = locais.id_local ';
		$select = ' ,sg_local as Local ';	
		$from = ' ,redes,locais ';			
		}
	
	// Aqui pego todos os SO selecionados
	$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
		$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	
	
	if ($_GET['orderby']) 
		$orderby = $_GET['orderby'];
	else 
		$orderby = '4 DESC';
	
			
	   $query =  "SELECT 
				  distinct 		comp.te_nome_computador,
				  				comp.te_ip,
								comp.id_ip_rede,
								usbl.dt_event,
								usbl.cs_event,								
								usbd.id_device,
								usbd.nm_device,								
								usbv.id_vendor,
								usbv.nm_vendor,																
								comp.id_so, 
								comp.te_node_address " . 
								$select . " 
				  FROM 			usb_logs    usbl, 
				  				usb_vendors usbv,
								usb_devices usbd,
								computadores comp ".
								$from . " 
				  WHERE 		usbl.dt_event >= '" . $_SESSION["data_ini"] . "' AND 
								usbl.dt_event <= '" . $_SESSION["data_fim"] . "' AND 
								usbv.id_vendor = usbl.id_vendor AND
								usbd.id_vendor = usbl.id_vendor AND
								usbd.id_device = usbl.id_device AND
								comp.te_node_address = usbl.te_node_address AND 
								comp.id_so = usbl.id_so ".
								$query_redes. " 
				  ORDER BY 		$orderby ";
	//echo $query . '<br>';
		$result = mysql_query($query) or die ('Erro no select ou sua sessão expirou!');
	
	if (mysql_num_rows($result) > 0)
		{
		?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html>
		<head>
		<title>Relat&oacute;rio de Utiliza&ccedil;&atilde;o de Dispositivos USB</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<script language="JavaScript" type="text/JavaScript">
		<!--
		function MM_openBrWindow(theURL,winName,features) 
			{
			window.open(theURL,winName,features); //v2.0
			}
		//-->
		</script>
		</head>
		
		<body bgcolor="#FFFFFF" topmargin="5">
		<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
		  <tr bgcolor="#E1E1E1"> 
			<td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
			<td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
			<td bgcolor="#FFFFFF">&nbsp;</td>
		  </tr>
		  <tr bgcolor="#E1E1E1"> 
			<td nowrap bgcolor="#FFFFFF"><div align="center"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
			- Relat&oacute;rio de Utiliza&ccedil;&atilde;o de Dispositivos USB</strong></font></div></td>
		  </tr>
		  <tr> 
			<td height="1" bgcolor="#333333"></td>
		  </tr>
		  <tr> 
			<td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
				em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
		  </tr>
		</table>
		<br>
		<br>
		<br>
		<br>
		
		<?
		$cor = 0;
		$num_registro = 1;
		
		?>
	  <table border="0" align="center" cellpadding="0" cellspacing="1">
	  <tr> 
		<td align="center" nowrap></td>
	  </tr>
	  <tr> 
		<td height="1" bgcolor="#333333"></td>
	  </tr>
	  <tr> 
		<td> <table border="0" cellpadding="2" cellspacing="0" bordercolor="#333333" align="center">
			<tr bgcolor="#E1E1E1"> 
			  <td align="center"  nowrap>&nbsp;</td>
			  <td align="center"  nowrap><div align="left"><strong></strong></div></td>
			  <td align="center"  nowrap>&nbsp;</td>
			  <td align="center"  nowrap bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Nome da M&aacute;quina</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="center">IP</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="center">Sub-Rede</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="left">Local</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="center">Data/Hora</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="left">Dispositivo</div></td>
			  <td nowrap >&nbsp;</td>
			  <td nowrap class="cabecalho_tabela"><div align="left">Evento</div></td>
			  <td nowrap >&nbsp;</td>
			</tr>
			<?  
		$Cor = 0;
		$NumRegistro = 1;
		
		while($row = mysql_fetch_array($result)) 
			{
			  
		 	?>
			<tr <? if ($Cor) { echo 'bgcolor="#E1E1E1"'; } ?>> 
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><? echo $NumRegistro; ?></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_nome_computador']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['te_ip']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['id_ip_rede']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['Local']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="center"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo substr($row['dt_event'],6,2).'/'.substr($row['dt_event'],4,2).'/'.substr($row['dt_event'],0,4).' às '.substr($row['dt_event'],8,2).':'.substr($row['dt_event'],10,2).'h'; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo $row['nm_vendor'] . ' / ' . $row['nm_device']; ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			  <td nowrap class="opcao_tabela"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $row['te_node_address'];?>&id_so=<? echo $row['id_so'];?>" target="_blank"><? echo ($row['cs_event']=='I'?'Inserção':'Remoção'); ?></a></div></td>
			  <td nowrap>&nbsp;</td>
			<? 
			$Cor=!$Cor;
			$NumRegistro++;
			}
	?>
		  </table>
		</p>
		<p></p>
		<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
		  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
		  de Informa&ccedil;&otilde;es Computacionais</font><br>
		  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
		  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
		</body>
		</html>    
		<?		
		}
	}
else
	{
	$id_acao = 'cs_usb_device_use';

	
	//$historical_data_help = $oTranslator->_("Dados historicos obtidos de versoes anteriores a 2.4");
	?>
	 <script src="../../include/sniffer.js" type="text/javascript" language="javascript"></script>
	 <script src="../../include/dyncalendar.js" type="text/javascript" language="javascript"></script>
	 <link href="../../include/dyncalendar.css" media="screen" rel="stylesheet">
	
	<table width="90%" border="0" align="center">
	  <tr> 
		<td class="cabecalho">
		  <?php echo $oTranslator->_('Relatorio de utilização de dispositivos USB'); ?>
		</td>
	  </tr>
	  <tr> 
		<td class="descricao">
		  <?php echo $oTranslator->_('Exibe informacoes sobre a utilizacao de dispositivos USB nos computadores gerenciados.'); ?>
		</td>
	  </tr>
	</table>
	<form method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
	  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
		<tr>
		  <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
			  <tr> 
				<td class="label" colspan="2">
				  <?php echo $oTranslator->_('Selecione o periodo em que devera ser realizada a consulta:') ?>            </td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333" colspan="2"></td>
			  </tr>
			  <tr valign="middle"> 
				<td width="33%" height="1" nowrap valign="middle"> <input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input1;?>"> 
				  <script type="text/javascript" language="JavaScript">
		<!--
		function calendar1Callback(date, month, year)	
			{
			document.forms['forma'].date_input1.value = date + '/' + month + '/' + year;
			}
		calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
		-->
		</script> &nbsp; <font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
				  &nbsp;&nbsp; <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<? echo $date_input2;?>"> 
				  <script type="text/javascript" language="JavaScript">
		<!--
		function calendar2Callback(date, month, year)	
			{
			document.forms['forma'].date_input2.value = date + '/' + month + '/' + year;
			}
		calendar2 = new dynCalendar('calendar2', 'calendar2Callback');
		-->
		</script> </td>
				<td align="left" class="descricao"><?php echo $oTranslator->_('formato:'); ?> dd/mm/aaaa</td>
			  </tr>
			  <tr> 
				<td height="1" bgcolor="#333333" colspan="2"></td>
			  </tr>
			</table>
		  </td>
		</tr>
		<tr> 
		  <td valign="top"> 
			<?  $v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
			require_once($v_require);		
			?>
	
		  </td>
		</tr>
		<tr> 
		  <td valign="top"> 
			<?  require_once('../../include/selecao_so_inc.php');		?>
		  </td>
		</tr>
		<tr> 
		  <td valign="top">
			<table width="100%" border="0" cellpadding="0" cellspacing="1">
			  <tr> 
				<td height="1" bgcolor="#333333"></td>
			  </tr>
			  <tr> 
				<td> <div align="center"> 
					<input name="submitGerarRelatorio" id="submitGerarRelatorio" type="submit" value="        Gerar Relat&oacute;rio      " onClick="ChecaTodasAsRedes(),<? echo ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>, 
																											 SelectAll(this.form.elements['list4[]']), 
																											 SelectAll(this.form.elements['list6[]'])">				
				  </div></td>
			  </tr>
			  <tr> 
				<td>&nbsp;</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</form>
	</body>
	</html>
    <?
	}
	?>
