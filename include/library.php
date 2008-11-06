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

@session_start();
@define('CACIC',1);

@include_once('config.php');
require_once('define.php');

if(!include_once( TRANSLATOR_PATH.'/Translator.php'))
  die ("<h1>There is a trouble with phpTranslator package. It isn't found.</h1>");

/*
 * componente (objeto) para realizar traducao
 */
$oTranslator = new Translator( CACIC_LANGUAGE, CACIC_PATH.CACIC_LANGUAGE_PATH, CACIC_LANGUAGE_STANDARD );
$oTranslator->setURLPath(TRANSLATOR_PATH_URL);
$oTranslator->setLangFilesInSubDirs(true);
$oTranslator->initStdLanguages();

//Debug($_SERVER['SCRIPT_FILENAME']);

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
	$mainFolder = GetMainFolder();

	if ($strNiveisPermitidos <> '')
		$boolNivelPermitido = stripos2(','.$strNiveisPermitidos.',',','.$_SESSION['cs_nivel_administracao'].',',false);
	else
		$boolNivelPermitido = true;
		
	$url_aplicacao = ''; // do arquivo de configuracao
	include $mainFolder.'/include/config.php'; // Incluo o config.php para pegar as chaves de criptografia	

	if (session_is_registered("id_usuario") && session_is_registered("id_usuario_crypted") && 
	    $_SESSION["id_usuario_crypted"] == EnCrypt($key,$iv,$_SESSION["id_usuario"],"1","0","0","") &&
	    $boolNivelPermitido)
   		return true;
	$strLocation = $url_aplicacao.'/include/acesso_nao_permitido.php';

	header ("Location: $strLocation");		
	exit;		
	}

// ------------------------------------------------------------------------
// Função para obtenção do ENDEREÇO MAC da estação chamadora
// Function to get the MAC ADDRESS of the caller station
// ATENÇÃO: Sem funcionalidade caso o FireWall esteja habilitado na estação
// ------------------------------------------------------------------------
function returnMacAddress() 
	{
	// This code is under the GNU Public Licence
	// Written by michael_stankiewicz {don't spam} at yahoo {no spam} dot com
	// Tested only on linux, please report bugs
	
	// Adaptado às necessidades do Sistema CACIC - por Anderson Peterle
	// Adapted to Sistema CACIC requirements - by Anderson Peterle

	// WARNING: the commands 'which' and 'arp' should be executable
	// by the apache user; on most linux boxes the default configuration
	// should work fine

	// Get the arp executable path
	$location = exec('which arp', $arpTable);

	// Split the output so every line is an entry of the $arpSplitted array
	//$arpSplitted = split("\n",$arpTable);

	// Get the remote ip address (the ip address of the client, the browser)
	$remoteIp = $GLOBALS['REMOTE_ADDR'];

	// Cicle the array to find the match with the remote ip address
	foreach ($arpTable as $value) 
		{		
		// Split every arp line, this is done in case the format of the arp
		// command output is a bit different than expected
		$valueSplitted = split(" ",$value);
		foreach ($valueSplitted as $spLine) 
			{
			if (preg_match("/$remoteIp/",$spLine)) 
				{
				$ipFound = true;
				}
			// The ip address has been found, now rescan all the string
			// to get the mac address
			if ($ipFound) 
				{
				// Rescan all the string, in case the mac address, in the string
				// returned by arp, comes before the ip address
				// (you know, Murphy's laws)
				reset($valueSplitted);
				foreach ($valueSplitted as $spLine) 
					{
					if (preg_match("/[0-9a-f][0-9a-f][:-]".
									"[0-9a-f][0-9a-f][:-]".
									"[0-9a-f][0-9a-f][:-]".
									"[0-9a-f][0-9a-f][:-]".	
									"[0-9a-f][0-9a-f][:-]".
									"[0-9a-f][0-9a-f]/i",$spLine)) 
						{
						return $spLine;
						}
					}
				}
			$ipFound = false;
			}
		}
	return '';
	}

