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

// Verifico se o computador em quest�o j� foi inserido anteriormente, e se n�o foi, insiro.
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