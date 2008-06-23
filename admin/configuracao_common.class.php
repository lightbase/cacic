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

/**
 * @version $Id: configuracao_common.class.php 2008-06-18 22:10 harpiain $
 * @package CACIC-Admin
 * @subpackage AdminSetup
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

/*
 * Classe de templates 
 */
if( ! @include("pat/patErrorManager.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patError.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patTemplate.php") )
{
	die('Erro na inclusão da biblioteca patTemplate! (patTemplate include error!).');
}
 
/*
 * Uma classe para implementar segurança em transações 
 */
 define( 'SECURITY', 1 );
 require_once('security/security.php');

 /**
  * Dados de configuração para o CACIC 
  */
 unset($configuracao);
 
/**
 * Classe para definições de configurações
 */
 class Configuracao extends patTemplate {
 	
 	var $setup_type;
 	
 	/**
 	 * Componente (objeto) para traduções
 	 */
 	var $oTranslator;
 	
 	/**
 	 * Componente (objeto)para acesso a banco de dados
 	 */
 	var $db_link;
 	
 	function Configuracao() {
 		global $oTranslator, $ip_servidor, $usuario_bd, $senha_usuario_bd, $nome_bd;
    	$this->db_link = mysql_connect( $ip_servidor, $usuario_bd, $senha_usuario_bd);
    	mysql_select_db($nome_bd,$this->db_link); 
 		parent::patTemplate();
 		$this->oTranslator = $oTranslator;
    	/*
    	 * Inicializa template
    	 */
    	$this->setNamespace('cfgCommon');
    	$this->readTemplatesFromInput('configuracao_common_01.tmpl.php');
     	$this->addVar('CommonSetup_head', 'CACIC_TITLE', $this->oTranslator->_('Configuracao Basica') );
     	$this->addVar('CommonSetup_head', 'CACIC_LANG', CACIC_LANGUAGE );
     	$this->addVar('CommonSetup_head', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     	$this->addVar('CommonSetup_head', 'CACIC_THEME', CACIC_THEME );
     	$this->addVar('CommonSetup_head', 'CACIC_URL', CACIC_URL );
     	$this->addVar('CommonSetup_messages', 'MESSAGES', $this->oTranslator->_('Mensagens') );
 	}
 	
	/**
	 * Armazena na "sessao" os dados de configuração comuns
	 * @access protected
	 */
 	function setup() {
 		global $configuracao;
 		$configuracao['common'] = 'Definicoes comuns ao sistema';
 	}
 	
 	/**
 	 * Atribui o tipo de configuração a ser processada
 	 * @access protected
 	 */
 	function setSetupType($type) {
 		$this->setup_type = $type;
 	}
  	
 	/**
 	 * Atribui o tipo de configuração a ser processada
 	 * @access protected
 	 */
 	function showMessage($msg) {
 		$this->clearVar('CommonSetup_messages', 'MESSAGE');
     	$this->addVar('CommonSetup_messages', 'MESSAGE', $msg );
 	}
  	
    /**
     * Lança execeção se ocorrer erro
     * @access protected
     */
    function throwError($msg) {
    	throw new Exception($msg);
    }
    
 }
 
?>