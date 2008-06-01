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

if($_POST['submit']) 
	{
	$_SESSION["list2"]       = $_POST['list2'];
	$_SESSION["list4"]       = $_POST['list4'];
	$_SESSION["list6"]       = $_POST['list6'];
	$_SESSION["list8"]       = $_POST['list8'];			
	$_SESSION["list12"]      = $_POST['list12'];				
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
	}

?>
<html>
<head>
<title>Relat&oacute;rio de informa&ccedil;&otilde;es de Patrim&ocirc;nio e Localiza&ccedil;&atilde;o F&iacute;sica </title>
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
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td nowrap bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>CACIC 
      - Relat&oacute;rio de informa&ccedil;&otilde;es de Patrim&ocirc;nio e Localiza&ccedil;&atilde;o 
      F&iacute;sica </strong></font></td>
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
<? 
require_once('../../include/library.php');
conecta_bd_cacic();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{

	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			{
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
			}
		$query_redes = "AND computadores.id_ip_rede IN (". $redes_selecionadas .")";
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
	$query_redes = ' AND computadores.id_ip_rede = redes.id_ip_rede ';

	if (trim($locais_selecionados) <> "''")
		$query_redes .= ' AND redes.id_local IN ('. $locais_selecionados .') ';
		
	$query_redes .= ' AND redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';		
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}

// Inicializo variável para registro de destaques de duplicidades
$in_destacar_duplicidade_total = '';

// Aqui pego todas as U.O. que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel2")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel2", "unid_organizacional_nivel2.nm_unid_organizacional_nivel2",  $_SESSION["list6"][$i]); 
	else if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel1a")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel1a", "unid_organizacional_nivel1a.nm_unid_organizacional_nivel1a",  $_SESSION["list6"][$i]); 
	else if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel1")) 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel1", "unid_organizacional_nivel1.nm_unid_organizacional_nivel1",  $_SESSION["list6"][$i]); 

	if (strpos($_SESSION["list6"][$i],'#in_destacar_duplicidade.S')>-1)
		{
		if ($in_destacar_duplicidade_total) $in_destacar_duplicidade_total .= '#';
		$_SESSION["list6"][$i] = str_replace("#in_destacar_duplicidade.S", "",  $_SESSION["list6"][$i]); 					
		$arr_in_destacar_duplicidade_tmp = explode('\"',$_SESSION["list6"][$i]);
		$in_destacar_duplicidade_total  .= $arr_in_destacar_duplicidade_tmp[1];	
		}

	$campos_patrimonio .= $_SESSION["list6"][$i];
	}

	
// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_patrimonio = str_replace('\\', '', $campos_patrimonio);
$campos_patrimonio = str_replace('"', "'", $campos_patrimonio);


if ($_GET['orderby']) { $orderby = $_GET['orderby']; }
else { $orderby = '3'; } //por Nome de Computador

// Caso a versão do MySQL utilizado não disponha de subquery...
$query = 'SELECT 	concat(computadores.te_node_address, DATE_FORMAT( max(patrimonio.dt_hr_alteracao),"%d%m%Y%H%i")) as tripa_node_data '.
					$select.' 
		  FROM 		patrimonio, 
		  			computadores '.
					$from . '
		  WHERE 	patrimonio.te_node_address = computadores.te_node_address '.
		  			$query_redes . ' 
		  GROUP  BY computadores.te_node_address';
$result = mysql_query($query) or die('Erro no select (1) ou sua sessão expirou!');

$where = '';
while ($row = mysql_fetch_array($result)) 
	{ 
	$where .= ",'" . $row['tripa_node_data'] . "'";
	}
