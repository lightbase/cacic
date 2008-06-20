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
 * @version $Id: configuracao_padrao.class.php 2008-06-18 22:10 harpiain $
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
 * Classe geral para configurações 
 */
 require_once('configuracao_common.class.php');
 
/**
 * 
 */
 class Configuracao_Padrao extends Configuracao {

    function Configuracao_Padrao() {
    	parent::Configuracao();
    	$this->setSetupType('standard');
    	/*
    	 * Inicializa template
    	 */
    	$this->setNamespace('cfgPadrao');
    	$this->readTemplatesFromInput('configuracao_padrao_01.tmpl.php');
    	$this->clearVar('CommonSetup_head', 'CACIC_TITLE');
     	$this->addVar('CommonSetup_head', 'CACIC_TITLE', $this->oTranslator->_('Configuracao Padrao') );
     	
     	$this->addVar('StandardSetup', 'CACIC_URL', CACIC_URL );
     	$this->addVar('StandardSetup_form', 'TITULO', $this->oTranslator->_('Configuracao Padrao') );
     	$this->addVar('StandardSetup_form', 'DESCRICAO', $this->oTranslator->_('Definir as configuracoes para sugestoes em formularios') );
     	$this->addVar('StandardSetup_form', 'NM_ORGANIZACAO_TITLE', $this->oTranslator->_('Nome da organizacao') );
     	$this->addVar('StandardSetup_form', 'TE_SERVUPDT_STD_TITLE', $this->oTranslator->_('Nome ou IP do servidor de atualizacao') );
     	$this->addVar('StandardSetup_form', 'TE_SERVCACIC_STD_TITLE', $this->oTranslator->_('Nome ou IP do servidor de aplicacao (gerente)') );
     	$this->addVar('StandardSetup_form', 'EXIBE_ERROS_CRITICOS_TITLE', $this->oTranslator->_('Exibir erros criticos aos usuarios') );
     	$this->addVar('StandardSetup_form', 'EXIBE_BANDEJA_TITLE', $this->oTranslator->_('Exibir o icone do CACIC na bandeja (systray)') );
     	$this->addVar('StandardSetup_form', 'TE_MACADDR_INVALID_TITLE', $this->oTranslator->_('Enderecos MAC a desconsiderar') );
     	$this->addVar('StandardSetup_form', 'TE_MACADDR_INVALID_HELP', $this->oTranslator->_('Atencao: informe os enderecos separados por virgulas') );
     	$this->addVar('StandardSetup_form', 'TE_JANELAS_EXCECAO_TITLE', $this->oTranslator->_('Aplicativos (janelas) a evitar') );
     	$this->addVar('StandardSetup_form', 'TE_JANELAS_EXCECAO_HELP', $this->oTranslator->_('Evita que o Gerente de Coletas seja acionado enquanto tais aplicativos (janelas) estiverem ativos') );
     	$this->addVar('StandardSetup_form', 'BTN_SALVAR', $this->oTranslator->_('Gravar alteracoes') );
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $configuracao;
    	parent::setup();
 		$configuracao['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';
    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	$this->clearVar('StandardSetup_form', 'SET_FOCUS');
     	$this->addVar('StandardSetup_form', 'SET_FOCUS', 'nm_organizacao' );
    	$btn_salvar = Security::read('btn_salvar');
    	$this->showForm();
    } 
    
    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	global $configuracao;
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CommonSetup_head');
     	// Monta cabecalho da pagina para "configuracao_padrao"
     	$this->displayParsedTemplate('StandardSetup');

    	// Monta corpo da pagina para "configuracao_padrao"
    	$this->displayParsedTemplate('StandardSetup_form');
    	
    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CommonSetup_messages');
     	$this->displayParsedTemplate('CommonSetup_foot');
    }
 }

?>