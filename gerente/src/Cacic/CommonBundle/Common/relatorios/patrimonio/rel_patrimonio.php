<?php
session_start();
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


/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else 
	{ // Inserir regras para outras verificações (ex: permissões do usuário)!
	}
require_once('../../include/library.php');	

if($_POST['submit']) 
	{
	$_SESSION["list2"]       	= $_POST['list2'];
	$_SESSION["list4"]       	= $_POST['list4'];
	$_SESSION["list6"]       	= $_POST['list6'];
	$_SESSION["list6o"]      	= $_POST['list6'];
	$_SESSION["list8"]       	= $_POST['list8'];			
	$_SESSION["list12"]      	= $_POST['list12'];				
	$_SESSION["cs_situacao"]	= $_POST["cs_situacao"];
	$_SESSION['orderby'] 	 	= '';
	$_SESSION['post'] 		 	= $_POST;
	}
else
	{	
	//GravaTESTES('Entrei 2...');		
	$_SESSION["list6"]			= $_SESSION['list6o'];
	//GravaTESTES('Entrei 2a...');		
	}
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

	$query_redes =  " AND computadores.id_rede IN (". $redes_selecionadas .") ";
	//$query_redes .= " AND redes.te_ip = computadores.te_ip ";	
	//$query_redes .= " AND locais.id_local = redes.id_local ";		
		//}
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = ' AND computadores.id_rede = redes.id_rede ';

	if (trim($locais_selecionados) <> "''")
		$query_redes .= ' AND redes.id_local IN ('. $locais_selecionados .') ';
		
	$query_redes .= ' AND redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';		
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";

// Inicializo variável para registro de destaques de duplicidades
$in_destacar_duplicidade_total = '';

$intTotalCampos = 0;

// Aqui pego todas as U.O. que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel2")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel2", "unid_organizacional_nivel2.nm_unid_organizacional_nivel2",  $_SESSION["list6"][$i]); 
	else if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel1a")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel1a", "unid_organizacional_nivel1a.nm_unid_organizacional_nivel1a",  $_SESSION["list6"][$i]); 
	else if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel1")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel1", "unid_organizacional_nivel1.nm_unid_organizacional_nivel1",  $_SESSION["list6"][$i]); 

	if (strpos($_SESSION["list6"][$i],'#in_destacar_duplicidade.S') !== FALSE)
		{
		if ($in_destacar_duplicidade_total) $in_destacar_duplicidade_total .= '#';
		$_SESSION["list6"][$i] = str_replace("#in_destacar_duplicidade.S", "",  $_SESSION["list6"][$i]); 					
		$arr_in_destacar_duplicidade_tmp = explode('\"',$_SESSION["list6"][$i]);
		$in_destacar_duplicidade_total  .= $arr_in_destacar_duplicidade_tmp[1];	
		}

	$campos_patrimonio .= $_SESSION["list6"][$i];
	$intTotalCampos ++;
	}

	
// Aqui substitui todas as strings \ por vazio que a variável $campos_patrimonio retorna
$campos_patrimonio = str_replace('\\', '', $campos_patrimonio);
$campos_patrimonio = str_replace('"', "'", $campos_patrimonio);
$campos_patrimonio .= ",dt_hr_alteracao,DATE_FORMAT( dt_hr_alteracao,'%d/%m/%Y %H:%ih') as 'Data/Hora de Alteração'";

$intTotalCampos += 9; // Acrescento o total de campos fixos  - Anderson Peterle - 07/11/2008 12:32h

if ($_GET['orderby']) 
	{ 
	$orderby = $_GET['orderby']; 
	if ($orderby == ($intTotalCampos-1)) // Desvio da coluna com a formatação da data de alteração - Anderson Peterle - 07/11/2008 12:38h
		$orderby --;
	}
else 
	$orderby = '3'; // por Nome de Computador

// Caso a versão do MySQL utilizado não disponha de subquery...
$query = 'SELECT 	concat(computadores.id_computador, DATE_FORMAT( max(patrimonio.dt_hr_alteracao),"%d%m%Y%H%i")) as tripa_node_data '.
					$select.' 
		  FROM 		patrimonio, 
		  			computadores '.
					$from . '
		  WHERE 	patrimonio.id_computador = computadores.id_computador '.
		  			$query_redes . ' 
		  GROUP  BY computadores.id_computador';
