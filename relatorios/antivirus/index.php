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
		// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'cs_coleta_officescan';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio 
      de Configura&ccedil;&otilde;es do Antiv&iacute;rus OfficeScan</td>
  </tr>
  <tr> 
    <td class="descricao">Este 
      relat&oacute;rio exibe a configura&ccedil;&atilde;o 
      do antiv&iacute;rus OfficeScan atualmente instalada 
      nos computadores das redes selecionadas. &Eacute; 
      poss&iacute;vel selecionar os sistemas operacionais 
      desejados e tamb&eacute;m determinar quais informa&ccedil;&otilde;es 
      do antiv&iacute;rus OfficeScan ser&atilde;o exibidas 
      no relat&oacute;rio.</td>
  </tr>
  <tr> 
    <td>
				</td>
  </tr>
</table>
<form action="rel_antivirus.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma" onSubmit="valida_form()">
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr>
	<?
	/*
  if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
  	{
	?>
	<td valign="top">
	  <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label">Selecione os locais:</td>
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
                      <select multiple name="list11[]" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?
                        $query = "SELECT 	id_local,
											sg_local
                                  FROM 		locais
                                  ORDER BY	sg_local";
                        $result = mysql_query($query) or die('Erro na consulta à tabela "locais".');
                        while ($row = mysql_fetch_array($result)) 
							{ 
                            echo '<option value=' . $row['id_local'] . '>' . $row["sg_local"] . '</option>';
                        	}
						?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list11[]'],this.form.elements['list12[]'])" name="B132">					  
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list12[]'],this.form.elements['list11[]'])" name="B232">					  
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list12[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
              use SHIFT or CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
		    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>  
	<?
	
	}
	*/
	?> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td class="label">Selecione 
              as configura&ccedil;&otilde;es que deseja exibir:</td>
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
                      <select multiple name="list5[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <option value=", nu_versao_engine as &quot;Vers&atilde;o Engine&quot;">Vers&atilde;o 
                        do Engine</option>
                        <option value=", nu_versao_pattern as &quot;Vers&atilde;o Pattern&quot;">Vers&atilde;o 
                        do Pattern</option>
                        <option value=", DATE_FORMAT(dt_hr_instalacao,'%d/%m/%Y às %H:%ih') as &quot;Data/Hora Instala&ccedil;&atilde;o&quot;">Data/Hora 
                        Instala&ccedil;&atilde;o</option>
                        <option value=", DATE_FORMAT(dt_hr_coleta,'%d/%m/%Y às %H:%ih') as &quot;Data/Hora &Uacute;ltima Coleta&quot;">Data/Hora 
                        &Uacute;ltima Coleta</option>
                        <option value=", te_servidor as &quot;Servidor&quot;">Endere&ccedil;o 
                        Servidor</option>
                        <option value=", IF(in_ativo='1', 'S','N') as &quot;Ativo&quot;">Antiv&iacute;rus 
                        Ativo</option>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
					<?				  
                      //<input type="button" value="   &gt;   " onClick="copia(this.form.elements['list5[]'],this.form.elements['list7[]']); move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
					 ?>
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">					  
                      <br>
                      <br>
					  <?
                      //<input type="button" value="   &lt;   " onClick="exclui(this.form.elements['list6[]'],this.form.elements['list8[]']); exclui(this.form.elements['list6[]'],this.form.elements['list7[]']); move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">
					  ?>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">					  
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list6[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
              use SHIFT or CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
<?	
/*	
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Selecione 
              as configura&ccedil;&otilde;es para estat&iacute;sticas:</td>
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
                      <select multiple name="list7[]" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                      </select>
                      </font></div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list7[]'],this.form.elements['list8[]'])" name="B1322">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list8[]'],this.form.elements['list7[]'])" name="B2322">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list8[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;(Dica: 
              use SHIFT or CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
*/
?>	
	
    <tr> 
      <td valign="top"> 
        <?  $v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
		require_once($v_require);		
		?>
      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?  require_once('../../include/selecao_so_inc.php');		?>
      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"><br> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
				<?
                //<input name="submit" type="submit" id="submit" onClick="SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list6[]']), SelectAll(this.form.elements['list8[]'])" value="     Gerar Relat&oacute;rio     ">
				?>
                <input name="submit" type="submit" id="submit" onClick="<? echo ($_SESSION['cs_nivel_administracao']<>1 && 
																				 $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>,
																		SelectAll(this.form.elements['list4[]']), 
																		SelectAll(this.form.elements['list6[]'])" value="     Gerar Relat&oacute;rio     ">				
              </div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
</body>
</html>
