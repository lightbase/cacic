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