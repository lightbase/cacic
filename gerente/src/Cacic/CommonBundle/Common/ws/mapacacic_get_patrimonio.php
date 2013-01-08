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

/*
Consulta que devolve as configurações da interface da janela de patrimonio a ser apresentada pelo agente.
=========================================================================================================
*/
$query = 'SELECT 	id_etiqueta, 
					te_etiqueta, 
					in_exibir_etiqueta, 
					te_help_etiqueta 
		  FROM 		patrimonio_config_interface 
		  WHERE		id_local = '.$arrDadosRede['id_local'].' 
		  ORDER BY id_etiqueta';

conecta_bd_cacic();														
$result = mysql_query($query);
	
// ARGHHH!!! - Favor não me julgar somente por isso!   :)  (Anderson Peterle - Domingo à noitinha, ainda em férias!(???))
$bool_1a = false;
$id      = '';
	
$i = 1;
while ($campos = mysql_fetch_array($result))  
	{				
	if ($i == 2 && !$bool_1a)
		{
		$id = '1a';
		$i  = 1;
		$bool_1a = true;
		}
	else
		$id = $i;

	$strXML_Values .= '<te_etiqueta'		. $id . '>'. EnCrypt($key,$iv,$campos["te_etiqueta"]		,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</te_etiqueta'        . $id . '>';
	$strXML_Values .= '<in_exibir_etiqueta' . $id . '>'. EnCrypt($key,$iv,$campos["in_exibir_etiqueta"]	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</in_exibir_etiqueta' . $id . '>';
	$strXML_Values .= '<te_help_etiqueta'   . $id . '>'. EnCrypt($key,$iv,$campos["te_help_etiqueta"]	,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</te_help_etiqueta'   . $id . '>';
	$i++ ;
	}

/*
Consulta que devolve os itens das tabelas de U.O. níveis 1, 1a e 2
==================================================================
*/
$query = '	SELECT 		te_locais_secundarios,
						id_local
         	FROM 		usuarios
	  		WHERE 		id_usuario = '.DeCrypt($key,$iv,$_POST['id_usuario'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$result = mysql_query($query);
$row = mysql_fetch_array($result);

$where = ' AND loc.id_local = '.$row['id_local'];
if ($row['te_locais_secundarios']<>'' && $where <> '')
	{
	// Faço uma inserção de "(" para ajuste da lógica para consulta	
	$where = str_replace(' loc.id_local = ',' (loc.id_local = ',$where);
	$where .= ' OR loc.id_local in ('.$row['te_locais_secundarios'].')) ';
	}

$query = '	SELECT 		uo1.id_unid_organizacional_nivel1  	as uo1_id, 
						uo1.nm_unid_organizacional_nivel1 	as uo1_nm,
						uo1a.id_unid_organizacional_nivel1a as uo1a_id, 
		 				uo1a.nm_unid_organizacional_nivel1a as uo1a_nm,
         				uo2.id_unid_organizacional_nivel2   as uo2_id, 
		 				uo2.nm_unid_organizacional_nivel2   as uo2_nm,
						uo2.id_local						as uo2_id_local,
						loc.sg_local 						as loc_sg
         	FROM 		unid_organizacional_nivel1a uo1a, 
						unid_organizacional_nivel1  uo1,
						unid_organizacional_nivel2  uo2,						
						locais loc
	  		WHERE 		uo1.id_unid_organizacional_nivel1   = uo1a.id_unid_organizacional_nivel1 AND
			            uo1a.id_unid_organizacional_nivel1a = uo2.id_unid_organizacional_nivel1a AND
						uo2.id_local = loc.id_local '.
						$where . '
			ORDER BY 	loc_sg,uo1_nm,uo1a_nm,uo2_nm';
//GravaTESTES($query);		  
$result = mysql_query($query);

$strTripaIdUON1 = '';
$strAux = '';
while ($campos = mysql_fetch_array($result))
	{
	$strAux = '#'.$campos['uo1_id'].'-'.$campos['uo1_nm'].'#';			
	$pos1 = stripos2($strTripaIdUON1,$strAux,false);	
	if (!$pos1)	
		{	
	  	$strXML_Values  .= '<IT1>';
	  	$strXML_Values  .= '<ID1>' . EnCrypt($key,$iv,$campos['uo1_id'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID1>';
	  	$strXML_Values  .= '<NM1>' . EnCrypt($key,$iv,$campos['uo1_nm'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</NM1>';
	  	$strXML_Values  .= '</IT1>'; 		
		$strTripaIdUON1 .= '#'.$campos['uo1_id'].'-'.$campos['uo1_nm'].'#';
		}			
	}

mysql_data_seek($result,0);

$strTripaIdUON1a = '';
$strAux = '';
while ($campos = mysql_fetch_array($result))
	{
		
	$strAux = '#'.$campos['uo1a_id'].'-'.$campos['uo2_id_local'].'#';		
	$pos1 = stripos2($strTripaIdUON1a,$strAux,false);

	if (!$pos1)	
		{
	  	$strXML_Values .= '<IT1a>';
	  	$strXML_Values .= '<ID1>' 		. EnCrypt($key,$iv,$campos['uo1_id']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID1>';
	  	$strXML_Values .= '<SG_LOC>' 	. EnCrypt($key,$iv,$campos['loc_sg']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</SG_LOC>';
	  	$strXML_Values .= '<ID1a>' 		. EnCrypt($key,$iv,$campos['uo1a_id']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID1a>';
	  	$strXML_Values .= '<NM1a>' 		. EnCrypt($key,$iv,$campos['uo1a_nm']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</NM1a>';
		$strXML_Values .= '<ID_LOCAL>' 	. EnCrypt($key,$iv,$campos['uo2_id_local'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_LOCAL>';
		$strXML_Values .= '</IT1a>';

		$strTripaIdUON1a .= '#'.$campos['uo1a_id'].'-'.$campos['uo2_id_local'].'#';
		}		
	}

mysql_data_seek($result,0);
while ($campos = mysql_fetch_array($result))
	{
  	$strXML_Values .= '<IT2>';
  	$strXML_Values .= '<ID1a>' 		. EnCrypt($key,$iv,$campos['uo1a_id']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID1a>';
  	$strXML_Values .= '<ID2>' 		. EnCrypt($key,$iv,$campos['uo2_id']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID2>';
	$strXML_Values .= '<NM2>' 		. EnCrypt($key,$iv,$campos['uo2_nm']	  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</NM2>';
	$strXML_Values .= '<ID_LOCAL>' 	. EnCrypt($key,$iv,$campos['uo2_id_local'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_LOCAL>';
  	$strXML_Values .= '</IT2>';
	}

// Envio os valores já existentes no banco, referentes ao ID_SO+TE_NODE_ADDRESS da estação chamadora...
$query = '	SELECT 		pat.id_unid_organizacional_nivel1a, 
		 	       		pat.id_unid_organizacional_nivel2,
				   		pat.te_localizacao_complementar,
				   		pat.te_info_patrimonio1,
				   		pat.te_info_patrimonio2,
				   		pat.te_info_patrimonio3,
				   		pat.te_info_patrimonio4,
				   		pat.te_info_patrimonio5,
				   		pat.te_info_patrimonio6,
				   		pat.dt_hr_alteracao,
						uo2.id_local
         	FROM   		patrimonio pat,
						unid_organizacional_nivel2 uo2
	  		WHERE  		id_computador= '.$arrDadosComputador['id_computador'].' AND
			            uo2.id_unid_organizacional_nivel2 = pat.id_unid_organizacional_nivel2
			ORDER  BY 	pat.dt_hr_alteracao DESC LIMIT 1';
$result = mysql_query($query);

if (count($result)>0)
	{
	$valores = mysql_fetch_array($result);
	$strXML_Values .= '<ID_UON1a>'			. EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_UON1a>';		
	$strXML_Values .= '<ID_UON2>'			. EnCrypt($key,$iv,$valores['id_unid_organizacional_nivel2'] ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_UON2>';		
	$strXML_Values .= '<ID_LOCAL>'			. EnCrypt($key,$iv,$valores['id_local']						 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</ID_LOCAL>';			
	$strXML_Values .= '<TE_LOC_COMPL>'		. EnCrypt($key,$iv,$valores['te_localizacao_complementar']	 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_LOC_COMPL>';			
	$strXML_Values .= '<TE_INFO1>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio1']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO1>';				
	$strXML_Values .= '<TE_INFO2>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio2']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO2>';				
	$strXML_Values .= '<TE_INFO3>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio3']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO3>';				
	$strXML_Values .= '<TE_INFO4>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio4']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO4>';				
	$strXML_Values .= '<TE_INFO5>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio5']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO5>';				
	$strXML_Values .= '<TE_INFO6>'			. EnCrypt($key,$iv,$valores['te_info_patrimonio6']			 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</TE_INFO6>';									
	$strXML_Values .= '<DT_HR_ALTERACAO>'	. EnCrypt($key,$iv,$valores['dt_hr_alteracao']				 ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</DT_HR_ALTERACAO>';										
	}

$strXML_Values .= '<STATUS>' . EnCrypt($key,$iv,'S', $v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';		
require_once('../include/common_bottom.php');
?>