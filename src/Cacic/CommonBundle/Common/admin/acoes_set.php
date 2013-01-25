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
session_start();
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administra��o
// 2 - Gest�o Central
// 3 - Supervis�o
/*
LimpaTESTES();
foreach($HTTP_POST_VARS as $i => $v) 
	{
	GravaTESTES('Em acoes_set => I : '.$i);
	GravaTESTES('Em acoes_set => V : '.$v);	
	GravaTESTES('*********************************');		
	}
*/
//$_POST['frmSistemasOperacionais'];
//$_POST['frmRedes'];
//$_POST['frmEnderecosMac'];

// Removo a a��o em quest�o da rede
conecta_bd_cacic();	

$strWhereRedes = '';
if ($_POST['frmRedes_NaoSelecionadas'])
	$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_NaoSelecionadas'].') ' . ($_POST['frmRedes_Selecionadas'] ? ' OR ' : '');

if ($_POST['frmRedes_Selecionadas'])
	$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_Selecionadas'].') ';

// Para garantir, removo as a��es incondicionais para posterior inser��o
// ---------------------------------------------------------------------
$query  = 'DELETE FROM acoes_redes WHERE id_acao = "col_env_not_optional" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('1-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 

$query  = 'DELETE FROM acoes_redes WHERE id_acao = "col_soft_not_optional" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('1-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 

$query  = 'DELETE FROM acoes_so WHERE id_acao = "col_env_not_optional" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('3-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_so'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 

$query  = 'DELETE FROM acoes_so WHERE id_acao = "col_soft_not_optional" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_so'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 

// ---------------------------------------------------------------------
	
$query  = 'DELETE FROM acoes_redes WHERE id_acao = "'.$_POST['id_acao'].'" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 
GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes',$_SESSION["id_usuario"]);				
	
// Removo todos os sistemas operacionais associadas � a��o em quest�o.
$query  = 'DELETE FROM acoes_so WHERE id_acao="'.$_POST['id_acao'].'" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_so',$_SESSION["id_usuario"]);

// Removo todos os mac address associados � a��o em quest�o.
$query = "DELETE FROM acoes_excecoes WHERE 	id_acao='".$_POST['id_acao']."' AND (" . 	$strWhereRedes . ")";
$result = mysql_query($query) or die('7-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_excecoes',$_SESSION["id_usuario"]);
	
if (trim($_POST['frmRedes_Selecionadas']))
	{
	$arrRedes_Selecionadas = explode(',',$_POST['frmRedes_Selecionadas']);	
	$arrSO_Selecionados	   = explode(',',$_POST['frmSO_Selecionados']);
	$arrMAC_Selecionados   = explode(',',$_POST['frmMAC_Selecionados']);
	
	$strValues = '';
	for ($intRedes = 0; $intRedes < count($arrRedes_Selecionadas); $intRedes ++)
		for ($intSO = 0; $intSO < count($arrSO_Selecionados); $intSO ++)
			{
			$strValues .= ($strValues ? ',' : '');
			$strValues .= '(' . $arrSO_Selecionados[$intSO] . ',"' . $_POST['id_acao'] . '",' . $arrRedes_Selecionadas[$intRedes] . ')';
			}
	if ($strValues)
		{	
		$query = "INSERT INTO acoes_so (id_so, id_acao, id_rede)  VALUES " . $strValues;
		mysql_query($query) or die('8-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_so',$_SESSION["id_usuario"]);					
		}
	
	$strValues = '';
	for ($intRedes = 0; $intRedes < count($arrRedes_Selecionadas); $intRedes ++)
		for ($intMAC = 0; $intMAC < count($arrMAC_Selecionados); $intMAC ++)
			{
			$strValues .= ($strValues ? ',' : '');
			$strValues .= '("' . $arrMAC_Selecionados[$intMAC] . '","' . $_POST['id_acao'] . '",' . $arrRedes_Selecionadas[$intRedes] . ')';
			}
	if ($strValues)
		{	
		$query = "INSERT INTO acoes_excecoes (te_node_address, id_acao, id_rede)  VALUES " . $strValues;
		mysql_query($query) or die('9-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_excecoes',$_SESSION["id_usuario"]);					
		}
	
	$strValues = '';
	for ($intRedes = 0; $intRedes < count($arrRedes_Selecionadas); $intRedes ++)
		{
		$strValues .= ($strValues ? ',' : '');
		$strValues .= '(' . $arrRedes_Selecionadas[$intRedes] . ',"' . $_POST['id_acao'] . '")';
		}
	if ($strValues)
		{	
		$query = "INSERT INTO acoes_redes (id_rede,id_acao) VALUES " . $strValues;
		mysql_query($query) or die('10-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes',$_SESSION["id_usuario"]);
		}
		
	// Insiro as a��es n�o opcionais
	// -----------------------------
	$strValues = '';
	for ($intRedes = 0; $intRedes < count($arrRedes_Selecionadas); $intRedes ++)
		{
		$strValues .= ($strValues ? ',' : '');
		$strValues .= '(' . $arrRedes_Selecionadas[$intRedes] . ',"col_env_not_optional"),';
		$strValues .= '(' . $arrRedes_Selecionadas[$intRedes] . ',"col_soft_not_optional")';		
		}
	$query = "INSERT INTO acoes_redes (id_rede,id_acao) VALUES " . $strValues;
	mysql_query($query) or die('11-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
	
	$strValues = '';
	for ($intRedes = 0; $intRedes < count($arrRedes_Selecionadas); $intRedes ++)
		for ($intSO = 0; $intSO < count($arrSO_Selecionados); $intSO ++)
			{
			$strValues .= ($strValues ? ',' : '');
			$strValues .= '(' . $arrSO_Selecionados[$intSO] . ',"col_env_not_optional",' . $arrRedes_Selecionadas[$intRedes] . '),';
			$strValues .= '(' . $arrSO_Selecionados[$intSO] . ',"col_soft_not_optional",' . $arrRedes_Selecionadas[$intRedes] . ')';
			}
	if ($strValues)
		{	
		$query = "INSERT INTO acoes_so (id_so, id_acao, id_rede)  VALUES " . $strValues;
		mysql_query($query) or die('12-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		}	
	// ---------------------------------------------------------------------
		
	}		

header ("Location: ../include/operacao_ok.php?chamador=../admin/modulos.php&tempo=1");	
?>