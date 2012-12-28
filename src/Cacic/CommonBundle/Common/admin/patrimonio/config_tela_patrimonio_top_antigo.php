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
<div align="center"><img src="tela_patrimon.gif" alt="" width="737" height="292" border="0" usemap="#Map">
<map name="Map"><area shape="rect" coords="603,184,724,221" href="etiqueta_generica.php?id_etiqueta=etiqueta9" target="botFrame">
<area shape="rect" coords="461,185,586,221" href="etiqueta_generica.php?id_etiqueta=etiqueta8" target="botFrame">
<area shape="rect" coords="320,184,445,223" href="etiqueta_generica.php?id_etiqueta=etiqueta7" target="botFrame">
<area shape="rect" coords="603,146,725,184" href="etiqueta_generica.php?id_etiqueta=etiqueta6" target="botFrame">
<area shape="rect" coords="461,146,585,184" href="etiqueta_generica.php?id_etiqueta=etiqueta5" target="botFrame">
<area shape="rect" coords="321,147,444,183" href="etiqueta_generica.php?id_etiqueta=etiqueta4" target="botFrame">
<area shape="rect" coords="320,110,723,143" href="etiqueta3.php" target="botFrame" alt="Configure o campo de texto livre">
<area shape="rect" coords="5,186,307,221" href="etiqueta2.php" target="botFrame" alt="Configure a 3&ordf; ComboBox">
<area shape="rect" coords="5,148,305,184" href="etiqueta1a.php" target="botFrame" alt="Configure a 2&ordf; ComboBox">
<area shape="rect" coords="6,111,305,143" href="etiqueta1.php" target="botFrame" alt="Configure a 1&ordf; ComboBox">
</map></div>
</body>
</html>
