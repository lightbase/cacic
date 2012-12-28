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
session_start();
// Defini��o do n�vel de compress�o (Default=m�ximo)
//$v_compress_level = '9';
$v_compress_level = '0';
 
require_once('../include/library.php');

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decripta��o/encripta��o
if ($_POST['padding_key'])
	{
	// Valores espec�ficos para trabalho com o PyCACIC - 04 de abril de 2008 - Rog�rio Lino - Dataprev/ES
	$strPaddingKey 	= $_POST['padding_key']; // A vers�o inicial do agente em Python exige esse complemento na chave...
	}
	
$boolAgenteLinux 	= (trim(DeCrypt($key,$iv,$_POST['AgenteLinux'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) <> ''?true:false);

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$v_dados_rede = getDadosRede();

// Se o envio de informa��es foi feito com dados criptografados... (Vers�es 2.0.2.5+)
$te_node_address 		= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_so_new         		= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so           		= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

//Para implementa��o futura ap�s altera��o do agente, para que ele tamb�m envie os dados abaixo
$te_nome_computador 	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_ip              	= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_nome_host       	= DeCrypt($key,$iv,$_POST['te_nome_host']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_ip_rede         	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_workgroup       	= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$id_usuario 			= DeCrypt($key,$iv,$_POST['id_usuario']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

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

	// Aten��o: n�o use o count (*) - Com espa�o entre o count e o (*)				
	$query = "SELECT COUNT(*) 
			  FROM patrimonio 
			  WHERE te_node_address = '" . $te_node_address . "'
			  AND id_so = '" . $arrSO['id_so'] . "'";
	conecta_bd_cacic();
	
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
		{  // Atualiza��o das informa��es de patrim�nio (e n�o inclus�o). 
	
		// Inclui as informa��es no hist�rico.
	// Inclui dados patrimoniais.
	$query_verify = "SELECT te_node_address, id_so FROM patrimonio
	                        WHERE te_node_address = '" . $te_node_address . "' AND id_so = '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query_verify);
	
        if(mysql_num_rows($result) <> 0) {
	     $query = "UPDATE patrimonio 
	                  SET id_unid_organizacional_nivel1a = '".
	                      DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$strPaddingKey). "'".
	                         
	                ", id_unid_organizacional_nivel2 = '".
	                      DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel2'],$v_cs_cipher,$v_cs_compress,$strPaddingKey). "'".
	                        
	                ", te_localizacao_complementar = '".
	                      DeCrypt($key,$iv,$_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                      
	                ", te_info_patrimonio1 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio1'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio2 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio2'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio3 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio3'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio4 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio4'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio5 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio5'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                ", te_info_patrimonio6 = '".DeCrypt($key,$iv,$_POST['te_info_patrimonio6'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)."'".
	                  
	              " WHERE te_node_address = '" . $te_node_address . "' AND id_so = '" . $arrSO['id_so'] . "'";
	}
        else {
	     $query = "INSERT INTO patrimonio ( te_node_address, id_so, dt_hr_alteracao,
	                                   id_unid_organizacional_nivel1a,
	                                   id_unid_organizacional_nivel2,
	                                   te_localizacao_complementar,
	                                   te_info_patrimonio1,
	                                   te_info_patrimonio2,
	                                   te_info_patrimonio3,
	                                   te_info_patrimonio4,
	                                   te_info_patrimonio5,
	                                   te_info_patrimonio6 )
			 VALUES ( '" . $te_node_address . "', 
			          '" . $arrSO['id_so'] . "', NOW(),
			          '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel1a'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['id_unid_organizacional_nivel2']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_localizacao_complementar']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio1']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio2']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio3']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "', 
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio4']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio5']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "',
			          '" . DeCrypt($key,$iv,$_POST['te_info_patrimonio6']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey) . "')";
	}
		$result = mysql_query($query);
		$_SESSION['id_usuario'] = $id_usuario;			
		GravaLog('INS',$_SERVER['SCRIPT_NAME'],'patrimonio');				
		}
	echo '<?xml version="1.0" encoding="iso-8859-1" ?>'.$query.'<STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inv�lida</STATUS>';	
?>
