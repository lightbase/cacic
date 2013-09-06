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
?>		
<table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">  
<?
		$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' WHERE redes.id_local = '.$_SESSION['id_local']:'WHERE 1=1 ');

		if ($_SESSION['te_locais_secundarios'] <> '' && $where <> '')
			{
			// Faço uma inserção de "(" para ajuste da lógica para consulta
			$where = str_replace(' WHERE redes.id_local = ',' WHERE (redes.id_local = ',$where);
			$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
			}

		$queryRedesDisponiveis = "SELECT 	distinct redes.id_ip_rede, 
											redes.nm_rede,
											redes.id_local 
								  FROM 		redes ".
								  			$where ."
								  ORDER BY  nm_rede";
		$resultRedesDisponiveis = mysql_query($queryRedesDisponiveis) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('redes')));	
		
		if ($boolDetalhes)
			{
			$queryRedesSelecionadas = "SELECT 	redes.id_local, 
												redes.id_ip_rede,
												redes.nm_rede
									  FROM 		redes,
									  			aplicativos_redes AR ".
												$where ." AND
									  			redes.id_local = AR.id_local AND
												redes.id_ip_rede = AR.id_ip_rede AND
												AR.id_aplicativo = ".$_GET['id_aplicativo']."
									  ORDER BY  nm_rede";
			$resultRedesSelecionadas = mysql_query($queryRedesSelecionadas) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('redes')));	

			$strTripaRedesSelecionadas = '';
			$redesDisponiveis  = '';
			$redesSelecionadas = '';		

				$strTripaRedesSelecionadas .= '1000_1000#';			
			   	$redesSelecionadas .= '<option value="1000_1000">1000 - Rede de Testes</option>';						
				
			/* Monto uma tripa com as redes selecionadas */
			while($campos=mysql_fetch_array($resultRedesSelecionadas)) 	
				{
				$strTripaRedesSelecionadas .= $campos['id_local'].'_'.$campos['id_ip_rede'].'#';			
			   	$redesSelecionadas .= '<option value="' . $campos['id_local'].'_'.$campos['id_ip_rede']. '">' . $campos['id_ip_rede'] . ' - ' . capa_string($campos['nm_rede'], 35) . '</option>';						
				}
			
			$strTripaRedesSelecionadas = '#' . $strTripaRedesSelecionadas;
		
			$msg = $oTranslator->_('(OBS: Estao sendo exibidas somente as redes selecionadas pelo administrador.)');
			}
		
		/* Agora monto os itens dos combos de redes disponíveis e selecionadas. */ 
		while($campos=mysql_fetch_array($resultRedesDisponiveis)) 	
			{
			$strRedeDisponivel = '#'.$campos['id_local'].'_'.$campos['id_ip_rede'].'#';
			$intPos = stripos2($strTripaRedesSelecionadas,$strRedeDisponivel);
			if ($intPos === false)
			   	$redesDisponiveis  .= '<option value="' . $campos['id_local'].'_'.$campos['id_ip_rede']. '">' . $campos['id_ip_rede'] . ' - ' . capa_string($campos['nm_rede'], 35) . '</option>';
			}  
			
		?>
             Selecione as redes: </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td><table border="0" cellpadding="0" cellspacing="0">
			
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left"><?php echo $oTranslator->_('Disponiveis:');?></div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela"><?php echo $oTranslator->_('Selecionadas:');?><br></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple size="10" name="list1[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? echo $redesDisponiveis; ?> 
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list1[]'],this.form.elements['list2[]'])" name="B1">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list2[]'],this.form.elements['list1[]'])" name="B2">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple size="10" name="list2[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? echo $redesSelecionadas; ?> 				  
                    </select>
                  </td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">
              <?php echo $oTranslator->_('(Dica: use SHIFT ou CTRL para selecionar multiplos itens)'); ?>
            </td>
          </tr>
</table>
