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
 Esse script tem como objetivo enviar ao servidor de suporte remoto na estação as configurações (em XML) que são específicas para a 
 estação em questão. São levados em consideração a rede do agente, sistema operacional e Mac-Address.
*/
require_once('../include/library.php');

// Definição do nível de compressão (Default = 9 => máximo)
//$v_compress_level = 9;
$v_compress_level   = 0;  // Mantido em 0(zero) para desabilitar a Compressão/Decompressão 
						  // Há necessidade de testes para Análise de Viabilidade Técnica 


GravaTESTES('AuthClient: Valores Recebidos:');
foreach($HTTP_POST_VARS as $i => $v) 
	GravaTESTES('AuthClient: POST => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));

foreach($HTTP_GET_VARS as $i => $v) 
	GravaTESTES('AuthClient: GET => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));

GravaTESTES('');	

$retorno_xml_header = '<?xml version="1.0" encoding="iso-8859-1" ?>';
$retorno_xml_values = '';

// Essas variáveis conterão os indicadores de criptografia e compactação
$v_cs_cipher		= (trim($_POST['cs_cipher'])   <> ''?trim($_POST['cs_cipher'])   : '4');
$v_cs_compress		= (trim($_POST['cs_compress']) <> ''?trim($_POST['cs_compress']) : '4');

$v_cs_cipher		= '1';

$strPaddingKey  	= '';
	
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

GravaTESTES('AuthClient: te_palavra_chave: '.$te_palavra_chave); 	
$arrComputadores 	= getValores('computadores c, redes r', 'c.te_palavra_chave,c.te_nome_computador,c.te_ip,r.id_local'   , 'c.te_node_address = "'.$te_node_address.'" and c.id_so = '.$arrSO['id_so'].' and r.id_ip_rede = c.id_ip_rede');
$strTePalavraChave	= $arrComputadores['te_palavra_chave'];

GravaTESTES('AuthClient: strTePalavraChave: '.$strTePalavraChave); 	


// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos domínios
if ($te_palavra_chave == $strTePalavraChave)
	{
	GravaTESTES('AuthClient: Palavra-Chave OK!'); 	
	conecta_bd_cacic();	

	if ($_POST['nm_usuario_visitante'] && $_POST['te_senha_visitante'])
		{
		$nm_usuario_visitante 		= DeCrypt($key,$iv,$_POST['nm_usuario_visitante'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 					
		$te_senha_visitante	  		= DeCrypt($key,$iv,$_POST['te_senha_visitante'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 			
		GravaTESTES('AuthClient: nm_usuario_visitante 		=> '.$nm_usuario_visitante);
		GravaTESTES('AuthClient: te_senha_visitante   		=> '.$te_senha_visitante);		

		// Autentico o usuário técnico, verificando nome, senha e local
		$query_AUTENTICA = "SELECT  id_usuario,
									nm_usuario_completo,
									id_local,
									te_locais_secundarios,
									te_emails_contato
						 	FROM	usuarios
							WHERE   nm_usuario_acesso = '".$nm_usuario_visitante."' AND
							        te_senha = PASSWORD('".$te_senha_visitante."')";
		$result_AUTENTICA = mysql_query($query_AUTENTICA);			
		$row = mysql_fetch_array($result_AUTENTICA);
		if ($row['id_usuario']<>'')			
			{			
			$boolIdLocal = stripos2($row['te_locais_secundarios'],$arrComputadores['id_local'],false);
			GravaTESTES('AuthClient: boolIdLocal => '.$boolIdLocal); 				
			GravaTESTES('AuthClient: arrComputadores[id_local] => '.$arrComputadores['id_local']); 					
			GravaTESTES('AuthClient: row[id_local] => '.$row['id_local']); 						
			if ($row['id_local'] == $arrComputadores['id_local'] || $boolIdLocal)
				{
				$id_sessao	  			   = DeCrypt($key,$iv,$_POST['id_sessao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 							
				$id_usuario_visitante 	   = $row['id_usuario'];
				$te_node_address_visitante = DeCrypt($key,$iv,$_POST['te_node_address_visitante'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 															
				$dt_hr_autenticacao	 	   = date('Y-m-d H:i:s');						
				
				$query_SESSAO = "INSERT INTO srcacic_sessoes_logs 
											(id_sessao,
											 id_usuario_visitante,
											 te_node_address_visitante,											 
											 dt_hr_ultimo_contato)
								VALUES ("  . $id_sessao 				. ", 
										"  . $id_usuario_visitante 		. ",
										'" . $te_node_address_visitante . "',
										'" . $dt_hr_autenticacao		. "')";
				$result_SESSAO = mysql_query($query_SESSAO);	

				GravaTESTES('AuthClient: query_SESSAO => '.$query_SESSAO); 										

				$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>'.$retorno_xml_values;			
				$retorno_xml_values .= '<ID_USUARIO_VISITANTE>'.EnCrypt($key,$iv,$row['id_usuario'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</ID_USUARIO_VISITANTE>';		
				$retorno_xml_values .= '<NM_USUARIO_COMPLETO>'.EnCrypt($key,$iv,$row['nm_usuario_completo'],$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</NM_USUARIO_COMPLETO>';								
				if ($row['te_emails_contato'] <> '')
					{
					$strTeNomeComputador = $arrComputadores['te_nome_computador'];				
					$strTeIp 			 = $arrComputadores['te_ip'];							
			
					// Envio e-mail informando da abertura de sessão
					$corpo_mail = "Prezado usuário(a) ".$row['nm_usuario_completo'].",\n\n
									informamos que foi realizada autenticação de acesso para Suporte Remoto Seguro à estação '".$strTeNomeComputador."' (IP: ".$strTeIp.") através do Sistema CACIC em ".$dt_hr_inicio_sessao . " a partir de seu usuário '".$nm_usuario_visitante.", cadastrado no www-cacic.'\n\n\n\n
									_______________________________________________________________________
								CACIC - Configurador Automático e Coletor de Informações Computacionais\n
								srCACIC - Módulo para Suporte Remoto Seguro do Sistema CACIC\n
								Desenvolvido pela Dataprev - Unidade Regional Espírito Santo";

					// Manda mail para os administradores.
					mail($row['te_emails_contato'], "Sistema CACIC - Módulo srCACIC - Autenticação para Suporte Remoto Seguro", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
					}										
				}
			else
				$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'O Usuário Técnico Não Tem Permissão de Suporte Remoto Nesta SubRede',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';						
			}
		else
			$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'O Usuário Técnico Não Foi Autenticado',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		}
	}

$retorno_xml = $retorno_xml_header . $retorno_xml_values; 
GravaTESTES('AuthClient XML: '.$retorno_xml);				
echo $retorno_xml;	
?>
