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
		// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'col_anvi';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="85%" border="0" align="center">
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
  <table width="85%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr>
	<?php
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
                        <?php
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
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
		    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>  
	<?php
	
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
                        <option value=", DATE_FORMAT(dt_hr_instalacao,'%d/%m/%Y às %H:%ih') as &quot;Data/Hora Instala&ccedil;&atilde;o&quot;,DATE_FORMAT(dt_hr_instalacao,'%Y%m%d%H%i') as &quot;DHI&quot;">Data/Hora 
                        Instala&ccedil;&atilde;o</option>
                        <option value=", DATE_FORMAT(dt_hr_coleta,'%d/%m/%Y às %H:%ih') as &quot;Data/Hora &Uacute;ltima Coleta&quot;,DATE_FORMAT(dt_hr_coleta,'%Y%m%d%H%i') as &quot;DHUC&quot;">Data/Hora 
                        &Uacute;ltima Coleta</option>
                        <option value=", te_servidor as &quot;Servidor&quot;">Endere&ccedil;o 
                        Servidor</option>
                        <option value=", IF(in_ativo='1', 'S','N') as &quot;Ativo&quot;">Antiv&iacute;rus 
                        Ativo</option>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
					<?php				  
                      //<input type="button" value="   &gt;   " onClick="copia(this.form.elements['list5[]'],this.form.elements['list7[]']); move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
					 ?>
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">					  
                      <br>
                      <br>
					  <?php
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
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
          </tr>
        </table></td>
    </tr>
    
<?php	
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
              use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
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
	$date_input1 = date('d/m/Y');
	$date_input2 = $date_input1;	

?>	
	
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
      <td class="label" colspan="2">Informe o per&iacute;odo (data de instala&ccedil;&atilde;o) em que dever&aacute; 
        ser realizada a consulta: (ou deixe em branco) </td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr valign="middle"> 
      <td width="33%" height="1" nowrap valign="middle">
<input name="date_input1" type="text" size="10"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $date_input1;?>">
&nbsp; 
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">a</font> 
&nbsp;&nbsp; <input name="date_input2" type="text" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" value="<?php echo $date_input2;?>">
</td>
      <td align="left" class="descricao">&nbsp;&nbsp;(formato: dd/mm/aaaa)</td>
      </tr>
  </table></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    <tr> 
            <td class="label" colspan="2">Selecione os servidores de atualiza&ccedil;&atilde;o 
              para consulta: (opcional)</td>
    </tr>
    <tr> 
      <td height="1" bgcolor="#333333" colspan="2"></td>
    </tr>
    <tr> 
      <td height="1"><table border="0" cellpadding="0" cellspacing="0">
      	<tr>
        <td><div align="left">
			<?php    /* Consulto todos os servidores já catalogados no banco. */ 
					  	$query = "SELECT 	distinct te_servidor
								  FROM 		officescan
								  WHERE		te_servidor <> ''
								  ORDER BY 	te_servidor";
						$result = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela officescan ou sua sessão expirou!');

						/* Agora monto os itens do combo de so's. */ 
						while($campos = mysql_fetch_array($result)) {
						   $itens_combo_servidores = $itens_combo_servidores . '<option value="' . $campos['te_servidor']. '">' . $campos['te_servidor'] . '</option>';
						}
						?>

<select multiple id="frm_te_servidor[]" name="frm_te_servidor[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
<?php 
/*echo '<option value="" selected>Todos</option>';*/
echo $itens_combo_servidores; ?>
                      </select></div>
		      </td>
		      <td width="40">
		      	<div align="center">
		      		<input type="button" value="   &gt;   " onClick="move(this.form.elements['frm_te_servidor[]'], this.form.elements['frm_te_serv_sel[]'])" name="B332">
		      		<br>
		      		<br>
		      		<input type="button" value="   &lt;   " onClick="move(this.form.elements['frm_te_serv_sel[]'], this.form.elements['frm_te_servidor[]'])" name="B432">
			</div>
		      </td>
		      <td>&nbsp;</td>
<td><select multiple name="frm_te_serv_sel[]" size="10" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >

        </select></td>
	<td>&nbsp;</td>
	</tr>
	</table></td>
      </tr>
	  <tr> 
    <td colspan="2" class="descricao">&nbsp;(Dica: 
      use SHIFT ou CTRL para selecionar m&uacute;ltiplos itens)</td>
  </tr>
  </table></td>
    </tr>	
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
	
    <tr>
      <td valign="top">
      <table width="100%" border="0" cellpadding="0" cellspacing="1" align="center">
    
    <tr> 
      <td height="1" bgcolor="#333333"></td>
    </tr>
    <tr valign="middle"> 
      <td width="33%" height="1" nowrap valign="middle" class="label"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type="checkbox" name="frmCsExibeInfoPatrimonial" value="S" id="frmCsExibeInfoPatrimonial" />Exibir Informa&ccedil;&otilde;es de Patrim&ocirc;nio</font></td>
      </tr>
  </table></td>
    </tr>
	
    <tr>
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?php  
	$cs_situacao = 'T';
	$v_require = '../../include/' .($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'selecao_redes_inc.php':'selecao_locais_inc.php');
		require_once($v_require);		
		?>      </td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> 
        <?php  require_once('../../include/selecao_so_inc.php');		?>      </td>
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
				<?php
                //<input name="submit" type="submit" id="submit" onClick="SelectAll(this.form.elements['list2[]']), SelectAll(this.form.elements['list4[]']), SelectAll(this.form.elements['list6[]']), SelectAll(this.form.elements['list8[]'])" value="     Gerar Relat&oacute;rio     ">
				?>
                <input name="submit" type="submit" id="submit" onClick="ChecaTodasAsRedes(),<?php echo ($_SESSION['cs_nivel_administracao']<>1 && 
																				 $_SESSION['cs_nivel_administracao']<>2?"SelectAll(this.form.elements['list2[]'])":"SelectAll(this.form.elements['list12[]'])")?>,
																		SelectAll(this.form.elements['list4[]']), 
																		SelectAll(this.form.elements['list6[]']),
																		SelectAll(this.form.elements['frm_te_servidor[]']),
																		SelectAll(this.form.elements['frm_te_serv_sel[]'])" value="     Gerar Relat&oacute;rio     ">
Formato:<select name="formato" size="1">

        <option value="html">HTML</option>
        <option value="ods">Planilha (.ods)</option>
        <option value="pdf">PDF</option>
        <option value="csv">CSV</option>

    </select>	
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
