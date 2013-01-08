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
// $Id: wsdl.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//
require_once 'SOAP/Base.php';
require_once 'SOAP/Fault.php';
require_once 'SOAP/globals.php';

DEFINE("WSDL_CACHE_MAX_AGE",12*60*60);
DEFINE("WSDL_CACHE_USE",0); // set to zero to turn off caching

/**
*  SOAP_WSDL
*  this class parses wsdl files, and can be used by SOAP::Client to properly register
* soap values for services
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* TODO:
*    add wsdl caching
*    implement IDL type syntax declaration so we can generate WSDL
*
* @access public
* @version $Id: wsdl.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package SOAP::Client
* @author Shane Caraveo <shane@php.net> Conversion to PEAR and updates
* @author Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
class SOAP_WSDL extends SOAP_Base
{
    var $tns = NULL;
    var $definition = array();
    var $namespaces = array();
    var $ns = array();
    var $xsd = SOAP_XML_SCHEMA_VERSION;
    var $complexTypes = array();
    var $messages = array();
    var $portTypes = array();
    var $bindings = array();
    var $imports = array();
    var $services = array();
    var $service = '';
    var $uri = '';
    
    function SOAP_WSDL($uri = false) {
        parent::SOAP_Base('WSDL');
        $this->uri = $uri;
        $this->parse($uri);
        reset($this->services);
        $this->service = key($this->services);
    }

    function set_service($service) {
        if (array_key_exists($service, $this->services)) {
            $this->service = $service;
        }
    }
    
    function parse($uri) {
        $parser = new SOAP_WSDL_Parser($uri, $this);
        if ($parser->fault) {
            $this->raiseSoapFault($parser->fault);
        }
    }
    
    function getEndpoint($portName)
    {
        return (isset($this->services[$this->service]['ports'][$portName]['location']))
                ? $this->services[$this->service]['ports'][$portName]['location']
                : $this->raiseSoapFault("no endpoint for port for $portName", $this->uri);
    }
    
    // find the name of the first port that contains an operation of name $operation
    function getPortName($operation)
    {
        foreach ($this->services[$this->service]['ports'] as $port => $portAttrs) {
            if ($this->bindings[$portAttrs['binding']]['operations'][$operation] != '') {
                return $port;
            }
        }
        return $this->raiseSoapFault("no operation $operation in wsdl", $this->uri);
    }
    
    function getOperationData($portName,$operation)
    {
        if (isset($this->services[$this->service]['ports'][$portName]['binding'])
            && $binding = $this->services[$this->service]['ports'][$portName]['binding']) {
            // get operation data from binding
            if (is_array($this->bindings[$binding]['operations'][$operation])) {
                $opData = $this->bindings[$binding]['operations'][$operation];
            }
            // get operation data from porttype
            $portType = $this->bindings[$binding]['type'];
            if (!$portType) {
                return $this->raiseSoapFault("no port type for binding $binding in wsdl " . $this->uri);
            }
            if (is_array($this->portTypes[$portType][$operation])) {
                $opData['parameterOrder'] = $this->portTypes[$portType][$operation]['parameterOrder'];
                $opData['input'] = array_merge($opData['input'], $this->portTypes[$portType][$operation]['input']);
                $opData['output'] = array_merge($opData['output'], $this->portTypes[$portType][$operation]['output']);
            }
            // message data from messages
            $inputMsg = $opData['input']['message'];
            $opData['input']['parts'] = $this->messages[$inputMsg];
            $outputMsg = $opData['output']['message'];
            $opData['output']['parts'] = $this->messages[$outputMsg];
            return $opData;
        }
        return $this->raiseSoapFault("no binding for port $portName in wsdl", $this->uri);
    }
    
    function matchMethod(&$operation) {
        // Overloading lowercases function names :(
        foreach ($this->services[$this->service]['ports'] as $port => $portAttrs) {
            foreach (array_keys($this->bindings[$portAttrs['binding']]['operations']) as $op) {
                if (strcasecmp($op, $operation) == 0) {
                    $operation = $op;
                }
            }
        }
    }
    
    function getSoapAction($portName, $operation)
    {
        if (isset($this->bindings[$this->services[$this->service]['ports'][$portName]['binding']]['operations'][$operation]['soapAction']) &&
            $soapAction = $this->bindings[$this->services[$this->service]['ports'][$portName]['binding']]['operations'][$operation]['soapAction']) {
            return $soapAction;
        }
        return false;
    }
    
    function getNamespace($portName, $operation)
    {
        if (isset($this->bindings[$this->services[$this->service]['ports'][$portName]['binding']]['operations'][$operation]['input']['namespace']) &&
            $namespace = $this->bindings[$this->services[$this->service]['ports'][$portName]['binding']]['operations'][$operation]['input']['namespace']) {
            return $namespace;
        }
        return false;
    }

    function getNamespaceAttributeName($namespace) {
        /* if it doesn't exist at first, flip the array and check again */
        if (!array_key_exists($namespace, $this->ns)) {
            $this->ns = array_flip($this->namespaces);
        }
        /* if it doesn't exist now, add it */
        if (!array_key_exists($namespace, $this->ns)) {
            return $this->addNamespace($namespace);
        }
        return $this->ns[$namespace];
    }
    
    function addNamespace($namespace) {
        if (array_key_exists($namespace, $this->ns)) {
            return $this->ns[$namespace];
        }
        $this->nsc++;
        $attr = 'ns'.$this->nsc;
        $this->namespaces['ns'.$this->nsc] = $namespace;
        $this->ns[$namespace] = $attr;
        return $attr;
    }
    
    function _validateString($string) {
        return true;
        echo "testing $string<br>\n";
        if (preg_match("/^[\w_:#\/]+$/",$string)) return true;
        echo "test failed<br>\n";
        return false;
    }
    
    /**
     * generateProxyCode
     * generates stub code from the wsdl that can be saved to a file, or eval'd into existence
     */
    function generateProxyCode($port = '') {
        if (!$port) {
            reset($this->services[$this->service]['ports']);
            $port = current($this->services[$this->service]['ports']);
        }
        // XXX currentPort is BAD
        $clienturl = $port['location']; 
        $classname = $this->service;

        if (!$this->_validateString($classname)) return NULL;
        
        $class = "class $classname extends SOAP_Client {\n".
        "    function $classname() {\n".
        "        \$this->SOAP_Client(\"$clienturl\", 0);\n".
        "    }\n";

        // get the binding, from that get the port type
        $primaryBinding = $port['binding']; //$this->services[$this->service]['ports'][$port['name']]["binding"];
        $primaryBinding = preg_replace("/^(.*:)/","",$primaryBinding);
        $portType = $this->bindings[$primaryBinding]['type'];
        $portType = preg_replace("/^(.*:)/","",$portType);
        
        // XXX currentPortType is BAD
        foreach ($this->portTypes[$portType] as $opname => $operation) {
            $args = "";
            $argarray = "";
            foreach ($operation["input"] as $argname => $argtype) {
                if ($argname == "message") {
                    $_msgtype = preg_replace("/^(.*:)/","",$argtype);
                    foreach ($this->messages[$_msgtype] as $_argname => $_argtype) {
                        if ($args) $args .= ", ";
                        $args .= "\$".$_argname;
                        if (!$this->_validateString($_argname)) return NULL;
                        if ($argarray) $argarray .= ", ";
                        $argarray .= "\"$_argname\"=>\$".$_argname;
                    }
                }
            }
            //$binding = $operation["binding"]["name"];
            $soapaction = $this->bindings[$primaryBinding]["operations"][$opname]["soapAction"];
            $namespace = $this->bindings[$primaryBinding]["operations"][$opname]["input"]["namespace"];
            
            // validate entries
            if (!$this->_validateString($opname)) return NULL;
            if (!$this->_validateString($namespace)) return NULL;
            if (!$this->_validateString($soapaction)) return NULL;
            
            $class .= "    function $opname($args) {\n".
            "        return \$this->call(\"$opname\", array($argarray), \"$namespace\", \"$soapaction\"); \n".
            "    }\n";
        }    
        $class .= "}\n";
        return $class;
    }

}

