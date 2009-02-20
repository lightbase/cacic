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
require_once('../include/library.php');

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						// Há necessidade de testes para Análise de Viabilidade Técnica 

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


// Para agilizar o processo de verificação da versão...
$te_versao_mapa		= DeCrypt($key,$iv,$_POST['te_versao_mapa']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><' .'CONFIGS>'; //(?? Deve ser coisa do Macromedia DreamWeaver... - Anderson Peterle)
$retorno_xml_values	 = '';

$boolVersaoCorreta   = true;

if ($te_versao_mapa <> '')
	{
	$v_array_versoes_agentes = parse_ini_file('../repositorio/install/versoes_agentes.ini');	
	if ($v_array_versoes_agentes['mapacacic.exe'] <> $te_versao_mapa)
		{
		$retorno_xml_values	 = '<TE_VERSAO_MAPA>'.EnCrypt($key,$iv,$v_array_versoes_agentes['mapacacic.exe'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</TE_VERSAO_MAPA>';	
		$boolVersaoCorreta = false;
		}
	}

if ($boolVersaoCorreta)
	{
	// Autenticação do agente MapaCacic.exe:
	autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
	// Essa condição testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
	if (trim(DeCrypt($key,$iv,$_POST['cs_MapaCacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='S')
		{	
		$v_dados_rede = getDadosRede();	
		conecta_bd_cacic();
		$qry_usuario = "SELECT 	a.id_usuario,
								a.nm_usuario_completo,
								b.id_grupo_usuarios
						FROM 	usuarios a, 
								grupo_usuarios b, 
								locais c
						WHERE 	a.id_grupo_usuarios = b.id_grupo_usuarios AND
								b.id_grupo_usuarios = 7 AND
								a.nm_usuario_acesso = '". trim(DeCrypt($key,$iv,$_POST['nm_acesso'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."' AND 
								a.id_local = c.id_local AND 
								a.te_senha = ";
								//SHA1('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress)) ."')";
								
		// Solução temporária, até total convergência para versões 4.0.2 ou maior de MySQL 
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$v_AUTH_SHA1 	 = " SHA1('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";
		$v_AUTH_PASSWORD = " PASSWORD('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";	
	
		// Para MySQL 4.0.2 ou maior	
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$query = $qry_usuario . $v_AUTH_SHA1; 
		
		$result_qry_usuario = mysql_query($query);
		if (mysql_num_rows($result_qry_usuario)<=0)
			{
			// Para MySQL até 4.0	
			// Anderson Peterle - Dataprev/ES - 04/09/2006		
			$query = $qry_usuario . $v_AUTH_PASSWORD;
			$result_qry_usuario = mysql_query($query);
			}
		
		if (mysql_num_rows($result_qry_usuario)>0)
			{
			$row = mysql_fetch_array($result_qry_usuario);
			$retorno_xml_values .= '<ID_USUARIO>'.EnCrypt($key,$iv,$row['id_usuario'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</ID_USUARIO>';								
			$retorno_xml_values .= '<NM_USUARIO_COMPLETO>'.EnCrypt($key,$iv,$row['nm_usuario_completo'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NM_USUARIO_COMPLETO>';										
			$_SESSION['id_usuario'] = $row['id_usuario'];		
			GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso');						
			}
		}
	}
$retorno_xml = $retorno_xml_header . $retorno_xml_values . "</CONFIGS>";  
echo $retorno_xml;	  
?>
