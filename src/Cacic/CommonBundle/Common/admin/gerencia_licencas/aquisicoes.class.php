<?php
/**
 * @version $Id: aquisicoes.class.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Classe para Controle de Aquisicoes
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
 class Aquisicoes extends Cacic_Common {
 	
    function Aquisicoes() {
    	parent::Cacic_Common();
    	$this->setNamespace('gerenciaLicencas');
    	$this->setRoot(dirname(__FILE__));
    	$this->readTemplatesFromInput('aquisicoes_01.tmpl.php');
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $cacic_common, $cacic_setup;
    	parent::setup();
 		$cacic_common['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';

    	$titulo = $this->oTranslator->_('Cadastro de Processos de Aquisicoes');
    	
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
     	
 		
     	$this->addVar('Aquisicoes', 'CACIC_URL', CACIC_URL );
     	$this->addVar('Aquisicoes_form', 'TITULO', $titulo );
     	$this->addVar('Aquisicoes_form', 'DESCRICAO_TITLE', $this->oTranslator->_('Controle de processos de aquisicoes') );
     	$this->addVar('Aquisicoes_form', 'AQUISICAO_TITLE', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('Aquisicoes_form', 'NOME_EMPRESA_NAME_TITLE', $this->oTranslator->_('Nome da empresa') );
     	$this->addVar('Aquisicoes_form', 'NOME_PROPRIETARIO_TITLE', $this->oTranslator->_('Nome do proprietario') );
     	$this->addVar('Aquisicoes_form', 'NR_NOTA_FISCAL_TITLE', $this->oTranslator->_('Nota Fiscal') );
     	$this->addVar('Aquisicoes_form', 'DATA_AQUISICAO_TITLE', $this->oTranslator->_('Data de aquisicao') );

     	$this->addVar('Aquisicoes_insert_edit', 'NOME_PROPRIETARIO_NAME_INPUT_LABEL', $this->oTranslator->_('Nome do proprietario') );
     	$this->addVar('Aquisicoes_insert_edit', 'AQUISICAO_INPUT_LABEL', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('Aquisicoes_insert_edit', 'NOME_EMPRESA_NAME_INPUT_LABEL', $this->oTranslator->_('Nome da empresa') );
     	$this->addVar('Aquisicoes_insert_edit', 'NR_NOTA_FISCAL_INPUT_LABEL', $this->oTranslator->_('Nota Fiscal') );
     	$this->addVar('Aquisicoes_insert_edit', 'DATA_AQUISICAO_INPUT_LABEL', $this->oTranslator->_('Data de aquisicao') );
     	$this->addVar('Aquisicoes_insert_edit', 'DATA_AQUISICAO_FORMATO', $this->oTranslator->_('DD/MM/AAAA', T_SIGLA) );
     	
     	$this->addVar('Aquisicoes_insert_edit', 'SELECT_OPTION', $this->oTranslator->_('--- Selecione ---') );
     	
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_INCLUIR_TITLE', $this->oTranslator->_('Incluir registro') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_INCLUIR', $this->oTranslator->_('Incluir')." ". strtolower($this->oTranslator->_('Processo de aquisicao')));
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_INCLUIR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_SALVAR_TITLE', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_SALVAR', $this->oTranslator->_('Gravar') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_SALVAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_CANCELAR_TITLE', $this->oTranslator->_('Cancelar alteracoes') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_CANCELAR', $this->oTranslator->_('Cancelar') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_CANCELAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_RESET_TITLE', $this->oTranslator->_('Restaurar valores') );
     	$this->addVar('Aquisicoes_insert_edit', 'BTN_RESET', $this->oTranslator->_('Restaurar') );
     	
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO', $this->oTranslator->_('Informe esse campo') );
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO_QTDE', $this->oTranslator->_('Informe numero da nota fiscal') );
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO_DATA', $this->oTranslator->_('Informe data valida') );
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO_LIC_TYPE', $this->oTranslator->_('Informe nome do proprietario') );
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO_NOME_EMPRESA', $this->oTranslator->_('Informe nome da empresa') );
     	$this->addVar('Aquisicoes_insert_edit', 'MSG_VALIDACAO_AQUISICAO', $this->oTranslator->_('Informe processo de aquisicao') );
     	
     	$this->addVar('Aquisicoes_list', 'ACTIONS_DELETE_TITLE', $this->oTranslator->_('Excluir registro') );
     	$this->addVar('Aquisicoes_list', 'ACTIONS_EDIT_TITLE', $this->oTranslator->_('Editar registro') );
     	$this->addVar('Aquisicoes_actions_acl', 'ACTIONS_TITLE', $this->oTranslator->_('Acoes') );

    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	global $cacic_common, $cacic_setup;
    	$this->setup(); // atribui dados globais
    	 
		if($cacic_setup['acl_permission']=='disabled') // desabilita acões caso sem permissão
		   $this->addVar('Aquisicoes_insert_edit', 'acl_permission', $cacic_setup['acl_permission'] );
		elseif($cacic_setup['btn_action_incluir'])
		   $this->addVar('Aquisicoes_insert_edit', 'acl_permission', true );
		elseif($cacic_setup['btn_action_edit'])
		   $this->addVar('Aquisicoes_insert_edit', 'acl_permission', true );
		else
		   $this->addVar('Aquisicoes_insert_edit', 'acl_permission', false );
		
		// desabilita acões caso sem permissão
     	$this->addVar('Aquisicoes_actions_acl', 'acl_permission', $cacic_setup['acl_permission'] );
		  
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
		$data_aquisicao = Security::getDate('data_aquisicao');
		$nr_processo = Security::getString('nr_processo');
		$nm_empresa = Security::getString('nm_empresa');
		$nm_proprietario = Security::getString('nm_proprietario');
		$nr_notafiscal = Security::getString('nr_notafiscal');
		
		list($dia, $mes, $ano) = explode("/", $data_aquisicao);
		$data_aquisicao= date('Y-m-d', strtotime($ano."-".$mes."-".$dia));
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql = '';
    	if($cacic_setup['btn_salvar']) {
		  	// verifica se Itens adiquiridos já está cadastrado
		  	$sql = "select * from aquisicoes " .
			                           " where id_aquisicao = '" . $id_aquisicao ."'; ";
		  $db_result = mysql_query($sql);
    	  if(mysql_num_rows($db_result))
			$sql = "update aquisicoes set nr_notafiscal = '" . $nr_notafiscal ."'".
			                              ", dt_aquisicao = '".$data_aquisicao."'".
			                              ", nr_processo = '".$nr_processo."'" .
			                              ", nm_empresa = '".$nm_empresa."'" .
			                              ", nm_proprietario = '".$nm_proprietario."'" .
			                           " where id_aquisicao = '" . $id_aquisicao ."'; ";
		  else {
			$sql = "insert into aquisicoes (dt_aquisicao, nr_processo, nm_empresa, nm_proprietario, nr_notafiscal) " .
					       "value (" .
					       		  "'".$data_aquisicao."', " .
					       		  "'".$nr_processo."', " .
					       		  "'".$nm_empresa."', " .
					       		  "'".$nm_proprietario."', " .
					       		  "'".$nr_notafiscal."' " .
					       		  ");";
		  }
    	}
			                           
    	if($cacic_setup['btn_action_excluir']) {
    		$id_aquisicao = $cacic_setup['btn_action_excluir'];
    		if($id_aquisicao)
			   $sql = "delete from aquisicoes " .
			                           " where id_aquisicao = '" . $id_aquisicao ."'; ";
    	}
				
		/*
		 * Atualiza dados na tabela
		 */				
	    $db_result = mysql_query($sql);
	    $error = mysql_errno($this->db_link);
	    $msg .= $this->oTranslator->_('messagem do servidor:')." <br><pre> ";
	    $msg .= mysql_error($this->db_link)."</pre>";
	    
    	/*
    	 *  Lança execeção se ocorrer erro
    	 */
    	($error) ? $this->throwError($msg):"";

    	$this->setMessageText('<span class="OKImg">'.$this->oTranslator->_('Processamento realizado com sucesso')."</span>");
    }
    
    /**
     * Obtem e preenche dados de formulario
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function fillForm() {
    	global $cacic_common, $cacic_setup;
    	
		$aquisicao_id = $cacic_setup['btn_action_edit'];
		
     	$list = array();
     	$count = 0;
     	
    	$sql = "select * from aquisicoes order by id_aquisicao";
    	$db_result = mysql_query($sql);
    	while( $aquisicoes = mysql_fetch_assoc($db_result) ) {
    		$count++;
            // monta linha de dados da licenca
			$_arrAux = array( array( 'AQUISICAO_PROC'=>$aquisicoes['nr_processo'],
		                             'NOME_EMPRESA'=>$aquisicoes['nm_empresa'],
		                             'NOME_PROPRIETARIO'=>$aquisicoes['nm_proprietario'],
		                             'NR_NOTA_FISCAL'=>$aquisicoes['nr_notafiscal'],
		                             'DATA_AQUISICAO'=>date( $this->oTranslator->_('date view format', T_SIGLA), 
		                                                         strtotime($aquisicoes['dt_aquisicao'] )),
		                             'SEQUENCIAL'=>$count,
		                             
		                             'AQUISICAO_ID'=>$aquisicoes['id_aquisicao'],
		                             
		                             'acl_permission'=> $cacic_setup['acl_permission']
		                    ) );
		    $list = array_merge($list, $_arrAux);
		    
		    // Atribui ao formulario os dados a serem editados
		    if($cacic_setup['btn_action_edit'] and ($aquisicao_id==$aquisicoes['id_aquisicao'])) {
		    	
 		       $this->addVar('Aquisicoes_insert_edit', 'AQUISICAO_ID', $aquisicoes['id_aquisicao']);
 		       $this->addVar('Aquisicoes_insert_edit', 'AQUISICAO_PROC', $aquisicoes['nr_processo']);
 		       $this->addVar('Aquisicoes_insert_edit', 'NOME_EMPRESA', $aquisicoes['nm_empresa']);
 		       $this->addVar('Aquisicoes_insert_edit', 'NOME_PROPRIETARIO', $aquisicoes['nm_proprietario']);		                                                         
		       $this->addVar('Aquisicoes_insert_edit', 'NR_NOTA_FISCAL', $aquisicoes['nr_notafiscal'] );
		       $this->addVar('Aquisicoes_insert_edit', 'DATA_AQUISICAO', date($this->oTranslator->_('date view format', T_SIGLA), 
		                                                                      strtotime($aquisicoes['dt_aquisicao'])) );
		    }
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('Aquisicoes_list', $list );
    	
    }


    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CacicCommon_head');
     	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('Aquisicoes');

    	$this->displayParsedTemplate('Aquisicoes_form');

    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CacicCommon_messages');
     	$this->displayParsedTemplate('CacicCommon_footer');
    }
 }

?>