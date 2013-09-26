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

        // Garantir que o resultado contenha somente UTF-8 valido
        $resultado = TagValueHelper::UTF8Sanitize($arrResult[1][0]);

        return empty($resultado) ? null : $resultado;
    }

    // Metódo para recuperar array com nomes das tags delimitadas por "<" e ">"
    public static function getTagsFromValues($pStrSource, $pStrTags = '[]')
    {
        preg_match_all("/\[\/(.*?)\]/",$pStrSource,$arrResult);
        $teste = print_r($arrResult[1], true);
        //error_log("01010101011010101010101010101010101011010101: $teste");
        //error_log("20202020202020220202020202020220202020202020: $pStrSource");
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

    /*
     * Faz o parse da tag software, retornando uma sequência de registros que possui a tag software
     */

    public static function getSoftwareTags($pStrSource, $pStrTags = '[]')
    {
        preg_match_all("/\[Software\](.*?)\[\/Software\]/",$pStrSource,$arrResult);
        $teste = print_r($arrResult[1], true);
        //error_log("2222222222222222222222222222222222222222222222222222222222: $teste");
        //error_log("20202020202020220202020202020220202020202020: $pStrSource");
        return empty($arrResult[1]) ? null : $arrResult[1] ;
    }

    /*
     * Limpa caracteres que não são UTF-8
     * Extraído de http://stackoverflow.com/questions/1401317/remove-non-utf8-characters-from-string
     */

    public static function UTF8Sanitize($text) {
        return iconv('UTF-8', 'UTF-8//IGNORE', $text);
    }

    /*
     * Trata o caso de retornar os registros multivalorados para mostrar em uma tabela
     */

    public static function getTableValues($source) {
        preg_match_all("/\[\[REG\]\](.*?)\[\[REG\]\]/",$source,$arrResult);

        // Se não houver match, retorna a fonte
        if (empty($arrResult[1])) {
            return $source;
        }

        //Caso contrário retorna os elementos prontos para serem inseridos em uma tabela
        $saida = '';
        $i = 1;
        foreach ($arrResult[1] as $linha) {
            $saida = $saida . '<tr>';
            $saida = $saida . "<th>#$i</th>";
            $saida = $saida . "<td>$linha</td>";
            $saida = $saida . '</tr>';
            $i = $i + 1;
        }

        return $saida;

    }



}