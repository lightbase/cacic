<?
	// Relatorio de classificacao de softwares
	// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'cs_coleta_hardware';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>
<html>
<body>
<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho">Formul&aacute;rio para classifica&ccedil;&atilde;o de softwares</td>
  </tr>
</table>
<form action="../softwares.php" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="90%" border="0" align="center">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">Selecione 
              os softwares que deseja classificar:</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left">N&atilde;o classificados:</div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="90">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela">Selecionados:</td>
                  <td width="90">&nbsp;&nbsp;</td>
		  <td nowrap class="cabecalho_tabela">Tipo do software:</td>
		  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple name="list5[]" size="30" style="width: 320px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <? 	$query = "SELECT id_software_inventariado, nm_software_inventariado
			  FROM softwares_inventariados
			  WHERE (id_tipo_software = 0) 
			  ORDER BY nm_software_inventariado";

			$result_aplicativos_selecionados = mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela softwares_inventariados.');
			/* Agora monto os itens do combo de hardwares selecionadas. */ 
       while($campos_aplicativos_selecionados=mysql_fetch_array($result_aplicativos_selecionados)) 	{
						   echo '<option value=' . $campos_aplicativos_selecionados['id_software_inventariado'] . '>' . capa_string($campos_aplicativos_selecionados['nm_software_inventariado'],148)  . '</option>';
						}  ?>
                      </select>
                      </div></td>
                  <td>&nbsp;&nbsp;&nbsp;</td>
                  <td width="90"> <div align="center"> 
                      <input type="button" value="   &gt;   " onClick="move(this.form.elements['list5[]'],this.form.elements['list6[]'])" name="B132">
                      <br>
                      <br>
                      <input type="button" value="   &lt;   " onClick="move(this.form.elements['list6[]'],this.form.elements['list5[]'])" name="B232">
                    </div></td>
                  <td>&nbsp;&nbsp;&nbsp;</td>
                  <td><select multiple name="list6[]" size="30" style="width: 200px" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;&nbsp;&nbsp;</td>
		  <td nowrap>
		  <?
		  $query 	= "	SELECT  *
		  				FROM	tipos_software
						ORDER BY	te_descricao_tipo_software";
		  $result 	= mysql_query($query) or die('Ocorreu um erro durante a consulta à tabela tipos_software.');		  
		  while ($row = mysql_fetch_array($result))
		  	{
			?>
			<input type="radio" name="tiponovo" value="<? echo $row['id_tipo_software'];?>"><? echo $row['te_descricao_tipo_software'];?><BR>
			<?
			}
			?>
			
		  </td>
		  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td valign="top">&nbsp;</td>
    </tr>
    <tr> 
      <td valign="top"> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="        Classificar Softwares Selecionados        " onClick="SelectAll(this.form.elements['list6[]']), SelectAll(this.form.elements['tiponovo'])">
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
