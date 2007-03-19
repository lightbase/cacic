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

require_once('library.php');
conecta_bd_cacic();
?>
<html>
<head>

<link rel="stylesheet"   type="text/css" href="../../../include/cacic.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?
// JavaScripts para fazer a selecao entre os listbox, movendo itens entre eles.
require_once('../../../include/selecao_listbox.js');
?>
</head>
<body background="../../../imgs/linha_v.gif" onLoad="verifica_status();">
<script language="JavaScript" type="text/javascript" src="../../../include/cacic.js"></script>
<?
if (!$id_acao) 
	{ 
	$cs_situacao = 'T'; 
	} // Se não for setada a var id_acao, serão exibidas todas as redes para selecao.
else 
	{
	$where = ($_SESSION['cs_nivel_administrativo']<>1 && $_SESSION['cs_nivel_administrativo']<>2?' AND id_local='.$_SESSION['id_local']:'');	
	$query = "SELECT 	cs_situacao 
			  FROM 		acoes_redes 
			  WHERE 	id_acao='$id_acao' ".
						$where . " LIMIT 1";
	$result = mysql_query($query) or die ('Erro na consulta à tabela acoes');
	
	$cs_situacao = (mysql_num_rows($result) > 0?mysql_result($result, 0, 'cs_situacao'):$cs_situacao);
	}	 
?>
