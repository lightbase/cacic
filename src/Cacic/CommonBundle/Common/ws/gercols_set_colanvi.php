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

// Verifico se o computador em questão já foi inserido anteriormente, e se não foi, insiro.
$query = "SELECT count(*) as num_registros
		  FROM officescan
		  WHERE id_computador = " . $arrDadosComputador['id_computador'];
conecta_bd_cacic();			  
$result = mysql_query($query);
if (mysql_result($result, 0, "num_registros") == 0) 
	{
	$query = "INSERT INTO officescan(id_computador)
			  VALUES (" . $arrDadosComputador['id_computador'];
	$result = mysql_query($query);
	} 

$query = "UPDATE officescan 
		  SET 	nu_versao_engine 	= '" . DeCrypt($key,$iv,$_POST['nu_versao_engine']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
				nu_versao_pattern   = '" . DeCrypt($key,$iv,$_POST['nu_versao_pattern']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
				dt_hr_coleta        = NOW(),
				dt_hr_instalacao    = '" . DeCrypt($key,$iv,$_POST['dt_hr_instalacao']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
				te_servidor         = '" . DeCrypt($key,$iv,$_POST['te_servidor']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
				in_ativo            = '" . DeCrypt($key,$iv,$_POST['in_ativo']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "' 
		  WHERE id_computador      	= "  . $arrDadosComputador['id_computador'];
$result = mysql_query($query);

$strXML_Values .= '<STATUS>OK</STATUS>';		
require_once('../include/common_bottom.php');
?>