//echo $query . '<br>';		  
$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('patrimonio')));

$where = '';
while ($row = @mysql_fetch_array($result)) 
	$where .= ",'" . $row['tripa_node_data'] . "'";

$where = " AND concat(computadores.id_computador, DATE_FORMAT(patrimonio.dt_hr_alteracao,'%d%m%Y%H%i'))  in (" . substr($where,1).")";

$criterios 		= '';
$value_anterior = '';
$join_UO1		= '';
$join_UO2		= '';
// Monto as strings de critérios de Unidade Organizacional de Nível 1 e Nível 2, escolhidos para a consulta patrimonial
while(list($key, $value) = each($_SESSION['post']))
	{
	if (trim($value)<>'' && trim($value)<>'123456' && (trim(strpos($key,'frm_condicao1'))<>'' || trim(strpos($key,'IDS_frm'))<>'')) 
		{
		if 	(trim(strpos($key,'nivel2'))<>'') // Identificador(es) de UO2
			$join_UO2 = $value;				
		elseif (trim(strpos($key,'IDS_frm_UO2'))<>'') // Desvio do campo Option do select UO2...
			{
			$join_UO2 = str_replace('frm_te_valor_condicao1',$value,$join_UO2);
			$join_UO2 = str_replace('__','.',$join_UO2);				
			$join_UO2 = str_replace("\'",'',$join_UO2);								
			}			
		elseif 	(trim(strpos($key,'nivel1a'))<>'') // Identificador(es) de UO1a
			$join_UO1a = $value;
		elseif (trim(strpos($key,'IDS_frm_UO1a'))<>'') // Desvio do campo Option do select UO1a...
			{
			$join_UO1a = str_replace('frm_te_valor_condicao1',$value,$join_UO1a);
			$join_UO1a = str_replace('__','.',$join_UO1a);				
			$join_UO1a = str_replace("\'",'',$join_UO1a);												
			}				
		elseif 		(trim(strpos($key,'nivel1'))<>'') // Identificador(es) de UO1
			$join_UO1 = $value;
		elseif (trim(strpos($key,'IDS_frm_UO1'))<>'') // Desvio do campo Option do select UO1...
			{
			$join_UO1 = str_replace('frm_te_valor_condicao1',$value,$join_UO1);
			$join_UO1 = str_replace('__','.',$join_UO1);				
			$join_UO1 = str_replace("\'",'',$join_UO1);												
			}					
		}
	}

// Reinicializo o array para nova listagem, agora para os critérios posteriores
reset($_SESSION['post']);
while(list($key, $value) = each($_SESSION['post']))
	{
	if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'' && trim(strpos($key,'frm_UO'))=='') 
		{
		if (trim(strpos($key,'frm_condicao2_'))<>'')
			$criterios .= str_replace('frm_condicao2_','',$value);
		elseif (trim(strpos($key,'frm_te_valor_condicao2_'))<>'')
			$criterios = str_replace('frm_te_valor_condicao2',$value,$criterios);

		$value_anterior = $value;
		}
	} 

if ($criterios)
	{
	$criterios = (substr($criterios,-5)==' AND '?substr($criterios,0,strlen($criterios)-5):$criterios);
	$criterios = str_replace('-MENOR-',' < ',$criterios);
	$criterios = str_replace('-MAIOR-',' > ',$criterios);	
	$criterios = str_replace("\'","'",$criterios);		
	}
	
if ($join_UO1 || $join_UO1a || $join_UO2)
	{
	$where_uon = " AND computadores.id_computador = patrimonio.id_computador ";
	if ($join_UO1)
		$where_uon1 = $where_uon . " AND patrimonio.id_unid_organizacional_nivel1a = unid_organizacional_nivel1a.id_unid_organizacional_nivel1a AND ".$join_UO1." " ;	

	if ($join_UO1a)
		$where_uon1a = $where_uon . " AND unid_organizacional_nivel1a.id_unid_organizacional_nivel1 = unid_organizacional_nivel1.id_unid_organizacional_nivel1 AND ".$join_UO1a." " ;	

	if ($join_UO2)
		$where_uon2 = $where_uon . " AND patrimonio.id_unid_organizacional_nivel2 = unid_organizacional_nivel2.id_unid_organizacional_nivel2 AND ".$join_UO2." " ;	
	}
	
