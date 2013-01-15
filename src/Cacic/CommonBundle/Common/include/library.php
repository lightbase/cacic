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

@session_start();

@define('CACIC',1);
@define('SECURITY',1);

include_once('include/config.php');
require_once('define.php');

if(!include_once( TRANSLATOR_PATH.CACIC_DS.'Translator.php'))
  die ("<h1>There is a trouble with phpTranslator package. It isn't found.</h1>");

/*
 * componente (objeto) para realizar traducao
 */
$oTranslator = new Translator( CACIC_LANGUAGE, CACIC_PATH.CACIC_LANGUAGE_PATH, CACIC_LANGUAGE_STANDARD );
$oTranslator->setURLPath(TRANSLATOR_PATH_URL);
$oTranslator->setLangFilesInSubDirs(true);
$oTranslator->initStdLanguages();

/**
 * Inicializa constantes para traducao de mensagens em javascript
 */
function initJSTranslateConst() 
	{
	global $oTranslator;
	?>
	<script language=JavaScript>
  	const CACIC_JS_MSG_IP_REDE_INVALIDA = '<?php echo $oTranslator->_('Endereco de subrede invalido!')?>';
  	const CACIC_JS_MSG_MASCARA_REDE_INVALIDA = '<?php echo $oTranslator->_('Mascara de subrede invalida!')?>';
  	const CACIC_JS_MSG_ATENCAO = '<?php echo strtoupper($oTranslator->_('Atencao:'))?>';
  	const CACIC_JS_MSG_REDE_AVISO = '<?php echo $oTranslator->_('Com esta mascara, esta subrede atendera a faixa')?>';
  	const CACIC_JS_MSG_CONFIRMA = '<?php echo $oTranslator->_('Confirma?')?>';
  	const CACIC_JS_MSG_A = '<?php echo $oTranslator->_('a')?>';
	</script>
	<?php
	} // end initJSTranslateConst

// --------------------------------------------------------------------------
// Função para retorno dos nomes das colunas de hardware passível de controle
// --------------------------------------------------------------------------
function getDescricoesColunasComputadores()
	{
	// Conecto ao banco
	conecta_bd_cacic();	
	
	// Consulto lista de colunas de hardware e retorno em um array
	$queryDescricoesColunasComputadores  = "SELECT 	nm_campo, 
											te_descricao_campo
								 FROM 		descricoes_colunas_computadores";
	$resultDescricoesColunasComputadores = mysql_query($queryDescricoesColunasComputadores) or die('Ocorreu um erro durante a consulta à tabela descricoes_colunas_computadores.');

	// Crio um array que conterá nm_campo => te_descricao_campo.	 
	$arrDescricoesColunasComputadoresAux = array();
	while($rowHardware = mysql_fetch_array($resultDescricoesColunasComputadores)) 	
		$arrDescricoesColunasComputadoresAux[trim($rowHardware['nm_campo'])] = $rowHardware['te_descricao_campo'];

	return $arrDescricoesColunasComputadoresAux;
	}

// --------------------------------------------------------------
// Função para retorno dos nomes de hardware passível de controle
// --------------------------------------------------------------
function getDescricaoHardware()
	{
	// Conecto ao banco
	conecta_bd_cacic();	
	
	// Consulto lista de descrições de hardware e retorno em um array
	$queryDescricaoHardware  = "SELECT 	nm_campo_tab_hardware, 
										te_desc_hardware
							 FROM 		descricao_hardware";
	$resultDescricaoHardware = mysql_query($queryDescricaoHardware) or die('Ocorreu um erro durante a consulta à tabela descricao_hardware.');

	// Crio um array que conterá nm_campo => te_descricao_campo.	 
	$arrDescricaoHardwareAux = array();
	while($rowHardware = mysql_fetch_array($resultDescricaoHardware)) 	
		$arrDescricaoHardwareAux[trim($rowHardware['nm_campo_tab_hardware'])] = $rowHardware['te_desc_hardware'];
	return $arrDescricaoHardwareAux;
	}
	
// --------------------------------------------------------------------------------------
// Função para bloqueio de acesso indevido
// --------------------------------------------------------------------------------------
function AntiSpy($strNiveisPermitidos = '')
	{
	//GravaTESTES('AntiSpy: strNiveisPermitidos="'.$strNiveisPermitidos.'"');
	//GravaTESTES('AntiSpy: Nivel Atual="'.$_SESSION['cs_nivel_administracao'].'"');	
	if ($strNiveisPermitidos <> '')
		$boolNivelPermitido = stripos2(','.$strNiveisPermitidos.',',','.$_SESSION['cs_nivel_administracao'].',',false);
	else
		$boolNivelPermitido = true;

	include CACIC_PATH . 'include/config.php'; // Incluo o config.php para pegar as chaves de criptografia	

	//GravaTESTES('AntiSpy: Caminho do config="'.CACIC_PATH . 'include/config.php'.'"');			
	
	if (session_is_registered("id_usuario") && session_is_registered("id_usuario_crypted") && 
	    $_SESSION["id_usuario_crypted"] == EnCrypt($key,$iv,$_SESSION["id_usuario"],"1","0","0","") &&
	    $boolNivelPermitido)
   		return true;
	$strLocation = CACIC_PATH . 'include/acesso_nao_permitido.php';
	
	//GravaTESTES('AntiSpy: Redirecionando para Nivel Atual="'.CACIC_PATH . 'include/acesso_nao_permitido.php'.'"');	
	
	header ("Location: $strLocation");		
	exit;		
	}
	
