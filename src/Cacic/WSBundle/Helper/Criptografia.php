<?php

namespace Cacic\CommonBundle\Helper;

use Symfony\Component\HttpFoundation\Request;


class Criptografia {

    public $key;
    public $iv;

    //Contrutor privado para evitar instanciação da classe.
    private function  __construct(){}

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


    public static function enCrypt( Request $request   ,$pStrPlainData, $pStrCsCipher, $pStrCsCompress, $pIntCompressLevel=0, $pStrPaddingKey = '', $pBoolForceEncrypt = false)
    {
        $pStrCipherKey = $pStrPaddingKey;

        if ((($pStrCsCipher=='1') && !$request->request->get('cs_debug') || $pBoolForceEncrypt) )
        {
            $strResult = base64_encode(@mcrypt_cbc(MCRYPT_RIJNDAEL_128,$key,$pStrPlainData,MCRYPT_ENCRYPT,$iv));
            $strResult .= '__CRYPTED__';
        }
        else
            $strResult = $pStrPlainData;

        if (($pStrCsCompress == '1' || $pStrCsCompress == '2') && $pIntCompressLevel > 0)
            $strResult = gzdeflate($strResult,$pIntCompressLevel);

        $strResult = self::replaceInvalidHTTPChars($strResult);
        return trim($strResult);
    }

    // ---------------------------------
    // Metodo para descriptografia
    // To decrypt values
    // p_cs_cipher => Y/N
    // ---------------------------------
    public static function deCrypt(Request $request, $pStrCriptedData, $pStrCsCipher, $pIntCsUnCompress='0', $pStrPaddingKey = '', $pBoolForceDecrypt = false)
    {

        $pStrCipherKey = $pStrPaddingKey;

        // Bloco de Substituições para antes da Decriptação
        // ------------------------------------------------
        // Razão: Dependendo da configuração do servidor, os valores
        //        enviados, pertinentes é criptografia, tendem a ser interpretados incorretamente.
        // Obs.:  Vide Lista de Convenções Abaixo
        // =======================================================================================
        $ppStrCriptedData = str_ireplace('[[MAIS]]','+',$pStrCriptedData,$countMAIS);
        // =======================================================================================

        if ( (substr($ppStrCriptedData,-11) == '__CRYPTED__') && ((($pStrCsCipher=='1') && !$request->request->get('cs_debug')) || $pBoolForceDecrypt))
        {
            $ppStrCriptedData = str_replace('__CRYPTED__' , '' 	, $ppStrCriptedData);
            $strResult = (trim($ppStrCriptedData)<>''?@mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$key,base64_decode($ppStrCriptedData),MCRYPT_MODE_CBC,$iv):'');
        }
        else
            $strResult = $pStrCriptedData;

        // Bloco de Substituições para depois da Decriptação
        // -------------------------------------------------
        // Razão: idem acima, porém, com dados pertinentes aos valores a serem recebidos
        // =============================================================================
        $strResult = self::replacePseudoTagsWithCorrectChars($strResult);
        // =============================================================================

        if ($pIntCsUnCompress == '1')
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


}