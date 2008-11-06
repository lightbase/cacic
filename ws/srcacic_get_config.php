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
 1) <DOMINIOS>Domínios cadastrados no Gerente WEB. O domínio referente à subrede da estação será acrescido de "*".</DOMINIOS>
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

/*	
LimpaTESTES();
GravaTESTES('Valores POST Recebidos:');
foreach($HTTP_POST_VARS as $i => $v) 
	GravaTESTES('Nome/Valor do POST_Request: "'.$i.'"/"'.$v.'"');

GravaTESTES('Valores GET Recebidos:');
foreach($HTTP_GET_VARS as $i => $v) 
	GravaTESTES('Nome/Valor do GET_Request: "'.$i.'"/"'.$v.'"');

GravaTESTES('');	
GravaTESTES('');	
*/

// Autenticação da Estação Visitada
$te_node_address   	= DeCrypt($key,$iv,$_POST['te_node_address']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so             	= DeCrypt($key,$iv,$_POST['te_so']				,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_palavra_chave  	= DeCrypt($key,$iv,$_POST['te_palavra_chave']	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

// ATENÇÃO: Apenas retornará um ARRAY contendo "id_so" e "te_so".
$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											'',
											$te_so,
											'', 
											'', 
											'',
											'');									

$arrComputadores 	= getValores('computadores', 'te_palavra_chave,id_ip_rede'   , 'te_node_address = "'.$te_node_address.'" and id_so = '.$arrSO['id_so']);;
$strTePalavraChave	= $arrComputadores['te_palavra_chave'];
$strIdIpRede		= $arrComputadores['id_ip_rede'];

/*
LimpaTESTES();
GravaTESTES('strTePalavraChave:'.$strTePalavraChave);
GravaTESTES('strIdIpRede:'.$strIdIpRede);
*/

// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos domínios
if ($te_palavra_chave == $strTePalavraChave)
	{
	$arrRedes 		= getValores('redes','id_dominio','id_ip_rede = "'.$strIdIpRede.'"');
	$strIdDominio	= $arrRedes['id_dominio'];
	
	conecta_bd_cacic();	
	$query_SEL	= 'SELECT		id_dominio,
								nm_dominio  
				   FROM			dominios
				   WHERE		in_ativo = "S"
				   ORDER BY		nm_dominio';
	$result_SEL = mysql_query($query_SEL);
	
	$strTripaDominios = '';
	while ($row_SEL = mysql_fetch_array($result_SEL))
		$strTripaDominios .= $row_SEL['id_dominio'].';'.$row_SEL['nm_dominio'].($row_SEL['id_dominio']==$strIdDominio?'*':'').';';

	if ($strTripaDominios <> '')
		$retorno_xml_values = '<DOMINIOS>'.EnCrypt($key,$iv,$strTripaDominios  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</DOMINIOS>';
	}

if ($retorno_xml_values <> '')
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;
else
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'ERRO!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		
$retorno_xml = $retorno_xml_header . $retorno_xml_values; 

echo $retorno_xml;	  
?>
