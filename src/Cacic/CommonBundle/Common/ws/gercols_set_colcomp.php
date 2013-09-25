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

$strTripaDados = DeCrypt($key,$iv,$_POST['CompartilhamentosLocais'],$v_cs_cipher, $v_cs_compress, $strPaddingKey);		

// Deleto todos os compartilhamentos desse computador, antes de inserir os atualizados.
$query = "DELETE FROM compartilhamentos WHERE id_computador = " . $arrDadosComputador['id_computador'];
conecta_bd_cacic();
mysql_query($query);

$arrCompartilhamentosLocais = explode('[REG]',$strTripaDados);
$strValues = '';	
// Agora insiro todos os compartilhamentos.
for ($intIndice=0; $intIndice < count($arrCompartilhamentosLocais); $intIndice++)
	{
	/*
	ATENÇÂO: O agente envia as informações serializadas com o seguinte conteúdo:
	nm_compartilhamento + '<FIELD>' +
	nm_dir_compart      + '<FIELD>' +
	cs_tipo_compart     + '<FIELD>' +
	te_comentario       + '<FIELD>' +
	in_senha_leitura    + '<FIELD>' +
	in_senha_escrita    + '<FIELD>' +
	cs_tipo_permissao;
	*/											  
	$arrCamposCompartilhamentosLocais = explode('[FIELD]',$arrCompartilhamentosLocais[$intIndice]);
	$strValues .= ($strValues <> ''?',':'');
	$strValues .= '( '.$arrDadosComputador['id_computador'].','.
					'"'.$arrCamposCompartilhamentosLocais[0].'",'.
					'"'.$arrCamposCompartilhamentosLocais[1].'",'.
					'"'.$arrCamposCompartilhamentosLocais[2].'",'.
					'"'.$arrCamposCompartilhamentosLocais[3].'",'.
					'"'.$arrCamposCompartilhamentosLocais[4].'",'.
					'"'.$arrCamposCompartilhamentosLocais[5].'",'.
					'"'.$arrCamposCompartilhamentosLocais[6].'")';						
	}

if ($strValues <> '')
	{
	$query = "INSERT INTO compartilhamentos 
											(id_computador,
											nm_compartilhamento, 
											nm_dir_compart, 
											cs_tipo_compart,
											te_comentario,
											in_senha_leitura,
											in_senha_escrita, 
											cs_tipo_permissao)
			  VALUES ".$strValues;
	mysql_query($query);
	}

$strXML_Values .= '<STATUS>OK</STATUS>';		
require_once('../include/common_bottom.php');
?>