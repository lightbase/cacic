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

$te_rcactions 		 = DeCrypt($_POST['te_rcactions'],$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
if ($te_rcactions <> '')
	{	
	$arrRCActionsRecords = explode('[REG]',$te_rcactions);		
	$strQuery = '';
	for ($indexRecords = 0; $indexRecords < count($arrRCActionsRecords); $indexRecords++)
		{
		$arrRCActionsFields = explode('[FIELD]',$arrRCActionsRecords[$indexRecords]);		
		$strQuery .= ($strQuery <> '' ? ',' : '');
		$strQuery .= "( " . $arrRCActionsFields[0] . ",
					   '" . $arrRCActionsFields[1] . "',
					   '" . $arrRCActionsFields[2] . "',
					   '" . $arrRCActionsFields[3] . "',
					   '" . $arrRCActionsFields[4] . "',
						" . $arrRCActionsFields[5] . ")";
		}
		
	if ($strQuery)
		{
		$query = "INSERT INTO srcacic_actions (id_conexao,
											   dt_hr_action,
											   te_action,
											   te_param1,
											   te_param2,
											   te_flag)
				VALUES           		      ".$strQuery;
		//GravaTESTES($query);																				
		$result = mysql_query($query);		
		}
	$strXML_Values .= '<STATUS>OK</STATUS>';		
	}
else
	$strXML_Values .= '<STATUS>' . EnCrypt('Registro de Acoes VAZIO!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey) . '</STATUS>';		

require_once('../include/common_bottom.php');
?>