// ------------------------------------------------------------------------------------------------
// Função para exibição de data do script para fins de Debug. Os IP´s são definidos em menu_seg.php
// Novas informações poderão ser acrescentadas futuramente...
// ------------------------------------------------------------------------------------------------
function Debug($p_ScriptFileName)
	{
	// Verifico se o script chamador refere-se a um gerador de imagem e impeço o Debug neste caso.
	$intIsPie = substr_count($p_ScriptFileName,'pie_'); 
	if (!$intIsPie)
		{
		$strRemoteAddr = '['.$_SERVER['REMOTE_ADDR'].']';
		$intIPsDisplayDebugs = substr_count($_SESSION['cIpsDisplayDebugs'],$strRemoteAddr); 
		if ($intIPsDisplayDebugs)
			{
			echo '<font size=1>';
			echo '<br>______________________________________________________________________<br>';
			echo 'Debug: '.$_SERVER['REMOTE_ADDR'].' => '.$p_ScriptFileName.' - '.date("dmy H:i",filemtime($p_ScriptFileName));	
			echo '<br>______________________________________________________________________<br>';
			echo '</font>';
			}
		}
	}

/**
 * Menu a ser apresentado ao usuario conforme o idioma selecionado
 *
 * @param string $_menu_name Nome do menu a ser pesquisado
 * @return string Caminho do menu
 */
function getMenu($_menu_name) 
	{
    $_file_lang = 'language'.DIRECTORY_SEPARATOR.CACIC_LANGUAGE.DIRECTORY_SEPARATOR.$_menu_name;
    if(is_file($_file_lang) and is_readable($_file_lang)) 
        return $_file_lang;
    else 
		{
        $_file_lang = 'language'.DIRECTORY_SEPARATOR.CACIC_LANGUAGE_STANDARD.DIRECTORY_SEPARATOR.$_menu_name;
        if(is_file($_file_lang) and is_readable($_file_lang)) 
	        return $_file_lang;
        else return "Erro no menu (Menu error)!".$_file_lang;
        }
	}


// __________________________________________________________________
// Apenas uma alternativa mais completa à função "stripos" do PHP5...
// __________________________________________________________________
/*
// Retornará 0 ou 1 se $pos for FALSE
// 		0 -> Se a String haystack NÃO CONTIVER a subString needle
// 		1 -> Se a String haystack CONTIVER     a subString needle
// Retornará a posição da subString needle na string haystack se $boolRetornaPosicao for TRUE ou NULO
*/
function stripos2($strString, $strSubString, $boolRetornaPosicao = true)
	{
	$intPos = strpos($strString, stristr( $strString, $strSubString ));

	if (!$boolRetornaPosicao)
		$intPos = (($intPos < 0 || trim($intPos) == '') ? 0 : 1);

	return $intPos;
	}
	
// ---------------------------------
// Função usada para descriptografia 
// To decrypt values 
// p_cs_cipher => Y/N 
// ---------------------------------
function DeCrypt($p_CipherKey, $p_IV, $p_CriptedData, $p_cs_Cipher, $p_cs_UnCompress='0', $p_PaddingKey = '') 
	{
	$p_CipherKey .= $p_PaddingKey;
	
	// Bloco de Substituições para antes da Decriptação
	// ------------------------------------------------
	// Razão: Dependendo da configuração do servidor, os valores
	//        enviados, pertinentes à criptografia, tendem a ser interpretados incorretamente.
	// Obs.:  Vide Lista de Convenções Abaixo
	// =======================================================================================
	$pp_CriptedData = str_ireplace('[[MAIS]]','+',$p_CriptedData,$countMAIS);
	// =======================================================================================
	
	if ($p_cs_Cipher=='1') 
		$v_result = (trim($pp_CriptedData)<>''?@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$p_CipherKey,base64_decode($pp_CriptedData),MCRYPT_MODE_CBC,$p_IV):'');
	else
		$v_result = $p_CriptedData;	

	// Bloco de Substituições para depois da Decriptação
	// -------------------------------------------------
	// Razão: Ídem acima, porém, com dados pertinentes aos valores a serem recebidos
	// =============================================================================		
	$v_result = str_ireplace('[[AD]]'     ,'"'   ,$v_result,$countAD);		
	$v_result = str_ireplace('[[AS]]'     ,"'"   ,$v_result,$countAS);				
	$v_result = str_ireplace('[[BarrInv]]','\\\\',$v_result,$countINV);			
	$v_result = str_ireplace('[[ESPACE]]' ,' '   ,$v_result,$countESPACE);						
	// =============================================================================

	// Convenções Adotadas para as Substituições
	// -----------------------------------------
	// [[MAIS]]    => Sinal de Mais   => "+" (comumente interpretado como espaço, prejudicando a decriptografia)
	// [[BarrInv]] => Barra Invertida => "\" (comumente interpretado como ESCAPE na recepçâo)
	// [[AS]]      => Aspa Simples    => "'"
	// [[AD]]      => Aspa Dupla      => '"'
	// [[ESPACE]]  => Espaço          => ' '
	// =============================================================================================
	
	if ($p_cs_UnCompress == '1')
		$v_result = gzinflate($v_result);		

	//GravaTESTES('Em DeCrypt: p_PaddingKey = "'.$p_PaddingKey.'"');					
	
	// Aqui retiro do resultado a ocorrência do preenchimento, caso exista. (o agente Python faz esse preenchimento)
	if ($p_PaddingKey <> '') 
		{               
		$char 		= substr($p_PaddingKey,0,1);
       	$re 		= "/".$char."*$/";
       	$v_result 	= preg_replace($re, "", $v_result);
   		} 

	GravaTESTES('Em DeCrypt: p_CriptedData = "'.$p_CriptedData.'"');				
	GravaTESTES('Em DeCrypt: v_result = "'.$v_result.'"');				

	return trim($v_result);
	}

