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
if (!$_SESSION['unidades_disco'])
	$_SESSION['unidades_disco'] = false;
if ($exibir == 'unidades_disco')
	$_SESSION['unidades_disco'] = !($_SESSION['unidades_disco']);
else
	$_SESSION['unidades_disco'] = false;

?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=unidades_disco&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['unidades_disco'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> 
      <?=$oTranslator->_('Unidades de discos');?></a></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <?
		if ($_SESSION['unidades_disco'] == true) {
		// EXIBIR INFORMA��ES DAS UNIDADES DE DISCO DO COMPUTADOR
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_unid_disc' AND
					  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
							
			$result_acoes =  mysql_query($query);
			if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
    			$query = "SELECT 	* 
						  FROM 		unidades_disco
					  	  WHERE 	te_node_address = '". $_GET['te_node_address'] ."' AND 
					  				id_so = '". $_GET['id_so'] ."'";
			$result_disco = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('unidades_disco')));
			if(mysql_num_rows($result_disco) > 0) {
				echo '<tr><td><br> 
					  <table border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#999999" bordercolordark="#E1E1E1">
						<tr bgcolor="'.$strPreenchimentoPadrao.'">						 
						  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Rotulo').'</div></td>';
				echo '<td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Sistema de arquivos').'</div></td>
						  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Numero serial').'</div></td>
						  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Tamanho (MB)').'</div></td>
						  <td nowrap colspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Espaco (MB)').'</div></td>
						  <td nowrap colspan="2" rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Utilizacao (%)').'</div></td>
						</tr>
						<tr bgcolor="'.$strPreenchimentoPadrao.'">
						  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Utilizado').'</div></td>
						  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Livre').'</div></td>
						</tr>';
				
				while($row = mysql_fetch_assoc($result_disco)) {
						$espaco_utilizado = '';
					 $percent_espaco_utilizado = '';
					 if ($row['nu_capacidade'] > 0) {	
						 $espaco_utilizado = $row['nu_capacidade'] - $row['nu_espaco_livre'];
						 $percent_espaco_utilizado = round(($espaco_utilizado / $row['nu_capacidade']) * 100); 
						 $grafico = '<table width="100" border="0" cellspacing="0" cellpadding="0">
										<tr>
										  <td width="'. $percent_espaco_utilizado .'" height="10" bgcolor="#0066FF"><font size="1">&nbsp;</font></td>
										  <td height="10" bgcolor="#FF0099"><font size="1">&nbsp;</font></td>
										</tr>
									 </table>';
					 }
						
					 else
					     $grafico = '&nbsp;';
					 
					 $usado = $percent_espaco_utilizado;
					 $img = "../../imgs/grad.png";	
					 									
					 echo '<tr>						  
							<td nowrap align="center" class="opcao_tabela">'. $row['te_letra'] .'</td>';
							echo '<td nowrap align="center" class="opcao_tabela">&nbsp;'. $row['cs_sist_arq'] .'</td>
							<td nowrap align="center" class="opcao_tabela">&nbsp;'. $row['nu_serial'] .'</td>
							<td nowrap align="center" class="opcao_tabela">&nbsp;'. $row['nu_capacidade'] .'</td>
							<td nowrap align="center" class="opcao_tabela">&nbsp;'. $espaco_utilizado .'</td>
							<td nowrap align="center" class="opcao_tabela">&nbsp;'. $row['nu_espaco_livre'] .'</td>
							<td nowrap class="opcao_tabela"><img src="inc_unidades_disco_ocupacao.php?usado='.($percent_espaco_utilizado).'&img=../../imgs/grad.png&livre='.$oTranslator->_('% livre').'&ocupado='.$oTranslator->_('% ocupado').'" /></td>
						   </tr>';
				}
				echo '</table></td></tr>';
			 ?>
  <tr> 
    <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font><font size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;</font></td>
  </tr>
  <?
			}
			else {
				echo '<tr><td> 
						<p>
						<div align="center">
						<br>
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						'.$oTranslator->_('Nao existem unidades de disco nesta maquina').'
						</font></div>
						</p>
					  </td></tr>';
						}	
			}
					else {
									echo '<tr><td> 
											<div align="center">
											<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
											'.$oTranslator->_('O modulo de coleta de informacoes das Unidades de Disco nao foi habilitado pelo Administrador').'
											</font></div>
												</td></tr>';
								}
		}
		// FIM DA EXIBI��O DE INFORMA��ES DAS UNIDADES DE DISCO DO COMPUTADOR
		?>
</table>

