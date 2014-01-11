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
// Esse arquivo é um arquivo de include, usado pelo arquivo compuatdor.php. 
if (!$_SESSION['hardware'])
	$_SESSION['hardware'] = false;
if ($exibir == 'hardware')
	$_SESSION['hardware'] = !($_SESSION['hardware']);
else
	$_SESSION['hardware'] = false;
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=hardware&id_computador=<?php echo $_GET['id_computador']?>"> 
      <img src="../../imgs/<?php if($_SESSION['hardware'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">&nbsp;<?php echo $oTranslator->_('Hardware instalado');?></a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?php

  $strCor = '';  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
		if ( $_SESSION['hardware'] == true) {
		// EXIBIR INFORMAÇÕES DE HARDWARE DO COMPUTADOR
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_hardware' AND
					  			id_rede = '".mysql_result($result,0,'id_rede')."'";
			$result_acoes =  mysql_query($query);
			if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
		?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td></td>
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Placa mae');?></td>
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "te_placa_mae_desc"); ?></b></td>	
  </tr>
  <?php
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						    
  ?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="2"></td>  
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Fabricante');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "te_placa_mae_fabricante"); ?></b></td>	
  </tr>
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						      
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('BIOS');?></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_bios_desc"); ?></b></td>	
  </tr>
  <?php
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						    
  ?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="2"></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Fabricante');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_bios_fabricante"); ?></b></td>	
  </tr>
  
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						 
  $arrMemRAM = explode('Slot ',mysql_result($result, 0, "te_mem_ram_desc")); 
  $intContaSlots = 0;
  for ($intRAM=0;$intRAM < count($arrMemRAM);$intRAM++)
  	{
	if ($arrMemRAM[$intRAM]<>'')
		{
		$strSlot = $intContaSlots.':';
  		?> 
	  	<tr bgcolor="<?php echo $strCor;?>"> 
    	<td></td>
	    <td class="opcao_tabela" align="left"><?php echo ($intContaSlots==0?$oTranslator->_('Memoria RAM'):'');?></td>
    	<td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Slot');?> <?php echo $intContaSlots;?>:</td>
    	<td class="opcao_tabela" align="left"><b><?php echo str_replace($intContaSlots.': ','',str_replace(' - ','',$arrMemRAM[$intRAM])); ?></b></td>		
	  	</tr>
  		<?php 	
		$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  	
		$intContaSlots ++;
		}
	}
  
  echo $linha;
  ?> 
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left"><?php echo $oTranslator->_('Teclado');?></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_teclado_desc"); ?></b></td>	
  </tr>

  
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<?php echo $strCor;?>"> 
  	<td>&nbsp;</td>
  	<td class="OPCAO_TABELA" 			align="left"><?php echo $oTranslator->_('Mouse');?></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_mouse_desc"); ?></b></td>	
  	</tr>

  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left"><?php echo $oTranslator->_('Modem');?></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_modem_desc"); ?></b></td>	
  </tr>

  <?php echo $linha;  
  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  	
  ?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left"><?php echo $oTranslator->_('Placa de som');?></td>
    <td class="opcao_tabela" 	align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" 	align="left"><b><?php echo mysql_result($result, 0, "te_placa_som_desc"); ?></b></td>	
  </tr>	
  	<?php 
  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
 	echo $linha;
	?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td></td>
    <td class="opcao_tabela" 		 align="left"><?php echo $oTranslator->_('Placa de video');?></td>
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Descricao');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "te_placa_video_desc"); ?></b></td>	
  </tr>
  <?php 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="2"></td>
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Quantidade de cores');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "qt_placa_video_cores"); ?></b></td>	
  </tr>
  <?php 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="2"></td>	
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Resolucao');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "te_placa_video_resolucao"); ?></b></td>	
  </tr>
  <?php 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="2"></td>	
    <td class="opcao_tabela" align="left"><?php echo $oTranslator->_('Quantidade de memoria');?></td>
    <td class="opcao_tabela" align="left"><b><?php echo mysql_result($result, 0, "qt_placa_video_mem").' MB'; ?></b></td>	
  </tr>
	<?php echo $linha;
	// Obtenho os nomes dos hardwares passíveis de controle
	$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
  
	$strQueryTotalizaGeralExistentes = ' SELECT  	cs_tipo_componente,
												 	te_valor
								 		 FROM	 	componentes_estacoes
										 WHERE   	id_computador = '.mysql_result($result, 0, "id_computador") . '
										 ORDER BY 	cs_tipo_componente,te_valor';
	$resultTotalizaGeralExistentes   = mysql_query($strQueryTotalizaGeralExistentes) or die($oTranslator->_('falha na consulta a tabela (%1) ou sua sessao expirou!', array('componentes_estacoes')));

	$strComponenteAtual = '';
	$intSequencial      = 0;
  	while ($rowTotalizaGeralExistentes = mysql_fetch_array($resultTotalizaGeralExistentes))
  		{
		if ($strComponenteAtual <> $rowTotalizaGeralExistentes['cs_tipo_componente'])
			{
			$strComponenteAtual = $rowTotalizaGeralExistentes['cs_tipo_componente'];
			$intSequencial = 1;
			}
		else
			$intSequencial ++;

		$arrColunasValores = explode('#FIELD#',$rowTotalizaGeralExistentes['te_valor']);
		for ($i=0; $i<count($arrColunasValores);$i++)
			{
			$arrColunas = explode('###',$arrColunasValores[$i]);	
		  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  						
			?>	
			<tr bgcolor="<?php echo $strCor;?>"> 
			<td>&nbsp;</td>
			<?php if ($i > 0)
				{
				?>
				<td>&nbsp;</td>				
				<?php
				}
			?>
			<td class="opcao_tabela"><?php echo $arrDescricoesColunasComputadores[$arrColunas[0]].($i==0?' '.$intSequencial:':');?></td>
			<?php if ($i == 0)
				{
				?>
				<td class="opcao_tabela"><?php echo $oTranslator->_('Descricao');?></td>				
				<?php
				}
			?>
			<td class="dado" colspan="3"><?php echo $arrColunas[1]; ?></td>
			</tr>
			<?php
			}
		echo $linha;			
		}

  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  		
  	?> 
  	<tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center"><br>
          <input name="historico_hardware" type=submit id="historico_hardware" value="<?php echo $oTranslator->_('Historico de alteracoes na configuracao de hardware');?>" >
          <br>
          &nbsp; 
          <input name="te_node_address" type="hidden" id="te_node_address" value="<?php echo mysql_result($result, 0, "te_node_address");?>">
          <input name="id_so" type="hidden" id="id_so" value="<?php echo mysql_result($result, 0, "id_so");?>">
          <input name="id_computador" type="hidden" id="id_computador" value="<?php echo mysql_result($result, 0, "id_computador");?>">          
        </div>
      </form></td>
  </tr>
  <?php		
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">'.
						$oTranslator->_('O modulo de coleta de informacoes de hardware nao foi habilitado pelo Administrador').
						'</font></div>
					  </td></tr>';
			}
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE HARDWARE DO COMPUTADOR
		?>
</table>
