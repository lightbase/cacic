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
// $Id: Fault.php,v 1.2 2002/03/06 15:43:02 uw Exp $
//
require_once('PEAR.php');
require_once('SOAP/Message.php');

/**
* define('SOAP_DEBUG', false);
*
* @package  SOAP
* @access   public
* @author   Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more
* @author   Dietrich Ayala <dietrich@ganx4.com> Original Author
* @version  $Id: Fault.php,v 1.2 2002/03/06 15:43:02 uw Exp $
*/
class SOAP_Fault extends PEAR_Error
{
    
    /**
    *
    * 
    * @param    string 
    * @param    mixed
    * @param    mixed
    * @param    mixed
    * @param    mixed
    */
    function SOAP_Fault($message = 'unknown error', $code = null, $mode = null, $options = null, $userinfo = null)
    {
    
        if (is_array($userinfo)) {
            $actor = $userinfo['actor'];
            $detail = $userinfo['detail'];
        } else {
            $actor = 'Unknown';
            $detail = $userinfo;
        }
        parent::PEAR_Error($message, $code, $mode, $options, $detail);
        $this->error_message_prefix = $actor;
        
    }
    
    // set up a fault
    function message()
    {
        return new SOAP_Message('Fault',
                                    array(
                                        'faultcode' => $this->code,
                                        'faultstring' => $this->message,
                                        'faultactor' => $this->error_message_prefix,
                                        'faultdetail' => $this->userinfo
                                    ),
                                    SOAP_ENVELOP
                                );
    }
    
    function getFault()
    {
        return array(
                'faultcode' => $this->code,
                'faultstring' => $this->message,
                'faultactor' => $this->error_message_prefix,
                'faultdetail' => $this->userinfo
            );
    }
    
    function getActor()
    {
        return $this->error_message_prefix;
    }
    
    function getDetail()
    {
        return $this->userinfo;
    }
    
}
?>