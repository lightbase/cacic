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
// Ao fazer require deste arquivo, observe que o SELECT aseguir deve antecede-lo na pagina destino:
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

$linha = '<tr bgcolor="#e7e7e7"> 
		  <td height="1" colspan="5"></td>
          </tr>';	  
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
	$diference = date_difference(trim(substr($access_day[1],0,2)).'-'.$access_day[2].'-'.$access_day[0],$today);	
	
	if ($diference > 4) // Acima de 5 dias
		$img_date = '<img src="../../imgs/arvore/tree_computer_red.gif" title="�ltimo acesso realizado h� mais de 5 dias (120 horas)" width="16" height="16">';
	else if($diference > 0) // At� 5 dias
		$img_date = '<img src="../../imgs/arvore/tree_computer_yellow.gif" title="�ltimo acesso realizado h� at� 5 dias (120 horas)" width="16" height="16">';
	else // At� 1 dia
		$img_date = '<img src="../../imgs/arvore/tree_computer_green.gif" title="�ltimo acesso realizado h� at� 1 dia (at� 24 horas)" width="16" height="16">';
		
	?>
    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Nome do Computador');?></td>
    <td class="dado" colspan="3"><? echo mysql_result($result, 0, "te_nome_computador");?></td>
	
    </tr>
    <? echo $linha?> 
    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao agente principal');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_versao_cacic"); ?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao gerente de coletas');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_versao_gercols"); ?></td>
    </tr>
    <? echo $linha?> 
	
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Endereco TCP/IP');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_ip"); ?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data/Hora inclusao');?></td>
    <td class="dado"><? echo date("d/m/Y �\s H:i\h", strtotime(mysql_result($result, 0, "dt_hr_inclusao"))); ?></td>
    </tr>
    <? echo $linha?> 
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Sistema operacional');?></td>
    <td class="dado"><? echo mysql_result($result, 0, "te_desc_so"); ?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data/Hora do ultimo acesso');?></td>
    <td class="dado"><? echo date("d/m/Y �\s H:i\h", strtotime(mysql_result($result, 0, "dt_hr_ult_acesso"))). ' ' .$img_date; ?></td>
    </tr>

<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>
</tr>
</table></td>
</tr>
</table>		
