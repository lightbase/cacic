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
if (!$_SESSION['officescan'])
	$_SESSION['officescan'] = false;
if ($exibir == 'officescan')
	$_SESSION['officescan'] = !($_SESSION['officescan']);
else
	$_SESSION['officescan'] = false;

$strCor = '';  
$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" bgcolor="#333333" height="1"></td>
  </tr>
  <tr> 
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp; <a href="computador.php?exibir=officescan&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['officescan'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">
   			 <?=$oTranslator->_('Antivirus officeScan');?></a></td>
  </tr>
  <tr> 
    <td colspan="5" bgcolor="#333333" height="1"></td>
  </tr>
  
  <?

		if ($_SESSION['officescan'] == true) 
			{
			// EXIBIR INFORMA��ES DO OFFICESCAN DO COMPUTADOR
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_officescan' AND
					  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
							
			$result_acoes =  mysql_query($query);
			if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
						$query = "SELECT 	* 
								  FROM 		officescan 
								  WHERE 	te_node_address = '". $_GET['te_node_address'] ."' AND 
											id_so = '". $_GET['id_so'] ."'";
						$result_officescan = mysql_query($query);
						if(mysql_num_rows($result_officescan) > 0) {
		?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao do engine');?></td>
    <td class="dado"><? echo mysql_result($result_officescan, 0, "nu_versao_engine"); ?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Servidor do OfficeScan');?></td>
    <td class="dado"><div align="left"><? echo mysql_result($result_officescan, 0, "te_servidor"); ?>&nbsp;</div></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Versao do Pattern');?></td>
    <td class="dado"><? echo mysql_result($result_officescan, 0, "nu_versao_pattern"); ?></td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data de instalacao');?></td>
    <td class="dado"><? echo date("d/m/Y �\s H:i\h", strtotime(mysql_result($result_officescan, 0, "dt_hr_instalacao"))); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Estado do OfficeScan');?></td>
    <td class="dado"> 
      <? if (mysql_result($result_officescan, 0, "in_ativo") == 1)
					echo $oTranslator->_('Ativo'); 
				 else
					echo $oTranslator->_('Desativado');
		    ?>
      </td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data da ultima coleta');?></td>
    <td class="dado"><? echo date("d/m/Y �\s H:i\h", strtotime(mysql_result($result_officescan, 0, "dt_hr_coleta"))); ?></td>
  </tr>
  <?
			}
			else {
				echo '<tr><td> 
						<p>
						<div align="center">
						<br>
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						'.$oTranslator->_('Nao foram coletadas informacoes do OfficeScan referente a esta maquina').'
						</font></div>
						</p>
					  </td></tr>';
			}
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						'.$oTranslator->_('O modulo de coleta de informacoes do Antivirus OfficeScan nao foi habilitado pelo Administrador').'
						</font></div>
					  </td></tr>';
			}
			
		}
		// FIM DA EXIBI��O DE INFORMA��ES DO OFFICESCAN DO COMPUTADOR
		?>
		
</table>