class SOAP_WSDL_Cache extends SOAP_Base
{
    function SOAP_WSDL_Cache() {
        parent::SOAP_Base('WSDLCACHE');
    }
    /**
     * _cacheDir
     * return the path to the cache, if it doesn't exist, make it
     */
    function _cacheDir() {
        $dir = getenv("WSDLCACHE");
        if (!$dir) $dir = "./wsdlcache";
        @mkdir($dir, 0700);
        return $dir;
    }
    
    /**
     * get
     * retreives file from cache if it exists, otherwise retreive from net,
     * add to cache, and return from cache
     */
    function get($wsdl_fname, $cache=0) {
        $cachename = SOAP_WSDL_Cache::_cacheDir() . "/" . md5($wsdl_fname);
        $cacheinfo = $cachename.".info";
        $cachename .= ".wsdl";
        $md5_wsdl = "";
        $file_data = '';
        if (WSDL_CACHE_USE && file_exists($cachename)) {
            $wf = fopen($cachename,"rb");
            if ($wf) {
                $file_data = fread($wf, filesize($cachename));
                $md5_wsdl = md5($file_data);
                fclose($wf);
            }
            if ($cache) {
                if ($cache != $md5_wsdl) {
                    return $this->raiseSoapFault("WSDL Checksum error!", $wsdl_fname);
                }
            } else {
                $fi = stat($cachename);
                $cache_mtime = $fi[8];
                #print cache_mtime, time()
                if ($cache_mtime + WSDL_CACHE_MAX_AGE < time()) {
                    # expired
                    $md5_wsdl = ""; # refetch
                }
            }
        }
        if (!$md5_wsdl) {
            $fd = @file($wsdl_fname);
            if (!$fd) {
                return $this->raiseSoapFault("Unable to retrieve WSDL", $wsdl_fname);
            }
            $file_data = join('',$fd);
            
            $md5_wsdl = md5($file_data);
            
            if (WSDL_CACHE_USE) {
                $fp = fopen($cachename, "wb");
                fwrite($fp, $file_data);
                fclose($fp);
            }
        }
        if (WSDL_CACHE_USE && $cache && $cache != $md5_wsdl) {
            return $this->raiseSoapFault("WSDL Checksum error!", $wsdl_fname);
        }
        return $file_data;
    }
    
}