// ------------------------------------------------------------------------
// Função para obtenção do ENDEREÇO MAC da estação chamadora
// Function to get the MAC ADDRESS of the caller station
// ATENÇÃO: Sem funcionalidade caso o FireWall esteja habilitado na estação
// ------------------------------------------------------------------------
function returnMacAddress1()
	{
	//First get the IP address then use the
	//DOS command + only get row with client IP address
	//This takes only one line of the ARP table instead
	//of what could be a very large table of data to
	//hopefull give a small speed/performance advantage

	$remoteIp = rtrim($_SERVER['REMOTE_ADDR']);
	$location = rtrim(`arp -a $remoteIp`);
	//for ($i=0;$i < count($location);$i++)
	//	GravaTESTES('location['.$i.']:'.$location[$i]);	
	//print_r($remoteIp.$location);//display

	//reduce no of white spaces then
	//Split up into array element by white space
	$location = preg_replace('/\s+/', 's', $location);
	$location = split('\s',$location);//

	$num=count($location);//get num of array elements
	$loop=0;//start at array element 0
	while ($loop < $num)
		{
		//mac address is always one after the
		//IP after inserting the firstline
		//(preg_replace) line above.
		if ($location[$loop] == $remoteIp)
			return $location[$loop];
		else 
			$loop ++;
		}
	return '';
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
function getMenu($_menu_name) {
       $_file_lang = 'language'.DIRECTORY_SEPARATOR.CACIC_LANGUAGE.DIRECTORY_SEPARATOR.$_menu_name;
       if(is_file($_file_lang) and is_readable($_file_lang)) {
               return $_file_lang;
       }
       else {
               $_file_lang = 'language'.DIRECTORY_SEPARATOR.CACIC_LANGUAGE_STANDARD.DIRECTORY_SEPARATOR.$_menu_name;
               if(is_file($_file_lang) and is_readable($_file_lang)) {
                       return $_file_lang;
               }
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
	
	/*GravaTESTES('Em DeCrypt: p_CipherKey   = "'.$p_CipherKey.'"');
	  GravaTESTES('Em DeCrypt: p_IV          = "'.$p_IV.'"');	
	  GravaTESTES('Em DeCrypt: p_CriptedData = "'.$p_CriptedData.'"');		
	  GravaTESTES('Em DeCrypt: p_cs_Cipher   = "'.$p_cs_Cipher.'"');	
	*/
	// Bloco de Substituições para antes da Decriptação
	// ------------------------------------------------
	// Razão: Dependendo da configuração do servidor, os valores
	//        enviados, pertinentes à criptografia, tendem a ser interpretados incorretamente.
	// Obs.:  Vide Lista de Convenções Abaixo
	// =======================================================================================
	$_p_CriptedData = str_ireplace('<MAIS>','+',$p_CriptedData,$countMAIS);
	// =======================================================================================
	
	if ($p_cs_Cipher=='1') 
		$v_result = (trim($_p_CriptedData)<>''?@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$p_CipherKey,base64_decode($_p_CriptedData),MCRYPT_MODE_CBC,$p_IV):'');
	else
		$v_result = $_p_CriptedData;	

	// Bloco de Substituições para depois da Decriptação
	// -------------------------------------------------
	// Razão: Ídem acima, porém, com dados pertinentes aos valores a serem recebidos
	// =============================================================================		
	$v_result = str_ireplace('<AD>'     ,'"'   ,$v_result,$countAD);		
	$v_result = str_ireplace('<AS>'     ,"'"   ,$v_result,$countAS);				
	$v_result = str_ireplace('<BarrInv>','\\\\',$v_result,$countINV);			
	$v_result = str_ireplace('<ESPACE>' ,' '   ,$v_result,$countESPACE);						
	// =============================================================================

	// Convenções Adotadas para as Substituições
	// -----------------------------------------
	// <MAIS>    => Sinal de Mais   => "+" (comumente interpretado como espaço, prejudicando a decriptografia)
	// <BarrInv> => Barra Invertida => "\" (comumente interpretado como ESCAPE na recepçâo)
	// <AS>      => Aspa Simples    => "'"
	// <AD>      => Aspa Dupla      => '"'
	// <ESPACE>  => Espaço          => ' '
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
	//GravaTESTES('Em DeCrypt: v_result = "'.$v_result.'"');				
	
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

// --------------------------------------------------------------------------------------
// Função de conexão a um servidor de FTP
// --------------------------------------------------------------------------------------
function conecta_ftp($p_te_serv, $p_user_name, $p_user_pass, $p_port, $p_passive = false) 
	{

	//Conecta ao servidor FTP
	//ATENÇÃO à configuração "MaxClientsPerHost", que deve estar, no mínimo com 2
	$con = ftp_connect("$p_te_serv",$p_port);

	if ($p_passive)
		{
		// Seta FTP Passivo
		ftp_pasv($con,true);
		}
	
	//Faz o login no servidor FTP
	$result = ftp_login($con, "$p_user_name", "$p_user_pass");

	return ($result?$con:'0');
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
function Abrevia($Nome)
	{
	$Array_Nome = explode(" ", $Nome);
	$v_return = '';
	if (count($Array_Nome)>1)
		for ($i=0; $i<count($Array_Nome); $i++)
			$v_return .= substr($Array_Nome[$i], 0, 1).'.';
	else
		$v_return = substr($Array_Nome[0],0,3).'.';
		
  	return $v_return;	
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
	$v_Valor = str_replace('"','<AD>',$p_Valor);
	$v_Valor = str_replace("'",'<AS>',$v_Valor);	
	conecta_bd_cacic();
	$date = @getdate(); 
	$queryINS  = "INSERT into testes(te_linha) VALUES ( '(".$date['mday'].'/'.$date['mon'].'/'.$date['year'].' - '.$date['hours'].':'.$date['minutes'].':'.$date['seconds'].")Server " .$_SERVER['HTTP_HOST']." Station: ".$_SERVER['REMOTE_ADDR']." - ".$v_Valor . "')";
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
function GravaLog($p_cs_acao, $p_nm_script, $p_nm_tabela)
	{
	$arr_nm_script = explode('/',$p_nm_script);	
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
				  			'$p_cs_acao',
							'".$arr_nm_script[count($arr_nm_script)-1]."',
							'$p_nm_tabela',
							'".$_SESSION['id_usuario']."',
							'".$_SERVER['REMOTE_ADDR']."')";
	$resultINS = mysql_query($queryINS);
	}
	

//________________________________________________________________________________________________
// Retorna 1 ou 0 para decremento de PRIMEIRO e ULTIMO nome
//________________________________________________________________________________________________
function FatorDecremento($Numero)
	{
	if ($Numero>1)
		{
		return 1;
		}
	else
		{
		return 0;
		}
	}
// _______________________________________________________________	

// --------------------------------------------------------------------------------------
// Função de conexão ao BD do CACIC
// --------------------------------------------------------------------------------------
function conecta_bd_cacic() 
{

	$ident_bd = mysql_connect($GLOBALS["ip_servidor"] . ':' . $GLOBALS["porta"], 
							  $GLOBALS["usuario_bd"], 
							  $GLOBALS["senha_usuario_bd"]);
	if (mysql_select_db($GLOBALS["nome_bd"], $ident_bd) == 0) 
		{ 
		die('<b>Problemas durante a conexão ao BD ou sua sessão expirou!</b>');

		}
	return $ident_bd;
}

// ------------------------------------------------------------------------------
// Função para obtenção de dados da subrede de acesso, em função do IP e Máscara.
// Function to retrieve access subnet data, based on IP/Mask address.
// ------------------------------------------------------------------------------
function GetDadosRede($strTeIP = '')
	{
	conecta_bd_cacic();	

	$query_redes  	= "SELECT 	id_ip_rede,
								te_mascara_rede
					   FROM		redes";
	$result_redes 	= mysql_query($query_redes);

	$v_id_ip_rede 	= '';
	$v_te_ip 	  	= ($strTeIP <> ''?$strTeIP:$_SERVER["REMOTE_ADDR"]);

	// Percorro cada ID_IP_REDE + TE_MASCARA_REDE para checar se o IP da estação está na faixa de IPs
	while ($v_dados_redes = mysql_fetch_array($result_redes))
		{
       	$ip_octets = split("\.", $v_te_ip);
       	$mask_octets = split("\.", $v_dados_redes['te_mascara_rede']);
       	unset($bin_sn);      	
		for ( $o = 0; $o < count($ip_octets); $o++ )
			{
       		$bin_sn[] = decbin(intval($ip_octets[$o]) & intval($mask_octets[$o]));			
       		}
		$apply_mask = join(".", $bin_sn);

       	$ip_octets = split("\.", $apply_mask);
       	unset($bin_sn);
       	for ( $o = 0; $o < count($ip_octets); $o++ )
			{
           		$bin_sn[] = bindec($ip_octets[$o]);
       		}
       	$subnet = join(".", $bin_sn);				
		if (trim($subnet)== trim($v_dados_redes['id_ip_rede']))
			{
			$v_id_ip_rede = trim($v_dados_redes['id_ip_rede']); 
			break;
			}		
		}
	// Obs.: as colunas sg_local e nm_local são requeridas por menu_esq.php		
	//       the columns sg_local and nm_local have been requested by menu_esq.php			
	$query_ver = '	SELECT 	nm_rede,
							te_serv_cacic,
							te_serv_updates,
							nu_limite_ftp,
							nu_porta_serv_updates, 
							te_path_serv_updates, 							
							nm_usuario_login_serv_updates,
							te_senha_login_serv_updates,
							id_ip_rede,
							redes.id_local,
							sg_local,
							nm_local							
					FROM	redes,
							locais
					WHERE 	redes.id_ip_rede = "'.$v_id_ip_rede.'" AND
							redes.id_local = locais.id_local';
	$result_ver = mysql_query($query_ver);

	if (!$v_dados = @mysql_fetch_array($result_ver))
		{		

		// Neste caso, apela-se para uma rede que tenha configurações válidas...
		$query_ver = '	SELECT 	redes.id_ip_rede,	
								redes.te_serv_updates,
								redes.nu_limite_ftp,
								redes.nu_porta_serv_updates, 
								redes.te_path_serv_updates, 
								redes.nm_usuario_login_serv_updates,
								redes.te_senha_login_serv_updates,
								redes.te_serv_cacic,
								redes.id_local,
								"Alternative"
						FROM	redes,
								configuracoes_locais conf
						WHERE 	conf.te_serv_updates_padrao = redes.te_serv_updates and
								trim(redes.nu_porta_serv_updates) <> "" and
								trim(redes.nm_usuario_login_serv_updates) <> "" and
								trim(redes.te_senha_login_serv_updates) <> ""
						LIMIT 1';
		$result_ver = mysql_query($query_ver);
		$v_dados	= mysql_fetch_array($result_ver);
		}	
	return $v_dados;
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
		GravaLog('UPD',$_SERVER['SCRIPT_NAME'],'configuracoes_locais');		
		return '1'; 
		}
	else return '0';
	}




function conecta_bd_cacic_web() 
	{
    if (conecta_bd_cacic() == '0') 
		{
 	    echo '<br><br><br><br><br> 
		<table border="1" cellpadding="0" cellspacing="0" align="center" width="0%">
		<tr> 
		<td bgcolor="#000064" align="center"> <font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b> 
        &nbsp;&nbsp;Falha na Conex&atilde;o ao Banco de Dados CACIC&nbsp;&nbsp;&nbsp;</b></font></td>
		</tr>
		<tr>
        <td bgcolor="#c0c0c0" bordercolor="#c0c0c0" align="center">
		<table border="0" cellspacing="4" cellpadding="4">
        <tr> 
        <td><p><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000"><strong>ATEN&Ccedil;&Atilde;O:</strong> 
        <br>
        N&atilde;o foi poss&iacute;vel estabelecer comunica&ccedil;&atilde;o com o banco de dados do CACIC.</font></p>
        <p><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Poss&iacute;veis causas:</font></font></p>
        <ul>
		<li><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif" align="left">Computador servidor </font><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000"> do CACIC desligado;</font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif">Banco de Dados do CACIC desativado</font><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">;</font></font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Problemas de comunica&ccedil;&atilde;o na rede local;</font></font></li>
        <li><font color="#ffffff" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#000000">Incorre&ccedil;&atilde;o em par&acirc;metros de conex&atilde;o.</font></font></li>
        </ul></td></tr></table></td></tr></table>';
	} 
}
// Function for calculating the network address \\
##################################################
function network_address($cadpri,$cadsec) 
	{
	$tempchar="";
	$cadres="";
	$cadpri=dectobin($cadpri);
	$cadsec=dectobin($cadsec);
	
	for ($i=0;$i<8;$i++) 
		{
		$tempchar=substr($cadpri,$i,1);
		
		if ($tempchar=="1") 
			{
			$cadres=$cadres.substr($cadsec,$i,1);
			} 
		else 
			{
			$cadres=$cadres."?";
			}
		}
	return bindec(strtr($cadres,"?","0"));
	}
// Fuction for turning a decimal into a binary number \\
########################################################
function dectobin($dectobin) 
	{
	$cadtemp="";
	$dectobin = decbin($dectobin);
	$numins = 8 - strlen($dectobin);

	for ($i = 0; $i < $numins; $i++) 
		{
		$cadtemp = $cadtemp."0";
		}
	return $cadtemp.$dectobin;
	}

function autentica_agente($p_CipherKey, $p_IV, $p_cs_cipher, $p_cs_compress, $p_PaddingKey='') 
	{
	/*
	GravaTESTES('###########################################');		
	GravaTESTES('Script Chamador:  '.$_SERVER['REQUEST_URI']);		
	GravaTESTES('1:  '.$_SERVER['HTTP_USER_AGENT']);	
	GravaTESTES('11: '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['HTTP_USER_AGENT'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));
	GravaTESTES('2:  '.$_SERVER['PHP_AUTH_USER']);	
	GravaTESTES('22: '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_USER'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));	
	GravaTESTES('3:  '.$_SERVER['PHP_AUTH_PW']);		
	GravaTESTES('33: '.strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_PW'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)));			
	GravaTESTES('###########################################');			
	*/
	if ((strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['HTTP_USER_AGENT'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'AGENTE_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_USER'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'USER_CACIC') ||
	    (strtoupper(DeCrypt($p_CipherKey,$p_IV,$_SERVER['PHP_AUTH_PW'],$p_cs_cipher, $p_cs_compress,$p_PaddingKey)) != 'PW_CACIC'))   
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
	$query = 'SELECT te_node_address, te_nome_computador, te_ip, id_ip_rede, te_workgroup
	          FROM computadores
			  WHERE te_node_address = "'.$te_node_address.'"
			  AND id_so = "'.$id_so.'"';
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);


    if (mysql_num_rows($result) == 0)
		{
		return '0'; // O computador não existe, deverá ser incluido
		}
	elseif ($row['te_nome_computador'] == '' || 
			$row['te_ip'] == '' ||
			$row['id_ip_rede'] == '' || 
			$row['te_workgroup'] == '') 									
		{
		 return '2';  // O computador existe porém sem uma dessas informações...  - Anderson 16/04/2004 - 21:31h!!!!!!!!!!!!
		}
	else 
		{ 
		return '1';  // O computador existe, sem necessidade de atualizações
		}
	}

// --------------------------------------------------------------------------------------
// --------------------------------------------------------------------------------------
function ChecaSO($te_node_address,$id_so)
	{
	$boolRetorno = '0';
	conecta_bd_cacic();
	$query = 'SELECT id_so FROM so WHERE id_so <> '.$id_so.' AND in_mswindows = "S"';
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_array($result))
		{	
		$arrComputadores = getValores('computadores', 'count(te_node_address) as TotalSO', 'te_node_address = "'.$te_node_address.'" and id_so = '.$row['id_so']);
		$intTotalSO = $arrComputadores['TotalSO'];
		if ($intTotalSO > 0)
			{
			$boolRetorno = '1';
			//GravaTESTES('***** Processo DePara de Versão do MS-Windows *****');			
			//GravaTESTES('te_node_address='.$te_node_address.'#id_so_atual='.$id_so.'#id_so_antigo='.$row['id_so']);			

			$arrPatrimonio = getValores('patrimonio', 'count(te_node_address) as TotalPAT', 'te_node_address = "'.$te_node_address.'" and id_so = '.$row['id_so']);			
			$intTotalPAT   = $arrPatrimonio['TotalPAT']; 
			if ($intTotalPAT > 0)
				{
				//GravaTESTES('***** Gravando novo ID_SO na tabela PATRIMONIO *****');											

				$query = 'UPDATE patrimonio SET id_so = '.$id_so.' WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
				$result = mysql_query($query);				
				}
			
			$arrOfficescan = getValores('officescan', 'count(te_node_address) as TotalOFF', 'te_node_address = "'.$te_node_address.'" and id_so = '.$row['id_so']);			
			$intTotalOFF = $arrOfficescan['TotalOFF'];
			if ($intTotalOFF > 0)
				{
				//GravaTESTES('***** Gravando novo ID_SO na tabela OFFICESCAN *****');											
				$query = 'UPDATE officescan SET id_so = '.$id_so.' WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
				$result = mysql_query($query);				
				}

			$query = 'UPDATE historico_hardware SET id_so = '.$id_so.' WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);		
				
			$query = 'UPDATE historico_tcpip    SET id_so = '.$id_so.' WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				
			

			//GravaTESTES('* Deletando ID antigo de comput., apl_mon, compart, compon_est_hist, soft_inv_est, unid_disco, var_amb_est *');

			$query = 'DELETE from computadores                     WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from aplicativos_monitorados          WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from compartilhamentos                WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from componentes_estacoes_historico   WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from softwares_inventariados_estacoes WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from unidades_disco                   WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			$query = 'DELETE from variaveis_ambiente_estacoes      WHERE te_node_address = "'.$te_node_address.'" AND id_so='.$row['id_so'];
			$result = mysql_query($query);				

			//GravaTESTES('======================================================================');										
			}
		}
	return $boolRetorno;
	}

// --------------------------------------------------------------------------------------
// Função que insere se um dado computador no BD, caso ele não esteja inserido.
// --------------------------------------------------------------------------------------
function inclui_computador_caso_nao_exista(	$te_node_address, 
											$id_so_new, 
											$te_so_new,
											$id_ip_rede, 
											$te_ip, 
											$te_nome_computador, 
											$te_workgroup) 
	{											
	$v_te_ip = $te_ip;
	// ...caso o IP esteja inválido, obtenho-o a partir de variável do servidor
	// Tudo bem! Ainda vou implementar usando expressões regulares!!!  :)
	if (substr_count($v_te_ip,'zf')>0 || trim($v_te_ip)=='')
		$v_te_ip = 	$_SERVER['REMOTE_ADDR'];
	
	/*
	GravaTESTES('Script Chamador: '.$_SERVER['REQUEST_URI']);		
	GravaTESTES('v_te_ip: '.$v_te_ip);			
	GravaTESTES('te_node_address: '.$te_node_address);			
	GravaTESTES('id_so_new: '.$id_so_new);			
	GravaTESTES('te_so_new: '.$te_so_new);			
	GravaTESTES('te_so_new_new: '.$te_so_new_new);					
	GravaTESTES('id_ip_rede: '.$id_ip_rede);			
	GravaTESTES('te_ip: '.$te_ip);			
	GravaTESTES('v_te_ip: '.$v_te_ip);				
	GravaTESTES('te_nome_computador: '.$te_nome_computador);			
	GravaTESTES('te_workgroup: '.$te_workgroup);									
	*/
	
	$arrSO = getValores('so', 'id_so', 'id_so = '.$id_so_new);
	$id_so = $arrSO['id_so'];
	
	$arrSO = getValores('so', 'te_so', 'te_so = "'.$te_so_new.'"');
	$te_so = $arrSO['te_so'];
	
	if ($te_so == '' && $id_so <> '' && $id_so <> 0 && $te_so_new <> '') // Encontrei somente o Identificador Externo (ID_SO)
		{
		
		$te_so = $te_so_new;
				
		conecta_bd_cacic();
		$query = 'UPDATE so 
       	  		  SET te_so = "'.$te_so_new.'"
			      WHERE id_so = '.$id_so;
		//GravaTESTES('query 1: '.$query);							  
		$result = mysql_query($query);
		}	
	elseif ($te_so <> '' && ($id_so == '' || $id_so == 0)) // Encontrei somente o Identificador Interno (TE_SO)
		{
		conecta_bd_cacic();
		$query = 'SELECT id_so 
				  FROM   so
			      WHERE  te_so = "'.$te_so.'"';
		//GravaTESTES('query 2: '.$query);							  				  
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$id_so = $row['id_so'];
		}

	if ($te_so == '' && ($id_so == '' || $id_so == 0)) // Nada Encontrado, provavelmente versão antiga de agente.
		{		
		if ($te_so_new <> '' && ($id_so_new <> '' && $id_so_new <> 0)) // Só insiro se houver conteúdo chegando...
			{
			conecta_bd_cacic();			
			if ($id_so == 0 || $id_so == '')
				{
				// Busco o último valor do Identificador Externo caso não tenha recebido valor para o ID Externo
				$querySEL  = 'SELECT max(id_so) as MaxIdSo
							  FROM   so';
				$resultSEL = mysql_query($querySEL);
				$rowSEL    = mysql_fetch_array($resultSEL);			
				$id_so = ($rowSEL['MaxIdSo']+1);														   
				}
			else
				$id_so = $id_so_new;

			// Insiro a informação na tabela de Sistemas Operacionais incrementando o Identificador Externo
			$queryINS  = 'INSERT 
						  INTO 		so(id_so,te_desc_so,sg_so,te_so) 
						  VALUES    ('.$id_so.',"S.O. a Cadastrar","Sigla a Cadastrar","'.$te_so_new.'")';
		//GravaTESTES('queryINS: '.$queryINS);							  						  
			$resultINS = mysql_query($queryINS);		
	
			// Carrego os dados referente à rede da estação		
			$v_dados_rede = getDadosRede();			
			
			// Verifico pelo local se há coletas configuradas e acrescento o S.O. à tabela de ações
			$querySEL  = 'SELECT 	id_acao
						  FROM   	acoes_so
						  WHERE  	id_local = '.$v_dados_rede['id_local'].' 
						  GROUP BY 	id_acao';							  						
		//GravaTESTES('querySEL: '.$querySEL);							  						  						  
			$resultSEL = mysql_query($querySEL);
			
			// Caso existam ações configuradas para o local, incluo o S.O. para que também execute-as...
			$strInsereID = '';
			while ($rowSEL    = mysql_fetch_array($resultSEL))
				{
				$strInsereID .= ($strInsereID <> ''?',':'');
				$strInsereID .= '('.$v_dados_rede['id_local'].','.$rowSEL['id_acao'].','.$id_so.')';
				}
										
			if ($strInsereID <> '')
				{
				$queryINS = 'INSERT INTO acoes_so(id_local, id_acao, id_so) 
							 VALUES '.$strInsereID;
				$resultINS = mysql_query($queryINS);
				}
				
			}					
		}

	$arrRetorno = array('id_so'=>'','te_so'=>'');
	if ($id_so > 0 && $te_node_address <> '') // Não é interessante inserir uma máquina sem ID_SO e nem TE_NODE_ADDRESS!!!
		{		
		$checa_existe = computador_existe($te_node_address, $id_so);
		conecta_bd_cacic();		
	    if ($checa_existe == '0') // O computador não existe: INCLUIR.
			{ 

			$query = 'INSERT INTO computadores (te_node_address, id_so, te_so, id_ip_rede, te_ip, te_nome_computador, te_workgroup, dt_hr_inclusao, dt_hr_ult_acesso)
					  VALUES ("'.$te_node_address.'", "'.$id_so.'","'.$te_so.'", "'.$id_ip_rede.'","'.$v_te_ip.'","'.$te_nome_computador.'","'.$te_workgroup.'", NOW(), NOW())';
			}
		elseif ($checa_existe == '2') // O computador existe: ATUALIZAR.
			{
			$query = 'UPDATE computadores 
        	  		  SET id_ip_rede = "'.$id_ip_rede.'",
					      te_ip = "'.$v_te_ip.'",
					      te_so = "'.$te_so.'",					  
						  te_nome_computador="'.$te_nome_computador.'",
						  te_workgroup="'.$te_workgroup.'"					  
				      WHERE te_node_address = "'.$te_node_address.'"
							AND id_so = "'.$id_so.'"';
			} 
//GravaTESTES('QUERY : '.$query);			
		$result = mysql_query($query);			
		$arrRetorno = array('id_so'=>$id_so,'te_so'=>$te_so);
		// OK! O computador foi INCLUIDO/ATUALIZADO.
		
		// Removo qualquer registro de insucesso na instalação existente para o IP + SO (!!!)
		$query = 'DELETE 
				  FROM		insucessos_instalacao
				  WHERE		te_ip = "'.$v_te_ip.'" AND
				  			te_so = "'.$te_so.'"'; // Talvez eu melhore esta cláusula mais tarde
		$result = mysql_query($query);												
		}

	// ******************************************************************************************************************************
	// ******************************************************************************************************************************
	// Novo Conceito:
	// As estações poderão ter uma licença MS-Windows e n sabores de Linux
	// Desta forma, será customizada a ocupação do banco e será mantida a versão do S.O. mais atual para fins de Gestão de Licenças e
	// estatísticas
	//
	// Anderson Peterle - Dataprev/ES - 01/08/2008 12:04h
	// ******************************************************************************************************************************
	// ******************************************************************************************************************************
	$arrSO = getValores('so', 'in_mswindows', 'te_so = "'.$te_so_new.'"');
	$in_mswindows = $arrSO['in_mswindows'];	
	
	if ($in_mswindows == 'S')
		{	
		$arrSO = getValores('so', 'id_so', 'te_so = "'.$te_so_new.'"');		
		$id_so = $arrSO['id_so'];
		if (ChecaSO($te_node_address,$id_so))
			{
			//GravaTESTES('***** Forçando coletas ANVI COMP HARD MONI SOFT UNDI para a estação *****');											
			$query = '	UPDATE	computadores 
						SET 	dt_hr_coleta_forcada_estacao = now(),
								te_nomes_curtos_modulos="COMP#HARD#SOFT#ANVI#MONI#UNDI" 
						WHERE 	te_node_address="'.$te_node_address.'" AND
								id_so='.$id_so; 
			$result = mysql_query($query);		
			//GravaTESTES('======================================================================');														
			}							
		}
		
	// ******************************************************************************************************************************
	// ******************************************************************************************************************************

	return $arrRetorno; 
	}


/* --------------------------------------------------------------------------------------
 Função usada para recuperar valores de campos únicos. Útil para a tabela de configurações.
 Passou a retornar array com colunas a partir de 22/10/2008
 -------------------------------------------------------------------------------------- */
function getValores($tabela, $campos, $where="1") 
	{	
	$arrRetorno = array();	
	conecta_bd_cacic();
	$query_SEL = 'SELECT '.$campos.' FROM '.$tabela.' WHERE '.$where.' LIMIT 1';

	$result_SEL = mysql_query($query_SEL);
	if (mysql_num_rows($result_SEL) > 0) 
		{
		$row_SEL = mysql_fetch_array($result_SEL);		

		for ($i=0;$i < mysql_num_fields($result_SEL);$i++)
			{
			$arrTMP = array(mysql_field_name($result_SEL,$i) => $row_SEL[$i]);
			$arrRetorno = array_merge($arrRetorno,$arrTMP);
			}
		} 
	return $arrRetorno;
	}


function get_subrede($ip) 
	{
    $subrede = explode( '.', $ip);
    return $subrede[0] . '.' . $subrede[1] . '.' . $subrede[2] . '.0';
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
	 if ($tamanho_original > $tamanho_desejado) return substr($string,0, $tamanho_desejado - 1) . '...';
	 else 
	 	{ 
		for ($i = $tamanho_original; $i < $tamanho_desejado; $i++) $string = $string . '&nbsp;';
		return $string;
	 	} 
	}

// --------------------------------------------------------------------------------------
// Função usada para fazer uma quebra de linha em uma string
// --------------------------------------------------------------------------------------
function quebra_linha($string, $tamanho_desejado) {
	 $tamanho_original = strlen($string);
	 for($i = 0; $i < $tamanho_original; $i++) {
		if($cont == $tamanho_desejado) {
			$cont = 0;
			$nova_string = $nova_string . '<br>';
			$i--;
		}
		else {
			$cont++;
			$nova_string = $nova_string . $string[$i];
		}
	 }
	 return $nova_string;
}

// --------------------------------------------------------------------------------------
// Função usada para fazer updates das versões dos módulos nas subredes...
// --------------------------------------------------------------------------------------
function atualiza_red_ver_mod($pp_id_ip_rede, $p_nm_modulo, $p_te_versao_modulo, $p_te_hash, $p_id_local)
	{
	$MainFolder		= GetMainFolder();
	$cs_tipo_so = (stripos2($p_nm_modulo,'.exe',false)?'MS-Windows':'GNU/LINUX');
	$cs_tipo_so = (stripos2($p_nm_modulo,'.ini',false)?'MS-Windows':$cs_tipo_so);		

	conecta_bd_cacic();
	$query_UPD = '	UPDATE 	redes 
					set dt_verifica_updates = NOW() 
					WHERE 	id_ip_rede = "'.$pp_id_ip_rede.'" AND
					        id_local = '.$p_id_local;		
	$result = mysql_query($query_UPD);

	// Aqui eu excluo a versão anterior...
	// No caso do Linux é um pacote, por isso mato pelo tipo de S.O.
	$query_DEL	= 'DELETE 	
				   FROM 	redes_versoes_modulos
				   WHERE 	id_local = '.$p_id_local.' AND
				   			TRIM(id_ip_rede) = "'.trim($pp_id_ip_rede).'" AND
							trim(cs_tipo_so) = "'.$cs_tipo_so.'" ';
	
	if ($cs_tipo_so == 'MS-Windows')
		$query_DEL	.= ' AND TRIM(nm_modulo)="'.trim($p_nm_modulo).'"';
	//GravaTESTES('mod query_DEL: '.$query_DEL);						   																
	$result_DEL = mysql_query($query_DEL);
	
	$v_te_versao_modulo = $p_te_versao_modulo;
	if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
		{
		$v_te_versao_modulo = str_replace('.','',$v_te_versao_modulo);
		}

	$query_INS	= 'INSERT  
				   INTO 		redes_versoes_modulos (id_ip_rede,
													   nm_modulo,
													   te_versao_modulo,
													   id_local,
													   dt_atualizacao,
													   cs_tipo_so,
													   te_hash)
						   values					   ("'.$pp_id_ip_rede.'",
														"'.$p_nm_modulo.'",
														"'.$v_te_versao_modulo.'","'.
														$p_id_local.'",
														now(),
														"'.$cs_tipo_so.'",
														"'.$p_te_hash.'")';
	//GravaTESTES('mod query_INS: '.$query_INS);						   																														
	$result_INS = mysql_query($query_INS);
	}

// --------------------------------------------------------------------------------------------------------------------------------------------------------
// Função usada para fazer updates das versões dos módulos nos servidores de updates quando a chamada tem origem na página, via opção Update de Subredes...
// --------------------------------------------------------------------------------------------------------------------------------------------------------
function atualiza_red_ver_mod_pagina($pp_te_serv_updates, $p_nm_modulo, $p_te_versao_modulo,$p_te_hash)
	{
	$MainFolder		= GetMainFolder();
	$cs_tipo_so = (stripos2($p_nm_modulo,'.exe',false)?'MS-Windows':'GNU/LINUX');
	$cs_tipo_so = (stripos2($p_nm_modulo,'.ini',false)?'MS-Windows':$cs_tipo_so);	

	conecta_bd_cacic();
//	$query_SEL = '  SELECT id_ip_rede,
//						   id_local
//					FROM   redes
//					WHERE  te_serv_updates = "'.$pp_te_serv_updates.'"';
	$query_SEL = '
	SELECT	id_ip_rede,
			nm_rede,
			id_local
	FROM	redes
	WHERE	te_serv_updates = "'.$pp_te_serv_updates.'"';
					// AND id_local = '.$p_id_local;
	//GravaTESTES('query_SEL: '.$query_SEL);						   
	$result_SEL = mysql_query($query_SEL);
	
	$locais_redes = '';
	while ($row = mysql_fetch_array($result_SEL))
		{
		if ($locais_redes <> '') $locais_redes .= ',';
		$locais_redes .= '"#'.$row['id_local'].$row['id_ip_rede'].'#"';
		}
					
	$query_UPD = '	UPDATE 	redes 
					set dt_verifica_updates = NOW() 
					WHERE 	CONCAT("#",id_local,id_ip_rede,"#") IN ('.$locais_redes.')';
					// AND id_local = '.$p_id_local;
	//GravaTESTES('query_UPD: '.$query_UPD);						   							
	$result_UPD = mysql_query($query_UPD);

	// Aqui eu excluo a versão anterior...
	// No caso do Linux é um pacote, por isso mato pelo tipo de S.O.
	$query_DEL	= 'DELETE 	
				   FROM 	redes_versoes_modulos
				   WHERE 	CONCAT("#",id_local,id_ip_rede,"#") IN ('.$locais_redes.') AND
							trim(cs_tipo_so) = "'.$cs_tipo_so.'" ';
	
	if ($cs_tipo_so == 'MS-Windows')
		$query_DEL	.= ' AND TRIM(nm_modulo)="'.trim($p_nm_modulo).'"';
	//GravaTESTES('mod_pagina query_DEL: '.$query_DEL);						   									
	$result_DEL = mysql_query($query_DEL);

	/*
	$query_DEL	= 'DELETE 	
				   FROM 	redes_versoes_modulos
				   WHERE 	TRIM(id_ip_rede) IN ('.$redes.') AND
				            nm_modulo = "'.$p_nm_modulo.'" AND
							trim(cs_tipo_so) = "'.$cs_tipo_so.'"';
							// AND	id_local = '.$p_id_local;
	//GravaTESTES('query_DEL: '.$query_DEL);						   							
	$result_DEL = mysql_query($query_DEL);

	*/
	
	$v_te_versao_modulo = $p_te_versao_modulo;
	if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
		{
		$v_te_versao_modulo = str_replace('.','',$v_te_versao_modulo);
		}

	$query_INS	= 'INSERT  
				   INTO 		redes_versoes_modulos (id_local,
				   									   id_ip_rede,
													   nm_modulo,
													   te_versao_modulo,
													   dt_atualizacao,
													   cs_tipo_so,
													   te_hash) values ';
													   
	$virgula = '';													   
	mysql_data_seek($result_SEL,0);
	while ($row = mysql_fetch_array($result_SEL))
		{
		$query_INS .= $virgula .'('.$row['id_local'].',
								  "'.$row['id_ip_rede'].'",
								  "'.$p_nm_modulo.'",
								  "'.$p_te_versao_modulo.'",'.
								    'now(),
								  "'.$cs_tipo_so.'",
								  "'.$p_te_hash.'")';
		$virgula = ',';
		}
	//GravaTESTES('query_INS: '.$query_INS);						   
	$result_INS = mysql_query($query_INS);
	}

