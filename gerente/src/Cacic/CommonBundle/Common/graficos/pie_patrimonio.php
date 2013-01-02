<?
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

include_once '../include/library.php'; 
//AntiSpy();
conecta_bd_cacic();
$v_novo_campo = $_GET['v_nome_campo'];

if (strpos($_GET['v_nome_campo'],'nivel1')>0)
	{
	$v_novo_campo = str_replace('id_unid','nm_unid',str_replace('patrimonio.','unid_organizacional_nivel1.',$_GET['v_nome_campo']));
	$_GET['v_nome_campo'] = $v_novo_campo;	
	}
elseif (strpos($_GET['v_nome_campo'],'nivel2')>0)
	{
	$v_novo_campo = str_replace('id_unid','nm_unid',str_replace('patrimonio.','unid_organizacional_nivel2.',$_GET['v_nome_campo']));	
	$_GET['v_nome_campo'] = $v_novo_campo;
	}

$query = 'SELECT '.$v_novo_campo. ', 
				 count('. $v_novo_campo . ') as "' . $_GET['v_label'] . '"
		  FROM so, '.$from.' computadores a left join patrimonio on (a.te_node_address = patrimonio.te_node_address and a.id_so = patrimonio.id_so) ' . $where . $where_uon1 .  $where_uon2 . '		  
		  WHERE a.te_nome_computador IS NOT NULL AND 
				a.id_so = so.id_so AND 
				a.id_so IN ('. str_replace("-=-", '"',$_GET['v_so_selecionados']) .') '.str_replace("-=-", '"',$_GET['v_query_redes']). ' AND
				a.te_node_address = patrimonio.te_node_address and a.id_so = patrimonio.id_so 
		  GROUP BY '.$v_novo_campo .' 
		  ORDER BY '.$v_novo_campo ;	  
/*
$query = 'SELECT '.$v_novo_campo. ', 
				 count('. $v_novo_campo . ') as "' . $_GET['v_label'] . '"
		  FROM so, computadores a left join patrimonio on (a.te_node_address = patrimonio.te_node_address and a.id_so = patrimonio.id_so ' . $_SESSION['where'] . $_SESSION['where_uon1'] .  $_SESSION['where_uon2'] . '		  
		  WHERE uon1.id_unid_organizacional_nivel1 = patrimonio.id_unid_organizacional_nivel1 and 
		        uon2.id_unid_organizacional_nivel2 = patrimonio.id_unid_organizacional_nivel2 and 
				a.te_nome_computador IS NOT NULL AND 
				a.id_so = so.id_so AND 
				a.id_so IN ('. str_replace("-=-", '"',$_GET['v_so_selecionados']) .') '.str_replace("-=-", '"',$_GET['v_query_redes']). ' AND
				a.te_node_address = patrimonio.te_node_address and a.id_so = patrimonio.id_so 
		  GROUP BY '.$v_novo_campo .' 
		  ORDER BY '.$v_novo_campo ;	  


//		  FROM so, computadores a, patrimonio, unid_organizacional_nivel1 uon1, unid_organizacional_nivel2 uon2
*/

//session_unregister('where');
$result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!'); 
require_once $_SERVER['DOCUMENT_ROOT'] . 'include/monta_estatisticas.php';
?>