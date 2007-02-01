<?
// Comentário
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252"></meta>
    <title>frmTesteFraseAJAX</title>
 <script language="JavaScript" type="text/javascript" src="include/Ajax.js"></script>	  
  </head>
  <body onload="document.formTeste.strFrase.focus()"><center>
      <form name="formTeste" action="ajax1.php" method="post"
            id="formTeste">
        
    <table cellspacing="2" cellpadding="3" border="0" width="60%">
      <tr> 
        <th colspan="2"> Teste de Comunica&ccedil;&atilde;o AJAX&nbsp;&nbsp; </th>
      </tr>
      <tr> 
        <td>Frase:</td>
        <td align="left"> <input type="text" name="strFrase" maxlength="80" size="80"
                     id="strFrase"/> </td>
      </tr>
      <tr>
        <td nowrap>Valor1 Recebido pelo Servidor:</td>
        <td align="left"><input type="text" name="strValor1" maxlength="80" size="80"
                     id="strValor1"/></td>
      </tr>
      <tr>
        <td nowrap>Valor2 Recebido pelo Servidor:</td>
        <td align="left"><input type="text" name="strValor2" maxlength="80" size="80"
                     id="strValor2"/></td>
      </tr>
	  
      <tr> 
        <td colspan="2" align="center"> <input type="button" name="btnSubmit" value="Submeter Frase"
                     id="btnSubmit" onClick="fAjaxEnviaArgumentos('ajax2.php',document.formTeste.strFrase.value)"/> 
        </td>
      </tr>
    </table>
      </form>
    </center></body>
</html>
