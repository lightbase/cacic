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
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=hardware&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['hardware'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0"> Hardware  Instalado</a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?

  $strCor = '';  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
		if ( $_SESSION['hardware'] == true) {
		// EXIBIR INFORMAÇÕES DE HARDWARE DO COMPUTADOR
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_hardware' AND
					  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
			$result_acoes =  mysql_query($query);
			if (mysql_result($result_acoes, 0, "cs_situacao") <> 'N') {
		?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td></td>
    <td class="opcao_tabela"         align="left">Placa M&atilde;e</td>
    <td class="opcao_tabela" align="left">Descri&ccedil;&atilde;o:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "te_placa_mae_desc"); ?></b></td>	
  </tr>
  <?
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						    
  ?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="2"></td>  
    <td class="opcao_tabela" align="left">Fabricante:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "te_placa_mae_fabricante"); ?></b></td>	
  </tr>
  <?
  echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						      
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left">BIOS</td>
    <td class="opcao_tabela" 	align="left">Descrição:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_bios_desc"); ?></b></td>	
  </tr>
  <?
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						    
  ?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="2"></td>
    <td class="opcao_tabela" 	align="left">Fabricante:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_bios_fabricante"); ?></b></td>	
  </tr>
  
  <? 
  echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						 
  $arrMemRAM = explode('Slot ',mysql_result($result, 0, "te_mem_ram_desc")); 
  $intContaSlots = 0;
  for ($intRAM=0;$intRAM < count($arrMemRAM);$intRAM++)
  	{
	if ($arrMemRAM[$intRAM]<>'')
		{
		$strSlot = $intContaSlots.':';
  		?> 
	  	<tr bgcolor="<? echo $strCor;?>"> 
    	<td></td>
	    <td class="opcao_tabela" 		 align="left"><? echo ($intContaSlots==0?'Mem&oacute;ria RAM':'');?></td>
    	<td class="opcao_tabela" align="left">Slot <? echo $intContaSlots;?>:</td>
    	<td class="opcao_tabela" align="left"><b><? echo str_replace($intContaSlots.': ','',str_replace(' - ','',$arrMemRAM[$intRAM])); ?></b></td>		
	  	</tr>
  		<? 	
		$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  	
		$intContaSlots ++;
		}
	}
  
  echo $linha;
  ?> 
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left">Teclado</td>
    <td class="opcao_tabela" 	align="left">Descrição:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_teclado_desc"); ?></b></td>	
  </tr>

  
  <? 
  echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>"> 
  	<td>&nbsp;</td>
  	<td class="OPCAO_TABELA" 			align="left">Mouse</td>
    <td class="opcao_tabela" 	align="left">Descrição:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_mouse_desc"); ?></b></td>	
  	</tr>

  <? 
  echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left">Modem</td>
    <td class="opcao_tabela" 	align="left">Descrição:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_modem_desc"); ?></b></td>	
  </tr>

  <?
  	echo $linha;  
  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  	
  ?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela" 		 	align="left">Placa de Som</td>
    <td class="opcao_tabela" 	align="left">Descrição:</td>
    <td class="opcao_tabela" 	align="left"><b><? echo mysql_result($result, 0, "te_placa_som_desc"); ?></b></td>	
  </tr>	
  	<? 
  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
 	echo $linha;
	?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td></td>
    <td class="opcao_tabela" 		 align="left">Placa de V&iacute;deo</td>
    <td class="opcao_tabela" align="left">Descrição:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "te_placa_video_desc"); ?></b></td>	
  </tr>
  <? 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="2"></td>
    <td class="opcao_tabela" align="left">Quantidade de Cores:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "qt_placa_video_cores"); ?></b></td>	
  </tr>
  <? 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="2"></td>	
    <td class="opcao_tabela" align="left">Resolu&ccedil;&atilde;o:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "te_placa_video_resolucao"); ?></b></td>	
  </tr>
  <? 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<? echo $strCor;?>"> 
    <td colspan="2"></td>	
    <td class="opcao_tabela" align="left">Quantidade de Mem&oacute;ria:</td>
    <td class="opcao_tabela" align="left"><b><? echo mysql_result($result, 0, "qt_placa_video_mem").' MB'; ?></b></td>	
  </tr>
	<?
	echo $linha;
	// Obtenho os nomes dos hardwares passíveis de controle
	$arrDescricoesColunasComputadores = getDescricoesColunasComputadores();
  
	$strQueryTotalizaGeralExistentes = ' SELECT  	cs_tipo_componente,
												 	te_valor
								 		 FROM	 	componentes_estacoes
										 WHERE   	te_node_address = "'.mysql_result($result, 0, "te_node_address") . '" AND
									 		 	  			   id_so='  . mysql_result($result, 0, "id_so").'
										 ORDER BY 	cs_tipo_componente,te_valor';
	$resultTotalizaGeralExistentes   = mysql_query($strQueryTotalizaGeralExistentes) or die('Problema Consultando Tabela Componentes_Estações 1!');

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
			<tr bgcolor="<? echo $strCor;?>"> 
			<td>&nbsp;</td>
			<?
			if ($i > 0)
				{
				?>
				<td>&nbsp;</td>				
				<?
				}
			?>
			<td class="opcao_tabela"><? echo $arrDescricoesColunasComputadores[$arrColunas[0]].($i==0?' '.$intSequencial:':');?></td>
			<?
			if ($i == 0)
				{
				?>
				<td class="opcao_tabela"><? echo 'Descrição:';?></td>				
				<?
				}
			?>
			<td class="dado" colspan="3"><? echo $arrColunas[1]; ?></td>
			</tr>
			<?
			}
		echo $linha;			
		}

  	$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  		
  	?> 
  	<tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center"><br>
          <input name="historico_hardware" type=submit id="historico_hardware" value="Hist&oacute;rico de Altera&ccedil;&otilde;es na Configura&ccedil;&atilde;o de Hardware" >
          <br>
          &nbsp; 
          <input name="te_node_address" type="hidden" id="te_node_address" value="<? echo mysql_result($result, 0, "te_node_address");?>">
          <input name="id_so" type="hidden" id="id_so" value="<? echo mysql_result($result, 0, "id_so");?>">
        </div>
      </form></td>
  </tr>
  <?		
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						O módulo de Coleta de Informações de Hardware não foi habilitado pelo Administrador do CACIC.
						</font></div>
					  </td></tr>';
			}
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE HARDWARE DO COMPUTADOR
		?>
</table>
