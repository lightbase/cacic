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

session_start();

require_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...

// 1 - Administração
// 2 - Gestão Central
// 3 - Supervisão

/*
$_SESSION['sessStrIdRedes']
$_SESSION['sessStrNmItens']
*/

// Função para replicação do conteúdo do REPOSITÓRIO nos servidores de UPDATES das redes cadastradas.
// Apenas entro no processo se os arrays contendo redes e ítens contiverem valores para ITENS e REDES
if (($_SESSION['sessStrIdRedes'][0] <> '') &&
	($_SESSION['sessStrNmItens'][1] <> '')) 
	{
	?>
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
	<head>
	<link rel="stylesheet"   type="text/css" href="../../include/css/cacic.css">

	<title><?php echo $oTranslator->_('Verificacao/Atualizacao dos Servidores de Updates');?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<script language="JavaScript" type="text/javascript" src="../../include/js/ajax/common.js"></script>	            
	<script language="JavaScript" type="text/javascript" src="../../include/js/jquery.js"></script>	
	<script language="JavaScript" type="text/javascript" src="../../include/js/cacic.js"></script>	    
	<script language="JavaScript" type="text/javascript" src="../../include/js/ajax/processa_update_subrede.js"></script>
	</head>
    
	<body background="../../imgs/linha_v.gif">
	<div id="divUpdateSubrede" name="divUpdateSubrede">
	<form method="post" ENCTYPE="multipart/form-data" name="forma" id="forma" action="#">
	<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr nowrap>
    <td class="cabecalho"><?php echo $oTranslator->_('Verificacao/Atualizacao dos Servidores de Updates');?></td>
    </tr>
	<tr>
    <td class="descricao"><?php echo $oTranslator->_('Verificacao-Atualizacao dos Servidores de Updates help');?></td>
    </tr>    
	</table>

	<br>
	<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">	
    <tr><td colspan="5" align="right">
    <input type="button" id="frmButton" name="frmButton" class="botoes" value="Iniciar Processo de Atualização">
    </td></tr>    
	<tr bgcolor="#FFFFFF"><td height="2" colspan="5"></td></td></tr>    
	<tr bordercolor="#000000" bgcolor="#CCCCCC">
    <td valign="center" class="cabecalho_tabela"			><p align="left"><?php echo $oTranslator->_('Endereco IP');?></p></td>
	<td valign="center" class="cabecalho_tabela" colspan="2"><p align="left"><?php echo $oTranslator->_('Localizacao ou nome da SubRede');?></p></td>		
	<td valign="center" class="cabecalho_tabela"			><p align="left"><?php echo $oTranslator->_('Ítem');?></p></td>
	<td valign="center" class="cabecalho_tabela"			><p align="left"><?php echo $oTranslator->_('Status');?></p></td>			
	</tr>
	

	<?php	
	$arrRedes = explode(',', $_SESSION['sessStrIdRedes']);
	$arrItens = explode(',', $_SESSION['sessStrNmItens']);
	
	for ($intLoopRedes = 0; $intLoopRedes < count($arrRedes); $intLoopRedes++)
		{
		$arrDadosRede = getValores('redes, locais','te_ip_rede,nm_rede,sg_local','redes.id_rede = ' . $arrRedes[$intLoopRedes] . ' AND locais.id_local = redes.id_local');
		
		for ($intLoopItens = 0; $intLoopItens < count($arrItens); $intLoopItens++)		
			{
			$strSufixoIdentificador = '_' . $arrRedes[$intLoopRedes] . '_' . $arrItens[$intLoopItens];
			
			if ($strZebraColor == '#FFFFFF') 
				$strZebraColor = '#EEEEEE'; 
			else 
				$strZebraColor = '#FFFFFF';					

			$strIdIpRede = ($intLoopItens == 0 ? $arrDadosRede['te_ip_rede'] 									: '&nbsp;');
			$strNmRede   = ($intLoopItens == 0 ? $arrDadosRede['sg_local'] . ' / ' . $arrDadosRede['nm_rede'] 	: '&nbsp;');

			?>		
	        <tr id="tr<?php echo $strSufixoIdentificador;?>" bgcolor="<?php echo $strZebraColor;?>">
    	    <td valign="center" class="dado_peq_sem_fundo"	  	     			><p align="left"><div id="div1<?php 		    echo $strSufixoIdentificador;?>" name="div1<?php 			 echo $strSufixoIdentificador;?>"><?php echo $strIdIpRede; ?></div></p></td>
			<td valign="center" class="dado_peq_sem_fundo" nowrap	 colspan="2"><p align="left"><div id="div2<?php 		    echo $strSufixoIdentificador;?>" name="div2<?php 			 echo $strSufixoIdentificador;?>"><?php echo $strNmRede;   ?></div></p></td>
			<td valign="center" class="dado_peq_sem_fundo"	  	   				><p align="left"><div id="divSendProcess<?php   echo $strSufixoIdentificador;?>" name="divSendProcess<?php 	 echo $strSufixoIdentificador;?>"><?php echo $arrItens[$intLoopItens]; ?></div></p></td>
			<td valign="center" class="dado_peq_sem_fundo" nowrap				><p align="left"><div id="divProcessStatus<?php echo $strSufixoIdentificador;?>" name="divProcessStatus<?php echo $strSufixoIdentificador;?>">&nbsp;</div></p></td>
	        </tr>	        
			<?php
			}
			?>
		<tr bgcolor="#000000"><td height="1" colspan="5"></td></td></tr>
        <?php    
		}
	?>
	</table>	
	<input type="hidden" id="frmRedes"  		name="frmRedes"  		value="<?php echo $_SESSION['sessStrIdRedes'];?>">            
	<input type="hidden" id="frmItens"  		name="frmItens"  		value="<?php echo $_SESSION['sessStrNmItens'];?>">        
    <?php
	// Defino a variável de sessão abaixo para evitar o reenvio de itens 
	$_SESSION['sessStrTripaItensEnviados'] 		 = '';
	
	session_unregister('sessStrIdRedes');
	session_unregister('sessStrNmItens');	
	?>
    </form>
	</div>			
	</body>
	</html>
	<?php
	}
	?>