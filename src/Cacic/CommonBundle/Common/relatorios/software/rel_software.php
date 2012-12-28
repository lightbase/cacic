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

require_once ('../../include/multipagina.php');
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');

AntiSpy();

$DbConnect = conecta_bd_cacic();

if ($_GET['principal'])
	{
	$query = ' SELECT 	id_so
			   FROM   	so';	  
    if ($_GET['id_so']<>'')
		$query .= ' WHERE id_so='.$_GET['id_so'];
	$result = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('id_so')));
	$_SESSION["list4"] = '';				
	while ($row = mysql_fetch_array($result))
		{
		if ($_SESSION["list4"] <> '') $_SESSION["list4"] .= '#';
		$_SESSION["list4"] .= $row['id_so'];
		}

	$_SESSION["list4"] = explode('#',$_SESSION["list4"]);					
	$_SESSION["list6"] = explode('#',', dt_hr_ult_acesso as "'.$oTranslator->_('Ultimo acesso').'"');
	$_SESSION["cs_situacao"] 	= 'T';
	}
elseif($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2'];
	$_SESSION["list4"] = $_POST['list4'];
	$_SESSION["list6"] = $_POST['list6'];
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12'];		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
	}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?=$oTranslator->_('Relatorio de configuracoes de software');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="<?=CACIC_URL?>/include/cacic.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>
<?
if ($_GET['principal'])
	{
	echo '<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif">';
	}
else
	{
	echo '<body bgcolor="#FFFFFF" topmargin="5">';
	}
	?>

<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF"><? if (!$_GET['principal']) echo '<img src="../../imgs/cacic_logo.png" width="50" height="50">'; ?> </td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
	<?
	if ($_GET['principal'] == 'so')
		{
		echo $oTranslator->_('Distribuicao de sistemas operacionais dos computadores gerenciados');
		}
	elseif ($_GET['principal'] == 'acessos')
		{
		echo $oTranslator->_('Distribuicao do ultimo acesso dos agentes');
		}
	elseif ($_GET['orderby'] == 6 || $_GET['orderby']==7)
		{		
		echo $oTranslator->_('Distribuicao por versoes de agentes do CACIC');		
		}
	elseif (!$_GET['orderby'])
		{
		echo $oTranslator->_('Relatorio de configuracoes de software');
		}				
	?>
	</strong></font></td>
  </tr>
  <?
  // Caso a chamada tenha origem nos detalhamentos a partir da página principal, é enviado o ID_LOCAL 
  // para consulta individual
  if ($_GET['id_local']<>'')
  	{
	?>
  	<tr> 
   	<td bgcolor="#CCCCCC"><div align="center"><?=$oTranslator->_('Local');?> <strong><? echo $_GET['nm_local'].' ('.$_GET['sg_local'].')';?></strong>
    </div></td>
  	</tr>	
	<?
	}
  ?>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<? 
	if (!$_GET['principal'])
		{
		echo $oTranslator->_('Gerado em'). " " . date("d/m/Y à\s H:i"); 
		}
	?>
	</font></p></td>
  </tr>
</table>
<br>
<? 
$from 				= ' ,redes ';			
$local 				= '';
$redes_selecionadas = '';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
	$redes_selecionadas = "'" . $_SESSION["list2"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list2"] ); $i++ ) 
		$redes_selecionadas = $redes_selecionadas . ",'" . $_SESSION["list2"][$i] . "'";

	if (!$_GET['principal']) $query_redes = 'AND redes.id_ip_rede IN ('. $redes_selecionadas .')';		

	if ($_GET['id_local']=='')
		$local = ' AND computadores.id_ip_rede = redes.id_ip_rede AND redes.id_local = '.$_SESSION['id_local'];	

	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = "'" . $_SESSION["list12"][0] . "'";
	for( $i = 1; $i < count($_SESSION["list12"] ); $i++ ) 
		$locais_selecionados .= ",'" . $_SESSION["list12"][$i] . "'";

	$query_redes = 'AND computadores.id_ip_rede = redes.id_ip_rede AND
						redes.id_local = locais.id_local ';
						
	$select = ' ,sg_local as Local ';	
	$from .= ' ,locais ';				
	}

if (($_SESSION['te_locais_secundarios']  <> '' || $_GET['id_local'] == '') && $local <> '')		
	{
	$local = str_replace(' redes.id_ip_rede AND redes.id_local = ',' redes.id_ip_rede AND (redes.id_local = ',$local);

	if($_SESSION['te_locais_secundarios']<>'')
	   $local .= ' OR redes.id_local IN ('.$_SESSION['te_locais_secundarios'].')';
	
	$local .= ')'; 						
	}

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}

// Aqui pego todas as configurações de software que deseja exibir
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	$campos_software = $campos_software . $_SESSION["list6"][$i];
	}
// Aqui substitui todas as strings \ por vazio que a variável $campos_software retorna
$campos_software = str_replace('\\', '', $campos_software);


if ($_GET['orderby']) 
	{ 
	$orderby = $_GET['orderby']; 
	}
else 
	{ 
	$orderby = '3'; 
	} //por Nome de Computador