$where = " AND concat(computadores.te_node_address, DATE_FORMAT(patrimonio.dt_hr_alteracao,'%d%m%Y%H%i'))  in (" . substr($where,1).")";

	$criterios 		= '';
	$value_anterior = '';
	$join_UO1		= '';
	$join_UO2		= '';
		
	// Monto as strings de critérios de Unidade Organizacional de Nível 1 e Nível 2, escolhidos para a consulta patrimonial
	while(list($key, $value) = each($HTTP_POST_VARS))
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
	reset($HTTP_POST_VARS);
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'' && trim(strpos($key,'frm_UO'))=='') 
			{
			if (trim(strpos($key,'frm_condicao2_'))<>'')
				{
				$criterios .= str_replace('frm_condicao2_','',$value);
				}
			elseif (trim(strpos($key,'frm_te_valor_condicao2_'))<>'')
				{
				$criterios = str_replace('frm_te_valor_condicao2',$value,$criterios);
				}			
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
	$where_uon = " AND computadores.te_node_address = patrimonio.te_node_address ";
	if ($join_UO1)
		{
		$where_uon1 = $where_uon . " AND patrimonio.id_unid_organizacional_nivel1a = unid_organizacional_nivel1a.id_unid_organizacional_nivel1a AND ".$join_UO1." " ;	
//		$where_uon = '';
//		$from .= " ,unid_organizacional_nivel1";		
		}

	if ($join_UO1a)
		{
		$where_uon1a = $where_uon . " AND unid_organizacional_nivel1a.id_unid_organizacional_nivel1 = unid_organizacional_nivel1.id_unid_organizacional_nivel1 AND ".$join_UO1a." " ;	
//		$where_uon = '';
//		$from .= " ,unid_organizacional_nivel1a";		
		}

	if ($join_UO2)
		{
		$where_uon2 = $where_uon . " AND patrimonio.id_unid_organizacional_nivel2 = unid_organizacional_nivel2.id_unid_organizacional_nivel2 AND ".$join_UO2." " ;	
//		$from .= " ,unid_organizacional_nivel2";		
		}
	}
// O valor para join_opcional é relativo à seleção de critérios para a pesquisa.
// O LEFT JOIN só deverá ser utilizado para os casos em que não forem apontados critérios...
$join_opcional = '';
if (!$join_UO1 && !$join_UO1a && !$join_UO2)
	{
	$join_opcional = ',computadores left join patrimonio on (computadores.te_node_address = patrimonio.te_node_address AND computadores.id_so = patrimonio.id_so) ';
	}
else
	{
	$from .= ' ,patrimonio, computadores ';
	}	
$query = " SELECT 	computadores.te_node_address, 
					so.id_so, 
					computadores.te_nome_computador as 'Nome Comp.', 
					sg_so as 'S.O.', 
					computadores.te_ip as 'IP'" .
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
					patrimonio.te_node_address = computadores.te_node_address AND
					patrimonio.id_so = computadores.id_so AND 
					patrimonio.id_unid_organizacional_nivel2 = unid_organizacional_nivel2.id_unid_organizacional_nivel2 AND
					unid_organizacional_nivel2.id_unid_organizacional_nivel1a = unid_organizacional_nivel1a.id_unid_organizacional_nivel1a AND
					unid_organizacional_nivel1a.id_unid_organizacional_nivel1 = unid_organizacional_nivel1.id_unid_organizacional_nivel1 " . 								 
   					$where . 
					" AND computadores.id_so IN (". $so_selecionados .") ". $criterios . $query_redes .	$where_uon1 . $where_uon2 . " 
		   ORDER BY " . $orderby; 
$result = mysql_query($query) or die('Não Existem Registros para os Parâmetros de Consulta Fornecidos ou sua sessão expirou!');
if (mysql_num_rows($result)==0)
	{
	echo mensagem('Não Existem Registros para os Parâmetros de Consulta Fornecidos.');	
	}
