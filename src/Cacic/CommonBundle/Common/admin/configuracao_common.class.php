<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
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
	die('Erro na inclus�o da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patError.php") )
{
	die('Erro na inclus�o da biblioteca patTemplate! (patTemplate include error!).');
}
if( ! @include("pat/patTemplate.php") )
{
	die('Erro na inclus�o da biblioteca patTemplate! (patTemplate include error!).');
}
 
/*
 * Uma classe para implementar seguran�a em transa��es 
 */
 define( 'SECURITY', 1 );
 require_once('security/security.php');

 /**
  * Dados de configura��o para o CACIC 
  */
 unset($configuracao);
 
/**
 * Classe para defini��es de configura��es
 */
 class Configuracao extends patTemplate {
 	
 	var $setup_type;
 	
 	/**
 	 * Componente (objeto) para tradu��es
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
	 * Armazena na "sessao" os dados de configura��o comuns
	 * @access protected
	 */
 	function setup() {
 		global $configuracao;
 		$configuracao['common'] = 'Definicoes comuns ao sistema';
 	}
 	
 	/**
 	 * Atribui o tipo de configura��o a ser processada
 	 * @access protected
 	 */
 	function setSetupType($type) {
 		$this->setup_type = $type;
 	}
  	
 	/**
 	 * Atribui o tipo de configura��o a ser processada
 	 * @access protected
 	 * @param string $msg A mensagem a ser mostrada
 	 * @param boolean $js Mostra mensagem usando recurso de javascript
 	 */
 	function showMessage($msg, $js=false) {
 		$this->clearVar('CommonSetup_messages_cond', 'MESSAGE');
 		if($js)
 			$this->addVar('CommonSetup_messages_cond', 'msgtype', 'js' );
 			
 		$this->addVar('CommonSetup_messages_cond', 'MESSAGE', $msg );
 	}
  	
    /**
     * Lan�a exece��o se ocorrer erro
     * @access protected
     */
    function throwError($msg) {
    	throw new Exception($msg);
    }
    
    /**
     * Dump de variaceis
     * @access protected
     */
    function varDump($var) {
    	echo "<pre>";
    	var_dump($var);
    	echo "</pre>";
    }
    
 }
 
?>