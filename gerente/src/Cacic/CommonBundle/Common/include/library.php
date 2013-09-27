<?php
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

@session_start();

@define('CACIC',1);
@define('SECURITY',1);

@define('DEBUG',$_POST['cs_debug']);

include_once('config.php');
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
// Fun��o para retorno dos nomes das colunas de hardware pass�vel de controle
// --------------------------------------------------------------------------
function getDescricoesColunasComputadores()
	{
	// Conecto ao banco
	conecta_bd_cacic();	
	
	// Consulto lista de colunas de hardware e retorno em um array
	$queryDescricoesColunasComputadores  = "SELECT 	te_source, 
													te_target,
													te_description
								 			FROM 	descricoes_colunas_computadores dcc";
	$resultDescricoesColunasComputadores = mysql_query($queryDescricoesColunasComputadores) or die('Ocorreu um erro durante a consulta � tabela descricoes_colunas_computadores.');

	// Crio um array que conter� nm_campo => te_descricao_campo.	 
	$arrDescricoesColunasComputadoresAux = array();
	while($rowHardware = mysql_fetch_array($resultDescricoesColunasComputadores)) 	
		$arrDescricoesColunasComputadoresAux[trim($rowHardware['te_source']) . '.' . trim($rowHardware['te_target'])] = $rowHardware['te_description'];

	return $arrDescricoesColunasComputadoresAux;
	}

// --------------------------------------------------------------
// Fun��o para retorno dos nomes de hardware pass�vel de controle
// --------------------------------------------------------------
function getDescricaoHardware()
	{
	// Conecto ao banco
	conecta_bd_cacic();	
	
	// Consulto lista de descri��es de hardware e retorno em um array
	$queryDescricaoHardware  = "SELECT 	nm_class_name,
										nm_property_name,
										te_property_description
							 FROM 		classes_properties cp,
							 			classes c
							 WHERE		cp.id_class = c.id_class";
	$resultDescricaoHardware = mysql_query($queryDescricaoHardware) or die('Ocorreu um erro durante a consulta � tabela classes_properties.');

	// Crio um array que conter� nm_campo => te_descricao_campo.	 
	$arrDescricaoHardwareAux = array();
	while($rowHardware = mysql_fetch_array($resultDescricaoHardware)) 	
		$arrDescricaoHardwareAux[trim($rowHardware['nm_class_name']) . '.' . trim($rowHardware['nm_property_name'])] = $rowHardware['te_property_description'];
	return $arrDescricaoHardwareAux;
	}
	
// --------------------------------------------------------------------------------------
// Fun��o para bloqueio de acesso indevido
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
	    $_SESSION["id_usuario_crypted"] == EnCrypt($_SESSION["id_usuario"],"1","0","0","") &&
	    $boolNivelPermitido)
   		return true;
	$strLocation = CACIC_PATH . 'include/acesso_nao_permitido.php';
	
	//GravaTESTES('AntiSpy: Redirecionando para Nivel Atual="'.CACIC_PATH . 'include/acesso_nao_permitido.php'.'"');	
	
	header ("Location: $strLocation");		
	exit;		
	}
	