class SOAP_WSDL_Parser extends SOAP_Base
{
    // define internal arrays of bindings, ports, operations, messages, etc.
    var $currentMessage;
    var $currentOperation;
    var $currentPortType;
    var $currentBinding;
    var $currentPort;
    // parser vars
    var $tns = NULL;
    var $soapns = array('soap');
    var $uri = '';
    var $soap_stack = array();
    var $wsdl = NULL;
    var $schema = '';
    var $schemaStatus = '';
    var $status = '';
    
    var $cache;
    
    // constructor
    function SOAP_WSDL_Parser($uri, &$wsdl) {
        parent::SOAP_Base('WSDLPARSER');
        $this->cache = new SOAP_WSDL_Cache();
        $this->uri = $uri;
        $this->wsdl = &$wsdl;
        $this->parse($uri);
    }
    
    function parse($uri) {
        // Check whether content has been read.
        // XXX implement caching
        #$fd = @file($uri);
        $fd = $this->cache->get($uri);
        if (PEAR::isError($fd)) {
            return $this->raiseSoapFault($fd);
        }

        // Create an XML parser.
        $parser = xml_parser_create();
        // Set the options for parsing the XML data.
        //xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1); 
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        // Set the object for the parser.
        xml_set_object($parser, $this);
        // Set the element handlers for the parser.
        xml_set_element_handler($parser, 'startElement', 'endElement');
        xml_set_character_data_handler($parser, 'characterData');
        //xml_set_default_handler($this->parser, 'defaultHandler');
    
        // Parse the XML file.
        if (!xml_parse($parser,$fd, true)) {
            $detail = sprintf('XML error on line %d: %s',
                                    xml_get_current_line_number($parser),
                                    xml_error_string(xml_get_error_code($parser)));
            return $this->raiseSoapFault("Unable to parse WSDL file $uri", $detail);
        }
        xml_parser_free($parser);
        return TRUE;
    }
    
