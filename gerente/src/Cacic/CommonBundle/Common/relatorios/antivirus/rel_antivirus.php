<? session_start();
require_once('../../include/library.php');

if($_POST['submit']) 
	{
	$_SESSION["list2"] 		 				= $_POST['list2'];
	$_SESSION["list4"] 		 				= $_POST['list4'];
	$_SESSION["list6"] 		 				= $_POST['list6'];
	$_SESSION["list8"] 		 				= $_POST['list8'];
	$_SESSION["list10"]		 				= $_POST['list10'];
	$_SESSION["list12"]		 				= $_POST['list12'];
	$_SESSION["cs_situacao"] 				= $_POST["cs_situacao"];
	$_SESSION["cs_exibe_info_patrimonial"] 	= $_POST["frmCsExibeInfoPatrimonial"];
	$_SESSION["te_servidor"]				= '';
	$_SESSION['orderby'] = '';		
	if (count($_POST["frm_te_serv_sel"]) > 0)
		{
		for ( $i = 0; $i < count($_POST["frm_te_serv_sel"]); $i++ )
			if ($_POST["frm_te_serv_sel"])
				{
				$_SESSION["te_servidor"] .= ($_SESSION["te_servidor"]<>''?',':'');
				$_SESSION["te_servidor"] .= '"'.$_POST["frm_te_serv_sel"][$i].'"';
				}
		$_SESSION["te_servidor"] = ($_SESSION["te_servidor"]<>''?' AND officescan.te_servidor in ('.$_SESSION["te_servidor"].')':'');
		}
	else
		{
		// se nenhum servidor foi escolhido (opcional), ent�o utiliza todos disponiveis
		for( $i = 0; $i < count($_POST["frm_te_servidor"]); $i++ )
			if ($_POST["frm_te_servidor"])
				{ 
				$_SESSION["te_servidor"] .= ($_SESSION["te_servidor"]<>''?',':'');
				$_SESSION["te_servidor"] .= '"'.$_POST["frm_te_servidor"][$i].'"';
				}
		$_SESSION["te_servidor"] = ($_SESSION["te_servidor"]<>''?' AND officescan.te_servidor in ('.$_SESSION["te_servidor"].')':'');		
		}

	$_SESSION['where_date']  				= '';

	if ($_POST['date_input1'] <> '')
		{
		$arrDateInput  = explode('/',$_POST['date_input1']);
		$_SESSION['where_date']   .= ' AND officescan.dt_hr_instalacao >= "'.$arrDateInput[2].'-'.$arrDateInput[1].'-'.$arrDateInput[0].' 00:00:00"';
		}

	if ($_POST['date_input2'] <> '')
		{
		$arrDateInput  = explode('/',$_POST['date_input2']);
		$_SESSION['where_date']   .= ' AND officescan.dt_hr_instalacao <= "'.$arrDateInput[2].'-'.$arrDateInput[1].'-'.$arrDateInput[0].' 23:59:59"';
		}
	
	if ($_SESSION['where_date'])
		$_SESSION['where_date'] = ' AND trim(officescan.dt_hr_instalacao) <> "" '.$_SESSION['where_date'];	
	
	}
?>
<?php
/*
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Relatorio de configuracoes do antivirus officeScan');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body bgcolor="#FFFFFF" topmargin="5">
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
	
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
      <strong><?=$oTranslator->_('Relatorio de configuracoes do antivirus officeScan');?></strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
        <?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y �\s H:i\h"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
*/
?>
<? 
require_once('../../include/RelatorioHTML.php');
require_once('../../include/RelatorioPDF.php');
require_once('../../include/RelatorioODS.php');
require_once('../../include/RelatorioCSV.php');

AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
$from				= '';
$select				= '';
$query_redes		= '';

if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

	$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND computadores.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as "Local" ';	
	$from = ' ,redes, locais ';	
	}	

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";

// Aqui pego todas as configura��es de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	$campos_software .= $_SESSION["list6"][$i];
	
// Aqui substitui todas as strings \ por vazio que a vari�vel $campos_hardware retorna
$campos_software = str_replace('\\', '', $campos_software);

if (isset($_GET['orderby']))
{
	$orderby = $_GET['orderby'];
	$_SESSION['orderby'] = $orderby;
}
else if ($_SESSION['orderby'] != '')
{
	$orderby = $_SESSION['orderby'];
}
else
{
	$orderby = 'computadores.te_nome_computador';
}

$query = 'SELECT 	DISTINCT computadores.te_node_address, 
					so.id_so,
					UNIX_TIMESTAMP(computadores.dt_hr_ult_acesso) as "ult_acesso",
					computadores.te_nome_computador as "Nome Comp.", 				
					so.sg_so as "S.O.", 
					computadores.te_ip as "IP"' .
          			$campos_software . 
					$select . ' 
		  FROM 		so,
		  			computadores 
		  			LEFT JOIN officescan ON computadores.te_node_address = officescan.te_node_address and computadores.id_so = officescan.id_so '.
					$_SESSION["te_servidor"].
					$from. ' 		 
		  WHERE  	TRIM(computadores.te_nome_computador) <> "" AND 
		  			computadores.id_so = so.id_so AND 
					computadores.id_so IN ('. $so_selecionados .')'. 
					$_SESSION['where_date'].
		  			$query_redes .' 
		  ORDER BY ' . $orderby; 
