<? /*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="1">
  <tr> 
    <td class="label">Selecione os sistemas operacionais:</td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td height="1"><table border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td>&nbsp;&nbsp;</td>
          <td class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Disponiveis');?></div></td>
          <td>&nbsp;&nbsp;</td>
          <td width="40">&nbsp;</td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap class="cabecalho_tabela"><span class="necessario">*</span><?=$oTranslator->_('Selecionados');?></td>
          <td nowrap>&nbsp;&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td> <div align="left"> 
              <?    /* Consulto todos os sistemas operacionais. */ 
					  	$query = "SELECT id_so, te_desc_so
								  FROM so
								  WHERE id_so > 0
								  ORDER BY te_desc_so";
						$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('so')));

						/* Agora monto os itens do combo de so's. */ 
						while($campos = mysql_fetch_array($result)) {
						   $itens_combo_so = $itens_combo_so . '<option value="' . $campos['id_so']. '">' . capa_string($campos['te_desc_so'], 35) . '</option>';
						}
						?>
              <select multiple size="10" name="list3[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              </select>
              </div></td>
          <td>&nbsp;</td>
          <td width="40"> <div align="center"> 
              <input type="button" value="   &gt;   " onClick="move(this.form.elements['list3[]'],this.form.elements['list4[]'])" name="B13">
              <br>
              <br>
              <input type="button" value="   &lt;   " onClick="move(this.form.elements['list4[]'],this.form.elements['list3[]'])" name="B23">
            </div></td>
          <td>&nbsp;</td>
          <td><select multiple size="10" name="list4[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
              <? echo $itens_combo_so; ?> </select></td>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
  <tr> 
     <td class="descricao">
        <i><?=$oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?></i>
     </td>
  </tr>
</table>
