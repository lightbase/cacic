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

include_once '../include/library.php'; 
//// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();
$query = 'SELECT a.'.$_GET['v_nome_campo']. ', 
				 count(a.'. $_GET['v_nome_campo'] . ') as "' . $_GET['v_label'] . '"
		  FROM computadores a, so
		  WHERE a.te_nome_computador IS NOT NULL AND a.id_so = so.id_so 
 		  AND a.id_so IN ('. str_replace("-=-", '"',$_GET['v_so_selecionados']) .') '.str_replace("-=-", '"',$_GET['v_query_redes']).'  
		  GROUP BY a.'.$_GET['v_nome_campo'] .' 
		  ORDER BY a.'.$_GET['v_nome_campo'] ;	  
	  
$result = mysql_query($query) or die('Erro no select'); 
require_once $_SERVER['DOCUMENT_ROOT'] . 'include/monta_estatisticas.php';
?>