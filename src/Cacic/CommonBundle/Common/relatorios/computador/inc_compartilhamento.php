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
if (!$_SESSION['compartilhamento'])
	$_SESSION['compartilhamento'] = false;
if ($exibir == 'compartilhamento')
	$_SESSION['compartilhamento'] = !($_SESSION['compartilhamento']);
else
	$_SESSION['compartilhamento'] = false;
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=compartilhamento&id_computador=<?php echo $_GET['id_computador']?>"> 
      <img src="../../imgs/<?php if($_SESSION['compartilhamento'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> 
      <?php echo $oTranslator->_('Compartilhamentos de diretorios e impressoras');?></a></td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <?php if ($_SESSION['compartilhamento'] == true) {

				$query = "SELECT 	cs_situacao
						  FROM 		acoes_redes
						  WHERE 	id_acao = 'cs_coleta_compart' AND
						  			id_rede = '".mysql_result($result,0,'id_rede')."'";
					$result_acoes =  mysql_query($query);
				
				if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
	
        		// EXIBIR INFORMAÇÕES DOS COMPARTILHAMENTOS DO COMPUTADOR
									$query = "SELECT 	* 
											  FROM 		compartilhamentos
											  WHERE 	id_computador = ". $_GET['id_computador'];
									$result_compartilhamento = mysql_query($query) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('compartilhamentos')));
									if(mysql_num_rows($result_compartilhamento) > 0) {
										echo '<tr><td><br> 
													<table width="98%" border="1" align="center" cellpadding="1" cellspacing="0" bordercolor="#999999" bordercolordark="#E1E1E1">
													<tr bgcolor="#FFFFCC">';
													
										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {
											echo '<td nowrap rowspan="2" class="opcao_tabela"><div align="center">&nbsp;</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Nome').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Diretorio').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Comentario').'</div></td>
												  <td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Tipo').'</div></td>';
										}
										else {
											echo '<td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Nome').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Diretorio').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Comentario').'</div></td>
												  <td nowrap class="opcao_tabela"><div align="center">'.$oTranslator->_('Tipo').'</div></td>';
										}
													
										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {
											echo '	<td nowrap rowspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Permissao').'</div></td>
													<td nowrap colspan="2" class="opcao_tabela"><div align="center">'.$oTranslator->_('Senha').'</div></td>
													</tr>
													<tr bgcolor="#E1E1E1">
													<td nowrap  bgcolor="#FFFFCC" class="opcao_tabela"><div align="center">'.$oTranslator->_('Leitura').'</div></td>
													<td nowrap  bgcolor="#FFFFCC" class="opcao_tabela"><div align="center">'.$oTranslator->_('Gravacao').'</div></td>
													</tr>';
										}
										else						
											echo '</tr>'; 
										if( mysql_result($result_compartilhamento, 0, "id_so") <= 5 ) {	              
											$result_compartilhamento = mysql_query($query);																				
											while($row = mysql_fetch_assoc($result_compartilhamento)) {
													$img_alerta == '&nbsp;';
													if ($row['cs_tipo_compart'] == 'D')
													$tipo_compart = '<img src="../../imgs/compart_dir.gif" title="'.$oTranslator->_('Compartilhamento de diretorio').'">';
													else
													$tipo_compart = '<img src="../../imgs/compart_print.gif" title="'.$oTranslator->_('Compartilhamento de impressora').'">';
													
													if( $row['in_senha_leitura'] == 1 )
														$senha_leitura = '<img src="../../imgs/checked.gif">';
													else {
														$senha_leitura = '<img src="../../imgs/unchecked.gif">';
													$img_alerta = '<img src="../../imgs/alerta_amarelo.gif" title="'.$oTranslator->_('Risco Medio: Privacidade').'" width="8" height="8">';
													}
													
													if( $row['in_senha_escrita'] == 1 )
														$senha_escrita = '<img src="../../imgs/checked.gif">';
													else {
														$senha_escrita = '<img src="../../imgs/unchecked.gif">';
													$img_alerta = '<img src="../../imgs/alerta_vermelho.gif" title="'.$oTranslator->_('Risco Alto: Integridade e Privacidade').'" width="8" height="8">';
													}
													
													if( $row['cs_tipo_permissao'] == 'L' )
														$tipo_permissao = $oTranslator->_('Leitura');
													if( $row['cs_tipo_permissao'] == 'G' )
														$tipo_permissao = $oTranslator->_('Gravacao');
													if( $row['cs_tipo_permissao'] == 'D' )
														$tipo_permissao = $oTranslator->_('Depende de senha');
													
													echo '<tr>						  
														<td nowrap align="center" class="opcao_tabela">'. $img_alerta .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. $row['nm_compartilhamento'] .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. quebra_linha(strtolower($row['nm_dir_compart']), 32) .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. capa_string($row['te_comentario'], 28) .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $tipo_compart .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. $tipo_permissao .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $senha_leitura .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $senha_escrita .'</td>
																</tr>';
											}
										}
										else {
											$result_compartilhamento = mysql_query($query);										
											while($row = mysql_fetch_assoc($result_compartilhamento)) {
													if ($row['cs_tipo_compart'] == 'D')
													$tipo_compart = '<img src="../../imgs/compart_dir.gif" title="'.$oTranslator->_('Compartilhamento de diretorio').'">';
													else
													$tipo_compart = '<img src="../../imgs/compart_print.gif" title="'.$oTranslator->_('Compartilhamento de impressora').'">';
													
													echo '<tr>						  
														<td nowrap class="dado">&nbsp;'. $row['nm_compartilhamento'] .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. quebra_linha(strtolower($row['nm_dir_compart']), 68) .'</td>
														<td nowrap class="opcao_tabela">&nbsp;'. capa_string($row['te_comentario'], 28) .'</td>
														<td nowrap align="center" class="opcao_tabela">'. $tipo_compart .'</td>
																</tr>';
											}
										}
										echo '</table></td></tr>';
										?>
  <tr> 
    <td>&nbsp;&nbsp;</td>
  </tr>
  <?php
									}
									else {
										echo '<tr><td> 
												<p>
												<div align="center">
												<br>
												<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
												'.$oTranslator->_('Nao existem compartilhamentos nesta maquina').'
												</font></div>
												</p>
													</td></tr>';
									}
								}
								else {
									echo '<tr><td> 
											<div align="center">
											<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
											'.$oTranslator->_('Modulo de coleta de Compartilhamentos de Diretorios e impressoras nao habilitado pelo Administrador').'
											</font></div>
												</td></tr>';
								}
								// FIM DA EXIBIÇÃO DE INFORMAÇÕES DOS COMPARTILHAMENTOS DO COMPUTADOR
					}
		?>
</table>
