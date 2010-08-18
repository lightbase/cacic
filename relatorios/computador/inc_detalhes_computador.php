
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

// Ao fazer require deste arquivo, observe que o SELECT a seguir deve antecede-lo na pagina destino:

// SELECT 	dt_hr_ult_acesso, 
//			te_nome_computador, 
//			te_versao_cacic,
//			te_ip,
//			dt_hr_inclusao, 
//			te_desc_so, 
//			dt_hr_ult_acesso 
// FROM		computadores
// WHERE	te_node_address=xx-xx-xx-xx-xx and
//			id_so= yy

## Selects para exibir resumo das configurações do computador - por Stevenes Donato
##Hardware

$query_hard = "SELECT te_placa_mae_desc, qt_mem_ram 
	FROM computadores
	WHERE     te_node_address = '". $_GET['te_node_address'] ."' AND computadores.id_so = ". $_GET['id_so'];

$result_hard = mysql_query($query_hard);

#fim hardware


##CPU

$query_cpu = "SELECT DISTINCT te_valor
		FROM componentes_estacoes
		WHERE cs_tipo_componente = 'CPU' AND te_node_address = '". $_GET['te_node_address'] ."'";

$result_cpu = mysql_query($query_cpu);

$cpu = explode('#FIELD#',mysql_result($result_cpu, 0, "te_valor"));

$cpu2 = explode('#FIELD#', $cpu);

#fim CPU

##Patrimonio

$query_pat = "SELECT DISTINCT `te_info_patrimonio1` 
	     FROM `patrimonio`
	     WHERE  te_node_address = '". $_GET['te_node_address'] ."'";

$result_pat = mysql_query($query_pat);

#fim patrimonio


##local
$query_local = "SELECT `te_localizacao_complementar` 
	      FROM `patrimonio`
   	     WHERE  te_node_address = '". $_GET['te_node_address'] ."'";

$result_local = mysql_query($query_local);

# fim local

## Total soft

$query_soft =  "SELECT id_software_inventariado 
		FROM softwares_inventariados_estacoes
		WHERE te_node_address = '". $_GET['te_node_address'] ."'";

$result_soft = mysql_query($query_soft);

$TotalSoft = mysql_num_rows($result_soft);

# fim total software

## Total compartilhamentos

$query_compart = "SELECT `nm_compartilhamento` 
		FROM `compartilhamentos`
		WHERE te_node_address = '". $_GET['te_node_address'] ."'";

$result_compart = mysql_query($query_compart);

$TotalCompart = mysql_num_rows($result_compart);

# fim total compartilhamentos

## Total discos

$query_discos = "SELECT `te_letra` FROM `unidades_disco`
		WHERE te_node_address = '". $_GET['te_node_address'] ."'";

$result_discos = mysql_query($query_discos);

$TotalDiscos = mysql_num_rows($result_discos);

$query_capac = "SELECT SUM(nu_capacidade) as CAPAC 
FROM unidades_disco 
WHERE te_node_address = '". $_GET['te_node_address'] ."'";

$result_capac = mysql_query($query_capac);
$capacidade = mysql_result($result_capac,"CAPAC") / 1000;

$query_livre = "SELECT SUM(nu_espaco_livre) as LIVRE
FROM unidades_disco 
WHERE te_node_address = '". $_GET['te_node_address'] ."'";

$result_livre = mysql_query($query_livre);
$livre = mysql_result($result_livre,"LIVRE") / 1000;

# fim total discos

## FIM Selects para exibir resumo das configurações do computador - por Stevenes Donato


$linha = '<tr bgcolor="#e7e7e7">  <td height="1" colspan="5"></td>    </tr>';	  

?>

<table width="102%" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#999999">
<tr bgcolor="#E1E1E1"> 
<td bgcolor="#FFFFFF" class="cabecalho"> <div align="center"><b><br>

<?=$oTranslator->_('Detalhes do Computador');?> 

<? echo mysql_result($result, 0, "te_nome_computador");  ?>

</b></div></td>
</tr>
</table>

<br>

<table width="100%" border="0" cellpadding="0" cellspacing="2">
<tr> 
<td><table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
<td colspan="5"> </td>
</tr>

<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>
</tr>
<tr> 
<td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<?=$oTranslator->_('Informacoes basicas');?></td>
</tr>
<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>

