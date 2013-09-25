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
require_once('../include/library.php');

// Defini��o do n�vel de compress�o (Default = 9 => m�ximo)
//$v_compress_level = 9;
$v_compress_level = 0;  // Mantido em 0(zero) para desabilitar a Compress�o/Decompress�o 
						// H� necessidade de testes para An�lise de Viabilidade T�cnica 

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


// Para agilizar o processo de verifica��o da vers�o...
$te_versao_mapa		= DeCrypt($key,$iv,$_POST['te_versao_mapa']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

// Diferenciar entre opera��o de Autentica��o e de Acesso
$te_operacao		= DeCrypt($key,$iv,$_POST['te_operacao']		,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 

//GravaTESTES('te_operacao='.$te_operacao);
//GravaTESTES('te_versao_mapa='.$te_versao_mapa);

//GravaTESTES('Debug 0');
$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?><STATUS>OK</STATUS><' .'CONFIGS>'; //(?? Deve ser coisa do Macromedia DreamWeaver... - Anderson Peterle)

//GravaTESTES('Debug 1');
$retorno_xml_values	 = '';
//GravaTESTES('Debug 2');
$boolVersaoCorreta   = true;
//GravaTESTES('Debug 3');
if ($te_versao_mapa <> '')
	{
//GravaTESTES('Montando array...');
	$v_array_versoes_agentes = parse_ini_file('../repositorio/install/versoes_agentes.ini');	
//GravaTESTES('v_array_versoes_agentes='.$v_array_versoes_agentes['mapacacic.exe']);
	if ($v_array_versoes_agentes['mapacacic.exe'] <> $te_versao_mapa)
		{
		$retorno_xml_values	 = '<TE_VERSAO_MAPA>'.EnCrypt($key,$iv,$v_array_versoes_agentes['mapacacic.exe'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</TE_VERSAO_MAPA>';	
		$boolVersaoCorreta = false;
		}
	}

if ($boolVersaoCorreta && $te_operacao == 'Autentication')
	{
	// Autentica��o do agente MapaCacic.exe:
	autentica_agente($key,$iv,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
	// Essa condi��o testa se a chamada trouxe o valor de cs_mapa-cacic, enviado por MapaCacic.exe
	if (trim(DeCrypt($key,$iv,$_POST['cs_MapaCacic'],$v_cs_cipher,$v_cs_compress,$strPaddingKey))=='S')
		{	
		// Foi retirada a obrigatoriedade de N�vel "T�cnico" em Outubro/2009
		$v_dados_rede = getDadosRede();	
		conecta_bd_cacic();
		$qry_usuario = "SELECT 	a.id_usuario,
								a.nm_usuario_completo,
								a.id_local,
								a.te_locais_secundarios,
								c.sg_local
						FROM 	usuarios a, 
								locais c
						WHERE 	a.nm_usuario_acesso = '". trim(DeCrypt($key,$iv,$_POST['nm_acesso'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."' AND 
								a.id_local = c.id_local AND 
								a.te_senha = ";
								//SHA1('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress)) ."')";

//GravaTESTES('QRY_USUARIO='.$qry_usuario);

		// Solu��o tempor�ria, at� total converg�ncia para vers�es 4.0.2 ou maior de MySQL 
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$v_AUTH_SHA1 	 = " SHA1('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";
		$v_AUTH_PASSWORD = " PASSWORD('". trim(DeCrypt($key,$iv,$_POST['te_senha'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) ."')";	
	
		// Para MySQL 4.0.2 ou maior	
		// Anderson Peterle - Dataprev/ES - 04/09/2006
		$query = $qry_usuario . $v_AUTH_SHA1; 
//GravaTESTES('QUERY = '.$query);		
		$result_qry_usuario = mysql_query($query);
		if (mysql_num_rows($result_qry_usuario)<=0)
			{
			// Para MySQL at� 4.0	
			// Anderson Peterle - Dataprev/ES - 04/09/2006		
			$query = $qry_usuario . $v_AUTH_PASSWORD;
			$result_qry_usuario = mysql_query($query);
			}
		
		if (mysql_num_rows($result_qry_usuario)>0)
			{
			$row = mysql_fetch_array($result_qry_usuario);
			$retorno_xml_values .= '<ID_USUARIO>'.EnCrypt($key,$iv,$row['id_usuario'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</ID_USUARIO>';								
			$retorno_xml_values .= '<NM_USUARIO_COMPLETO>'.EnCrypt($key,$iv,$row['nm_usuario_completo'].' ('.$row['sg_local'].')',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NM_USUARIO_COMPLETO>';										
			$_SESSION['id_usuario'] = $row['id_usuario'];		
			GravaLog('ACE',$_SERVER['SCRIPT_NAME'],'acesso');						
			}

		}
	}
$retorno_xml = $retorno_xml_header . $retorno_xml_values . "</CONFIGS>";  
echo $retorno_xml;	  
?>
