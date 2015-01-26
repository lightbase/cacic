<?php

namespace Cacic\WSBundle\Helper;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpKernel\Kernel;

class OldCacicHelper
{
    /*
     * Diretório do Symfony
     * @var string
     */
    public $rootDir;

    /*
     * No construtor recebo os parâmetros do Kernel do Symfony
     */

    public function __construct($kernel) {
        // Não sei porquê ele retorna no diretório app
        $this->rootDir = $kernel->getRootDir() . "/..";
    }

    public function getRootDir() {
        // Tenta consertar caminho do diretório
        $rootDir = realpath($this->rootDir);

        return $rootDir;
    }

    /*
     * Configuração do arquivo de hashes e executáveis que será lido pelo Agente
     */

    public function iniFile() {
        return OldCacicHelper::getRootDir() . OldCacicHelper::CACIC_PATH_RELATIVO_DOWNLOADS . 'versions_and_hashes.ini';
    }

	// define o nome do agente principal do CACIC
	const CACIC_MAIN_PROGRAM_NAME = 'cacic280';
  
    // define o nome da pasta local para o CACIC
    const CACIC_LOCAL_FOLDER_NAME = 'Cacic';

    // define  o nome da pasta de scripts de interface com os agentes
    const CACIC_WEB_SERVICES_FOLDER_NAME = 'ws/';

    // define  chave para agentes CACIC
    const CACIC_KEY = 'CacicBrasil';

    // define  chave para agentes CACIC
    //const CACIC_PATH = '/srv/cacic3';

    // define  IV para agentes CACIC
    const CACIC_IV = 'abcdefghijklmnop';
    
    // define  path para componentes de instalação, coleta de dados de patrimônio e cliente de Suporte Remoto do CACIC
    const CACIC_PATH_RELATIVO_DOWNLOADS = '/web/downloads/';

    // Arquivo com hashes dos agentes
    //const iniFile =  '/srv/cacic3/downloads/versions_and_hashes.ini';
    
    /**
     * 
     * Converte string no padrão Camel Case para o padrão com Underscore
     * Ex.: idGrupoUsuario -> id_grupo_usuario
     * @param string $string
     */
    public static function camelCaseToUnderscore( $string )
    {
    	$string = preg_replace('/(?<=\\w)(?=[A-Z])/',"_$1", $string);
		$string = strtolower($string);
		return $string;
    }
     //---------------------------------------------------------------------------------
    //  Substituir alguns valores inválidos ao tráfego HTTP
    //
    //  @return String contendo o string com valores inv�lidos substituidos por v�lidos
    //---------------------------------------------------------------------------------
    public static function replaceInvalidHTTPChars($pStrString)
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
    public static function replacePseudoTagsWithCorrectChars($pStrString)
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


    public static function enCrypt( Request $request   ,$pStrPlainData, $pIntCompressLevel=0, $pStrPaddingKey = '', $pBoolForceEncrypt = false)
    {
        $pStrCipherKey = $pStrPaddingKey;

        if ((($request->get('cs_cipher')=='1') && !$request->request->get('cs_debug') || $pBoolForceEncrypt) )
        {
            $strResult = base64_encode(@mcrypt_cbc(MCRYPT_RIJNDAEL_128,OldCacicHelper::CACIC_KEY,$pStrPlainData,MCRYPT_ENCRYPT,OldCacicHelper::CACIC_IV));
            $strResult .= '__CRYPTED__';
        }
        else
            $strResult = $pStrPlainData;

        if (($request->get('cs_compress') == '1' || $request->get('cs_compress') == '2') && $pIntCompressLevel > 0)
            $strResult = gzdeflate($strResult,$pIntCompressLevel);

        $strResult = self::replaceInvalidHTTPChars($strResult);
        return trim($strResult);
    }

