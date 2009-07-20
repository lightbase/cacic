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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar ao servidor de suporte remoto na estação(srcacic.exe) as configurações (em XML) que são específicas para a 
 estação/usuário em questão. São levados em consideração a rede do agente, sistema operacional e Mac-Address.
 
 Retorno:
 1) <SERVIDORES_AUTENTICACAO>Servidores de Autenticação cadastrados no Gerente WEB. O servidor de autenticação referente à subrede da estação será acrescido de "*".</SERVIDORES_AUTENTICACAO>
 2) <STATUS>Retornará OK se a palavra chave informada "bater" com a chave armazenada previamente no BD</STATUS>
*/

require_once('../include/library.php');

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level   = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						  // Há necessidade de testes para Análise de Viabilidade Técnica 

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values = '';

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher		= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress		= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$v_cs_cipher		= '1';

$strPaddingKey  	= '';


LimpaTESTES();
/*
if (count($HTTP_POST_VARS) > 0)
	{
	GravaTESTES('***** getConfig *****');
	foreach($HTTP_POST_VARS as $i => $v) 
		GravaTESTES('GetConfig: POST => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
	GravaTESTES('');
	}

if (count($HTTP_GET_VARS)>0)
	{
	GravaTESTES('srCACIC_getConfig.Valores GET Recebidos:');
	foreach($HTTP_GET_VARS as $i => $v) 
		GravaTESTES('GetConfig: GET => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
	GravaTESTES('');	
	}
*/
conecta_bd_cacic();			

// Autenticação da Estação Visitada
$te_node_address   	= DeCrypt($key,$iv,$_POST['te_node_address'] ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so             	= DeCrypt($key,$iv,$_POST['te_so']		     ,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_palavra_chave  	= DeCrypt($key,$iv,$_POST['te_palavra_chave'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

/*
GravaTESTES('srCACIC_getConfig.te_node_address:'.$te_node_address);
GravaTESTES('srCACIC_getConfig.te_so:'.$te_so);
GravaTESTES('srCACIC_getConfig.te_palavra_chave:'.$te_palavra_chave);
*/

// ATENÇÃO: Apenas retornará um ARRAY contendo "id_so" e "te_so".
$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											'',
											$te_so,
											'', 
											'', 
											'',
											'');

$arrComputadores 	= getValores('computadores', 'te_palavra_chave,id_ip_rede'   , 'te_node_address = "'.$te_node_address.'" and id_so = '.$arrSO['id_so']);

$strTePalavraChave	= $arrComputadores['te_palavra_chave'];
$strIdIpRede		= $arrComputadores['id_ip_rede'];


//GravaTESTES('srCACIC_getConfig.strTePalavraChave:'.$strTePalavraChave);
//GravaTESTES('srCACIC_getConfig.strIdIpRede:'.$strIdIpRede);


// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores para autenticação
if ($te_palavra_chave == $strTePalavraChave)
	{
	$arrRedes 		= getValores('redes','id_servidor_autenticacao','id_ip_rede = "'.$strIdIpRede.'"');
	$strIdServidor	= $arrRedes['id_servidor_autenticacao'];
	
	//conecta_bd_cacic();	
	$query_SEL	= 'SELECT		id_servidor_autenticacao,
								nm_servidor_autenticacao  
				   FROM			servidores_autenticacao
				   WHERE		in_ativo = "S" AND
				   			    id_servidor_autenticacao = '.$strIdServidor.' 
				   ORDER BY		nm_servidor_autenticacao';
	$result_SEL = mysql_query($query_SEL);
	
	$strTripaServidores = '';
	while ($row_SEL = mysql_fetch_array($result_SEL))
		$strTripaServidores .= $row_SEL['id_servidor_autenticacao'].';'.$row_SEL['nm_servidor_autenticacao'].($row_SEL['id_servidor_autenticacao']==$strIdServidor?'*':'').';';

	if ($strTripaServidores == '')
		$strTripaServidores = '0;0';	

	$retorno_xml_values = '<SERVIDORES_AUTENTICACAO>'.EnCrypt($key,$iv,$strTripaServidores  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</SERVIDORES_AUTENTICACAO>';
	}

if ($retorno_xml_values <> '')
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;
else
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'ERRO!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		
$retorno_xml = $retorno_xml_header . $retorno_xml_values; 

echo $retorno_xml;	  
?>
