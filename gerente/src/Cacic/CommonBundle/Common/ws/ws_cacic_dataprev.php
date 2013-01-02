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
require("SOAP/nusoap.php");
set_magic_quotes_runtime(0);

function estatistica_cacic($pergunta) 
	{
	require_once("../include/config.php");
	$conexao = mysql_connect($ip_servidor, $usuario_bd, $senha_usuario_bd);
	$bancodedados = mysql_select_db($nome_bd, $conexao);
	$xml_resposta = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>";
	
	$xml_resposta = $xml_resposta."<estatisticas>";
  	require("bloco_consultas.php");
	$xml_resposta = $xml_resposta."</estatisticas>";
	 
	$saida = $xml_resposta;
	mysql_close($conexao);
	return $saida;	 
	}

// Teste da fun��o antes de publicar como Web Services

//$pergunta = "1,3,5,6,8,";
//echo $pergunta;
//print_r(estatistica_cacic($pergunta));  

// Publica��o como Web service

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