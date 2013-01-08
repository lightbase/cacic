<table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">  
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
  /* Lembre-se de setar as variáveis 
		$cs_situacao e $id_acao 
		antes de dar um include nesse arquivo. */

			$query = "SELECT te_nome_computador, id_computador
				  FROM computadores
                                  ORDER BY te_nome_computador";
			$msg = $oTranslator->_('(OBS: Estao sendo exibidas somente as redes selecionadas pelo administrador.)');
		$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('computadores')));
		/* Agora monto os itens do combo de redes . */ 
		while($campos=mysql_fetch_array($result)) 	{
		   $itens_combo_soft = $itens_combo_soft . '<option value="' . 
                                        $campos['id_computador']. '">' . 
                                        $campos['te_nome_computador'] . '</option>';
		}  ?>
              Selecione as estações: </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td class="descricao"><p>  
                <input type="radio" name="cs_situacaos" value="T" onclick="verifica_status();SetaClassDigitacao(this.form.elements['list3[]']);"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <?php echo $oTranslator->_('<strong>Todas</strong> as redes');?><br>
                <input name="cs_situacaos" type="radio" onclick="verifica_status();SetaClassNormal(this.form.elements['list3[]']);" value="S" checked  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <?php echo $oTranslator->_('Apenas redes <strong>selecionadas</strong>');?><br>
                </p></td>
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
                      <select multiple size="10" name="list3[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?php echo $itens_combo_soft; ?> 
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="BB1">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="BB2">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple size="10" name="list4[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
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
