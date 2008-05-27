<?
 /* 
 */
	require_once('../../../include/inicio_gerencia_licencas_inc.php');
?>
 <script src="../../../include/sniffer.js" type="text/javascript" language="javascript"></script>
 <script src="../../../include/dynCalendar.js" type="text/javascript" language="javascript"></script>
 <link href="../../../include/dynCalendar.css" media="screen" rel="stylesheet">

<table width="90%" border="0" align="center">
  <tr> 
    <td class="cabecalho"><?=$oTranslator->_('Cadastro de Aquisicoes');?></td>
  </tr>
  <tr> 
    <td class="descricao">Colocar algo aqui</td>
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
		alert("Voce deve informar a data de aquisicao.");
		return false;
	}
	else if (document.form_aquisicao.numero_processo.value.length != 11) {
		alert("Voce deve informar o numero do processo no formato 'aaaa/nnnnnn'.");
		return false;
	}
	return true;
}
//-->
</script>
<form action="rel_cadastro_aquisicao.php" method="post" ENCTYPE="multipart/form-data" name="form_aquisicao"   onsubmit="return valida_form_cadastro_aquisicao(this)">
  <table width="90%" border="0" align="center" cellpadding="5" cellspacing="1">
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label" colspan="2">
              <?=$oTranslator->_('Informe o periodo em que foi realizada a aquisicao:');?>
            </td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="2"></td>
          </tr>
          <tr> 
            <td width="33%" height="1" nowrap>&nbsp;<br>
              <input name="date_aquisicao" type="text" size="7"  class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              <script type="text/javascript" language="JavaScript">
<!--
		function calendar1Callback(date, month, year)	{
			if (String(month).length == 1) {
				month = '0' + month;
			}
			document.forms['form_aquisicao'].date_aquisicao.value = year + '/' + month;
		}
  	calendar1 = new dynCalendar('calendar1', 'calendar1Callback');
//-->
</script>
              </td><td align="left" class="descricao">(<?=$oTranslator->_('formato:');?> aaaa/mm)</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr> 
      <td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="1">
          <tr> 
            <td class="label" colspan="2"><?=$oTranslator->_('Informe o numero do processo:');?></td>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="2"></td>
          </tr>
          <tr> 
            <td width="33%" height="1" nowrap>&nbsp;<br>
              <input name="numero_processo" type="text" size="11"  maxlength="11" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" > 
              </td><td align="left" class="descricao">(<?=$oTranslator->_('formato:');?> aaaa/nnnnnn)</td>
          </tr>
        </table>
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
                <input name="submit" type="submit" value="<?=$oTranslator->_('Incluir Aquisicao');?>" onClick="">
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
