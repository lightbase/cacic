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
// Esse arquivo � um arquivo de include, usado pelo arquivo computador.php. 
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
	// EXIBIR INFORMA��ES DE SOFTWARE DO COMPUTADOR
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
	// FIM DA EXIBI��O DE INFORMA��ES DE SOFTWARE DO COMPUTADOR
	?>
</table>
