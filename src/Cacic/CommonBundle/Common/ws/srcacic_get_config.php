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

 Objetivo:
 ---------
 Esse script tem como objetivo enviar ao servidor de suporte remoto na esta��o(srcacic.exe) as configura��es (em XML) que s�o espec�ficas para a 
 esta��o/usu�rio em quest�o. S�o levados em considera��o a rede do agente, sistema operacional e Mac-Address.
 
 Retorno:
 1) <SERVIDORES_AUTENTICACAO>Servidores de Autentica��o cadastrados no Gerente WEB. O servidor de autentica��o referente � subrede da esta��o ser� acrescido de "*".</SERVIDORES_AUTENTICACAO>
 2) <STATUS>Retornar� OK se a palavra chave informada "bater" com a chave armazenada previamente no BD</STATUS>
*/

require_once('../include/library.php');

// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
$v_compress_level   = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						  // H� necessidade de testes para An�lise de Viabilidade T�cnica 

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values = '';

// Essas vari�veis conter�o os indicadores de criptografia e compacta��o
$v_cs_cipher		= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress		= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$v_cs_cipher		= '1';

$strPaddingKey  	= '';


/*
LimpaTESTES();

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
		GravaTESTES('srCACIC_getConfig: GET => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));
	GravaTESTES('');	
	}
*/

conecta_bd_cacic();			

// Autentica��o da Esta��o Visitada
$te_node_address   	= trim( DeCrypt($key,$iv,$_POST['te_node_address'] ,$v_cs_cipher,$v_cs_compress,$strPaddingKey) ); 
$te_so             	= trim( DeCrypt($key,$iv,$_POST['te_so']		     ,$v_cs_cipher,$v_cs_compress,$strPaddingKey) ); 
$te_palavra_chave  	= trim( DeCrypt($key,$iv,$_POST['te_palavra_chave'],$v_cs_cipher,$v_cs_compress,$strPaddingKey) ); 


/*
GravaTESTES('srCACIC_getConfig.te_node_address:'.$te_node_address);
GravaTESTES('srCACIC_getConfig.te_so:'.$te_so);
GravaTESTES('srCACIC_getConfig.te_palavra_chave:'.$te_palavra_chave);
*/

// ATEN��O: Apenas retornar� um ARRAY contendo "id_so" e "te_so".
$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											'',
											$te_so,
											'', 
											'', 
											'',
											'');

$arrComputadores 	= getValores('computadores', 'te_palavra_chave,id_ip_rede'   , 'te_node_address = "'.$te_node_address.'" and id_so = '.trim($arrSO['id_so']));

$strTePalavraChave	= trim( $arrComputadores['te_palavra_chave'] );
$strIdIpRede		= trim( $arrComputadores['id_ip_rede'] );

//GravaTESTES('srCACIC_getConfig.strTePalavraChave:'.$strTePalavraChave);
//GravaTESTES('srCACIC_getConfig.strIdIpRede:'.$strIdIpRede);


// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores para autentica��o
if ($te_palavra_chave == $strTePalavraChave)
	{
	//GravaTESTES('OK! Palavras Chaves IGUAIS!');	
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

	$retorno_xml_values .= '<SERVIDORES_AUTENTICACAO>'.EnCrypt($key,$iv,$strTripaServidores  ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</SERVIDORES_AUTENTICACAO>';
	}
//else
	//GravaTESTES('Oops! Palavras Chaves DIFERENTES!');					

if ($retorno_xml_values <> '')
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;
else
	$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'ERRO!',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		
$retorno_xml = $retorno_xml_header . $retorno_xml_values; 
$retorno_xml = str_replace('+','<MAIS>', $retorno_xml); 
$retorno_xml = str_replace(' ','<ESPACE>', $retorno_xml); 

//GravaTESTES('retorno_xml_values: '.$retorno_xml);	

echo $retorno_xml;	  
?>
