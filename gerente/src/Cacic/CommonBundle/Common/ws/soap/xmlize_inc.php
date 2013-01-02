<!--
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 -->
<?php

/* xmlize() is by Hans Anderson, www.hansanderson.com/contact/
 *
 * Ye Ole "Feel Free To Use it However" License [PHP, BSD, GPL].
 * some code in xml_depth is based on code written by other PHPers
 * as well as one Perl script.  Poor programming practice and organization
 * on my part is to blame for the credit these people aren't receiving.
 * None of the code was copyrighted, though.
 *
 * This is a stable release, 1.0.  I don't foresee any changes, but you
 * might check http://www.hansanderson.com/php/xml/ to see
 *
 * usage: $xml = xmlize($xml_data);
 *
 * See the function traverse_xmlize() for information about the
 * structure of the array, it's much easier to explain by showing you.
 * Be aware that the array is very complex.  I use xmlize all the time,
 * but still need to use traverse_xmlize or print_r() quite often to
 * show me the structure!
 *
 */

function xmlize($data, $WHITE=1) {

    $data = trim($data);
    $vals = $index = $array = array();
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, $WHITE);
    if ( !xml_parse_into_struct($parser, $data, $vals, $index) )
    {
	die(sprintf("XML error: %s at line %d",
                    xml_error_string(xml_get_error_code($parser)),
                    xml_get_current_line_number($parser)));

    }
    xml_parser_free($parser);

    $i = 0; 

    $tagname = $vals[$i]['tag'];
    if ( isset ($vals[$i]['attributes'] ) )
    {
        $array[$tagname]['@'] = $vals[$i]['attributes'];
    } else {
        $array[$tagname]['@'] = array();
    }

    $array[$tagname]["#"] = xml_depth($vals, $i);

    return $array;
}

/* 
 *
 * You don't need to do anything with this function, it's called by
 * xmlize.  It's a recursive function, calling itself as it goes deeper
 * into the xml levels.  If you make any improvements, please let me know.
 *
 *
 */

function xml_depth($vals, &$i) { 
    $children = array(); 

    if ( isset($vals[$i]['value']) )
    {
        array_push($children, $vals[$i]['value']);
    }

    while (++$i < count($vals)) { 

        switch ($vals[$i]['type']) { 

           case 'open': 

                if ( isset ( $vals[$i]['tag'] ) )
                {
                    $tagname = $vals[$i]['tag'];
                } else {
                    $tagname = '';
                }

                if ( isset ( $children[$tagname] ) )
                {
                    $size = sizeof($children[$tagname]);
                } else {
                    $size = 0;
                }

                if ( isset ( $vals[$i]['attributes'] ) ) {
                    $children[$tagname][$size]['@'] = $vals[$i]["attributes"];
                }

                $children[$tagname][$size]['#'] = xml_depth($vals, $i);

            break; 


            case 'cdata':
                array_push($children, $vals[$i]['value']); 
            break; 

            case 'complete': 
                $tagname = $vals[$i]['tag'];

                if( isset ($children[$tagname]) )
                {
                    $size = sizeof($children[$tagname]);
                } else {
                    $size = 0;
                }

                if( isset ( $vals[$i]['value'] ) )
                {
                    $children[$tagname][$size]["#"] = $vals[$i]['value'];
                } else {
                    $children[$tagname][$size]["#"] = '';
                }

                if ( isset ($vals[$i]['attributes']) ) {
                    $children[$tagname][$size]['@']
                                             = $vals[$i]['attributes'];
                }			

            break; 

            case 'close':
                return $children; 
            break;
        } 

    } 

	return $children;

}


/* function by acebone@f2s.com, a HUGE help!
 *
 * this helps you understand the structure of the array xmlize() outputs
 *
 * usage:
 * traverse_xmlize($xml, 'xml_');
 * print '&lt;pre&gt;' . implode("", $traverse_array . '&lt;/pre&gt;';
 *
 *
 */ 

function traverse_xmlize($array, $arrName = "array", $level = 0) {

    foreach($array as $key=>$val)
    {
        if ( is_array($val) )
        {
            traverse_xmlize($val, $arrName . "[" . $key . "]", $level + 1);
        } else {
            $GLOBALS['traverse_array'][] = '$' . $arrName . '[' . $key . '] = "' . $val . "\"\n";
        }
    }

    return 1;

}

?>
