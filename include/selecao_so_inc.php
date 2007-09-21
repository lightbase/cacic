<? /*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
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
          <td class="cabecalho_tabela"><div align="left">Dispon&iacute;veis:</div></td>
          <td>&nbsp;&nbsp;</td>
          <td width="40">&nbsp;</td>
          <td nowrap>&nbsp;&nbsp;</td>
          <td nowrap class="cabecalho_tabela">Selecionados:</td>
          <td nowrap>&nbsp;&nbsp;</td>
        </tr>
        <tr> 
          <td>&nbsp;</td>
          <td> <div align="left"> 
              <?    /* Consulto todos os sistemas operacionais. */ 
					  	$query = "SELECT id_so, te_desc_so
								  FROM so
								  WHERE id_so > 0";
						$result = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela so ou sua sessão expirou!');

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
    <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
      use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
  </tr>
</table>
