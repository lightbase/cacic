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

require_once('../include/common_top.php');

$v_tripa_monitorados 		= DeCrypt($key,$iv,$_POST['te_tripa_monitorados'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$v_tripa_nao_achados = ''; //Conter� os registros n�o encontrados na base
$v_conta_tripa_monitorados = 0;

if ($v_tripa_monitorados<>'')
	{	
	
	$query_mon = "SELECT id_aplicativo
				  FROM   aplicativos_monitorados
				  WHERE  id_computador = ".$arrDadosComputador['id_computador'];		  
			  
	conecta_bd_cacic();			  	
	$result_mon = mysql_query($query_mon);

	//Buscar todos os registros deste computador em aplicativos_monitorados.
	//Montar vetor com as informa��es acima.
	//Atualizar os registros existentes e acrescentar os inexistentes.
	$v_array_monitorados = explode("#",$v_tripa_monitorados);
	$v_tripa_codigos_pesquisados = '';
	while ($v_reg_mon = mysql_fetch_array($result_mon))
		{
		if ($v_tripa_codigos_pesquisados <> '') $v_tripa_codigos_pesquisados .= '#';		
		$v_tripa_codigos_pesquisados .= $v_reg_mon['id_aplicativo']; 
		
		for ($v1=0;$v1 < count($v_array_monitorados);$v1 ++)
			{				
			$v_array_itens_monitorados =	explode(",",$v_array_monitorados[$v1]);
			if ($v_array_itens_monitorados[0]==$v_reg_mon['id_aplicativo'])
				{
				$query = "UPDATE aplicativos_monitorados 
								 SET 
								 te_licenca        = '".$v_array_itens_monitorados[1]."',							 
								 cs_instalado      = '".($v_array_itens_monitorados[2]==''?'N':$v_array_itens_monitorados[2])."',  								 
								 te_versao         = '".$v_array_itens_monitorados[3]."',
								 te_ver_engine     = '".$v_array_itens_monitorados[4]."',
								 te_ver_pattern    = '".$v_array_itens_monitorados[5]."'
						  WHERE  id_computador     =  ".$arrDadosComputador['id_computador']." AND 
								 id_aplicativo = ".$v_reg_mon['id_aplicativo'];

				conecta_bd_cacic();			  				
				$result = mysql_query($query);
				$v1 = count($v_array_monitorados);
				}
			}
		}	

	$v_array_codigos_pesquisados = explode('#',str_replace(' ','',$v_tripa_codigos_pesquisados));
	$v_tripa_codigos_monitorados = '';
	for ($v_moni=0;$v_moni < count($v_array_monitorados);$v_moni++)
		{
		$v_array_monitorados_tmp = explode(',',$v_array_monitorados[$v_moni]);
		if ($v_tripa_codigos_monitorados <> '') $v_tripa_codigos_monitorados .= '#';		
		$v_tripa_codigos_monitorados .= $v_array_monitorados_tmp[0]; 			
		}
			
	$v_array_codigos_monitorados = explode('#',str_replace(' ','',$v_tripa_codigos_monitorados));
	$v_array_diferentes = array_diff($v_array_codigos_monitorados,$v_array_codigos_pesquisados);

	$v_tripa_diferentes = implode('#',$v_array_diferentes);
	$v_array_diferentes = explode('#',str_replace(' ','',$v_tripa_diferentes));

	for ($v_dife=0;$v_dife < count($v_array_diferentes);$v_dife++)
		{		
		for ($v_moni=0;$v_moni < count($v_array_monitorados);$v_moni++)
			{
			$v_array_itens_para_insercao =	explode(',',$v_array_monitorados[$v_moni]);						
			if ($v_array_itens_para_insercao[0]==$v_array_diferentes[$v_dife])
				{
				$query = "INSERT INTO 	aplicativos_monitorados 
										(id_computador,
										 id_aplicativo,
										 te_licenca,								 
										 cs_instalado,
										 te_versao,
										 te_ver_engine,
										 te_ver_pattern)											
					  VALUES 		(".$arrDadosComputador['id_computador'].",".
									 $v_array_itens_para_insercao[0].",'".
									 $v_array_itens_para_insercao[1]."','".
									 $v_array_itens_para_insercao[2]."','".
									 $v_array_itens_para_insercao[3]."','".
									 $v_array_itens_para_insercao[4]."','".
									 $v_array_itens_para_insercao[5]."')";

				$result = mysql_query($query);			
				$v_moni = count($v_array_monitorados);
				}
			}
		}
	}

$strXML_Values .= '<STATUS>OK</STATUS>';		
require_once('../include/common_bottom.php');
?>