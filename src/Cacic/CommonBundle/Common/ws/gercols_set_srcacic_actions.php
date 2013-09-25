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