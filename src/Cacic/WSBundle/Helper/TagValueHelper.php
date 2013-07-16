<?php
/**
 * Classe responsável por trabalhar com as TAGS referentes as classes WMI da coleta do agente
 * Essas classes WMI definem o tipo do dado coletado.
 * referencia WMI: http://msdn.microsoft.com/en-us/library/windows/desktop/aa394573(v=vs.85).aspx
 */

namespace Cacic\WSBundle\Helper;


class TagValueHelper
{
    //Contrutor privado para evitar instanciação da classe.
    private function  __construct(){}

    // Metódo para recuperar valores das Classes
    public static function getClassValue($pStrTagLabel, $pStrSource, $pStrTags = '[]')
    {
        //Tratar as tags depois!
        preg_match_all("/Win32_" . $pStrTagLabel . "=(.*)/i",$pStrSource, $arrResult);
        return $arrResult[1][0];
    }

    // Metódo para recuperar array com nomes das Classes
    public static function getClassNames($pStrSource, $pStrTags = '[]')
    {
        preg_match_all("/Win32_(.*?)=/",$pStrSource,$arrResult);
        return $arrResult[1];
    }

    // Metódo para recuperar valores delimitados por tags definidas em  $pStrTags
    public static function getValueFromTags($pStrTagLabel, $pStrSource, $pStrTags = '[]')
    {
        //Tratar as tags depois!
        preg_match_all("(\[" . $pStrTagLabel . "\](.+)\[\/" . $pStrTagLabel . "\])i",$pStrSource, $arrResult);
        return empty($arrResult[1][0]) ? null : $arrResult[1][0] ;
    }

    // Metódo para recuperar array com nomes das tags delimitadas por "<" e ">"
    public static function getTagsFromValues($pStrSource, $pStrTags = '[]')
    {
        preg_match_all("/\[\/(.*?)\]/",$pStrSource,$arrResult);
        return empty($arrResult[1]) ? null : $arrResult[1] ;
    }

    // Metódo para excluir uma tag
    public static function delTags($pStrTagLabel, $pStrSource, $pStrTags = '[]')
    {
        $strBeginTag = substr($pStrTags,0,1) 		. $pStrTagLabel . substr($pStrTags,1,1);
        $strEndTag   = substr($pStrTags,0,1) . '/' 	. $pStrTagLabel . substr($pStrTags,1,1);
        $strSource	 = $pStrSource;

        $strSource = str_replace($strBeginTag . $strEndTag,'',$strSource);

        while ($strActualValue = getValueFromTags($pStrTagLabel,$strSource))
            $strSource = str_replace($strBeginTag . $strActualValue . $strEndTag,'',$strSource);

        return $strSource;
    }
    // Metódo para atribuir valor a tags
    public static function setValueToTags($pStrTagLabel, $pStrValue, $pStrSource, $pStrTags = '[]')
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


}