// ---------------------------------
// Se a função HASH nativa faltar...
// ---------------------------------
if(!function_exists('hash')) 
	{
   	function hash($algo, $data, $raw_output = 0)
   		{
      	if($algo == 'md5') 
			return(md5($data, $raw_output));
      	if($algo == 'sha1') 
			return(sha1($data, $raw_output));
   		}
	} 

// ------------------------------
// Função usada para criptografia
// To crypt values
// p_cs_cipher => Y/N 
// ------------------------------
function EnCrypt($p_CipherKey, $p_IV, $p_PlainData, $p_cs_Cipher, $p_cs_Compress, $p_compress_level=0, $p_PaddingKey = '') 
	{
	$p_CipherKey .= $p_PaddingKey;
	
	if ($p_cs_Cipher=='1') 
		$v_result = base64_encode(@mcrypt_cbc(MCRYPT_RIJNDAEL_128,$p_CipherKey,$p_PlainData,MCRYPT_ENCRYPT,$p_IV));		
	else
		$v_result = $p_PlainData;	

	if (($p_cs_Compress == '1' || $p_cs_Compress == '2') && $p_compress_level > 0)
		$v_result = gzdeflate($v_result,$p_compress_level);
//		$v_result = url_encode(gzcompress($v_result,$p_compress_level));		
	//GravaTESTES('ENCRYPT: '.$p_CipherKey.' / '.$p_IV.' / '.$p_PlainData.' / '.$v_result.' / '.$p_cs_Cipher);		
	return trim($v_result);		
	}

//________________________________________________________________________________________________
// Retorna o PRIMEIRO e ULTIMO nome no argumento $Nome
//________________________________________________________________________________________________
function PrimUltNome($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
	$FatorDecremento = FatorDecremento(Count($Array_Nome));
  	return $Array_Nome[0] . " " . $Array_Nome[Count($Array_Nome) - $FatorDecremento];	
	}
//________________________________________________________________________________________________
// Retorna o PRIMEIRO nome no argumento $Nome
//________________________________________________________________________________________________
function PrimNome($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
  	return $Array_Nome[0];	
	}

//________________________________________________________________________________________________
// Retorna o nome abreviado e com pontos
//________________________________________________________________________________________________
function Abrevia($pStrName)
	{
	$arrName = explode(" ", $pStrName);
	$strReturn = '';
	if (count($arrName)>1)
		for ($i=0; $i<count($arrName); $i++)
			$strReturn .= substr($arrName[$i], 0, 1).'.';
	else
		$strReturn = substr($arrName[0],0,3).'.';
		
  	return $strReturn;	
	}

//_____________________
// Grava Log para DEBUG
// Em 15/06/2007 optou-se por mostrar data de criação do script corrente na tela de estações específicas,
// identificadas por seus IP´s na variável de sessão cIpsDisplayDebugs, declarada e menu_esq.php
//_____________________
function Log_Debug($p_msg)
	{
	$posIPsDisplayDebugs = strpos($GLOBALS["cIPsDisplayDebugs"],$_SERVER['REMOTE_ADDR']); 
	if ($posIPsDisplayDebugs >= 0)
		GravaTESTES($p_msg);
	}
//________________________________________________________________________________________________
// Limpa a tabela TESTES, utilizada para depuração de código
//________________________________________________________________________________________________
function LimpaTESTES()
	{
	conecta_bd_cacic();
	$queryDEL  = 'DELETE from testes';
	$resultDEL = mysql_query($queryDEL);
	}

//___________________________________
// Grava informações na tabela TESTES
//___________________________________
function GravaTESTES($p_Valor)
	{
	$v_Valor = str_replace('"','<AspaDupla>',$p_Valor);
	$v_Valor = str_replace("'",'<AspaSimples>',$v_Valor);	
	conecta_bd_cacic();
	$date = @getdate(); 
	$queryINS  = "INSERT into testes(te_linha) VALUES ('" . $_SERVER['REQUEST_URI'] . ": (".$date['mday'].'/'.$date['mon'].' - '.$date['hours'].':'.$date['minutes'].")Server " .$_SERVER['HTTP_HOST']." Station: ".$_SERVER['REMOTE_ADDR']." - ".$v_Valor . "')";
	$resultINS = mysql_query($queryINS);
	}

//________________________________________________________________________________________________
// Grava na tabela SRCACIC_LOGS as informações de atividades na estação visitada
//________________________________________________________________________________________________
function GravaLogSrCacic($p_id_sessao, $p_te_acao)
	{
	conecta_bd_cacic();
	$queryINS  = "INSERT 
				  INTO		srcacic_logs
				  			(id_sessao,
							 dt_hr_acao,
							 te_acao) 
				  VALUES 	(".$p_id_sessao.",
				  			  NOW(),
							'".$p_te_acao."')";
	$resultINS = mysql_query($queryINS);
	}


