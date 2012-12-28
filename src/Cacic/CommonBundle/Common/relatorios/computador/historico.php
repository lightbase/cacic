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
// Arquivo de hist�rico de rede, hardware e patrim�nio

require_once('../../include/library.php');
AntiSpy();
conecta_bd_cacic();

if ($_POST['historico_hardware']) 
	{
	$query = "SELECT 	DATE_FORMAT(dt_hr_alteracao,'%d/%m/%Y �s %H:%ih') as '".$oTranslator->_('Data de alteracao')."',
						dt_hr_alteracao, 
						te_placa_rede_desc, 
			  			te_cpu_desc, 
						te_cpu_freq, 
						te_cpu_fabricante, 
			  			te_cpu_serial, 
						te_placa_mae_desc, 
			  			te_placa_mae_fabricante, 
						te_placa_video_desc, 
			  			qt_placa_video_cores, 
						te_placa_video_resolucao, 
			  			qt_placa_video_mem, 
						qt_mem_ram, 
						te_mem_ram_desc, 
			  			te_bios_desc, 
						te_bios_fabricante, 
						te_placa_som_desc, 
			  			te_modem_desc, 
						te_cdrom_desc, 
						te_teclado_desc, 
			  			te_mouse_desc 
			  FROM 		historico_hardware 
			  WHERE 	te_node_address = '". $_POST['te_node_address'] ."' AND 
			  			id_so = '". $_POST['id_so'] ."'  
			  ORDER BY 	dt_hr_alteracao DESC";
	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('historico_hardware')));
	$tipo_historico = $oTranslator->_('Historico de Hardware');
	}
else if ($_POST['historico_patrimonio']) 
	{
	$query = "SELECT 	te_etiqueta, 
						nm_campo_tab_patrimonio
			  FROM 		patrimonio_config_interface
			  WHERE 	in_exibir_etiqueta = 'S'";
	$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('patrimonio_config_interface')));

	while ($row = mysql_fetch_array($result)) 
		{ 
		if (strtolower($row['nm_campo_tab_patrimonio']) == 'id_unid_organizacional_nivel1' ) 
			{ 
			$row['nm_campo_tab_patrimonio'] = 'b.nm_unid_organizacional_nivel1'; 
			}
		else if (strtolower($row['nm_campo_tab_patrimonio']) == 'id_unid_organizacional_nivel1a' ) 
			{ 
			$row['nm_campo_tab_patrimonio'] = 'd.nm_unid_organizacional_nivel1a'; 
			}
		else if (strtolower($row['nm_campo_tab_patrimonio']) == 'id_unid_organizacional_nivel2' ) 
			{ 
			$row['nm_campo_tab_patrimonio'] = 'c.nm_unid_organizacional_nivel2'; 
			}
		$campos = $campos . ", " . $row['nm_campo_tab_patrimonio'] . " AS '" . $row["te_etiqueta"] . "'";
		}

	$query = "SELECT 	DATE_FORMAT(dt_hr_alteracao,'%d/%m/%Y �s %H:%ih') as '".$oTranslator->_('Data de alteracao')."', 
						dt_hr_alteracao " . $campos . "
		  	  FROM 		patrimonio a 

			  LEFT JOIN unid_organizacional_nivel1a d on (a.id_unid_organizacional_nivel1a=d.id_unid_organizacional_nivel1a )
			  LEFT JOIN unid_organizacional_nivel1 b on (d.id_unid_organizacional_nivel1=b.id_unid_organizacional_nivel1) 
			  LEFT JOIN unid_organizacional_nivel2 c on (d.id_unid_organizacional_nivel1a=c.id_unid_organizacional_nivel1a)
						
		  	  WHERE 	a.te_node_address = '" . $_POST['te_node_address'] . "' AND 
		  				a.id_so = '" . $_POST['id_so'] . "'

		  	  ORDER BY 	dt_hr_alteracao";	

	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('patrimonio')));
	$tipo_historico = $oTranslator->_('Historico de Patrimonio');
	}
else if ($_POST['historico_rede']) 
	{
	$query = "SELECT 	DATE_FORMAT(dt_hr_alteracao,'%d/%m/%Y �s %H:%ih') as '".$oTranslator->_('Data de alteracao')."',
						dt_hr_alteracao, 
						te_nome_computador as 'Nome da M�quina', 
						te_ip as 'IP', 
			  			te_mascara as 'Mascara de Rede', 
						te_gateway as 'Gateway', 
						te_wins_primario as 'Wins Prim�rio', 
			  			te_wins_secundario as 'Wins Secundario', 
						te_dns_primario as 'DNS Prim�rio', 
			  			te_dns_secundario as 'DNS Secundario', 
						te_dominio_dns as 'Dom�nio DNS', 
			  			te_serv_dhcp as 'Servidor DHCP'
			  FROM 		historico_tcp_ip   
			  WHERE 	te_node_address = '".$_POST['te_node_address']."' AND 
			  			id_so = '". $_POST['id_so'] ."'   
			  ORDER BY 	dt_hr_alteracao";
	$result = mysql_query($query) or die ($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('historico_tcp_ip')));
	$tipo_historico = $oTranslator->_('Historico de TCP/IP');
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Hist&oacute;rico <? echo $tipo_historico?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF">
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong>
        <? echo $tipo_historico?>
      </strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p>&nbsp;</p></td>
  </tr>
</table>
<br>
<? 
$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
if (mysql_num_rows($result) > 0) 
	{
	// Obtenho os nomes do hardware pass�vel de controle
	$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
	
	echo '<table cellpadding="1" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
		 <tr bgcolor="#E1E1E1" >
		  <td nowrap align="left"><font size="2" face="Verdana, Arial">&nbsp;</font></td>';
	
	for ($i=0; $i < mysql_num_fields($result); $i++) 
		{ //Table Header
		if (mysql_field_name($result, $i) <> 'dt_hr_alteracao')
			{
			$strNomeColuna = $arrDescricoesColunasComputadores[mysql_field_name($result, $i)];
	   		print '<td nowrap align="center"><b><font size="2" face="Verdana, Arial">'. ($strNomeColuna <> ''?$strNomeColuna:mysql_field_name($result, $i)) .'</font><b></td>';
			}
		}
	echo '</tr>';

	while ($row = mysql_fetch_row($result)) 
		{ //Table body
		echo '<tr ';
		if ($cor) 
			echo 'bgcolor="#E1E1E1"'; 
		echo '>';
		echo '<td nowrap align="left"><font size="2" face="Verdana, Arial">' . $num_registro . '</font></td>';
	
		for ($i=0; $i < $fields; $i++) 
			{
			if (mysql_field_name($result, $i)<>'dt_hr_alteracao')
				echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 
			}
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
		}
	echo '</table>';
	}
else 
	{
	echo '</table>';
	echo mensagem($oTranslator->_('Nao foi encontrado nenhum registro'));
	}
?>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
