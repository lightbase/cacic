<?php
/**
 * @version $Id: aquisicoes_itens.class.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Classe para Itens Adquiridos
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

/*
 * Classe geral 
 */
 include_once('common/cacic_common.class.php');
 
/**
 * Implementa controle de itens adquiridos
 */
 class Tipos_Licenca extends Cacic_Common {
 	
    function Tipos_Licenca() {
    	parent::Cacic_Common();
    	$this->setNamespace('gerenciaLicencas');
    	$this->setRoot(dirname(__FILE__));
    	$this->readTemplatesFromInput('aquisicoes_itens_01.tmpl.php');
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $cacic_common, $cacic_setup;
    	parent::setup();
 		$cacic_common['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';

    	$titulo = $this->oTranslator->_('Itens adiquiridos');
    	
    	// Obtem acoes de formulário
 		$cacic_setup['btn_action_edit'] = Security::getString('btn_action_edit');
		$cacic_setup['btn_action_excluir'] = Security::getString('btn_action_excluir');
		$cacic_setup['btn_action_incluir'] = Security::getInt('btn_action_incluir');
		$cacic_setup['btn_salvar'] = Security::getInt('btn_salvar');
		
    	// Obtem dado de permissão (ACL) do utilizador
		$cacic_setup['acl_permission'] = ($this->isAdminUser()?'enabled':'disabled');
		
    	/*
    	 * Inicializa template com textos basicos
    	 */
     	$this->setPageTitle( $titulo );
     	
 		
     	$this->addVar('AquisicoesItens', 'CACIC_URL', CACIC_URL );
     	$this->addVar('AquisicoesItens_form', 'TITULO', $titulo );
     	$this->addVar('AquisicoesItens_form', 'DESCRICAO_TITLE', $this->oTranslator->_('Controle de itens adiquiridos') );
     	$this->addVar('AquisicoesItens_form', 'AQUISICAO_ITEM_NAME_TITLE', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('AquisicoesItens_form', 'SOFTWARE_NAME_TITLE', $this->oTranslator->_('Software') );
     	$this->addVar('AquisicoesItens_form', 'TIPO_LICENCA_TITLE', $this->oTranslator->_('Tipo de licenca') );
     	$this->addVar('AquisicoesItens_form', 'QUANTIDADE_LICENCA_TITLE', $this->oTranslator->_('Quantidade de licencas') );
     	$this->addVar('AquisicoesItens_form', 'VENCIMENTO_LICENCA_TITLE', $this->oTranslator->_('Data de vencimento') );

     	$this->addVar('AquisicoesItens_insert_edit', 'TIPO_LICENCA_NAME_INPUT_LABEL', $this->oTranslator->_('Tipo de licenca') );
     	$this->addVar('AquisicoesItens_insert_edit', 'AQUISICAO_NAME_INPUT_LABEL', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('AquisicoesItens_insert_edit', 'SOFTWARE_NAME_INPUT_LABEL', $this->oTranslator->_('Software') );
     	$this->addVar('AquisicoesItens_insert_edit', 'QTDE_LICENCA_INPUT_LABEL', $this->oTranslator->_('Quantidade de licencas') );
     	$this->addVar('AquisicoesItens_insert_edit', 'DATA_VENCIMENTO_INPUT_LABEL', $this->oTranslator->_('Data de vencimento') );
     	$this->addVar('AquisicoesItens_insert_edit', 'DATA_VENCIMENTO_FORMATO', $this->oTranslator->_('DD/MM/AAAA', T_SIGLA) );
     	$this->addVar('AquisicoesItens_insert_edit', 'OBSERVACAO_INPUT_LABEL', $this->oTranslator->_('Observacao') );
     	
     	$this->addVar('AquisicoesItens_insert_edit', 'SELECT_OPTION', $this->oTranslator->_('--- Selecione ---') );
     	
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_INCLUIR_TITLE', $this->oTranslator->_('Incluir registro') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_INCLUIR', $this->oTranslator->_('Incluir')." ". strtolower($this->oTranslator->_('Itens adiquiridos')));
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_INCLUIR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_SALVAR_TITLE', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_SALVAR', $this->oTranslator->_('Gravar') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_SALVAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_CANCELAR_TITLE', $this->oTranslator->_('Cancelar alteracoes') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_CANCELAR', $this->oTranslator->_('Cancelar') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_CANCELAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_RESET_TITLE', $this->oTranslator->_('Restaurar valores') );
     	$this->addVar('AquisicoesItens_insert_edit', 'BTN_RESET', $this->oTranslator->_('Restaurar') );
     	
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO', $this->oTranslator->_('Informe esse campo') );
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO_QTDE', $this->oTranslator->_('Quantidade deve ser valor numerico') );
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO_DATA', $this->oTranslator->_('Informe data valida') );
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO_LIC_TYPE', $this->oTranslator->_('Informe tipo de licenca') );
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO_SOFTWARE', $this->oTranslator->_('Informe software') );
     	$this->addVar('AquisicoesItens_insert_edit', 'MSG_VALIDACAO_AQUISICAO', $this->oTranslator->_('Informe processo de aquisicao') );
     	
     	$this->addVar('AquisicoesItens_list', 'TIPO_LICENCA_ACTIONS_DELETE_TITLE', $this->oTranslator->_('Excluir registro') );
     	$this->addVar('AquisicoesItens_list', 'TIPO_LICENCA_ACTIONS_EDIT_TITLE', $this->oTranslator->_('Editar registro') );
     	$this->addVar('AquisicoesItens_actions_acl', 'ACTIONS_TITLE', $this->oTranslator->_('Acoes') );

    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	global $cacic_common, $cacic_setup;
    	$this->setup(); // atribui dados globais
    	 
		if($cacic_setup['acl_permission']=='disabled') // desabilita acões caso sem permissão
		   $this->addVar('AquisicoesItens_insert_edit', 'acl_permission', $cacic_setup['acl_permission'] );
		elseif($cacic_setup['btn_action_incluir'])
		   $this->addVar('AquisicoesItens_insert_edit', 'acl_permission', true );
		elseif($cacic_setup['btn_action_edit'])
		   $this->addVar('AquisicoesItens_insert_edit', 'acl_permission', true );
		else
		   $this->addVar('AquisicoesItens_insert_edit', 'acl_permission', false );
		
		// desabilita acões caso sem permissão
     	$this->addVar('AquisicoesItens_actions_acl', 'acl_permission', $cacic_setup['acl_permission'] );
		  
    	if((isset($cacic_setup['btn_salvar']) and ($cacic_setup['btn_salvar'])) or 
    	   (isset($cacic_setup['btn_action_excluir']) and ($cacic_setup['btn_action_excluir'])) ) {
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
    	global $cacic_common, $cacic_setup;
    	$error = true;
    	$msg = $this->oTranslator->_('Ocorreu erro no processamento... ');
    	/*
    	 * Obtem dados do formulario
    	 */
		$id_aquisicao = Security::getInt('id_aquisicao');
		$id_software = Security::getInt('id_software');
		$id_tipo_licenca = Security::getInt('id_tipo_licenca');
		$qtde_licenca = Security::getInt('qtde_licenca');
		$data_vencimento = Security::getDate('data_vencimento');
		$observacao = Security::getString('observacao');
		
		list($dia, $mes, $ano) = explode("/", $data_vencimento);
		$data_vencimento= date('Y-m-d', strtotime($ano."-".$mes."-".$dia));
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql = '';
    	if($cacic_setup['btn_salvar']) {
		  	// verifica se Itens adiquiridos já está cadastrado
		  	$sql = "select * from aquisicoes_item " .
			                           " where id_aquisicao = '" . $id_aquisicao ."'".
			                               " and id_software = '" . $id_software ."'".
			                               " and id_tipo_licenca = ".$id_tipo_licenca."; ";
		  $db_result = mysql_query($sql);
    	  if(mysql_num_rows($db_result))
			$sql = "update aquisicoes_item set qt_licenca = '" . $qtde_licenca ."'".
			                              ", dt_vencimento_licenca = '".$data_vencimento."'".
			                              ", te_obs = '".$observacao."'".
			                           " where id_aquisicao = '" . $id_aquisicao ."'".
			                             " and id_software = '" . $id_software ."'".
			                             " and id_tipo_licenca = ".$id_tipo_licenca."; ";
		  else {
		    
		    
			$sql = "insert into aquisicoes_item (id_aquisicao, id_software, id_tipo_licenca, qt_licenca, dt_vencimento_licenca, te_obs) " .
					       "value ('".$id_aquisicao."', " .
					       		  "'".$id_software."', " .
					       		  "'".$id_tipo_licenca."', " .
					       		  "'".$qtde_licenca."', " .
					       		  "'".$data_vencimento."', " .
					       		  "'".$observacao."' " .
					       		  ");";
		  }
    	}
			                           
    	if($cacic_setup['btn_action_excluir']) {
    		list($id_aquisicao, $id_software, $id_tipo_licenca) = explode("_", $cacic_setup['btn_action_excluir']);
    		if($id_aquisicao and $id_software and $id_tipo_licenca)
			   $sql = "delete from aquisicoes_item " .
			                           " where id_aquisicao = '" . $id_aquisicao ."'".
			                             " and id_software = '" . $id_software ."'".
			                             " and id_tipo_licenca = ".$id_tipo_licenca."; ";
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
     * Obtem e preenche dados de tipo de licença
     * @access private
     */
    function fillTiposLicenca($_id_tipo_licenca_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from tipos_licenca order by te_tipo_licenca";
    	$db_result = mysql_query($sql);
     	$list = array();
     	$selected = "";
    	while( $lic_type = mysql_fetch_assoc($db_result) ) {
    		$selected = ($_id_tipo_licenca_selected==$lic_type['id_tipo_licenca'])?"selected":"";
            // monta linha de dados da licenca
			$_arrAux = array( array( 'TIPO_LICENCA_NAME'=>$lic_type['te_tipo_licenca'],
		                             'TIPO_LICENCA_ID'=>$lic_type['id_tipo_licenca'],
		                             'TIPO_LICENCA_SELECTED'=>$selected
		                    ) );
		    $list = array_merge($list, $_arrAux);
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('TipoLicenca_insert_edit_list', $list );
    	
    }

    /**
     * Obtem e preenche dados de aquisições
     * @access private
     */
    function fillAquisicoes($_id_aquisicao_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from aquisicoes order by id_aquisicao";
    	$db_result = mysql_query($sql);
     	$list = array();
     	$selected = "";
    	while( $aquisicoes = mysql_fetch_assoc($db_result) ) {
    		$selected = ($_id_aquisicao_selected==$aquisicoes['id_aquisicao'])?"selected":"";
            // monta linha de dados da licenca
			$_arrAux = array( array( 'AQUISICAO_PROC'=>$aquisicoes['nr_processo']." ".$aquisicoes['dt_aquisicao']." ".$aquisicoes['nm_empresa'],
		                             'AQUISICAO_ID'=>$aquisicoes['id_aquisicao'],
		                             'AQUISICAO_SELECTED'=>$selected
		                    ) );
		    $list = array_merge($list, $_arrAux);
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('Aquisicao_insert_edit_list', $list );
    	
    }

    /**
     * Obtem e preenche dados de aquisições
     * @access private
     */
    function getAquisicaoProcesso($_id_aquisicao_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from aquisicoes where id_aquisicao=".$_id_aquisicao_selected;
    	$db_result = mysql_query($sql);
    	$aquisicoes = mysql_fetch_assoc($db_result);

		return $aquisicoes['nr_processo']." ".$aquisicoes['dt_aquisicao']." ".$aquisicoes['nm_empresa'];
    }

    /**
     * Obtem e preenche dados de software
     * @access private
     */
    function fillSoftwares($_id_soft_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from softwares order by nm_software";
    	$db_result = mysql_query($sql);
     	$list = array();
     	$selected = "";
    	while( $soft = mysql_fetch_assoc($db_result) ) {
            $selected = ($_id_soft_selected==$soft['id_software'])?"selected":"";
            // monta linha de dados da licenca
			$_arrAux = array( array( 'SOFTWARE_NAME'=>$soft['nm_software'],
		                             'SOFTWARE_ID'=>$soft['id_software'],
		                             'SOFTWARE_SELECTED'=>$selected
		                    ) );
		    $list = array_merge($list, $_arrAux);
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('Software_insert_edit_list', $list );
    	
    }

    /**
     * Obtem e preenche dados de software
     * @access private
     */
    function getSoftwares($_id_soft_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from softwares where id_software=".$_id_soft_selected;
    	$db_result = mysql_query($sql);
    	$soft = mysql_fetch_assoc($db_result);
		return $soft['nm_software'];
    }

    /**
     * Obtem e preenche dados de software
     * @access private
     */
    function getTipoLicenca($_id_tipo_licenca_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from tipos_licenca where id_tipo_licenca=".$_id_tipo_licenca_selected;
    	$db_result = mysql_query($sql);
    	$lic_type = mysql_fetch_assoc($db_result);
		return $lic_type['te_tipo_licenca'];
    }

    /**
     * Obtem e preenche dados de formulario
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function fillForm() {
    	global $cacic_common, $cacic_setup;
    	
		list($aquisicao_id, $software_id, $tipo_licenca_id) = explode("_", $cacic_setup['btn_action_edit']);
		
    	$this->fillTiposLicenca($tipo_licenca_id);
    	$this->fillAquisicoes($aquisicao_id);
    	$this->fillSoftwares($software_id);
    	
     	$list = array();
     	$count = 0;
     	
    	$sql = "select * from aquisicoes_item order by id_aquisicao,id_software,id_tipo_licenca";
    	$db_result = mysql_query($sql);
    	while( $aquisicoes_itens = mysql_fetch_assoc($db_result) ) {
    		$count++;
            // monta linha de dados da licenca
			$_arrAux = array( array( 'AQUISICAO_PROC'=>$this->getAquisicaoProcesso($aquisicoes_itens['id_aquisicao']),
		                             'SOFTWARE'=>$this->getSoftwares($aquisicoes_itens['id_software']),
		                             'TIPO_LICENCA'=>$this->getTipoLicenca($aquisicoes_itens['id_tipo_licenca']),
		                             'QUANTIDADE_LICENCA'=>$aquisicoes_itens['qt_licenca'],
		                             'VENCIMENTO_LICENCA'=>date( $this->oTranslator->_('date view format', T_SIGLA), 
		                                                         strtotime($aquisicoes_itens['dt_vencimento_licenca'] )),
		                             'OBSERVACAO'=>$this->oTranslator->_('Observacao').": ".$aquisicoes_itens['te_obs'],
		                             'SEQUENCIAL'=>$count,
		                             
		                             'AQUISICAO_ID'=>$aquisicoes_itens['id_aquisicao'],
		                             'SOFTWARE_ID'=>$aquisicoes_itens['id_software'],
		                             'TIPO_LICENCA_ID'=>$aquisicoes_itens['id_tipo_licenca'],
		                             
		                             'acl_permission'=> $cacic_setup['acl_permission']
		                    ) );
		    $list = array_merge($list, $_arrAux);
		    
		    // Atribui ao formulario os dados a serem editados
		    if($cacic_setup['btn_action_edit'] and ($aquisicao_id==$aquisicoes_itens['id_aquisicao'] and
		                                            $software_id==$aquisicoes_itens['id_software'] and
		                                            $tipo_licenca_id==$aquisicoes_itens['id_tipo_licenca'])) {
		       $this->addVar('AquisicoesItens_insert_edit', 'QTDE_LICENCA', $aquisicoes_itens['qt_licenca'] );
		       $this->addVar('AquisicoesItens_insert_edit', 'DATA_VENCIMENTO', date($this->oTranslator->_('date view format', T_SIGLA), strtotime($aquisicoes_itens['dt_vencimento_licenca'])) );
		       $this->addVar('AquisicoesItens_insert_edit', 'OBSERVACAO', $aquisicoes_itens['te_obs'] );
		    }
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('AquisicoesItens_list', $list );
    	
    }


    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CacicCommon_head');
     	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('AquisicoesItens');

    	$this->displayParsedTemplate('AquisicoesItens_form');

    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CacicCommon_messages');
     	$this->displayParsedTemplate('CacicCommon_footer');
    }
 }

?>