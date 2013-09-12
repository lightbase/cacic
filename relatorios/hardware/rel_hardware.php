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

if($_POST['submit']) {
	$_SESSION["list2"] 	= $_POST['list2'];
	$_SESSION["list4"] 	= $_POST['list4'];
	$_SESSION["list6"] 	= $_POST['list6'];
	$_SESSION["list8"] 	= $_POST['list8'];		
	$_SESSION["list12"] = $_POST['list12'];			
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
	$_SESSION['orderby'] = '';
}
/*
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Relat&oacute;rio de Configura&ccedil;&otilde;es de Hardware</title>
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
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de Configura&ccedil;&otilde;es de Hardware</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p align="left"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gerado 
        em <? echo date("d/m/Y à\s H:i"); ?></font></p></td>
  </tr>
</table>
<br>
<br>
<br>
<br>
<? 
*/
require_once('../../include/library.php');
require_once('../../include/RelatorioHTML.php');
require_once('../../include/RelatorioPDF.php');
require_once('../../include/RelatorioODS.php');
require_once('../../include/RelatorioCSV.php');
// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') // Apenas Redes Selecionadas
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes	
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			{
			$redes_selecionadas .= ",'" . $_SESSION["list2"][$i] . "'";
			}
		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		//}
	//else // Todas as Redes
		//{
		//$query_redes = 'AND computadores.id_ip_rede = redes.id_ip_rede AND 
		//					redes.id_local = '. $_SESSION['id_local'].' AND
		//					redes.id_local = locais.id_local ';
		//$select = ' ,sg_local as Local ';	
		//$from = ' ,redes,locais ';							
		//}
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		{
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";
		}
	$query_redes = 'AND a.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';	
	}	

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}

$campos_hardware = '';
// Aqui pego todas as configurações de hardware que deseja exibir
$campos_hardware = '';
$campo_componentes_estacoes = '';
$join_componentes_estacoes = '';
$cs_componentes_estacoes = array();
$exibe_componentes = array();
$where_operador = FALSE;

$componentes_estacoes[0]['value'] = 'te_cpu_desc';
$componentes_estacoes[0]['tipo'] = 'CPU';

$componentes_estacoes[1]['value'] = 'te_cdrom_desc';
$componentes_estacoes[1]['tipo'] = 'CDROM';

$componentes_estacoes[2]['value'] = 'te_placa_rede_desc';
$componentes_estacoes[2]['tipo'] = 'TCPIP';

for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	$campo = $_SESSION['list6'][$i];
	$pcampo = explode('###', $campo);

	for ( $j = 0; $j < count($componentes_estacoes); $j++)
		{
			$pos = strpos($campo, $componentes_estacoes[$j]['value']);
			if ($pos !== FALSE)
				{
				$campo_componentes_estacoes = ', ce.te_valor, ce.cs_tipo_componente';
				$join_componentes_estacoes = 'LEFT OUTER JOIN componentes_estacoes ce ON (a.te_node_address = ce.te_node_address AND a.id_so = ce.id_so)';
				$cs_componentes_estacoes[] = $componentes_estacoes[$j]['tipo'];
				
				$exibe_componentes[] = $pcampo[1];

				break;
				}
		}
	$campos_hardware = $campos_hardware . ', '.$pcampo[0];//.' AS "'.$pcampo[1].'"';
	}
// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_hardware = str_replace('\\', '', $campos_hardware);

// Monta a a clausula WHERE referente a tabela componentes_estacoes
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
	$orderby = '4'; //por Nome Comp.
} 
 $query = ' SELECT	a.te_node_address,
 					so.id_so,					
					UNIX_TIMESTAMP(a.dt_hr_ult_acesso) as ult_acesso,
					a.te_nome_computador as "Nome Comp.", 
					sg_so as "S.O.", 
					a.te_ip as "IP"' .
					$campo_componentes_estacoes .
					$campos_hardware .
					$select .'
		   FROM 	so LEFT OUTER JOIN computadores a ON (a.id_so = so.id_so)
		   		'.$join_componentes_estacoes.'
				'.$from . ' 		 		 
		   WHERE  	TRIM(a.te_nome_computador) <> "" AND 
		   			a.id_so IN ('. $so_selecionados .') '
					.$query_redes .' 
		   ORDER BY ' . $orderby;
$result = mysql_query($query) or die('Erro na query SQL ou sua Sessão expirou! '.mysql_error());
#echo('<br><br>'.$query.'<br><br>');
$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);
/* PRE CLASSES RELATORIO (REMOVER)
echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
     <tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial"> </font></td>';
*/

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

$relatorio->setTitulo('CACIC  - Relatório de Configurações de Hardware');

$dicionario = carrega_dicionario();

// incializa com o header da coluna para o numero dos registros
$header = array('#');
for ($i=3; $i < mysql_num_fields($result); $i++) { //Table Header
	$name =  mysql_field_name($result, $i);

	// se nao constar no dicionario assumir que ja existe o alias
	if (isset($dicionario[$name]))
	{
		$name_tra = $dicionario[mysql_field_name($result, $i)];
	}
	else
	{
		$name_tra = $name;
	}

	if ($name != 'te_valor' AND $name != 'cs_tipo_componente')
	{
		if ($name == 'te_cpu_desc' OR $name == 'te_cdrom_desc' OR $name == 'te_placa_rede_desc')
		{
			$header[] = '<b><font size="1" face="Verdana, Arial">'. $name_tra .'</font></b>';
		}
		else
		{
			$header[] = '<b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '">'. $name_tra .'</a></font></b>';
		}
	}
}
$relatorio->setTableHeader($header);




