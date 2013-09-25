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
// | Authors: Shane Caraveo <Shane@Caraveo.com>                           |
// +----------------------------------------------------------------------+
//
// $Id: http.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//

require_once 'SOAP/globals.php';
require_once 'SOAP/Base.php';

/**
*  HTTP Transport for SOAP
*
* @access public
* @version $Id: http.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package SOAP::Transport::HTTP
* @author Shane Caraveo <shane@php.net>
*/
class SOAP_Transport_HTTP extends SOAP_Base
{
    
    /**
    * Basic Auth string
    *
    * @var  string
    */
    var $credentials = '';
    
    /**
    *
    * @var  int connection timeout in seconds - 0 = none
    */
    var $timeout = 4;
    
    /**
    * Error number
    * 
    * @var  int
    */
    var $errno = 0;
    
    /**
    * Error message
    * 
    * @var  string
    */
    var $errmsg = '';
    
    /**
    * Array containing urlparts - parse_url()
    * 
    * @var  mixed
    */
    var $urlparts = NULL;
    
    /**
    * Connection endpoint - URL
    *
    * @var  string
    */
    var $url = '';
    
    /**
    * Incoming payload
    *
    * @var  string
    */
    var $incoming_payload = '';
    
    /**
    * HTTP-Request User-Agent
    *
    * @var  string
    */
    var $_userAgent = SOAP_LIBRARY_NAME;

    /**
    * SOAP_Transport_HTTP Constructor
    *
    * @param string $URL    http url to soap endpoint
    *
    * @access public
    */
    function SOAP_Transport_HTTP($URL)
    {
        parent::SOAP_Base('HTTP');
        $this->urlparts = @parse_url($URL);
        $this->url = $URL;
    }
    
    /**
    * send and receive soap data
    *
    * @param string &$msg       outgoing post data
    * @param string $action      SOAP Action header data
    * @param int $timeout  socket timeout, default 0 or off
    *
    * @return string|fault response
    * @access public
    */
    function &send(&$msg, $action = '', $timeout = 0)
    {
        if (!$this->_validateUrl()) {
            return $this->raiseSoapFault($this->errmsg);
        }
        
        if ($timeout) 
            $this->timeout = $timeout;
    
        if (strcasecmp($this->urlparts['scheme'], 'HTTP') == 0) {
            return $this->_sendHTTP($msg, $action);
        } else if (strcasecmp($this->urlparts['scheme'], 'HTTPS') == 0) {
            return $this->_sendHTTPS($msg, $action);
        }
        
        return $this->raiseSoapFault('Invalid url scheme '.$this->url);
    }

    /**
    * set data for http authentication
    * creates Authorization header
    *
    * @param string $username   username
    * @param string $password   response data, minus http headers
    *
    * @return none
    * @access public
    */
    function setCredentials($username, $password)
    {
        $this->credentials = 'Authorization: Basic ' . base64_encode($username . ':' . $password) . "\r\n";
    }
    
    // private members
    
    /**
    * validate url data passed to constructor
    *
    * @return boolean
    * @access private
    */
    function _validateUrl()
    {
        if ( ! is_array($this->urlparts) ) {
            $this->errno = 2;
            $this->errmsg = "Unable to parse URL $url";
            return FALSE;
        }
        if (!isset($this->urlparts['host'])) {
            $this->errmsg = "No host in URL $url";
            return FALSE;
        }
        if (!isset($this->urlparts['port'])) {
            
            if (strcasecmp($this->urlparts['scheme'], 'HTTP') == 0)
                $this->urlparts['port'] = 80;
            else if (strcasecmp($this->urlparts['scheme'], 'HTTPS') == 0) 
                $this->urlparts['port'] = 443;
                
        }
        if (isset($this->urlparts['user'])) {
            $this->setCredentials($this->urlparts['user'], $this->urlparts['password']);
        }
        
        return TRUE;
    }
    
    /**
    * remove http headers from response
    *
    * @return boolean
    * @access private
    */
    function _parseResponse()
    {
        if (preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $this->incoming_payload, $match)) {
            $this->response = preg_replace("/[\r|\n]/", '', $match[2]);
            // if no content, return false
            return strlen($this->response) > 0;
        }
        return FALSE;
    }
    
    /**
    * create http request, including headers, for outgoing request
    *
    * @return string outgoing_payload
    * @access private
    */
    function &_getRequest(&$msg, $action)
    {
        $this->outgoing_payload = 
                "POST {$this->urlparts['path']} HTTP/1.0\r\n".
                "User-Agent: {$this->_userAgent}\r\n".
                "Host: {$this->urlparts['host']}\r\n".
                $this->credentials. 
                "Content-Type: text/xml\r\n".
                "Content-Length: ".strlen($msg)."\r\n".
                "SOAPAction: \"$action\"\r\n\r\n".
                $msg;
        return $this->outgoing_payload;
    }
    
    /**
    * send outgoing request, and read/parse response
    *
    * @param string &$msg   outgoing SOAP package
    * @param string $action   SOAP Action
    *
    * @return string &$response   response data, minus http headers
    * @access private
    */
    function &_sendHTTP(&$msg, $action = '')
    {
        $this->_getRequest($msg, $action);
        
        // send
        if ($this->timeout > 0) {
            $fp = fsockopen($this->urlparts['host'], $this->urlparts['port'], $this->errno, $this->errmsg, $this->timeout);
        } else {
            $fp = fsockopen($this->urlparts['host'], $this->urlparts['port'], $this->errno, $this->errmsg);
        }
        if (!$fp) {
            $this->errmsg = "Unable to connect to {$this->urlparts['host']}:{$this->urlparts['port']}";
            return $this->raiseSoapFault($this->errmsg);
        }
        if (!fputs($fp, $this->outgoing_payload, strlen($this->outgoing_payload))) {
            $this->errmsg = "Error POSTing Data to {$this->urlparts['host']}";
            return $this->raiseSoapFault($this->errmsg);
        }
        
        // get reponse
        while ($data = fread($fp, 32768)) {
            $this->incoming_payload .= $data;
        }

        fclose($fp);

        if (!$this->_parseResponse()) {
            $this->errmsg = 'Invalid HTTP Response';
            return $this->raiseSoapFault($this->errmsg, $this->outgoing_payload."\n\n".$this->incoming_payload);
        }
        return $this->response;
    }

    /**
    * send outgoing request, and read/parse response, via HTTPS
    *
    * @param string &$msg   outgoing SOAP package
    * @param string $action   SOAP Action
    *
    * @return string &$response   response data, minus http headers
    * @access private
    */
    function &_sendHTTPS(&$msg, $action)
    {
        /* NOTE This function uses the CURL functions
        *  Your php must be compiled with CURL
        */
        if (!extension_loaded('php_curl')) {
            $this->errno = -1;
            $this->errmsg = 'CURL Extension is required for HTTPS';
            return $this->raiseSoapFault($this->errmsg);
        }
        
        $this->_getRequest($msg, $action);
        
        $ch = curl_init(); 
        if ($this->timeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout); //times out after 4s 
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->outgoing_payload);
        curl_setopt($ch, CURLOPT_URL, $this->url); 
        curl_setopt($ch, CURLOPT_FAILONERROR, 1); 
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_VERBOSE, 1); 
        $this->response = curl_exec($ch); 
        curl_close($ch);
        
        return $this->response;
    }
} // end SOAP_Transport_HTTP
?>