// -------------------------------------------------------------
// Função usada para tratamento de exceções provocadas por erros
// -------------------------------------------------------------
function trata_erros($type, $info, $file, $row)
	{
	echo '<br><b>Tipo</b>: '.$type.
	     '<br><b>Informação</b>: '.$info . 
		 '<br><b>Arquivo</b>: '.$file.
		 '<br><b>Linha</b>: '.$row.'<br>';
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
		// set_error_handler("trata_erros");		
		
//		ftp_pasv($v_conexao_ftp,true);
		@ftp_chdir($v_conexao_ftp,$p_nm_pasta_origem);									

		$MainFolder		= GetMainFolder();				

		$strNmArquivoDestino = $p_nm_arquivo_destino;
		$strNmArquivoOrigem  = $p_nm_arquivo_origem;
		

		$long_cs_tipo_ftp = ($p_cs_tipo_ftp == 'A'?FTP_ASCII:FTP_BINARY);

		if (file_exists($MainFolder . '/'.$strNmArquivoDestino)) 
			{
			$dtData = @date("YmdHis");
			$arrArquivoDestino = explode('/',$strNmArquivoDestino);
			$strNmArquivoBackup = $MainFolder . '/'.$p_nm_pasta_backup . '/' .$arrArquivoDestino[count($arrArquivoDestino)-1].'_'.$dtData;

			copy($MainFolder . '/'.$strNmArquivoDestino, $strNmArquivoBackup);

			} 
			
		if (@ftp_get($v_conexao_ftp, $MainFolder . '/' .$strNmArquivoDestino,$strNmArquivoOrigem, $long_cs_tipo_ftp))
			$resultado = 1;
			
		// Para desabilitar o modo de tratamento de exceções			
		// restore_error_handler();				
		}
			
	// fecha a conexão
	ftp_close($v_conexao_ftp);

	return $resultado;
	}
