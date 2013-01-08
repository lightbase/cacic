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
// Esse arquivo é um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['aplicativo_monitorado'])
	$_SESSION['aplicativo_monitorado'] = false;
if ($exibir == 'aplicativo_monitorado')
	{
	$_SESSION['aplicativo_monitorado'] = !($_SESSION['aplicativo_monitorado']);
	}
else
	{
	$_SESSION['aplicativo_monitorado'] = false;
	}
	
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr> 
	<td height="1" bgcolor="#333333" colspan="6"></td>
</tr>

<tr bgcolor="#E1E1E1"> 
<td colspan="6" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=aplicativo_monitorado&id_computador=<?php echo $_GET['id_computador']?>"> 
<img src="../../imgs/<?php if($_SESSION['aplicativo_monitorado'] == true) 
							echo 'menos';
   			 			else 
							echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_('Sistemas monitorados');?></a></font>
</td>

</tr>
<tr> 
<td height="1" bgcolor="#333333" colspan="6"></td>
</tr>

<?php if ($_SESSION['aplicativo_monitorado'] == true) 
	{
	$linha = '	<tr bgcolor="'.$strCorDaLinha.'"> 
				<td height="1" colspan="6"></td>
				</tr>';
		
	?>
	<tr> 
	<td class="cabecalho_tabela">&nbsp;<u><?php echo $oTranslator->_('Nome');?></u></td>
	<td class="cabecalho_tabela"><u><?php echo $oTranslator->_('Identificador/versao');?></u></td>		
	<td class="cabecalho_tabela"><u><?php echo $oTranslator->_('Licenca');?></u></td>	
	<td class="cabecalho_tabela"><u><?php echo $oTranslator->_('Engine');?></u></td>	
	<td class="cabecalho_tabela"><u><?php echo $oTranslator->_('Pattern');?></u></td>	
	<td class="cabecalho_tabela"><u><?php echo $oTranslator->_('Instalado');?></u></td>		
	</tr>
	<?php echo $linha;		
	// EXIBIR INFORMAÇÕES DE SISTEMAS MONITORADOS NO COMPUTADOR
	$query = "SELECT 	cs_situacao
			  FROM 		acoes_redes 
			  WHERE 	id_acao = 'cs_coleta_monitorado' AND
						id_rede = ".mysql_result($result,0,'id_rede');
	$result_acoes =  mysql_query($query);
			
	if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
		{
		$query = "	SELECT 		DISTINCT pam.nm_aplicativo, am.te_versao,am.te_licenca, am.te_ver_engine, am.te_ver_pattern, am.cs_instalado 
					FROM 		aplicativos_monitorados am,
								perfis_aplicativos_monitorados pam
					WHERE 		pam.nm_aplicativo NOT LIKE '%#DESATIVADO#%' and (am.id_computador = ".$_GET['id_computador']." AND 
								am.id_aplicativo = pam.id_aplicativo AND
								(trim(am.te_versao)<>'' OR 
								 trim(am.te_licenca)<>'' OR 
								 trim(am.te_ver_engine)<>'' OR
								 trim(am.te_ver_pattern)<>'' OR
								 trim(am.cs_instalado)<>''))											
					ORDER BY	pam.nm_aplicativo";

		$result_software = mysql_query($query);
		$v_achei = 0;
		$strCor = '';
		while ($row = mysql_fetch_array($result_software)) 
			{
			$v_achei = 1;
			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  			
			?>
			<tr bgcolor="<?php echo $strCor;?>"> 
			<td class="descricao">&nbsp;<?php echo $row['nm_aplicativo']; ?></td>
			<td class="descricao"><?php echo $row['te_versao']; ?></td>
			<td class="descricao"><?php echo $row['te_licenca']; ?></td>	
			<td class="descricao"><?php echo $row['te_ver_engine']; ?></td>	
			<td class="descricao"><?php echo $row['te_ver_pattern']; ?></td>	
			<td class="descricao"><?php echo $row['cs_instalado']; ?></td>		
			</tr>
			<?php echo $linha;
			}
		if (!$v_achei)
			{
			echo '<tr><td colspan="6"> 
					<p>
					<div align="center">
					<br>
					<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
					'.$oTranslator->_('Nao foram coletadas informacoes de aplicativos monitorados referente a esta maquina').'
					</font></div>
					</p>
				  </td></tr>';
			}
		}
	else 
		{
		echo '<tr><td colspan="6"> 
				<div align="center">
				<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
				'.$oTranslator->_('O modulo de coleta de informacoes de sistemas monitorados nao foi habilitado pelo administrador').'
				</font></div>
			  </td></tr>';
		}
	}
	// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE SISTEMAS MONITORADOS DO COMPUTADOR
	?>
</table>
