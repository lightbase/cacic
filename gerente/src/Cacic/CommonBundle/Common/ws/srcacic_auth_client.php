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
 Esse script tem como objetivo enviar ao servidor de suporte remoto na esta��o as configura��es (em XML) que s�o espec�ficas para a 
 esta��o em quest�o. S�o levados em considera��o a rede do agente, sistema operacional e Mac-Address.
*/
require_once('../include/library.php');

function insereNovoSO($te_so_new)
	{
	conecta_bd_cacic();
	
	// Busco o �ltimo valor do Identificador Externo caso n�o tenha recebido valor para o ID Externo
	$querySEL  = 'SELECT max(id_so) as MaxIdSo
				  FROM   so';
	$resultSEL = mysql_query($querySEL);
	$rowSEL    = mysql_fetch_array($resultSEL);			
	$id_so = ($rowSEL['MaxIdSo']+1);														   

	// Insiro a informa��o na tabela de Sistemas Operacionais incrementando o Identificador Externo
	$queryINS  = 'INSERT 
				  INTO 		so(id_so,te_desc_so,sg_so,te_so) 
				  VALUES    ('.$id_so.',"S.O. a Cadastrar","Sigla a Cadastrar","'.$te_so_new.'")';

	$resultINS = mysql_query($queryINS);		
	return $id_so;
	}

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