// ------------------------------------------------------------------------------------------------
// Fun��o para exibi��o de data do script para fins de Debug. Os IP�s s�o definidos em menu_seg.php
// Novas informa��es poder�o ser acrescentadas futuramente...
// ------------------------------------------------------------------------------------------------
function Debug($p_ScriptFileName)
	{
	// Verifico se o script chamador refere-se a um gerador de imagem e impe�o o Debug neste caso.
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


/*
__________________________________________________________________
Apenas uma alternativa mais completa � fun��o "stripos" do PHP5...
__________________________________________________________________
Retornar� 0 ou 1 se $pos for FALSE
		0 -> Se a String haystack N�O CONTIVER a subString needle
		1 -> Se a String haystack CONTIVER     a subString needle
Retornar� a posi��o da subString needle na string haystack se $boolRetornaPosicao for TRUE ou NULO
*/
function stripos2($strString, $strSubString, $boolRetornaPosicao = true)
	{
	$intPos = strpos($strString, stristr( $strString, $strSubString ));

	if (!$boolRetornaPosicao)
		$intPos = (($intPos < 0 || trim($intPos) == '') ? 0 : 1);

	return $intPos;
	}
//---------------------------------------------------------------------------------
//  Substituir alguns valores inv�lidos ao tr�fego HTTP
//
//  @return String contendo o string com valores inv�lidos substituidos por v�lidos
//---------------------------------------------------------------------------------
function replaceInvalidHTTPChars($pStrString)
	{
    $strNewString = str_replace('+'  , '[[MAIS]]'		, $pStrString);
    $strNewString = str_replace(' '  , '[[ESPACE]]'  	, $strNewString);
    $strNewString = str_replace('"'  , '[[AD]]'      	, $strNewString);
    $strNewString = str_replace("'"  , '[[AS]]'      	, $strNewString);
    $strNewString = str_replace('\\' , '[[BarrInv]]' 	, $strNewString);

	return $strNewString;
	}	
	
//------------------------------------------------------------------------------
//  Repor valores substituidos durante tr�fego HTTP
//
//  @return String contendo o string com valores substituidos
//------------------------------------------------------------------------------
function replacePseudoTagsWithCorrectChars($pStrString)
	{
	// Conven��es Adotadas para as Substitui��es
	// -----------------------------------------
	// [[MAIS]]    => Sinal de Mais   => "+" (comumente interpretado como espa�o, prejudicando a decriptografia) (Deve ser substitu�do ANTES da decriptografia!!!!)
	// [[BarrInv]] => Barra Invertida => "\" (comumente interpretado como ESCAPE na recep��o)
	// [[AS]]      => Aspa Simples    => "'"
	// [[AD]]      => Aspa Dupla      => '"'
	// [[ESPACE]]  => Espa�o          => ' '
	// =============================================================================================
	
    $strNewString = str_replace('[[MAIS]]' 		, '+' 	, $pStrString);
    $strNewString = str_replace('[[ESPACE]]' 	, ' ' 	, $strNewString);	
    $strNewString = str_replace('[[AD]]'     	, '"' 	, $strNewString);
    $strNewString = str_replace('[[AS]]'     	, "'"	, $strNewString);
    $strNewString = str_replace('[[BarrInv]]'	, '.'	, $strNewString); // Ao substituir [[BarrInv]] por "\\" tive problemas, prefer� deixar "."
	return $strNewString;
	}	
// ---------------------------------
// Fun��o usada para descriptografia 
// To decrypt values 
// p_cs_cipher => Y/N 
// ---------------------------------
function DeCrypt($pStrCriptedData, $pStrCsCipher, $pIntCsUnCompress='0', $pStrPaddingKey = '', $pBoolForceDecrypt = false) 
	{
	global $key;
	global $iv;
	
	$pStrCipherKey .= $pStrPaddingKey;
	
	// Bloco de Substitui��es para antes da Decripta��o
	// ------------------------------------------------
	// Raz�o: Dependendo da configura��o do servidor, os valores
	//        enviados, pertinentes � criptografia, tendem a ser interpretados incorretamente.
	// Obs.:  Vide Lista de Conven��es Abaixo
	// =======================================================================================
	$ppStrCriptedData = str_ireplace('[[MAIS]]','+',$pStrCriptedData,$countMAIS);
	// =======================================================================================
	
	if ( (substr($ppStrCriptedData,-11) == '__CRYPTED__') && ((($pStrCsCipher=='1') && !DEBUG) || $pBoolForceDecrypt))
		{
	    $ppStrCriptedData = str_replace('__CRYPTED__' , '' 	, $ppStrCriptedData);		
		$strResult = (trim($ppStrCriptedData)<>''?@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,base64_decode($ppStrCriptedData),MCRYPT_MODE_CBC,$iv):'');
		}
	else
		$strResult = $pStrCriptedData;	

	// Bloco de Substitui��es para depois da Decripta��o
	// -------------------------------------------------
	// Raz�o: �dem acima, por�m, com dados pertinentes aos valores a serem recebidos
	// =============================================================================		
	$strResult = replacePseudoTagsWithCorrectChars($strResult);		
	// =============================================================================

	if ($pIntCsUnCompress == '1')
		$strResult = gzinflate($strResult);		

	//GravaTESTES('Em DeCrypt: p_PaddingKey = "'.$p_PaddingKey.'"');					
	
	// Aqui retiro do resultado a ocorr�ncia do preenchimento, caso exista. (o agente Python faz esse preenchimento)
	if ($pStrPaddingKey <> '') 
		{               
		$char 		= substr($pStrPaddingKey,0,1);
       	$re 		= "/".$char."*$/";
       	$strResult 	= preg_replace($re, "", $strResult);
   		} 

	//GravaTESTES('Em DeCrypt: pStrCriptedData = "'.$pStrCriptedData.'"');				
	//GravaTESTES('Em DeCrypt: strResult = "'.$strResult.'"');				

	return trim($strResult);
	}

