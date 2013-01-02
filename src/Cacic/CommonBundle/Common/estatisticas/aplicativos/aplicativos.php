<?
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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verifica��es (ex: permiss�es do usu�rio)!
}

/*
if (count($HTTP_POST_VARS) > 0)
	foreach($HTTP_POST_VARS as $i => $v) 
		echo 'POST => '.$i.' => '.$v.'<br>';
*/

if($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2']; //Redes selecionadas
	$_SESSION["list4"] = $_POST['list4']; //SO selecionados
	$_SESSION["list6"] = $_POST['list9']; //Aplicativos selecionados
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12']; //Locais Selecionados (No caso de N�vel Administrativo)		
	$_SESSION["cs_situacao"] = $_POST["cs_situacao"];
	}

require_once('../../include/library.php');
AntiSpy('1,2,3,4');
conecta_bd_cacic();

$linha = '<tr bgcolor="#e7e7e7"> 
			  <td height="1" colspan="'.($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2?'4':'5').'"></td>
         </tr>';

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<link rel="stylesheet"   type="text/css" href="../../include/cacic.css">
<title>Aplicativos</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script LANGUAGE="JavaScript">
<!-- Begin
function open_window(theURL,winName,features) { 
    window.open(theURL,winName,features);
}
//-->
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="2" topmargin="10" marginwidth="0" marginheight="0">
<script language="JavaScript" type="text/javascript" src="../../include/cacic.js"></script>
<table border="0" align="left" cellpadding="0" cellspacing="0" bordercolor="#999999">
  <tr bgcolor="#E1E1E1"> 
    <td rowspan="5" bgcolor="#FFFFFF"><img src="../../imgs/cacic_logo.png" width="50" height="50"></td>
    <td rowspan="5" bgcolor="#FFFFFF">&nbsp;</td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr bgcolor="#E1E1E1"> 
    <td bgcolor="#FFFFFF" class="cabecalho_rel">
      <?=$oTranslator->_('Estatisticas de sistemas monitorados');?>
    </td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p class="descricao"><?=$oTranslator->_('Gerado em');?> <? echo date("d/m/Y - H:i"); ?></p></td>
  </tr>
</table>
<p><br>
</p>
<p>&nbsp;</p>
<p><br>
  <?

$_SESSION['select']  			= '';
$_SESSION['from']  				= '';
$_SESSION['where'] 				= '';
$_SESSION['redes_selecionadas'] = '';
$_SESSION['query_redes'] = ' AND computadores.id_ip_rede = redes.id_ip_rede ';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	// Aqui pego todas as redes selecionadas e fa�o uma query p/ condi��o de redes
	$_SESSION['redes_selecionadas'] = '';
	for( $i = 0; $i < count($_SESSION["list2"] ); $i++ ) 
		{
		if ($_SESSION['redes_selecionadas'])
			$_SESSION['redes_selecionadas'] .= ',';
		$_SESSION['redes_selecionadas'] .= "'".$_SESSION["list2"][$i]."'";
		}

	$_SESSION['query_redes'] .= ' AND redes.id_ip_rede IN ('. $_SESSION['redes_selecionadas'] .')';	
	}
else
	{
	// Aqui pego todos os locais selecionados e fa�o uma query p/ condi��o de redes/locais
	$locais_selecionados = '';
	for( $i = 0; $i < count($_SESSION["list12"] ); $i++ ) 
		{
		if ($locais_selecionados)
			$locais_selecionados .= ',';
		$locais_selecionados .= $_SESSION["list12"][$i];
		}

	if ($locais_selecionados)
		$_SESSION['query_redes'] .= ' AND locais.id_local IN ('.$locais_selecionados.') ';
	}

$_SESSION['query_redes'] .= ' AND redes.id_local = locais.id_local ';		
$_SESSION['select'] = ' ,nm_rede,nm_local ';	
$_SESSION['from'] = ' ,locais, redes ';			

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}
$_SESSION["so_selecionados"] = $so_selecionados;

//Pego os aplicativos selecionados para o relat�rio

$aplicativos_selecionados = '';
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	if ($aplicativos_selecionados)
		$aplicativos_selecionados .= ',';
	$aplicativos_selecionados .= $_SESSION["list6"][$i] ;
	}