$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('so')));

$cor = 0;
$num_registro = 1;

if (isset($_GET['formato']))
{
	$formato = $_GET['formato'];
}
else
{
	$formato = $_POST['formato'];
}
switch ($formato)
{
	case "pdf":
		$relatorio = new RelatorioPDF();
		break;
	case "ods":
		$relatorio = new RelatorioODS();
		break;
	case "csv":
		$relatorio = new RelatorioCSV();
		break;
	default:
		$relatorio = new RelatorioHTML();
		break;
}

$relatorio->setTitulo('CACIC  - Relat�rio de Configura��es do Antiv�rus OfficeScan');

$fields=mysql_num_fields($result);

$intColunaDHI = 0; // Coluna apenas para ordenar pela Data/Hora de Instalacao
$strTripaColunasValidas = array();
$tra = array();
$header = array('#');
for ($i=3; $i < mysql_num_fields($result); $i++) 
{//Table Header
	$iAux = $i;
	$iAux = ($iAux==6?7:$iAux);
	$iAux = ($iAux==8?9:$iAux);	
	// N�o posso mostrar as colunas datas/horas usadas para ordena��o	
	if (mysql_field_name($result, $i)<>'DHI' && mysql_field_name($result, $i)<>'DHUC')
	{
	   	#print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($iAux+1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
		$header[] = '<b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($iAux+1) . '">'. mysql_field_name($result, $i) .'</a></font></b>';
		$strTripaColunasValidas[] = $i;
		$tra[$i] = mysql_field_name($result, $i);
	}
}


// Caso seja selecionada a exibi��o de Informa��es Patrimoniais...
if ($_SESSION['cs_exibe_info_patrimonial']<>'')
	{
	$strTripaMacSO = '';

	// Foi necess�rio implementar essa P.O.G. devido a erro #1054 do MySQL 5.x!!!		
	while ($row = mysql_fetch_array($result))
		{
		$strTripaMacSO .= ($strTripaMacSO <> ''?',':'');
		$strTripaMacSO .= '"'.$row['te_node_address'].'_'.$row['id_so'].'"';		
		}
		
	// Restauro o ponteiro da consulta
	mysql_data_seek($result,0);

	$query_pat = 'SELECT nm_campo_tab_patrimonio,
						 te_etiqueta
				  FROM   patrimonio_config_interface
				  WHERE	 id_local = '.$_SESSION['id_local'];
	$result_pat = mysql_query($query_pat);	

	$select_pat = 'pat.te_node_address,pat.id_so';
	

	while ($row_pat = mysql_fetch_array($result_pat))
		{
		$boolMostraColuna = false;
		if ($row_pat['nm_campo_tab_patrimonio']    =='id_unid_organizacional_nivel1')
			{
			$select_pat .= ', uon1.nm_unid_organizacional_nivel1 as "' . $row_pat['te_etiqueta'].'"';
			$boolMostraColuna = true;
			}
		elseif ($row_pat['nm_campo_tab_patrimonio']=='id_unid_organizacional_nivel1a')
			{
			$select_pat .= ', uon1a.nm_unid_organizacional_nivel1a as "' . $row_pat['te_etiqueta'].'"';
			$boolMostraColuna = true;			
			}
		elseif ($row_pat['nm_campo_tab_patrimonio']=='id_unid_organizacional_nivel2')
			{
			$select_pat .= ', uon2.nm_unid_organizacional_nivel2 as "' . $row_pat['te_etiqueta'].'"';			
			$boolMostraColuna = true;			
			}
		else
			{
			$select_pat .= ', ' . $row_pat['nm_campo_tab_patrimonio'] . ' as "' . $row_pat['te_etiqueta'].'"';			
			$boolMostraColuna = true;			
			}
		
		// Mostro apenas as colunas interessantes	
		if ($boolMostraColuna)
			{
			$iAux ++;
		   	#print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial">'. $row_pat['te_etiqueta'] .'</font><b></td>';
			$header[] = '<b><font size="1" face="Verdana, Arial">'. $row_pat['te_etiqueta'] .'</font></b>';
			}
		}
		
	$from_pat = '  	unid_organizacional_nivel1  uon1, 
					unid_organizacional_nivel1a uon1a,
					unid_organizacional_nivel2  uon2,
					patrimonio pat,
					computadores comp';
					
	$where_pat = '  comp.te_node_address = pat.te_node_address AND 
					comp.id_so = pat.id_so AND 
					pat.id_unid_organizacional_nivel1a = uon1a.id_unid_organizacional_nivel1a AND
					pat.id_unid_organizacional_nivel2  = uon2.id_unid_organizacional_nivel2 AND
					uon1a.id_unid_organizacional_nivel1a = uon2.id_unid_organizacional_nivel1a AND
					uon1a.id_unid_organizacional_nivel1  = uon1.id_unid_organizacional_nivel1 ';
	$query_pat = ' SELECT ' .$select_pat.
				 ' FROM '	.$from_pat.
				 ' WHERE '	.$where_pat.
				 		 ' AND concat(pat.te_node_address,"_",pat.id_so) in ('.$strTripaMacSO.') '; 
	
	$result_pat = mysql_query($query_pat);
	while ($row_pat = mysql_fetch_array($result_pat))
		{
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['uon1']  							= $row_pat[2];
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['uon1a'] 							= $row_pat[3];
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['uon2']  							= $row_pat[4];				
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_localizacao_complementar']  	= $row_pat[5];						
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio1']  			= $row_pat[6];								
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio2'] 	 		= $row_pat[7];								
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio3']  			= $row_pat[8];								
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio4']  			= $row_pat[9];								
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio5']  			= $row_pat[10];								
		$arrMacSO[$row_pat['te_node_address'].'_'.$row_pat['id_so']]['te_info_patrimonio6']  			= $row_pat[11];																		
		}						 
	}

	