// O valor para join_opcional é relativo à seleção de critérios para a pesquisa.
// O LEFT JOIN só deverá ser utilizado para os casos em que não forem apontados critérios...
$join_opcional = '';
if (!$join_UO1 && !$join_UO1a && !$join_UO2)
	$join_opcional = ',computadores left join patrimonio on (computadores.te_node_address = patrimonio.te_node_address AND computadores.id_so = patrimonio.id_so) ';
else
	$from .= ' ,patrimonio, computadores ';

$query = " SELECT 	DISTINCT computadores.te_node_address, 
					so.id_so, 
					UNIX_TIMESTAMP(computadores.dt_hr_ult_acesso),
					UNIX_TIMESTAMP(patrimonio.dt_hr_alteracao),
					computadores.te_nome_computador as 'Nome Comp.', 
					sg_so as 'S.O.', 
					computadores.te_ip_computador as 'IP',
					computadores.id_computador " .					
          			$campos_patrimonio . 
					$select . " 
		   FROM 	unid_organizacional_nivel1,
					unid_organizacional_nivel1a,		   
		   			unid_organizacional_nivel2,
					so ".
		   			$join_opcional . 
					$from . "
		   WHERE  	TRIM(computadores.te_nome_computador) <> '' AND 
		   			computadores.id_so = so.id_so AND
					patrimonio.id_computador = computadores.id_computador AND 
					patrimonio.id_unid_organizacional_nivel2 = unid_organizacional_nivel2.id_unid_organizacional_nivel2 AND
					unid_organizacional_nivel2.id_unid_organizacional_nivel1a = unid_organizacional_nivel1a.id_unid_organizacional_nivel1a AND
					unid_organizacional_nivel1a.id_unid_organizacional_nivel1 = unid_organizacional_nivel1.id_unid_organizacional_nivel1 ".
   					$where . 
					" AND computadores.id_so IN (". $so_selecionados .") ". $criterios . $query_redes .	$where_uon1 . $where_uon2 . " 
		   ORDER BY " . $orderby;
$result = mysql_query($query) or die($oTranslator->_('Registros nao encontrados na tabela %1 ou sua sessao expirou!',array('unid_organizacional_nivel1')));

if (mysql_num_rows($result)==0)
	die($oTranslator->_('Registros nao encontrados na tabela %1 para os dados fornecidos!',array('unid_organizacional_nivel1')));	
