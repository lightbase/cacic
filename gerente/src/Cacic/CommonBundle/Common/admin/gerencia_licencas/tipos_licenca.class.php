<?php
/**
 * @version $Id: tipos_licenca.class.php 2009-08-26 23:26 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Classe para tipos de licenca
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

/*
 * Classe geral 
 */
 include_once('common/cacic_common.class.php');
 
/*
 * 
 */
 unset($g_tipos_licenca);
/**
 * Implementa controle de tipos de licença
 */
 class Tipos_Licenca extends Cacic_Common {
 	
    function Tipos_Licenca() {
    	parent::Cacic_Common();
    	$this->setNamespace('gerenciaLicencas');
    	$this->setRoot(dirname(__FILE__));
    	$this->readTemplatesFromInput('tipos_licenca_01.tmpl.php');
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $cacic_common, $tipos_licenca_setup;
    	parent::setup();
 		$cacic_common['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';

    	$titulo = $this->oTranslator->_('Tipos de licenca');
    	
    	// Obtem acoes de formulário
 		$tipos_licenca_setup['btn_action_edit'] = Security::getInt('btn_action_edit');
		$tipos_licenca_setup['btn_action_excluir'] = Security::getInt('btn_action_excluir');
		$tipos_licenca_setup['btn_action_incluir'] = Security::getInt('btn_action_incluir');
		$tipos_licenca_setup['btn_salvar'] = Security::getInt('btn_salvar');
		
    	// Obtem dado de permissão (ACL) do utilizador
		$tipos_licenca_setup['acl_permission'] = ($this->isAdminUser()?'enabled':'disabled');
		
    	/*
    	 * Inicializa template com textos basicos
    	 */
     	$this->setPageTitle( $titulo );
     	
 		
     	$this->addVar('TiposLicenca', 'CACIC_URL', CACIC_URL );
     	$this->addVar('TiposLicenca_form', 'TITULO', $titulo );
     	$this->addVar('TiposLicenca_form', 'DESCRICAO', $this->oTranslator->_('Controle de tipos de licencas') );
     	$this->addVar('TiposLicenca_form', 'TIPO_LICENCA_NAME_TITLE', $this->oTranslator->_('Tipo de licenca') );

     	$this->addVar('TiposLicenca_insert_edit', 'TIPO_LICENCA_NAME_INPUT_LABEL', $this->oTranslator->_('Tipo de licenca') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_INCLUIR_TITLE', $this->oTranslator->_('Incluir registro') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_INCLUIR', $this->oTranslator->_('Incluir')." ".strtolower($this->oTranslator->_('Tipo de licenca')) );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_INCLUIR_DENY',  $tipos_licenca_setup['acl_permission']);
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_SALVAR_TITLE', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_SALVAR', $this->oTranslator->_('Gravar') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_SALVAR_DENY',  $tipos_licenca_setup['acl_permission']);
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_CANCELAR_TITLE', $this->oTranslator->_('Cancelar alteracoes') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_CANCELAR', $this->oTranslator->_('Cancelar') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_CANCELAR_DENY',  $tipos_licenca_setup['acl_permission']);
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_RESET_TITLE', $this->oTranslator->_('Restaurar valores') );
     	$this->addVar('TiposLicenca_insert_edit', 'BTN_RESET', $this->oTranslator->_('Restaurar') );
     	$this->addVar('TiposLicenca_insert_edit', 'MSG_VALIDACAO', $this->oTranslator->_('Informe tipo de licenca') );
     	$this->addVar('TiposLicenca_list', 'TIPO_LICENCA_ACTIONS_DELETE_TITLE', $this->oTranslator->_('Excluir registro') );
     	$this->addVar('TiposLicenca_list', 'TIPO_LICENCA_ACTIONS_EDIT_TITLE', $this->oTranslator->_('Editar registro') );

     	$this->addVar('TiposLicenca_actions_acl', 'TIPO_LICENCA_ACTIONS_TITLE', $this->oTranslator->_('Acoes') );

    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	global $cacic_common, $tipos_licenca_setup;
    	$this->setup(); // atribui dados globais
    	 
		if($tipos_licenca_setup['acl_permission']=='disabled') // desabilita acões caso sem permissão
		   $this->addVar('TiposLicenca_insert_edit', 'acl_permission', $tipos_licenca_setup['acl_permission'] );
		elseif($tipos_licenca_setup['btn_action_incluir'])
		   $this->addVar('TiposLicenca_insert_edit', 'acl_permission', true );
		elseif($tipos_licenca_setup['btn_action_edit'])
		   $this->addVar('TiposLicenca_insert_edit', 'acl_permission', true );
		else
		   $this->addVar('TiposLicenca_insert_edit', 'acl_permission', false );
		
		// desabilita acões caso sem permissão
     	$this->addVar('TiposLicenca_actions_acl', 'acl_permission', $tipos_licenca_setup['acl_permission'] );
		  
    	if((isset($tipos_licenca_setup['btn_salvar']) and ($tipos_licenca_setup['btn_salvar'])) or 
    	   (isset($tipos_licenca_setup['btn_action_excluir']) and ($tipos_licenca_setup['btn_action_excluir'])) ) {
			try {
				$this->salvarDados();
			}
			catch( Exception $erro ) {
				$msg = '<span class="ErroImg"></span>';
				$msg .= '<span class="Erro">'.$erro->getMessage()."</span>";
				$this->setMessageText($msg);
			}    		
    	}
    	$this->fillForm();
    	$this->showForm();
    } 
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function salvarDados() {
    	global $cacic_common, $tipos_licenca_setup;
    	$error = true;
    	$msg = $this->oTranslator->_('Ocorreu erro no processamento... ');
    	/*
    	 * Obtem dados do formulario
    	 */
		$te_tipo_licenca = Security::getString('te_tipo_licenca');
		$id_tipo_licenca = Security::getInt('id_tipo_licenca');
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql = '';
    	if($tipos_licenca_setup['btn_salvar']) {
		  // Lança execeção se tentar salvar descrição da licença não informada
		  (empty($te_tipo_licenca)) ? $this->throwError($msg.$this->oTranslator->_('Nao informada descricao do tipo de licenca')):"";
		    
    	  if($id_tipo_licenca)
			$sql = "update tipos_licenca set te_tipo_licenca = '" . $te_tipo_licenca ."'".
			                           " where id_tipo_licenca = ".$id_tipo_licenca."; ";
		  else {
		  	// verifica se Tipo de Licenca já está cadastrado
		  	$sql = "select * from tipos_licenca " .
		  			        "where te_tipo_licenca = '" . $te_tipo_licenca ."'";
		  	$db_result = mysql_query($sql);
		    $msg .= $this->oTranslator->_('Tipo de licenca ja cadastrado')." ";
		    // Lança execeção se já existir Tipo de Licença com mesma descrição e não cadastra
		    (mysql_num_rows($db_result)) ? $this->throwError($msg):"";
		    
		    
			$sql = "insert into tipos_licenca (te_tipo_licenca) " .
					       "value ('" . $te_tipo_licenca ."');";
		  }
    	}
			                           
    	if($tipos_licenca_setup['btn_action_excluir']) {
    		$id_tipo_licenca = $tipos_licenca_setup['btn_action_excluir'];
			$sql = "delete from tipos_licenca where id_tipo_licenca = ".$id_tipo_licenca."; ";
    	}
						
		/*
		 * Atualiza dados na tabela
		 */				
	    $db_result = mysql_query($sql);
	    $error = mysql_errno($this->db_link);
	    $msg .= $this->oTranslator->_('messagem do servidor:')." ";
	    $msg .= mysql_error($this->db_link);
	    
    	/*
    	 *  Lança execeção se ocorrer erro
    	 */
    	($error) ? $this->throwError($msg):"";

    	$this->setMessageText('<span class="OKImg">'.$this->oTranslator->_('Processamento realizado com sucesso')."</span>");
    }
    
    /**
     * Obtem e preenche dados de formulario
     * @access private
     */
    function fillForm() {
    	global $cacic_common, $tipos_licenca_setup;
    	$sql = "select * from tipos_licenca order by te_tipo_licenca";
    	$db_result = mysql_query($sql);
     	$tipos_licenca_list = array();
     	$count = 0;
    	while( $lic_type = mysql_fetch_assoc($db_result) ) {
    		$count++;
            // monta linha de dados da licenca
			$_arrAux = array( array( 'TIPO_LICENCA_NAME'=>$lic_type['te_tipo_licenca'],
		                             'TIPO_LICENCA_ID'=>$lic_type['id_tipo_licenca'],
		                             'TIPO_LICENCA_SEQ'=>$count,
		                             'acl_permission'=> $tipos_licenca_setup['acl_permission']
		                    ) );
		    $tipos_licenca_list = array_merge($tipos_licenca_list, $_arrAux);
		    
		    // Atribui ao formulario os dados a serem editados
		    if($tipos_licenca_setup['btn_action_edit']==$lic_type['id_tipo_licenca']) {
		       $this->addVar('TiposLicenca_insert_edit', 'TE_TIPO_LICENCA', $lic_type['te_tipo_licenca'] );
		       $this->addVar('TiposLicenca_insert_edit', 'TIPO_LICENCA_ID', $lic_type['id_tipo_licenca'] );
		    }
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('TiposLicenca_list', $tipos_licenca_list );
    	
    }

    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CacicCommon_head');
     	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('TiposLicenca');

    	$this->displayParsedTemplate('TiposLicenca_form');

    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CacicCommon_messages');
     	$this->displayParsedTemplate('CacicCommon_footer');
    }
 }

?>