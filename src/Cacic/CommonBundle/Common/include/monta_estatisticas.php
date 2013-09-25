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
 */
include_once $_SERVER['DOCUMENT_ROOT'] . 'include/piechart.php'; 
include_once $_SERVER['DOCUMENT_ROOT'] . 'include/library.php'; 
AntiSpy();
$v_nao_identificado = 0;

conecta_bd_cacic();

while ($row_result = mysql_fetch_array($result))		
	{ 
	$v_label         = $_GET['v_label'];
	$v_posicao_ponto = strpos($_GET['v_nome_campo'],'.');
	if ($v_posicao_ponto>0)
		{
		$v_posicao_ponto += 1;
		}
	$v_campo         = substr($_GET['v_nome_campo'],$v_posicao_ponto,strlen($_GET['v_nome_campo'])-$v_posicao_ponto);

	$v_total      = $row_result[$v_label];	
	$v_row_result = trim($row_result[$v_campo]);

	$v_pontos_preenchimento = 80;
	if (strlen($v_row_result) >= $v_pontos_preenchimento) 
		{
		$v_row_result = substr($v_row_result,1,$v_pontos_preenchimento - 1);
		}

	while (strpos($v_row_result,'  ')>0) 
		{
		$v_row_result = str_replace('  ',' ',$v_row_result);
		}
	$v_row_result = str_pad($v_row_result,$v_pontos_preenchimento,'.',STR_PAD_RIGHT);

	if ($v_total == 0 || str_replace('.','',$v_row_result) == '') 
		{
		$v_nao_identificado ++;
		}
	else
		{
		$v_array[$v_row_result] = $v_total;				
		}
	}
	
if ($v_nao_identificado > 0)
	{
	$v_array[str_pad('Não Identificado',$v_pontos_preenchimento,'.',STR_PAD_RIGHT)] = $v_nao_identificado;				
	}

	
$CreatePie = 0;
$Sort = 0;
// O terceiro parâmetro define a altura do gráfico a ser criado.
// O fator 20 é ideal para que as tabelas tenham o tamanho exato para a matriz enviada.
phPie($v_array, 700 , (count($v_array)+1) * 20, $CenterX, $CenterY, $DiameterX, $DiameterY, $MinDisplayPct, $DisplayColors, $BackgroundColor, $LineColor, true, 3,$CreatePie, $Sort);
?>