//________________________________________________________________________________________________
// Grava na tabela LOG informações de atividades
//________________________________________________________________________________________________
function GravaLog($pStrCsAcao, $pStrNmScript, $pStrNmTabela, $intIdUsuario)
	{
	$arrNmScript = explode('/',$pStrNmScript);	
	conecta_bd_cacic();
	$queryINS  = "INSERT 
				  INTO		log
				  			(dt_acao,
							cs_acao,
							nm_script,
							nm_tabela,
							id_usuario,
							te_ip_origem) 
				  VALUES 	(NOW(),
				  			'" . $pStrCsAcao . "',
							'" . $arrNmScript[count($arrNmScript)-1] . "',
							'" . $pStrNmTabela . "',
							"  . $intIdUsuario . ",
							'".$_SERVER['REMOTE_ADDR']."')";
	$resultINS = mysql_query($queryINS);
	}
	

//________________________________________________________________________________________________
// Retorna 1 ou 0 para decremento de PRIMEIRO e ULTIMO nome
//________________________________________________________________________________________________
function FatorDecremento($Numero)
	{
	if ($Numero>1)
		return 1;
	else
		return 0;
	}
// _______________________________________________________________	

// --------------------------------------------------------------------------------------
// Função de conexão ao BD do CACIC
// --------------------------------------------------------------------------------------
function conecta_bd_cacic() 
	{
	/*
	echo $GLOBALS["ip_servidor"].'<br>';
	echo $GLOBALS["porta"].'<br>';
	echo $GLOBALS["usuario_bd"].'<br>';
	echo $GLOBALS["senha_usuario_bd"].'<br>';
	echo $GLOBALS["nome_bd"].'<br>';
	*/
	
	$ident_bd = mysql_connect($GLOBALS["ip_servidor"] . ':' . $GLOBALS["porta"], 
							  $GLOBALS["usuario_bd"], 
							  $GLOBALS["senha_usuario_bd"]);
	if (mysql_select_db($GLOBALS["nome_bd"], $ident_bd) == 0) 
		die('<b>Problemas durante a conexão ao BD ou sua sessão expirou!</b>');
	return $ident_bd;		
	}

// ------------------------------------------------------------------------------
// Função para obtenção de dados da subrede de acesso, em função do IP e Máscara.
// Function to retrieve access subnet data, based on IP/Mask address.
// ------------------------------------------------------------------------------
function getDadosRede($pIntIdRede = 0)
	{	
	global $key;
	global $iv;
	global $v_cs_cipher;
	global $v_cs_compress;
	global $strPaddingKey;
	
	$intIdRede = ($pIntIdRede ? $pIntIdRede : getIdRede());
	
	$queryDados = '	SELECT 	r.id_rede,
							r.nm_rede,
							r.te_serv_cacic,
							r.te_serv_updates,
							r.nu_limite_ftp,
							r.nu_porta_serv_updates, 
							r.te_path_serv_updates, 							
							r.nm_usuario_login_serv_updates,
							r.te_senha_login_serv_updates,
							r.te_ip_rede,
							r.id_local,
							r.cs_permitir_desativar_srcacic,
							l.sg_local,
							l.nm_local,
							r.dt_debug as dt_debug_subnet,
							l.dt_debug as dt_debug_local							
					FROM	redes r,
							locais l
					WHERE 	r.id_rede = ' . $intIdRede . ' AND
							r.id_local   = l.id_local';
	$resultDados = mysql_query($queryDados);
	$rowDados    = @mysql_fetch_array($resultDados);
	return $rowDados;	
	}

function getIdRede()
	{
	global $key;
	global $iv;
	global $v_cs_cipher;
	global $v_cs_compress;
	global $strPaddingKey;
	
	// Duas tentativas de obtenção do IP da Estação
	$strTeIPComputador = ($_POST['te_ip_computador']  ? trim(DeCrypt($key,$iv,$_POST['te_ip_computador'],$v_cs_cipher,$v_cs_compress,$strPaddingKey)) : $_SERVER['REMOTE_ADDR']);
	$strTeIPComputador = ($strTeIPComputador 		  ? $strTeIPComputador 																	 		  : getenv("REMOTE_ADDR"));	
	GravaTESTES('getIdRede: strTeIPComputador = "' . $strTeIPComputador . '"');	
	conecta_bd_cacic();	

	$queryRedes = "SELECT 	id_rede,
							te_ip_rede,
							te_mascara_rede
				   FROM		redes";
	GravaTESTES('getIdRede: queryRedes = "' .$queryRedes. '"');
	$resultRedes= mysql_query($queryRedes);
	$intIdRede  = 0;	

	// Percorro cada TE_IP + TE_MASCARA_REDE para checar se o IP da estação está na faixa de IPs
	while ($rowRedes = mysql_fetch_array($resultRedes))
		{
       	$ip_octets   = split("\.", $strTeIPComputador);
       	$mask_octets = split("\.", $rowRedes['te_mascara_rede']);
       	unset($bin_sn);      	
		
		for ( $o = 0; $o < count($ip_octets); $o++ )
       		$bin_sn[] = decbin(intval($ip_octets[$o]) & intval($mask_octets[$o]));			

		$apply_mask = join(".", $bin_sn);

       	$ip_octets = split("\.", $apply_mask);
       	unset($bin_sn);
       	for ( $o = 0; $o < count($ip_octets); $o++ )
       		$bin_sn[] = bindec($ip_octets[$o]);

       	$subnet = join(".", $bin_sn);				

		if (trim($subnet)== trim($rowRedes['te_ip_rede']))
			{
			$intIdRede = $rowRedes['id_rede'];
			break;
			}		
		}
		
	if ($intIdRede == 0)
		{
		// Neste caso, apela-se para uma rede que tenha configurações válidas...
		$queryRedes = '	SELECT 	r.id_rede
						FROM	redes r
						WHERE 	trim(r.nu_porta_serv_updates) <> "" and
								trim(r.nm_usuario_login_serv_updates) <> "" and
								trim(r.te_senha_login_serv_updates) <> ""
						LIMIT 1';
		$resultRedes = mysql_query($queryRedes);
		$rowRedes	 = mysql_fetch_array($resultRedes);
		$intIdRede = $rowRedes['id_rede'];
		}	
	GravaTESTES('getIdRede: Result->' . $intIdRede);
	return $intIdRede;	
	}
