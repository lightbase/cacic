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

// ********************************************************************************************************************************
// Alteração total no tratamento de informações de componentes de hardware da estação de trabalho - Anderson Peterle - Janeiro/2013
// ********************************************************************************************************************************	
$strCollectType  = DeCrypt($_POST['CollectType'], $v_cs_cipher,$v_cs_compress,$strPaddingKey);

// Defino os dois arrays que conterão as configurações para Coletas, Classes e Propriedades
$arrClassesNames 		= array();
$arrCollectsDefClasses 	= array();
										
// Chamo o procedimento (function) que atribuirá os devidos valores aos arrays acima										
getClassesDefinitions($strCollectType);
										
GravaTESTES('strCollectType: ' . $strCollectType);		
if ($arrCollectsDefClasses[$strCollectType])
	{	
	// Obtenho configuração para notificação de alterações
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
						// ATENÇÃO: Registro já foi criado durante a obtenção das configurações, no script get_config.php.
						$queryUPD = "UPDATE computadores_coletas SET te_class_values = '" . $strNewClassValues . "' WHERE id_computador = " . $arrDadosComputador[0]['id_computador'] . " AND nm_class_name = '" . $strClassName . "'";	
						mysql_query($queryUPD,$DBConnectSC);												
						}
					else
						{
						// ATENÇÃO: Registro já foi criado durante a obtenção das configurações, no script get_config.php.
						$queryINS = "INSERT INTO computadores_coletas(id_computador,nm_class_name,te_class_values) VALUES (" . $arrDadosComputador[0]['id_computador'] . ",'" . $strClassName . "','" . $strNewClassValues ."')";
						mysql_query($queryINS,$DBConnectSC);	
						}																				
					}
				}			
			}			
		}	
	 
	 // Caso a string acima não esteja vazia, monto o email para notificação
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
		$strCorpoMail .= " uma alteração foi identificada no computador cujos detalhes encontram-se abaixo discriminados:\n\n";				
		$strCorpoMail .= " Nome do Host: ". getComponentValue($arrDadosComputador[0]['id_computador'], 'ComputerSystem', 'Caption')  ."\n";
		$strCorpoMail .= " Endereço IP....: ". getComponentValue($arrDadosComputador[0]['id_computador'], 'NetworkAdapterConfiguration', 'IPAddress') . "\n";
		$strCorpoMail .= " Local...............: ". $arrDadosRede[0]['nm_local']."\n";
		$strCorpoMail .= " Rede................: ". $arrDadosRede[0]['nm_rede'] . ' (' . $arrDadosRede[0]['te_ip_rede'] .")\n\n";		
		$strCorpoMail .= $strDeletedItems_Text . $strInsertedItems_Text . $strUpdatedItems_Text;
		$strCorpoMail .= "\n\nPara visualizar mais informações sobre esse computador, acesse o endereço\nhttp://";
		$strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $arrDadosComputador[0]['id_computador'];
		$strCorpoMail .= "\n\n\n________________________________________________\n";
		$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
		$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";
	
		// Manda mail para os administradores.
		mail($resConfigsLocais['te_notificar_mudancas_emails'], "[Sistema CACIC] Alteração Detectada - " . $arrCollectsDefClasses[$strCollectType], "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
		}							
	}
GravaTESTES('Final');																				
$strXML_Values .= '<STATUS>OK</STATUS>';			
require_once('../include/common_bottom.php');	
?>