// ---------------------------------
// Se a fun��o HASH nativa faltar...
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
// Fun��o usada para criptografia
// To crypt values
// p_cs_cipher => Y/N 
// ------------------------------
function EnCrypt($pStrPlainData, $pStrCsCipher, $pStrCsCompress, $pIntCompressLevel=0, $pStrPaddingKey = '', $pBoolForceEncrypt = false) 
	{
	global $key;
	global $iv;
	
	$pStrCipherKey .= $pStrPaddingKey;
	
	if ((($pStrCsCipher=='1') && !DEBUG) || $pBoolForceEncrypt)
		{
		$strResult = base64_encode(@mcrypt_cbc(MCRYPT_RIJNDAEL_128,$key,$pStrPlainData,MCRYPT_ENCRYPT,$iv));		
		$strResult .= '__CRYPTED__';
		}
	else
		$strResult = $pStrPlainData;	
	
	if (($pStrCsCompress == '1' || $pStrCsCompress == '2') && $pIntCompressLevel > 0)
		$strResult = gzdeflate($strResult,$pIntCompressLevel);
//		$v_result = url_encode(gzcompress($v_result,$p_compress_level));		
	//GravaTESTES('ENCRYPT: '.$p_CipherKey.' / '.$p_IV.' / '.$p_PlainData.' / '.$v_result.' / '.$p_cs_Cipher);		

	$strResult = replaceInvalidHTTPChars($strResult);
	return trim($strResult);		
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
// Em 15/06/2007 optou-se por mostrar data de cria��o do script corrente na tela de esta��es espec�ficas,
// identificadas por seus IP�s na vari�vel de sess�o cIpsDisplayDebugs, declarada e menu_esq.php
//_____________________
function Log_Debug($p_msg)
	{
	$posIPsDisplayDebugs = strpos($GLOBALS["cIPsDisplayDebugs"],$_SERVER['REMOTE_ADDR']); 
	if ($posIPsDisplayDebugs >= 0)
		GravaTESTES($p_msg);
	}
//________________________________________________________________________________________________
// Limpa a tabela TESTES, utilizada para depura��o de c�digo
//________________________________________________________________________________________________
function LimpaTESTES()
	{
	conecta_bd_cacic();
	$queryDEL  = 'DELETE from testes';
	$resultDEL = mysql_query($queryDEL);
	}

//___________________________________
// Grava informa��es na tabela TESTES
//___________________________________
function GravaTESTES($p_Valor)
	{
	$v_Valor 		= str_replace('"','[AD]',$p_Valor);
	$v_Valor 		= str_replace("'",'[AS]',$v_Valor);	
	$date 			= @getdate(); 
	$arrScriptName 	= explode('/',$_SERVER['SCRIPT_NAME']);
	$queryINS  		= "INSERT into testes(te_linha) VALUES ('" . $arrScriptName[count($arrScriptName)-1] . ": (".$date['mday'].'/'.$date['mon'].' - '.$date['hours'].':'.$date['minutes'].")Svr " .$_SERVER['HTTP_HOST']." Sta: ".$_SERVER['REMOTE_ADDR']." - ".$v_Valor . "')";
	$DBConnectionGT	= conecta_bd_cacic();	
	mysql_query($queryINS,$DBConnectionGT);
	}

//________________________________________________________________________________________________
// Grava na tabela SRCACIC_LOGS as informa��es de atividades na esta��o visitada
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
// Grava na tabela LOG informa��es de atividades
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
// Fun��o de conex�o ao BD do CACIC
// --------------------------------------------------------------------------------------
function  conecta_bd_cacic() 
	{
	/*
	echo $GLOBALS["ip_servidor"].'<br>';
	echo $GLOBALS["porta"].'<br>';
	echo $GLOBALS["usuario_bd"].'<br>';
	echo $GLOBALS["senha_usuario_bd"].'<br>';
	echo $GLOBALS["nome_bd"].'<br>';
	*/
	
	$ident_bd = mysql_pconnect($GLOBALS["ip_servidor"] . ':' . $GLOBALS["porta"], 
							   $GLOBALS["usuario_bd"], 
							   $GLOBALS["senha_usuario_bd"]);
	if (mysql_select_db($GLOBALS["nome_bd"], $ident_bd) == 0) 
		die('<b>Problemas durante a conex�o ao BD ou sua sess�o expirou!</b>');
		
	return $ident_bd;		
	}

// ------------------------------------------------------------------------------
// Fun��o para obten��o de dados da subrede de acesso, em fun��o do IP e M�scara.
// Function to retrieve access subnet data, based on IP/Mask address.
// ------------------------------------------------------------------------------
function getDadosRede($pIntIdRede = 0)
	{	
	$intIdRede = ($pIntIdRede ? $pIntIdRede : getIdRede());
	return getArrFromSelect(  'redes r, locais l', 
						'r.id_rede,
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
						 r.te_debugging as te_debugging_subnet,
						 l.te_debugging as te_debugging_local',
						'r.id_rede = ' . $intIdRede . ' AND r.id_local   = l.id_local');	
	}

function getIdRede()
	{
	global $key;
	global $iv;
	global $v_cs_cipher;
	global $v_cs_compress;
	global $strPaddingKey;
	
	// Duas tentativas de obten��o do IP da Esta��o
	$strTeIPComputador = ($_POST['te_ip_computador']  ? $_POST['te_ip_computador'] : $_SERVER['REMOTE_ADDR']);
	$strTeIPComputador = ($strTeIPComputador 		  ? $strTeIPComputador 		   : getenv("REMOTE_ADDR"));	
	$arrRedes = getArrFromSelect('redes','id_rede,te_ip_rede,te_mascara_rede');
	$intIdRede  = 0;	

	// Percorro cada TE_IP + TE_MASCARA_REDE para checar se o IP da esta��o est� na faixa de IPs
	$intLoopRedes = 0;
	while ($intLoopRedes < count($arrRedes))
		{
       	$ip_octets   = split("\.", $strTeIPComputador);
       	$mask_octets = split("\.", $arrRedes[$intLoopRedes]['te_mascara_rede']);
       	unset($bin_sn);      	
		
		for ( $o = 0; $o < count($ip_octets); $o++ )
       		$bin_sn[] = decbin(intval($ip_octets[$o]) & intval($mask_octets[$o]));			

		$apply_mask = join(".", $bin_sn);

       	$ip_octets = split("\.", $apply_mask);
       	unset($bin_sn);
       	for ( $o = 0; $o < count($ip_octets); $o++ )
       		$bin_sn[] = bindec($ip_octets[$o]);

       	$subnet = join(".", $bin_sn);				

		if (trim($subnet)== trim($arrRedes[$intLoopRedes]['te_ip_rede']))
			{
			$intIdRede = $arrRedes[$intLoopRedes]['id_rede'];
			break;
			}		
		$intLoopRedes++;
		}
		
	if ($intIdRede == 0)
		{
		// Neste caso, apela-se para uma rede que tenha configura��es v�lidas...
		$arrQualquerRede = getArrFromSelect('redes','id_rede','trim(nu_porta_serv_updates) <> "" and trim(nm_usuario_login_serv_updates) <> "" and trim(te_senha_login_serv_updates) <> "" LIMIT 1');
		$intIdRede = $arrQualquerRede[0]['id_rede'];
		}	
	return $intIdRede;	
	}
// ------------------------------------------------------------------------------
// Fun��o para obten��o de dados da estacao de trabalho
// Function to retrieve workstation data
// ------------------------------------------------------------------------------
function getDadosComputador($pStrTeNodeAddress, 
							$pStrTeSO,
							$pStrTeUltimoLogin)							
	{			
	//GravaTESTES('pStrTeNodeAddress: '.$pStrTeNodeAddress);
	//GravaTESTES('pStrTeSO: '.$pStrTeSO);
	
	// Obtenho o id_so da base, caso exista
	// Insiro novo S.O. caso n�o exista
	$DBConnectionGDC = conecta_bd_cacic();						
	
	$boolNewSO = false;
	
	$arrSO = getArrFromSelect('so', 'id_so', 'te_so = "' . $pStrTeSO . '"');
	if (!$arrSO[0]['id_so'])
		{	
		conecta_bd_cacic();	
		// Insiro a informa��o na tabela de Sistemas Operacionais incrementando o Identificador Externo
		$queryINS_SO  = 'INSERT 
						 INTO 		so(te_desc_so,sg_so,te_so) 
						 VALUES    ("S.O. a Cadastrar","Sigla a Cadastrar","'.$pStrTeSO.'")';
		//GravaTESTES('Library - queryINS_SO: '.$queryINS_SO);							  						  
		mysql_query($queryINS_SO,$DBConnectionGDC);						
		$boolNewSO = true;
		}
	
	$arrDadosComputador = getArrFromSelect('computadores,so', 'so.id_so,computadores.*', 'computadores.te_node_address = "' . $pStrTeNodeAddress . '" and computadores.id_so = ' . $arrSO[0]['id_so']);		
	$arrDadosRede		= getDadosRede(getIdRede());

	if ($arrDadosComputador[0]['dt_hr_inclusao'] == '')
		{
		$queryINS_Comp = 'INSERT INTO 	computadores 
										(te_node_address, 
										 id_so, 
										 id_rede, 
										 dt_hr_inclusao)
						  VALUES 		("'.$pStrTeNodeAddress.'",
						  				  '.$arrSO[0]['id_so'].',
										  '.$arrDadosRede[0]['id_rede'].',
										 "'. @date("Y-m-d- H:i:s").'")';
		//GravaTESTES('Library - queryINS_Comp: '.$queryINS_Comp);							  						  										   
		mysql_query($queryINS_Comp,$DBConnectionGDC);						
				
		$arrDadosComputador = getArrFromSelect('computadores', '*', 'computadores.te_node_address = "' . $pStrTeNodeAddress . '" and id_so = ' . $arrSO[0]['id_so'] . ' and id_rede = ' . $arrDadosRede[0]['id_rede']);				

		global $strNetworkAdapterConfiguration;
		global $strComputerSystem;		
		global $strOperatingSystem;		
				
		$queryINS = "INSERT INTO computadores_collects(id_computador,nm_class_name,te_class_values) VALUES 
					(" . $arrDadosComputador[0]['id_computador'] . ",'NetworkAdapterConfiguration','"   . $strNetworkAdapterConfiguration ."'),
					(" . $arrDadosComputador[0]['id_computador'] . ",'ComputerSystem','" 				. $strComputerSystem ."'),				
					(" . $arrDadosComputador[0]['id_computador'] . ",'OperatingSystem','" 				. $strOperatingSystem ."')";									
		mysql_query($queryINS,$DBConnectionGDC);		
		}
		
	/* Atualizo a data/hora de �ltimo acesso e vers�es dos agentes principais */
	$query = 'UPDATE 	computadores SET 
						dt_hr_ult_acesso  = "'	. @date("Y-m-d- H:i:s")				. '",
			  	  		te_versao_cacic   = "' 	. $_POST['te_versao_cacic']  		. '",
			  	  		te_versao_gercols = "' 	. $_POST['te_versao_gercols']  		. '",						
				  		te_ultimo_login	  = "' 	. $pStrTeUltimoLogin				. '"  
			  WHERE 	id_computador = '		. $arrDadosComputador[0]['id_computador'];
	mysql_query($query,$DBConnectionGDC);

	$arrDadosComputador = getArrFromSelect('computadores', 'computadores.*', 'computadores.id_computador = ' . $arrDadosComputador[0]['id_computador']);		

	if ($boolNewSO)
		{
		// Verifico pelo local se h� coletas configuradas e acrescento o S.O. � tabela de a��es
		$arrAcoesSO  = getArrFromSelect('acoes_so','id_acao','id_local = '.$arrDadosRede[0]['id_local'].' GROUP BY id_acao');							  						
			
		// Caso existam a��es configuradas para o local, incluo o S.O. para que tamb�m execute-as...
		$strInsereID 	   = '';
		$intLoopArrAcoesSO = 0;
		while ($intLoopArrAcoesSO < count($arrAcoesSO))
			{
			$strInsereID .= ($strInsereID <> ''?',':'');
			$strInsereID .= '('.$arrDadosRede[0]['id_local'].','.$arrAcoesSO[$intLoopArrAcoesSO]['id_acao'].','.$arrSO[0]['id_so'].')';
			$intLoopArrAcoesSO ++;
			}
										
		if ($strInsereID <> '')
			{
			$queryINS = 'INSERT INTO acoes_so(id_local, id_acao, id_so) 
						 VALUES '.$strInsereID;
			//GravaTESTES('Library - queryINS 1: '.$queryINS);							  						  							 
			$resultINS = mysql_query($queryINS,$DBConnectionGDC);
			}			
		}			

	return $arrDadosComputador;
	}

// --------------------------------------------------------------------------------------
// Para Atualiza��o das colunas dt_hr_alteracao_patrim_uonx da table configuracoes
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

function autentica_agente($p_PaddingKey='') 
	{
	if ((strtoupper(DeCrypt($_POST['HTTP_USER_AGENT'],$_POST['cs_cipher'], $_POST['cs_compress'],$pStrPaddingKey,true)) != 'AGENTE_CACIC') ||
	    (strtoupper(DeCrypt($_POST['PHP_AUTH_USER'  ],$_POST['cs_cipher'], $_POST['cs_compress'],$pStrPaddingKey,true)) != 'USER_CACIC') ||
	    (strtoupper(DeCrypt($_POST['PHP_AUTH_PW'    ],$_POST['cs_cipher'], $_POST['cs_compress'],$pStrPaddingKey,true)) != 'PW_CACIC'))   
	   	{
        echo 'Acesso n�o autorizado.';
		exit;
		} 
	}


// --------------------------------------------------------------------------------------
// Fun��o que verifica se um dado computador j� esta�cadastrado no BD  
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
		$strResult = '0'; // O computador n�o existe, dever� ser incluido
	elseif ($row['te_nome_computador'] 	== '' || 
			$row['te_ip_computador'] 	== '' ||
			$row['id_rede'] 			== '' || 
			$row['te_workgroup'] 		== '') 									
		 $strResult = '2';  // O computador existe por�m sem uma dessas informa��es...  - Anderson 16/04/2004 - 21:31h!!!!!!!!!!!!
	else 
		$strResult = '1';  // O computador existe, sem necessidade de atualiza��es

	return $strResult;
	}

