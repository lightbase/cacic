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

include_once '../include/library.php'; 
//AntiSpy();
$v_novo_campo = 'b.' . $_GET['v_nome_campo'];

if ($_GET['v_nome_campo']=='te_versao_cacic')
	{
	$v_novo_campo = str_replace('b.','a.',$v_novo_campo);
	$_GET['v_nome_campo'] = $v_novo_campo;	
	}

conecta_bd_cacic();
$query = 'SELECT '.$v_novo_campo. ', 
				 count('. $v_novo_campo . ') as "' . $_GET['v_label'] . '"
		  FROM computadores a,so, versoes_softwares b
		  WHERE a.te_nome_computador IS NOT NULL AND a.id_so = so.id_so 
 		  AND a.id_so IN ('. str_replace("-=-", '"',$_GET['v_so_selecionados']) .') '.str_replace("-=-", '"',$_GET['v_query_redes']).' AND
		  a.te_node_address = b.te_node_address and a.id_so = b.id_so 
		  GROUP BY '.$v_novo_campo .' 
		  ORDER BY '.$v_novo_campo ;	  
  
$result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!'); 
require_once $_SERVER['DOCUMENT_ROOT'] . 'include/monta_estatisticas.php';
?>