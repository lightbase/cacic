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
// Perdido? http://www.zend.com/zend/art/parsing.php
// http://www.zend.com/zend/tut/tutbarlach.php

// Definição do nível de compressão (Default = 9 => máximo)
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
	ATENÇÂO: O agente envia as informações serializadas com o seguinte conteúdo:
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