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
// $Id: server.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//

require_once('SOAP/globals.php');
require_once('SOAP/Parser.php');
require_once('SOAP/Message.php');
require_once('SOAP/Value.php');

// make errors handle properly in windows
error_reporting(2039);

/**
*  SOAP::Server
* SOAP Server Class
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* @access   public
* @version  $Id: server.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package  SOAP::Client
* @author   Shane Caraveo <shane@php.net> Conversion to PEAR and updates
* @author   Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
class SOAP_Server {

    /**
    *
    * @var  array
    */    
    var $dispatch_map = array(); // create empty dispatch map
    var $dispatch_objects = array();
    var $soapobject = NULL;
    
    /**
    * Store debugging messages in $debug_str?
    * 
    * @var  boolean
    * @see  $debug_str, SOAP_Server()
    */
    var $debug_flag = SOAP_DEBUG;
    
    /**
    * Debugging messages
    *
    * @var  string
    * @see  $debug_flag, SOAP_Server()
    */
    var $debug_data = '';
    
    /**
    *
    * @var  string
    */
    var $headers = '';
    
    /**
    *
    * @var  string
    */
    var $request = '';
    
    /**
    *
    * @var  string  XML-Encoding
    */
    var $xml_encoding = 'UTF-8';
    
    /**
    * 
    * @var  boolean
    */
    var $fault = false;
    
    /**
    *
    * @var  string  fault-code
    */
    var $fault_code = '';
    
    /**
    *
    * @var  string  fault-string
    */
    var $fault_str = '';
    
    /**
    * 
    */
    var $fault_actor = '';
    
    var $result = 'successful'; // for logging interop results to db

    function SOAP_Server($debug = SOAP_DEBUG) {
        // turn on debugging?
        $this->debug_flag = $debug;
    }
    
    // parses request and posts response
    function service($data)
    {
        global $_ENV, $_SERVER;
        // if this is not a POST with Content-Type text/xml, try to return a WSDL file
        if ($_SERVER['REQUEST_METHOD'] != 'POST' ||
            strncmp($_ENV['HTTP_CONTENT_TYPE'], 'text/xml', 8) != 0) {
                // this is not possibly a valid soap request, try to return a WSDL file
                $this->makeFault('Server',"Invalid SOAP request");
                $response = $this->fault();
        } else {
            // $response is a soap_msg object
            $response = $this->parseRequest($data);
        }
        
        $this->debug("parsed request and got an object of this class '" . get_class($response) . "'");
        $this->debug('server sending...');

        // pass along the debug string
        if ($this->debug_flag) {
            $response->debug($this->debug_data);
        }
        $payload = $response->serialize();
        // print headers
        if ($this->fault) {
            //$header[] = "HTTP/1.0 500 Internal Server Error\r\n";
            $header[] = 'Status: 500 Internal Server Error\r\n';
        } else {
            //$header[] = "HTTP/1.0 200 OK\r\n";
            $header[] = 'Status: 200 OK\r\n';
        }

        $header[] = 'Server: ' . SOAP_LIBRARY_NAME . "\r\n";
        $header[] = 'Connection: Close\r\n';
        $header[] = "Content-Type: text/xml; charset=$this->xml_encoding\r\n";
        $header[] = 'Content-Length: ' . strlen($payload) . "\r\n\r\n";
        reset($header);
        foreach ($header as $hdr) {
            header($hdr);
        }
        $this->response = join("\n", $header) . $payload;
        print $payload;
    }
    
    function parseRequest($data='')
    {
        global $_ENV, $_SERVER;
        
        $this->debug('entering parseRequest() on ' . date('H:i Y-m-d'));
        $this->debug('request uri: ' . $_SERVER['PATH_INFO']);

        // get headers
        // get SOAPAction header
        if ($headers_array['SOAPAction']) {
            $this->SOAPAction = str_replace('"','',$_ENV['HTTP_SOAPACTION']);
            $this->service = $this->SOAPAction;
        }

        // get the character encoding of the incoming request
        if (ereg('=',$_ENV['HTTP_CONTENT_TYPE'])) {
            $enc = str_replace('"','',substr(strstr($_ENV['HTTP_CONTENT_TYPE'],'='),1));
            if (eregi("^(ISO-8859-1|US-ASCII|UTF-8)$",$enc)) {
                $this->xml_encoding = $enc;
            } else {
                $this->xml_encoding = 'us-ascii';
            }
        }
        $this->debug("got encoding: $this->xml_encoding");

        $this->request = $dump."\r\n\r\n".$data;
        // parse response, get soap parser obj
        $parser = new SOAP_Parser($data,$this->xml_encoding);
        // if fault occurred during message parsing
        if ($parser->fault()) {
            // parser debug
            $this->debug($parser->debug_data);
            $this->result = 'fault: error in msg parsing';
            $this->makeFault('Server',"error in msg parsing:\n".$parser->getResponse());

            // return soapresp
            return $this->fault();
        // else successfully parsed request into SOAP_Value object
        } else {
            // evaluate message, getting back a SOAP_Value object
            $this->debug('calling parser->getResponse()');
            $this->methodname = $parser->root_struct_name[0];
            $this->debug("method name: $this->methodname");

            // does method exist?
            if (!$this->methodname || !$this->validateMethod($this->methodname)) {
                // "method not found" fault here
                $this->debug("method '$this->methodname' not found!");
                $this->result = 'fault: method not found';
                $this->makeFault('Server',"method '$this->methodname' not defined in service '$this->service'");
                return $this->fault();
            }
            $this->debug("method '$this->methodname' exists");


            if (!$request_val = $parser->getResponse()) {
                return $this->fault();
            }
            // parser debug
            $this->debug($parser->debug_data);

            /* set namespaces
            if ($parser->namespaces['xsd'] != '') {
                //print 'got '.$parser->namespaces['xsd'];
                global $SOAP_namespaces;
                $this->XMLSchemaVersion = $parser->namespaces['xsd'];
                $tmpNS = array_flip($SOAP_namespaces);
                $tmpNS['xsd'] = $this->XMLSchemaVersion;
                $tmpNS['xsi'] = $this->XMLSchemaVersion.'-instance';
                $SOAP_namespaces = array_flip($tmpNS);
            }*/

            if (strcasecmp(get_class($request_val),'SOAP_Value')==0) {
                // verify that SOAP_Value objects in request match the methods signature
                if ($this->verifyMethod($request_val)) {
                    $this->debug("request data - name: $request_val->name, type: $request_val->type, value: $request_val->value");
                    // need to set special error detection inside the value class
                    // so as to differentiate between no params passed, and an error decoding
                    $request_data = $request_val->decode();
                    $this->debug($request_val->debug_data);
                    $this->debug("request data: $request_data");
                    $this->debug("about to call method '$this->methodname'");

                    // if there are parameters to pass
                    if ($request_data) {
                        // call method with parameters
                        $this->debug("calling '$this->methodname' with params");
                        if (is_object($this->soapobject)) {
                            $method_response = call_user_func_array(array(&$this->soapobject, $this->methodname),$request_data);
                        } else {
                            $method_response = call_user_func_array($this->methodname,$request_data);
                        }
                    } else {
                        // call method w/ no parameters
                        $this->debug("calling $this->methodname w/ no params");
                        if (is_object($this->soapobject)) {
                            $method_response = call_user_func(array(&$this->soapobject, $this->methodname));
                        } else {
                            $method_response = call_user_func($this->methodname);
                        }
                    }

                    $this->debug("done calling method: $this->methodname");

                    // if return val is SOAP_Message
                    if (strcasecmp(get_class($method_response),'SOAP_Message')==0) {
                        if (eregi('fault',$method_response->value->name)) {
                            $this->fault = true;
                        }
                        $return_msg = $method_response;
                    } else {
                        // if return val is SOAP_Value object
                        if (strcasecmp(get_class($method_response),'SOAP_Value')==0) {
                            $return_val = array($method_response);

                        // create soap_val object w/ return values from method, use method signature to determine type
                        } else {
                            $this->debug("creating new SOAP_Value to return, of type $this->return_type");
                            if (is_array($this->return_type) && is_array($method_response)) {
                                $i = 0;

                                foreach ($this->return_type as $key => $type) {
                                    if (is_numeric($key)) $key = 'item';
                                    $return_val[] = new SOAP_Value($key,$type,$method_response[$i++]);
                                }
                            } else {
                                $return_name = 'return';
                                if (is_array($this->return_type)) {
                                    $keys = array_keys($this->return_type);
                                    if (!is_numeric($keys[0])) $return_name = $keys[0];
                                    $values = array_values($this->return_type);
                                    $this->return_type = $values[0];
                                }
                                $return_val = array(new SOAP_Value($return_name,$this->return_type,$method_response));
                            }
                        }
                        if ($this->debug_flag) {
                            $this->debug($return_val->debug_data);
                        }

                        $this->debug("creating return soap_msg object: ".$this->methodname.'Response');
                        // response object is a soap_msg object
                        $return_msg =  new SOAP_Message($this->methodname.'Response',$return_val,"$this->service");
                    }

                    if ($this->debug_flag) {
                        $return_msg->debug_flag = true;
                    }

                    $this->result = 'successful';

                    return $return_msg;
                } else {
                    // debug
                    $this->debug('ERROR: request not verified against method signature');
                    $this->result = 'fault: request failed validation against method signature';

                    // return soapresp
                    return $this->fault();
                }
            } else {
                // debug
                $this->debug("ERROR: parser did not return SOAP_Value object: $request_val ".get_class($request_val));
                $this->result = "fault: parser did not return SOAP_Value object: $request_val";

                // return fault
                $this->makeFault('Server',"parser did not return SOAP_Value object: $request_val");
                return $this->fault();
            }
        }
    }
    
    function verifyMethod($request)
    {
        global $SOAP_typemap;

        //return true;
        $this->debug('entered verifyMethod() w/ request name: '.$request->name);
        $params = $request->value;

        // if there are input parameters required...
        if ($sig = $this->dispatch_map[$this->methodname]['in']) {
            $this->input_value = count($sig);
            $this->return_type = $this->getReturnType($this->methodname);
            if (is_array($params)) {
                if ($this->debug_flag) {
                    $this->debug('entered verifyMethod() with '.count($params).' parameters');

                    foreach ($params as $v) {
                        $this->debug("param '$v->name' of type '$v->type'");
                    }
                }
                // validate the number of parameters
                if (count($params) == count($sig)) {
                    $this->debug('got correct number of parameters: '.count($sig));

                    // make array of param types
                    foreach ($params as $param) {
                        $p[] = strtolower($param->type);
                    }
                    $sig_t = array_values($sig);
                    // validate each param's type
                    for($i=0; $i < count($p); $i++) {
                        // type not match
                        // if soap types do not match, we ok it if the mapped php types match
                        // this allows using plain php variables to work (ie. stuff like Decimal would fail otherwise)
                        // XXX we should do further validation of the value of the type
                        if (strcasecmp($sig_t[$i],$p[$i])!=0 &&
                            !(isset($SOAP_typemap[SOAP_XML_SCHEMA_VERSION][$sig_t[$i]]) &&
                            strcasecmp($SOAP_typemap[SOAP_XML_SCHEMA_VERSION][$sig_t[$i]],$SOAP_typemap[SOAP_XML_SCHEMA_VERSION][$p[$i]])==0)) {

                            $param = $params[$i];

                            $this->debug("mismatched parameter types: [{$sig_t[$i]}] != [{$p[$i]}]");
                            $this->makeFault('Client',"soap request contained mismatching parameters of name $param->name had type [{$p[$i]}], which did not match signature's type: [{$sig_t[$i]}], matched? ".(strcasecmp($sig_t[$i],$p[$i])));

                            return false;
                        }
                        $this->debug("parameter type match: $sig_t[$i] = $p[$i]");
                    }
                    return true;
                // oops, wrong number of paramss
                } else {
                    $this->debug('oops, wrong number of parameters!');
                    $this->makeFault('Client',"soap request contained incorrect number of parameters. method '$this->methodname' required ".count($sig).' and request provided '.count($params));

                    return false;
                }
            // oops, no params...
            } else {
                $this->debug("oops, no parameters sent! Method '$this->methodname' requires ".count($sig).' input parameters!');
                $this->makeFault('Client',"soap request contained incorrect number of parameters. method '$this->methodname' requires ".count($sig).' parameters, and request provided none');

                return false;
            }
        // no params
        } elseif (count($params)==0) {
            $this->input_values = 0;
            return true;
        }
        // we'll try it anyway
        return true;
    }
    
    // get string return type from dispatch map
    function getReturnType()
    {
        if (is_array($this->dispatch_map[$this->methodname]['out'])) {
            if (count($this->dispatch_map[$this->methodname]['out']) > 1) {
                $this->debug("got multiple return types from dispatch map: '$type'");
                return $this->dispatch_map[$this->methodname]['out'];
            }
            $type = array_shift($this->dispatch_map[$this->methodname]['out']);
            $this->debug("got return type from dispatch map: '$type'");
            return $type;
        }
        return false;
    }
    
    // dbg
    function debug($string)
    {
        if ($this->debug_flag) {
            $this->debug_data .= "SOAP_Server: $string\n";
        }
    }
    
    function validateMethod($methodname)
    {
        $this->soapobject =  NULL;
        /* if it's in our function list, ok */
        if (array_key_exists($methodname, $this->dispatch_map))
            return TRUE;
        
        /* if it's in an object, it's ok */
        foreach ($this->dispatch_objects as $obj) {
            if (method_exists(&$obj, $methodname)) {
                $this->soapobject =  &$obj;
                return TRUE;
            }
        }
        return FALSE;
    }
    
    function addObjectMap(&$obj)
    {
        $this->dispatch_objects[] = &$obj;
    }
    
    // add a method to the dispatch map
    function addToMap($methodname, $in, $out)
    {
        if (!function_exists($methodname)) {
            $this->makeFault('Server',"error mapping function\n");
            return $this->fault();
        }
        $this->dispatch_map[$methodname]['in'] = $in;
        $this->dispatch_map[$methodname]['out'] = $out;
        return TRUE;
    }
    
    // set up a fault
    function fault()
    {
        return new SOAP_Message('Fault',
            array(
                'faultcode' => $this->fault_code,
                'faultstring' => $this->fault_str,
                'faultactor' => $this->fault_actor,
                'faultdetail' => $this->fault_detail . $this->debug_data
            ),
            'http://schemas.xmlsoap.org/SOAP/envelope/'
        );
    }
    
    function makeFault($fault_code, $fault_string)
    {
        $this->fault_code = $fault_code;
        $this->fault_str = $fault_string;
        $this->fault = true;
    }
}
?>