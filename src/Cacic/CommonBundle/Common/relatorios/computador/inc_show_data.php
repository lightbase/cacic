<?php
// Parte de script para exibição de dados de vários Collects Types - Anderson PETERLE - Jan/2013

if (count($arrCollectsDefClasses) > 0)
	{	
	$arrParameters = array();	
	for ($intLoopArrClassesNames = 0; $intLoopArrClassesNames < count($arrClassesNames); $intLoopArrClassesNames++)
		{
		$keyArrClassesNames = key($arrClassesNames);			
		$arrProperties = getValores('classes cl,classes_properties cp', 'cp.id_property,cp.nm_property_name,cp.te_property_description,cp.nm_function_pos_db', 'cl.nm_class_name = "' . $keyArrClassesNames . '" AND cp.id_class = cl.id_class Order by cp.nm_property_name');		
	
		// 0 -> nm_property_name
		// 1 -> te_property_description
		
		for ($intLoopArrProperties = 0; $intLoopArrProperties < count($arrProperties); $intLoopArrProperties++)
			{
			// Para buscar o valor só me basta o nome da Property (nm_property_name)
			$keyArrPropertiesInstances = key($arrProperties[$intLoopArrProperties]);
			$arrInstances 		= explode('[[REG]]',getComponentValue($intIdComputador,$keyArrClassesNames,$arrProperties[$intLoopArrProperties][$keyArrPropertiesInstances]));
						
			$intLenArrInstances = count($arrInstances);	
			for ($intLoopInstances = 0; $intLoopInstances < count($arrInstances); $intLoopInstances++)						
				{
				// Monto variáveis contendo os nomes conforme as instances
				$strPropertyValue  = 'str' . $keyArrClassesNames . '_' . $arrProperties[$intLoopArrProperties][$keyArrPropertiesInstances] . '_' . ($intLoopInstances + 1);						
				echo 'strPropertyValue: '.$strPropertyValue.'<br>';
				
				// Atribuo os valores usando a variável do nome acima (variável variável)
				$$strPropertyValue = $arrInstances[$intLoopInstances];
				echo 'sstrPropertyValue: '.$$strPropertyValue.'<br>';				
				}
			next($arrProperties[$intLoopArrProperties]);
			}				

		for ($intLoopInstances = 0; $intLoopInstances < $intLenArrInstances; $intLoopInstances++)
			{
			if ($intLoopInstances > 0)
				{
				?>
				<tr><td colspan="4" height="3" class="opcao_tabela_blue" align="center"></td></tr>                                                            
                <?php
				}
			for ($intLoopArrProperties = 0; $intLoopArrProperties < count($arrProperties); $intLoopArrProperties++)
				{
				// Monto o nome da variável da 1a coluna e resgato o valor usando variável variável
				$strPropertyValue  = 'str' . $keyArrClassesNames . '_' . $arrProperties[$intLoopArrProperties]['nm_property_name'] . '_' . ($intLoopInstances + 1);					
				if ($intLoopInstances==0 && $intLoopArrProperties == 0 && count($arrClassesNames) > 1)
					{
					$strCor = $strPreenchimentoPadrao;												
					if ($intLoopArrClassesNames > 0)
						{
						?>
						<tr><td colspan="4" class="opcao_tabela_blue" align="center">&nbsp;</td></tr>                                            
                        <?php
						}
						?>
					<tr bgcolor="<?php echo $strCor;?>"><td colspan="4" class="opcao_tabela_black_bold" align="left"><u><?php echo $oTranslator->_($arrClassesNames[$keyArrClassesNames]) . ($intLenArrInstances > 1 ? '  (' . $intLenArrInstances . ' Ítens)' : '') ;?></u></td></tr>                    
                    <?php						
					}
				if  ($intLenArrInstances > 1 && $intLoopArrProperties == 0 && count($arrClassesNames) > 1)
					{
					?>
					<tr><td colspan="4" class="opcao_tabela" align="center">Ítem <?php echo ($intLoopInstances + 1) . '/' . ($intLenArrInstances);?></td></tr>
                    <?php
					}
				$strValueToShow = $$strPropertyValue;
				if ($arrProperties[$intLoopArrProperties]['nm_function_pos_db'])
					{
					$strNmFunctionPosDB = $arrProperties[$intLoopArrProperties]['nm_function_pos_db'];
					$strNmFunctionPosDB = substr($strNmFunctionPosDB,0,strpos($strNmFunctionPosDB,'('));
					
					$strTeParameter1 = '';
					$strTeParameter2 = '';
					$strTeParameter3 = '';										
					$strTeParameter4 = '';										
					$strTeParameter5 = '';																				
					if (stripos2($arrProperties[$intLoopArrProperties]['nm_function_pos_db'],'(',false))
						{
						$strTeParametersTMP = substr($arrProperties[$intLoopArrProperties]['nm_function_pos_db'],strpos($arrProperties[$intLoopArrProperties]['nm_function_pos_db'],'('));
						$strTeParametersTMP = str_replace(')','',str_replace('(','',$strTeParametersTMP));
						$arrParametersTMP 	= explode(',',$strTeParametersTMP);

						for ($intLoopArrParametersTMP = 0; $intLoopArrParametersTMP < count($arrParametersTMP); $intLoopArrParametersTMP++)
							{
							$strVarName  = 'strTeParameter' . ($intLoopArrParametersTMP + 1);
							$$strVarName = $arrParametersTMP[$intLoopArrParametersTMP];
							}
						}
					$arrParameters = (object) array('id_property' 		=> $arrProperties[$intLoopArrProperties]['id_property'],
													'te_property_value' => $strValueToShow,
													'te_parameter_1' 	=> $strTeParameter1,
													'te_parameter_2' 	=> $strTeParameter2,
													'te_parameter_3' 	=> $strTeParameter3,
													'te_parameter_4' 	=> $strTeParameter4,
													'te_parameter_5' 	=> $strTeParameter5);																																																																	

					
					$strValueToShow = call_user_func_array($strNmFunctionPosDB,array($arrParameters));
					}
				?>                
				<tr bgcolor="<?php echo $strCor;?>"> 
				<td nowrap="nowrap" align="right"><div class="propertyDescription" id="TePropertyDescription" name="TePropertyDescription"><?php echo $oTranslator->_($arrProperties[$intLoopArrProperties]['te_property_description']);?>:</div></td>
                <td><div class="propertyValue" 		 id="TePropertyValue" 		name="TePropertyValue"		><?php echo $strValueToShow; ?></div></td>                
                <td colspan="2"></td>
                </tr>                        				
				<?php
				}
			}
		next($arrClassesNames);					
		}
	
	// Liberação das variáveis array	
	unset($arrProperties);
	unset($arrInstances);
	unset($arrClassesNames);
	unset($arrCollectsDefClasses);
	
	?> 
	<tr> 
	<td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank"> 
	<div align="center"><br>
  	<input name="historico" type=submit id="historico" value="<?php echo $oTranslator->_('Historico de Alteracoes');?>" >
	<br>
	&nbsp; 
	<input name="id_computador" type="hidden" id="id_computador" value="<?php echo $intIdComputador;?>">          
	</div>
	</form></td>
  	</tr>
  	<?php		
	}
else 
	echo '<tr><td><div align="center"><font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">'.
		$oTranslator->_('Este modulo de coletas nao foi habilitado pelo Administrador').'</font></div></td></tr>';
?>			