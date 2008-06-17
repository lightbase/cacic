<? session_start();

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
		// se nenhum servidor foi escolhido (opcional), então utiliza todos disponiveis
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es do Antiv&iacute;rus OfficeScan</title>
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
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Configura&ccedil;&otilde;es do Antiv&iacute;rus OfficeScan</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i\h"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
require_once('../../include/library.php');
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
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
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

// Aqui pego todas as configurações de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	$campos_software .= $_SESSION["list6"][$i];
	
// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_software = str_replace('\\', '', $campos_software);

if ($_GET['orderby']) 
	$orderby = $_GET['orderby'];
else
	$orderby = 'computadores.te_nome_computador';

$query = 'SELECT 	distinct computadores.te_node_address, 
					so.id_so, 
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
$result = mysql_query($query) or die('Erro no select ou sua sessão expirou!');

$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

$intColunaDHI = 0; // Coluna apenas para ordenar pela Data/Hora de Instalacao
$strTripaColunasValidas = '#';
for ($i=2; $i < mysql_num_fields($result); $i++) 
	{//Table Header
	$iAux = $i;
	$iAux = ($iAux==6?7:$iAux);
	$iAux = ($iAux==8?9:$iAux);	
	// Não posso mostrar as colunas datas/horas usadas para ordenação	
	if (mysql_field_name($result, $i)<>'DHI' && mysql_field_name($result, $i)<>'DHUC')
		{
	   	print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($iAux+1) . '">'. mysql_field_name($result, $i) .'</a></font><b></td>';
		$strTripaColunasValidas .= $i . '#';
		}
	}

// Caso seja selecionada a exibição de Informações Patrimoniais...
if ($_SESSION['cs_exibe_info_patrimonial']<>'')
	{
	$strTripaMacSO = '';

	// Foi necessário implementar essa P.O.G. devido a erro #1054 do MySQL 5.x!!!		
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
		   	print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial">'. $row_pat['te_etiqueta'] .'</font><b></td>';			
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

	
echo '</tr>';

while ($row = mysql_fetch_row($result)) 
	{//Table body
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) 
		{
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial" ';
		if 		($row[$i] == 'N') 
			echo 'color="#FF0000"><strong>N</strong>';
		else 	
			{
			echo '>';

			// Não posso mostrar as colunas datas/horas usadas para ordenação				
			$boolExibe = false;
			$j = $i-1;
			while ($j < $fields && !$boolExibe)
				{
				$j++;
				$boolExibe = stripos2($strTripaColunasValidas, '#'.$j.'#',false);
				}
			$i = $j;

			echo $row[$i];
			}			
		echo '&nbsp;</td>'; 		
		}
	if ($_SESSION['cs_exibe_info_patrimonial']<>'')
		{
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['uon1'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['uon1a'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['uon2'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_localizacao_complementar'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio1'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio2'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio3'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio4'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio5'];
		echo '&nbsp;</td>'; 				

		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">';
		echo $arrMacSO[$row[0].'_'.$row[1]]['te_info_patrimonio6'];
		echo '&nbsp;</td>'; 				
		
		}
    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
	}
echo '</table><br><br>';
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'antivirus'; // Nome do pie que será chamado por tabela_estatisticas
	require_once('../../include/tabela_estatisticas.php');
	}
?></p>

<p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>