else
	{
	$fields=mysql_num_fields($result);
	if ($in_destacar_duplicidade_total)
		$arr_in_destacar_duplicidade_total = explode('#',$in_destacar_duplicidade_total);

	if (@isset($_GET['formato']))
		$formato = $_GET['formato'];
	else
		$formato = $_POST['formato'];

	switch ($formato)
		{
		case "pdf":
			require_once('../../include/RelatorioPDF.php');
			$relatorio = new RelatorioPDF();
			break;
		case "ods":
			require_once('../../include/RelatorioODS.php');		
			$relatorio = new RelatorioODS();
			break;
		case "csv":
			require_once('../../include/RelatorioCSV.php');
			$relatorio = new RelatorioCSV();
			break;
		default:
			require_once('../../include/RelatorioHTML.php');
			$relatorio = new RelatorioHTML();
			break;
		}

	$relatorio->setTitulo($oTranslator->_('Relatorio de informacoes de Patrimonio e Localizacao Fisica'));

	// String com nomes dos campos que não devem ser mostrados, concatenando-os com # para fins de busca em substring. 
 	$strNaoMostrarCamposNomes   = '#dt_hr_alteracao#'; 
 	
	// String para indices das colunas que não serão mostradas, concatenando-os com # para fins de busca em substring. 
 	$strNaoMostrarCamposIndices = ''; 
	
	$in_destacar_duplicidade_tmp = '';
	
	$header = array('#');

	//Table Header
	for ($i=4; $i < mysql_num_fields($result); $i++) 
		{
		$boolNaoMostrar = stripos2($strNaoMostrarCamposNomes,'#'.mysql_field_name($result, $i).'#',false); 
 		if (!$boolNaoMostrar) 
			{
			$header[] = '<font size="1" face="Verdana, Arial"><b><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></b></font>';
			if ($in_destacar_duplicidade_total && in_array(mysql_field_name($result, $i), $arr_in_destacar_duplicidade_total))
				{
				if ($in_destacar_duplicidade_tmp) $in_destacar_duplicidade_tmp .= '#';
				$in_destacar_duplicidade_tmp .= $i;
				}
			}
		else 
 			$strNaoMostrarCamposIndices .= '#'.$i.'#';			
		}

	$relatorio->setTableHeader($header);

	@mysql_data_seek($result,0);
	$table = array();
	while ($row = mysql_fetch_row($result)) 
		{//pre Table body
		
		// ja existe entrada para este MAC?
		if (isset($table[$row[0]]))
			{
			// acesso mais rencente?
			if ($row[2] > $table[$row[0]][2])
				$table[$row[0]] = $row;

			// desempatar pela entrada de patrimonio mais recente
			if ($row[2] == $table[$row[0]][2])
				if ($row[3] > $table[$row[0]][3])
					$table[$row[0]] = $row;
			}
		else
			$table[$row[0]] = $row;
		}

	if ($in_destacar_duplicidade_tmp) 
		{
		$arr_in_destacar_duplicidade = explode('#',$in_destacar_duplicidade_tmp);
		$v_arr_campos_valores = array();
		$num_registro = 1;
		foreach ($table as $row)
			{
		    for ($i = 5; $i < $fields; $i++) 
				if (trim($row[$i])<>'' && in_array($i,$arr_in_destacar_duplicidade)) 
					array_push($v_arr_campos_valores,$i . ',' . trim(strtolower($row[$i])));
			}
		}
	$v_arr_total_campos_valores = array();
	$v_arr_total_campos_valores = array_count_values($v_arr_campos_valores);

	$num_registro = 1;
	$registros_valores_duplicados = array();

	foreach ($table as $row)
		{
	    for ($i = 5; $i < $fields; $i++) 
			{
			if (trim($row[$i]) != '' && in_array($i, $arr_in_destacar_duplicidade)) 
				{
				$v_chave = $i . ',' . trim($row[$i]);
				// se o valor aparece mais de 1 vez
				if ($v_arr_total_campos_valores[strtolower($v_chave)] > 1)
					{
					if (!isset($registros_valores_duplicados[$num_registro]))
						$registros_valores_duplicados[$num_registro] = array();

					$registros_valores_duplicados[$num_registro][] = $i;
					}
				}
			}
		$num_registro++;
		}

	$cor = FALSE;
	$num_registro = 1;
	foreach ($table as $key => $row) 
		{ //Table body	
		$c1 = '<font size="1" face="Verdana, Arial">' . $num_registro . '</font>'; 
		$c2 = "<font size='1' face='Verdana, Arial'><a href='../computador/computador.php?id_computador=". $row[7]."' target='_blank'>" . $row[4] ."</a>";
		unset($row[0]);
		unset($row[1]);
		unset($row[2]);
		unset($row[3]);
		unset($row[4]);
		
		for ($i=4; $i < $fields; $i++) 
			{			
			$campoDuplicado = FALSE;		
			$boolNaoMostrar = stripos2($strNaoMostrarCamposIndices,'#'.$i.'#',false); 
			if (!$boolNaoMostrar) 
				{  			
				$possuiCampoDuplicado = isset($registros_valores_duplicados[$num_registro]);
				if ($possuiCampoDuplicado) 
					$relatorio->setRowColor($num_registro - 1, 0xFF, 0xFF, 0x99); //'bgcolor="#FFFF99"');
		
				$cell = '<font size="1" face="Verdana, Arial"';
	
				if ($possuiCampoDuplicado)
					if (in_array($i, $registros_valores_duplicados[$num_registro]))
						{	
						$cell .= 'color="#FF0000" ';
						$campoDuplicado = TRUE;
						$relatorio->setCellColor($num_registro - 1, $i - 3, array(0xFF, 0, 0));
						}
				
				$cell .= '>';
				if ($campoDuplicado) 
					$cell .= '<b>';

				$cell .= $row[$i];
				if ($campoDuplicado)
					$cell .= '</b>';	
				$row[$i] = $cell;
				$cell = '';
				}				
			else
				unset($row[$i]);				
			}	
		array_unshift($row, $c1, $c2);
		$relatorio->addRow($row);
		$cor = !$cor;
		$num_registro++;										
		}

	$relatorio->output();
	}
	?>