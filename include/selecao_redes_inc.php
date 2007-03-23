<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa�es da Previd�cia Social, Brasil

 Este arquivo �parte do programa CACIC - Configurador Autom�ico e Coletor de Informa�es Computacionais

 O CACIC �um software livre; voc�pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen� Pblica Geral GNU como 
 publicada pela Funda�o do Software Livre (FSF); na vers� 2 da Licen�, ou (na sua opni�) qualquer vers�.

 Este programa �distribuido na esperan� que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA�O a qualquer
 MERCADO ou APLICA�O EM PARTICULAR. Veja a Licen� Pblica Geral GNU para maiores detalhes.

 Voc�deve ter recebido uma c�ia da Licen� Pblica Geral GNU, sob o t�ulo "LICENCA.txt", junto com este programa, se n�, escreva para a Funda�o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
  /* Lembre-se de setar as vari�eis 
		$cs_situacao e $id_acao 
		antes de dar um include nesse arquivo. */
?>		
<table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">  
<?
		$where = ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?' redes.id_local = '.$_SESSION['id_local']:'');
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
			// Usei o trecho abaixo para o caso da coleta avulsa de informa�es patrimoniais...			
			$where = ($id_acao == 'cs_coleta_patrimonio'?' OR id_acao = "cs_coleta_patrimonio") '.$where:') '.$where);	
			$query = "SELECT 	distinct redes.id_ip_rede, 
								nm_rede
					  FROM 		redes, 
					  			acoes_redes 
					  WHERE 	redes.id_ip_rede = acoes_redes.id_ip_rede AND 
					  			( acoes_redes.id_acao = '$id_acao' ".
								$where;
			$msg = '(OBS: Est� sendo exibidas somente as redes selecionadas pelo administrador.)';
			}
		$result = mysql_query($query) or die('Ocorreu um erro durante a consulta da tabela redes.');
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
                <input type="radio" name="cs_situacao" value="T" onclick="verifica_status();SetaClassDigitacao(this.form.elements['list1[]']);"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                <strong>Todas</strong> as redes<br>
                <input name="cs_situacao" type="radio" onclick="verifica_status();;SetaClassNormal(this.form.elements['list1[]']);" value="S" checked  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
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
              use SHIFT or CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
</table>