function AutenticaLDAP($pIdServidorAutenticacao, $pNmNomeAcessoAutenticacao, $pTeSenhaAcessoAutenticacao)
	{
	include_once(CACIC_PATH . 'include/class.ldap.php');
	
	$arrServidores = getArrFromSelect('servidores_autenticacao',  
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
	$settings['openldap']['servers']   	= $arrServidores[0]['nm_servidor_autenticacao_dns'];
	$settings['openldap']['port']      	= $arrServidores[0]['nu_porta_servidor_autenticacao'];
	$settings['openldap']['username']  	= $pNmNomeAcessoAutenticacao;
	$settings['openldap']['password']  	= $pTeSenhaAcessoAutenticacao;
	$settings['openldap']['tls']       	= '';
	$settings['openldap']['tls-url']   	= '';
	$settings['openldap']['bind-dn']   	= $arrServidores[0]['te_atributo_identificador'] ;
	$settings['openldap']['base-dn']   	= '';
	$settings['openldap']['protocol']  	= $arrServidores[0]['nu_versao_protocolo'];
	$settings['openldap']['referrals'] 	= 0;
	$settings['openldap']['timelimit'] 	= 10;
	$settings['openldap']['timeout']   	= 10;
	
    $openldap 							= openldap::instance($settings['openldap'],$settings['openldap']['username'], $settings['openldap']['password']);
   	$search   							= $openldap->search('', '('. $settings['openldap']['bind-dn'].'='.$settings['openldap']['username'].')');	
	$results  							= $openldap->results($search); 
	$boolAuthenticate 					= $openldap->authenticate('', $results[0]["dn"], $settings['openldap']['password']);

	if ($boolAuthenticate && ($results[0]["dn"]) && ((trim($arrServidores[0]['te_atributo_status_conta'])=='') || (trim($results[0][$arrServidores[0]['te_atributo_status_conta']][0])==trim($arrServidores[0]['te_atributo_valor_status_conta_valida']))))
		{
		$arrRetorno['nm_nome_completo'] = $results[0][strtolower($arrServidores[0]['te_atributo_retorna_nome' ])][0];					
		$arrRetorno['te_email']			= $results[0][strtolower($arrServidores[0]['te_atributo_retorna_email'])][0];								
		}

    unset($openldap);
	
	return $arrRetorno;
	}

/* ---------------------------------------------------------------------------------------------------------------------------------------------------
 Fun��o usada para retornar um array bidimensional contendo indices num�ricos e nomes dos campos recuperados na consulta - Anderson PETERLE - Jan/2013
 ----------------------------------------------------------------------------------------------------------------------------------------------------- */
function getArrFromSelect($pStrTablesNames, $pStrFieldsNames, $pStrWhereAndOthers="1") 
	{	
	$queryToSEL = 'SELECT '.$pStrFieldsNames.' FROM '.$pStrTablesNames.' WHERE '.$pStrWhereAndOthers;

if (stripos2($queryToSEL, 'uo2_id',false))
	GravaTESTES($queryToSEL);
					
	$arrRetorno = array();	
	
	$DBConnectionGV = conecta_bd_cacic(); // Pego uma conexao do tipo persistente => mysql_pconnect
	
	$resultSEL = mysql_query($queryToSEL,$DBConnectionGV);
	
	if ($resultSEL) 
		{
		mysql_data_seek($resultSEL,0);
		$rowSEL = mysql_fetch_array($resultSEL);		

		$strColumnsNames = '';
		for ($intLoopRows = 0;$intLoopRows < mysql_num_fields($resultSEL);$intLoopRows++)
			{
			$strColumnsNames .= (!$strColumnsNames ? '' : ',');
			$strColumnsNames .= mysql_field_name($resultSEL,$intLoopRows);
			}

		$arrColumnsNames = explode(',',$strColumnsNames);
		$intRowSequence  = 0;
		mysql_data_seek($resultSEL,0);

		while ($rowSEL = mysql_fetch_array($resultSEL))
			{		
			for ($intLoopCols = 0;$intLoopCols < count($arrColumnsNames);$intLoopCols++)
				$arrRetorno[$intRowSequence][$arrColumnsNames[$intLoopCols]] = $rowSEL[$arrColumnsNames[$intLoopCols]];

			$intRowSequence ++;
			}
		} 

	return $arrRetorno;
	}
	
/* 
--------------------------------------------------------------------------------------------------
Procedimento usado para preparar dois arrays com informa��es sobre Coletas, Classes e Propriedades
--------------------------------------------------------------------------------------------------*/   
function getClassesDefinitions($pStrCollectType)
	{
	global $arrClassesNames;
	global $arrCollectsDefClasses;
	
	$arrDadosDefClasses = getArrFromSelect('acoes, 
									  classes,
									  classes_properties,
									  collects_def_classes',
									 'acoes.te_descricao_breve,
									  classes.nm_class_name,						
									  classes.te_class_description,														
									  classes_properties.nm_property_name,
									  classes_properties.id_property,									  
									  classes_properties.te_property_description,									  									  
									  classes_properties.nm_function_pre_db',									  									  
									 'acoes.id_acao = "' . $pStrCollectType . '" 					AND  
									  collects_def_classes.id_acao 	= acoes.id_acao 				AND 
									  classes.id_class			 	= collects_def_classes.id_class AND 
									  classes_properties.id_class	= classes.id_class
									  ORDER BY 	acoes.te_descricao_breve,
									  			classes.te_class_description,
												classes_properties.te_property_description');

	for ($intLoopArrDadosDefClasses = 0; $intLoopArrDadosDefClasses < count($arrDadosDefClasses); $intLoopArrDadosDefClasses ++) 
		{
		$arrClassesNames[$arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_class_name']] = $arrDadosDefClasses[$intLoopArrDadosDefClasses]['te_class_description'];			
		$arrCollectsDefClasses[$pStrCollectType]=($arrCollectsDefClasses[$pStrCollectType] == '' ? $arrDadosDefClasses[$intLoopArrDadosDefClasses]['te_descricao_breve'] : $arrCollectsDefClasses[$pStrCollectType]);	
		$arrCollectsDefClasses[$pStrCollectType . '.' . $arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_class_name'] . '.' . $arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_property_name']] = $arrDadosDefClasses[$intLoopArrDadosDefClasses]['id_property'];
		$arrCollectsDefClasses[$pStrCollectType . '.' . $arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_class_name'] . '.' . $arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_property_name'] . '.nm_function_pre_db'] = $arrDadosDefClasses[$intLoopArrDadosDefClasses]['nm_function_pre_db'];		
		}				
	}
	
/* --------------------------------------------------------------------------------------
 Fun��o usada para recuperar valores da tabela computadores_collects
 -------------------------------------------------------------------------------------- */
function getComponentValue($pIntIdComputador, $pStrClassName, $pStrPropertyName) 
	{	
	$arrComponentValue = getArrFromSelect('computadores_collects','te_class_values','id_computador = ' . $pIntIdComputador . ' AND nm_class_name = "' . $pStrClassName . '"');
	return str_replace('[[COMMA]]',',', getValueFromTags($pStrPropertyName, $arrComponentValue[0]['te_class_values'])); 
	}

// --------------------------------------------------------------------------------------
// Fun��o usada para uma mensagem
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
// Fun��o usada para cortar uma string
// --------------------------------------------------------------------------------------
function capa_string($string, $tamanho_desejado) 
	{
	 // A id�ia dessa fun��o � deixar os listbox do formul�rio com um tamanho fixo.
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
// Fun��o usada para fazer uma quebra de linha em uma string
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
// Fun��o usada para buscar arquivo remoto para atualiza��o no servidor
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
		// Para habilitar o modo de tratamento de exce��es
		
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
			
		// Para desabilitar o modo de tratamento de exce��es			
		// restore_error_handler();				
		}
			
	// fecha a conex�o
	ftp_close($v_conexao_ftp);

	return $resultado;
	}


