<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 */
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../../include/library.php');
AntiSpy();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
<title>Configura&ccedil;&atilde;o da Tela de Patrim&ocirc;nio</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body bgcolor="#FFFFFF" background="../../imgs/linha_v.gif" text="#000000" topmargin="4">
<table width="85%" border="0" align="center">
  <tr>
    <td class="cabecalho">
      <?php echo $oTranslator->_('Tela de Coleta de Informacoes de Patrimonio');?>
    </td>
  </tr>
  <tr>
    <td class="descricao">
      <?php echo $oTranslator->_('Tela de Coleta de Informacoes de Patrimonio - ajuda');?>
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
