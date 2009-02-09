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
require_once "library.php";
$query = "SELECT 	*
		  FROM 		locais ".
		  $whereLocais . "
		  ORDER BY	sg_local";
conecta_bd_cacic();		  
$result = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('locais')));

		/* Montagem dos itens do combo de locais . */ 
		while($campos=mysql_fetch_array($result)) 	
			{
		   	$itens_combo_locais = $itens_combo_locais . '<option value="' . $campos['id_local']. '">' . $campos['sg_local'] . '</option>';
			}  
			?>
         <?=$oTranslator->_('Selecione os locais:');?> 
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
			<table border="0" cellpadding="0" cellspacing="0">
			
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Disponiveis:');?></div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
	  <td nowrap class="cabecalho_tabela"><span class="necessario">*</span><?=$oTranslator->_('Selecionados:');?><br></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple size="10" name="list11[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
					  	<?
						echo $itens_combo_locais; 
						?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list11[]'],this.form.elements['list12[]'])" name="B1">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list12[]'],this.form.elements['list11[]'])" name="B2">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple size="10" name="list12[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
          <tr> 
            <td colspan="6" class="descricao">(<?=$oTranslator->_('Dica: use SHIFT ou CTRL para selecionar multiplos itens');?>)</td>
          </tr>
				
              </table></td>
          </tr>