// --------------------------------------------------------------------------------------
// Função usada para listar o conteúdo do servidor de updates...
// --------------------------------------------------------------------------------------
function lista_updates($p_te_serv_updates, 
					   $p_nm_usuario_login_serv_updates_gerente,
					   $p_te_senha_login_serv_updates_gerente,
					   $p_nu_porta_serv_updates,
					   $p_te_path_serv_updates) 
	{
	$v_conexao_ftp = conecta_ftp($p_te_serv_updates,
								 $p_nm_usuario_login_serv_updates_gerente,
								 $p_te_senha_login_serv_updates_gerente,
								 $p_nu_porta_serv_updates,
								 true
								);
	$resultado = '';
	if ($v_conexao_ftp)
		{
		// obtém a lista de arquivos para /$p_te_path_serv_updates
		$buff = @ftp_rawlist($v_conexao_ftp, $p_te_path_serv_updates);
		
		$v_array_versoes_agentes = array();

		$buff = @implode('#',$buff);

		if ($buff)
			{
			// Elimina incidência de espaços duplicados
			while (strpos($buff,'  ') > 0) 
				{
				$buff = str_replace('  ',' ',$buff);			
				}									
			$buff = explode('#',$buff);

		 	for ($i=0;$i<count($buff);$i++)
				{
				$itens = explode(' ',$buff[$i]);

				if ($itens[8] == 'versoes_agentes.ini')
					{
					$i = count($buff);
					// define some variables
					$local_file  = '/tmp/versoes_agentes_'.$p_te_serv_updates.'.ini';
					$server_file = $p_te_path_serv_updates.'/versoes_agentes.ini';			
					if (@ftp_get($v_conexao_ftp, $local_file, $server_file, FTP_ASCII))
						{
						$v_array_versoes_agentes = parse_ini_file('/tmp/versoes_agentes_'.$p_te_serv_updates.'.ini');
						}
					}
				}
		
		 	for ($i=0;$i<count($buff);$i++)
				{
				$itens = explode(' ',$buff[$i]);
				if ($itens[8] <> 'supergerentes' && $itens[8] <> 'install' && $itens[8] <> '.' && $itens[8] <> '..')
					{
					$tamanho = ($itens[4]/1024);		
					if ($itens[4]<1024) $tamanho = 0;

					$resultado .= '<tr><td align="right">'.($i+1).'</td><td>'.$itens[8].'</td><td align="right">'.$tamanho.'</td><td colspan="3">';
					if ($v_array_versoes_agentes[$itens[8]])
						{
						$resultado .= $v_array_versoes_agentes[$itens[8]];
						}
					else
						{
						$resultado .= $itens[6].'-'.$itens[5].'&nbsp;/&nbsp;'.$itens[7].'h';			
						}
					$resultado .= '</td></tr>';
					}
				}
			}
		if (!$resultado) $resultado .= '<tr><td colspan="4" align="center"><font color="#FF0000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>PASTA DE UPDATES VAZIA OU INACESSÍVEL!</b></font></td></tr>';	
	}

// fecha a conexão
//ftp_close($v_conexao_ftp);
	
return $resultado;
}

