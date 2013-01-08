<?php
/**
 * @version $Id: cacic_common.class.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
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
 unset($cacic_common);
 
 define(DISABLED, 'disabled');
 
/**
 * Classe para definições de configurações
 */
 class Cacic_Common extends patTemplate {
 	
 	
 	var $setup_type;
 	
 	/**
 	 * Componente (objeto) para traduções
 	 */
 	var $oTranslator;
 	
 	/**
 	 * Componente (objeto) para acesso a banco de dados
 	 */
 	var $db_link;
 	
 	/**
 	 * Itens por página em paginação
 	 */
 	 var $pagination_items = 20;
 	
 	/**
 	 * Conterá a página apresentada (corrente)
 	 */
 	 var $pagination_current_page;
 	
 	/**
 	 * Conterá a quantidade total de itens a serem mostrados
 	 */
 	 var $pagination_total_items;
 	
 	function Cacic_Common() {
 		global $oTranslator, $ip_servidor, $usuario_bd, $senha_usuario_bd, $nome_bd;
    	$this->db_link = mysql_connect( $ip_servidor, $usuario_bd, $senha_usuario_bd);
    	mysql_select_db($nome_bd,$this->db_link); 
 		parent::patTemplate();
 		$this->oTranslator = $oTranslator;
    	/*
    	 * Inicializa template
    	 */
    	$this->setNamespace('cacicCommon');
    	$this->setRoot(dirname(__FILE__));
    	$this->readTemplatesFromInput('cacic_common_01.tmpl.php');
    	
     	$this->addVar('CacicCommon_head', 'CACIC_TITLE', $this->oTranslator->_('Configuracao Basica') );
     	$this->addVar('CacicCommon_head', 'CACIC_LANG', CACIC_LANGUAGE );
     	$this->addVar('CacicCommon_head', 'CACIC_LANG_CHARSET', CACIC_LANG_CHARSET );
     	$this->addVar('CacicCommon_head', 'CACIC_THEME', CACIC_THEME );
     	$this->addVar('CacicCommon_head', 'CACIC_URL', CACIC_URL );
     	$this->addVar('CacicCommon_messages', 'MESSAGES', $this->oTranslator->_('Mensagens') );

     	// Monta paginação
     	$this->addVar('CacicCommon_pagination', 'PAGE_TITLE', $this->oTranslator->_('Paginacao') );
     	$this->addVar('CacicCommon_pagination_first', 'PAGE_TEXT', $this->oTranslator->_('Primeira') );
     	$this->addVar('CacicCommon_pagination_first', 'PAGE_TEXT_TITLE', $this->oTranslator->_('Primeira pagina') );
     	$this->addVar('CacicCommon_pagination_back', 'PAGE_TEXT', $this->oTranslator->_('Anterior') );
     	$this->addVar('CacicCommon_pagination_back', 'PAGE_TEXT_TITLE', $this->oTranslator->_('Pagina anterior') );
     	$this->addVar('CacicCommon_pagination_next', 'PAGE_TEXT', $this->oTranslator->_('Proxima') );
     	$this->addVar('CacicCommon_pagination_next', 'PAGE_TEXT_TITLE', $this->oTranslator->_('Proxima pagina') );
     	$this->addVar('CacicCommon_pagination_last', 'PAGE_TEXT', $this->oTranslator->_('Ultima') );
     	$this->addVar('CacicCommon_pagination_last', 'PAGE_TEXT_TITLE', $this->oTranslator->_('Ultima pagina') );
     	
 	}
 	
	/**
	 * Armazena na "sessao" os dados de configuração comuns
	 * @access protected
	 */
 	function setup() {
 		global $cacic_common;
 		$cacic_common['common'] = 'Declaracoes comuns ao sistema';
 	}
 	
	/**
	 * Atribui título da página
	 * @param string $_title Título a ser atríbuido à pagina
	 * @access protected
	 */
 	function setPageTitle($_title) {
    	$this->clearVar('CacicCommon_head', 'CACIC_TITLE');
     	$this->addVar('CacicCommon_head', 'CACIC_TITLE', $_title );
 	}
 	
	/**
	 * Verifica se é usuário administrador
	 * @access protected
	 */
 	function isAdminUser() {
 		$is_admin_user = false;
    	$is_admin_user = ($_SESSION['cs_nivel_administracao']==1);
    	
    	return $is_admin_user;
 	}
 	
 	/**
 	 * Atribui o tipo de configuração a ser processada
 	 * @access protected
 	 * @param string $msg A mensagem a ser mostrada
 	 * @param boolean $js Mostra mensagem usando recurso de javascript
 	 */
 	function setMessageText($_msg, $_js=false) {
 		$this->clearVar('CacicCommon_messages_cond', 'MESSAGE');
 		if($_js)
 			$this->addVar('CacicCommon_messages_cond', 'msgtype', 'js' );
 			
 		$this->addVar('CacicCommon_messages_cond', 'MESSAGE', $_msg );
 	}
  	
    /**
     * Lança execeção se ocorrer erro
     * @access protected
     */
    function throwError($_msg) {
    	throw new Exception($_msg);
    }

    /**
     * Obtem e preenche dados de paginacao
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function fillPagination($_total_items) {
     	// Monta paginação
     	$_page_to_show = Security::getInt('page');
     	if(!$_page_to_show)
     	   $_page_to_show = 1;
     	$this->setPageCurrent($_page_to_show);
     	
     	if($_total_items)
     	   $this->setPageTotalItems($_total_items); 
     	   
     	$this->addVar('CacicCommon_pagination', 'PAGE_CURRENT', $_page_to_show );

     	   
     	$show = (($this->getPageCurrent()==1 or $this->getPageCurrent()==0)?DISABLED:true);
     	$this->addVar('CacicCommon_pagination_first', 'SHOW', $show ); // SHOW: true, false, DISABLED
     	$this->addVar('CacicCommon_pagination_first', 'PAGE_NUMBER', 1 );

     	$this->addVar('CacicCommon_pagination_back', 'SHOW', $show );
     	$this->addVar('CacicCommon_pagination_back', 'PAGE_NUMBER', $this->getPageBack() );
     	   
     	$this->addRows('CacicCommon_pages_list_cond', $this->pages2Show($_page_to_show, 10) );
     	
     	$show = (($this->getPageNext()==$this->getPageLast())?DISABLED:true);
     	$this->addVar('CacicCommon_pagination_next', 'SHOW', $show  );
     	$this->addVar('CacicCommon_pagination_next', 'PAGE_NUMBER', $this->getPageNext() );
     	
     	$show = (($this->getPageLast()==$this->getPageCurrent())?DISABLED:true);
     	$this->addVar('CacicCommon_pagination_last', 'SHOW', $show );
     	$this->addVar('CacicCommon_pagination_last', 'PAGE_NUMBER', $this->getPageLast() );
     	
     	return $_page_to_show;
    }
    
    /**
     * Atribui número de itens por página
     */
    function setPageItems($_page_items) {
    	$this->pagination_items = $_page_items;
    }
    
    /**
     * Atribui número total de itens a paginar
     */
    function setPageTotalItems($_total_items) {
    	$this->pagination_total_items = $_total_items;
    }
    
    /**
     * Obtem número de itens por página
     */
    function getPageItems() {
    	return $this->pagination_items;
    }
    
    /**
     * Obtem número da página anterior
     */
    function getPageBack() {
    	$page_back = ($this->pagination_current_page-1)<=0?1:$this->pagination_current_page-1;
    	return $page_back;
    }
    
    /**
     * Obtem número da próxima página
     */
    function getPageNext() {
    	$page_next = ($this->pagination_current_page+1)>$this->getPageLast()?$this->getPageLast():$this->pagination_current_page+1;
    	return $page_next;
    }
    
    /**
     * Obtem número da última página
     */
    function getPageLast() {
    	$page_last  = (int)($this->pagination_total_items / $this->pagination_items);
    	return $page_last;
    }
    
    /**
     * Atribui número da página atual (corrente)
     */
    function setPageCurrent($_page_current) {
    	$_page_current = ($_page_current?$_page_current:Security::getInt('page'));
    	$this->pagination_current_page = $_page_current;
    }
    
    /**
     * Obtem número da página atual (corrente)
     */
    function getPageCurrent() {
    	return $this->pagination_current_page;
    }
    
    /**
     * Obtem número de itens por página (corrente ou de uma dada)
     */
    function getPageFristItem($_page) {
    	$ret = ($this->pagination_current_page - 1) * $this->pagination_items;
    	if($_page)
    		$ret = ($_page - 1) * $this->pagination_items;
    	$ret = ($ret<=0?0:$ret);
    	return $ret;
    }
    
    /**
     * Prepara dados de paginacao
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function pages2Show( $_pages_to_show, $_count) {
    	if($_pages_to_show<=0) {
    	  $_current_page = 1;
    	  $_pages_to_show = 1;
    	}
    	else
    	  $_current_page = $_pages_to_show;
    	  
    	$count = 0;
    	$_pages_to_show = $_pages_to_show - (int)( $_count / 2);
    	$_pages_to_show = $_pages_to_show<=0?$_current_page:$_pages_to_show; 
     	$pages_show = array();
    	while ($count < $_count) {
    		$count++;
    		$class_current_page = ($_pages_to_show==$_current_page?'currentpage':'');
	     	$pages = array( array( 'PAGE_TEXT' => $this->oTranslator->_($_pages_to_show),
	     	                       'PAGE_NUMBER'=> $_pages_to_show,
	     	                       'DISABLED'=>($class_current_page?DISABLED:false), 
	     	                       'CLASS_CURRENT_PAGE'=>$class_current_page
	     	                     )
	     	              );
	     	$pages_show = array_merge($pages_show, $pages);
    		$_pages_to_show++;
    	}
     	
     	return $pages_show;
    }
    
    
    /**
     * Dump de variaceis
     * @access protected
     */
    function varDump($_var) {
    	echo "<pre>";
    	var_dump($_var);
    	echo "</pre>";
    }
    
 }
 
?>