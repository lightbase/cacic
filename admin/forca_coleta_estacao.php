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
 * verifica se houve login e também as permissões de usuário
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para verificar permissões do usuário!
}
require_once('../include/library.php'); 
// Comentado temporariamente - AntiSpy();
?>
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../include/cacic.css">
<title>Coleta For&ccedil;ada por Computador</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? // O require abaixo será usado pelo inc_opcoes_administrativas/coleta_forcada.php - Não remova! - A.A.P. 23/09/2004
require_once('../include/opcoes_avancadas_combos.js'); ?> 

</head>

<body>
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<form action="forca_coleta_estacao_set.php"  method="post" ENCTYPE="multipart/form-data" name="forma">
<table width="90%" border="0" align="center">
  <tr> 
      <td class="cabecalho">Coleta For&ccedil;ada no Computador <u><em><? echo $_GET['te_nome_computador']; ?></em></u></td>
  </tr>
  <tr> 
      <td class="descricao">Esta p&aacute;gina permite for&ccedil;ar coletas em determinado computador.</td>
  </tr>

<br>
  <table width="90%" border="0" align="center" cellpadding="0" cellspacing="1">
	<?
	$forca_coleta_estacao = 'OK'; // Variável a ser verificada pela rotina em opcoes_avancadas.php
	require_once('../include/opcoes_avancadas.php');
	?>
    
    	<tr> 
      	<td height="1" bgcolor="#333333"></td>
    	</tr>
    	<tr> 
      	<td>&nbsp;</td>
    	</tr>
    	<tr> 
      	<td><div align="center"> 
        <input name="submit" type="submit" value="Força Coletas">
        </div></td>
    	</tr>
  </table>
</table>
</form>
<p>&nbsp;</p>

</body>
</html>
