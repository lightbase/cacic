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
// $Id: transport.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//

require_once 'SOAP/Base.php';

/**
* SOAP Transport Layer
*
* This layer can use different protocols dependant on the endpoint url provided
* no knowlege of the SOAP protocol is available at this level
* no knowlege of the transport protocols is available at this level
*
* @access   public
* @version  $Id: transport.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package  SOAP::Transport
* @author   Shane Caraveo <shane@php.net>
*/
class SOAP_Transport extends SOAP_Base
{

    /**
    * Transport object - build using the constructor as a factory
    * 
    * @var  object  SOAP_Transport_SMTP|HTTP
    */
    var $transport = NULL;
    
    /**
    * Error message
    * 
    * Used to communicate between SOAP_Transport() and send()
    *
    * @var  string
    */
    var $errmsg = '';

    /**
    * SOAP::Transport constructor
    *
    * @param string $url   soap endpoint url
    *
    * @access public
    */
    function SOAP_Transport($url, $debug = SOAP_DEBUG)
    {
        parent::SOAP_Base('TRANSPORT');
        /* only HTTP transport for now, later look at url for scheme */
        $this->debug_flag = $debug;

        $urlparts = @parse_url($url);

        if (strcasecmp($urlparts['scheme'], 'http') == 0 || strcasecmp($urlparts['scheme'], 'http') == 0) {
            include_once('SOAP/Transport/HTTP.php');
            $this->transport = new SOAP_Transport_HTTP($url);
            return;
        } else if (strcasecmp($urlparts['scheme'], 'mailto') == 0) {
            include_once('SOAP/Transport/SMTP.php');
            $this->transport = new SOAP_Transport_SMTP($url);
            return;
        }
        $this->errmsg = "No Transport for {$urlparts['scheme']}";
        $this->raiseSoapFault($this->errmsg);
    }
    
    /**
    * send a soap package, get a soap response
    *
    * @param string &$soap_data   soap data to be sent (in xml)
    * @param string $action SOAP Action
    * @param int $timeout protocol timeout in seconds
    *
    * @return string &$response   soap response (in xml)
    * @access public
    */
    function &send(&$soap_data, $action = '', $timeout = 0)
    {
        if (!$this->transport) {
            return $this->raiseSoapFault($this->errmsg);
        }
        
        $response = $this->transport->send($soap_data, $action, $timeout);
        if (PEAR::isError($response)) {
            return $this->raiseSoapFault($response);
        }
        
        $this->debug("OUTGOING: ".$this->transport->outgoing_payload);
        $this->debug("INCOMING: ".$this->transport->incoming_payload);
        #echo "\n OUTGOING: ".$this->transport->outgoing_payload."\n\n";
        #echo "\n INCOMING: ".preg_replace("/>/",">\n",$this->transport->incoming_payload)."\n\n";
        return $response;
    }

} // end SOAP_Transport
?>