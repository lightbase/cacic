<?
 /* 
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
		// Essa vari�vel � usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
		$id_acao = 'cs_coleta_hardware';
  require_once('../../include/inicio_relatorios_inc.php'); 
?>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Relat&oacute;rio de Softwares Licenciados</td>
  </tr>
</table>
<form action="softwares.php" target="_blank" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="90%" border="0" align="center">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Selecione 
              os softwares que deseja exibir:</td>
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
                      <select multiple name="list5[]" size="25" style="width:300px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? // In�cio - C�mara dos Deputados	
							$query = "SELECT id_software_inventariado, nm_software_inventariado
									  FROM softwares_inventariados 
									  WHERE id_tipo_software = 4 
									  ORDER BY nm_software_inventariado";
						   // Fim - C�mara dos Deputados
						$result_aplicativos_selecionados = mysql_query($query) or die('Ocorreu um erro durante a consulta � tabela softwares_inventariados ou sua sess�o expirou!');
						/* Agora monto os itens do combo de hardwares selecionadas. */ 
       while($campos_aplicativos_selecionados=mysql_fetch_array($result_aplicativos_selecionados)) 	{
						   echo '<option value=' . $campos_aplicativos_selecionados['id_software_inventariado'] . '>' . capa_string($campos_aplicativos_selecionados['nm_software_inventariado'],148)  . '</option>';
						}  ?>
                      </select>
                      </div></td>
                  <td>&nbsp;</td>
                  <td width="40"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">
                    </div></td>
                  <td>&nbsp;</td>
                  <td><select multiple name="list6[]" size="25" style="width:300px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="        Gerar Relat&oacute;rio      " onClick="SelectAll(this.form.elements['list6[]'])">
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
