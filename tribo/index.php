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
          <td><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#666666"><font color="#333333" size="1"><font size="2"><a href="http://www-cacic/">Gerente Centralizado</a></font></font></font></font><font color="#333333" size="1"></font></font></font></td>
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
