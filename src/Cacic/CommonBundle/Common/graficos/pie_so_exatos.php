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
include 	 '../include/piechart.php';
//AntiSpy();
conecta_bd_cacic();

   $query = 'SELECT count(a.id_so) as qtd, b.te_desc_so  
			          FROM computadores a, so b 
													WHERE a.id_so = b.id_so 
													AND te_nome_computador IS NOT NULL 
													AND a.te_node_address <> ""
													AND dt_hr_ult_acesso is not null
						       GROUP BY a.id_so 
													ORDER BY a.id_so';

   $result = mysql_query($query) or die('Erro no select ou sua sess�o expirou!');

 		while ($row_result = mysql_fetch_assoc($result))		{ 
			$v_row_result = str_pad($row_result['te_desc_so'],20,'.',STR_PAD_RIGHT);
		    $arr[$v_row_result] = $row_result['qtd'];			
	 	} 
   	$CreatePie = 1;
   	$Sort      = 1;		
	phPie($arr, 420 ,	159, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);
?>