// --------------------------------------------------------------------------------------
// Função usada para calcular diferença entre datas...
// É necessário usar o formato MM-DD-AAAA nessa função
// --------------------------------------------------------------------------------------
function date_diff($from, $to) 
	{ 
  	list($from_month, $from_day, $from_year) = explode("-", $from);
  	list($to_month, $to_day, $to_year) = explode("-", $to);
         
  	$from_date = mktime(0,0,0,$from_month,$from_day,$from_year);
  	$to_date = mktime(0,0,0,$to_month,$to_day,$to_year);
         
  	$days = ($to_date - $from_date)/86400;
  
	/* Adicionado o ceil($days) para garantir que o resultado seja sempre um número inteiro */

  	return ceil($days);
	}  

function GetMainFolder()
	{
	return $GLOBALS["path_aplicacao"]; // Definido em ..include/config.php
	//$LocalMainFolder = explode("/",$_SERVER['REQUEST_URI']);	
	//return $_SERVER['DOCUMENT_ROOT'] . '/' . $LocalMainFolder[1];
	}
// --------------------------------------------------------------------------------------
// Função usada para fazer updates de subredes...
// A variável p_origem poderá conter "Agente" ou "Pagina" para o tratamento de variáveis $_SESSION
// --------------------------------------------------------------------------------------
function update_subredes($p_id_ip_rede, $p_origem, $p_objetos, $p_id_local, $p_array_agentes_hashs) 
{
$MainFolder		= GetMainFolder();

// Função para replicação do conteúdo do REPOSITÓRIO nos servidores de UPDATES das redes cadastradas.
if ($handle = opendir($MainFolder . '/repositorio')) 
	{
	$v_nomes_arquivos_REP = array();
	$v_versoes_arquivos_REP = array();	
	if (file_exists($MainFolder . '/repositorio/versoes_agentes.ini'))
		$v_array_versoes_agentes = parse_ini_file($MainFolder . '/repositorio/versoes_agentes.ini');
	
	// Para tratamento dos agentes GNU/Linux  -  Anderson Peterle - Maio/2008 (Dataprev/ES)
	if ($handle = opendir($MainFolder . '/repositorio/agentes_linux')) 
		{
		while (false !== ($v_arquivo = readdir($handle))) 
			{
			if ((strpos($p_objetos,$v_arquivo) > 0 || $p_objetos=='*') and substr($v_arquivo,0,1) != ".") 				
				{
				
				// Armazeno o nome do arquivo
				array_push($v_nomes_arquivos_REP, $v_arquivo);

				$caminho_arquivo = $MainFolder . '/repositorio/agentes_linux/' . $v_arquivo;

				if (isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes['pycacic'])			
					array_push($v_versoes_arquivos_REP, $v_arquivo . '#'.str_replace('.','',$versao_agente));
				else
					array_push($v_versoes_arquivos_REP, $v_arquivo . '#'. strftime("%Y%m%d%H%M", filemtime($caminho_arquivo)));
				}
			}	
		}

	// Para tratamento dos agentes MS-Windows - Anderson Peterle - Maio/2008 (Dataprev/ES)
	$handle = opendir($MainFolder . '/repositorio');
	while (false !== ($v_arquivo = readdir($handle))) 
		{
		if ((strpos($p_objetos,$v_arquivo) > 0 || $p_objetos=='*') and substr($v_arquivo,0,1) != ".") 				
			{
			// Armazeno o nome do arquivo
			array_push($v_nomes_arquivos_REP, $v_arquivo);

			$caminho_arquivo = $MainFolder . '/repositorio/' . $v_arquivo;

			if (isset($v_array_versoes_agentes) && $versao_agente = $v_array_versoes_agentes[$v_arquivo])			
				{				
				// A string 0103 será concatenada em virtude da inserção da informação de versão nos agentes
				// até então era usada a data do arquivo como versão, a string 0103 fará com que o Gerente de Coletas 
				// entenda que as versões atuais são maiores, ou seja, a versão 20100103 é maior que 20051201
				array_push($v_versoes_arquivos_REP, $v_arquivo . '#'.str_replace('.','',$versao_agente) . '0103');
				}
			else
				{
				array_push($v_versoes_arquivos_REP, $v_arquivo . '#'. strftime("%Y%m%d%H%M", filemtime($caminho_arquivo)));
				}
			}
		}

	$query_SEL_REDES= '	SELECT 	*
						FROM	redes_versoes_modulos
						WHERE 	id_ip_rede = "' . $p_id_ip_rede . '" AND
						        id_local = '.$p_id_local.
					  ' ORDER BY nm_modulo';
	conecta_bd_cacic();
	
	$v_nomes_arquivos_FTP 	= array();
	$v_versoes_arquivos_FTP = array();				
	
	$Result_SEL_REDES = mysql_query($query_SEL_REDES);
	$v_achei = 0;
	while ($row = mysql_fetch_array($Result_SEL_REDES))
		{
		array_push($v_nomes_arquivos_FTP, trim($row['nm_modulo']));
		array_push($v_versoes_arquivos_FTP, trim($row['nm_modulo']).'#'.trim($row['te_versao_modulo']));										
		for ($cnt_arquivos_REP = 0; $cnt_arquivos_REP < count($v_nomes_arquivos_REP); $cnt_arquivos_REP++)
			{
			if (trim($v_nomes_arquivos_REP[$cnt_arquivos_REP]) == trim($row['nm_modulo']) &&
				trim($v_versoes_arquivos_REP[$cnt_arquivos_REP]) == trim($row['te_versao_modulo']))
				{
				$v_achei ++;
				$cnt_arquivos_REP = count($v_nomes_arquivos_REP);
				}
			}
		}

	if ($v_achei < count($v_nomes_arquivos_REP))
		{	
		$query_SEL_REDES= '	SELECT 		re.id_ip_rede,
										re.nm_rede,
										re.te_serv_updates,
										re.nu_porta_serv_updates, 
										re.te_path_serv_updates, 
										re.nm_usuario_login_serv_updates_gerente,								
										re.te_senha_login_serv_updates_gerente,
										re.id_local								
							FROM		redes re 
							WHERE 		re.id_ip_rede = "' . $p_id_ip_rede . '" AND
										re.id_local = '.$p_id_local;
		conecta_bd_cacic();
		$Result_SEL_REDES = mysql_query($query_SEL_REDES);
	
		$row = mysql_fetch_array($Result_SEL_REDES);
		if (trim($row['te_serv_updates'] . 
				 $row['nu_porta_serv_updates'] .
				 $row['te_path_serv_updates'] .
				 $row['nm_usuario_login_serv_updates_gerente'] .
				 $row['te_senha_login_serv_updates_gerente'] .
				 $row['nu_porta_serv_updates']) != '')
				{
				$v_tripa_objetos_enviados 			= '';
				$v_conta_objetos_enviados 			= 0;				
				$v_conta_objetos_nao_enviados 		= 0;			
				$v_conta_objetos_atualizados 		= 0;
				$v_conta_objetos_nao_atualizados 	= 0;			
				$v_conta_objetos_diferentes			= 0;
				$v_conta_objetos_inexistentes		= 0;
				$v_array_objetos_enviados 			= array();			
				$v_array_objetos_nao_enviados 		= array();						
				$v_array_objetos_atualizados 		= array();
				$v_array_objetos_nao_atualizados	= array();
				$v_efetua_conexao_ftp 				= -1;	
				$v_conexao_ftp 						= '';
				$v_efetua_conexao_ftp = -1;												

				//if (TentaPing("ftp://".$row['nm_usuario_login_serv_updates'].":".
			    //                   	   $row['te_senha_login_serv_updates']."@".
				//				       $row['te_serv_updates']).
				//					   $row['te_path_serv_updates']."/cacic.txt")
				if (CheckFtpLogin($row['te_serv_updates'],
							  	  $row['nm_usuario_login_serv_updates_gerente'],
							  	  $row['te_senha_login_serv_updates_gerente'],
								  $row['nu_porta_serv_updates']))
					{
					$v_efetua_conexao_ftp = 0 ;
					$v_conexao_ftp = conecta_ftp($row['te_serv_updates'],
												 $row['nm_usuario_login_serv_updates_gerente'],
												 $row['te_senha_login_serv_updates_gerente'],
												 $row['nu_porta_serv_updates'],
												 false
												);
					}

				if ($v_conexao_ftp)
					{
					$_SESSION['v_tripa_servidores_updates'] .= ($p_origem == 'Pagina'?'#'.trim($row['id_local']).trim($row['te_serv_updates']).'#':'');
					sort($v_nomes_arquivos_REP,SORT_STRING);						
					sort($v_versoes_arquivos_REP,SORT_STRING);											
					sort($v_nomes_arquivos_FTP,SORT_STRING);											
					sort($v_versoes_arquivos_FTP,SORT_STRING);																
					$v_efetua_conexao_ftp = 1;
	
					for ($cnt_nomes_arquivos_REP = 0; $cnt_nomes_arquivos_REP < count($v_nomes_arquivos_REP); $cnt_nomes_arquivos_REP++) 
						{
						// Atenção: acertar depois...
						$v_pasta_agente_linux = (stripos2($v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],'.tgz',false)?'agentes_linux/':'');
						$v_achei = 0;
						for ($cnt_nomes_arquivos_FTP = 0; $cnt_nomes_arquivos_FTP < count($v_nomes_arquivos_FTP); $cnt_nomes_arquivos_FTP++)
							{
							if ($v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP] == $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP])
								{
								$v_achei = 1;
								if ($v_versoes_arquivos_FTP[$cnt_nomes_arquivos_FTP] <> $v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP])
									{
									$v_conta_objetos_diferentes ++;
									@ftp_chdir($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);									
									@ftp_delete($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP]);									
									
									if (@ftp_put($v_conexao_ftp,
												$row['te_path_serv_updates'] . '/' . $v_nomes_arquivos_FTP[$cnt_nomes_arquivos_FTP],
												$MainFolder . '/repositorio/'. $v_pasta_agente_linux . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
												FTP_BINARY))
										{
										array_push($v_array_objetos_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
										$arr_versao_arquivo = explode('#',$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP]);
										if ($p_origem == 'Pagina')										
											atualiza_red_ver_mod_pagina($row['te_serv_updates'], $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$arr_versao_arquivo[1],$p_array_agentes_hashs[$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]]);
										else
											atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$arr_versao_arquivo[1],$p_array_agentes_hashs[$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]],$row['id_local']);
										echo '<font size="1px" color="orange">Atualizado...: <font color="black">'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP].'</font></font><br>';											
										$v_conta_objetos_atualizados ++;
										flush();																													
										}
									else
										{
										array_push($v_array_objetos_nao_atualizados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);											
										echo '<font color=red size="1px" color="red">Não Atualizado: <font color="black">'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP].'</font></font><br>';
										$v_conta_objetos_nao_atualizados ++;
										flush();																													
										}	
									}
								$cnt_nomes_arquivos_FTP = count($v_nomes_arquivos_FTP);
								}										
							}

						if ($v_achei == 0)
							{
							$arr_versao_arquivo = explode('#',$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP]);							
							$v_conta_objetos_inexistentes ++;
							$v_conta_objetos_enviados ++;
							$v_tripa_objetos_enviados .= ($v_tripa_objetos_enviados?'#':'');							
							$v_tripa_objetos_enviados .= $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP].','.$arr_versao_arquivo[1];
							@ftp_chdir($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);									
							@ftp_delete($v_conexao_ftp,$row['te_path_serv_updates'].'/'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);									
								
							if (@ftp_put($v_conexao_ftp,
										$row['te_path_serv_updates'] . '/' . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
										$MainFolder . '/repositorio/' . $v_pasta_agente_linux . $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],
										FTP_BINARY))
								{
								array_push($v_array_objetos_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
								if ($p_origem == 'Pagina')										
									atualiza_red_ver_mod_pagina($row['te_serv_updates'], $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$arr_versao_arquivo[1],$p_array_agentes_hashs[$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]]);
								else
									atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$arr_versao_arquivo[1],$p_array_agentes_hashs[$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]],$row['id_local']);
								
								//atualiza_red_ver_mod($row['id_ip_rede'],$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP],$v_versoes_arquivos_REP[$cnt_nomes_arquivos_REP],$row['id_local']);
								$v_conta_objetos_enviados ++;
								echo '<font size="1px" color="green">Enviado.......: <font color="black">'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP].'</font></font><br>';
								flush();																			
								}
							else
								{
								array_push($v_array_objetos_nao_enviados, $v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP]);
								$v_conta_objetos_nao_enviados ++;
								echo '<font color=red size="1px" color="red">Não Enviado: <font color="black">'.$v_nomes_arquivos_REP[$cnt_nomes_arquivos_REP].'</font></font><br>';
								$v_achei = 0;
								flush();																											
								}									
							}												
						}	
						if (($v_conta_objetos_diferentes   > 0 and $v_conta_objetos_diferentes   == $v_conta_objetos_atualizados) ||
							($v_conta_objetos_inexistentes > 0 and $v_conta_objetos_inexistentes == $v_conta_objetos_enviados)    ||
							($v_conta_objetos_diferentes  == 0 and $v_conta_objetos_inexistentes == 0))
							{
							Marca_Atualizado($p_id_ip_rede,$p_id_local);
							}
						ftp_quit($v_conexao_ftp);							
					}
				else
					{
	
					if (trim($row['te_serv_updates'])						== 	'' || 
						trim($row['nu_porta_serv_updates'])					== 	'' ||
						trim($row['te_path_serv_updates'])  				== 	'' ||
						trim($row['nm_usuario_login_serv_updates_gerente']) == 	'' ||
						trim($row['te_senha_login_serv_updates_gerente'])	==	'' ||
						trim($row['nu_porta_serv_updates'])					==	'')
						{
	
						$_SESSION['v_status_conexao'] = 'NC'; // Não Configurado
						}
					else
						{
						$_SESSION['v_status_conexao'] = 'OFF'; // Off Line
						}
					}

			if ($p_origem == 'Pagina')
				{
				$_SESSION['v_tripa_objetos_enviados'] 			= 	$v_tripa_objetos_enviados;
				$_SESSION['v_conta_objetos_enviados'] 			= 	$v_conta_objetos_enviados;				
				$_SESSION['v_conta_objetos_nao_enviados']		= 	$v_conta_objetos_nao_enviados;
				$_SESSION['v_conta_objetos_atualizados']		=	$v_conta_objetos_atualizados;
				$_SESSION['v_conta_objetos_nao_atualizados']	= 	$v_conta_objetos_nao_atualizados;
				$_SESSION['v_efetua_conexao_ftp'] 				= 	$v_efetua_conexao_ftp;
				$_SESSION['v_conexao_ftp'] 						= 	$v_conexao_ftp;
				}
			}
		}
	else
		{
		$_SESSION['v_efetua_conexao_ftp'] = 1;
		}
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