    // ---------------------------------
    // Metodo para descriptografia
    // To decrypt values
    // p_cs_cipher => Y/N
    // ---------------------------------
    public static function deCrypt(Request $request, $pStrCriptedData, $pStrPaddingKey = '', $pBoolForceDecrypt = false)
    {
        // Bloco de Substituições para antes da Decriptação
        // ------------------------------------------------
        // Razão: Dependendo da configuração do servidor, os valores
        //        enviados, pertinentes é criptografia, tendem a ser interpretados incorretamente.
        // Obs.:  Vide Lista de Convenções Abaixo
        // =======================================================================================
        $ppStrCriptedData = str_ireplace('[[MAIS]]','+',$pStrCriptedData,$countMAIS);
        // =======================================================================================

        if ( (substr($ppStrCriptedData,-11) == '__CRYPTED__') && ((($request->get('cs_cipher')=='1') && !$request->get('cs_debug')) || $pBoolForceDecrypt))
        {
            $ppStrCriptedData = str_replace('__CRYPTED__' , '' 	, $ppStrCriptedData);
            $strResult = (trim($ppStrCriptedData)<>''?@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,OldCacicHelper::CACIC_KEY,base64_decode($ppStrCriptedData),MCRYPT_MODE_CBC,OldCacicHelper::CACIC_IV):'');
        }
        else
            $strResult = $pStrCriptedData;

        // Bloco de Substituições para depois da Decriptação
        // -------------------------------------------------
        // Razão: idem acima, porém, com dados pertinentes aos valores a serem recebidos
        // =============================================================================
        $strResult = self::replacePseudoTagsWithCorrectChars($strResult);
        // =============================================================================

        if ($request->get('cs_compress') == '1')
            $strResult = gzinflate($strResult);

        // Aqui retiro do resultado a ocorrência do preenchimento, caso exista. (o agente Python faz esse preenchimento)
        if ($pStrPaddingKey <> '')
        {
            $char 		= substr($pStrPaddingKey,0,1);
            $re 		= "/".$char."*$/";
            $strResult 	= preg_replace($re, "", $strResult);
        }

        return trim($strResult);
    }

    public function getTest( Request $request){
        $iniFile = $this->iniFile();
        if ( $iniFile )
        {
            $arrVersionsAndHashes = parse_ini_file( $iniFile );
            return array(
                'INSTALLCACIC.EXE_HASH' => OldCacicHelper::EnCrypt($request, $arrVersionsAndHashes['installcacic.exe_HASH'],true),
                'MainProgramName' => OldCacicHelper::CACIC_MAIN_PROGRAM_NAME.'.exe',
                'LocalFolderName' => OldCacicHelper::CACIC_LOCAL_FOLDER_NAME
            );

        } else {
            error_log("ERRO: Arquivo .ini de configuração dos binários não encontrado");
        }
    }


    /*
     * Responsável por autenticação do agente CACIC
     */
    public static function autenticaAgente(Request $request)
    {
        if( ( $request->get('HTTP_USER_AGENT') != "YwpgjzZ86/eCsjvOki1KkQ==__CRYPTED__") ||
            ( $request->get('PHP_AUTH_USER')   != "Dcr8b5IfZOJjt6qyH5dGyw==__CRYPTED__") ||
            ( $request->get('PHP_AUTH_PW')     != "Yot8BeM9lOh431SB7dYQXw==__CRYPTED__")
          )
        {
            echo 'CACIC URL Access Denied.';
            die;
        }
    }



    public static function getOnlyFileName($pStrFullFileName)
    {
        $strResult = str_replace('/' ,'#SLASH#',$pStrFullFileName);
        $strResult = str_replace('\\','#SLASH#',$strResult);
        $arrResult = explode('#SLASH#',$strResult);
        return $arrResult[count($arrResult)-1];
    }

    public static function stripos2($strString, $strSubString, $boolRetornaPosicao = true)
    {
        $intPos = strpos($strString, stristr( $strString, $strSubString ));

        if (!$boolRetornaPosicao)
            $intPos = (($intPos < 0 || trim($intPos) == '') ? 0 : 1);

        return $intPos;
    }

    public static function  udate($format = 'u', $utimestamp = null) {
        if (is_null($utimestamp))
            $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

}
