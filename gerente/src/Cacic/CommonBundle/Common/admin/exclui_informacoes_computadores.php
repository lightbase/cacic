<?php
 /*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e InformaÃ§Ãµes da PrevidÃªncia Social, Brasil

 Este arquivo Ã© parte do programa CACIC - Configurador AutomÃ¡tico e Coletor de InformaÃ§Ãµes Computacionais

 O CACIC Ã© um software livre; vocÃª pode redistribui-lo e/ou modifica-lo dentro dos termos da LicenÃ§a PÃºblica Geral GNU como
 publicada pela FundaÃ§Ã£o do Software Livre (FSF); na versÃ£o 2 da LicenÃ§a, ou (na sua opniÃ£o) qualquer versÃ£o.

 Este programa Ã© distribuido na esperanÃ§a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÃ‡Ã‚O a qualquer
 MERCADO ou APLICAÃ‡ÃƒO EM PARTICULAR. Veja a LicenÃ§a PÃºblica Geral GNU para maiores detalhes.

 VocÃª deve ter recebido uma cÃ³pia da LicenÃ§a PÃºblica Geral GNU, sob o tÃ­tulo "LICENCA.txt", junto com este programa, se nÃ£o, escreva para a FundaÃ§Ã£o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
session_start(); 
/*
 * verifica se houve login e tambÃ©m regras para outras verificaÃ§Ãµes (ex: permissÃµes do usuÃ¡rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificaÃ§Ãµes (ex: permissÃµes do usuÃ¡rio)!
}

require_once('../include/library.php'); 
AntiSpy('1,3'); // Permitido somente a estes cs_nivel_administracao...

$boolAdmin = ($_SESSION['cs_nivel_administracao'] == 1 || $_SESSION['cs_nivel_administracao'] == 2);
// 1 - AdministraÃ§Ã£o
// 2 - GestÃ£o Central
// 3 - SupervisÃ£o

conecta_bd_cacic();

if ($_POST['submit_cond'])
	{
	$query_sele_exclui = '';
	$arrClassesAndPropertiesToSearch  = array();
	
	while(list($key, $value) = each($HTTP_POST_VARS))
		{	
		if (trim($value)<>'' && trim(strpos($key,'frm_'))<>'') 
			{
			if (stripos2($key,'_DB',false))
				{
				if (stripos2($key,'frm_condicao_',false))				
					{
					if ($query_sele_exclui) $query_sele_exclui .= ' and ';
					$query_sele_exclui .= str_replace('frm_condicao_DB_','',$value);
					}
				else
					$query_sele_exclui = str_replace('frm_te_valor_condicao',$value,$query_sele_exclui);
				}
			else
				{
				if (stripos2($key,'frm_condicao_',false))				
					{
					$strClassAndPropertyName = str_replace('frm_condicao_','',$key);
					$strClassAndPropertyName = str_replace('_','.',$strClassAndPropertyName);					
					$arrClassesAndPropertiesToSearch[$strClassAndPropertyName]= str_replace($strClassAndPropertyName,'',$value);
					}
				else
					{
					$arrClassesAndPropertiesToSearch[$strClassAndPropertyName] = str_replace('frm_te_valor_condicao',$value,$arrClassesAndPropertiesToSearch[$strClassAndPropertyName]);	
					$arrClassesAndPropertiesToSearch[$strClassAndPropertyName] = str_replace('[[AS]]',"'",$arrClassesAndPropertiesToSearch[$strClassAndPropertyName]);						
					}				
				}
			}
		} 

	if ($query_sele_exclui)
		{
		$query_sele_exclui = (substr($query_sele_exclui,-5)==' and '?substr($query_sele_exclui,0,strlen($query_sele_exclui)-5):$query_sele_exclui);
		$query_sele_exclui = str_replace('-MENOR-'					,' < '	,$query_sele_exclui);
		$query_sele_exclui = str_replace('-MAIOR-'					,' > '	,$query_sele_exclui);	
		$query_sele_exclui = str_replace('DB.'	  					,''		,$query_sele_exclui);		
		$query_sele_exclui = str_replace('__'	  					,'.'	,$query_sele_exclui);	
		$query_sele_exclui = str_replace('frm_te_valor_condicao'	,''		,$query_sele_exclui);		
		$query_sele_exclui = str_replace('\\'						,''		,$query_sele_exclui);					
		$query_sele_exclui = str_replace("[[AS]]"					,"'"	,$query_sele_exclui);					
		$query_sele_exclui = ' AND ' . $query_sele_exclui;					
		}

	if (!$boolAdmin && ($_SESSION['te_locais_secundarios']<>'' && $where <> ''))
		$where .= ' OR redes.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';

	$where .= (!$boolAdmin ? ' AND redes.id_local = '.$_SESSION['id_local']:'');				
	$where .= (!$boolAdmin ? ' AND computadores.dt_hr_exclusao IS NULL':'');					
	// Arrays conforme colunas que ser�o exibidas na tabela de resultados
	$arrNomeMaquina 		= array();
	$arrLocal				= array();
	$arrEnderecoIP			= array();
	$arrEnderecoMAC 		= array();
	$arrSistemaOperacional 	= array();
	$arrAgentePrincipal		= array();
	$arrGerCols				= array();
	$arrUltimoAcesso		= array();
	$arrInclusao			= array();
	
	// Array chave
	$arrIdComputador		= array();

	$Query_Pesquisa = 'SELECT 	computadores.id_so,
								computadores.te_node_address,
								computadores.te_versao_cacic, 
								computadores.te_versao_gercols, 
								computadores.dt_hr_ult_acesso,
								computadores.dt_hr_inclusao,								
								computadores.dt_hr_exclusao,																
								computadores.id_usuario_exclusao,																								
								so.sg_so,
								redes.id_local,
								redes.nm_rede,								
								computadores.id_computador
						FROM	so,
								computadores,								
								redes
						WHERE   computadores.id_rede = redes.id_rede AND 
								computadores.id_so = so.id_so '.$query_sele_exclui.
								$where . ' 
						ORDER 	by computadores.dt_hr_ult_acesso,redes.nm_rede,so.sg_so';
	//echo 'IP: ' . $_SERVER['REMOTE_ADDRESS'].'<BR>';
	//echo 'Query: '.$Query_Pesquisa.'<br>';
						
	$result = mysql_query($Query_Pesquisa) or die('Erro no select (1) ou sua sessÃ£o expirou!');

	$strIdLocal 				= '';
	$strIdUsuario 				= '';	
	$arrSgLocal 				= array();
	$intTotalMicrosSelecionados	= 0;	
	
	// Inicializando arrays containers de informa��es
	$arrIdComputador 		= array();
	$arrLocal				= array();
	$arrEnderecoIP			= array();
	$arrEnderecoMAC			= array();
	$arrSistemaOperacional	= array();
	$arrAgentePrincipal		= array();
	$arrGerCols				= array();
	$arrUltimoAcesso		= array();
	$arrInclusao			= array();
	$arrExclusao			= array();
	$arrUsuarioExclusao		= array();			
	
	while($row = mysql_fetch_array($result)) 
		{		  
		if ($row['id_local']<>'' && !$arrSgLocal[$row['id_local']])
			{
			$arrSgLocal[$row['id_local']] = '*';
			$strIdLocal .= ($strIdLocal ? ',' : '');
			$strIdLocal .= $row['id_local'];
			}			

		if ($row['id_usuario_exclusao'] <> '' && $row['id_usuario_exclusao'] <> 'NULL' && !$arrUsuarios[$row['id_usuario_exclusao']])
			{
			$arrUsuarios[$row['id_usuario_exclusao']] = '*';
			$strIdUsuario .= ($strIdUsuario ? ',' : '');
			$strIdUsuario .= $row['id_usuario_exclusao'];
			}			

		array_push($arrIdComputador,$row['id_computador']);

		$arrLocal[$row['id_computador']] 				= $row['id_local'];
		$arrEnderecoIP[$row['id_computador']] 			= '';
		$arrEnderecoMAC[$row['id_computador']] 			= $row['te_node_address'];
		$arrSistemaOperacional[$row['id_computador']] 	= $row['sg_so'];
		$arrAgentePrincipal[$row['id_computador']] 		= $row['te_versao_cacic'];
		$arrGerCols[$row['id_computador']] 				= $row['te_versao_gercols'];
		$arrUltimoAcesso[$row['id_computador']] 		= date("d/m/y H:i", strtotime($row['dt_hr_ult_acesso']));	
		$arrInclusao[$row['id_computador']] 			= date("d/m/y H:i", strtotime($row['dt_hr_inclusao']));			
		$arrExclusao[$row['id_computador']] 			= ($row['dt_hr_exclusao'] <> 'NULL' && $row['dt_hr_exclusao'] <> '' ? date("d/m/y H:i", strtotime($row['dt_hr_exclusao'])) : '');					
		$arrUsuarioExclusao[$row['id_computador']] 		= $row['id_usuario_exclusao'];									
		}

	if ($strIdUsuario <> '')
		{
		$strQueryUsuarios = 'SELECT usuarios.id_usuario,
									usuarios.nm_usuario_acesso,
									usuarios.nm_usuario_completo
							 FROM	usuarios
							 WHERE  usuarios.id_usuario in ('.$strIdUsuario.')';
		$resultUsuarios = mysql_query($strQueryUsuarios) or die('Erro no select (2) ou sua sess�o expirou!');		
		while($rowUsuarios = mysql_fetch_array($resultUsuarios)) 
			$arrUsuarios[$rowUsuarios['id_usuario']] = PrimUltNome($rowUsuarios['nm_usuario_completo']) . ' (' . $rowUsuarios['nm_usuario_acesso'] . ')';						
		}

	if ($strIdLocal <> '')
		{
		$strQueryLocais = 'SELECT 	locais.id_local,
									locais.sg_local
						 FROM		locais
						 WHERE   	locais.id_local in ('.$strIdLocal.')';

		$resultLocais = mysql_query($strQueryLocais) or die('Erro no select (3) ou sua sess�o expirou!');		
		while($rowLocais = mysql_fetch_array($resultLocais)) 
			$arrSgLocal[$rowLocais['id_local']] = $rowLocais['sg_local'];						
		}
		
	reset($arrClassesAndPropertiesToSearch);

	// Ser� usado na limpeza dos valores recebidos
	$tstrToRemove = array(	'not like '	,
							'' 			,
							'like '		,
							'()'		,
							'%'			,
							'"'			,
							"'"			,
							'= '		,
							'-MAIOR- '	,
							'-MENOR- '	,
							'<> ');

	function getOnlyValueToCompare($pStrValueToProcess, $pStrToExcept = '')
		{
		global $tstrToRemove;
																		
		$strValueToReturn = $pStrValueToProcess;
				
		for ($intLoopToRemove = 0; $intLoopToRemove < count($tstrToRemove);$intLoopToRemove ++)
			{
			if ($pStrToExcept)
				$strValueToReturn = ($tstrToRemove[$intLoopToRemove] <> $pStrToExcept ? str_replace($tstrToRemove[$intLoopToRemove]	, '' , $strValueToReturn) : $strValueToReturn);
			else
				$strValueToReturn = str_replace($tstrToRemove[$intLoopToRemove]	, '' , $strValueToReturn);
			}

		return trim($strValueToReturn);
		}

	$intLoop 			 = 0;
	$intTotalSelecionado = 0;	
	reset($arrIdComputador);	
	while($intLoop < count($arrIdComputador)) 
		{		  				
		$boolFilterOK = true;
		
		$intLoopArrClassesAndPropertiesToSearch = 0;
		while ($intLoopArrClassesAndPropertiesToSearch < count($arrClassesAndPropertiesToSearch) && $boolFilterOK)						
			{
			$key 			   = key($arrClassesAndPropertiesToSearch);		
			$strComponentValue = getComponentValue($arrIdComputador[$intLoop], substr($key,0,stripos2($key, '.', true)), substr($key,stripos2($key, '.', true)+1));
			if (stripos2($arrClassesAndPropertiesToSearch[$key], 'not like ',false))
				$boolFilterOK 		= !stripos2($strComponentValue, getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]),false); // N�O CONTENHA
			elseif (stripos2($arrClassesAndPropertiesToSearch[$key], 'like ',false))
				{
				$intTotalPercents  = substr_count($arrClassesAndPropertiesToSearch[$key],'%');								
				if ($intTotalPercents == 2) // CONTENHA
					$boolFilterOK = stripos2($strComponentValue, getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]),false);
				else
					{
					$intPos = stripos2(getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key],'%'),'%');
					$intLen = strlen(getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]));					

					if ($intPos == 0)
						$boolFilterOK = (substr($strComponentValue,($intLen - (2 * $intLen))) == getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key])); // TERMINE COM
					else
						$boolFilterOK = (substr($strComponentValue,0,$intLen) == getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key])); // INICIE COM						
					}				
				}
			elseif (stripos2($arrClassesAndPropertiesToSearch[$key], '= ',false))
				$boolFilterOK = (getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]) == $strComponentValue); 	// � IGUAL A
			elseif (stripos2($arrClassesAndPropertiesToSearch[$key], '-MAIOR- ',false))
				$boolFilterOK = (getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]) < $strComponentValue);	// � MAIOR QUE
			elseif (stripos2($arrClassesAndPropertiesToSearch[$key], '-MENOR- ',false))
				$boolFilterOK = (getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]) > $strComponentValue);	// � MENOR QUE
			elseif (stripos2($arrClassesAndPropertiesToSearch[$key], '<> ',false))
				$boolFilterOK = (getOnlyValueToCompare($arrClassesAndPropertiesToSearch[$key]) <> $strComponentValue);	// � DIFERENTE DE
			else
				$boolFilterOK = ($strComponentValue == '');														// SEJA NULO OU VAZIO

			next($arrClassesAndPropertiesToSearch);
			$intLoopArrClassesAndPropertiesToSearch ++;
			}

		if (!$boolFilterOK)	
			{
			$arrIdComputador[$intLoop]	= '';
			$arrLocal					= null;
			$arrEnderecoIP				= null;
			$arrEnderecoMAC				= null;
			$arrSistemaOperacional		= null;
			$arrAgentePrincipal			= null;
			$arrGerCols					= null;
			$arrUltimoAcesso			= null;
			$arrInclusao				= null;
			$arrExclusao				= null;			
			$arrUsuarioExclusao			= null;			
			}
		else
			$intTotalSelecionado ++;
	
		$intLoop ++;
		}	

	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
	<title><?php echo $oTranslator->_('Excluir Computadores');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">	
	<link href="../include/css/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Verifica_Check_Exclui()
		{
		var v_total_check;
		v_total_check = 0;
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'checkbox' && window.document.forms[i].elements[j].checked == true)
					{
					v_total_check ++;
					}
				}
			}		

		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].type == 'submit' && window.document.forms[i].elements[j].name == 'submit_exc')
					{
					if (v_total_check==0) window.document.forms[i].elements[j].disabled = true
					else window.document.forms[i].elements[j].disabled = false;
					}
				}
			}					
		}
	</script>	
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
	<form name="form1" method="post">
	<table width="95%" border="0" align="center">
	<tr> 
	
	  <td class="cabecalho"><?php echo $oTranslator->_('Excluir Computadores');?></td>
	</tr>
	<tr> 
	
	  <td class="descricao"><?php echo $oTranslator->_('Esta opcao permite a selecao final para exclusao dos computadores selecionados na pesquisa');?>.</td>
	</tr>
	</table>
	<br><br>
  	<table width="85%" align="center"><tr>
    <td><div align="center"> 
    <?php if ($boolAdmin && $intTotalSelecionado)
		{
		?>
   		<input name="submit_exc" type="submit" value="<?php echo $oTranslator->_('Excluir computadores selecionados');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma exclusao');?>');">&nbsp;&nbsp;&nbsp;
		<?php
		}
		?>
   	<input name="submit_nova" type="submit" value="<?php echo $oTranslator->_('Selecionar novamente');?>">	
	</div></td>		
   	</tr></table>
	<br><br>
	<table border="0" align="center" cellpadding="0" cellspacing="0">
  	<tr> 
    <td> <table border="1" cellpadding="1" cellspacing="0" align="center">
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
	
          <tr bgcolor="#E1E1E1"> 
            <td align="center" colspan="2" nowrap><img src="../imgs/exclui_computador.gif" width="23" height="23"></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?php echo $oTranslator->_('Nome da maquina');?></div></td>
            <td align="center"  nowrap class="cabecalho_tabela"><div align="left"><?php echo $oTranslator->_('Local');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Endereco IP');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Endereco MAC');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Sistema operacional');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Agente Principal') . ' (' . CACIC_MAIN_PROGRAM_NAME . ')';?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('GerCols');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Ultimo acesso');?></div></td>
            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Inclusao');?></div></td>            
            <?php
			if ($boolAdmin)
				{
				?>
	            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Exclusao');?></div></td>
	            <td nowrap class="cabecalho_tabela"><div align="center"><?php echo $oTranslator->_('Usuario (Exclusao)');?></div></td>                
                <?php
				}
            ?>
          </tr>
          <tr> 
            <td height="1" bgcolor="#333333" colspan="23"></td>
          </tr>
          <?php
	$Cor 		 = 0;
	$intLoop	 = 0;
	reset($arrIdComputador);	
	while($intLoop < count($arrIdComputador)) 
		{		  
		if ($arrIdComputador[$intLoop])
			{
			$arrComputerSystem 				= getArrFromSelect('computadores_collects', 'te_class_values', 'nm_class_name = "ComputerSystem" AND id_computador = ' . $arrIdComputador[$intLoop]);
			$arrNetworkAdapterConfiguration = getArrFromSelect('computadores_collects', 'te_class_values', 'nm_class_name = "NetworkAdapterConfiguration" AND id_computador = ' . $arrIdComputador[$intLoop]);		
			?>
			<tr <?php if ($Cor) echo 'bgcolor="#E1E1E1"'; ?>> 
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"><?php echo $NumRegistro; ?></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><input type="checkbox" name="chk_<?php echo $arrIdComputador[$intLoop]; ?>" value="1" <?php echo ($boolAdmin && $arrExclusao[$arrIdComputador[$intLoop]] ? 'disabled' : 'checked onClick="Verifica_Check_Exclui();"');?>></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"  ><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo getValueFromTags('Caption',$arrComputerSystem[0]['te_class_values']); ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="left"  ><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrSgLocal[$arrLocal[$arrIdComputador[$intLoop]]]; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo getValueFromTags('IPAddress',$arrNetworkAdapterConfiguration[0]['te_class_values']); ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo getValueFromTags('MACAddress',$arrNetworkAdapterConfiguration[0]['te_class_values']);?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrSistemaOperacional[$arrIdComputador[$intLoop]]; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrAgentePrincipal[$arrIdComputador[$intLoop]]; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrGerCols[$arrIdComputador[$intLoop]]; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrUltimoAcesso[$arrIdComputador[$intLoop]]; ?></a></div></td>
            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrInclusao[$arrIdComputador[$intLoop]];   ?></a></div></td>
            <?php
			if ($boolAdmin)
				{
				?>
	            <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrExclusao[$arrIdComputador[$intLoop]]; ?></a></div></td>
    	        <td nowrap class="dado_peq_sem_fundo_normal"><div align="center"><a href="../relatorios/computador/computador.php?id_computador=<?php echo $arrIdComputador[$intLoop];?>" target="_blank"><?php echo $arrUsuarios[$arrUsuarioExclusao[$arrIdComputador[$intLoop]]];   ?></a></div></td>
                <?php
				}
            ?>            
			</tr>
			<?php 
			}
		$Cor=!$Cor;
		$intLoop++;
		}		
		
	if ($intLoop == 0)
		{
		?>
         <td colspan="<?php echo ($boolAdmin ? '22' : '20'); ?>" align="center" class="label_vermelho"><?php echo $oTranslator->_('Nao foram encontrados registros');?></TD>
          <script language="JavaScript">
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == 'submit_exc')
					{
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		</script>
          <?php
		}
		?>
        </table></td>
  	</tr>
  	<tr> 
    <td height="1" bgcolor="#333333"></td>
  	</tr>
	</table>

  	<br><br>
  	<table width="85%" align="center"><tr>
    <td><div align="center"> 
    <?php if ($boolAdmin && $intTotalSelecionado)
		{
		?>
   		<input name="submit_exc" type="submit" value="<?php echo $oTranslator->_('Excluir computadores selecionados');?>" onClick="return Confirma('<?php echo $oTranslator->_('Confirma exclusao');?>');">&nbsp;&nbsp;&nbsp;
        <?php
		}
		?>
   	<input name="submit_nova" type="submit" value="<?php echo $oTranslator->_('Selecionar novamente');?>">	
	</div></td>		
   	</tr></table>
	<p><p><p>  
	</form>
	</html>	
	<?php
	} 
else	
	{
	if ($_POST['submit_exc'])
		{
		$v_cs_exclui = '';
		$strTripaCampos = '';
		while(list($key, $value) = each($HTTP_POST_VARS))
			if (strpos($key,'chk_')>-1)
				{				
				$strTripaCampos .= ($strTripaCampos == ''?'':',');				
				$strTripaCampos .= str_replace('chk_','',$key);
				}			

		conecta_bd_cacic();								
		$boolOK = false;
		$v_query_exclui = 'UPDATE computadores SET dt_hr_exclusao = "' . @date("Y-m-d- H:i:s") . '", id_usuario_exclusao = ' . $_SESSION["id_usuario"] . ' WHERE id_computador in ('.$strTripaCampos.')';
		$exclui 		= @mysql_query($v_query_exclui);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela funÃ§Ã£o MYSQL_QUERY()									
		$v_cs_exclui = '1';

		GravaLog('DEL',$_SERVER['SCRIPT_NAME'],'computadores',$_SESSION["id_usuario"]);
		}
	?>	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../include/css/cacic.css">
	<title><?php echo $oTranslator->_('Excluir Computadores');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="../include/js/jquery.js"></script>	    
	<link href="../include/css/cacic.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	function Preenche_Condicao_VAZIO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<VAZIO>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}
	function Preenche_Condicao_NULO(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo)
					{
					window.document.forms[i].elements[j].value = '<NULL>';
					window.document.forms[i].elements[j].disabled = true;					
					}
				}
			}		
		}

	function Verifica_Condicoes_Seta_Campo(p_campo)
		{
		for (i=0;i<window.document.forms.length;i++)
			{
			for (j=0;j<window.document.forms[i].elements.length;j++)
				{
				if (window.document.forms[i].elements[j].name == p_campo && window.document.forms[i].elements[j].value == '<VAZIO>')
					{
					window.document.forms[i].elements[j].value = '';
					window.document.forms[i].elements[j].disabled = false;										
					}
				}
			}		
		SetaCampo(p_campo);								
		}

	function Verifica_Selecao(p_campo,p_campo_selecao)
		{
		if (p_campo.value == '')
			{
			for (i=0;i<window.document.forms.length;i++)
				{
				for (j=0;j<window.document.forms[i].elements.length;j++)
					{
					if (window.document.forms[i].elements[j].name == p_campo_selecao)
						{
						window.document.forms[i].elements[j].value = '';
						}
					}
				}		
			}
		}

	
	function Valida_Form_Pesquisa(p_argumento)
		{
		var v_conteudo = '';
		var v_tamanho = 0;
		v_tamanho = p_argumento.length;
		for (i=0;i<window.document.forms.length;i++)
			for (j=0;j<window.document.forms[i].elements.length;j++)
				if (window.document.forms[i].elements[j].name.substring(0,v_tamanho) == p_argumento && 
				    window.document.forms[i].elements[j].value != '')
					v_conteudo = v_conteudo + window.document.forms[i].elements[j].value;

		if (v_conteudo == '')
			{
			alert('<?php echo $oTranslator->_('Eh necessario informar ao menos uma condicao para pesquisa');?>!');
			return false;
			}		
		else
			{
			for (i=0;i<window.document.forms.length;i++)
				for (j=0;j<window.document.forms[i].elements.length;j++)
					if (window.document.forms[i].elements[j].value == '')
						{
						var objField = window.document.forms[i].elements[j];
						$(objField).toggleClass('novalue');					
						}
			
			$(".novalue").attr('disabled','disabled');
			}

		return true;
		}			
	</SCRIPT>
	</head>

	<body background="../imgs/linha_v.gif">
	<script language="JavaScript" type="text/javascript" src="../include/js/cacic.js"></script>
	<form name="form1" method="post">
	<table width="85%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr> 
	
  	<td class="cabecalho"><?php echo $oTranslator->_('Excluir Computadores');?></td>
	</tr>
	<tr> 
	
  	<td class="descricao"><?php echo $oTranslator->_('kciq_msg Excluir Computadores advise');?></td>
	</tr>
	</table>
	<br><br>
	<table width="85%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
    <td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="<?php echo $oTranslator->_('Selecionar computadores para exclusao');?>" onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');return Valida_Form_Pesquisa('frm_condicao_');">
   	</div></td>
   	</tr></table>

	<br><br>	
	<table width="85%" align="center" border="0" cellpadding="0" cellspacing="0">
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>	
	<tr bgcolor="#CCCCCC"> 
  	<td class="destaque"><?php echo $oTranslator->_('Campo');?></font></strong></td>
  	<td class="destaque"><?php echo $oTranslator->_('Condicao');?></font></strong></td>
  	<td class="destaque"><?php echo $oTranslator->_('Valor para pesquisa');?></font></strong></td>
	</tr>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	<?php
	$cor = 0;
	require_once('../include/library.php');

	$arrProperties = array();
	$queryPROPERTIES = 'SELECT 		nm_class_name,
									te_class_description,
									nm_property_name,
									te_property_description 
				  		FROM 		classes_properties cp,
									classes c
						WHERE		c.id_class = cp.id_class 
						ORDER BY 	te_property_description';
	$resPROPERTIES 	 = @mysql_query($queryPROPERTIES);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela funÃ§Ã£o MYSQL_QUERY()
	while ($rowPROPERTIES = mysql_fetch_array($resPROPERTIES))		
		$arrProperties[$rowPROPERTIES['nm_class_name'].'.'.$rowPROPERTIES['nm_property_name']] = $rowPROPERTIES['te_class_description'] . ' - ' . $rowPROPERTIES['te_property_description'];
	
	$queryDESC = 'SELECT 	* 
				  FROM 		descricoes_colunas_computadores
				  WHERE		cs_condicao_pesquisa = "S" 
				  ORDER BY	te_description';
	$resDESC 	= @mysql_query($queryDESC);	 //Neste caso, o "@" inibe qualquer mensagem de erro retornada pela funÃ§Ã£o MYSQL_QUERY()
	
	$arrCampos = array();
	while ($rowDESC = mysql_fetch_array($resDESC))		
		$arrCampos[$rowDESC['te_source'].'.'.$rowDESC['te_target']] = ($arrProperties[$rowDESC['te_source'].'.'.$rowDESC['te_target']] ? $arrProperties[$rowDESC['te_source'].'.'.$rowDESC['te_target']] : $rowDESC['te_description']);		
		
	asort($arrCampos);
	for ($i=0; $i < count($arrCampos); $i++) 
		{	
		$key = key($arrCampos);
		if ($arrCampos[$key])
			{		
			?>
			<tr <?php if ($cor) echo 'bgcolor="#E1E1E1"';?>> 
			<td nowrap><?php echo $arrCampos[$key];?></td>
			<td><select name="frm_condicao_<?php echo $key; ?>" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);" >
			<option value=""></option>
			<?php if (stripos2($key,'dt_')) 
				{
				$v_operacao = "(TO_DAYS(NOW())-TO_DAYS(a.".$key.")";
				?>
				<option value="<?php echo $v_operacao . ' =       frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo str_pad( $oTranslator->_('IGUAL A')  ,18,'�');?></option>					
				<option value="<?php echo $v_operacao . ' -MAIOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo str_pad( $oTranslator->_('MAIOR QUE'),18,'�');?></option>					
				<option value="<?php echo $v_operacao . ' -MENOR- frm_te_valor_condicao)'; ?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo str_pad( $oTranslator->_('MENOR QUE'),18,'�');?></option>											
				<?php
				}
			else
				{
				?>
				<option value="<?php echo       $key." =       [[AS]]frm_te_valor_condicao[[AS]]"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('IGUAL A');?></option>		
				<option value="<?php echo       $key." <>      [[AS]]frm_te_valor_condicao[[AS]]"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('DIFERENTE DE');?></option>			
				<option value="<?php echo       $key." -MAIOR- [[AS]]frm_te_valor_condicao[[AS]]"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('MAIOR QUE');?></option>
				<option value="<?php echo       $key." -MENOR- [[AS]]frm_te_valor_condicao[[AS]]"  ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('MENOR QUE');?></option>						
				<option value="<?php echo       $key." like    [[AS]]%frm_te_valor_condicao%[[AS]]";?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('CONTENHA');?></option>
				<option value="<?php echo "[[AS]]%frm_te_valor_condicao%[[AS]] not like  (".$key.")  ";?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('NAO CONTENHA');?></option>			
				<option value="<?php echo       $key." like    [[AS]]frm_te_valor_condicao%[[AS]]" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('INICIE COM');?></option>
				<option value="<?php echo       $key." like    [[AS]]%frm_te_valor_condicao[[AS]]" ;?>" onClick="Verifica_Condicoes_Seta_Campo('<?php echo "frm_te_valor_condicao_". $key; ?>');"><?php echo $oTranslator->_('TERMINE COM');?></option>				
				<option value="<?php echo 'TRIM('.$key.") = [[AS]][[AS]] and " 					      ;?>" onClick="Preenche_Condicao_VAZIO('<?php echo "frm_te_valor_condicao_". $key; ?>');"		 ><?php echo $oTranslator->_('SEJA VAZIO');?></option>		
				<option value="<?php echo $key." IS NULL " 					          ;?>" onClick="Preenche_Condicao_NULO('<?php echo "frm_te_valor_condicao_". $key; ?>');"		 ><?php echo $oTranslator->_('SEJA NULO');?></option>					
				<?php
				}
				?>
			</select> </td>
			<td><input name="frm_te_valor_condicao_<?php echo $key; ?>" type="text" class="normal" onFocus="SetaClassDigitacao(this);" onBlur="SetaClassNormal(this);Verifica_Selecao(this,'<?php echo "frm_condicao_". $key; ?>');" size="60" maxlength="100"></td>
			</tr>
			<?php			
			$cor=!$cor;
			}
		next($arrCampos);
		}
	?>
  	<tr> 
    <td colspan="3" height="1" bgcolor="#333333"></td>
  	</tr>
	
	</table>
	<br><br>
	<table width="85%" align="center" border="0" cellpadding="0" cellspacing="0"><tr>
	<td colspan="3"><div align="center"> 
   	<input name="submit_cond" type="submit" value="<?php echo $oTranslator->_('Selecionar computadores para exclusao');?>" onClick="return Valida_Form_Pesquisa('frm_te_valor_condicao_');">	
	</div></td>
	</tr></table>

	<p><p><p>  
	</form>
	</html>	
	<?php
	}
	?>