// --------------------------------------------------------------------------------------
function Marca_Atualizado($p_id_ip_rede,$p_id_local)
	{
	$query_UPD = '	UPDATE 	redes 
							set dt_verifica_updates = NOW() 
					WHERE 	id_ip_rede = "'.$p_id_ip_rede.'" AND
							id_local = '.$p_id_local;		
	conecta_bd_cacic();									
	$result_UPD = mysql_query($query_UPD);
	}
// --------------------------------------------------------------------
// Função usada para verificar a possibilidade de login no servidor FTP
// --------------------------------------------------------------------
function CheckFtpLogin($server, $user, $pass, $port) 
	{
	//  1 - Usuário Logado
	//  0 - Usuário Não Logado
	// -1 - Conexão Impossível, provavelmente servidor off-line 
	$sck = fsockopen($server, $port);
	if ($sck) 
		{
		$data = fgets($sck, 1024);
		fputs($sck, "USER $user\n");
		$data = fgets($sck, 1024);
		fputs($sck, "PASS $pass\n");
		$data = fgets($sck, 1024);
		if (ereg("230", $data)) 
			{
			# User logged in
			return 1;
			} 
		else 
			{
			# Login failed
			return 0;
			}
		fclose($sck);
		} 
	else 
		{
		return -1;
		}
	}

