<?php
  /*
   * Caso o CACIC ainda nao tenha sido configurado redireciona a pagina para
   * para realizar-se a instalacao/configuracao.
   */
  if(!is_readable("include/config.php")) {
     header("Location: instalador");
  }
?>
<!--
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais</title>
<LINK REL="shortcut icon" HREF="cacic_icon.ico" TYPE="imag/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="55,*" cols="*" framespacing="0" frameborder="no" border="0">
  <frame src="top.php" name="topFrame" scrolling="NO" noresize >
  <frameset rows="*" cols="181,*" framespacing="0" frameborder="NO" border="0">
    <frame src="menu_esq.php" name="leftFrame" noresize>
    <frame src="principal.php" name="mainFrame"></frameset>
<noframes><body>

</body></noframes>
</html>
