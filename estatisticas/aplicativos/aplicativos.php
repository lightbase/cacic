<?
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
  die('Acesso negado (Access denied)!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if($_POST['submit']) 
	{
	$_SESSION["list2"] = $_POST['list2']; //Redes selecionadas
	$_SESSION["list4"] = $_POST['list4']; //SO selecionados
	$_SESSION["list6"] = $_POST['list9']; //Aplicativos selecionados
	$_SESSION["list8"] = $_POST['list8'];	
	$_SESSION["list12"] = $_POST['list12']; //Locais Selecionados (No caso de Nível Administrativo)		
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
    <td bgcolor="#FFFFFF" class="cabecalho_rel">Estat&iacute;sticas 
      de Sistemas Monitorados</td>
  </tr>
  <tr> 
    <td height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td><p class="descricao">Gerado em <? echo date("d/m/Y à\s H:i"); ?></p></td>
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
$_SESSION['query_redes'] = ' AND computadores.id_ip_rede = redes.id_ip_rede AND ';
if ($_SESSION['cs_nivel_administracao']<>1 && $_SESSION['cs_nivel_administracao']<>2)
	{
	// Aqui pego todas as redes selecionadas e faço uma query p/ condição de redes
	$_SESSION['redes_selecionadas'] = '';
	for( $i = 0; $i < count($_SESSION["list2"] ); $i++ ) 
		{
		if ($_SESSION['redes_selecionadas'])
			$_SESSION['redes_selecionadas'] .= ',';
		$_SESSION['redes_selecionadas'] .= "'".$_SESSION["list2"][$i]."'";
		}

	$_SESSION['query_redes'] .= 'redes.id_ip_rede IN ('. $_SESSION['redes_selecionadas'] .')';	
	}
else
	{
	// Aqui pego todos os locais selecionados e faço uma query p/ condição de redes/locais
	$locais_selecionados = '';
	for( $i = 0; $i < count($_SESSION["list12"] ); $i++ ) 
		{
		if ($locais_selecionados)
			$locais_selecionados .= ',';
		$locais_selecionados .= $_SESSION["list12"][$i];
		}

	$_SESSION['query_redes'] .= 'redes.id_local = locais.id_local ';
	if ($locais_selecionados)
		$_SESSION['query_redes'] .= ' AND locais.id_local IN ('.$locais_selecionados.') ';
		
	$_SESSION['select'] = ' ,sg_local as SgLocal ';	
	$_SESSION['from'] = ' ,locais ';			
	}

$_SESSION['from'] .= ',redes ';

// Aqui pego todos os SO selecionados
$so_selecionados = "'" . $_SESSION["list4"][0] . "'";
for( $i = 1; $i < count($_SESSION["list4"] ); $i++ ) 
	{
	$so_selecionados = $so_selecionados . ",'" . $_SESSION["list4"][$i] . "'";
	}
$_SESSION["so_selecionados"] = $so_selecionados;

//Pego os aplicativos selecionados para o relatório

$aplicativos_selecionados = '';
for( $i = 0; $i < count($_SESSION["list6"] ); $i++ ) 
	{
	if ($aplicativos_selecionados)
		$aplicativos_selecionados .= ',';
	$aplicativos_selecionados .= $_SESSION["list6"][$i] ;
	}

// Exibir as aplicações	 baseado no que foi solicitado	
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
?>
</p>
<table width="100%" border="0" align="center">
<td>
<? 
while($reg_selecao = @mysql_fetch_array($result_query_selecao))
	{
	?>
   	<table width="50%" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr bordercolor="#FFFFFF" bgcolor="#E1E1E1"> 
    <td colspan="3" bgcolor="#FFFFFF" nowrap class="cabecalho_tabela">	
	<div align="center">
	<? 
	$v_nm_aplicativo = $reg_selecao['nm_aplicativo'];
	echo $v_nm_aplicativo;
	$reg_id_aplicativo = $reg_selecao['id_aplicativo'];
	?> 
	</div>
	</td>
    </tr>
	<?
	$groupBy = '';
	$orderBy = '';	
	$where   = '';
	if ($reg_selecao['cs_car_inst_w9x'] > 0 || $reg_selecao['cs_car_inst_wnt'] > 0)
		{
		$where   = " AND a.cs_instalado = 'S' ";
		}	
	elseif ($reg_selecao['cs_car_ver_w9x'] > 0 && $reg_selecao['cs_car_ver_wnt'] > 0)
		{
		$where   = " AND a.te_versao <> '' ";
//		$groupBy = ', a.te_versao ';
//		$orderBy = ', a.te_versao ';		
		}
	
	// Exibir informações sobre a quantidade de máquinas e versões instaladas	
 	$query_aplicativo ="SELECT DISTINCT a.te_versao, 
										COUNT(a.te_versao) as total_equip, 
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
										b.cs_ide_licenca 
					 FROM  				aplicativos_monitorados a, 
					 					perfis_aplicativos_monitorados b, 
										computadores ".
										$_SESSION['from']." 
					 WHERE 				a.te_node_address  = computadores.te_node_address AND 
					 					a.id_so = computadores.id_so AND 
					 					a.id_aplicativo = ".$reg_id_aplicativo. " AND 
					 					a.id_aplicativo = b.id_aplicativo " . $_SESSION['query_redes'] . " AND 
					 					computadores.id_so IN (". $_SESSION['so_selecionados'] .") ".
										$where ."
					 GROUP BY 			a.te_versao ".
					 					$groupBy."  
					 ORDER BY 			total_equip DESC,a.te_versao,b.nm_aplicativo ".
					 					$orderBy;
//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	echo 'query_aplicativo: '.$query_aplicativo . '<br><br>';
										
	$result_query_versoes = mysql_query($query_aplicativo);
	/*
	Informações importantes:
	
	perfis_aplicativos_monitorados
	==============================
	cs_car_inst_w9x 1->Executável       2->INI       3->Registry
	cs_car_inst_wnt 1->Executável       2->INI       3->Registry	
	cs_car_ver_w9x  1->Data de Arquivo  2->Registry  3->INI       4->Versão de Executável
	cs_car_ver_wnt  1->Data de Arquivo  2->Registry  3->INI       4->Versão de Executável
	cs_ide_licenca  1->Registry         2->INI
	
	
	aplicativos_monitorados
	=======================
	cs_instalado	S/N
	*/
	$total_maquinas = 0;
	$sequencial = 1;

	$in_se_instalado = false;
	if (mysql_num_rows($result_query_versoes))
		{ 
		while($reg_versoes = mysql_fetch_array($result_query_versoes)) 
			{ 
			$te_versao      =  $reg_versoes['te_versao'];									
			//$cs_instalado   = ($reg_versoes['cs_instalado']  <>'' && $reg_versoes['cs_instalado']<>'0');			
			
			$cs_instalado   = (($reg_versoes['cs_car_inst_w9x']<>'' && $reg_versoes['cs_car_inst_w9x']<>'0') || ($reg_versoes['cs_car_inst_wnt'] <> '' && $reg_versoes['cs_car_inst_wnt'] <> '0'));						
			if (!$cs_instalado)
				$cs_instalado   = (($reg_versoes['cs_car_ver_w9x']<>'' && $reg_versoes['cs_car_ver_w9x']<>'0') || ($reg_versoes['cs_car_ver_wnt'] <> '' && $reg_versoes['cs_car_ver_wnt'] <> '0'));						
				
			$cs_ide_licenca = ($reg_versoes['cs_ide_licenca']<>'' && $reg_versoes['cs_ide_licenca']<>'0');		
	
			$label = ($te_versao<>''?'Versão/Configuração':'Instalação Detectada');			
			if ($cs_instalado)			
				$te_car_inst = ($reg_versoes['te_car_inst_w9x'] <> ''?$reg_versoes['te_car_inst_w9x']:$reg_versoes['te_car_inst_wnt']);
				
			$total_maquinas += $reg_versoes['total_equip'];

			} //Fim do while
		if ($total_maquinas > 0 && $cs_instalado && !$cs_ide_licenca)
			{
			?>
			<table width="50%" border="0" align="center">		
        	<tr valign="top" bgcolor="#E1E1E1"> 
	        <td class="cabecalho_tabela"><div align="left">Seq.</div></td>		
    	    <td class="cabecalho_tabela"><div align="left"><? echo $label;?></div></td>
			<? 
			/*
			if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{
				?>
				<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Local</div></td>		
				<?
				}
			*/
			?>
			
        	<td class="cabecalho_tabela"><div align="right">M&aacute;quinas</div></td>
	        <td class="cabecalho_tabela"><div align="right">%</div></td>		
    	    </tr>
			<?		

		mysql_data_seek($result_query_versoes,0);
		while($reg_versoes = mysql_fetch_array($result_query_versoes)) 
			{
			$label = ($te_versao<>''?'Versão/Configuração':'Instalação Detectada');			
			$cs_instalado   = ($reg_versoes['cs_instalado']  <>'' && $reg_versoes['cs_instalado']<>'0');			
			$cs_ide_licenca = ($reg_versoes['cs_ide_licenca']<>'' && $reg_versoes['cs_ide_licenca']<>'0');		
			$te_versao      =  $reg_versoes['te_versao'];					

			//echo 'cs_instalado: '.$cs_instalado.' => '.$reg_versoes['cs_instalado'].'<br>';
			//echo 'cs_ide_licenca: '.$cs_ide_licenca.' => '.$reg_versoes['cs_ide_licenca'].'<br>';			
			//echo 'te_versao: '.$reg_versoes['te_versao'].'<br>';						
			$cs_ide_licenca = ($reg_versoes['cs_ide_licenca']<>'' && $reg_versoes['cs_ide_licenca']<>'0');		
			
			if ($cs_instalado && !$cs_ide_licenca)
				{ 	
				?>			  
    	      	<tr> 
        	    <td nowrap class="opcao_tabela"><div align="left"><? echo $sequencial; ?></a></div></td>			
            	<td nowrap class="opcao_tabela"><div align="left"><a href="../../estatisticas/aplicativos/est_maquinas_aplicativos.php?teversao=<? echo $reg_versoes['te_versao']?>&idaplicativo=<? echo $reg_versoes['id_aplicativo']?>&nmversao=<? echo $reg_versoes['nm_aplicativo']?>&cs_car_inst=<? echo ($reg_versoes['cs_car_inst_wnt']<>''?$reg_versoes['cs_car_inst_wnt']:$reg_versoes['cs_car_inst_w9x']);?>" target="_blank"><? echo ($te_versao<>''?$reg_versoes['te_versao']:$label) ?></a></div></td>				
			<? 
			/*
			if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{
				?>
				<td nowrap class="opcao_tabela"><div align="left"><? echo $reg_versoes['SgLocal']; ?></div></td>		
				<?
				}
			*/
				?>        	
				
	            <td nowrap class="opcao_tabela"><div align="right"><? echo $reg_versoes['total_equip']; ?></div></td>
    	        <td nowrap class="ajuda"><div align="right">&nbsp;&nbsp;&nbsp;<? echo sprintf("%01.2f", $reg_versoes['total_equip'] * 100 / $total_maquinas); ?></div></td>
        	  	</tr>
				<?
				$sequencial ++;
				echo $linha; 
				}
			} //Fim do while
			?>
    	    <tr valign="top" bgcolor="#E1E1E1"> 
        	<td colspan="2" class="cabecalho_tabela"><div align="left">Total de Máquinas</div></td>
			<?
			/* 
			if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{
				?>
				<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">&nbsp;</div></td>		
				<?
				}
			*/
			?>
			
	        <td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><? echo $total_maquinas; ?></div></td>
    	    <td bgcolor="#E1E1E1" class="opcao_tabela"><div align="right">&nbsp;&nbsp;&nbsp;100.00</div></td>				
        	</tr>
	        </table>					
			<?
			}
 		} //Fim do if das versões
	// Exibir informações sobre a quantidade de máquinas e licenças
	 $query_aplicativo_licencas = "SELECT 	a.te_licenca, 
	 										COUNT(a.te_licenca) as total_equip, 
											a.id_aplicativo, 
											b.nm_aplicativo ".
											$_SESSION['select'] . ",
											b.cs_ide_licenca 
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
											computadores.id_so IN (". $_SESSION['so_selecionados'] .") ".
											$_SESSION['query_redes'] ." 
								GROUP BY 	a.id_aplicativo, a.te_licenca
								ORDER BY 	total_equip desc";
//if ($_SERVER['REMOTE_ADDR']=='10.71.0.58')
//	echo 'query_aplicativo_licencas: '.$query_aplicativo_licencas . '<br><br>';

	$result_query_licencas = mysql_query($query_aplicativo_licencas);
	if (mysql_num_rows($result_query_licencas))
 		{ 
		?>
		<table width="50%" border="0" align="center">		
		<tr valign="top" bgcolor="#FFFFFF"> 
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Seq.</div></td>		
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Licen&ccedil;a</div></td>
		<? 
		/*
		if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
			{
			?>
			<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">Local</div></td>		
			<?
			}
		*/
		?>
		<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right">M&aacute;quinas</div></td>
		</tr>
		<?
		$total_maquinas = 0;		
		$sequencial = 1;
		while($reg_licencas = mysql_fetch_array($result_query_licencas)) 
			{ 
			$te_informacao = ($reg_licencas['te_licenca']?$reg_licencas['te_licenca']:$reg_licencas['te_versao']);
			?>			  
	        <tr> 
    	    <td nowrap class="opcao_tabela"><div align="left"><? echo $sequencial; ?></a></div></td>			
    	    <td nowrap class="opcao_tabela"><div align="left"><a href="../../estatisticas/aplicativos/est_maquinas_aplicativos.php?telicenca=<? echo $reg_licencas['te_licenca']?>&idaplicativo=<? echo $reg_licencas['id_aplicativo']?>&nmversao=<? echo $reg_licencas['nm_aplicativo']?>" target="_blank"><? echo $te_informacao; ?></a></div></td>
			<? 
			/*
			if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
				{
				?>
				<td nowrap class="opcao_tabela"><div align="left"><? echo $reg_licencas[4]; ?></div></td>		
				<?
				}
			*/
			?>        	
        	<td nowrap class="opcao_tabela"><div align="right"><? echo $reg_licencas['total_equip'] ?></div></td>			
	        </tr>	  
			<?
			echo $linha; 
			$sequencial ++;
			$total_maquinas += $reg_licencas['total_equip'];			
			} //Fim do while
			?>
		</tr>
        <tr valign="top" bgcolor="#E1E1E1"> 
        <td class="cabecalho_tabela" colspan="2"><div align="left">Total de M&aacute;quinas</div></td>

		<? 
		/*
		if ($_SESSION['cs_nivel_administracao']==1 || $_SESSION['cs_nivel_administracao']==2)
			{
			?>
			<td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="left">&nbsp;</div></td>		
			<?
			}
		*/
		?>
		
        <td bgcolor="#E1E1E1" class="cabecalho_tabela"><div align="right"><? echo $total_maquinas; ?></div></td>		
        </tr>
        </table>							
		<?
		} //Fim do if else das versões
	?>	
	</table>
	<br><br>		
	</table>
	<? 
	}

	if (!$result_query_versoes)
	 	{
		?>
		<tr  align="center"> 
	    <td colspan="3" align="center" class="label_vermelho">A pesquisa não retornou registros</td>
	    </tr>
		<?
		} 
	
	?>
</p>

<table><br><br><tr><td class="descricao_rel">
<p align="left">Relat&oacute;rio 
  gerado pelo <b>CACIC</b> - Configurador Autom&aacute;tico e Coletor 
  de Informa&ccedil;&otilde;es Computacionais<br>Software desenvolvido 
  pela Dataprev - Unidade Regional Esp&iacute;rito Santo</p>	
</td></tr></table>  
</body>
</html>