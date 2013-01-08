<?php
 /* 
 */
	require_once('../../../include/inicio_gerencia_licencas_inc.php');
?>
 <script src="../../../include/js/sniffer.js" type="text/javascript" language="javascript"></script>
 <script src="../../../include/js/dyncalendar.js" type="text/javascript" language="javascript"></script>
 <link href="../../../include/css/dyncalendar.css" media="screen" rel="stylesheet">

<table width="85%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?php echo $oTranslator->_('Cadastro de Aquisicoes');?></td>
  </tr>
  <tr> 
    <td class="descricao"></td>
  </tr>
  <tr> 
    <td>
				</td>
  </tr>
</table>
<script type="text/javascript" language="JavaScript">
<!--
function valida_form_cadastro_aquisicao() {
	if (document.form_aquisicao.date_aquisicao.value == "") {
		alert('<?php echo $oTranslator->_("Voce deve informar a data de aquisicao.");?>');
		return false;
	}
	else if (document.form_aquisicao.numero_processo.value.length != 11) {
		alert('<?php echo $oTranslator->_("Voce deve informar o numero do processo no formato");?>' + ' aaaa/nnnnnn');
		return false;
	}
	return true;
}
//-->
</script>
<form action="rel_cadastro_aquisicao.php" method="post" ENCTYPE="multipart/form-data" name="form_aquisicao"   onsubmit="return valida_form_cadastro_aquisicao(this)">
  <table width="85%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label" colspan="2">
              <?php echo $oTranslator->_('Informe o periodo em que foi realizada a aquisicao:');?>
            </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="2"></td>
          </tr>
          <tr> 
            <td width="33%" height="1" nowrap>&nbsp;<br>
              <input name="date_aquisicao" size="12"  maxlength="12" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <script type="text/javascript" language="JavaScript">
<!--
		function calendar1Callback(date, month, year)	{
			if (String(month).length == 1) {
				month = '0' + month;
			}
			document.forms['form_aquisicao'].date_aquisicao.value = date + '/' + month + '/' + year;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
//-->
</script>
              </td><td align="left" class="descricao">(<?php echo $oTranslator->_('formato:');?> <?php echo $oTranslator->_('dd/mm/aaaa');?>)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label" colspan="2"><?php echo $oTranslator->_('Informe o numero do processo:');?></td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="2"></td>
          </tr>
          <tr> 
            <td width="33%" height="1" nowrap>&nbsp;<br>
              <input name="numero_processo" type="text" size="11"  maxlength="11" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              </td><td align="left" class="descricao">(<?php echo $oTranslator->_('formato:');?> <?php echo $oTranslator->_('aaaa/nnnnnn');?>)</td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="2"></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Nome da empresa:');?>
          <input name="nm_empresa" type="text" size="45"  maxlength="45" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        </td>
    </tr>
    <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Nome do proprietario:');?>
          <input name="nm_proprietario" type="text" size="45"  maxlength="45" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        </td>
    </tr>
    <tr> 
        <td class="label">
          <?php echo $oTranslator->_('Numero da nota fiscal:');?>
          <input name="nr_notafiscal" type="text" size="20"  maxlength="20" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
        </td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td height="1" bgcolor="#333333"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td> <div align="center"> 
                <input name="submit" type="submit" value="<?php echo $oTranslator->_('Incluir Aquisicao');?>" onClick="">
              </div></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