// ------------------------------------------------------------------------------
// Função para obtenção de dados da estacao de trabalho
// Function to retrieve workstation data
// ------------------------------------------------------------------------------
function getDadosComputador($pStrTeNodeAddress, 
							$pStrTeSO,
							$pStrTeIPComputador,
							$pStrTeNomeComputador,
							$pStrTeDominioDNS,
							$pStrTeDominioWindows,							
							$pStrTeUserName,
							$pStrTeWorkGroup)
	{		
	GravaTESTES('pStrTeNodeAddress: '.$pStrTeNodeAddress);
	GravaTESTES('pStrTeSO: '.$pStrTeSO);
	GravaTESTES('pStrTeIPComputador: '.$pStrTeIPComputador);
	GravaTESTES('pStrTeNomeComputador: '.$pStrTeNomeComputador);
	GravaTESTES('pStrTeDominioDNS: '.$pStrTeDominioDNS);
	GravaTESTES('pStrTeDominioWindows: '.$pStrTeDominioWindows);				
	GravaTESTES('pStrTeUserName: '.$pStrTeUserName);
	GravaTESTES('pStrTeWorkGroup: '.$pStrTeWorkGroup);
	// Obtenho o id_so da base, caso exista
	// Insiro novo S.O. caso não exista
	$arrSO = getValores('so', 'id_so', 'te_so = "' . $pStrTeSO . '"');
	if (!$arrSO['id_so'])
		{	
		conecta_bd_cacic();	
		// Insiro a informação na tabela de Sistemas Operacionais incrementando o Identificador Externo
		$queryINS_SO  = 'INSERT 
						 INTO 		so(te_desc_so,sg_so,te_so) 
						 VALUES    ("S.O. a Cadastrar","Sigla a Cadastrar","'.$pStrTeSO.'")';
		GravaTESTES('Library - queryINS_SO: '.$queryINS_SO);							  						  
		$resultINS_SO = mysql_query($queryINS_SO);		
		
		$arrSO = getValores('so', 'id_so', 'te_so = "' . $pStrTeSO . '"');		
		// Verifico pelo local se há coletas configuradas e acrescento o S.O. à tabela de ações
		$querySEL  = 'SELECT 	id_acao
					  FROM   	acoes_so
					  WHERE  	id_local = '.$arrDadosRede['id_local'].' 
					  GROUP BY 	id_acao';							  						
		GravaTESTES('Library - querySEL 1: '.$querySEL);							  						  
		$resultSEL = mysql_query($querySEL);
			
		// Caso existam ações configuradas para o local, incluo o S.O. para que também execute-as...
		$strInsereID = '';
		while ($rowSEL    = mysql_fetch_array($resultSEL))
			{
			$strInsereID .= ($strInsereID <> ''?',':'');
			$strInsereID .= '('.$arrDadosRede['id_local'].','.$rowSEL['id_acao'].','.$arrSO['id_so'].')';
			}
										
		if ($strInsereID <> '')
			{
			$queryINS = 'INSERT INTO acoes_so(id_local, id_acao, id_so) 
						 VALUES '.$strInsereID;
			GravaTESTES('Library - queryINS 1: '.$queryINS);							  						  							 
			$resultINS = mysql_query($queryINS);
			}			
		}
	
	$boolExists			= true;
	$arrDadosComputador = getValores('computadores,so', 'so.id_so,computadores.*', 'computadores.te_node_address = "' . $pStrTeNodeAddress . '" and computadores.id_so = so.id_so');		
	$arrDadosRede		= getDadosRede(getIdRede());

	if ($arrDadosComputador['dt_hr_inclusao'] == '')
		{
		$boolExists = false;

		$queryINS_Comp = 'INSERT INTO 	computadores 
										(te_node_address, 
										 id_so, 
										 id_rede, 
										 te_ip_computador, 
										 te_nome_computador, 
										 te_dominio_dns,
										 te_dominio_windows,
										 te_workgroup, 
										 dt_hr_inclusao, 
										 dt_hr_ult_acesso)
						  VALUES 		("'.$pStrTeNodeAddress.'",
						  				  '.$arrSO['id_so'].',
										  '.$arrDadosRede['id_rede'].',
										 "'.$pStrTeIPComputador.'",
										 "'.$pStrTeNomeComputador.'",
										 "'.$pStrTeDominioDNS.'",
										 "'.$pStrTeDominioWindows.'",
										 "'.$pStrTeWorkGroup.'",
										   NOW(),
										   NOW())';
		GravaTESTES('Library - queryINS_Comp: '.$queryINS_Comp);							  						  										   
		$resultINS_Comp = mysql_query($queryINS_Comp);						
		
		$arrDadosComputador = getValores('computadores', '*', 'computadores.te_node_address = "' . $pStrTeNodeAddress . '" and id_rede = ' . $arrDadosRede['id_rede']);				
		}
		
	
	$queryUPD_Comp = 'UPDATE 	computadores 
					  SET 		id_rede = '				. $arrDadosRede['id_rede']	.',
						  		te_ip_computador = "'	. $pStrTeIPComputador		.'",
						  		te_nome_computador="'	. $pStrTeNomeComputador		.'",
						  		te_dominio_dns="'		. $pStrTeDominioDNS			.'",
						  		te_dominio_windows="'	. $pStrTeDominioWindows		.'",						  						  
						  		te_workgroup="'			. $pStrTeWorkGroup			.'"					  
					  WHERE 	id_computador='			. $arrDadosComputador['id_computador'];
	GravaTESTES('Library - queryUPD_Comp: '.$queryUPD_Comp);							  						  										   
	$resultUPD_Comp = mysql_query($queryUPD_Comp);			
	
	return $arrDadosComputador;
	}

