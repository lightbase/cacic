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
		// EXIBIR INFORMA��ES TCP/IP DO COMPUTADOR
  ?> 

  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('Dominio DNS');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_dominio_dns"); ?></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('ServidorAutenticacao DNS primario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_dns_primario"); ?></td>
  </tr>
  <?php echo $linha; 
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>
    <td></td>
    <td></td>
    <td class="opcao_tabela"><?php echo $oTranslator->_('ServidorAutenticacao DNS secundario');?></td>
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
    <td class="opcao_tabela"><?php echo $oTranslator->_('ServidorAutenticacao WINS primario');?></td>
    <td class="dado"><?php echo mysql_result($result, 0, "te_wins_primario"); ?></td>
  </tr>
  <?php echo $linha;
  $strCor = ($strCor==''?$strPreenchimentoPadrao:'');						  
  ?>  
  <tr bgcolor="<?php echo $strCor;?>"> 
    <td>&nbsp;</td>    
    <td>&nbsp;</td>    
    <td>&nbsp;</td>            
    <td class="opcao_tabela"><?php echo $oTranslator->_('ServidorAutenticacao WINS secundario');?></td>
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
    <td class="opcao_tabela"><?php echo $oTranslator->_('ServidorAutenticacao DHCP');?></td>
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
		// FIM DA EXIBI��O DE INFORMA��ES TCP/IP DO COMPUTADOR
		?>
</table>
