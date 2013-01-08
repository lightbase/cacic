<?php
	// Essa variável é usada pelo arquivo de include selecao_redes_inc.php e inicio_relatorios_inc.php.
	$id_acao = 'cs_coleta_hardware';
  	require_once('../../include/inicio_relatorios_inc.php'); 
?>

<?php
/*
	if ($_SESSION["nm_grupo_usuarios"] <> "adm1")
		die("<h1><font color='red'>Acesso n&atilde;o autorizado!</font></h1>
			 <h3>Sua tentativa foi registrada no log!</h3>
		     <b>Nome:</b> " . $_SESSION["nm_usuario"] );
*/			 
?>

<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho">
     <?php echo $oTranslator->_('Exclusao de softwares nao associados a nenhuma maquina');?>
    </td> 
  </tr>
  <tr> 
    <td class="descricao">
      <?php echo $oTranslator->_('Exibe os softwares inventariados nos computadores que nao estao associados a nenhuma maquina.');?>
    </td>
  </tr>
</table>
<form action="confirma_remocao_softwares_nao_instalados.php" method="post" ENCTYPE="multipart/form-data" name="forma"   onsubmit="return valida_form()">
  <table width="85%" border="0" align="center">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label">
              <?php echo $oTranslator->_('Selecione os softwares que deseja remover:');?>
              <font size=0> <?php echo $oTranslator->_('(Softwares nao classificados e softwares lixo)');?></font>
            </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td height="1"><table border="0" cellpadding="0" cellspacing="0">
                <tr> 
                  <td>&nbsp;&nbsp;</td>
                  <td class="cabecalho_tabela"><div align="left"><?php echo $oTranslator->_('Nao instalados:');?></div></td>
                  <td>&nbsp;&nbsp;</td>
                  <td width="40">&nbsp;</td>
                  <td nowrap>&nbsp;&nbsp;</td>
                  <td nowrap class="cabecalho_tabela"><?php echo $oTranslator->_('Selecionados:');?></td>
		  <td nowrap>&nbsp;&nbsp;</td>
                </tr>
                <tr> 
                  <td>&nbsp;</td>
                  <td> <div align="left"> 
                      <select multiple name="list5[]" style="width: 300px" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                        <?php 	$query = "SELECT DISTINCT si.id_software_inventariado, si.nm_software_inventariado
			  FROM softwares_inventariados si
			  WHERE ((si.id_tipo_software = 0) or (si.id_tipo_software = 6)) AND si.id_software_inventariado NOT IN (
			  SELECT DISTINCT id_software_inventariado FROM softwares_inventariados_estacoes sie)
			  ORDER BY nm_software_inventariado LIMIT 300";
			$result_aplicativos_selecionados = mysql_query($query) or die($oTranslator->_('Ocorreu um erro no acesso a tabela %1 ou sua sessao expirou!',array('softwares_inventariados_estacoes')));
			/* Agora monto os itens do combo de hardwares selecionadas. */ 
       while($campos_aplicativos_selecionados=mysql_fetch_array($result_aplicativos_selecionados)) 	{
						   echo '<option value=' . $campos_aplicativos_selecionados['id_software_inventariado'] . '>' . capa_string($campos_aplicativos_selecionados['nm_software_inventariado'],80)  . '</option>';
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
                  <td><select multiple name="list6[]" size="25" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
                    </select></td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td class="descricao">&nbsp;&nbsp;&nbsp;
              <?php echo $oTranslator->_('(Dica: use SHIFT ou CTRL para selecionar multiplos itens)');?>
            </td>
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
      <td valign="top"> <br> <br> <table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td> <div align="center">
			<?php if ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 3) 
					{
					?>
                <input name="submit" type="submit" value="        <?php echo $oTranslator->_('Remover Softwares da Base de Dados');?>        " onClick="SelectAll(this.form.elements['list6[]'])">
						<?php
					}
					?>
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
