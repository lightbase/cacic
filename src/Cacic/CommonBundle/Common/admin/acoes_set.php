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
session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

require_once('../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão
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

// Removo a ação em questão da rede
conecta_bd_cacic();	

$strWhereRedes = '';
if ($_POST['frmRedes_NaoSelecionadas'])
	$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_NaoSelecionadas'].') ' . ($_POST['frmRedes_Selecionadas'] ? ' OR ' : '');

if ($_POST['frmRedes_Selecionadas'])
	$strWhereRedes .= ' id_rede IN ('. $_POST['frmRedes_Selecionadas'].') ';
	
$query  = 'DELETE FROM acoes_redes WHERE id_acao = "'.$_POST['id_acao'].'" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('1-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_redes'))."! ".	$oTranslator->_('kciq_msg session fail',false,true)."!"); 
GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_redes',$_SESSION["id_usuario"]);				
	
// Removo todos os sistemas operacionais associadas à ação em questão.
$query  = 'DELETE FROM acoes_so WHERE id_acao="'.$_POST['id_acao'].'" AND (' . 	$strWhereRedes . ')';
$result = mysql_query($query) or die('3-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'acoes_so',$_SESSION["id_usuario"]);

// Removo todos os mac address associados à ação em questão.
$query = "DELETE FROM acoes_excecoes WHERE 	id_acao='".$_POST['id_acao']."' AND (" . 	$strWhereRedes . ")";
$result = mysql_query($query) or die('5-'.$oTranslator->_('kciq_msg delete row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!"); 
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
		mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_so'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
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
		mysql_query($query) or die('4-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_excecoes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
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
		mysql_query($query) or die('6-'.$oTranslator->_('kciq_msg insert row on table fail', array('acoes_redes'))."! ".$oTranslator->_('kciq_msg session fail',false,true)."!");
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'acoes_redes',$_SESSION["id_usuario"]);
		}
	}		

header ("Location: ../include/operacao_ok.php?chamador=../admin/modulos.php&tempo=1");	
?>