// --------------------------------------------------------------------------------------
// Para Atualização das colunas dt_hr_alteracao_patrim_uonx da table configuracoes
// quando INCLUDE/UPDATE/DELETE em Unidades Organizacionais
// --------------------------------------------------------------------------------------
function atualiza_configuracoes_uonx($p_uonx) 
	{
    conecta_bd_cacic();
	$v_nome_coluna = 'dt_hr_alteracao_patrim_uon'.$p_uonx;
	$query = '	UPDATE  configuracoes_locais 
				SET		'.$v_nome_coluna.' = now()';

    if (mysql_query($query)) 
		{ 
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais',$_SESSION["id_usuario"]);		
		$strResult = '1'; 
		}
	else 
		$strResult = '0';
	
	return $strResult;
	}

function autentica_agente($p_CipherKey, $p_IV, $p_cs_cipher, $p_cs_compress, $p_PaddingKey='') 
	{
	/*
	LimpaTESTES();
	GravaTESTES('###########################################');		
	GravaTESTES('Script Chamador:  '.$_SERVER['REQUEST_URI']);		
	GravaTESTES('_SERVER[HTTP_USER_AGENT]:  '.$_SERVER['HTTP_USER_AGENT']);	
	GravaTESTES('strtoupper(DeCrypt(_SERVER[HTTP_USER_AGENT]): '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['HTTP_USER_AGENT'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));
	GravaTESTES('_SERVER[PHP_AUTH_USER]:  '.$_SERVER['PHP_AUTH_USER']);	
	GravaTESTES('strtoupper(DeCrypt(_SERVER[PHP_AUTH_USER]): '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_USER'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));
	GravaTESTES('_SERVER[PHP_AUTH_PW]:  '.$_SERVER['PHP_AUTH_PW']);		
	GravaTESTES('strtoupper(DeCrypt(_SERVER[PHP_AUTH_PW]): '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_PW'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));
	GravaTESTES('p_CipherKey: ' 	. $p_CipherKey);
	GravaTESTES('p_IV: ' 			. $p_IV);	
	GravaTESTES('p_cs_cipher: ' 	. $p_cs_cipher);
	GravaTESTES('p_cs_compress: ' 	. $p_cs_compress);
	GravaTESTES('p_PaddingKey: ' 	. $p_PaddingKey);		
	GravaTESTES('###########################################');			
	*/
	if ((strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['HTTP_USER_AGENT'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'AGENTE_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_USER']  ,$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'USER_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_PW']    ,$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'PW_CACIC'))   
	   	{
        echo 'Acesso não autorizado.';
		exit;
		} 
	}


// --------------------------------------------------------------------------------------
// Função que verifica se um dado computador já esta´cadastrado no BD  
// --------------------------------------------------------------------------------------
function computador_existe($te_node_address, $id_so) 
	{
    conecta_bd_cacic();
	$query = 'SELECT te_node_address, te_nome_computador, te_ip_computador, id_rede, te_workgroup
	          FROM computadores
			  WHERE te_node_address = "'.$te_node_address.'"
			  AND id_so = "'.$id_so.'"';
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);


    if (mysql_num_rows($result) == 0)
		$strResult = '0'; // O computador não existe, deverá ser incluido
	elseif ($row['te_nome_computador'] 	== '' || 
			$row['te_ip_computador'] 	== '' ||
			$row['id_rede'] 			== '' || 
			$row['te_workgroup'] 		== '') 									
		 $strResult = '2';  // O computador existe porém sem uma dessas informações...  - Anderson 16/04/2004 - 21:31h!!!!!!!!!!!!
	else 
		$strResult = '1';  // O computador existe, sem necessidade de atualizações

	return $strResult;
	}