GravaTESTES('***** authClient *****');
if (count($HTTP_POST_VARS)>0)
	foreach($HTTP_POST_VARS as $i => $v)
    	    GravaTESTES('AuthClient: POST => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));

if (count($HTTP_GET_VARS)>0)
	foreach($HTTP_GET_VARS as $i => $v)
    	GravaTESTES('AuthClient: GET => '.$i.' => '.$v.' => '.DeCrypt($key,$iv,$v,$v_cs_cipher,$v_cs_compress,$strPaddingKey));

GravaTESTES('');

conecta_bd_cacic();	
	
// Autentica��o da Esta��o Visitante
$te_node_address = DeCrypt($key,$iv,$_POST['te_node_address'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_so          = DeCrypt($key,$iv,$_POST['te_so']      ,$v_cs_cipher,$v_cs_compress,$strPaddingKey);

//$te_node_address_cli = DeCrypt($key,$iv,$_POST['te_node_address_cli'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 
$te_so_cli        	= DeCrypt($key,$iv,$_POST['te_so_cli']    ,$v_cs_cipher,$v_cs_compress,$strPaddingKey);
$te_palavra_chave  	= DeCrypt($key,$iv,urldecode($_POST['te_palavra_chave'])	,$v_cs_cipher,$v_cs_compress,$strPaddingKey); 



// ATEN��O: Apenas retornar� um ARRAY contendo "id_so" e "te_so".
$arrSO = inclui_computador_caso_nao_exista(	$te_node_address, 
											'',
											$te_so,
											'', 
											'', 
											'',
											'');									

//GravaTESTES('AuthClient: te_palavra_chave: '.$te_palavra_chave); 	
$arrComputadores 	= getValores('computadores c, redes r', 'c.te_palavra_chave,c.te_nome_computador,c.te_ip,r.id_local'   , 'c.te_node_address = "'.$te_node_address.'" and c.id_so = '.$arrSO['id_so'].' and r.id_ip_rede = c.id_ip_rede');
$strTePalavraChave	= $arrComputadores['te_palavra_chave'];

//GravaTESTES('AuthClient: strTePalavraChave: '.$strTePalavraChave); 	


// Valido a Palavra-Chave e monto a tripa com os nomes e ids dos servidores de autentica��o
if ($te_palavra_chave == $strTePalavraChave)
	{
//	GravaTESTES('AuthClient: Palavra-Chave OK!'); 	
	//conecta_bd_cacic();	

	if ($_POST['nm_usuario_cli'] && $_POST['te_senha_cli'])
		{
		$nm_usuario_cli 		= DeCrypt($key,$iv,$_POST['nm_usuario_cli'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 					
		$te_senha_cli	  		= DeCrypt($key,$iv,$_POST['te_senha_cli'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 			

		// Autentico o usu�rio t�cnico, verificando nome, senha e local
		$arrUsuarios = getValores('usuarios','id_usuario,
								  			  nm_usuario_completo,
											  id_local,
											  id_grupo_usuarios,
											  te_locais_secundarios,
											  te_emails_contato','nm_usuario_acesso = "'.$nm_usuario_cli.'" AND
							        		  te_senha = PASSWORD("'.$te_senha_cli.'")');
		if ($arrUsuarios['id_usuario']<>'')			
			{			
			$boolIdLocal = stripos2(trim($arrUsuarios['te_locais_secundarios']),$arrComputadores['id_local'],false);

			// Caso o usuario tenha como local primario o local do computador ou
			// Caso o usuario seja do nivel "Administracao" ou
			// Caso o usuario tenha como local secundario o local do computador.
			if ($arrUsuarios['id_local'] == $arrComputadores['id_local'] ||$arrUsuarios['id_grupo_usuarios'] == '2' || $boolIdLocal)
				{								
				$id_sessao	  			   = DeCrypt($key,$iv,$_POST['id_sessao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 							
				$id_usuario_cli 	   		= $arrUsuarios['id_usuario'];
				$te_motivo_conexao 		   = DeCrypt($key,$iv,$_POST['te_motivo_conexao'],$v_cs_cipher,$v_cs_compress,$strPaddingKey); 																			
				$te_documento_referencial  = DeCrypt($key,$iv,$_POST['te_documento_referencial'],$v_cs_cipher,$v_cs_compress,$strPaddingKey);

				$dt_hr_autenticacao	 	   = date('Y-m-d H:i:s');	
				//GravaTESTES('AuthClient: dt_hr_autenticacao => '.$dt_hr_autenticacao); 																		
				$dt_hr_inicio_sessao	   = date('d-m-Y') . ' �s ' . date('H:i') . 'h';
				//GravaTESTES('AuthClient: dt_hr_inicio_sessao => '.$dt_hr_inicio_sessao); 																		
				// Identifico o SO da m�quina visitante
				$arrIdSO = getValores('so','id_so','trim(te_so) = "'.trim($te_so_cli).'"');
				
				$id_so_cli = $arrIdSO['id_so'];
				//GravaTESTES('AuthClient: id_so_cli => '.$id_so_cli); 														
				
				if ($id_so_cli == '')
					$id_so_cli = insereNovoSO($te_so_cli);

				$query_SESSAO = "INSERT INTO srcacic_conexoes 
											(id_sessao,
											 id_usuario_cli,
											 te_documento_referencial,
											 te_motivo_conexao,
											 dt_hr_ultimo_contato,
											 dt_hr_inicio_conexao,
											 id_so_cli)											
								VALUES ("  . $id_sessao 				. ", 
										"  . $id_usuario_cli 		. ",
										'" . $te_documento_referencial . "',
										'" . $te_motivo_conexao . "',										
										'" . $dt_hr_autenticacao		. "',
										'" . $dt_hr_autenticacao		. "',
										 " . $id_so_cli			.")";								
				$result_SESSAO = mysql_query($query_SESSAO);
				
				$query_CONEXAO = "SELECT 	id_conexao
								  FROM		srcacic_conexoes 
								  WHERE		id_sessao = ". $id_sessao			. " AND
								  			id_usuario_cli = ".$id_usuario_cli	. " AND
											dt_hr_inicio_conexao = '".$dt_hr_autenticacao	. "'";								
				$result_CONEXAO = mysql_query($query_CONEXAO);
				$row_CONEXAO	= mysql_fetch_array($result_CONEXAO);
					

				//GravaTESTES('AuthClient: query_SESSAO => '.$query_SESSAO); 										

				$retorno_xml_values  = '<STATUS>'				.EnCrypt($key,$iv,'OK',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)										.'</STATUS>';			
				$retorno_xml_values .= '<ID_USUARIO_CLI>'	.EnCrypt($key,$iv,trim($arrUsuarios['id_usuario']),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)			.'</ID_USUARIO_CLI>';		
				$retorno_xml_values .= '<NM_USUARIO_COMPLETO>'	.EnCrypt($key,$iv,trim($arrUsuarios['nm_usuario_completo']),$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)	.'</NM_USUARIO_COMPLETO>';								
				$retorno_xml_values .= '<DT_HR_INICIO_SESSAO>'	.EnCrypt($key,$iv,$dt_hr_inicio_sessao			   ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)			.'</DT_HR_INICIO_SESSAO>';												
				$retorno_xml_values .= '<ID_CONEXAO>'			.EnCrypt($key,$iv,$row_CONEXAO['id_conexao']	   ,$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey)			.'</ID_CONEXAO>';																
		
				if ($arrUsuarios['te_emails_contato'] <> '')
					{
					$strTeNomeComputador = $arrComputadores['te_nome_computador'];				
					$strTeIp 			 = $arrComputadores['te_ip'];							
			
					// Envio e-mail informando da abertura de sess�o
					$corpo_mail = "Prezado usu�rio(a) ".$arrUsuarios['nm_usuario_completo'].",\n\n
									informamos que foi realizada autentica��o de acesso para Suporte Remoto Seguro � esta��o '".$strTeNomeComputador."' (IP: ".$strTeIp.") atrav�s do Sistema CACIC em ".$dt_hr_inicio_sessao . " a partir de seu usu�rio '".$nm_usuario_cli.", cadastrado no www-cacic.'\n\n\n\n
									_______________________________________________________________________
								CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais\n
								srCACIC - M�dulo para Suporte Remoto Seguro do Sistema CACIC\n
								Desenvolvido pela Dataprev - Unidade Regional Esp�rito Santo";

					// Manda mail para os administradores.
					//mail($arrUsuarios['te_emails_contato'], "Sistema CACIC - M�dulo srCACIC - Autentica��o para Suporte Remoto Seguro", "$corpo_mail", "From: cacic@{$_SERVER['SERVER_NAME']}");
					}										
				}
			else
				$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'O Usu�rio T�cnico N�o Tem Permiss�o de Suporte Remoto Nesta SubRede',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';						
			}
		else
			$retorno_xml_values = '<STATUS>'.EnCrypt($key,$iv,'O Usu�rio T�cnico N�o Foi Autenticado',$v_cs_cipher,$v_cs_compress,$v_compress_level,$strPaddingKey).'</STATUS>';
		}
	}

$retorno_xml = $retorno_xml_header . $retorno_xml_values; 

echo $retorno_xml;	
?>
