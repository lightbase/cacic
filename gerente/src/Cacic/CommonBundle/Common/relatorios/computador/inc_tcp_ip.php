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

if (!$_SESSION['tcp_ip'])
	$_SESSION['tcp_ip'] = false;
if ($exibir == 'tcp_ip')
	{
	$_SESSION['tcp_ip'] = !($_SESSION['tcp_ip']);
	}
else
	{
	$_SESSION['tcp_ip'] = false;
	}

$strCor = '';  
$strCor = ($strCor==''?$strPreenchimentoPadrao:'');		
$linha = '<tr bgcolor="'.$strCorDaLinha.'"> 
		  <td height="1" colspan="5"></td>
          </tr>';	  				  
?>
<table width="94%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td colspan="5" bgcolor="#E1E1E1" class="cabecalho_tabela">&nbsp;<a href="computador.php?exibir=tcp_ip&id_computador=<?php echo $_GET['id_computador']?>"> 
      <img src="../../imgs/<?php if($_SESSION['tcp_ip'] == true) echo 'menos';
   			 else echo 'mais'; ?>.gif" width="12" height="12" border="0">
   			  <?php echo $oTranslator->_('Protocolo TCP/IP (Configuracao Principal)');?> </a></td>
  </tr>
  <tr> 
    <td colspan="5" height="1" bgcolor="#333333"></td>
  </tr>
  <?php if ($_SESSION['tcp_ip'] == true) 
		{
		// EXIBIR INFORMAÇÕES TCP/IP DO COMPUTADOR
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Dominio DNS');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_dominio_dns"); ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Servidor DNS primario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_dns_primario"); ?></td>
  </tr>
  <?php echo $linha; 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Servidor DNS secundario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_dns_secundario"); ?></td>
  </tr>
  <?php echo $linha;  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Dominio Windows');?></td>
    <?php $arrTeDominioWindows = explode('@',mysql_result($result, 0, "te_dominio_windows"))?>
    <td class="dado"><?php echo $arrTeDominioWindows[count($arrTeDominioWindows)-1]; ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Servidor WINS primario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_wins_primario"); ?></td>
  </tr>
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>  
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>    
    <td>&nbsp;</td>    
    <td>&nbsp;</td>            
    <td class="opcao_tabela"><?php echo $oTranslator->_('Servidor WINS secundario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_wins_secundario"); ?></td>
  </tr>
  <?php  
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Endereco TCP/IP');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_ip_computador"); ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Mascara de rede');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_mascara"); ?></td>
  </tr>
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Endereco de rede');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_ip_rede"); ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Servidor DHCP');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_serv_dhcp");?></td>
  </tr>
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Gateway');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_gateway"); ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Ultimo login');?></td>   
    <td class="dado"><?php echo mysql_result($result, 0, "te_dominio_windows");?></tr>
  <?php echo $linha;  ?> 

  <tr> 
    <td>&nbsp;</td>
    <td colspan="4"> <form action="historico.php" method="post" name="form1" target="_blank">
        <div align="center">&nbsp;<br>
          <input name=historico_rede type=submit id=historico_rede value="<?php echo $oTranslator->_('Historico de alteracoes na configuracao de Rede');?>">
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
		// FIM DA EXIBIÇÃO DE INFORMAÇÕES TCP/IP DO COMPUTADOR
		?>
</table>
