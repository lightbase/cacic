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
require("SOAP/nusoap.php");
set_magic_quotes_runtime(0);

function estatistica_cacic($pergunta) 
	{
	require_once("../include/config.php");
	$conexao = mysql_connect($ip_servidor, $usuario_bd, $senha_usuario_bd);
	$bancodedados = mysql_select_db($nome_bd, $conexao);
	$xml_resposta = "<?php xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	
	$xml_resposta = $xml_resposta."<estatisticas>";
  	require("bloco_consultas.php");
	$xml_resposta = $xml_resposta."</estatisticas>";
	 
	$saida = $xml_resposta;
	mysql_close($conexao);
	return $saida;	 
	}

// Teste da função antes de publicar como Web Services

//$pergunta = "1,3,5,6,8,";
//echo $pergunta;
//print_r(estatistica_cacic($pergunta));  

// Publicação como Web service

$s = new soap_server;
$s->configureWSDL('ws_cacicwsdl', 'urn:ws_cacicwsdl');
$s->wsdl->schemaTargetNamespace = 'urn:ws_cacicwsdl';


$s->wsdl->addComplexType(
    'ListaEstatistica',
    'complexType',
    'array',
    '',
	'SOAP-ENC:Array',
	array(),
    array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'saida')),
	'Estatisticas'
);


$s->register('estatistica_cacic'
	,array('pergunta_xml' => 'xsd:string')        // input parameters
    ,array('return' => 'xsd:string')      // output parameters
    ,'urn:ws_cacicwsdl'                      // namespace
    ,'urn:ws_cacicwsdl#pergunta'   // soapaction
    ,'rpc'                                // style
    ,'encoded'                            // use
    ,'Estatisticas'            // documentation
);

$s->service($HTTP_RAW_POST_DATA);
?>