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
								 '" . DeCrypt($_POST['id_unid_organizacional_nivel1a'] ,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($_POST['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($_POST['te_info_patrimonio1']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($_POST['te_info_patrimonio2']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($_POST['te_info_patrimonio3']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
								 '" . DeCrypt($_POST['te_info_patrimonio4']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($_POST['te_info_patrimonio5']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								 '" . DeCrypt($_POST['te_info_patrimonio6']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
								  " . DeCrypt($_POST['id_usuario']						,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . ")";
conecta_bd_cacic();	
$result = mysql_query($query);
GravaLog('INS',$_SERVER['SCRIPT_NAME'],'patrimonio',DeCrypt($_POST['id_usuario'],$v_cs_cipher,$v_cs_compress,$strPaddingKey));				

require_once('../include/common_bottom.php');
?>