</tr>

	<?

	$today=date('m-d-Y');	

	$access_day = explode('-',mysql_result($result, 0, "dt_hr_ult_acesso"));	

	$diference = data_diff(trim(substr($access_day[1],0,2)).'-'.$access_day[2].'-'.$access_day[0],$today);	

	

	if ($diference > 4) // Acima de 5 dias

		$img_date = '<img src="../../imgs/arvore/tree_computer_red.gif" title="Último acesso realizado há mais de 5 dias (120 horas)" width="16" height="16">';

	else if($diference > 0) // Até 5 dias

		$img_date = '<img src="../../imgs/arvore/tree_computer_yellow.gif" title="Último acesso realizado há até 5 dias (120 horas)" width="16" height="16">';

	else // Até 1 dia

		$img_date = '<img src="../../imgs/arvore/tree_computer_green.gif" title="Último acesso realizado há até 1 dia (até 24 horas)" width="16" height="16">';	?>

    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Nome do Computador');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_nome_computador");?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Total de Softwares');?></td>
    <td class="dado"><a href="computador.php?exibir=software_inventariado&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo "$TotalSoft" ?></a></td>
    </tr>

    <? echo $linha?> 

    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao agente principal');?></td>
    <td class="dado"><a href="computador.php?exibir=software&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result, 0, "te_versao_cacic"); ?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao gerente de coletas');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_versao_gercols"); ?></td>
    </tr>

    <? echo $linha?> 
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Endereco TCP/IP');?></td>
    <td class="dado"><a href="computador.php?exibir=tcp_ip&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result, 0, "te_ip"); ?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data/Hora inclusao');?></td>
    <td class="dado"><? echo date("d/m/Y - H:i\h", strtotime(mysql_result($result, 0, "dt_hr_inclusao"))); ?></td>
    </tr>

    <? echo $linha?> 
    <tr> 
   <td><p>&nbsp;</p></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Sistema operacional');?></td>
    <td class="dado"><a href="computador.php?exibir=software&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result, 0, "te_desc_so"); ?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data/Hora do ultimo acesso');?></td>
    <td class="dado"><? echo date("d/m/Y - H:i\h", strtotime(mysql_result($result, 0, "dt_hr_ult_acesso"))). ' ' .$img_date; ?></td>
    </tr>

    <? echo $linha?> 

    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Placa-m&atilde;e');?></td>
    <td class="dado"><a href="computador.php?exibir=hardware&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result_hard, 0, "te_placa_mae_desc");?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Compartilhamentos');?></td>
    <td class="dado"><a href="computador.php?exibir=compartilhamento&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo "$TotalCompart" ?></a></td>

    </tr>

    <? echo $linha?> 
    <tr> 
    <td>&nbsp;</td>
     <td class="opcao_tabela"><?=$oTranslator->_('Memoria RAM');?></td>
    <td class="dado"><a href="computador.php?exibir=hardware&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result_hard, 0, "qt_mem_ram");?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Unidades de Disco');?></td>
    <td class="dado"><a href="computador.php?exibir=unidades_disco&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo "$TotalDiscos ,  Capacidade: $capacidade GB , Livre: $livre GB" ?></a></td>
    </tr>

    <? echo $linha?> 
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Patrim&ocirc;nio da CPU');?></td>
    <td class="dado"><a href="computador.php?exibir=patrimonio&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result_pat, 0, "te_info_patrimonio1"); ?></a></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Localiza&ccedil;&atilde;o');?></td>
    <td class="dado"><a href="computador.php?exibir=tcp_ip&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><? echo mysql_result($result_local, 0, "te_localizacao_complementar");?></a></td>

<? echo $linha?>

<tr>

<td><p>&nbsp;</p></td>

 <td class="opcao_tabela"><?=$oTranslator->_('Processador');?>

<!--    <td class="dado"><? echo $cpu; ?></td> -->
<? 	
	// Obtenho os nomes dos hardwares passíveis de controle
	$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
	$strQueryTotalizaGeralExistentes = ' SELECT DISTINCT 	cs_tipo_componente,te_valor
						FROM	 	componentes_estacoes
						 WHERE   	cs_tipo_componente = "CPU" AND te_node_address = "'.mysql_result($result, 0, "te_node_address") . '" AND id_so='  . mysql_result($result, 0, "id_so").'										 						ORDER BY 	cs_tipo_componente,te_valor';

	$resultTotalizaGeralExistentes   = mysql_query($strQueryTotalizaGeralExistentes) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('componentes_estacoes')));

	$strComponenteAtual = '';
	$intSequencial      = 0;
  	while ($rowTotalizaGeralExistentes = mysql_fetch_array($resultTotalizaGeralExistentes))

  		{
		if ($strComponenteAtual <> $rowTotalizaGeralExistentes['cs_tipo_componente'])

			{
			$strComponenteAtual = $rowTotalizaGeralExistentes['cs_tipo_componente'];
			$intSequencial = 1;
			}
		else
			$intSequencial ++;

		$arrColunasValores = explode('#FIELD#',$rowTotalizaGeralExistentes['te_valor']);

		for ($i=0; $i<count($arrColunasValores);$i++)

			{
			$arrColunas = explode('###',$arrColunasValores[$i]);	
		  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  			
			?>	
			<tr bgcolor="<? echo $strCor;?>"> 
			<td>&nbsp;</td>
			<?
			if ($i > 0)
				{
				?>
				<td>&nbsp;</td>				
				<?
				}
			?>
			<td class="opcao_tabela"><? echo $arrDescricoesColunasComputadores[$arrColunas[0]].($i==0?' '.$intSequencial:':');?></td>
			<?
			if ($i == 0)
				{
				?>
				<td class="opcao_tabela"><?=$oTranslator->_('Descricao');?></td>				
				<?
				}
			?>
			<td class="dado" colspan="3"><? echo $arrColunas[1]; ?></td>
			</tr>
			<?
			}

		echo $linha;			
		}
  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  		
  	?> 
  	<tr>  </td>
    </tr>
<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>
</tr>
</table></td>
</tr>
</table>		
