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
include 	 '../include/piechart.php';
//// Comentado temporariamente - AntiSpy();
conecta_bd_cacic();

   $query = 'SELECT count(a.id_so) as qtd, b.te_desc_so  
			          FROM computadores a, so b 
													WHERE a.id_so = b.id_so 
													AND te_nome_computador IS NOT NULL 
													AND a.te_node_address <> ""
													AND dt_hr_ult_acesso is not null
						       GROUP BY a.id_so 
													ORDER BY a.id_so';

   $result = mysql_query($query) or die('Erro no select ou sua sessão expirou!');

 		while ($row_result = mysql_fetch_assoc($result))		{ 
			$v_row_result = str_pad($row_result['te_desc_so'],20,'.',STR_PAD_RIGHT);
		    $arr[$v_row_result] = $row_result['qtd'];			
	 	} 
   	$CreatePie = 1;
   	$Sort      = 1;		
	phPie($arr, 420 ,	159, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);
?>