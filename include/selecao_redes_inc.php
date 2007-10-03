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
  /* Lembre-se de setar as variáveis 
		$cs_situacao e $id_acao 
		antes de dar um include nesse arquivo. */
?>		
<table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">  
<?
		$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' redes.id_local = '.$_SESSION['id_local']:'');
		if ($_SESSION['te_locais_secundarios']<>'' && $where <> '')
			{
			// Faço uma inserção de "(" para ajuste da lógica para consulta	
			$where = str_replace(' redes.id_local = ',' (redes.id_local = ',$where);
			$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
			}
		
		if ($cs_situacao == 'T') 
			{
			$where = ($where<>''?' WHERE '.$where:$where);
			
			$query = "SELECT 	distinct id_ip_rede, 
								nm_rede
					  FROM 		redes ".
					  			$where;
			}
		else 
			{			
			$where = ($where<>''?' AND '.$where:$where);
			// Usei o trecho abaixo para o caso da coleta avulsa de informações patrimoniais...			
			$where = ($id_acao == 'cs_coleta_patrimonio'?' OR "'.$id_acao.'" = "cs_coleta_patrimonio") '.$where:$where.') ');						
			$query = "SELECT 	distinct redes.id_ip_rede, 
								nm_rede
					  FROM 		redes, 
					  			acoes_redes 
					  WHERE 	(redes.id_ip_rede = acoes_redes.id_ip_rede AND 
					  			acoes_redes.id_acao = '$id_acao' ".
								$where;
			$msg = '(OBS: Estão sendo exibidas somente as redes selecionadas pelo administrador.)';
			}

		$result = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela redes ou sua sessão expirou!');
		/* Agora monto os itens do combo de redes . */ 
		while($campos=mysql_fetch_array($result)) 	
			{
		   	$itens_combo_redes = $itens_combo_redes . '<option value="' . $campos['id_ip_rede']. '">' . $campos['id_ip_rede'] . ' - ' . capa_string($campos['nm_rede'], 22) . '</option>';
			}  
			?>
              Selecione as redes: </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td class="descricao"><p>  
                <input type="radio" name="cs_situacao" id="cs_situacao" value="T" onclick="verifica_status();SetaClassDigitacao(this.form.elements['list1[]']);"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <strong>Todas</strong> as redes<br>
                <input type="radio" name="cs_situacao" id="cs_situacao" value="S" onclick="verifica_status();;SetaClassNormal(this.form.elements['list1[]']);"    class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" checked>
                Apenas redes <strong>selecionadas<br>
                &nbsp;<? echo $msg?></p></td>
          </tr>
          <tr> 
<tr><td><br></td></tr>
            <td><table border="0" cellpadding="0" cellspacing="0">
			
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left">Dispon&iacute;veis:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela">Selecionadas:<br></td>
                  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple size="10" name="list1[]" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? echo $itens_combo_redes; ?> 
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
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;(Dica: 
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
</table>
