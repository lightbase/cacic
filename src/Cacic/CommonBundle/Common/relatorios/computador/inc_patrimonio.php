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
// Esse arquivo é um arquivo de include, usado pelo arquivo computador.php. 
if (!$_SESSION['patrimonio'])
	$_SESSION['patrimonio'] = false;
if ($exibir == 'patrimonio')
	$_SESSION['patrimonio'] = !($_SESSION['patrimonio']);
else
	$_SESSION['patrimonio'] = false;
	
$strCor = '';  
$strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
		
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela"> 
      &nbsp;<a href="computador.php?exibir=patrimonio&te_node_address=<? echo $_GET['te_node_address']?>&id_so=<? echo $_GET['id_so']?>"> 
      <img src="../../imgs/<? if($_SESSION['patrimonio'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">
   			 <?=$oTranslator->_('Informacoes de patrimonio e localizacao fisica');?></a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?		// EXIBIR INFORMAÇÕES DE PATRIMÔNIO DO COMPUTADOR
		if ($_SESSION['patrimonio'] == true) 
			{
			$query = "SELECT 	cs_situacao
					  FROM 		acoes_redes 
					  WHERE 	id_acao = 'cs_coleta_patrimonio' AND
					  			id_ip_rede = '".mysql_result($result,0,'id_ip_rede')."'";
							
			$result_acoes =  mysql_query($query);
			if (@mysql_result($result_acoes, 0, "cs_situacao") <> 'N') 
				{
					$query = " SELECT a.*, nm_unid_organizacional_nivel1, nm_unid_organizacional_nivel1a, nm_unid_organizacional_nivel2 
								  FROM patrimonio a
								  LEFT JOIN unid_organizacional_nivel2 c ON (a.id_unid_organizacional_nivel2=c.id_unid_organizacional_nivel2)
								  LEFT JOIN unid_organizacional_nivel1a d ON (a.id_unid_organizacional_nivel1a=d.id_unid_organizacional_nivel1a)
								  LEFT JOIN unid_organizacional_nivel1 b ON (d.id_unid_organizacional_nivel1= b.id_unid_organizacional_nivel1)
								  WHERE         a.te_node_address = '". $_GET['te_node_address'] ."' AND a.id_so = '". $_GET['id_so'] ."'
								  ORDER BY dt_hr_alteracao desc limit 1";

						$result_patrimonio = mysql_query($query);
						if(mysql_num_rows($result_patrimonio) > 0) 
							{
						
          function texto_campo($campo_pesquisado) 
		  	{ 
										$query_patrimonio_config_interface = "SELECT 	te_etiqueta
																			  FROM 		patrimonio_config_interface
																			  WHERE 	id_etiqueta = '$campo_pesquisado' AND
																			  			in_exibir_etiqueta <> 'N' AND
																						id_local = ".$_SESSION['id_local'];
										$result_patrimonio_config_interface =  mysql_query($query_patrimonio_config_interface);
										if (mysql_num_rows($result_patrimonio_config_interface) == 1) { return mysql_result($result_patrimonio_config_interface, 0, 'te_etiqueta'); }
										else { return ''; }
			}
		?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? echo texto_campo('etiqueta1'); ?>:</td>
    <td colspan="3" class="dado"><? echo mysql_result($result_patrimonio, 0, "nm_unid_organizacional_nivel1"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>   
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? echo texto_campo('etiqueta1a'); ?>:</td>
    <td colspan="3" class="dado"><? echo mysql_result($result_patrimonio, 0, "nm_unid_organizacional_nivel1a"); ?></td>
  </tr>
  
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? echo texto_campo('etiqueta2'); ?>:</td>
    <td colspan="3" class="dado"><? echo mysql_result($result_patrimonio, 0, 'nm_unid_organizacional_nivel2'); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? echo texto_campo('etiqueta3'); ?>:</td>
    <td colspan="3" class="dado"><? echo mysql_result($result_patrimonio, 0, "te_localizacao_complementar"); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?=$oTranslator->_('Data da alteracao');?></td>
    <td class="dado" colspan="3"><? echo date('d/m/Y H:i', strtotime(mysql_result($result_patrimonio, 0, 'dt_hr_alteracao'))); ?></td>
  </tr>
  <? echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  
  <tr bgcolor="<? echo $strCor;?>">
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? $var_aux = texto_campo('etiqueta4');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?></td>
    <td class="dado" colspan="3"><? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio1') ; } ?></td>
  </tr>
	<?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>   
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">
      <? $var_aux = texto_campo('etiqueta5');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?>    </td>
    <td class="dado" colspan="3">
      <? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio2'); } ?>      </td>
  </tr>
  <?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>">
    <td class="opcao_tabela">&nbsp;</td>
    <td><span class="opcao_tabela">
      <? $var_aux = texto_campo('etiqueta6');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?>
    </span></td>
    <td class="dado" colspan="3"><? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio3'); } ?></td>
  </tr>
  <?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  
  <tr bgcolor="<? echo $strCor;?>"> 
    <td class="opcao_tabela">&nbsp;</td>
    <td>
      <? $var_aux = texto_campo('etiqueta7');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?>    </td>
    <td class="dado" colspan="3">
      <? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio4'); } ?>      </td>
  </tr>
  <?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<? echo $strCor;?>">
    <td>&nbsp;</td>
    <td class="opcao_tabela"><? $var_aux = texto_campo('etiqueta8');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?></td>
    <td class="dado"><? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio5'); } ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  
  <tr bgcolor="<? echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela">
      <? $var_aux = texto_campo('etiqueta9');
										   if ($var_aux) { echo $var_aux . ':'; } 
										 ?>      </td>
    <td class="dado" colspan="3">
      <? if ($var_aux) { echo mysql_result($result_patrimonio, 0, 'te_info_patrimonio6'); } ?>    </td>
  </tr>
  <?
	echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>   
  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center"> <br>
          <input name=historico_patrimonio type=submit value="<?=$oTranslator->_('Historico de alteracoes das informacoes de patrimonio');?>" >
          &nbsp; 
          <input name="te_node_address" type="hidden" id="te_node_address" value="<? echo mysql_result($result, 0, "te_node_address");?>">
          <input name="id_so" type="hidden" id="id_so" value="<? echo mysql_result($result, 0, "id_so");?>">
          <br>
          &nbsp; </div>
      </form></td>
  </tr>
  <?
			}
			else {
				echo '<tr><td> 
						<p>
						<div align="center">
						<br>
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						'.$oTranslator->_('Nao foram coletadas informacoes de patrimonio e/ou Localizacao fisica').'
						</font></div>
						</p>
					  </td></tr>';
			}
			}
			else {
				echo '<tr><td> 
						<div align="center">
						<font font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">
						'.$oTranslator->_('O modulo de coleta de informacoes de patrimonio nao foi habilitado pelo Administrador').'
						</font></div>
					  </td></tr>';
			}
			
		}
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES DE PATRIMÔNIO DO COMPUTADOR
		?>
</table>
