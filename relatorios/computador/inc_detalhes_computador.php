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
<td bgcolor="#FFFFFF" class="cabecalho"> <div align="center"><b><br>&nbsp; Detalhes do computador 
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
<td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp; Informa&ccedil;&otilde;es B&aacute;sicas</td>
</tr>
<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>
</tr>
	<?
	$today=date('m-d-Y');	
	$access_day = explode('-',mysql_result($result, 0, "dt_hr_ult_acesso"));	
	$diference = date_diff(trim(substr($access_day[1],0,2)).'-'.$access_day[2].'-'.$access_day[0],$today);	
	
	if ($diference > 4) // Acima de 5 dias
		$img_date = '<img src="../../imgs/arvore/tree_computer_red.gif" title="Último acesso realizado há mais de 5 dias (120 horas)" width="16" height="16">';
	else if($diference > 0) // Até 5 dias
		$img_date = '<img src="../../imgs/arvore/tree_computer_yellow.gif" title="Último acesso realizado há até 5 dias (120 horas)" width="16" height="16">';
	else // Até 1 dia
		$img_date = '<img src="../../imgs/arvore/tree_computer_green.gif" title="Último acesso realizado há até 1 dia (até 24 horas)" width="16" height="16">';
		
	?>
    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Nome do Computador:</td>
    <td class="dado" colspan="3"><? echo mysql_result($result, 0, "te_nome_computador");?></td>
	
    </tr>
    <? echo $linha?> 
    <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Vers&atilde;o Agente Principal do CACIC:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_versao_cacic"); ?></td>
    <td class="opcao_tabela">Vers&atilde;o Gerente de Coletas do CACIC:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_versao_gercols"); ?></td>
    </tr>
    <? echo $linha?> 
	
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela">Endere&ccedil;o TCP/IP:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_ip"); ?></td>
    <td class="opcao_tabela">Data/Hora Inclus&atilde;o:</td>
    <td class="dado"><? echo date("d/m/Y à\s H:i\h", strtotime(mysql_result($result, 0, "dt_hr_inclusao"))); ?></td>
    </tr>
    <? echo $linha?> 
    <tr> 
    <td><p>&nbsp;</p></td>
    <td class="opcao_tabela">Sistema Operacional:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_desc_so"); ?></td>
    <td class="opcao_tabela">Data/Hora &Uacute;ltimo Acesso:</td>
    <td class="dado"><? echo date("d/m/Y à\s H:i\h", strtotime(mysql_result($result, 0, "dt_hr_ult_acesso"))). ' ' .$img_date; ?></td>
    </tr>

<tr> 
<td colspan="5" height="1" bgcolor="#333333"></td>
</tr>
</table></td>
</tr>
</table>		