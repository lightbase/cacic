<?php
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
 // Esse arquivo é um arquivo de include, usado pelo arquivo compuatdor.php. 
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
    <td colspan="3" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;&nbsp;<a href="computador.php?exibir=ferramentas&id_computador=<?php echo $_GET['id_computador']?>"><img src="../../imgs/<?php if($_SESSION['ferramentas'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_('Ferramentas');?></a></td>
  </tr>
  <tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  </tr>
		<?php if ($_SESSION['ferramentas'] == true) { ?>
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
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=ping&ip=<?php echo mysql_result($result, 0, "te_ip_computador"); ?>','','width=550,height=370');return false"><?php echo $oTranslator->_('Analise se ativo na rede');?></a></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><img src="../../imgs/tracert.gif" width="25" height="22"></td>
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=traceroute&ip=<?php echo mysql_result($result, 0, "te_ip_computador"); ?>','','width=550,height=370');return false"><?php echo $oTranslator->_('Analisar rota de rede');?></a></td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td><img src="../../imgs/nmap.gif" width="22" height="18"></td>
          <td class="opcao_tabela"><a href="#" onClick="open_window('../../relatorios/comandos_rede.php?tool=nmap&ip=<?php echo mysql_result($result, 0, "te_ip_computador"); ?>','','width=550,height=370');return false"><?php echo $oTranslator->_('Servicos abertos para a rede');?></a></td>
        </tr>
      </table>
  </tr>
  <?php echo $linha; 
		 } ?> 
		
</table>
