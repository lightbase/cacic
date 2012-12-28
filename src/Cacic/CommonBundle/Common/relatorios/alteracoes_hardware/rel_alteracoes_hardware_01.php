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
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

if($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];

	// Aqui eu inverto as datas para YYYYMMDD
	$v_elementos = explode("/",$_POST['date_input1']);
	$v_data_ini = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];	
 	$_SESSION["data_ini"] = $v_data_ini;
	$v_elementos = explode("/",$_POST['date_input2']);
	$v_data_fim = $v_elementos[2] .'/'. $v_elementos[1] .'/'. $v_elementos[0];
 	$_SESSION["data_fim"] = $v_data_fim;
	}
?>
<? 
require_once('../../include/library.php');
AntiSpy();

$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	//if($_SESSION["cs_situacao"] == 'S') 
		//{
		// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
		$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
		for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
			$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

		$query_redes = 'AND id_ip_rede IN ('. $redes_selecionadas .')';
		//}	
	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND comp.id_ip_rede = redes.id_ip_rede AND 
						redes.id_local IN ('. $locais_selecionados .') AND
						redes.id_local = locais.id_local ';
	$select = ' ,sg_local as Local ';	
	$from = ' ,redes,locais ';			
	}

// houve impressao de dados historicos
$obteve_dados = true;

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";

// Aqui pego todas as configura��es de hardware que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	$campos_hardware = $campos_hardware . $_SESSION["list6"][$i];

// Aqui substitui todas as strings \ por vazio que a vari�vel $campos_hardware retorna
$campos_hardware = str_replace('\\', '', $campos_hardware);

// Aqui inclui o "hist." devido � origem das informa��es sobre o hardware ser a tabela de hist�ricos
$campos_hardware = str_replace(', ', ', hist.', $campos_hardware);

if ($_GET['orderby']) 
	$orderby = $_GET['orderby'];
else 
	$orderby = '1';

		
   $query =  "SELECT 
   			  distinct 		comp.te_nome_computador,
							comp.id_so, 
							comp.te_node_address " . 
							$campos_hardware .
							$select . " 
			  FROM 			historico_hardware hist, 
			  				computadores comp ".
							$from . " 
			  WHERE 		DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('" . $_SESSION["data_ini"] . "', '%Y%m%d') AND 
							DATE_FORMAT(hist.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('" . $_SESSION["data_fim"] . "', '%Y%m%d') AND 
							comp.te_node_address = hist.te_node_address AND 
							comp.id_so = hist.id_so ".
							$query_redes. " 
			  ORDER BY 		$orderby ";

	$result = mysql_query($query) or die ($oTranslator->_('Falha na Consulta a tabela (%1) ou sua sessao expirou!',array('historico_hardware')));


$cor = 0;
$num_registro = 1;

$fields=mysql_num_fields($result);

echo '<br><br>';
echo '<table align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">' .
        "<tr><th colspan=200>".$oTranslator->_('Dados historicos')."</th></tr>".      
		'<tr bgcolor="#E1E1E1" >
      <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

echo '<td nowrap align="left"><b><font size="1" face="Verdana, Arial">Computador</font><b></td>';
for ($i=3; $i < $fields; $i++) //Table Header
   	print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial">'. mysql_field_name($result, $i) .'</font><b></td>';

echo '</tr>';


while ($row = mysql_fetch_row($result)) //Table body
	{ 
    echo '<tr ';
	if ($cor) { echo 'bgcolor="#E1E1E1"'; } 
	echo '>';
    echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>';
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $row[2] ."&id_so=". $row[1] ."' target='_blank'>" . $row[0] ."</a>&nbsp;</td>"; 
    for ($i=3; $i < $fields; $i++) 
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $row[$i] .'&nbsp;</td>'; 

    $cor=!$cor;
	$num_registro++;
    echo '</tr>';
	}
echo '</table>';
echo '<br><br>';
if (count($_SESSION["list8"])>0)
	{
	$v_opcao = 'alteracoes_hardware'; // Nome do pie que ser� chamado por tabela_estatisticas
	$query_redes .= " AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') >= DATE_FORMAT('".$v_data_ini."', '%Y%m%d')) 
		  		AND (DATE_FORMAT(a.dt_hr_alteracao, '%Y%m%d') <= DATE_FORMAT('".$v_data_fim."', '%Y%m%d')) ";
	// Os sinais -=- acima s�o propositais em substitui��o aos "'" e sofrer�o replace no pie.
	require_once('../../include/tabela_estatisticas.php');
	}

?>
</p>
