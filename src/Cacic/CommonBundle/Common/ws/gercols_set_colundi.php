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
// Perdido? http://www.zend.com/zend/art/parsing.php
// http://www.zend.com/zend/tut/tutbarlach.php

// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
require_once('../include/common_top.php');

$strTripaDados = DeCrypt($key,$iv,$_POST['UnidadesDiscos'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);		

conecta_bd_cacic();

// Deleto todos os compartilhamentos desse computador, antes de inserir os atualizados.
$query = "	DELETE 	FROM unidades_disco 
			WHERE 	te_node_address = '" . $te_node_address . "'
			AND 	id_so = '" . $arrSO['id_so'] . "'";
mysql_query($query);

// Agora insiro.
//foreach($result as $arr)
$arrUnidadesDiscos = explode('[REG]',$strTripaDados);

$strValues = '';	
// Agora insiro todos os compartilhamentos.
for ($intIndice=0; $intIndice < count($arrUnidadesDiscos); $intIndice++)
	{
	/*
	ATEN��O: O agente envia as informa��es serializadas com o seguinte conte�do:
	te_letra			+ '<FIELD>' +
	id_tipo_unid_disco	+ '<FIELD>' +
	cs_sist_arq			+ '<FIELD>' +
	nu_serial			+ '<FIELD>' +
	nu_capacidade		+ '<FIELD>' +
	nu_espaco_livre		+ '<FIELD>' +
	te_unc				
	*/											  
	$arrCamposUnidadesDiscos = explode('[FIELD]',$arrUnidadesDiscos[$intIndice]);
	$strValues .= ($strValues <> ''?',':'');
	$strValues .= '( "'.$te_node_address.'",'.
					'"'.$arrSO['id_so'].'",'.
					'"'.$arrCamposUnidadesDiscos[0].'",'.
					'"'.$arrCamposUnidadesDiscos[1].'",'.
					'"'.$arrCamposUnidadesDiscos[2].'",'.
					'"'.$arrCamposUnidadesDiscos[3].'",'.
					'"'.$arrCamposUnidadesDiscos[4].'",'.
					'"'.$arrCamposUnidadesDiscos[5].'",'.
					'"'.$arrCamposUnidadesDiscos[6].'")';
					
	}
if ($strValues <> '')
	{
	$query = "INSERT INTO unidades_disco 
						(te_node_address, 
						id_so,
						te_letra,
						id_tipo_unid_disco, 
						cs_sist_arq, 
						nu_serial, 
						nu_capacidade, 
						nu_espaco_livre,
						te_unc) 
				VALUES " . $strValues;
	mysql_query($query);
	}

$strXML_Values .= '<STATUS>OK</STATUS>';			
require_once('../include/common_bottom.php');?>