// Fun��o para recuperar valores delimitados por tags definidas em  $pStrTags
function getValueFromTags($pStrTagLabel, $pStrSource, $pStrTags = '[]')
	{
	//Tratar as tags depois!
	preg_match_all("(\[" . $pStrTagLabel . "\](.+)\[\/" . $pStrTagLabel . "\])i",$pStrSource, $arrResult);    
	return $arrResult[1][0];
	}

// Fun��o para recuperar array com nomes das tags delimitadas por "<" e ">"
function getTagsFromValues($pStrSource, $pStrTags = '[]')
	{
	preg_match_all("/\[\/(.*?)\]/",$pStrSource,$arrResult);
	return $arrResult[1];
	}

// Fun��o para excluir uma tag
function delTags($pStrTagLabel, $pStrSource, $pStrTags = '[]')
	{
	$strBeginTag = substr($pStrTags,0,1) 		. $pStrTagLabel . substr($pStrTags,1,1);
	$strEndTag   = substr($pStrTags,0,1) . '/' 	. $pStrTagLabel . substr($pStrTags,1,1);	
	$strSource	 = $pStrSource;

	$strSource = str_replace($strBeginTag . $strEndTag,'',$strSource);	
	
	while ($strActualValue = getValueFromTags($pStrTagLabel,$strSource))
		$strSource = str_replace($strBeginTag . $strActualValue . $strEndTag,'',$strSource);
		
	return $strSource;
	}

