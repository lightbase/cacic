<?php
//namespace Cacic\CommonBundle\Common;
  /*
   * Caso o CACIC ainda nao tenha sido configurado redireciona a pagina para
   * para realizar-se a instalacao/configuracao.
   */
  if(!is_readable("include/config.php")) {
     header("Location: instalador");
  }
?>
<!--
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>CACIC - Configurador Automático e Coletor de Informações Computacionais</title>
<LINK REL="shortcut icon" HREF="cacic_icon.ico" TYPE="imag/x-icon" >
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="55,*" cols="*" framespacing="0" frameborder="no" border="0">
  <frame src="top.php" name="topFrame" scrolling="NO" noresize >
  <frameset rows="*" cols="190,*" framespacing="0" frameborder="NO" border="0">
    <frame src="menu_esq.php" name="leftFrame" noresize>
    <frame src="principal.php" name="mainFrame"></frameset>
<noframes><body>

</body></noframes>
</html>
