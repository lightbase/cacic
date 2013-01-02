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
// $Id: Client.php,v 1.16 2002/03/21 02:13:29 shane Exp $
//

require_once 'Value.php';
require_once 'Base.php';
require_once 'Transport.php';
require_once 'Message.php';
require_once 'WSDL.php';
require_once 'Fault.php';

/**
*  SOAP Client Class
* this class is the main interface for making soap requests
*
* basic usage: 
*   $soapclient = new SOAP_Client( string path [ , boolean wsdl] );
*   echo $soapclient->call( string methodname [ , array parameters] );
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* @access   public
* @version  $Id: Client.php,v 1.16 2002/03/21 02:13:29 shane Exp $
* @package  SOAP::Client
* @author   Shane Caraveo <shane@php.net> Conversion to PEAR and updates
* @author   Stig Bakken <ssb@fast.no> Conversion to PEAR
* @author   Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
class SOAP_Client extends SOAP_Base
{
    
    /**
    * SOAP fault code
    * 
    * @var  mixed
    */    
    var $faultcode = '';
    
    /**
    * SOAP fault string
    * 
    * @var  mixed
    */
    var $faultstring = '';
    
    /**
    * SOAP fault details
    * 
    * @var  mixed
    */
    var $faultdetail = '';
    
    /**
    * Communication endpoint.
    *
    * Currently the following transport formats are supported:
    *  - HTTP
    *  - SMTP
    * 
    * Example endpoints:
    *   http://www.example.com/SOAP/server.php
    *   https://www.example.com/SOAP/server.php
    *   mailto:soap@example.com
    *
    * @var  string
    * @see  SOAP_Client()
    */
    var $endpoint = '';
    
    /**
    * 
    */
    var $portName = '';
    
    
    /**
    * Endpoint type 
    *
    * @var  string  e.g. wdsl
    */
    var $endpointType = '';
    
    /**
    * WDSL object
    *
    * @var  object  SOAP_WDSL
    */
    var $wsdl = NULL;
    
    
    /**
    * SOAP_Client constructor
    *
    * @param string endpoint (URL)
    * @param boolean wsdl (true if endpoint is a wsdl file)
    * @param string portName
    * @access public
    */
    function SOAP_Client($endpoint, $wsdl = false, $portName = false)
    {
        parent::SOAP_Base('Client');
        $this->endpoint = $endpoint;
        $this->portName = $portName;
        
        // make values
        if ($wsdl) {
            $this->endpointType = 'wsdl';
            // instantiate wsdl class
            $this->wsdl = new SOAP_WSDL($this->endpoint);
            if ($this->wsdl->fault) {
                $this->raiseSoapFault($this->wsdl->fault);
            }
        }
    }
    
    /**
    * SOAP_Client::call
    *
    * @param string method
    * @param array  params
    * @param string namespace  (not required if using wsdl)
    * @param string soapAction   (not required if using wsdl)
    *
    * @return array of results
    * @access public
    */
    function call($method, $params = array(), $namespace = false, $soapAction = false)
    {
        $this->fault = null;

        if ($this->endpointType == 'wsdl') {
            $this->setSchemaVersion($this->wsdl->xsd);
            // get portName
            if (!$this->portName) {
                $this->portName = $this->wsdl->getPortName($method);
                if (PEAR::isError($this->portName)) {
                    return $this->raiseSoapFault($this->portName);
                }
            }
            $namespace = $this->wsdl->getNamespace($this->portName, $method);

            // get endpoint
            $this->endpoint = $this->wsdl->getEndpoint($this->portName);
            if (PEAR::isError($this->endpoint)) {
                return $this->raiseSoapFault($this->endpoint);
            }
            $this->debug("endpoint: $this->endpoint");
            $this->debug("portName: $this->portName");
            // get operation data
            $opData = $this->wsdl->getOperationData($this->portName, $method);
            if (PEAR::isError($opData)) {
                return $this->raiseSoapFault($opData);
            }
            $soapAction = $opData['soapAction'];

            // set input params
            $nparams = array();
            if (count($opData['input']['parts']) > 0) {
                $i = 0;
                reset($params);
                foreach ($opData['input']['parts'] as $name => $type) {
                    if (isset($params[$name])) {
                        $nparams[$name] = $params[$name];
                    } else {
                        // XXX assuming it's an array, not a hash
                        // XXX this is pretty pethetic, but "fixes" a problem where
                        // paremeter names do not match correctly
                        $nparams[$name] = current($params);
                        next($params);
                    }
                    if (gettype($nparams[$name]) != 'object' &&
                        get_class($nparams[$name]) != 'soap_value') {
                        // type is a qname likely, split it apart, and get the type namespace from wsdl
                        $qname = new QName($type);
                        if ($qname->ns) 
                            $type_namespace = $this->wsdl->namespaces[$qname->ns];
                        else
                            $type_namespace = NULL;
                        $type = $qname->name;
                        $nparams[$name] = new SOAP_Value($name, $type, $nparams[$name], $namespace, $type_namespace, $this->wsdl);
                    }
                }
            }
            $params = $nparams;
        }
        
        
        $this->debug("soapAction: $soapAction");
        $this->debug("namespace: $namespace");
        
        // make message
        $soapmsg = new SOAP_Message($method, $params, $namespace, NULL, $this->wsdl);
        if ($soapmsg->fault) {
            return $this->raiseSoapFault($soapmsg->fault);
        }

        // serialize the message
        $soap_data = $soapmsg->serialize();
        $this->debug("soap_data " . $soap_data);
        if (PEAR::isError($soap_data)) {
            return $this->raiseSoapFault($soap_data);
        }
        $this->debug('<xmp>' . $soap_data . '</xmp>');
        
        // instantiate client
        $dbg = "calling server at '$this->endpoint'...";
        
        $soap_transport = new SOAP_Transport($this->endpoint, $this->debug_flag);
        if ($soap_transport->fault) {
            return $this->raiseSoapFault($soap_transport->fault);
        }
        
        $this->debug($dbg . 'instantiated client successfully');
        $this->debug("endpoint: $this->endpoint<br>\n");

        // send
        $dbg = "sending msg w/ soapaction '$soapAction'...";
       
        // send the message
        $this->response = $soap_transport->send($soap_data, $soapAction);
        if ($soap_transport->fault) {
            return $this->raiseSoapFault($this->response);
        }

        // parse the response
        $return = $soapmsg->parseResponse($this->response);
        $this->debug($soap_transport->debug_data);
        $this->debug($dbg . 'sent message successfully and got a(n) ' . gettype($return) . ' back');

        // check for valid response
        if (PEAR::isError($return)) {
            return $this->raiseSoapFault($return);
        } else if (strcasecmp(get_class($return), 'SOAP_Value') != 0) {
            return $this->raiseSoapFault("didn't get SOAP_Value object back from client");
        }

        // decode to native php datatype
        $returnArray = $return->decode();
        // fault?
        if (PEAR::isError($returnArray)) {
            return $this->raiseSoapFault($returnArray);
        }
        if (is_array($returnArray)) {
            if (isset($returnArray['faultcode']) || isset($returnArray['SOAP-ENV:faultcode'])) {
                foreach ($returnArray as $k => $v) {
                    if (stristr($k,'faultcode')) $this->faultcode = $v;
                    if (stristr($k,'faultstring')) $this->faultstring = $v;
                    if (stristr($k,'faultdetail')) $this->faultdetail = $v;
                    if (stristr($k,'faultactor')) $this->faultactor = $v;
                }
                return $this->raiseSoapFault($this->faultstring, $this->faultdetail, $this->faultactor, $this->faultcode);
            }
            // return array of return values
            if (count($returnArray) == 1) {
                return array_shift($returnArray);
            }
            return $returnArray;
        }
        return $returnArray;
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
    
    /**
    * SOAP_Client::__call
    *
    * Overload extension support
    * if the overload extension is loaded, you can call the client class
    * with a soap method name
    * $soap = new SOAP_Client(....);
    * $value = $soap->getStockQuote('MSFT');
    *
    * @param string method
    * @param array  args
    * @param string retur_value
    *
    * @return boolean
    * @access public
    */
    function __call($method, $args, &$return_value)
    {
        if (!$this->wsdl) return FALSE;
        #$return_value = call_user_func_array(array(&$this, 'my_' . $method), $args);
        $this->wsdl->matchMethod($method);
        $return_value = $this->call($method, $args);
        return TRUE;
    }
    
}

if (extension_loaded('overload')) {
    overload('SOAP_Client');
}
?>