// --------------------------------------------------------------------------------------
// Função usada para teste de pings em IP específico...
// Inutilizada a partir da implementação da função CheckFtpLogin
// --------------------------------------------------------------------------------------
function TentaPing($link)
	{
	/*
	$v_comando_ping	= "ping ";
	$v_opcao_pings 	= ' -n';
	$v_opcao_tempo 	= ' -w';			
	$v_pings 		= 1;
	$v_tempo 		= 1;			

	if (PHP_OS == "Linux" || PHP_OS == "Unix")
		{
		$v_opcao_pings = ' -c';								
		$v_opcao_tempo = ' -i';												
		}
	$v_tentativas = 2;				
	for ($cnt_tentativas = 0; $cnt_tentativas <= $v_tentativas; $cnt_tentativas++)			
		{	
//		$v_pings ++;	
		$comando = $v_comando_ping . $v_opcao_pings . ' ' . $v_pings . ' ' .$v_opcao_tempo . ' ' . $v_tempo . ' ' . $p_te_serv_updates;
		$v_array_resultado = '';

		exec($comando, $v_array_resultado);			

		$v_efetua_conexao_ftp = 0;												
		for ($cnt_array = 0; $cnt_array < count($v_array_resultado); $cnt_array++)
			{
			if (ereg("bytes from",strtolower($v_array_resultado[$cnt_array])) || ereg("resposta de",strtolower($v_array_resultado[$cnt_array])))
				{
				$v_efetua_conexao_ftp 	= 1;
				$cnt_array 				= count($v_array_resultado);									
				$cnt_tentativas 		= $v_tentativas;
				}
			}
		}
		return $v_efetua_conexao_ftp;	
	*/

//	$link=correcturl($link);
	return chkuri($link);	
	}

