<html>
<head>
<title>Caso de Uso - Coleta de Informa&ccedil;&otilde;es de Hardware</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<table width="85%" border="0" align="center">
  <tr>
    <td><p><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><strong>&nbsp;<br>
        Caso de Uso - Coleta de Informa&ccedil;&otilde;es de Hardware</strong></font></p>
      <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Sum&aacute;rio:</strong> 
        Agente Oper&aacute;rio realiza a coleta de informa&ccedil;&otilde;es de 
        hardware no computador onde est&aacute; instalado.</font></p>
      <table border="0" cellpadding="2" cellspacing="4">
        <tr> 
          <td colspan="2" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fluxo 
            Principal: </strong></font></td>
        </tr>
        <tr> 
          <td width="16" valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">1.</font></div></td>
          <td width="636"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">O 
            Agente Oper&aacute;rio questiona ao Agente Gerente se a coleta de 
            informa&ccedil;&otilde;es de hardware dever&aacute; ser realizada.</font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">2.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">O Agente 
            Gerente, baseado no endere&ccedil;o da subrede, na vers&atilde;o do 
            sistema operacional e na identifica&ccedil;&atilde;o do Agente Oper&aacute;rio, 
            verifica se dever&aacute; ser realizada a coleta de informa&ccedil;&otilde;es 
            de hardware no computador onde o Agente Oper&aacute;rio est&aacute; 
            instalado e responde ao questionamento. </font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">3.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"> S&atilde;o 
            coletadas informa&ccedil;&otilde;es sobre v&aacute;rios componentes 
            de hardware. </font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">4.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">As informa&ccedil;&otilde;es 
            de hardware coletadas s&atilde;o comparadas com o resultado da coleta 
            realizada anteriormente. </font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">5.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">As informa&ccedil;&otilde;es 
            coletadas s&atilde;o enviadas, via rede de dados, para o Agente Gerente.</font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">6.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">O Agente 
            Gerente atualiza essas informa&ccedil;&otilde;es no perfil correspondente 
            ao computador onde o agente est&aacute; instalado e acrescenta um 
            registro ao hist&oacute;rico de altera&ccedil;&otilde;es de hardware 
            correspondente a este computador.</font></td>
        </tr>
        <tr>
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">7.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">O Agente 
            Gerente identifica que houve altera&ccedil;&otilde;es na configura&ccedil;&atilde;o 
            de hardware e notifica, atrav&eacute;s de um e-mail, a(s) pessoa(s) 
            respons&aacute;vel(veis) por esses equipamentos para que sejam tomadas 
            as devidas provid&ecirc;ncias. Altera&ccedil;&otilde;es na configura&ccedil;&atilde;o 
            de hardware podem indicar falha de funcionamento, substitui&ccedil;&atilde;o 
            (&agrave;s vezes indevida) e, at&eacute; mesmo, furto do componente 
            de hardware.</font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">8.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">O Agente 
            Oper&aacute;rio armazena localmente as informa&ccedil;&otilde;es coletadas, 
            para que possa, na sua pr&oacute;xima execu&ccedil;&atilde;o, compar&aacute;-las 
            com o resultado obtido e decidir se as informa&ccedil;&otilde;es sofreram 
            ou n&atilde;o algum tipo de altera&ccedil;&atilde;o.</font></td>
        </tr>
      </table>
	  <hr>
      <table border="0" cellpadding="2" cellspacing="4">
        <tr> 
          <td colspan="2" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fluxo 
            Alternativo (2): Coleta de hardware n&atilde;o deve ser realizada</strong></font></td>
        </tr>
        <tr> 
          <td valign="top"> <div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">a.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Caso 
            o Agente Gerente responda negativamente ao questionamento do Agente 
            Oper&aacute;rio, o procedimento de coleta de informa&ccedil;&otilde;es 
            de hardware n&atilde;o &eacute; realizado e o caso de uso &eacute; 
            encerrado. </font></td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="2" cellspacing="4">
        <tr> 
          <td colspan="2" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fluxo 
            Alternativo (4): Informa&ccedil;&otilde;es de hardware id&ecirc;nticas 
            &agrave;s anteriormente coletadas</strong></font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">a.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Caso 
            as informa&ccedil;&otilde;es coletadas sejam id&ecirc;nticas &agrave;s 
            informa&ccedil;&otilde;es obtidas na coleta anterior, h&aacute; um 
            indicativo de que a configura&ccedil;&atilde;o de hardware permanece 
            inalterada e n&atilde;o h&aacute; a necessidade de reenvio das informa&ccedil;&otilde;es 
            para o Agente Gerente. O caso de uso &eacute; encerrado.</font></td>
        </tr>
      </table>
      <hr>
      <table border="0" cellpadding="2" cellspacing="4">
        <tr> 
          <td colspan="2" valign="top"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fluxo 
            Alternativo (7): Notifica&ccedil;&otilde;es de altera&ccedil;&atilde;o 
            de hardware n&atilde;o devem ser enviadas</strong></font></td>
        </tr>
        <tr> 
          <td valign="top"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">a.</font></div></td>
          <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Se o 
            sistema tiver sido configurado para n&atilde;o notificar a(s) pessoa(s) 
            respons&aacute;vel(veis) caso seja identificada altera&ccedil;&otilde;es 
            na configura&ccedil;&atilde;o de hardware, nenhum e-mail &eacute; 
            enviado. O caso de uso prossegue a partir do passo 7.</font></td>
        </tr>
      </table>
      <p>&nbsp;</p></td>
  </tr>
</table>
</body>
</html>
