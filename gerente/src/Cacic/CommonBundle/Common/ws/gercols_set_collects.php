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

// ********************************************************************************************************************************
// Altera��o total no tratamento de informa��es de componentes de hardware da esta��o de trabalho - Anderson Peterle - Janeiro/2013
// ********************************************************************************************************************************	
$strCollectType  = DeCrypt($_POST['CollectType'], $v_cs_cipher,$v_cs_compress,$strPaddingKey);

// Defino os dois arrays que conter�o as configura��es para Coletas, Classes e Propriedades
$arrClassesNames 		= array();
$arrCollectsDefClasses 	= array();
										
// Chamo o procedimento (function) que atribuir� os devidos valores aos arrays acima										
getClassesDefinitions($strCollectType);
										
GravaTESTES('strCollectType: ' . $strCollectType);		
if ($arrCollectsDefClasses[$strCollectType])
	{	
	// Obtenho configura��o para notifica��o de altera��es
	$resConfigsLocais = getValores('configuracoes_locais', 'te_notificar_mudancas_emails,te_notificar_mudancas_properties', 'id_local = '.$arrDadosRede[0]['id_local'].' AND te_notificar_mudancas_emails IS NOT NULL AND te_notificar_mudancas_properties IS NOT NULL');			

GravaTESTES('resConfigsLocais[0][te_notificar_mudancas_properties]: ' . $resConfigsLocais[0]['te_notificar_mudancas_properties']);										
	$arrClassesAndProperties = getValores('classes cl,
					 					   classes_properties cp',
										  'cp.id_property,
										   cp.nm_property_name,
										   cp.te_property_description,
										   cl.nm_class_name',
										  'cp.id_property in (' . $resConfigsLocais[0]['te_notificar_mudancas_properties']. ') AND cl.id_class = cp.id_class');				
	for ($intLoopArrClassesAndProperties = 0; $intLoopArrClassesAndProperties < count($arrClassesAndProperties); $intLoopArrClassesAndProperties++)
		$arrClassesPropertiesToNotificate[$arrClassesAndProperties[$intLoopArrClassesAndProperties]['nm_class_name'] . '.' . $arrClassesAndProperties[$intLoopArrClassesAndProperties]['nm_property_name']] = $arrClassesAndProperties[$intLoopArrClassesAndProperties]['te_property_description'];

GravaTESTES('resConfigsLocais[0][te_notificar_mudancas_properties]: ' . $resConfigsLocais[0]['te_notificar_mudancas_properties']);										
	$strInsertedItems_Text 	= '';
	$strDeletedItems_Text 	= '';
	$strUpdatedItems_Text 	= '';	

	$DBConnectSC = conecta_bd_cacic();
	foreach($HTTP_POST_VARS as $strClassName => $strClassValues)
		{
GravaTESTES('strClassName: ' . $strClassName);												
GravaTESTES('strClassValues: ' . $strClassValues);												
GravaTESTES('arrClassesNames['.$strClassName.']: ' . $arrClassesNames[$strClassName]);												
		if ($arrClassesNames[$strClassName])
			{
			$arrOldClassValues = getValores('computadores_coletas', 'te_class_values', 'nm_class_name = "'.$strClassName.'" AND id_computador = ' . $arrDadosComputador[0]['id_computador']);		
			$strNewClassValues = DeCrypt($strClassValues, $v_cs_cipher,$v_cs_compress,$strPaddingKey);		
GravaTESTES('Entrada 1 -> strNewClassValues: ' . $strNewClassValues);															
GravaTESTES('Entrada 1 -> arrOldClassValues[0][te_class_values]: ' . $arrOldClassValues[0]['te_class_values']);															
			if (($arrOldClassValues[0]['te_class_values'] == '') || ($arrOldClassValues[0]['te_class_values'] <> $strNewClassValues))
				{
GravaTESTES('Entrada 2');																			
				$arrNewTagsNames = getTagsFromValues($strNewClassValues);
GravaTESTES('Entrada 2 - count(arrNewTagsNames): ' . count($arrNewTagsNames));																							
				$arrOldTagsNames = getTagsFromValues($arrOldClassValues[0]['te_class_values']);			
GravaTESTES('Entrada 2 - count(arrOldTagsNames): ' . count($arrOldTagsNames));																								
				$intReferencial = max(count($arrOldTagsNames),count($arrNewTagsNames));
GravaTESTES('Entrada 2 - intReferencial: ' . $intReferencial);																												
				$arrTagsNames   = (count($arrOldTagsNames) > count($arrNewTagsNames) ? $arrOldTagsNames : $arrNewTagsNames);
GravaTESTES('Entrada 2 - count(arrTagsNames): ' . count($arrTagsNames));																								
				for ($intLoop = 0; $intLoop < count($arrTagsNames); $intLoop ++)
					{
GravaTESTES('Entrada 3 - intReferencial: ' . $intReferencial);																																	
					$strOldPropertyValue = getValueFromTags($arrTagsNames[$intLoop],$arrOldClassValues[0]['te_class_values']);
GravaTESTES('Entrada 3 - strOldPropertyValue: ' . $strOldPropertyValue);																																						
					$strNewPropertyValue = getValueFromTags($arrTagsNames[$intLoop],$strNewClassValues);			
GravaTESTES('Entrada 3 - strNewPropertyValue: ' . $strNewPropertyValue);																																						
	
					if ($arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoop]])
						{							
						if 	   ($strNewPropertyValue == '')			
							$strDeletedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoop]] . chr(13);			
						elseif ($strOldPropertyValue == '')
							$strInsertedItems_Text .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoop]] . chr(13);			
						else
							$strUpdatedItems_Text  .= $arrClassesPropertiesToNotificate[$strClassName . '.' . $arrTagsNames[$intLoop]] . chr(13);
						}		

					if ($strOldPropertyValue)	
						{
						$queryINS = "INSERT INTO computadores_coletas_historico(id_computador,nm_class_name,te_class_values,dt_hr_inclusao) VALUES (" . $arrDadosComputador[0]['id_computador'] . ",'" . $strClassName . "','" . $arrOldClassValues[0]['te_class_values'] ."',NOW())";
						mysql_query($queryINS,$DBConnectSC);					
						}
			
					if ($strOldClassValues['te_class_values'])
						{
						// ATEN��O: Registro j� foi criado durante a obten��o das configura��es, no script get_config.php.
						$queryUPD = "UPDATE computadores_coletas SET te_class_values = '" . $strNewClassValues . "' WHERE id_computador = " . $arrDadosComputador[0]['id_computador'] . " AND nm_class_name = '" . $strClassName . "'";	
						mysql_query($queryUPD,$DBConnectSC);												
						}
					else
						{
						// ATEN��O: Registro j� foi criado durante a obten��o das configura��es, no script get_config.php.
						$queryINS = "INSERT INTO computadores_coletas(id_computador,nm_class_name,te_class_values) VALUES (" . $arrDadosComputador[0]['id_computador'] . ",'" . $strClassName . "','" . $strNewClassValues ."')";
						mysql_query($queryINS,$DBConnectSC);	
						}																				
					}
				}			
			}			
		}	
	 
	 // Caso a string acima n�o esteja vazia, monto o email para notifica��o
	 if ($strDeletedItems_Text || $strInsertedItems_Text || $strUpdatedItems_Text ) 
		{ 				
		if ($strDeletedItems_Text)
			$strDeletedItems_Text 	= chr(13) . 'Itens Removidos:' . chr(13) . $strDeletedItems_Text 	. chr(13);
	
		if ($strInsertedItems_Text)
			$strInsertedItems_Text 	= chr(13) . 'Itens Inseridos:' . chr(13) . $strInsertedItems_Text 	. chr(13);
	
		if ($strUpdatedItems_Text)
			$strUpdatedItems_Text 	= chr(13) . 'Itens Alterados:' . chr(13) . $strUpdatedItems_Text 	. chr(13);
			
	
		$strCorpoMail = '';
		$strCorpoMail .= " Prezado administrador,\n\n";
		$strCorpoMail .= " uma altera��o foi identificada no computador cujos detalhes encontram-se abaixo discriminados:\n\n";				
		$strCorpoMail .= " Nome do Host: ". getComponentValue($arrDadosComputador[0]['id_computador'], 'ComputerSystem', 'Caption')  ."\n";
		$strCorpoMail .= " Endere�o IP....: ". getComponentValue($arrDadosComputador[0]['id_computador'], 'NetworkAdapterConfiguration', 'IPAddress') . "\n";
		$strCorpoMail .= " Local...............: ". $arrDadosRede[0]['nm_local']."\n";
		$strCorpoMail .= " Rede................: ". $arrDadosRede[0]['nm_rede'] . ' (' . $arrDadosRede[0]['te_ip_rede'] .")\n\n";		
		$strCorpoMail .= $strDeletedItems_Text . $strInsertedItems_Text . $strUpdatedItems_Text;
		$strCorpoMail .= "\n\nPara visualizar mais informa��es sobre esse computador, acesse o endere�o\nhttp://";
		$strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $arrDadosComputador[0]['id_computador'];
		$strCorpoMail .= "\n\n\n________________________________________________\n";
		$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
		$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";
	
		// Manda mail para os administradores.
		mail($resConfigsLocais['te_notificar_mudancas_emails'], "[Sistema CACIC] Altera��o Detectada - " . $arrCollectsDefClasses[$strCollectType], "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
		}							
	}
GravaTESTES('Final');																				
$strXML_Values .= '<STATUS>OK</STATUS>';			
require_once('../include/common_bottom.php');	
?>