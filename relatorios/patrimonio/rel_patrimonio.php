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

if($_POST['submit']) 
	{
	$_SESSION["list2"]       = $_POST['list2'];
	$_SESSION["list4"]       = $_POST['list4'];
	$_SESSION["list6"]       = $_POST['list6'];
	$_SESSION["list8"]       = $_POST['list8'];			
	$_SESSION["list12"]       = $_POST['list12'];				
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
    $_SESSION['where_uon1']  = '';
    $_SESSION['where_uon2']  = '';	
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

	if($_SESSION["cs_situacao"] == 'S') 
		{
		// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			{
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";
			}
		$query_redes = "AND comp.id_ip_rede IN (". $redes_selecionadas .")";
		}
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		{
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";
		}
	$query_redes = 'AND comp.id_ip_rede = redes.id_ip_rede AND 
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

// Inicializo variável para registro de destaques de duplicidades
$in_destacar_duplicidade_total = '';

// Aqui pego todas as configurações de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel1")) 
		{ 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel1", "unid_organizacional_nivel1.nm_unid_organizacional_nivel1",  $_SESSION["list6"][$i]); 
		$where_uon1 = " patrimonio.id_unid_organizacional_nivel1 =  unid_organizacional_nivel1.id_unid_organizacional_nivel1 AND ";
		$v_from = " unid_organizacional_nivel1,";
		}
	else if (strpos(strtolower($_SESSION["list6"][$i]), "patrimonio.id_unid_organizacional_nivel2")) 
		{ 
		$_SESSION["list6"][$i] = str_replace("patrimonio.id_unid_organizacional_nivel2", "unid_organizacional_nivel2.nm_unid_organizacional_nivel2",  $_SESSION["list6"][$i]); 
		$where_uon2 = " patrimonio.id_unid_organizacional_nivel2 =  unid_organizacional_nivel2.id_unid_organizacional_nivel2 AND ";
		$v_from = " unid_organizacional_nivel2,";
		}

	if (strpos($_SESSION["list6"][$i],'#in_destacar_duplicidade.S')>-1)
		{
		if ($in_destacar_duplicidade_total) $in_destacar_duplicidade_total .= '#';
		$_SESSION["list6"][$i] = str_replace("#in_destacar_duplicidade.S", "",  $_SESSION["list6"][$i]); 					
		$arr_in_destacar_duplicidade_tmp = explode('\"',$_SESSION["list6"][$i]);
		$in_destacar_duplicidade_total  .= $arr_in_destacar_duplicidade_tmp[1];	
		}

	$campos_patrimonio = $campos_patrimonio . $_SESSION["list6"][$i];
	}

	
// Aqui substitui todas as strings \ por vazio que a variável $campos_hardware retorna
$campos_patrimonio = str_replace('\\', '', $campos_patrimonio);
$campos_patrimonio = str_replace('"', "'", $campos_patrimonio);


if ($_GET['orderby']) { $orderby = $_GET['orderby']; }
else { $orderby = '3'; } //por Nome de Computador

$query = 'SELECT 	concat(comp.te_node_address, DATE_FORMAT( max(pat.dt_hr_alteracao),"%d%m%Y%H%i")) as tripa_node_data '.
					$select.' 
		  FROM 		patrimonio pat, 
		  			computadores comp '.
					$from . '
		  WHERE 	pat.te_node_address = comp.te_node_address '.
		  			$query_redes . ' 
		  GROUP  BY comp.te_node_address';

$result = mysql_query($query) or die('Erro no select (1)');

$where = '';
while ($row = mysql_fetch_array($result)) 
	{ 
	$where .= ",'" . $row['tripa_node_data'] . "'";
	}
$where = "and concat(comp.te_node_address, DATE_FORMAT(patrimonio.dt_hr_alteracao,'%d%m%Y%H%i'))  in (" . substr($where,1)."))" ;

//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	{
	$criterios = '';
	$value_anterior = '';
	while(list($key, $value) = each($HTTP_POST_VARS))
		{
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'') 
			{
			if (trim(strpos($key,'frm_condicao_'))<>'')
				{
				$criterios .= str_replace('frm_condicao_','',$value);
				}
			else
				{
				if ($value_anterior) $criterios .= ' and ';
				$criterios = str_replace('frm_te_valor_condicao',$value,$criterios);
				}
			
			$value_anterior = $value;
			}
		} 


	$criterios = ' and '.$criterios;
	$criterios = (substr($criterios,-5)==' and '?substr($criterios,0,strlen($criterios)-5):$criterios);
	$criterios = str_replace('-MENOR-',' < ',$criterios);
	$criterios = str_replace('-MAIOR-',' > ',$criterios);	
	$criterios = str_replace("\'","'",$criterios);		
//	}


if ($where_uon1)
	{
	$where_uon1 = " LEFT JOIN unid_organizacional_nivel1 ON ( patrimonio.id_unid_organizacional_nivel1 = unid_organizacional_nivel1.id_unid_organizacional_nivel1) " ;	
	}

if ($where_uon2)
	{
	$where_uon2 = " LEFT JOIN unid_organizacional_nivel2 ON ( patrimonio.id_unid_organizacional_nivel2 = unid_organizacional_nivel2.id_unid_organizacional_nivel2) " ;	
	}

$query = " SELECT 	comp.te_node_address, 
					so.id_so, 
					comp.te_nome_computador as 'Nome Comp.', 
					sg_so as 'S.O.', 
					comp.te_ip as 'IP'" .
          			$campos_patrimonio . 
					$select . " 
		   FROM 	so, 
		   			computadores comp left join patrimonio on (comp.te_node_address = patrimonio.te_node_address and comp.id_so = patrimonio.id_so " . 
					$where . 
					$where_uon1 .  
					$where_uon2 . 
					$from . "
		   WHERE  	TRIM(comp.te_nome_computador) <> '' and 
		   			comp.id_so = so.id_so AND " . 								 
					" comp.id_so IN (". $so_selecionados .") ". $criterios . $query_redes ." 
		   ORDER BY " . $orderby; 

$result = mysql_query($query) or die('Erro no select (2)');

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
  pela Dataprev - Escrit&oacute;rio do Esp&iacute;rito Santo</font></p>
</body>
</html>