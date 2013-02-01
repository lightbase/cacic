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
require_once('../../include/library.php');

AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...
// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

if (isset($_POST['ExecutaUpdate']))
	{				
	// Enviarei também ao update_subrede.php uma relação de agentes e versões para inserção na tabela redes_versoes_modulos, no caso da ocorrência de ServidorAutenticacao de Updates verificado anteriormente.
	// Exemplo de estrutura de agentes_versoes: col_soft.exe#22010103*col_undi.exe#22010103
	// 						   agentes_hashs:   col_soft.exe#4228204d66e268ad42d9d738a09800e8*col_undi.exe#2428204d67e268ad42d9d738a09800ff	
	$intTotalItens = 0;
	foreach($HTTP_POST_VARS as $strKey => $strPostValue) 
		{
		if ($strPostValue && substr($strKey,0,18)=='frmUpdateSubredes_' && $strPostValue <> 'on')
			{
			if ($strItens <> '') 
				$strItens .= ',';
				
			$strItens .= $strPostValue;		
			$intTotalItens ++;
			}
			
		if ($strPostValue && substr($strKey,0,5)=='rede_' && $strPostValue <> 'on')
			{
			if ($strRedes <> '') 
				$strRedes .= ',';			
				
			$strRedes .= $strPostValue;
			}			
		}
		
	$_SESSION['sessStrIdRedes']  	  = $strRedes;
	$_SESSION['sessStrNmItens']  	  = $strItens;

	header ("Location: update_subrede.php");			
	}
