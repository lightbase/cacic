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
session_start();
// Definição do nível de compressão (Default=máximo)
//$v_compress_level = '9';
$v_compress_level = '0';
 
require_once('../include/library.php');

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher	= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress	= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$strPaddingKey = '';

// O agente PyCACIC envia o valor "padding_key" para preenchimento da palavra chave para decriptação/encriptação
if ($_POST['padding_key'])
	{
	// Valores específicos para trabalho com o PyCACIC - 04 de abril de 2008 - Rogério Lino - Dataprev/ES
	$strPaddingKey 	= $_POST['padding_key']; // A versão inicial do agente em Python exige esse complemento na chave...
	}
	
$boolAgenteLinux 	= (trim(DeCrypt($key,$iv,$_POST['AgenteLinux'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) <> ''?true:false);

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

$v_dados_rede = getDadosRede();

// Se o envio de informações foi feito com dados criptografados... (Versões 2.0.2.5+)
$te_node_address 		= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_so_new         		= DeCrypt($key,$iv,$_POST['id_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so           		= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

//Para implementação futura após alteração do agente, para que ele também envie os dados abaixo
$te_nome_computador 	= DeCrypt($key,$iv,$_POST['te_nome_computador']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_ip              	= DeCrypt($key,$iv,$_POST['te_ip']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_nome_host       	= DeCrypt($key,$iv,$_POST['te_nome_host']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$id_ip_rede         	= DeCrypt($key,$iv,$_POST['id_ip_rede']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_workgroup       	= DeCrypt($key,$iv,$_POST['te_workgroup']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$id_usuario 			= DeCrypt($key,$iv,$_POST['id_usuario']			,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

/* Todas as vezes em que é feita a recuperação das configurações por um agente, é incluído 
 o computador deste agente no BD, caso ainda não esteja inserido. */
if ($te_node_address <> '')
	{ 
	$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											  	$id_so_new, 
											  	$te_so, 										
											  	$id_ip_rede, 
											  	$te_ip, 
											  	$te_nome_computador, 
											  	$te_workgroup);																				

	// Atenção: não use o count (*) - Com espaço entre o count e o (*)				
	$query = "SELECT COUNT(*) 
			  FROM patrimonio 
			  WHERE te_node_address = '" . $te_node_address . "'
			  AND id_so = '" . $arrSO['id_so'] . "'";
	conecta_bd_cacic();
	
	$result = mysql_query($query);
	if (mysql_num_rows($result) > 0) 
		{  // Atualização das informações de patrimônio (e não inclusão). 
	
		// Inclui as informações no histórico.
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
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inválida</STATUS>';	
?>