    // start-element handler
    function startElement($parser, $name, $attrs) {
        global $SOAP_XMLSchema;
        
        // get element prefix
        $qname = new QName($name);
        if ($qname->ns) {
            $ns = $qname->ns;
            if ($ns && ((!$this->tns && strcasecmp($qname->name,'definitions') == 0) || $ns == $this->tns)) {
                $name = $qname->name;
            }
        }
        $this->currentTag = $qname->name;
        
        // find status, register data
        switch($this->status) {
        case 'types':
            $parent_tag = '';
            $stack_size = count($this->schema_stack);
            if ($stack_size > 0) {
                $parent_tag = $this->schema_stack[$stack_size-1];
            }

            switch($qname->name) {
            case 'schema':
                if (array_key_exists('targetNamespace', $attrs)) {
                    $this->schema = $this->wsdl->getNamespaceAttributeName($attrs['targetNamespace']);
                } else {
                    $this->schema = $this->wsdl->getNamespaceAttributeName($this->wsdl->tns);
                }
                $this->wsdl->complexTypes[$this->schema] = array();
            break;
            case 'complexType':
                if ($parent_tag == 'schema') {
                    $this->currentElement = $attrs['name'];
                    if ($attrs['base']) {
                        $qn = new QName($attrs['base']);
                        $this->wsdl->complexTypes[$this->schema][$this->currentElement]['base'] = $qn->name;
                        $this->wsdl->complexTypes[$this->schema][$this->currentElement]['baseNS'] = $qn->ns;
                    } else {
                        $this->wsdl->complexTypes[$this->schema][$this->currentElement]['base'] = 'Struct';
                    }
                    $this->schemaStatus = 'complexType';
                }
            break;
            case 'element':
                if (array_key_exists('type',$attrs)) {
                    $qname = new QName($attrs['type']);
                    $attrs['type'] = $qname->name;
                    $attrs['namespace'] = $this->wsdl->namespaces[$qname->ns];
                }
                if ($parent_tag == 'schema') {
                    $this->currentElement = $attrs['name'];
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement] = $attrs;
                    $this->schemaStatus = 'complexType';
                } else {
                    if ($this->wsdl->complexTypes[$this->schema][$this->currentElement]['order'] == 'sequence'
                        && $this->wsdl->complexTypes[$this->schema][$this->currentElement]['base'] == 'Array') {
                            $this->wsdl->complexTypes[$this->schema][$this->currentElement]['arrayType'] = $attrs['type'];
                    }
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['elements'][$attrs['name']] = $attrs;
                }
            break;
            case 'complexContent':
                    
            break;
            case 'restriction':
                if ($attrs['base']) {
                    $qn = new QName($attrs['base']);
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['base'] = $qn->name;
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['baseNS'] = $qn->ns;
                } else {
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['base'] = 'Struct';
                }
            break;
            case 'sequence':
                $this->wsdl->complexTypes[$this->schema][$this->currentElement]['order'] = 'sequence';
                if (!array_key_exists('type',$this->wsdl->complexTypes[$this->schema][$this->currentElement])) {
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['type'] = 'Array';
                }
            break;
            case 'all':
                $this->wsdl->complexTypes[$this->schema][$this->currentElement]['order'] = 'all';
                if (!array_key_exists('type',$this->wsdl->complexTypes[$this->schema][$this->currentElement])) {
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['type'] = 'Struct';
                }
            break;
            case 'attribute':
                $this->wsdl->complexTypes[$this->schema][$this->currentElement]['attrs'] = $attrs;
                if ($attrs['ref']) {
                    $q = new QName($attrs['ref']);
                    foreach ($attrs as $k => $v) {
                        if ($k != 'ref' && strstr($k, $q->name)) {
                            $vq = new QName($v);
                            $this->wsdl->complexTypes[$this->schema][$this->currentElement][$q->name] = $vq->name;
                        }
                    }
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['attrs'] = $attrs;
                } elseif ($attrs['name']) {
                    $this->wsdl->complexTypes[$this->schema][$this->currentElement]['attrs'][$attrs['name']] = $attrs;
                }
            break;
            }
            
            $this->schema_stack[] = $qname->name;
            
        break;
        case 'message':
            if ($qname->name == 'part') {
                $this->wsdl->messages[$this->currentMessage][$attrs['name']] = $attrs['type'];
            }
        break;
        case 'portType':
            switch($qname->name) {
            case 'operation':
                $this->currentOperation = $attrs['name'];
                $this->wsdl->portTypes[$this->currentPortType][$attrs['name']]['parameterOrder'] = $attrs['parameterOrder'];
            break;
            default:
                $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$name]= $attrs;
                $qn = new QName($attrs['message']);
                $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$name]['message'] = $qn->name;
                $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$name]['namespace'] = $qn->ns;
            break;
            }
        break;
        case 'binding':
            switch($qname->name) {
                case 'binding':
                    if (in_array($qname->ns, $this->soapns)) {
                        $this->wsdl->bindings[$this->currentBinding] = array_merge($this->wsdl->bindings[$this->currentBinding],$attrs);
                    }
                break;
                case 'operation':
                    if (in_array($qname->ns, $this->soapns)) {
                        $this->wsdl->bindings[$this->currentBinding]['operations'][$this->currentOperation]['soapAction'] = $attrs['soapAction'];
                    } else {
                        $this->currentOperation = $attrs['name'];
                        $this->wsdl->bindings[$this->currentBinding]['operations'][$attrs['name']] = array();
                    }
                break;
                case 'input':
                    $this->opStatus = 'input';
                break;
                case 'body':
                    if (in_array($qname->ns, $this->soapns)) {
                        $this->wsdl->bindings[$this->currentBinding]['operations'][$this->currentOperation][$this->opStatus] = $attrs;
                    }
                break;
                case 'output':
                    $this->opStatus = 'output';
                break;
            }
        break;
        case 'service':
            switch($qname->name) {
            case 'port':
                $this->currentPort = $attrs['name'];
                $this->wsdl->services[$this->currentService]['ports'][$attrs['name']] = $attrs;
                // XXX hack to deal with binding namespaces
                $qn = new QName($attrs['binding']);
                $this->wsdl->services[$this->currentService]['ports'][$attrs['name']]['binding'] = $qn->name;
                $this->wsdl->services[$this->currentService]['ports'][$attrs['name']]['namespace'] = $qn->ns;
            break;
            case 'address':
                if (in_array($qname->ns, $this->soapns)) {
                    $this->wsdl->services[$this->currentService]['ports'][$this->currentPort]['location'] = $attrs['location'];
                }
            break;
            }
        }
        // set status
        switch($qname->name) {
        case 'import':
            //XXX
            $import = '';
            if ($attrs['location']) {
                $result = $this->parse($attrs['location'], $this->wsdl);
                if (PEAR::isError($result)) {
                    return $result;
                }
            }
            
            $this->wsdl->imports[$attrs['namespace']] = array(
                        'location' => $attrs['location'],
                        'namespace' => $attrs['namespace']);
            $this->currentImport = $attrs['namespace'];
            $this->status = 'import';
        case 'types':
            $this->status = 'types';
        break;
        case 'message':
            $this->status = 'message';
            $this->wsdl->messages[$attrs['name']] = array();
            $this->currentMessage = $attrs['name'];
        break;
        case 'portType':
            $this->status = 'portType';
            $this->wsdl->portTypes[$attrs['name']] = array();
            $this->currentPortType = $attrs['name'];
        break;
        case 'binding':
            if ($qname->ns && $qname->ns != $this->tns) break;
            $this->status = 'binding';
            $this->currentBinding = $attrs['name'];
            $qn = new QName($attrs['type']);
            $this->wsdl->bindings[$this->currentBinding]['type'] = $qn->name;
            $this->wsdl->bindings[$this->currentBinding]['namespace'] = $qn->ns;
        break;
        case 'service':
            $this->currentService = $attrs['name'];
            $this->wsdl->services[$this->currentService]['ports'] = array();
            $this->status = 'service';
        break;
        case 'definitions':
            $this->wsdl->definition = $attrs;
            foreach ($attrs as $key => $value) {
                if (strstr($key,'xmlns:') !== FALSE) {
                    $qn = new QName($key);
                    $this->wsdl->namespaces[$qn->name] = $value;
                    if ($key == 'targetNamespace' ||
                        strcasecmp($value,SOAP_SCHEMA)==0) {
                        $this->soapns[] = strtolower($qn->name);
                    } else
                    if (in_array($value, $SOAP_XMLSchema)) {
                        $this->wsdl->xsd = $value;
                    }
                }
            }
            if (isset($ns) && $ns) {
                $namespace = 'xmlns:'.$ns;
                if (!$this->wsdl->definition[$namespace]) {
                    return $this->raiseSoapFault("parse error, no namespace for $namespace",$this->uri);
                }
                $this->tns = $ns;
            }
        break;
        }
    }
    
    
    // end-element handler
    function endElement($parser, $name)
    {
        if ($name == 'schema')
            $this->schema = '';
        if ($this->schema) {
            array_pop($this->schema_stack);
            if (count($this->schema_stack) <= 1) {
                /* correct the type for sequences with multiple elements */
                if ($this->wsdl->complexTypes[$this->schema][$this->currentElement]['type'] == 'Array'
                    && array_key_exists('elements',$this->wsdl->complexTypes[$this->schema][$this->currentElement])
                    && count($this->wsdl->complexTypes[$this->schema][$this->currentElement]['elements']) > 1) {
                        $this->wsdl->complexTypes[$this->schema][$this->currentElement]['type'] = 'Struct';
                }
                $this->currentElement = '';
            }
        }
        
        // position of current element is equal to the last value left in depth_array for my depth
        //$pos = $this->depth_array[$this->depth];
        // bring depth down a notch
        //$this->depth--;
    }
    
    // element content handler
    function characterData($parser, $data)
    {
        # store the documentation in the WSDL file
        if ($this->currentTag == 'documentation') {
            if ($this->status ==  'service') {
                $this->wsdl->services[$this->currentService][$this->currentTag] .= $data;
            } else if ($this->status ==  'portType') {
                if ($this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag])
                    $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag] .= data;
                else
                    $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag] = data;
            } else if ($this->status ==  'binding') {
                if ($this->wsdl->bindings[$this->currentBinding][$this->currentTag])
                    $this->wsdl->bindings[$this->currentBinding][$this->currentTag] .= data;
                else
                    $this->wsdl->bindings[$this->currentBinding][$this->currentTag] = data;
            } else if ($this->status ==  'message') {
                if ($this->wsdl->messages[$this->currentMessage][$this->currentTag])
                    $this->wsdl->messages[$this->currentMessage][$this->currentTag] .= data;
                else
                    $this->wsdl->messages[$this->currentMessage][$this->currentTag] = data;
            } else if ($this->status ==  'operation') {
                if ($this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag])
                    $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag] .= data;
                else
                    $this->wsdl->portTypes[$this->currentPortType][$this->currentOperation][$this->currentTag] = data;
            }
        }
    }
}


?>