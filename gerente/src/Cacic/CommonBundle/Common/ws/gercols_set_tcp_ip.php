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
	
$queryINS = "INSERT INTO historico_tcp_ip
								   (id_computador,
									te_nome_computador,
									dt_hr_alteracao,
									te_ip_computador,
									te_mascara,
									id_rede,
									te_gateway,
									te_serv_dhcp,
									te_nome_host,
									te_dominio_dns,
									te_dominio_windows,										
									te_dns_primario,
									te_dns_secundario,
									te_wins_primario,
									te_wins_secundario, 
									te_workgroup,
									te_origem_mac)
			VALUES           (" . $arrDadosComputador['id_computador']. ", '" .
									$arrDadosComputador['te_nome_computador'] . "', 
									NOW(), '" .
									$arrDadosComputador['te_ip_computador'] 			. "', '" .
									DeCrypt($key,$iv,$_POST['te_mascara']			,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									$arrDadosRede['id_rede']  																	  . "', '" .
									DeCrypt($key,$iv,$_POST['te_gateway']			,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_serv_dhcp']			,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_nome_host']			,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_dominio_dns']		,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_dns_primario']		,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_dns_secundario']	,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_wins_primario']		,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_wins_secundario']	,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_workgroup']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', '" .
									DeCrypt($key,$iv,$_POST['te_origem_mac']		,$v_cs_cipher, $v_cs_compress,$strPaddingKey) . "')";

// Ele � criado durante a obten��o das configura��es, no script get_config.php.
$queryUPD = "UPDATE 	computadores
			 SET		te_ip_computador   	= '" . $arrDadosComputador['te_ip_computador']		. "',
						te_nome_computador 	= '" . $arrDadosComputador['te_nome_computador'] 	. "',
						te_mascara         	= '" . $arrDadosComputador['te_mascara'] 			. "',
						id_rede         	=  " . $arrDadosComputador['id_rede'] 				. ",
						te_gateway         	= '" . $arrDadosComputador['te_gateway']			. "',
						te_serv_dhcp       	= '" . $arrDadosComputador['te_serv_dhcp']			. "',
						te_nome_host       	= '" . $arrDadosComputador['te_nome_host']			. "',
						te_dominio_windows 	= '" . $arrDadosComputador['te_dominio_windows'] 	. "',
						te_dominio_dns     	= '" . $arrDadosComputador['te_dominio_dns']		. "',
						te_dns_primario    	= '" . $arrDadosComputador['te_dns_primario']		. "',
						te_dns_secundario  	= '" . $arrDadosComputador['te_dns_secundario']		. "',
						te_wins_primario   	= '" . $arrDadosComputador['te_wins_primario']		. "',
						te_wins_secundario 	= '" . $arrDadosComputador['te_wins_secundario']	. "',
						te_workgroup 	   	= '" . $arrDadosComputador['te_workgroup']			. "',
						te_origem_mac	 	= '" . $arrDadosComputador['te_origem_mac']			. "',
			  WHERE 	id_computador      	= '" . $arrDadosComputador['id_computador'];

conecta_bd_cacic();	
$resultINS = mysql_query($queryINS);
$resultUPD = mysql_query($queryUPD);

$strXML_Values .= '<STATUS>OK</STATUS>';		
require_once('../include/common_bottom.php');
?>