$table = array();
while ($row = mysql_fetch_assoc($result)) //Table body
{
	for ($i = 0; $i < count($componentes_estacoes); $i++)
	{
		if (array_search($componentes_estacoes[$i]['tipo'], $cs_componentes_estacoes) !== FALSE)
		{
			$row[$componentes_estacoes[$i]['value']] = array();
		}
	}

	if (isset($table[$row['te_node_address']]))
	{
		// $trow referencia a linha no array (ao inves de copiar)
		$trow = &$table[$row['te_node_address']];
		if ($row['ult_acesso'] > $trow['ult_acesso'])
		{
			$table[$row['te_node_address']] = $row;
			$trow = &$table[$row['te_node_address']];
		}
	}
	else
	{
		$table[$row['te_node_address']] = $row;
		$trow = &$table[$row['te_node_address']];
	}
	

	if ($row['id_so'] == $trow['id_so'])
	{
		// concatena componentes
		if (array_search($row['cs_tipo_componente'], $cs_componentes_estacoes) !== FALSE)
		{
			switch ($row['cs_tipo_componente'])
			{
				case 'CPU':
					$trow['te_cpu_desc'][] = $row['te_valor'];
					break;
				case 'CDROM':
					$trow['te_cdrom_desc'][] = $row['te_valor'];
					break;
				case 'TCPIP':
					$trow['te_placa_rede_desc'][] = $row['te_valor'];
					break;
			}
		}
	}
}

foreach ($table as $row)
{
	$relatorio->addRow(geraRow($row, $num_registro++, $dicionario));
	#exibe_row($row, $num_registro++, $cor, $dicionario);
}

function geraRow($row, $num_registro, $dicionario)
{
	# adiciona numero e nome no inicio da linha
	$c1 = '<font size="1" face="Verdana, Arial">' . $num_registro . '</font>';
	$c2 = "<font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row['te_node_address'] ."&id_so=". $row['id_so'] ."' target='_blank'>" . $row['Nome Comp.'] ."</a></font>";
	

	unset($row['te_node_address']);
	unset($row['id_so']);
	unset($row['Nome Comp.']);
	unset($row['cs_tipo_componente']);
	unset($row['te_valor']);
	unset($row['ult_acesso']);

	// processa tripas
	if (isset($row['te_cpu_desc']))
	{
		$row['te_cpu_desc'] = exibe_tripa($row['te_cpu_desc'], $dicionario);
	}
	if (isset($row['te_cdrom_desc']))
	{
		$row['te_cdrom_desc'] = exibe_tripa($row['te_cdrom_desc'], $dicionario);
	}
	if (isset($row['te_placa_rede_desc']))
	{
		$row['te_placa_rede_desc'] = exibe_tripa($row['te_placa_rede_desc'], $dicionario);
	}

	foreach ($row as $key => $value)
	{
		$row[$key] = '<font size="1" face="Verdana, Arial">' . $value .' </font>';
	}

	array_unshift($row, $c1, $c2);
	return $row;
}

function carrega_dicionario()
{
	$query = 'SELECT nm_campo, te_descricao_campo FROM descricoes_colunas_computadores';
	$result = mysql_query($query) or die("Erro MySQL: ".mysql_error());
	while ($row = mysql_fetch_row($result))
	{
		$dicionario[$row[0]] = $row[1];
	}
	return $dicionario;
}

function exibe_tripa($tripas, $dicionario)
{
	$ret = array();
	
	if (!is_array($tripas))
	{
		return $tripas;
	}

	foreach ($tripas as $tripa)
	{
		$item = array();
		$pares = explode('#FIELD#', $tripa);
		foreach ($pares as $par)
		{
			$cv = explode('###', $par);
			$chave = $cv[0];
			$valor = $cv[1];
			$item[] = $dicionario[$cv[0]].": ".$cv[1];
		}
		$ret[] = implode("<br>", $item);
	}
	return implode("<br><br>", $ret);
}

function exibe_row($relatorio, $row, $num_registro, $cor, $dicionario)
{

	echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row['te_node_address'] ."&id_so=". $row['id_so'] ."' target='_blank'>" . $row['Nome Comp.'] ."</a></font> </td>"; 
    unset($row['te_node_address']);
	unset($row['id_so']);
	unset($row['Nome Comp.']);
	unset($row['cs_tipo_componente']);
	unset($row['te_valor']);
	unset($row['ult_acesso']);
	
	// processa tripas
	if (isset($row['te_cpu_desc']))
	{
		$row['te_cpu_desc'] = exibe_tripa($row['te_cpu_desc'], $dicionario);
	}
	if (isset($row['te_cdrom_desc']))
	{
		$row['te_cdrom_desc'] = exibe_tripa($row['te_cdrom_desc'], $dicionario);
	}
	if (isset($row['te_placa_rede_desc']))
	{
		$row['te_placa_rede_desc'] = exibe_tripa($row['te_placa_rede_desc'], $dicionario);
	}
	
	if ($formato == "html")
	{
		foreach ($row as $key => $value)
		{
			if ($value == '') $value = '&nbsp';
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $value .' </td>'; 
		}
	}
	else
	{
		// processa tripas
		if (isset($row['te_cpu_desc']))
		{
			$row['te_cpu_desc'] = $row['te_cpu_desc'];
		}
		if (isset($row['te_cdrom_desc']))
		{
			$row['te_cdrom_desc'] = $row['te_cdrom_desc'];
		}
		if (isset($row['te_placa_rede_desc']))
		{
			$row['te_placa_rede_desc'] = $row['te_placa_rede_desc'];
		}
		$relatorio->addRow($row);
	}
    echo '</tr>';
}
$relatorio->output();
#ob_end_flush();
/*
echo '</table>';
echo '<br><br>';
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'hardware'; // Nome do pie que será chamado por tabela_estatisticas
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
*/
