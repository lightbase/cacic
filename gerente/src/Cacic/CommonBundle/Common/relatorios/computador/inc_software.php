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
if (!$_SESSION['software'])
	$_SESSION['software'] = false;
if ($exibir == 'software')
	$_SESSION['software'] = !($_SESSION['software']);
else
	$_SESSION['software'] = false;
$strCor = '';  
$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  

?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
<tr><td colspan="5" height="1" bgcolor="#333333"></td></tr>
<tr> 
<td bgcolor="#E1E1E1" colspan="5" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=software&id_computador=<?php echo $_GET['id_computador']?>"> 
<img src="../../imgs/<?php if($_SESSION['software'] == true) 
	echo 'menos';
else 
	echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_('Versoes de softwares basicos');?></a></td>
</tr>
<tr><td colspan="5" height="1" bgcolor="#333333"></td></tr>
<?php if ($_SESSION['software'] == true) 
	{
	// EXIBIR INFORMAÇÕES DE SOFTWARE DO COMPUTADOR
	$query = "SELECT 	cs_situacao
			  FROM 		acoes_redes 
			  WHERE 	id_acao = 'cs_coleta_software' AND
			  			id_rede = '".mysql_result($result,0,'id_rede')."'";
	$result_acoes =  mysql_query($query);
				
	if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
		{
		$query = "SELECT * FROM versoes_softwares
				  WHERE id_computador = ". $_GET['id_computador'];
		$result_software = mysql_query($query);
		if(mysql_num_rows($result_software) > 0) 
			{
			?>
	  		<tr bgcolor="<?php echo $strCor;?>"> 
    		<td>&nbsp;</td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Sistema operacional');?></td>
    		<td class="dado"><?php echo mysql_result($result, 0, "te_desc_so"); ?></td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do DirectX');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_directx"); ?></td>
  			</tr>
  			<?php echo $linha;
  			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  			?> 
  			<tr bgcolor="<?php echo $strCor;?>"> 
    		<td>&nbsp;</td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do internet explorer');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_ie"); ?></td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do ODBC');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_odbc"); ?></td>
  			</tr>
  			<?php echo $linha;
  			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  			?> 
  			<tr bgcolor="<?php echo $strCor;?>"> 
    		<td>&nbsp;</td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do Mozilla');?></td>
    		<td class="dado"><div align="left"><?php echo mysql_result($result_software, 0, "te_versao_mozilla"); ?></div></td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do DAO');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_dao"); ?></td>
  			</tr>
  			<?php echo $linha;
  			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  			?> 
  			<tr bgcolor="<?php echo $strCor;?>"> 
    		<td>&nbsp;</td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do Acrobat Reader');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_acrobat_reader"); ?></td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao do ADO');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_ado"); ?></td>
  			</tr>
  			<?php echo $linha;
  			$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  			?> 
  			<tr bgcolor="<?php echo $strCor;?>"> 
    		<td>&nbsp;</td>
    		<td class="opcao_tabela"><?php echo $oTranslator->_('Versao da maquina virtual java (JVM)');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_jre"); ?></td>
    		<td><?php echo $oTranslator->_('Versao do BDE');?></td>
    		<td class="dado"><?php echo mysql_result($result_software, 0, "te_versao_bde"); ?></td>
  			</tr>
  			<?php
			}
		else 
			{
			echo '	<tr><td> 
					<p>
					<div align="center">
					<br>
					<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
					'.$oTranslator->_('Nao foram coletadas informacoes de software referente a esta maquina').'
					</font></div>
					</p>
					</td></tr>';
			}
		}
	else 
		{
		echo   '<tr><td> 
				<div align="center">
				<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
				'.$oTranslator->_('O modulo de coleta de informacoes de software nao foi habilitado pelo Administrador').'
				</font></div>
				</td></tr>';
		}
	}
	// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE SOFTWARE DO COMPUTADOR
	?>
</table>
