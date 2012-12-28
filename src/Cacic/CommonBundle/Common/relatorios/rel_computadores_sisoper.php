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

require_once('../include/library.php');
AntiSpy();

require_once ('../include/multipagina.class.php');
$DbConnect = conecta_bd_cacic();

$titulo = $oTranslator->_('Relatorio de estacoes por sistema operacional')." (".$oTranslator->_('ID Externa').")";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $titulo;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="<?=CACIC_URL?>/include/cacic.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif">
<table border="0" align="default" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td rowspan="5" bgcolor="#FFFFFF">
       <img src="../imgs/cacic_logo.png" width="50" height="50">
    </td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF"><font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
		<?php echo $titulo;?>
	</strong></font></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
	<?php echo $oTranslator->_('Gerado em') . date("d/m/Y �\s H:i"); ?>
	</font></p></td>
  </tr>
</table>
<br>
<? 
// Obtem ID_SO
$so_selecionado = $_GET['id_so'];

$query = 'SELECT   computadores.te_node_address,
                   computadores.id_so,
                   computadores.te_nome_computador AS "'.$oTranslator->_('Nome do computador') .'",
                   so.te_desc_so AS "'.$oTranslator->_('Sistema operacional') .'",
                   computadores.te_ip AS "'.$oTranslator->_('IP') .'",
                   computadores.dt_hr_ult_acesso AS "'.$oTranslator->_('Ultimo acesso') .'",
                   locais.sg_local AS "'.$oTranslator->_('Local') .'",
                   computadores.te_versao_cacic AS "'.$oTranslator->_('Agente principal') .'",                   computadores.te_versao_cacic AS "'.$oTranslator->_('Gerente de coletas') .'"          FROM     computadores
                   LEFT JOIN so ON ( computadores.id_so = so.id_so )
                   LEFT JOIN redes ON ( computadores.id_ip_rede = redes.id_ip_rede )
                   LEFT JOIN locais ON ( redes.id_local=locais.id_local )
          WHERE    computadores.id_so='.$so_selecionado.' 
          ORDER BY te_nome_computador';

// *****************************************************
// C�digo para Pagina��o - Anderson Peterle - 24/06/2008
// *****************************************************

// definicoes de variaveis
$sql = "select nu_rel_maxlinhas from configuracoes_padrao";
$db_result = mysql_query($sql);
$cfgStdData = mysql_fetch_assoc($db_result);

$max_links 	= 100; // m�ximo de links � serem exibidos
$max_res 	= ($cfgStdData['nu_rel_maxlinhas'])?$cfgStdData['nu_rel_maxlinhas']:100; // m�ximo de resultados � serem exibidos por tela ou pagina
$mult_pag 	= new Mult_Pag($max_res); // cria um novo objeto navbar

// metodo que realiza a pesquisa
$resultado = $mult_pag->executar($query, $DbConnect, "", "mysql");
$reg_pag = mysql_num_rows($resultado); // total de registros por paginas ou telas

echo '<table cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
	 <tr bgcolor="#E1E1E1" >
	  <th nowrap align="left">&nbsp;</th>';
$num_fields =  mysql_num_fields($resultado);
for ($i=2; $i < $num_fields; $i++) 
	{
	echo '<th nowrap align="left">'. mysql_field_name($resultado, $i) .'</th>';
	}
echo '</tr>';

$cor = 0;
$num_registro = 1 + ($max_res * $pagina);

// visualizacao do conteudo
for ($n = 0; $n < $reg_pag; $n++) 
	{
  	$linha  = mysql_fetch_object($resultado); // retorna o resultado da pesquisa linha por linha em um array
	
	$strFieldTeNodeAddress    = mysql_field_name($resultado, 0);
	$strFieldIdSo		  = mysql_field_name($resultado, 1);
	$strFieldTeNomeComputador = mysql_field_name($resultado, 2);
		
	//Table body
	echo '<tr ';
	if ($cor) 
		echo 'bgcolor="#E1E1E1"';
			
	echo '>';
    $nomeComputador = $linha->$strFieldTeNomeComputador;
    $nomeComputador = $nomeComputador?$nomeComputador:$oTranslator->_('Nao disponivel');
	echo '<td nowrap align="right"><font size="1" face="Verdana, Arial">' . $num_registro . '</font></td>'; 
	echo "<td nowrap align='left'><font size='1' face='Verdana, Arial'>
           &nbsp;<a href='computador/computador.php?te_node_address=". 
                   $linha->$strFieldTeNodeAddress ."&id_so=". $linha->$strFieldIdSo ."' target='_blank'>" . 
                   $nomeComputador . "
                </a>
          </td>";
 
		for ($i=3; $i < $num_fields; $i++) 
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

// pega todos os links e define que 'Pr�xima' e 'Anterior' ser�o exibidos como texto plano
//$todos_links = $mult_pag->Construir_Links("todos", "sim");
$todos_links = $mult_pag->Construir_Links("strings", "sim");
//echo "<P>Esta � a lista de todos os links paginados</P>\n";

// fun��o que limita a quantidade de links no rodape
$links_limitados = $mult_pag->Mostrar_Parte($todos_links, $coluna, $max_links);

//echo "<P>Esta � a lista dos links limitados</P>\n";
for ($n = 0; $n < count($links_limitados); $n++) {
  echo $links_limitados[$n] . "&nbsp;";
}

?>
<p>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
    <?=$oTranslator->_('Gerado por');?> - 
    <strong>CACIC</strong> - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais
  </font><br>
  <font size="1" face="Verdana, Arial, Helvetica, sans-serif">
      Software desenvolvido pela Dataprev - Unidade Regional Esp&iacute;rito Santo
  </font>
</p>
</body>
</html>
