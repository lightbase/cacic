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
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more   |
// | Authors: Dietrich Ayala <dietrich@ganx4.com> Original Author         |
// +----------------------------------------------------------------------+
//
// $Id: parser.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//

require_once('SOAP/globals.php');
require_once('SOAP/Value.php');

/**
* SOAP Parser
* this class is used by SOAP::Message and SOAP::Server to parse soap packets
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* @access public
* @version $Id: parser.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package SOAP::Parser
* @author Shane Caraveo <shane@php.net> Conversion to PEAR and updates
* @author Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
class SOAP_Parser
{
    var $status = '';
    var $position = 0;
    var $pos_stat = 0;
    var $depth = 0;
    var $default_namespace = '';
    var $namespaces = array();
    var $message = array();
    var $fault = false;
    var $fault_code = '';
    var $fault_str = '';
    var $fault_detail = '';
    var $depth_array = array();
    var $debug_flag = SOAP_DEBUG;
    var $debug_data = '';
    var $previous_element = '';
    var $soapresponse = NULL;
    var $parent = 0;
    var $root_struct_name = array();
    var $curent_root_struct_name = '';
    var $entities = array ( '&' => '&amp;', '<' => '&lt;', '>' => '&gt;', "'" => '&apos;', '"' => '&quot;' );
    var $xml = '';
    var $xml_encoding = '';
    var $root_struct = array();
    var $curent_root_struct = 0;
    var $references = array();
    var $need_references = array();
    var $XMLSchemaVersion;
    
    function SOAP_Parser($xml, $encoding = 'UTF-8')
    {
        $this->setSchemaVersion(SOAP_XML_SCHEMA_VERSION);
        //global $soapTypes;
        //$this->soapTypes = $soapTypes;
        $this->xml = $xml;
        $this->xml_encoding = $encoding;
        
        // determines where in the message we are (envelope,header,body,method)
        // Check whether content has been read.
        if (!empty($xml)) {
            $this->debug('Entering SOAP_Parser()');

            //$this->debug("DATA DUMP:\n\n$xml");
            // Create an XML parser.
            $parser = xml_parser_create($this->xml_encoding);
            // Set the options for parsing the XML data.
            //xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
            xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
            // Set the object for the parser.
            xml_set_object($parser, &$this);

            // Set the element handlers for the parser.
            xml_set_element_handler($parser, 'startElement','endElement');
            xml_set_character_data_handler($parser,'characterData');
            xml_set_default_handler($parser, 'defaultHandler');

            // Parse the XML file.
            if (!xml_parse($parser,$xml,true)) {
                // Display an error message.
                $this->debug(sprintf('XML error on line %d: %s',
                xml_get_current_line_number($parser),
                xml_error_string(xml_get_error_code($parser))));
                $this->fault = true;
            } else {
                // get final value
                $this->soapresponse = $this->buildResponse($this->root_struct[0]);
            }
            xml_parser_free($parser);
        } else {
            $this->debug('xml was empty, didn\'t parse!');
        }
    }
    
    function setSchemaVersion($schemaVersion)
    {
        global $SOAP_namespaces;
        $this->XMLSchemaVersion = $schemaVersion;
        $tmpNS = array_flip($SOAP_namespaces);
        $tmpNS['xsd'] = $this->XMLSchemaVersion;
        $tmpNS['xsi'] = $this->XMLSchemaVersion.'-instance';
        $SOAP_namespaces = array_flip($tmpNS);
    }
    
    // loop through msg, building response structures
    function buildResponse($pos)
    {
        $response = NULL;

        if ($this->message[$pos]['children'] != '') {
            $this->debug('children string = '.$this->message[$pos]['children']);
            $children = explode('|',$this->message[$pos]['children']);
            $this->debug('it has '.count($children).' children');

            foreach ($children as $c => $child_pos) {
                //$this->debug("child pos $child_pos: ".$this->message[$child_pos]['name']);
                if ($this->message[$child_pos]['type'] != NULL) {
                    $this->debug('entering buildResponse() for '.$this->message[$child_pos]['name'].", array pos $c, pos: $child_pos");
                    $response[] = $this->buildResponse($child_pos);
                }
            }
            if (array_key_exists('arraySize',$this->message[$pos])) {
                $ardepth = count($this->message[$pos]['arraySize']);
                if ($ardepth > 1) {
                    $ar = array_pad(array(), $ardepth, 0);
                    if (array_key_exists('arrayOffset',$this->message[$pos])) {
                        for ($i = 0; $i < $ardepth; $i++) {
                            $ar[$i] += $this->message[$pos]['arrayOffset'][$i];
                        }
                    }
                    $elc = count($response);
                    for ($i = 0; $i < $elc; $i++) {
                        $al = "\$newresp";
                        for ($ad = 0; $ad < $ardepth; $ad++) {
                            $al .= "[".($ar[$ad])."]";
                        }
                        eval("$al = \$response[".$i."];");
                        
                        # increment our array pointers
                        $ad = $ardepth - 1;
                        $ar[$ad]++;
                        while ($ad > 0 && $ar[$ad] >= $this->message[$pos]['arraySize'][$ad]) {
                            $ar[$ad] = 0;
                            $ad--;
                            $ar[$ad]++;
                        }
                    }
                    $response = $newresp;
                } else if ($this->message[$pos]['arrayOffset'][0] > 0) {
                    # check for padding
                    $pad = $this->message[$pos]['arrayOffset'][0]+count($response)*-1;
                    $response = array_pad($response,$pad,NULL);
                }
            }
        }
        // add current node's value
        if ($response) {
            #print "Parser creating: {$this->message[$pos]["name"]} type: {$this->message[$pos]["type"]}\n";
            $response = new SOAP_Value($this->message[$pos]["name"], $this->message[$pos]["type"] , $response);
        } else {
            #print "Parser creating: {$this->message[$pos]["name"]} type: {$this->message[$pos]["type"]}\n";
            $this->debug('inside buildresponse: creating SOAP_Value '.$this->message[$pos]['name'].' of type '.$this->message[$pos]['type'].' and value: '.$this->message[$pos]['cdata']);
            $response = new SOAP_Value($this->message[$pos]['name'], $this->message[$pos]['type'] , $this->message[$pos]['cdata']);
        }

        return $response;
    }
    
    // start-element handler
    function startElement($parser, $name, $attrs)
    {
        // position in a total number of elements, starting from 0
        // update class level pos
        $pos = $this->position++;

        // and set mine
        $this->message[$pos]['pos'] = $pos;

        // parent/child/depth determinations
        
        // depth = how many levels removed from root?
        // set mine as current global depth and increment global depth value
        $this->message[$pos]['depth'] = $this->depth++;
        
        // else add self as child to whoever the current parent is
        if ($pos != 0) {
            $this->message[$this->parent]['children'] .= "|$pos";
        }

        // set my parent
        $this->message[$pos]['parent'] = $this->parent;

        // set self as current value for this depth
        $this->depth_array[$this->depth] = $pos;
        // set self as current parent
        $this->parent = $pos;
        
        // set status
        if (preg_match("/:Envelope$/i",$name)) {
            $this->status = 'envelope';
        } elseif (preg_match("/:Header$/i",$name)) {
            $this->status = 'header';
        } elseif (preg_match("/:Body$/i",$name)) {
            $this->status = 'body';
        // set method
        } elseif ($this->status == 'body') {
            // is this element allowed to be a root?
            // XXX this needs to be optimized, we loop through attrs twice now
            $can_root = TRUE;
            foreach ($attrs as $key => $value) {
                if (preg_match('/:root$/i', $key) && !$value) {
                    $can_root = FALSE;
                }
            }

            $this->status = 'method';

            if ($can_root) {
                if (strpos($name,':') !== false) {
                    $this->root_struct_name[] = $this->curent_root_struct_name = substr(strrchr($name,':'),1);
                } else {
                    $this->root_struct_name[] = $this->curent_root_struct_name = $name;
                }

                $this->root_struct[] = $this->curent_root_struct = $pos;
                $this->message[$pos]['type'] = 'Struct';
            }
        }

        // set my status
        $this->message[$pos]['status'] = $this->status;
        
        // set name
        $this->message[$pos]['name'] = htmlspecialchars($name);

        // set attrs
        $this->message[$pos]['attrs'] = $attrs;

        // get namespace
        if (strpos($name,':') !== false) {
            $namespace = substr($name,0,strpos($name,':'));
            $this->message[$pos]['namespace'] = $namespace;
            $this->default_namespace = $namespace;
        } else {
            $this->message[$pos]['namespace'] = $this->default_namespace;
        }

        // loop through atts, logging ns and type declarations
        foreach ($attrs as $key => $value) {
            // if ns declarations, add to class level array of valid namespaces
            if (preg_match('/^xmlns:/i',$key)) {
                $prefix = substr(strrchr($key,':'),1);

                if ($prefix == 'xsd') {
                    $this->setSchemaVersion($value);
                }

                $this->namespaces[substr(strrchr($key,':'),1)] = $value;

                // set method namespace
                # XXX unused???
                #if ($name == $this->curent_root_struct_name) {
                #    $this->methodNamespace = $value;
                #}
            // if it's a type declaration, set type
            } elseif (preg_match('/:type$/i',$key)) {
                $type = substr(strrchr($value,':'),1);

                if (!$type) $type = $value;
                $this->message[$pos]['type'] = $type;
                #print "set type for {$this->message[$pos]['name']} to {$this->message[$pos]['type']}\n";
                // should do something here with the namespace of specified type?
            } elseif (preg_match('/:arrayType$/i',$key)) {
                $this->message[$pos]['type'] = 'Array';
                $type = substr(strrchr($value,':'),1);
                if (!$type) $type = $value;
                $sa = strpos($type,'[');
                if ($sa > 0) {
                    $this->message[$pos]['arraySize'] = split(',',substr($type,$sa+1, strlen($type)-$sa-2));
                    $type = substr($type, 0, $sa);
                }
                $this->message[$pos]['arrayType'] = $type;
            } elseif (preg_match('/:offset$/i',$key)) {
                $this->message[$pos]['arrayOffset'] = split(',',substr($value, 1, strlen($value)-2));
            } elseif ($key == 'id') {
                # save id to reference array
                $this->references[$value] = $pos;
                $this->message[$pos]['id'] = $value;
            } elseif ($key == 'href' && $value[0] == '#') {
                $ref = substr($value, 1);
                if (array_key_exists($ref,$this->references)) {
                    # cdata, type, inval
                    $ref_pos = $this->references[$ref];
                    $this->message[$pos]['cdata'] = &$this->message[$ref_pos]['cdata'];
                    $this->message[$pos]['type'] = &$this->message[$ref_pos]['type'];
                    $this->message[$pos]['inval'] = &$this->message[$ref_pos]['inval'];
                } else {
                    # reverse reference, store in 'need reference'
                    $this->need_references[$ref][] = $pos;
                }
            }
        }
    }
    
    // end-element handler
    function endElement($parser, $name)
    {
        // position of current element is equal to the last value left in depth_array for my depth
        $pos = $this->depth_array[$this->depth];
        // bring depth down a notch
        $this->depth--;
        
        // get type if not explicitly declared in an xsi:type attribute
        // XXX check on integrating wsdl validation here
        if ($this->message[$pos]['type'] == '') {
            if ($this->message[$pos]['children'] != '') {
                $this->message[$pos]['type'] = 'Struct';
            } else {
                $parent = $this->message[$pos]['parent'];
                if ($this->message[$parent]['type'] == 'Array' &&
                  array_key_exists('arrayType', $this->message[$parent])) {
                    $this->message[$pos]['type'] = $this->message[$parent]['arrayType'];
                } else {
                    $this->message[$pos]['type'] = 'string';
                }
            }
        }
        
        // set eval str start if it has a valid type and is inside the method
        if ($pos >= $this->curent_root_struct) {
            $this->message[$pos]['inval'] = 'true';
        }
        
        // if in the process of making a soap_val, close the parentheses and move on...
        if ($this->message[$pos]['inval'] == 'true') {
            $this->message[$pos]['inval'] == 'false';
        }

        // if tag we are currently closing is the method wrapper
        if ($pos == $this->curent_root_struct) {
            $this->status = 'body';
        } elseif (stristr($name,':Body')) {
            $this->status = 'header';
        } elseif (stristr($name,':Header')) {
            $this->status = 'envelope';
        }

        // set parent back to my parent
        $this->parent = $this->message[$pos]['parent'];
        $this->debug("parsed $name end, type '".$this->message[$pos]['type']."' children = ".$this->message[$pos]['children']);
        #print ("parsed $name end, type '".$this->message[$pos]['type']."' children = ".$this->message[$pos]['children']."\n");
        
        # handle any reverse references now
        $idref = $this->message[$pos]['id'];

        if ($idref != '' && array_key_exists($idref,$this->need_references)) {
            foreach ($this->need_references[$idref] as $ref_pos) {
                #XXX is this stuff there already?
                $this->message[$ref_pos]['children'] = &$this->message[$pos]['children'];
                $this->message[$ref_pos]['cdata'] = &$this->message[$pos]['cdata'];
                $this->message[$ref_pos]['type'] = &$this->message[$pos]['type'];
                $this->message[$ref_pos]['inval'] = &$this->message[$pos]['inval'];
            }
            # wipe out our waiting list
            $this->need_references[$idref] = array();
        }
    }
    
    // element content handler
    function characterData($parser, $data)
    {
        $pos = $this->depth_array[$this->depth];
        $this->message[$pos]['cdata'] .= $data;
    }
    
    // default handler
    function defaultHandler($parser, $data)
    {
        //$this->debug("DEFAULT HANDLER: $data");
    }
    
    // function to check fault status
    function fault()
    {
        if ($this->fault) {
            return true;
        } else {
            return false;
        }
    }
    
    // have this return a soap_val object
    function getResponse()
    {
        if ($this->soapresponse) {
            return $this->soapresponse;
        } else {
            $this->debug('ERROR: did not successfully eval the msg');
            $this->fault = true;

            return new SOAP_Value('Fault','Struct', array(new SOAP_Value('faultcode','string','SOAP-ENV:Parser'), new SOAP_Value('faultstring','string',"couldn't build response")));
        }
    }
    
    function debug($string)
    {
        if ($this->debug_flag) {
            $this->debug_data .= 'SOAP_Parser: ' . preg_replace("/>/","/>\r\n/", $string) . "\n";
        }
    }
    
    function decodeEntities($text)
    {
        foreach ($this->entities as $entity => $encoded) {
            $text = str_replace($encoded,$entity,$text);
        }
        return $text;
    }
}

/*
$testtext = '<?php xml version=\'1.0\' encoding=\'UTF-8\'?>
<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/SOAP/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<SOAP-ENV:Body>
<ns1:echoStringResponse xmlns:ns1="http://soapinterop.org/" SOAP-ENV:encodingStyle="http://schemas.xmlsoap.org/SOAP/encoding/">
<return xsi:type="xsd:string">blah</return>
</ns1:echoStringResponse>

</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
';

$soapmsg = new SOAP_Parser($testtext);
$return = $soapmsg->getResponse();
print_r($return);
$returnArray = $return->decode();
print_r($returnArray);
*/
?>