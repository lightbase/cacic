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
 // Esse arquivo � um arquivo de include, usado pelo arquivo compuatdor.php. 
if (!$_SESSION['ferramentas'])
	$_SESSION['ferramentas'] = false;
if ($exibir == 'ferramentas')
	$_SESSION['ferramentas'] = !($_SESSION['ferramentas']);
else
	$_SESSION['ferramentas'] = false;
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="3" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;&nbsp;<a href="computador.php?exibir=ferramentas&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"><img src="../../imgs/<? if($_SESSION['ferramentas'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?=$oTranslator->_('Ferramentas');?></a></td>
  </tr>
  <tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  </tr>
		<?	if ($_SESSION['ferramentas'] == true) { ?>
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL,winName,features) { 
    window.open(theURL,winName,features);
}
//-->
</script>		
  <tr> 
    <td colspan="3"><table border="0">
        <tr> 
          <td><img src="../../imgs/ping.gif" width="23" height="23"></td>
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=ping&ip=<? echo mysql_result($result, 0, "te_ip"); ?>','','width=550,height=370');return false"><?=$oTranslator->_('Analise se ativo na rede');?></a></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><img src="../../imgs/tracert.gif" width="25" height="22"></td>
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=traceroute&ip=<? echo mysql_result($result, 0, "te_ip"); ?>','','width=550,height=370');return false"><?=$oTranslator->_('Analisar rota de rede');?></a></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><img src="../../imgs/nmap.gif" width="22" height="18"></td>
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=nmap&ip=<? echo mysql_result($result, 0, "te_ip"); ?>','','width=550,height=370');return false"><?=$oTranslator->_('Servicos abertos para a rede');?></a></td>
        </tr>
      </table>
  </tr>
  <? echo $linha; 
		 } ?> 
		
</table>
