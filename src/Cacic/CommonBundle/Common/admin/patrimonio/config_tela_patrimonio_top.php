<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../../include/library.php');
AntiSpy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Configura&ccedil;&atilde;o da Tela de Patrim&ocirc;nio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif" text="#000000" topmargin="4">
<table width="90%" border="0" align="center">
  <tr>
    <td class="cabecalho">
      <?=$oTranslator->_('Tela de Coleta de Informacoes de Patrimonio');?>
    </td>
  </tr>
  <tr>
    <td class="descricao">
      <?=$oTranslator->_('Tela de Coleta de Informacoes de Patrimonio - ajuda');?>
    </td>
  </tr>
</table>
<br>
<div align="center"><img src="tela_patrimon.png" alt="" width="798" height="291" border="0" usemap="#Map">
<map name="Map">
  <area shape="rect" coords="653,195,784,234" href="etiqueta_generica.php?id_etiqueta=etiqueta9" target="botFrame">
<area shape="rect" coords="653,148,784,191" href="etiqueta_generica.php?id_etiqueta=etiqueta6" target="botFrame">
<area shape="rect" coords="500,148,633,191" href="etiqueta_generica.php?id_etiqueta=etiqueta5" target="botFrame">
<area shape="rect" coords="348,195,480,235" href="etiqueta_generica.php?id_etiqueta=etiqueta7" target="botFrame">
<area shape="rect" coords="500,196,633,234" href="etiqueta_generica.php?id_etiqueta=etiqueta8" target="botFrame">
<area shape="rect" coords="349,148,481,189" href="etiqueta_generica.php?id_etiqueta=etiqueta4" target="botFrame">
<area shape="rect" coords="349,106,784,144" href="etiqueta3.php" target="botFrame" alt="Configure o campo de texto livre">
<area shape="rect" coords="12,194,338,235" href="etiqueta2.php" target="botFrame" alt="Configure a 3&ordf; ComboBox">
<area shape="rect" coords="12,148,338,189" href="etiqueta1a.php" target="botFrame" alt="Configure a 2&ordf; ComboBox">
<area shape="rect" coords="12,106,338,144" href="etiqueta1.php" target="botFrame" alt="Configure a 1&ordf; ComboBox">
</map></div>
</body>
</html>
