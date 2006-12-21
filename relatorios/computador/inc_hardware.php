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
// Esse arquivo é um arquivo de include, usado pelo arquivo compuatdor.php. 
if (!$_SESSION['hardware'])
	$_SESSION['hardware'] = false;
if ($exibir == 'hardware')
	{
	$_SESSION['hardware'] = !($_SESSION['hardware']);
	}
else
	{
	$_SESSION['hardware'] = false;
	}
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="3" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=hardware&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['hardware'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Hardware Instalado</a></td>
  </tr>
  <tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  </tr>
  <?
		if ( $_SESSION['hardware'] == true) {
		// EXIBIR INFORMAÇÕES DE HARDWARE DO COMPUTADOR
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_hardware' AND
					  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
			$result_acoes =  mysql_query($query);
			if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
		?>
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Descri&ccedil;&atilde;o da CPU:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_cpu_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Freq&uuml;&ecirc;ncia da CPU:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_cpu_freq").' Mhz'; ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Fabricante da CPU:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_cpu_fabricante"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">N&ordm; Serial da CPU:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_cpu_serial"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Placa M&atilde;e:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_mae_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Fabricante da Placa M&atilde;e:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_mae_fabricante"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Placa de V&iacute;deo:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_video_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Quant. Cores placa de V&iacute;deo:</td>
    <td class="dado"><? echo mysql_result($result, 0, "qt_placa_video_cores"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Resolu&ccedil;&atilde;o da Placa de V&iacute;deo:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_video_resolucao"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Mem. da Placa de V&iacute;deo:</td>
    <td class="dado"><? echo mysql_result($result, 0, "qt_placa_video_mem").' MB'; ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Mem&oacute;ria RAM:</td>
    <td class="dado"><? echo mysql_result($result, 0, 'qt_mem_ram').' MB'; ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Detalhes da Mem&oacute;ria RAM:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_mem_ram_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Descri&ccedil;&atilde;o da BIOS:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_bios_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Fabricante da BIOS:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_bios_fabricante"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Placa de Rede:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_rede_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Node Address Placa de Rede:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_node_address") . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(Método de obtenção: ' . mysql_result($result, 0, "te_origem_mac"); ?>)</td>
  </tr>
  
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Placa de Som:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_placa_som_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Modem:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_modem_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">CDROM:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_cdrom_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Teclado:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_teclado_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">Mouse:</td>
    <td class="dado"><? echo mysql_result($result, 0, "te_mouse_desc"); ?></td>
  </tr>
  <? echo $linha?> 
  <tr> 
    <td>&nbsp;</td>
    <td colspan="2"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center"><br>
          <input name=historico_hardware type=submit id=historico_hardware value="Hist&oacute;rico de Altera&ccedil;&otilde;es na Configura&ccedil;&atilde;o de Hardware" >
          <br>
          &nbsp; 
          <input name="te_node_address" type="hidden" id="te_node_address" value="<? echo mysql_result($result, 0, "te_node_address");?>">
          <input name="id_so" type="hidden" id="id_so" value="<? echo mysql_result($result, 0, "id_so");?>">
        </div>
      </form></td>
  </tr>
  <?		
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						O módulo de Coleta de Informações de Hardware não foi habilitado pelo Administrador do CACIC.
						</font></div>
					  </td></tr>';
			}
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE HARDWARE DO COMPUTADOR
		?>
</table>
