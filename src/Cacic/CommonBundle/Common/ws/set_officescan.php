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

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 
 
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

autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress, $strPaddingKey);

$te_node_address 			= DeCrypt($key,$iv,$_POST['te_node_address']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$id_so_new         			= DeCrypt($key,$iv,$_POST['id_so']					,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_so           			= DeCrypt($key,$iv,$_POST['te_so']					,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_ip           			= DeCrypt($key,$iv,$_POST['te_ip']					,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$id_ip_rede     			= DeCrypt($key,$iv,$_POST['id_ip_rede']				,$v_cs_cipher,$v_cs_compress, $strPaddingKey);
$te_nome_computador			= DeCrypt($key,$iv,$_POST['te_nome_computador']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 
$te_workgroup 				= DeCrypt($key,$iv,$_POST['te_workgroup']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey); 

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
	conecta_bd_cacic();
	// Verifico se o computador em questão já foi inserido anteriormente, e se não foi, insiro.
	$query = "SELECT count(*) as num_registros
			  FROM officescan
											WHERE te_node_address = '" . $te_node_address . "'
											AND id_so = '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query);
	if (mysql_result($result, 0, "num_registros") == 0) 
		{
						$query = "INSERT INTO officescan
																(te_node_address, id_so)
																VALUES ('" . $te_node_address . "', '" . $arrSO['id_so'] . "'  )";
						$result = mysql_query($query);
		} 
	
	$query = "UPDATE officescan 
			  SET 	nu_versao_engine 	= '" . DeCrypt($key,$iv,$_POST['nu_versao_engine']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
					nu_versao_pattern   = '" . DeCrypt($key,$iv,$_POST['nu_versao_pattern']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
					dt_hr_coleta        = NOW(),
					dt_hr_instalacao    = '" . DeCrypt($key,$iv,$_POST['dt_hr_instalacao']	,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
					te_servidor         = '" . DeCrypt($key,$iv,$_POST['te_servidor']		,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "',
					in_ativo            = '" . DeCrypt($key,$iv,$_POST['in_ativo']			,$v_cs_cipher,$v_cs_compress, $strPaddingKey) . "' 
			  WHERE te_node_address 	= '" . $te_node_address . "' and
					id_so           	= '" . $arrSO['id_so'] . "'";
	$result = mysql_query($query);
	
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS>';
	}
else
	echo '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>Chave (TE_NODE_ADDRESS + ID_SO) Inválida</STATUS>';	

?>