function AutenticaLDAP($pIdServidorAutenticacao, $pNmNomeAcessoAutenticacao, $pTeSenhaAcessoAutenticacao)
	{
	GravaTESTES(CACIC_PATH . 'include/class.ldap.php');
	include_once(CACIC_PATH . 'include/class.ldap.php');
	
	$arrServidores = getValores('servidores_autenticacao',  
								'nm_servidor_autenticacao,
								 nm_servidor_autenticacao_dns,										
								 te_ip_servidor_autenticacao,
								 id_tipo_protocolo,
								 nu_porta_servidor_autenticacao,										
								 nu_versao_protocolo,
								 te_atributo_identificador,
								 te_atributo_retorna_nome,
								 te_atributo_retorna_email, 
								 te_atributo_status_conta, 
								 te_atributo_valor_status_conta_valida'   , 								 								 
								'id_servidor_autenticacao = '.$pIdServidorAutenticacao.' AND in_ativo="S"');

	$arrRetorno = array('nm_nome_completo' 	=> '',
						'te_email' 			=> '');
						
	/* ldap settings */
	$settings['openldap']['servers']   	= $arrServidores['nm_servidor_autenticacao_dns'];
	$settings['openldap']['port']      	= $arrServidores['nu_porta_servidor_autenticacao'];
	$settings['openldap']['username']  	= $pNmNomeAcessoAutenticacao;
	$settings['openldap']['password']  	= $pTeSenhaAcessoAutenticacao;
	$settings['openldap']['tls']       	= '';
	$settings['openldap']['tls-url']   	= '';
	$settings['openldap']['bind-dn']   	= $arrServidores['te_atributo_identificador'] ;
	$settings['openldap']['base-dn']   	= '';
	$settings['openldap']['protocol']  	= $arrServidores['nu_versao_protocolo'];
	$settings['openldap']['referrals'] 	= 0;
	$settings['openldap']['timelimit'] 	= 10;
	$settings['openldap']['timeout']   	= 10;
	
    $openldap 							= openldap::instance($settings['openldap'],$settings['openldap']['username'], $settings['openldap']['password']);
   	$search   							= $openldap->search('', '('. $settings['openldap']['bind-dn'].'='.$settings['openldap']['username'].')');	
	$results  							= $openldap->results($search); 
	$boolAuthenticate 					= $openldap->authenticate('', $results[0]["dn"], $settings['openldap']['password']);

	if ($boolAuthenticate && ($results[0]["dn"]) && ((trim($arrServidores['te_atributo_status_conta'])=='') || (trim($results[0][$arrServidores['te_atributo_status_conta']][0])==trim($arrServidores['te_atributo_valor_status_conta_valida']))))
		{
		$arrRetorno['nm_nome_completo'] = $results[0][strtolower($arrServidores['te_atributo_retorna_nome' ])][0];					
		$arrRetorno['te_email']			= $results[0][strtolower($arrServidores['te_atributo_retorna_email'])][0];								
		}

    unset($openldap);
	
	return $arrRetorno;
	}

/* --------------------------------------------------------------------------------------
 Função usada para recuperar valores de campos únicos. Útil para a tabela de configurações.
 Passou a retornar array com colunas a partir de 22/10/2008
 -------------------------------------------------------------------------------------- */
function getValores($pStrTablesNames, $pStrFieldsNames, $pStrWhere="1") 
	{	
	$arrRetorno = array();	
	
	conecta_bd_cacic();
	
	$querySEL = 'SELECT '.$pStrFieldsNames.' FROM '.$pStrTablesNames.' WHERE '.$pStrWhere . ' LIMIT 1';
    	
	GravaTESTES('**************************************************************');
	GravaTESTES('Library: getValores: pStrTablesNames => '.$pStrTablesNames);
	GravaTESTES('Library: getValores: pStrFieldsNames => '.$pStrFieldsNames);
	GravaTESTES('Library: getValores: pStrWhere => '.$pStrWhere);		
	GravaTESTES('Library: getValores: querySEL => '.$querySEL);			
	GravaTESTES('**************************************************************');
	
	$resultSEL = mysql_query($querySEL);
	if (mysql_num_rows($resultSEL) > 0) 
		{
		mysql_data_seek($resultSEL,0);
		$rowSEL = mysql_fetch_array($resultSEL);		

		for ($i=0;$i < mysql_num_fields($resultSEL);$i++)
			{
			$arrTMP = array(mysql_field_name($resultSEL,$i) => $rowSEL[$i]);
			$arrRetorno = array_merge($arrRetorno,$arrTMP);
			}
		} 
	return $arrRetorno;
	}

// --------------------------------------------------------------------------------------
// Função usada para uma mensagem
// --------------------------------------------------------------------------------------
function mensagem($msg) 
	{
	 $texto = '<table  width="75%" border="0" align="center" cellpadding="15" cellspacing="1" bgcolor="#666666">
					  <tr> 
						<td valign="top" bgcolor="#EEEEEE">
						<p align="center">
						<font color="#333333" size="4" face="Verdana, Arial, Helvetica, sans-serif">
						<strong>
						'. $msg .'
						</strong>
						</font>
						</p>
						</td>
					  </tr>
					</table>';
	return $texto;
	}

// --------------------------------------------------------------------------------------
// Função usada para cortar uma string
// --------------------------------------------------------------------------------------
function capa_string($string, $tamanho_desejado) 
	{
	 // A idéia dessa função é deixar os listbox do formulário com um tamanho fixo.
	 $tamanho_original = strlen($string);
	 if ($tamanho_original > $tamanho_desejado) 
	 	return substr($string,0, $tamanho_desejado - 1) . '...';
	 else 
	 	{ 
		for ($i = $tamanho_original; $i < $tamanho_desejado; $i++) 
			$string = $string . '&nbsp;';
		return $string;
	 	} 
	}

// --------------------------------------------------------------------------------------
// Função usada para fazer uma quebra de linha em uma string
// --------------------------------------------------------------------------------------
function quebra_linha($string, $tamanho_desejado) 
	{
	$tamanho_original = strlen($string);
	for($i = 0; $i < $tamanho_original; $i++) 
		if($cont == $tamanho_desejado) 
			{
			$cont = 0;
			$nova_string = $nova_string . '<br>';
			$i--;
			}
		else 
			{
			$cont++;
			$nova_string = $nova_string . $string[$i];
			}

	return $nova_string;
	}

