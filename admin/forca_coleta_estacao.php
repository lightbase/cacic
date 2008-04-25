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

session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php'); 
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

?>
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title><?=$oTranslator->_('Coleta induzida por Computador');?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? // O require abaixo será usado pelo inc_opcoes_administrativas/coleta_forcada.php - Não remova! - A.A.P. 23/09/2004
require_once('../include/opcoes_avancadas_combos.js'); ?>
<style type="text/css">
<!--
.style1 {
	color: #000099;
	font-weight: bold;
}
-->
</style>
</head>

<body>
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="forca_coleta_estacao_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho"><?=$oTranslator->_('Coleta induzida por Computador');?> <u><em><? echo $_GET['te_nome_computador']; ?></em></u></td>
  </tr>
  <tr> 
      <td class="descricao"><?=$oTranslator->_('Esta pagina permite induzir coletas em determinado computador');?></td>
  </tr>
    	<tr> 
      	<td><br>
      	  <span class="style1"><u>Observa&ccedil;&otilde;es</u>: </span><br>
      	  1) 
      	  Este comando informar&aacute; ao computador alvo que a pr&oacute;xima coleta deve ser enviada ao gerente incondicionalmente, ou seja, mesmo que seja id&ecirc;ntica &agrave; anterior;<br>
      	  2)  A coleta acontecer&aacute; obedecendo ao intervalo previamente configurado por usu&aacute;rio com o devido privil&eacute;gio de acesso.</td>
    	</tr>

<br>
	<?
	$forca_coleta_estacao = 'OK'; // Variável a ser verificada pela rotina em opcoes_avancadas.php
	require_once('../include/opcoes_avancadas.php');
	?>
	<br>
	<br>
    	<tr> 
      	<td><div align="center"> 
        <input name="submit" type="submit" value="<?=$oTranslator->_('Induzir coletas');?>">
        </div></td>
    	</tr>
</table>
</form>
<p>&nbsp;</p>

</body>
</html>