// Exibir as aplica��es	 baseado no que foi solicitado	
$query_select = 'SELECT 	id_aplicativo,
							nm_aplicativo,
							cs_car_inst_w9x,
							cs_car_inst_wnt,
							cs_car_ver_w9x,
							cs_car_ver_wnt 							
				 FROM 		perfis_aplicativos_monitorados
				 WHERE 		id_aplicativo IN (' .$aplicativos_selecionados.') and
				            nm_aplicativo not like "%#DESATIVADO#%"
				 ORDER BY 	nm_aplicativo';

//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	echo 'query_select: '.$query_select . '<br><br>';
	
$result_query_selecao = mysql_query($query_select);

$strGroupByVersao	= ($_POST['rdCsSaidaDetalhada']=='S'?'':' GROUP BY a.te_versao ');
$strGroupByLicenca	= ($_POST['rdCsSaidaDetalhada']=='S'?'':' GROUP BY a.id_aplicativo, a.te_licenca ');	

$strOrderByVersao	= ($_POST['rdCsSaidaDetalhada']=='S'?' ORDER BY a.te_versao,nm_local,nm_rede,te_ip ':' ORDER BY total_equip DESC,a.te_versao,b.nm_aplicativo ');
$strOrderByLicenca	= ($_POST['rdCsSaidaDetalhada']=='S'?' ORDER BY a.te_licenca,nm_local,nm_rede,te_ip ':' ORDER BY total_equip DESC ');	

$strCountVersao		= ($_POST['rdCsSaidaDetalhada']=='S'?'':' ,COUNT(a.te_versao) as total_equip ');
$strCountLicenca	= ($_POST['rdCsSaidaDetalhada']=='S'?'':' ,COUNT(a.te_licenca) as total_equip ');	

