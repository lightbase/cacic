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
?>

<html>
<head>
<title>TRIBO do CACIC - Configurador Autom&aacute;tico e Coletor de Informa&ccedil;&otilde;es Computacionais</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="links.css" rel="stylesheet" type="text/css">
</head>
<?  include "./include/inc_top.php";  ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="150" valign="top" bgcolor="#D7D7D7">
	<br>
	<table width="100%" border="0" cellpadding="2" cellspacing="0">
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333" size="1"><font size="2"><a href="?pg=home.php">Home</a></font></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"> 
            <a href="?pg=docs/mase/identif_objetivos.php">Objetivos</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=seta_foto.php">ScreenShots</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=docs/index.php">Documenta&ccedil;&atilde;o</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=docs/suporte.php">Suporte</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=docs/faq.html">FAQ</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=docs/changelog.html">ChangeLog</a></font></font></font></td>
        </tr>
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><a href="?pg=docs/wiw.html">Quem � quem?</a></font></font></font></td>
        </tr>
        
        <tr valign="baseline"> 
          <td><img src="imgs/arrow.gif" width="16" height="16"></td>
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333" size="1"><font size="2"><a href="../">Gerente Centralizado</a></font></font></font></font><font color="#333333" size="1"></font></font></font></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      <p><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333" size="1"><strong><font size="2"><br>
        <br>
        </font></strong></font><font color="#333333"><strong><br>
        <br>
        </strong></font></font></font></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333"><strong><br>
        </strong></font></font></font></p>
    </td>
    <td width="1" background="imgs/linha.gif"></td>
    <td width="15"></td>
    <td valign="top">
       <?  if (! $pg) { include "home.php"; }
	       else {  include $pg; } ?>	
    </td>
  </tr>
</table>
<?  include "./include/inc_bottom.php";  ?>
</body>
</html>
