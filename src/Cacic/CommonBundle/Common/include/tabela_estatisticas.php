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
foreach ($_SESSION["list8"] as $v)
	{
	$v_campo_estatistica = str_replace('\\', '', $v);
	$v_campo_estatistica = str_replace(', ', '', $v_campo_estatistica);
	?>
	<br>
	<br>
	<br>
	<table align="center" cellpadding="2" cellspacing="0" border="1" bordercolor="#999999" bordercolordark="#E1E1E1">
	<tr bgcolor="#E1E1E1" align="center">
	<td nowrap align="center">
	  <font size="2" face="Verdana, Arial">
	    <?=$oTranslator->_('Estatisticas por');?> <? echo trim(str_replace('as ', '',substr($v_campo_estatistica,strpos($v_campo_estatistica,' ',1),strlen($v_campo_estatistica)))); ?></font></td></tr>
	<tr>
	<td nowrap align="center">
	<?
	
	$v_nome_campo = substr($v_campo_estatistica,0,strpos($v_campo_estatistica,' ',1));		
	
	$v_label = trim(str_replace('"', '',str_replace('as ', '',substr($v_campo_estatistica,strpos($v_campo_estatistica,' ',1),strlen($v_campo_estatistica)))));			
	
	echo "<img src='../../graficos/pie_" . $v_opcao . ".php?v_query_redes=".str_replace("'", "-=-",$query_redes)."&v_so_selecionados=" . str_replace("'","-=-",$so_selecionados) . "&v_nome_campo=" . $v_nome_campo . "&v_label=" . $v_label."&v_from=" . $v_from."'>";
	?>	
	</td></tr>
	</table>
	<?
	}
?>