?>
</p>
<table width="100%" border="0" align="center">
<tr>
<td align="center">
<? 
while($reg_selecao = @mysql_fetch_array($result_query_selecao))
	{
	if ($v_nm_aplicativo <> '')
		{
		if (($total_maquinas_versao + $total_maquinas_licenca) == 0)
			{
			?>
	    	<td align="center" class="label_vermelho"><?=$oTranslator->_('A pesquisa nao retornou registros');?></td>
            <?
            }
			?>
		</table>
		<br><br>		
		</table>        
		<?			
		} 			    
	?>
   	<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr bordercolor="#FFFFFF" bgcolor="#E1E1E1"> 
    <td colspan="3" bgcolor="#FFFFFF" nowrap class="cabecalho_secao">	
	<div align="center">
	<? 
	$where   		= '';
	if ($reg_selecao['cs_car_inst_w9x'] > 0 || $reg_selecao['cs_car_inst_wnt'] > 0)
		$where   = " AND (a.cs_instalado = 'S' OR (a.te_versao <> '' AND a.te_versao <> '?')) ";
	elseif ($reg_selecao['cs_car_ver_w9x'] > 0 && $reg_selecao['cs_car_ver_wnt'] > 0)
		$where   = " AND a.te_versao <> '' AND a.te_versao <> '?' ";
	
	$v_nm_aplicativo = $reg_selecao['nm_aplicativo'];
	echo $v_nm_aplicativo;
	$reg_id_aplicativo = $reg_selecao['id_aplicativo'];
	?> 
	</div>
	</td>
    </tr>
	<?
	
	// Exibir informa��es sobre a quantidade de m�quinas e vers�es instaladas	
 	$query_aplicativo ="SELECT DISTINCT a.te_versao, 
										a.id_aplicativo, 
					 					b.nm_aplicativo,
										b.cs_car_inst_w9x,
										b.cs_car_inst_wnt,
										a.cs_instalado,
										b.te_car_inst_w9x,
										b.te_car_inst_wnt ".
										$_SESSION['select'].",
										b.cs_car_ver_w9x,
										b.cs_car_ver_wnt,
										b.cs_ide_licenca,
										computadores.te_nome_computador,
										computadores.te_ip, 
										computadores.id_ip_rede,
										computadores.id_so,
										computadores.te_node_address ".
										$strCountVersao. " 
					 FROM  				aplicativos_monitorados a, 
					 					perfis_aplicativos_monitorados b, 
										computadores ".
										$_SESSION['from']." 
					 WHERE 				a.te_node_address  = computadores.te_node_address AND 
					 					a.id_so = computadores.id_so AND 
					 					a.id_aplicativo = ".$reg_id_aplicativo. " AND 
					 					a.id_aplicativo = b.id_aplicativo " . $_SESSION['query_redes'] . " AND 
					 					computadores.id_so IN (". $_SESSION['so_selecionados'] .") ".
										$where .
										$strGroupByVersao.
										$strOrderByVersao;
	$result_query_versoes = mysql_query($query_aplicativo);
	
	
	//if ($_SERVER['REMOTE_ADDR'] == '10.71.0.33' || $_SERVER['REMOTE_ADDR'] == '10.71.0.63')	
	//	echo $query_aplicativo . '<br>';
	
	/*
	Informa��es importantes:
	
	perfis_aplicativos_monitorados
	==============================
	cs_car_inst_w9x 1->Execut�vel       2->INI       3->Registry
	cs_car_inst_wnt 1->Execut�vel       2->INI       3->Registry	
	cs_car_ver_w9x  1->Data de Arquivo  2->Registry  3->INI       4->Vers�o de Execut�vel
	cs_car_ver_wnt  1->Data de Arquivo  2->Registry  3->INI       4->Vers�o de Execut�vel
	cs_ide_licenca  1->Registry         2->INI
	
	
	aplicativos_monitorados
	=======================
	cs_instalado	S/N
	*/

	$total_maquinas_versao 	= 0;
	$sequencial 			= 0;

	$in_se_instalado = false;
	if (mysql_num_rows($result_query_versoes))
		{ 
		// Crio vari�veis array a partir do resultado da consulta, para exibi��o de detalhes (reuso)
		// Anderson Peterle - 31/Julho/2009
		$arrTotalNaVersao		= array();
		$arrTeVersao 			= array();
		$arrTotalEquip 			= array();
		$arrIdAplicativo 		= array();
		$arrNmAplicativo		= array();
		$arrCsCarInstW9X		= array();
		$arrCsCarInstWNT		= array();
		$arrCsInstalado			= array();
		$arrTeCarInstW9X		= array();
		$arrTeCarInstWNT		= array();	
		$arrNmRede				= array();
		$arrNmLocal				= array();
		$arrCsCarVerW9X			= array();
		$arrCsCarVerWNT			= array();
		$arrCsIdeLicenca		= array();	
		$arrTeNomeComputador	= array();
		$arrTeIp				= array();	
		$arrIdIpRede			= array();		
		$arrIdSo				= array();
		$arrTeNodeAddress		= array();	
		
		while($reg_versoes = mysql_fetch_array($result_query_versoes)) 
			{ 
			$arrTotalNaVersao[$reg_versoes['te_versao']] ++;
			
			$arrTeVersao[$sequencial]			=	$reg_versoes['te_versao'];
			$arrTotalEquip[$sequencial] 		=	($_POST['rdCsSaidaDetalhada']=='S'?0:$reg_versoes['total_equip']);
			$arrIdAplicativo[$sequencial]		=	$reg_versoes['id_aplicativo'];
			$arrNmAplicativo[$sequencial]		=	$reg_versoes['nm_aplicativo'];
			$arrCsCarInstW9X[$sequencial]		=	$reg_versoes['cs_car_inst_w9x'];
			$arrCsCarInstWNT[$sequencial]		=	$reg_versoes['cs_car_inst_wnt'];
			$arrCsInstalado[$sequencial]		=	$reg_versoes['cs_instalado'];
			$arrTeCarInstW9X[$sequencial]		=	$reg_versoes['te_car_inst_w9x'];
			$arrTeCarInstWNT[$sequencial]		=	$reg_versoes['te_car_inst_wnt'];
			$arrNmRede[$sequencial]				=	$reg_versoes['nm_rede'];
			$arrNmLocal[$sequencial]			=	$reg_versoes['nm_local'];
			$arrCsCarVerW9X[$sequencial]		=	$reg_versoes['cs_car_ver_w9x'];
			$arrCsCarVerWNT[$sequencial]		=	$reg_versoes['cs_car_ver_wnt'];
			$arrCsIdeLicenca[$sequencial]		=	$reg_versoes['cs_ide_licenca'];
			$arrTeNomeComputador[$sequencial]	=	$reg_versoes['te_nome_computador'];
			$arrTeIp[$sequencial]				=	$reg_versoes['te_ip'];
			$arrIdIpRede[$sequencial]			=	$reg_versoes['id_ip_rede'];
			$arrIdSO[$sequencial]				=	$reg_versoes['id_so'];
			$arrTeNodeAddress[$sequencial]		=	$reg_versoes['te_node_address'];
							
			$cs_instalado   = (($arrCsCarInstW9X[$sequencial]<>'' && $arrCsCarInstW9X[$sequencial]<>'0') || ($arrCsCarInstWNT[$sequencial] <> '' && $arrCsCarInstWNT[$sequencial] <> '0'));						
			if (!$cs_instalado)
				$cs_instalado   = (($arrCsCarVerW9X[$sequencial]<>'' && $arrCsCarVerW9X[$sequencial]<>'0') || ($arrCsCarVerWNT[$sequencial] <> '' && $arrCsCarVerWNT[$sequencial] <> '0'));						
				
			$cs_ide_licenca = ($arrCsIdeLicenca[$sequencial]<>'' && $arrCsIdeLicenca[$sequencial]<>'0');		
	
			$label = ($arrTeVersao[$sequencial]<>''?'Vers�o/Configura��o':'Instala��o Detectada');			
			if ($cs_instalado)			
				$te_car_inst = ($arrTeCarInstW9X[$sequencial] <> ''?$arrTeCarInstW9X[$sequencial]:$arrTeCarInstWNT[$sequencial]);
				
			$total_maquinas_versao += ($_POST['rdCsSaidaDetalhada']=='S'?1:$arrTotalEquip[$sequencial]);
			$sequencial ++;
			} //Fim do while
			
		if ($total_maquinas_versao > 0 && $cs_instalado && !$cs_ide_licenca)
			{
			?>
			<table width="50%" border="1" align="center">		
        	<tr valign="top" bgcolor="#E1E1E1"> 
	        <td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>		
    	    <td class="cabecalho_tabela"><div align="center"><? echo $label;?></div></td>
        	<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Maquinas');?></div></td>
	        <td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Percentual', T_SIGLA);?></div></td>		
    	    </tr>
			<?	
			$intSequenciaVersoes = 0;	
			for ($intAuxVersoes = 0; $intAuxVersoes < count($arrTeVersao); $intAuxVersoes ++)
				{
				$intSequenciaVersoes ++;
				$label 			= ($arrTeVersao[$intAuxVersoes]		<> '' ? 'Vers�o/Configura��o':'Instala��o Detectada');			
				$cs_instalado   = ($arrCsInstalado[$intAuxVersoes]  	<> '' && $arrCsInstalado[$intAuxVersoes] <> '0');			
				$cs_ide_licenca = ($arrCsIdeLicenca[$intAuxVersoes]	<> '' && $arrCsIdeLicenca[$intAuxVersoes] <> '0');		
				
				if ($cs_instalado && !$cs_ide_licenca || ($arrTeVersao[$intAuxVersoes]))
					{ 	
					if ($intAuxVersoes > 0 && $_POST['rdCsSaidaDetalhada']=='S')
						{
						?>
						<tr><td colspan="4"></td></tr>
						<tr><td colspan="4"></td></tr>                        
						<tr valign="top" bgcolor="#E1E1E1"> 
						<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>		
						<td class="cabecalho_tabela"><div align="center"><? echo $label;?></div></td>
						<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Maquinas');?></div></td>
						<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Percentual', T_SIGLA);?></div></td>		
						</tr>                    
						<?
						}
					?>			  
					<tr> 
					<td nowrap class="opcao_tabela"><div align="right"><? echo $intSequenciaVersoes; ?></a></div></td>			
					<td nowrap class="opcao_tabela"><div align="center"><a href="../../estatisticas/aplicativos/est_maquinas_aplicativos.php?teversao=<? echo $arrTeVersao[$intAuxVersoes]?>&idaplicativo=<? echo $arrIdAplicativo[$intAuxVersoes]?>&nmversao=<? echo $arrNmAplicativo[$intAuxVersoes]?>&cs_car_inst=<? echo ($arrCsCarInstWNT[$intAuxVersoes]<>''?$arrCsCarInstWNT[$intAuxVersoes]:$arrCsCarInstW9X[$intAuxVersoes]);?>" target="_blank"><? echo ($arrTeVersao[$intAuxVersoes]<>''?$arrTeVersao[$intAuxVersoes]:$label); ?></a></div></td>								
					<td nowrap class="opcao_tabela"><div align="right"><? echo ($_POST['rdCsSaidaDetalhada']=='S'?$arrTotalNaVersao[$arrTeVersao[$intAuxVersoes]]:$arrTotalEquip[$intAuxVersoes]); ?></div></td>
					<td nowrap class="ajuda"><div align="right"><? echo sprintf("%01.2f", ($_POST['rdCsSaidaDetalhada']=='S'?$arrTotalNaVersao[$arrTeVersao[$intAuxVersoes]]:$arrTotalEquip[$intAuxVersoes]) * 100 / $total_maquinas_versao); ?></div></td>
					</tr>
					<?
					
					// Caso o usu�rio tenha optado por Sa�da Detalhada...
					// Neste ponto ser�o exibidos os detalhes em forma de links para os registros de computadores constantes do resultado da consulta
					// Anderson Peterle - 31/Julho/2009
					if ($_POST['rdCsSaidaDetalhada']=='S')
						{
						?>
						<tr valign="top"> 
						<td></td>
						<td>
						<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
						<tr bgcolor="#E1E1E1">
						<td nowrap class="cabecalho_tabela" width="20%"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>	
						<td>|</td>			
						<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('Computador');?></div></td>				
						<td>|</td>			                    
						<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('SubRede');?></div></td>				                        
						<td>|</td>			                    
						<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('Local');?></div></td>				                        
						</tr>
						<?
					  
						$intSequencialDetalhes 	= 1;
						$intAuxDetalhes 		= $intAuxVersoes;					
						$te_versao 				= $arrTeVersao[$intAuxDetalhes];
						$strCorFundo			= '.';	
						while ($te_versao == $arrTeVersao[$intAuxDetalhes]) 
							{
							?>
							<tr <? if ($strCorFundo) { echo 'bgcolor="#CCCCFF"'; } ?>> 
							<td nowrap class="opcao_tabela" width="20%"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $intSequencialDetalhes; ?></a></div></td>				
							<td>|</td>			                        
							<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrTeIp[$intAuxDetalhes].' ('.$arrTeNomeComputador[$intAuxDetalhes].')'; ?></a></div></td>				
							<td>|</td>			                        
							<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrIdIpRede[$intAuxDetalhes].' ('.$arrNmRede[$intAuxDetalhes].')'; ?></a></div></td>				                        
							<td>|</td>			                        
							<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrNmLocal[$intAuxDetalhes]; ?></a></div></td>				                        
							</tr>
							<?
							$strCorFundo = !$strCorFundo;							
							$intAuxDetalhes ++;
							$intSequencialDetalhes ++;						
							}
						?>
						</table></td></tr>					
						<?
						$intAuxVersoes = ($intAuxDetalhes-1);	
						}
					//else
					//	echo $linha; 					
					}
				} //Fim do for
			?>
    	    <tr valign="top" bgcolor="#E1E1E1"> 
        	<td colspan="2" class="cabecalho_tabela"><div align="left"><?=$oTranslator->_('Total de maquinas');?></div></td>
	        <td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><? echo $total_maquinas_versao; ?></div></td>            
    	    <td bgcolor="#E1E1E1" class="opcao_tabela"><div align="right">&nbsp;&nbsp;&nbsp;100.00</div></td>				
        	</tr>
	        </table>					
			<?
			}
 		} //Fim do if das vers�es
		
		
	// Exibir informa��es sobre a quantidade de m�quinas e licen�as
	 $query_aplicativo_licencas = "SELECT 	a.te_licenca, 
	 										a.te_versao,
											a.id_aplicativo, 
											b.nm_aplicativo ".
											$_SESSION['select'] . ",
											computadores.te_nome_computador,
											computadores.te_ip, 
											computadores.id_ip_rede,
											computadores.id_so,
											computadores.te_node_address, 											
											b.cs_ide_licenca ".
											$strCountLicenca . " 
								   FROM  	aplicativos_monitorados a, 
								   			perfis_aplicativos_monitorados b, 
											computadores ".
											$_SESSION['from'] ." 
								   WHERE	a.te_node_address  = computadores.te_node_address AND 
											a.id_so = computadores.id_so AND 							
											a.id_aplicativo = ". $reg_id_aplicativo ." AND
											a.id_aplicativo = b.id_aplicativo AND 
											trim(a.te_licenca) <> '?' AND 								
											trim(a.te_licenca) <> 'none' AND 																
						 					trim(a.te_licenca) <> '' AND 
											redes.id_ip_rede = computadores.id_ip_rede AND 
											locais.id_local = redes.id_local AND										
											computadores.id_so IN (". $_SESSION['so_selecionados'] .") ".
											$_SESSION['query_redes'] .
											$strGroupByLicenca . 
											$strOrderByLicenca;

	$result_query_licencas = mysql_query($query_aplicativo_licencas);
	if (mysql_num_rows($result_query_licencas))
 		{ 
		// Crio vari�veis array a partir do resultado da consulta, para exibi��o de detalhes (reuso)
		// Anderson Peterle - 31/Julho/2009
		$arrTotalNaLicenca		= array();
		$arrTeLicenca 			= array();
		$arrTeVersao 			= array();		
		$arrTotalEquip 			= array();
		$arrIdAplicativo 		= array();
		$arrNmAplicativo		= array();
		$arrCsIdeLicenca		= array();
		$arrNmRede				= array();
		$arrNmLocal				= array();
		$arrTeNomeComputador	= array();
		$arrTeIp				= array();	
		$arrIdIpRede			= array();		
		$arrIdSo				= array();
		$arrTeNodeAddress		= array();			
		?>
		<table width="50%" border="1" align="center">		
		<tr valign="top" bgcolor="#FFFFFF"> 
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>	
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Licenca');?></div></td>
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Maquinas');?></div></td>
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Percentual', T_SIGLA);?></div></td>		        
		</tr>
		<?
		$total_maquinas_licenca = 0;		
		$sequencial = 0;
		while($reg_licencas = mysql_fetch_array($result_query_licencas)) 
			{ 
			$arrTeLicenca[$sequencial]			=	$reg_licencas['te_licenca'];
			$arrTeVersao[$sequencial]			=	$reg_licencas['te_versao'];			
			$arrIdAplicativo[$sequencial]		=	$reg_licencas['id_aplicativo'];
			$arrNmAplicativo[$sequencial]		=	$reg_licencas['nm_aplicativo'];
			$arrNmRede[$sequencial]				=	$reg_licencas['nm_rede'];
			$arrNmLocal[$sequencial]			=	$reg_licencas['nm_local'];
			$arrCsIdeLicenca[$sequencial]		=	$reg_licencas['cs_ide_licenca'];
			$arrTeNomeComputador[$sequencial]	=	$reg_licencas['te_nome_computador'];
			$arrTeIp[$sequencial]				=	$reg_licencas['te_ip'];
			$arrIdIpRede[$sequencial]			=	$reg_licencas['id_ip_rede'];
			$arrIdSO[$sequencial]				=	$reg_licencas['id_so'];
			$arrTeNodeAddress[$sequencial]		=	$reg_licencas['te_node_address'];
			
			$arrTotalEquip[$arrTeLicenca[$sequencial]] += ($_POST['rdCsSaidaDetalhada']=='S'?1:0);			
			
			$sequencial ++;			
			$total_maquinas_licenca += ($_POST['rdCsSaidaDetalhada']=='S'?1:$reg_licencas['total_equip']);		
			} //Fim do while
		
		$intSequenciaLicencas = 0;
		for ($intAuxLicencas = 0; $intAuxLicencas < count($arrTeLicenca); $intAuxLicencas ++)			
			{ 			
			$intSequenciaLicencas ++;
			$te_informacao = ($arrTeLicenca[$intAuxLicencas]?$arrTeLicenca[$intAuxLicencas]:$arrTeVersao[$intAuxLicencas]);
			if ($intAuxLicencas > 0 && $_POST['rdCsSaidaDetalhada']=='S')
				{
				?>
				<tr><td colspan="4"></td></tr>
				<tr><td colspan="4"></td></tr>                        
				<tr valign="top" bgcolor="#E1E1E1"> 
				<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>		
				<td class="cabecalho_tabela"><div align="center"><?=$oTranslator->_('Licenca');?></div></td>
				<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Maquinas');?></div></td>
				<td class="cabecalho_tabela"><div align="right"><?=$oTranslator->_('Percentual', T_SIGLA);?></div></td>		
				</tr>                    
				<?
				}							
			?>			  
	        <tr> 
    	    <td nowrap class="opcao_tabela"><div align="right"><? echo $intSequenciaLicencas; ?></a></div></td>			
    	    <td nowrap class="opcao_tabela"><div align="center"><a href="../../estatisticas/aplicativos/est_maquinas_aplicativos.php?telicenca=<? echo $arrTeLicenca[$intAuxLicencas]?>&idaplicativo=<? echo $arrIdAplicativo[$intAuxLicencas]?>&nmversao=<? echo $arrNmAplicativo[$intAuxLicencas]?>" target="_blank"><? echo $te_informacao; ?></a></div></td>
        	<td nowrap class="opcao_tabela"><div align="right"><? echo ($_POST['rdCsSaidaDetalhada']=='S'?$arrTotalEquip[$arrTeLicenca[$intAuxLicencas]]:$total_maquinas_licenca); ?></div></td>			
			<td nowrap class="opcao_tabela"><div align="right"><? echo sprintf("%01.2f", ($_POST['rdCsSaidaDetalhada']=='S'?$arrTotalEquip[$arrTeLicenca[$intAuxLicencas]]:$total_maquinas_licenca) * 100 / $total_maquinas_licenca); ?></div></td>            
	        </tr>	  
			<?
			//echo $linha; 
			// Caso o usu�rio tenha optado por Sa�da Detalhada...
			// Neste ponto ser�o exibidos os detalhes em forma de links para os registros de computadores constantes do resultado da consulta
			// Anderson Peterle - 31/Julho/2009
			if ($_POST['rdCsSaidaDetalhada']=='S')
				{
				?>
				<tr valign="top"> 
				<td></td>
				<td>
				<table border="0" align="center" cellpadding="0" cellspacing="0" width="80%">
				<tr bgcolor="#E1E1E1">
				<td nowrap class="cabecalho_tabela" width="20%"><div align="right"><?=$oTranslator->_('Sequencial');?></div></td>	
				<td>|</td>			
				<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('Computador');?></div></td>				
				<td>|</td>			                    
				<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('SubRede');?></div></td>				                        
				<td>|</td>			                    
				<td nowrap class="cabecalho_tabela" width="20%"><div align="left"><?=$oTranslator->_('Local');?></div></td>				                        
				</tr>
				<?
			  
				$intSequencialDetalhes 	= 1;
				$intAuxDetalhes 		= $intAuxLicencas;					
				$te_licenca				= $arrTeLicenca[$intAuxDetalhes];

				$strCorFundo			= '.';
				while ($te_licenca == $arrTeLicenca[$intAuxDetalhes]) 
					{
					?>
					<tr <? if ($strCorFundo) { echo 'bgcolor="#CCCCFF"'; } ?>> 
					<td nowrap class="opcao_tabela" width="20%"><div align="right"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $intSequencialDetalhes; ?></a></div></td>				
					<td>|</td>			                        
					<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrTeIp[$intAuxDetalhes].' ('.$arrTeNomeComputador[$intAuxDetalhes].')'; ?></a></div></td>				
					<td>|</td>			                        
					<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrIdIpRede[$intAuxDetalhes].' ('.$arrNmRede[$intAuxDetalhes].')'; ?></a></div></td>				                        
					<td>|</td>			                        
					<td nowrap class="opcao_tabela" width="20%"><div align="left"><a href="../../relatorios/computador/computador.php?te_node_address=<? echo $arrTeNodeAddress[$intAuxDetalhes];?>&id_so=<? echo $arrIdSO[$intAuxDetalhes];?>" target="_blank"><? echo $arrNmLocal[$intAuxDetalhes]; ?></a></div></td>				                        
					</tr>
					<?
					$strCorFundo = !$strCorFundo;												
					$intAuxDetalhes ++;
					$intSequencialDetalhes ++;						
					}
				?>
				</table></td></tr>					
				<?
				$intAuxLicencas = ($intAuxDetalhes-1);	
				}
			
			} //Fim do for
			?>
		</tr>
        <tr valign="top" bgcolor="#E1E1E1"> 
        <td class="cabecalho_tabela" colspan="2"><div align="left"><?=$oTranslator->_('Total de maquinas');?></div></td>
        <td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><? echo $total_maquinas_licenca; ?></div></td>	
   	    <td bgcolor="#E1E1E1" class="opcao_tabela"><div align="right">&nbsp;&nbsp;&nbsp;100.00</div></td>				        	
        </tr>
        </table>							
		<?		
		} //Fim do if else das vers�es
	}
	?>
</p>

<table><br><br><tr><td class="descricao_rel">
<p align="left">
  <?=$oTranslator->_('Gerado por');?> <b>CACIC</b> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais<br>Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</p>	
</td></tr></table>  
</body>
</html>