function chkuri($link)
	{
    return(!$churl?false:true); 
	}

function correcturl($link)
	{
    return str_replace("ftp://","",str_replace("http://","",strtolower($link)));
	}

// -----------------------------------------------------------------------
// Função para gravação de relacionamento entre redes e perfis_aplicativos
// -----------------------------------------------------------------------
function seta_perfis_rede($p_id_local, $p_id_ip_rede, $p_perfis)
	{
	$v_perfis = explode('__',$p_perfis);
	if (count($v_perfis))
		{
		conecta_bd_cacic();
		
		for ($cnt_perfis = 0; $cnt_perfis <= count($v_perfis); $cnt_perfis++)			
			{
			$query_INS	= 'INSERT  
						   INTO 		aplicativos_redes (id_local,
						   								   id_ip_rede,
														   id_aplicativo)
						   values		('.$p_id_local.',
										 "'.$p_id_ip_rede.'",
										 '.$v_perfis[$cnt_perfis].')';
			$result_INS = mysql_query($query_INS);
			
			}
		}
	}
	
// Função usada para compactar dados de tabelas para atualização de bases...
// -------------------------------------------------------------------------
function compacta_dados_tabelas($p_dbname, $p_data_hora_inicio, $p_arquivo_saida)
	{
	conecta_bd_cacic();	
	$send = bzopen($p_arquivo_saida,"w"); //necessário ativar módulo componente bzip2

	$res = mysql_list_tables($p_dbname) or die(mysql_error(). 'ou sua sessão expirou!'); // Pega a lista de todas as tabelas
	while ($row = mysql_fetch_row($res))
		{
		$table = $row[0]; // cada uma das tabelas
		$v_fields = mysql_list_fields($p_dbname,$table);
		
		if (mysql_field_name($v_fields,0) == 'cs_enviado')
			{			
			$res2 = mysql_query("SHOW CREATE TABLE $table");
			while ( $lin = mysql_fetch_row($res2))
				{ 
				// Para cada tabela		
				$res3 = mysql_query("SELECT * FROM $table");
				if ($sql) // Caso a variável tenha conteúdo... Caso contrário, mais abaixo será atribuído.
					{
					bzwrite($send,"#");
					}					
				while($r = mysql_fetch_row($res3))
					{ 
	
					// Dump de todos os dados das tabelas
					# Evitar fechamento de aspas simples!!
					$r = str_replace("'","\'",$r);
						
					// Na minha base tem aspas duplas e tive que acrescentar essa linha:
					$r = str_replace('"','\"',$r);
	
					$sql = "#INSERT INTO $table VALUES ('";
					$sql .= implode("','",$r);
					$sql .= "')";
				
					bzwrite($send,$sql);
					}
				}
			}			
		}
		bzclose($send);
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
//==================================================
?>
