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
require_once('../include/common_top.php');

$query = "INSERT INTO patrimonio (	id_computador, 
									dt_hr_alteracao,
									id_unid_organizacional_nivel1a,
									id_unid_organizacional_nivel2,
									te_localizacao_complementar,
									te_info_patrimonio1,
									te_info_patrimonio2,
									te_info_patrimonio3,
									te_info_patrimonio4,
									te_info_patrimonio5,
									te_info_patrimonio6,
									id_usuario)
		  VALUES 				 (" . $arrDadosComputador['id_computador'] . ", 
									NOW()									  ,
								 '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel1a'] ,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($key,$iv,$_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio1']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio2']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio3']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio4']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio5']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio6']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								  " . DeCrypt($key,$iv,$_POST['id_usuario']						,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . ")";
conecta_bd_cacic();	
$result = mysql_query($query);
GravaLog('INS',$_SERVER['SCRIPT_NAME'],'patrimonio',DeCrypt($key,$iv,$_POST['id_usuario'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));				

$strXML_Values .= '<STATUS>' . EnCrypt($key,$iv,'S', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';		
require_once('../include/common_bottom.php');
?>