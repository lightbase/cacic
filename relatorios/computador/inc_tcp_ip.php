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
 // Esse arquivo é um arquivo de include, usado pelo arquivo computador.php.

if (!$_SESSION['tcp_ip'])
	$_SESSION['tcp_ip'] = false;
if ($exibir == 'tcp_ip')
	{
	$_SESSION['tcp_ip'] = !($_SESSION['tcp_ip']);
	}
else
	{
	$_SESSION['tcp_ip'] = false;
	}

$strCor = '';  
$strCor = ($strCor==''?'#CCCCFF':'');						  
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=tcp_ip&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['tcp_ip'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Protocolo 
      TCP/IP</a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?
		if ($_SESSION['tcp_ip'] == true) {
		// EXIBIR INFORMAÇÕES TCP/IP DO COMPUTADOR
		?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Nome do Host:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_nome_host"); ?></td>
    <td class="opcao_tabela">Serv. Wins Prim&aacute;rio:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_wins_primario"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Dom&iacute;nio DNS:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_dominio_dns"); ?></td>
    <td class="opcao_tabela">Serv. Wins Secundario:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_wins_secundario"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Endere&ccedil;o TCP/IP:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_ip"); ?></td>
    <td class="opcao_tabela">Serv. DNS Prim&aacute;rio:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_dns_primario"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Mascara de Rede:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_mascara"); ?></td>
    <td class="opcao_tabela">Serv. DNS Secundario:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_dns_secundario"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Endere&ccedil;o de Rede:</td>
    <td class="dado"><? echo mysql_result($result, 0, "id_ip_rede"); ?></td>
    <td class="opcao_tabela">Servidor DHCP:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_serv_dhcp");?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?'#CCCCFF':'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Gateway:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_gateway"); ?></td>
    <td class="opcao_tabela">&Uacute;ltimo Login:</td>   
    <td class="dado"><? echo mysql_result($result, 0, "te_dominio_windows");?></tr>
  <? echo $linha;  ?> 

  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center">&nbsp;<br>
          <input name=historico_rede type=submit id=historico_rede value="Hist&oacute;rico de Altera&ccedil;&otilde;es na Configura&ccedil;&atilde;o de Rede">
          <br>
          &nbsp; 
          <input name="te_node_address" type="hidden" id="te_node_address" value="<? echo mysql_result($result, 0, "te_node_address");?>">
          <input name="id_so" type="hidden" id="id_so" value="<? echo mysql_result($result, 0, "id_so");?>">
        </div>
      </form></td>
  </tr>
  <?
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES TCP/IP DO COMPUTADOR
		?>
</table>