$from_join = '';
if ($_GET['id_local'] <> '')	
	{
	$query_redes .= ' AND computadores.id_ip_rede = redes.id_ip_rede AND redes.id_local = '.$_GET['id_local'];
	}
else
	{
	$from_join = ' LEFT JOIN versoes_softwares ON (computadores.id_so = versoes_softwares.id_so and
			 													versoes_softwares.te_node_address = computadores.te_node_address) ';
	}																			

$query = ' SELECT 	distinct computadores.te_node_address, 
					so.id_so, 
					te_nome_computador as "Nome Comp.", 
					sg_so as "S.O.", 
					te_ip as "IP"' . 
					$campos_software .
					$select .
		 ' FROM   	so, computadores '.$from_join . $from . ' 																																				 		 
		   WHERE  	trim(computadores.te_nome_computador) <> ""  and
		   			computadores.id_so = so.id_so and
					computadores.id_so IN ('. $so_selecionados .') '. $query_redes .
					$local . '  
		   ORDER BY ' . $orderby;

	if ($_GET['orderby'] == 6 ||$_GET['orderby'] == 7)
		{
		$query .= ' desc';
		}

// *****************************************************
// Código para Paginação - Anderson Peterle - 24/06/2008
// *****************************************************

// definicoes de variaveis
$arrValores 					= getValores('configuracoes_padrao', 'nu_rel_maxlinhas', '1');			

$max_links 		  				= 100; // máximo de links à serem exibidos
$nu_rel_maxlinhas 				= ($arrValores['nu_rel_maxlinhas']<>''?$arrValores['nu_rel_maxlinhas']:100); // máximo de resultados a serem exibidos por tela ou pagina
$mult_pag 	      				= new Mult_Pag(); // cria um novo objeto navbar
$mult_pag->nu_rel_maxlinhas	= $nu_rel_maxlinhas;

// metodo que realiza a pesquisa
$resultado = $mult_pag->Executar($query, $DbConnect, "", "mysql");
$reg_pag = mysql_num_rows($resultado); // total de registros por paginas ou telas

echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
	 <tr bgcolor="#E1E1E1" >
	  <td nowrap align="left"><font size="1" face="Verdana, Arial">&nbsp;</font></td>';

for ($i=2; $i < mysql_num_fields($resultado); $i++) 
	{ //Table Header
	print '<td nowrap align="left"><b><font size="1" face="Verdana, Arial"><a href="?orderby=' . ($i + 1) . '&principal='.$_GET['principal'].'">'. mysql_field_name($resultado, $i) .'</a></font><b></td>';
	}
echo '</tr>';

$cor = 0;
$num_registro = 1 + ($nu_rel_maxlinhas * $pagina);

// visualizacao do conteudo
for ($n = 0; $n < $reg_pag; $n++) 
	{
  	$linha  = mysql_fetch_object($resultado); // retorna o resultado da pesquisa linha por linha em um array
	$fields = mysql_num_fields($resultado);  
	
	$strFieldTeNodeAddress    = mysql_field_name($resultado, 0);
	$strFieldIdSo			  = mysql_field_name($resultado, 1);
	$strFieldTeNomeComputador = mysql_field_name($resultado, 2);
		
	//Table body
	echo '<tr ';
	if ($cor) 
		echo 'bgcolor="#E1E1E1"';
			
	echo '>';
	echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'><a href='../computador/computador.php?te_node_address=". $linha->$strFieldTeNodeAddress ."&id_so=". $linha->$strFieldIdSo ."' target='_blank'>" . $linha->$strFieldTeNomeComputador ."</a>&nbsp;</td>"; 
	for ($i=3; $i < $fields; $i++) 
		{
		$strNomeCampo = mysql_field_name($resultado, $i);
		echo '<td nowrap align="left"><font size="1" face="Verdana, Arial">' . $linha->$strNomeCampo .'&nbsp;</td>'; 
		}
	$cor=!$cor;
	$num_registro++;
	echo '</tr>';
	}
echo '</table>';
echo '<br><br>';

// pega todos os links e define que 'Próxima' e 'Anterior' serão exibidos como texto plano

$todos_links = $mult_pag->Construir_Links("strings", "sim");

// função que limita a quantidade de links no rodape
$links_limitados = $mult_pag->Mostrar_Parte($todos_links, $coluna, $max_links);


//Esta é a lista dos links limitados";
for ($n = 0; $n < count($links_limitados); $n++) {
  echo $links_limitados[$n] . "&nbsp;";
}

//

if (count($_SESSION["list8"])>0)
	{	
	$v_opcao = 'software'; // Nome do pie que será chamado por tabela_estatisticas
	require_once($_SERVER['DOCUMENT_ROOT'] . 'include/tabela_estatisticas.php');
	}

?></p>
<?
if (!$_GET['principal']) 
	{
	?>
	<p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?=$oTranslator->_('Gerado por');?><strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor 
  	de Informa&ccedil;&otilde;es Computacionais</font><br>
  	<font size="1" face="Verdana, Arial, Helvetica, sans-serif">Software desenvolvido 
  	pela Dataprev - Unidade Regional Esp&iacute;rito Santo</font></p>
	<?
	}
	?>
</body>
</html>