// --------------------------------------------------------------------------------------
// Função usada para buscar arquivo remoto para atualização no servidor
// --------------------------------------------------------------------------------------
function atualizacao_especial( 	$p_nm_servidor, 
					   			$p_nm_usuario,
					   			$p_te_senha,
					   			$p_nu_porta,
								$p_cs_tipo_ftp,
					   			$p_nm_pasta_origem,
					   			$p_nm_arquivo_origem,							
								$p_nm_arquivo_destino,
								$p_nm_pasta_backup) 
	{
	$resultado = 0;	

	$v_conexao_ftp = conecta_ftp($p_nm_servidor,
								 $p_nm_usuario,
								 $p_te_senha,
								 $p_nu_porta,
								 true);

	if ($v_conexao_ftp)
		{
		// Para habilitar o modo de tratamento de exceções
		
//		ftp_pasv($v_conexao_ftp,true);
		@ftp_chdir($v_conexao_ftp,$p_nm_pasta_origem);									

		$strNmArquivoDestino = $p_nm_arquivo_destino;
		$strNmArquivoOrigem  = $p_nm_arquivo_origem;
		

		$long_cs_tipo_ftp = ($p_cs_tipo_ftp == 'A'?FTP_ASCII:FTP_BINARY);

		if (file_exists(CACIC_PATH . $strNmArquivoDestino)) 
			{
			$dtData = @date("YmdHis");
			$arrArquivoDestino = explode('/',$strNmArquivoDestino);
			$strNmArquivoBackup = CACIC_PATH . $p_nm_pasta_backup . '/' .$arrArquivoDestino[count($arrArquivoDestino)-1].'_'.$dtData;

			copy(CACIC_PATH . $strNmArquivoDestino, $strNmArquivoBackup);
			} 
			
		if (@ftp_get($v_conexao_ftp, CACIC_PATH .$strNmArquivoDestino,$strNmArquivoOrigem, $long_cs_tipo_ftp))
			$resultado = 1;
			
		// Para desabilitar o modo de tratamento de exceções			
		// restore_error_handler();				
		}
			
	// fecha a conexão
	ftp_close($v_conexao_ftp);

	return $resultado;
	}
	
// --------------------------------------------------------------------------------------
// Função usada para calcular diferença entre datas...
// É necessário usar o formato MM-DD-AAAA nessa função
// --------------------------------------------------------------------------------------
if(!function_exists('date_difference')) 
	{
	function date_difference($from, $to) 
		{ 
	  	list($from_month, $from_day, $from_year) = explode("-", $from);
  		list($to_month, $to_day, $to_year) = explode("-", $to);
         
	  	$from_date = mktime(0,0,0,$from_month,$from_day,$from_year);
  		$to_date = mktime(0,0,0,$to_month,$to_day,$to_year);
         
	  	$days = ($to_date - $from_date)/86400;
  
		/* Adicionado o ceil($days) para garantir que o resultado seja sempre um número inteiro */

	  	return ceil($days);
		}  
	}

// --------------------------------------------------------------------------------------
function diferenca_em_horas($p_dt_hr_ult_acesso)
	{
	$date_time=strtotime(date("Y/m/d H:i", strtotime($p_dt_hr_ult_acesso)));	
	$date = getdate(); 
	$date_time_now = $date['year']."-".$date['mon']."-".$date['mday']." ".$date['hours'];
	$date_time_now = strtotime($date_time_now);
	return (($date_time_now - $date_time)/3600); 
	}


// -----------------------------------------------------------------------
// Função para gravação de relacionamento entre redes e perfis_aplicativos
// -----------------------------------------------------------------------
function seta_perfis_rede($pIntIdRede, $strPerfis)
	{
	$arrPerfis = explode('__',$strPerfis);
	if (count($arrPerfis))
		{
		conecta_bd_cacic();
		
		for ($intLoopPerfis = 0; $intLoopPerfis < count($arrPerfis); $intLoopPerfis++)			
			{
			$query	= 'INSERT  
					   INTO 		aplicativos_redes (id_rede,
													   id_aplicativo)
					   values		('.$pIntIdRede.',
									 '.$arrPerfis[$intLoopPerfis].')';
			$result = mysql_query($query);			
			}
		}
	}

function url_encode($text)
	{
   	$text = urlencode($text);
   	if(!strpos($text,"%C3"))
		for($i=129;$i<255;$i++)
	   		{
           	$in = "%".int2hex($i);
           	$out = "%C3%".int2hex($i-64);
           	$text = str_replace($in,$out,$text);
       		}
	return $text;
	}
function url_decode($text)
	{
   	if(!strpos($text,"%C3"))
       for($i=129;$i<255;$i++)
	   		{
           	$in = "%".int2hex($i);
           	$out = "%C3%".int2hex($i-64);
           	$text = str_replace($in,$out,$text);
       		}
	return urldecode($text);
	}	
function int2hex($intega)
	{
   	$Ziffer = "0123456789ABCDEF";
	return $Ziffer[($intega%256)/16].$Ziffer[$intega%16];
	}	
function getOnlyFileName($pStrFullFileName)
	{
	$strResult = str_replace('/' ,'#SLASH#',$pStrFullFileName);
	$strResult = str_replace('\\','#SLASH#',$strResult);	
	$arrResult = explode('#SLASH#',$strResult);
	return $arrResult[count($arrResult)-1];	
	}	
//==================================================
?>