// Função para atribuir valor a tags
function setValueToTags($pStrTagLabel, $pStrValue, $pStrSource, $pStrTags = '[]')
	{
        $strBeginTag = substr($pStrTags,0,1) 		. $pStrTagLabel . substr($pStrTags,1,1);
        $strEndTag   = substr($pStrTags,0,1) . '/' 	. $pStrTagLabel . substr($pStrTags,1,1);
        $strSource	 = $pStrSource;

        $strActualValue = getValueFromTags($pStrTagLabel,$pStrSource);
        if (stripos2($strSource,$strBeginTag,false))
            $strSource = str_replace($strBeginTag . $strActualValue . $strEndTag,$strBeginTag . $pStrValue . $strEndTag,$pStrSource);
        else
            $strSource .= $strBeginTag . $pStrValue . $strEndTag;

        return $strSource;
	}
	
// --------------------------------------------------------------------------------------
// Fun��o usada para calcular diferen�a entre datas...
// � necess�rio usar o formato MM-DD-AAAA nessa fun��o
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
  
		/* Adicionado o ceil($days) para garantir que o resultado seja sempre um n�mero inteiro */

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
// Fun��o para grava��o de relacionamento entre redes e perfis_aplicativos
// -----------------------------------------------------------------------
function seta_perfis_rede($pIntIdRede, $strPerfis)
	{
	$arrPerfis = explode('__',$strPerfis);
	if (count($arrPerfis))
		{
		$DBConnectionSPR = conecta_bd_cacic();
		
		for ($intLoopPerfis = 0; $intLoopPerfis < count($arrPerfis); $intLoopPerfis++)			
			{
			$query	= 'INSERT  
					   INTO 		aplicativos_redes (id_rede,
													   id_aplicativo)
					   values		('.$pIntIdRede.',
									 '.$arrPerfis[$intLoopPerfis].')';
			mysql_query($query,$DBConnectionSPR);			
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

function getVarType($pVar) 
	{ 
    if(is_object($var)) 
        return get_class($var); 
    if(is_null($var)) 
        return 'null'; 
    if(is_string($var)) 
        return 'string'; 
    if(is_array($var)) 
        return 'array'; 
    if(is_int($var)) 
        return 'integer'; 
    if(is_bool($var)) 
        return 'boolean'; 
    if(is_float($var)) 
        return 'float'; 
    if(is_resource($var)) 
        return 'resource'; 
    //throw new NotImplementedException(); 
    return 'unknown'; 
	} 

// ********************************************************************************************************************
// As fun��es abaixo s�o definidas para uso por chamadas via call_user_func_array conforme abaixo:
// Campo "classes_properties.nm_function_pre_db" => tratamento do dado antes de ser persistido
// Campo "classes_properties.nm_function_pos_db" => tratamento do dado ap�s ser persistido, normalmente ao ser mostrado
// ********************************************************************************************************************	

//---------------------------------------------------------------------------------------------------------------------
// Busca no banco o valor correspondente ao identificador fornecido como elemento de pArrParameters
// Elementos aguardados => id_property e te_property_value 
//---------------------------------------------------------------------------------------------------------------------
function getTypeOf($pArrParameters)
	{	
	$strResult 			= '';	
	$strTePropertyValue = ($pArrParameters['te_property_value'] <> '' ? $pArrParameters['te_property_value'] : '00');
	$arrPropertyData 	= getArrFromSelect('classes_properties_types','te_type_description','id_property=' . $pArrParameters['id_property'] . ' AND cs_type = "' . $strTePropertyValue. '"');
	$strResult 			= $arrPropertyData[0]['te_type_description'];
	return $strResult;			
	}	

//------------------------------------------------------------
// Formata um n�mero conforme par�metros de m�scara fornecidos
// Elementos aguardados:
// te_property_value 	-> O valor a ser formatado
// te_parameter_1 		-> O n�mero de casas decimais
// te_parameter_2 		-> O caractere separador de decimais
// te_parameter_3 		-> O caractere separador de milhares
//------------------------------------------------------------
function getNumberFormat($pArrParameters)
	{
    return @number_format($pArrParameters['te_property_value'], $pArrParameters['te_parameter_1'], $pArrParameters['te_parameter_2'], $pArrParameters['te_parameter_3']);
	}	
	
function getValueFromFunction($pIntIdProperty,$pStrOldValueToShow,$pStrNmFunction)
	{
	$strNmFunctionDB = $pStrNmFunction;
	if (stripos2($pStrNmFunction,'(',false))
		$strNmFunctionDB = substr($strNmFunctionDB,0,strpos($strNmFunctionDB,'('));
					
	// Inicializo 5 vari�veis string para conterem os poss�veis 5 par�metros
	// Caso o n�mero de par�metros trabalhado pela fun��o seja maior, deve-se ajustar o bloco abaixo.
	$strTeParameter1 = '';
	$strTeParameter2 = '';
	$strTeParameter3 = '';										
	$strTeParameter4 = '';										
	$strTeParameter5 = '';																				
	if (stripos2($pStrNmFunction,'(',false))
		{
		$strTeParametersTMP = substr($pStrNmFunction,strpos($pStrNmFunction,'('));
		$strTeParametersTMP = str_replace(')','',str_replace('(','',$strTeParametersTMP));
		$arrParametersTMP 	= explode(',',$strTeParametersTMP);

		for ($intLoopArrParametersTMP = 0; $intLoopArrParametersTMP < count($arrParametersTMP); $intLoopArrParametersTMP++)
			{
			$strVarName  = 'strTeParameter' . ($intLoopArrParametersTMP + 1);
			$$strVarName = $arrParametersTMP[$intLoopArrParametersTMP];
			}
		}
		
	$arrParameters[] = array('id_property'			=>	$pIntIdProperty,
						   	 'te_property_value'	=>  $pStrOldValueToShow,
						   	 'te_parameter_1'		=>	$strTeParameter1,
						   	 'te_parameter_2'		=>	$strTeParameter2,
						   	 'te_parameter_3'		=>	$strTeParameter3,
						   	 'te_parameter_4'		=> 	$strTeParameter4,
						   	 'te_parameter_5'		=>	$strTeParameter5);
	
	$strResult = call_user_func_array($strNmFunctionDB,$arrParameters);
	return $strResult;	
	}	
//==================================================
?>
