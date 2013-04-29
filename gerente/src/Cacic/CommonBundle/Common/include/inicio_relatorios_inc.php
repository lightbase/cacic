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
 */
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}
require_once('library.php');
conecta_bd_cacic();
?>
<html>
<head>

<link rel="stylesheet"   type="text/css" href="<?php echo CACIC_URL?>/include/css/cacic.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="<?php echo CACIC_URL?>/include/js/cacic.js"></script>
<script language="JavaScript" type="text/javascript" src="<?php echo CACIC_URL?>/bibliotecas/javascript/asv/asvUtils.js"></script>
<?php
// JavaScripts para fazer a selecao entre os listbox, movendo itens entre eles.
require_once(CACIC_PATH.'/include/js/selecao_listbox.js');
?>
</head>
<body background="<?php echo CACIC_URL?>/imgs/linha_v.gif" onLoad="verifica_status();">
<?php if (!$id_acao) 
	{ 
	$cs_situacao = 'T'; 
	} // Se n�o for setada a var id_acao, ser�o exibidas todas as redes para selecao.
else 
	{
	$where = ($_SESSION['cs_nivel_administrativo']<>1 && $_SESSION['cs_nivel_administrativo']<>2?' AND id_local='.$_SESSION['id_local']:'');	
	$query = "SELECT 	id_acao 
			  FROM 		acoes_redes 
			  JOIN		redes ON acoes_redes.id_rede=redes.id_rede
			  WHERE 	id_acao='$id_acao' ".
						$where . " LIMIT 1";
	$result = mysql_query($query) or die ($oTranslator->_('Ocorreu um erro no acesso a tabela "%1" ou sua sessao expirou!',array('acoes_redes')));
	
	$cs_situacao = (mysql_num_rows($result) > 0?mysql_result($result, 0, 'id_acao'):$cs_situacao);
	}	 
?>