else
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">
	<title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php require_once('../../include/js/opcoes_avancadas_combos.js');  ?>
	<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>					    
	<script language="JavaScript" type="text/javascript" src="../../include/js/jquery.js"></script>					        
    <script language="JavaScript" type="text/JavaScript">
    <!--
    function MM_reloadPage(init) {  //reloads the window if Nav4 resized
      if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
        document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
      else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
    }
    MM_reloadPage(true);
    //-->

	function doSelectItems()
		{
		var oFrmItemsToSelect = returnObjById("frmStrItemsToSelect"); 
		var arrItemsToSelect  = oFrmItemsToSelect.value.split(','); 		
        for (intIndex = 0; intIndex < arrItemsToSelect.length; intIndex ++)		
			{
			$('input[name="' + 'frmUpdateSubredes_'   + arrItemsToSelect[intIndex] + '"]').attr('checked','checked');
			$('tr[id="'      + 'trFrmUpdateSubredes_' + arrItemsToSelect[intIndex] + '"]').css('background-color','#FFFF99');			
			}
		oFrmItemsToSelect = null;
		arrItemsToSelect  = null;
		}
		
    function verificar()
        {
        var formRedes = window.document.forma;
        var boolModulos = false;
        var boolRedes   = false;
        var strFraseErro = '';
    
        for (j=0;j<formRedes.elements.length;j++)
            if (formRedes.elements[j].type == 'checkbox' && (formRedes.elements[j].name).substring(0,18) == 'frmUpdateSubredes_')
                {
                if (formRedes[j].checked && formRedes.elements[j].value != 'versions_and_hashes.ini')
                    {
                    boolModulos = true;
                    j = formRedes.elements.length;
                    }
                }
    
        for (j=0;j<formRedes.elements.length;j++)
            if (formRedes.elements[j].type == 'checkbox' && formRedes.elements[j].id == 'redes')
                {
                if (formRedes[j].checked)
                    {
                    boolRedes = true;
                    j = formRedes.elements.length;				
                    }
                }
    
        if (boolModulos && boolRedes)
            return true;
        else
            {
            if (!boolModulos)
                strFraseErro = 'Módulos';
    
            if (!boolRedes)
                strFraseErro = (!boolModulos?' e ':'') + 'SubRedes';
    
            alert('ATENÇÃO: Verifique as seleções de '+strFraseErro);	
    //		formRedes.elements[min(intInicioModulos,intInicioRedes)].focus();		
            }
        return false;
        }
    </script>
	</head>
	
	<body background="../../imgs/linha_v.gif">
	<table width="85%" border="0" align="center">
	  <tr> 
		<td class="cabecalho"><?php echo $oTranslator->_('Atualizacoes de subredes');?></td>
	  </tr>
	  <tr> 
		
    <td class="descricao"><?php echo $oTranslator->_('Atualizacoes de subredes - texto de ajuda');?></td>
	  </tr>
	</table>
	
	<form method="post" ENCTYPE="multipart/form-data" name="forma">
	<script>
	function MarcaIncondicional(field,p_name) 
		{
		for (i = 1; i < field.length; i++) 
			if (field[i].type == 'checkbox' && field[i].name == p_name)
				field[i].checked = true;			

		return true;	
		}
	function MarcaDesmarcaTodoEsseLocal(strIdLocal) 
		{
		var Formulario = window.document.forms[0];
		var arrRede;
		for (i = 0; i < Formulario.length; i++) 
			if (Formulario[i].type == 'checkbox' && (Formulario[i].name).substring(0,5) == 'rede_')
				{
				arrRede = (Formulario[i].name).split('_');
				if (strIdLocal == arrRede[2])
					if (Formulario[i].checked)
						Formulario[i].checked = false;			
					else
						Formulario[i].checked = true;								
				}

		return true;
		}

	function MarcaDesmarcaTodaLegenda(strCor) 
		{
		var Formulario = window.document.forms[0];
		var arrRede;
		for (i = 0; i < Formulario.length; i++) 
			if (Formulario[i].type == 'checkbox' && (Formulario[i].name).substring(0,5) == 'rede_')
				{
				arrRede = (Formulario[i].name).split('_');
				if (strCor == arrRede[3])
					if (Formulario[i].checked)
						Formulario[i].checked = false;			
					else
						Formulario[i].checked = true;								
				}

		return true;
		}

	</script>

  <div align="center">
    <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
      <?php
		$v_classe = "label";
	  ?>
      <tr> 
        <td height="20"></td>
      </tr>
	  
      <tr> 
        <td nowrap align="center" colspan="5" class="<?php echo $v_classe; ?>"><br>
          <?php echo $oTranslator->_('Conteudo da Area de Downloads');?></td>
      </tr>
      <tr> 
        <td colspan="5" height="1" bgcolor="#333333"></td>
      </tr>
      <tr> 
        <td class="destaque" align="center" colspan="3" valign="middle">
           <input name="frmUpdateSubredes" id="frmUpdateSubredes" type="checkbox" onClick="MarcaDesmarcaTodos(this.form.frmUpdateSubredes);">  
          <?php echo $oTranslator->_('Marcar/desmarcar todos os itens');?>
        </td>
      </tr>
      <tr>
        <td nowrap colspan="2">&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap colspan="2">&nbsp;</td>
      </tr>
      <tr> 
        <td nowrap colspan="2"><table border="1" align="center" cellpadding="2" bordercolor="#999999">
            <tr bgcolor="#FFFFCC"> 
              <td colspan="8" class="cabecalho_tabela" align="center"><b><?php echo $oTranslator->_('Agentes para MS-Windows');?></b></td>			  
            </tr>
		
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Arquivo');?></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Tamanho(KB)');?></td>
              <td align="center" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Versao');?></td>
              <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Hash');?></td>
            </tr>
            <?php 
			if(file_exists(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini') && is_readable(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini'))		
				$_SESSION['sessArrVersionsIni'] = parse_ini_file(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini');

			$intTotalItensNoRep = 0;
			$strItemName		= '.';	
			$intLoopVersionsIni = 0;
			while ($intLoopVersionsIni >= 0)
				{
				$intLoopVersionsIni ++;
				$arrItemDefinitions = explode(',',$_SESSION['sessArrVersionsIni']['Item_' . $intLoopVersionsIni]);	

				// Somente itens diferente de GNU/Linux e que não são exibidos na opção "Downloads" do Gerente WEB			
				if (($arrItemDefinitions[1] <> 'S') && ($arrItemDefinitions[2] <> 'S')) 
					{
					$strItemName	= getOnlyFileName(trim($arrItemDefinitions[0]));

					if ($_SESSION['sessArrVersionsIni'][$strItemName . '_VER'] == '')
						$intLoopVersionsIni = -1;
					else
						{			
						$intTotalItensNoRep++;					
						echo '<tr id="trFrmUpdateSubredes_' . $strItemName .'"><td><input name="frmUpdateSubredes_' . $strItemName . '" id="frmUpdateSubredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="' . $strItemName . '"></td>';
						echo '<td>' . $strItemName . '</td>';										
				
						echo '<td align="right">' . $_SESSION['sessArrVersionsIni'][$strItemName . '_SIZE'] . '</td>';												
						if ($_SESSION['sessArrVersionsIni'][$strItemName . '_VER'])
							$strItemVersion = $_SESSION['sessArrVersionsIni'][$strItemName. '_VER'];
						else
							{
							$strFileData    = lstat(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . trim($arrItemDefinitions[0]));				
							$strItemVersion = strftime("%d/%m/%Y  %H:%Mh", $strFileData[9]);
							}
				
						echo '<td align="center" colspan="3">'.$strItemVersion.'</td>';													
						echo '<td align="center">' . $_SESSION['sessArrVersionsIni'][$strItemName . '_HASH'] . '</td>';
									
						echo '</tr>';		
						}
					}				
				}
	 	?>
		<tr><td colspan="8">&nbsp;</td></tr>
           <tr bgcolor="#FFFFCC"> 
              <td colspan="8" class="cabecalho_tabela" align="center"><b><?php echo $oTranslator->_('Agentes para GNU/Linux');?></b></td>			  
            </tr>
            <tr bgcolor="#FFFFCC"> 
              <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Arquivo');?></td>
              <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Tamanho(KB)');?></td>
              <td align="center" colspan="3" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Versao');?></td>
              <td align="center" nowrap bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Hash');?></td>
            </tr>
            <?php
			$strItemName		 = '.';	
			$intLoopVersionsIni  = 0;
			$boolFoundLinuxItems = false;
			while ($intLoopVersionsIni >= 0)
				{
				$intLoopVersionsIni ++;
				$arrItemDefinitions = explode(',',$_SESSION['sessArrVersionsIni']['Item_' . $intLoopVersionsIni]);	
			
				// Somente itens diferente de GNU/Linux e que não são exibidos na opção "Downloads" do Gerente WEB			
				if (($arrItemDefinitions[1] <> 'N') && ($arrItemDefinitions[2] == 'S')) 
					{
					$boolFoundLinuxItems = true;
					$strItemName	    = getOnlyFileName(trim($arrItemDefinitions[0]));

					if ($_SESSION['sessArrVersionsIni'][$strItemName . '_VER'] == '')
						$intLoopVersionsIni = -1;
					else
						{			
						$intTotalItensNoRep++;					
						echo '<tr><td><input name="frmUpdateSubredes_' . $strItemName . '" id="frmUpdateSubredes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="' . $strItemName . '"></td>';
						echo '<td>' . $strItemName . '</td>';										
				
						echo '<td align="right">' . $_SESSION['sessArrVersionsIni'][$strItemName . '_SIZE'] . '</td>';						
						if ($_SESSION['sessArrVersionsIni'][$strItemName . '_VER'])
							$strItemVersion = $_SESSION['sessArrVersionsIni'][$strItemName. '_VER'];
						else
							{
							$strFileData    = lstat(CACIC_PATH . CACIC_PATH_RELATIVO_DOWNLOADS . trim($arrItemDefinitions[0]));				
							$strItemVersion = strftime("%d/%m/%Y  %H:%Mh", $strFileData[9]);
							}
				
						echo '<td align="center" colspan="3">'.$strItemVersion.'</td>';													
						echo '<td align="center">' . $_SESSION['sessArrVersionsIni'][$strItemName . '_HASH'] . '</td>';
									
						echo '</tr>';		
						}
					}				
				}
            
	 if ($boolFoundLinuxItems)
		?>
        <tr><td colspan="8" class="Erro" align="center">Não Há Referências Válidas a Ítens GNU/Linux no Arquivo de Controle (versions_and_hashes.ini)</td></tr>
        
        </table></td>
      </tr>
    </table>
    <br>
  </div>
  <table width="85%" border="0" align="center" cellpadding="0" cellspacing="1">
		<?php
		$v_classe = "label";
		?>
		<tr> 
		  <td nowrap align="center" colspan="4" class="<?php echo $v_classe; ?>"><br>
             <?php echo $oTranslator->_('SubRedes Cadastradas');?></td>
		</tr>
		<tr> 
		  <td colspan="4" height="1" bgcolor="#333333"></td>
		</tr>
			  <tr> 
				<td class="destaque" align="center" colspan="4" valign="middle"><input name="redes" type="checkbox" id="redes" onClick="MarcaDesmarcaTodos(this.form.redes);">
				  <?php echo $oTranslator->_('Marcar/desmarcar todas as subRedes');?></td>
			  </tr>
			  <tr>
			    <td height="10" colspan="2">&nbsp;</td>
    </tr>
			  <tr>
			    <td height="10" colspan="2"></td>
    </tr>
			  <tr>
			    <td height="10" colspan="2"><table width="200" border="1" align="center">
                  <tr>
                    <td height="10" colspan="2" nowrap><div align="center"><strong><?php echo $oTranslator->_('Legenda para as SubRedes');?></strong></div></td>
                  </tr>
                  <tr>
                    <td height="10" nowrap bordercolor="#000000" class="td_amarelo"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('amarelo');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação" ><?php echo $oTranslator->_('Amarelo');?></div></a></td>
					
                    <td align="left" valign="middle" nowrap><?php echo $oTranslator->_('Existencia de modulo com versao ou hash-code diferente');?></td>
                  </tr>
                  <tr>
                    <td height="10" nowrap bordercolor="#000000" class="td_laranja"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('laranja');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação"><?php echo $oTranslator->_('Laranja');?></td>					
                    <td align="left" valign="middle" nowrap><span class="opcao_tabela"><?php echo $oTranslator->_('Inexistencia parcial de modulos');?></span></td>
                  </tr>
                  <tr>				  					  
                    <td height="10" nowrap bordercolor="#000000" class="td_vermelho"><a style="cursor: pointer"><div align="center" onClick="MarcaDesmarcaTodaLegenda('vermelho');" title="Clique para Marcar/Desmarcar as Redes Nesta Situação"><?php echo $oTranslator->_('Vermelho');?></td>
                    <td align="left" valign="middle" nowrap><span class="opcao_tabela"><?php echo $oTranslator->_('Inexistencia total de modulos');?></span></td>
                  </tr>
                </table>
		          <div align="center">
		            <small><?php echo $oTranslator->_('Dica: Clique nas Cores da legenda para marcar/desmarcar subredes em bloco');?></small>
		          </div></td>
    </tr>
			  

		<tr> 
		  <td nowrap colspan="4"><br>
		  <table border="1" align="center" cellpadding="2" bordercolor="#999999">
		    <tr bgcolor="#FFFFCC"> 
		      <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Sequencia');?></td>			
				  <td bgcolor="#EBEBEB" align="center"><img src="../../imgs/checked.gif" border="no"></td>				
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Endereco IP');?></td>
				  
            <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Nome da Subrede');?></td>			
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('ServidorAutenticacao de atualizacoes');?></td>
				  <td bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Caminho (path) FTP');?></td>											
				  <td colspan="2" bgcolor="#EBEBEB" class="cabecalho_tabela"><?php echo $oTranslator->_('Localizacao');?></td>											
	        </tr>
		    
		    <?php 
	   	 	$whereREDES = ($_SESSION['cs_nivel_administracao']<>1&&$_SESSION['cs_nivel_administracao']<>2?' AND loc.id_local = '.$_SESSION['id_local']:'');
			if ($_SESSION['te_locais_secundarios']<>'' && $whereREDES <> '')
				{
				// Faço uma inserção de "(" para ajuste da lógica para consulta	
				$whereREDES = str_replace(' loc.id_local = ',' (loc.id_local = ',$whereREDES);
				$whereREDES .= ' OR loc.id_local in ('.$_SESSION['te_locais_secundarios'].')) ';
				}
			
			$queryRVM = "	SELECT 		re.te_ip_rede,
										re.id_local,
										rvm.nm_modulo,
										rvm.te_versao_modulo,
										rvm.te_hash,
										rvm.cs_tipo_so,
										re.id_rede
							FROM 		redes re,
										redes_versoes_modulos rvm,
										locais loc
							WHERE		re.id_rede = rvm.id_rede ".
										$whereREDES ." and loc.id_local = re.id_local
							ORDER BY	re.id_rede,
										rvm.nm_modulo"; 

			conecta_bd_cacic();
													
			$resultRVM 			= mysql_query($queryRVM) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes_versoes_modulos'))); 																

			
			$intTotalItens     	= 0;   // Contarei no arquivo versions_and_hashes.ini os nomes com a string "_HASH"			
				
			$arrYellow		   	= array(); // Conterá os IPS das redes cujas versões de módulos divergirem das existentes na área de downloads
			$arrRedes		   	= array(); // Conterá todos os IPs das redes para pesquisa por ausência total de ítens
			$arrRedesTotais		= array();
			$strRedeAtual      	= '';
			$strItemsToSelect	= '';
			while ($rowRVM = mysql_fetch_array($resultRVM))
				{
				$arrRedes[$rowRVM['id_rede']] ++;
				$strItemName   = ($boolLinuxItem ? 'PyCACIC' : trim($rowRVM['nm_modulo']));				
				if ($_SESSION['sessArrVersionsIni'][$rowRVM['nm_modulo'] . '_HASH'])
					{
					$boolLinuxItem = stripos2($rowRVM['nm_modulo'] , 'PyCACIC' , false);

					if ($rowRVM['te_hash'] <> $_SESSION['sessArrVersionsIni'][$strItemName . '_HASH'])
						{
						$arrYellow[$rowRVM['id_rede']] += 1; // Versão/Hash diferente
						if (!stripos2($strItemsToSelect , $strItemName, false))						
							{
							$strItemsToSelect .= ($strItemsToSelect ? ',' : '');
							$strItemsToSelect .= $strItemName;							
							}
						}
					}
				elseif (!stripos2($strItemsToSelect , $strItemName, false))						
					{
					$strItemsToSelect .= ($strItemsToSelect ? ',' : '');
					$strItemsToSelect .= $strItemName;							
					}
				}
				
				

// ********************	
		$queryREDES = "	SELECT 		re.te_ip_rede,
									re.nm_rede,
									loc.id_local,
									loc.sg_local,
									re.te_serv_updates,
									re.te_path_serv_updates,
									re.id_rede
						FROM 		redes re,
									locais loc
						WHERE		re.id_local = loc.id_local ".
									$whereREDES ."
						GROUP BY    re.id_rede
						ORDER BY	loc.sg_local,
									re.nm_rede"; 
									
		$resultREDES = mysql_query($queryREDES) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!',array('redes'))); 										

		$intSequencial = 1;

		while ($rowREDES = mysql_fetch_array($resultREDES))
			{
			$strIdRedes       .= ($strIdRedes ? ',' : '');			
			$strIdRedes       .=  $rowREDES['id_rede'];
			
			$strCheck 	 = '';
			$strClasseTD = 'td_normal';
			
			// Se a rede não tiver indicadores é porque não tem ítens
			if (!$arrRedes[$rowREDES['id_rede']])
				$strClasseTD = 'td_vermelho';				 
			elseif ($arrRedes[$rowREDES['id_rede']] <> $intTotalItensNoRep)
				$strClasseTD = 'td_laranja';				 							
			elseif ($arrYellow[$rowREDES['id_rede']])
				$strClasseTD = 'td_amarelo';				 								
				
			$strCheck = ($strClasseTD == 'td_normal' ? '' : 'checked');
			?>
		    <tr>
			  <input name="frmIntTotalItensNoRep" type="hidden" id="frmIntTotalItensNoRep" value="<?php echo $intTotalItensNoRep;?>">                              
			  <input name="frmStrItemsToSelect"   type="hidden" id="frmStrItemsToSelect"   value="<?php echo $strItemsToSelect;?>">                              
		      <td class="<?php echo $strClasseTD;?>" align="right"><?php echo $intSequencial;?></td>									
			  <td class="<?php echo $strClasseTD;?>"><input name="rede_<?php echo $rowREDES['id_rede'].'_'.str_replace('td_','',$strClasseTD);?>" id="redes" type="checkbox" class="normal" onBlur="SetaClassNormal(this);" value="<?php echo $rowREDES['id_rede'];?>" <?php echo $strCheck;?>></td>
			  <td class="<?php echo $strClasseTD;?>"><?php echo $rowREDES['te_ip_rede'];?></td>
			  <td class="<?php echo $strClasseTD;?>"><?php echo $rowREDES['nm_rede'];?></td>
			  <td class="<?php echo $strClasseTD;?>"><?php echo $rowREDES['te_serv_updates'];?></td>
			  <td class="<?php echo $strClasseTD;?>"><?php echo $rowREDES['te_path_serv_updates'];?></td>
			  <td class="<?php echo $strClasseTD;?>" nowrap="nowrap" 
			<?php if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
				{
				?>
				colspan="2"
				<?php
				}
				?>
			><?php echo $rowREDES['sg_local'];?></td>
			  <?php if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{			
				?>
		      <td class="<?php echo $strClasseTD;?>" nowrap="nowrap"><img src="../../imgs/checked.gif" border="no"  onClick="MarcaDesmarcaTodoEsseLocal('<?php echo $rowREDES['id_local']; ?>');"></td>
				  <?php
				}
				?>
	        </tr>
		    <?php
			$intSequencial ++;							
			}

		// e 
		$strQueryDEL = 'DELETE FROM redes_versoes_modulos WHERE id_rede IN (' .$strIdRedes.') AND nm_modulo IN ('.$strNmItens.')';
		$resultDEL	 = mysql_query($strQueryDEL);							
	?> 
	      </table></td></tr>
	</table>        
	<p align="center">
	<input name="ExecutaUpdate" type="submit" id="ExecutaUpdate" value="<?php echo $oTranslator->_('Executar atualizacoes');?>"
	       onClick="return verificar();"
	       <?php echo ($_SESSION['cs_nivel_administracao'] <> 1 && $_SESSION['cs_nivel_administracao'] <> 3 ? 'disabled' : '')?>>
	</p>
	</form>		  			
    <script language="javascript">
		doSelectItems();
	</script>
	</body>
	</html>
	<?php
	}
	?>