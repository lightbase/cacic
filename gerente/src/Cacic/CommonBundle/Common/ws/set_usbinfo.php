<? 
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


// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 

require_once('../include/library.php');

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decripta��o/encripta��o
// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
if ($_POST['padding_key'])
	$strPaddingKey 	= $_POST['padding_key']; // A vers�o inicial do agente em Python exige esse complemento na chave...

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$te_usb_info 			 	= DeCrypt($key,$iv,$_POST['te_usb_info'],$v_cs_cipher, $v_cs_compress,$strPaddingKey); 

$retorno_xml_header  = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><CONFIGS>';
$retorno_xml_values	 = '';

conecta_bd_cacic();
//GravaTESTES('te_usb_info: '.$te_usb_info);

if ($te_usb_info <> '')
	{
	$arrUsbInfo = explode('_',$te_usb_info);
	
	$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
	$id_so_new         			= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
	$te_so           			= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
	$id_ip_rede     			= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher, $v_cs_compress,$strPaddingKey);
	$te_ip 						= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
	$te_nome_computador			= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher, $v_cs_compress,$strPaddingKey); 
	$te_workgroup 				= DeCrypt($key,$iv,$_POST['te_dominio_windows'],$v_cs_cipher, $v_cs_compress,$strPaddingKey);
	
	conecta_bd_cacic();
	
	/* Todas as vezes em que � feita a recupera��o das configura��es por um agente, � inclu�do 
	o computador deste agente no BD, caso ainda n�o esteja inserido. */
	if ($te_node_address <> '')
		{
		$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
													$id_so_new, 
													$te_so, 										
													$id_ip_rede, 
													$te_ip, 
													$te_nome_computador,
													$te_workgroup);									
		
		$query = "INSERT INTO usb_logs (te_node_address,
										id_so,
										cs_event,
										dt_event,
										id_vendor,
										id_device)
					VALUES           ('" . $te_node_address . "',
									  '" . $arrSO['id_so']  . "',
									  '" . $arrUsbInfo[0]   . "',
									  '" . $arrUsbInfo[1]   . "',									  
									  '" . $arrUsbInfo[2]   . "',
									  '" . $arrUsbInfo[3]   . "')";
		//GravaTESTES($query);																				
		$result = mysql_query($query);
		
		//
		$v_dados_rede = getDadosRede();		
		$arrTeUsbFilter 			 = getValores('configuracoes_locais', 'te_usb_filter'  			   ,'id_local='.$v_dados_rede['id_local']);								
		$arrTeNotificarUtilizacaoUSB = getValores('configuracoes_locais', 'te_notificar_utilizacao_usb','id_local='.$v_dados_rede['id_local']);

		$arrNmDevice = getValores('usb_devices', 'nm_device','id_device="'.$arrUsbInfo[3].'" AND id_vendor="'.$arrUsbInfo[2].'"');
		$strNmDevice = ($arrNmDevice['nm_device'] <> ''?$arrNmDevice['nm_device']:'('.$arrUsbInfo[3].') Dispositivo Desconhecido');

		$arrNmVendor = getValores('usb_vendors', 'nm_vendor','id_vendor="'.$arrUsbInfo[2].'"');	
		$strNmVendor = ($arrNmVendor['nm_vendor'] <> ''?$arrNmVendor['nm_vendor']:'('.$arrUsbInfo[2].') Fabricante Desconhecido');
		
		//GravaTESTES('te_usb_filter: "'.trim($arrTeUsbFilter['te_usb_filter']).'"');
		//GravaTESTES('te_notificar_utilizacao_usb: "'.trim($arrTeNotificarUtilizacaoUSB['te_notificar_utilizacao_usb']).'"');
	  	if ((trim($arrTeUsbFilter['te_usb_filter'])<>'') && (trim($arrTeNotificarUtilizacaoUSB['te_notificar_utilizacao_usb']) <> ''))
			{
			//GravaTESTES($arrTeUsbFilter['te_usb_filter']);
			//GravaTESTES($arrTeNotificarMudancaHardware['te_notificar_mudanca_hardware']);		
			
			$arrUSBfilter = explode('#',$arrTeUsbFilter['te_usb_filter']);
			$strUSBkey    = $arrUsbInfo[2] . "." . $arrUsbInfo[3];
			$indexOf 	  = array_search($strUSBkey,$arrUSBfilter);
			if ($indexOf <> -1)
				{
				$strCorpoMail = '';
				$strCorpoMail .= " Prezado administrador,\n\n";
				$strCorpoMail .= " foi " . ($arrUsbInfo[0] == 'I'?'inserido':'removido'). " o dispositivo '(".$arrUsbInfo[2].")$strNmVendor / (".$arrUsbInfo[3].")$strNmDevice' ".($arrUsbInfo[0] == 'I'?'n':'d')."a esta��o de trabalho abaixo:\n\n";				
				$strCorpoMail .= " Nome...........: ". $te_nome_computador ."\n";
				$strCorpoMail .= " Endere�o IP: ". $te_ip . "\n";
				$strCorpoMail .= " Rede............: ". $v_dados_rede['id_ip_rede'] ."\n";
		
				$strCorpoMail .= "\n\nPara visualizar mais informa��es sobre esse computador, acesse o endere�o\nhttp://";
				$strCorpoMail .= CACIC_PATH . '/relatorios/computador/computador.php?te_node_address=' . $te_node_address . '&id_so=' . $arrSO['id_so'];
				$strCorpoMail .= "\n\n\n________________________________________________\n";
				$strCorpoMail .= "CACIC - " . date('d/m/Y H:i') . "h \n";
				$strCorpoMail .= "Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";
		
				//GravaTESTES($strCorpoMail);		
				
				// Manda mail para os administradores.
				mail($arrTeNotificarUtilizacaoUSB['te_notificar_utilizacao_usb'], "[Sistema CACIC] ".($arrUsbInfo[0] == 'I'?'Inser��o':'Remo��o')." de Dispositivo USB Detectada", "$strCorpoMail", "From: cacic@{$_SERVER['SERVER_NAME']}");
				}
			}
		//	
		
		$retorno_xml_values	 .= '<NM_DEVICE>' . EnCrypt($key,$iv,($strNmVendor.' - ' .$strNmDevice),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey). '</NM_DEVICE>';
		}
	else
		$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';						
	}
else
	$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>USB Info Vazio!</STATUS>';					

echo $retorno_xml_header . $retorno_xml_values;
?>
