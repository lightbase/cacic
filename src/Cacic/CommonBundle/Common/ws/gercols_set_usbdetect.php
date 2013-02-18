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

/*
M�dulo espec�fico para recep��o de informa��es referentes ao uso de dispositivos USB nas esta��es de trabalho

Ser�o manipuladas as tabelas usb_vendors, usb_devices e usb_logs.

As informa��es para alimenta��o/atualiza��o das tabelas usb_vendors e usb_devices devem ser extra�das a partir do endere�o
http://www.linux-usb.org/usb.ids, utilizando-se a op��o Administra��o / Cadastros / USB Devices / Importar/Atualizar Informa��es

#
#	List of USB ID's
#
#	Maintained by Stephen J. Gowdy <linux.usb.ids@gmail.com>
#	If you have any new entries, please submit then via
#		http://www.linux-usb.org/usb-ids.html
#	or send entries as patches (diff -u old new) in the
#	body of your email (a bot will attempt to deal with it).
#	The latest version can be obtained from
#		http://www.linux-usb.org/usb.ids

*/
require_once('../include/common_top.php');

$te_usb_info = DeCrypt($_POST['te_usb_info'],$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
if ($te_usb_info <> '')
	{
	$arrUsbInfo = explode('_',$te_usb_info);

	$query = "INSERT INTO usb_logs (id_computador,
									cs_event,
									dt_event,
									id_vendor,
									id_device)
				VALUES           (" . $arrDadosComputador['id_computador'] . ",
								  '" . $arrUsbInfo[0]   . "',
								  '" . $arrUsbInfo[1]   . "',									  
								  '" . $arrUsbInfo[2]   . "',
								  '" . $arrUsbInfo[3]   . "')";
	//GravaTESTES($query);																				
	$result = mysql_query($query);
	
	//
	$arrTeUsbFilter 			 = getArrFromSelect('configuracoes_locais', 'te_usb_filter'  			   ,'id_local='.$arrDadosRede['id_local']);								
	$arrTeNotificarUtilizacaoUSB = getArrFromSelect('configuracoes_locais', 'te_notificar_utilizacao_usb','id_local='.$arrDadosRede['id_local']);

	$arrDeviceData = getArrFromSelect('usb_devices', 'id_device,nm_device','trim(id_device)="'.$arrUsbInfo[3].'" AND trim(id_vendor)="'.$arrUsbInfo[2].'"');
	
	if (trim($arrDeviceData[0]['nm_device'])=='')
		{
		$query = "INSERT 
				  INTO 		usb_devices(id_vendor,id_device,nm_device) 
				  VALUES 	('".$arrUsbInfo[2]."', 
							 '".$arrUsbInfo[3]."',							 									  						  
						 'Dispositivo USB Desconhecido')";							 									  						  							
		$result = mysql_query($query);
		}

	$arrVendorData = getArrFromSelect('usb_vendors', 'id_vendor,nm_vendor','trim(id_vendor)="'.$arrUsbInfo[2].'"');	
	if (trim($arrVendorData[0]['nm_vendor'])=='')
		{
		$query = "INSERT 
				  INTO 		usb_vendors(id_vendor,nm_vendor) 
				  VALUES 	('".$arrUsbInfo[2]."', 
							 'Fabricante de Dispositivos USB Desconhecido')";							 									  						
		$result = mysql_query($query);
		}

	if ((trim($arrTeUsbFilter[0]['te_usb_filter'])<>'') && (trim($arrTeNotificarUtilizacaoUSB[0]['te_notificar_utilizacao_usb']) <> ''))
		{
		$arrUSBfilter = explode('#',$arrTeUsbFilter[0]['te_usb_filter']);
		$strUSBkey    = $arrUsbInfo[2] . "." . $arrUsbInfo[3];
		$indexOf 	  = array_search($strUSBkey,$arrUSBfilter);
		if ($indexOf <> -1)
			{
			$strCorpoMail = '';
			$strCorpoMail .= " Prezado administrador,\n\n";
			$strCorpoMail .= " foi " . ($arrUsbInfo[0] == 'I'?'inserido':'removido'). " o dispositivo '(".$arrVendorData[0]['id_vendor'].")".$arrVendorData[0]['nm_vendor']." / (".$arrDeviceData[0]['id_device'].")".$arrDeviceData[0]['nm_device'].($arrUsbInfo[0] == 'I'?'n':'d')."a esta��o de trabalho abaixo:\n\n";				
			$strCorpoMail .= " Nome...........: ". $arrDadosComputador['te_nome_computador'] ."\n";
			$strCorpoMail .= " Endere�o IP: ". $arrDadosComputador['te_ip_computador'] . "\n";
			$strCorpoMail .= " Rede............: ". $arrDadosRede['nm_rede'] ." ('" .$arrDadosRede['te_ip_rede']. "')\n";
	
			$strCorpoMail .= "\n\nPara visualizar mais informa��es sobre esse computador, acesse o endere�o\nhttp://";
			$strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?id_computador=' . $arrDadosComputador['id_computador'];
			$strCorpoMail .= "\n\n\n________________________________________________\n";
			$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
			$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";
				
			// Manda mail para os administradores.
			mail($arrTeNotificarUtilizacaoUSB[0]['te_notificar_utilizacao_usb'], "[Sistema CACIC] ".($arrUsbInfo[0] == 'I'?'Inser��o':'Remo��o')." de Dispositivo USB Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
			}
		}
	
	$strXML_Values .= '<NM_DEVICE>' . EnCrypt('('.$arrVendorData[0]['id_vendor'].')'.$arrVendorData[0]['nm_vendor'].' - (' .$arrDeviceData[0]['id_device'].')'.$arrDeviceData[0]['nm_device'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</NM_DEVICE>';
	}

$strXML_Values .= '<STATUS>OK</STATUS>';		
require_once('../include/common_bottom.php');
?>