else
	{	

	$fields=mysql_num_fields($result);
	echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
	     <tr bgcolor="#E1E1E1" >
	      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

	if ($in_destacar_duplicidade_total) $arr_in_destacar_duplicidade_total = explode('#',$in_destacar_duplicidade_total);

	$in_destacar_duplicidade_tmp = '';
	for ($i=2; $i < mysql_num_fields($result); $i++) 
		{ //Table Header
	   	print '<td nowrap align="left"><font size="1" face="Verdana, Arial"><b><a href="?orderby=' . ($i + 1) . '">'. mysql_field_name($result, $i) .'</a></b></font></td>';
		if ($in_destacar_duplicidade_total && in_array(mysql_field_name($result, $i),$arr_in_destacar_duplicidade_total)) 
			{
			if ($in_destacar_duplicidade_tmp) $in_destacar_duplicidade_tmp .= '#';
			$in_destacar_duplicidade_tmp .= $i;
			}
		}
	echo '</tr>';

	if ($in_destacar_duplicidade_tmp) 
		{
		$arr_in_destacar_duplicidade = explode('#',$in_destacar_duplicidade_tmp);
		$v_arr_campos_valores = array();
		$num_registro = 1;
		while ($row = mysql_fetch_row($result)) 
			{
		    for ($i=3; $i < $fields; $i++) 
				{
				if (trim($row[$i])<>'' && in_array($i,$arr_in_destacar_duplicidade)) 
					{
					array_push($v_arr_campos_valores,$i . ',' . trim($row[$i]));			
					}
				}
			$num_registro ++;
			}
		$v_arr_total_campos_valores = array();
		$v_arr_total_campos_valores = array_count_values($v_arr_campos_valores);	

		$num_registro = 1;
		$v_registro_atual = '';
		@mysql_data_seek($result,0);
		while ($row = mysql_fetch_row($result)) 
			{
		    for ($i=3; $i < $fields; $i++) 
				{
				if (trim($row[$i])<>'' && in_array($i,$arr_in_destacar_duplicidade)) 
					{
					$v_chave = $i . ',' . trim($row[$i]);
					if ($v_arr_total_campos_valores[$v_chave]>1)
						{
						if ($v_registro_atual <> $num_registro) $v_campos_valores_duplicados .= 'r='.$num_registro.'#';
						$v_registro_atual = $num_registro;
						$v_campos_valores_duplicados .= '#c='.$i.'#';					
						}
					}
				}
			$num_registro++;
			}
		}

	$cor = 0;	
	$num_registro = 1;
	@mysql_data_seek($result,0);
	while ($row = mysql_fetch_row($result)) 
		{ //Table body
		$v_key_campos_valores_duplicados = strpos($v_campos_valores_duplicados,'r='.$num_registro.'#',0);
		echo '<tr ';
		
		if ($v_key_campos_valores_duplicados>-1) 
			echo 'bgcolor="#FFFF99"';
		elseif ($cor) 
			echo 'bgcolor="#E1E1E1"';
	
		echo '>';
	
		echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
		echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[0] ."&id_so=". $row[1] ."' target='_blank'>" . $row[2] ."</a>&nbsp;</td>"; 
	
		for ($i=3; $i < $fields; $i++) 
			{
			$v_bold='';
	
			echo '<td nowrap align="left"><font size="1" face="Verdana, Arial"';
	
			$j=$v_key_campos_valores_duplicados;			
			if ($j>-1)
				{
				$v_pesquisa_campo = 'c='.trim($i).'#';
				while ($j < strlen($v_campos_valores_duplicados))
					{
					if (substr($v_campos_valores_duplicados,$j,strlen($v_pesquisa_campo))==$v_pesquisa_campo)
						{			
						echo 'color="#FF0000"';
						$v_bold = 'OK';
						$j = strlen($v_campos_valores_duplicados);				
						}
					$j++;
	
					if (substr($v_campos_valores_duplicados,$j,2)=='r=')
						{
						$j = strlen($v_campos_valores_duplicados);
						}			
					}
					
				}
			
			echo '>';
			if ($v_bold) echo '<strong>';
			echo $row[$i];
			if ($v_bold) echo '</strong>';
			echo '&nbsp;</td>'; 		
			}
		$cor=!$cor;
		$num_registro++;
		echo '</tr>';
		}
	}	
	
echo '</table>';
echo '<br><br>';
/*
if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'patrimonio'; // Nome do pie que será chamado por tabela_estatisticas
	require_once($_SERVER['DOCUMENT_ROOT'] . '/cacic2/include/tabela_estatisticas.php');
	}
*/
?></p>
<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Relat&oacute;rio 
  gerado pelo <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais</font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
</body>
</html>