#echo '</tr>';
$relatorio->setTableHeader($header);

$table = array();
while ($row = mysql_fetch_row($result)) 
{//Table body
    
	// ja existe entrada para este MAC?
	if (isset($table[$row[0]]))
	{
		// acesso mais rencente?
		if ($row[2] > $table[$row[0]][2])
		{
			$table[$row[0]] = $row;
		}
	}
	else
	{
		$table[$row[0]] = $row;
	}
}

// adiciona informacoes patrimoniais
foreach ($table as $row)
{
	#exibe_row($num_registro, $cor, $row, $fields, $strTripaColunasValidas);

	if ($_SESSION['cs_exibe_info_patrimonial']<>'')
	{
		#echo '<td nowrap align="left">';
		$row[] =  '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['uon1'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['uon1a'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['uon2'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_localizacao_complementar'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio1'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio2'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio3'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio4'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio5'].'</font>';
		#echo '&nbsp;</td>'; 				

		#echo '<td nowrap align="left">';
		$row[] = '<font size="1" face="Verdana, Arial">'.$arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio6'].'</font>';
		#echo '&nbsp;</td>'; 				
		
	}
    $cor = !$cor;

	$relatorio->addRow(gera_row($num_registro, $row, $fields, $strTripaColunasValidas, $relatorio));
	$num_registro++;
}
#echo '</table><br><br>';

function gera_row($num_registro, $row, $fields, $strTripaColunasValidas, $relatorio)
{
	$c1 = '<font size="1" face="Verdana, Arial">' . $num_registro . '</font>'; 
	$c2 = "<font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[3] ."</a>";

	unset($row[0]);
	unset($row[1]);
	unset($row[2]);
	unset($row[3]);

	for ($i=4; $i < $fields; $i++) 
	{
		
		if ($row[$i] == 'N')
		{
			$row[$i] = '<font size="1" face="Verdana, Arial" color="#FF0000"><center><strong>N</strong></center</font>';
			$relatorio->setCellColor($num_registro - 1, $i - 2, array(0xFF, 0, 0));
		}
		else 	
		{
			if (array_search($i, $strTripaColunasValidas) !== FALSE)
			{
				$row[$i] = '<font size="1" face="Verdana, Arial" >'.$row[$i].'</font>';
			}
			else
			{
				unset($row[$i]);
			}
		}		
	}

	array_unshift($row, $c1, $c2);
	#print_r($row);
	#die();
	return $row;
}

function exibe_row($num_registro, $cor, $row, $fields, $strTripaColunasValidas)
{
	global $tra;

	echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[3] ."</a>&nbsp;</td>";

	for ($i=4; $i < $fields; $i++) 
	{
		
		if ($row[$i] == 'N')
		{
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial" ';
			echo 'color="#FF0000"><strong>N</strong>';
			echo '&nbsp;</td>'; 
		}
		else 	
		{
			if (array_search($i, $strTripaColunasValidas) !== FALSE)
			{
				echo '<td nowrap align="left"><font size="1" face="Verdana, Arial" >';
				echo /*$i." - ".$tra[$i]." - ".*/$row[$i];
				echo '&nbsp;</td>'; 
			}
		}		
	}
}

$relatorio->output();

/*
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'antivirus'; // Nome do pie que ser� chamado por tabela_estatisticas
	require_once('../../include/tabela_estatisticas.php');
	}
*/
?>
<?
/*
</p>

<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
  <?=$oTranslator->_('Gerado por');?> <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
*/
?>
