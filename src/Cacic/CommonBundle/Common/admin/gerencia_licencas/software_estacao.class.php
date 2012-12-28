<?php
/**
 * @version $Id: software_estacao.class.php 2009-10-12 14:05 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Classe para Controle de Software por Estação
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

/*
 * Classe geral 
 */
 include_once('common/cacic_common.class.php');
 
/**
 * Implementa Controle de Softwares
 */
 class Software extends Cacic_Common {
 	
 	function Software() {
    	parent::Cacic_Common();

 	}
 	
    /**
     * Obtem e preenche dados de software
     * @access private
     */
    function fillSoftwares($_id_soft_selected, $_disable=false) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select * from softwares order by nm_software";
    	$db_result = mysql_query($sql);
     	$list = array();
     	$selected = "";
     	$disabled = "";
    	while( $soft = mysql_fetch_assoc($db_result) ) {
            $selected = ($_id_soft_selected==$soft['id_software'])?"selected":"";
            $disabled = ($_id_soft_selected==$soft['id_software'] and $_disable)?"disabled":"";
            // monta linha de dados da licenca
			$_arrAux = array( array( 'SOFTWARE_NAME'=>$soft['nm_software'],
		                             'SOFTWARE_ID'=>$soft['id_software'],
		                             'SOFTWARE_SELECTED'=>$selected,
		                             'SOFTWARE_DISABLED'=>$disabled
		                    ) );
		    $list = array_merge($list, $_arrAux);
    	}
    	
    	return $list;
    }

    /**
     * Obtem e preenche dados de software
     * @access private
     */
    protected function getSoftwareName($_id_soft_selected) {
    	global $cacic_common, $cacic_setup;
    	$sql = "select nm_software from softwares where id_software=".$_id_soft_selected;
    	$db_result = mysql_query($sql);
    	$soft = mysql_fetch_assoc($db_result);
		return $soft['nm_software'];
    }

 }
  
/**
 * Implementa controle de Software por Estação
 */
 class Software_Estacao extends Software {
 	
    function Software_Estacao() {
    	parent::Software();
    	$this->setNamespace('softwareEstacao');
    	$this->setRoot(dirname(__FILE__));
    	list($file_name, $file_type, $script_type) = explode(".", basename(__FILE__));
    	$this->readTemplatesFromInput($file_name.'_01.tmpl.php');
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $cacic_common, $cacic_setup;
    	parent::setup();
 		$cacic_common['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';

    	$titulo = $this->oTranslator->_('Cadastro de Software por Estacao');
    	
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
     	
 		
     	$this->addVar('SoftwareEstacao', 'CACIC_URL', CACIC_URL );
     	$this->addVar('SoftwareEstacao_form', 'TITULO', $titulo );
     	$this->addVar('SoftwareEstacao_form', 'DESCRICAO_TITLE', $this->oTranslator->_('Controle de software por estacao') );
     	$this->addVar('SoftwareEstacao_form', 'SEQUENCIAL_TITLE', $this->oTranslator->_('Sequencial', T_SIGLA) );
     	$this->addVar('SoftwareEstacao_form', 'AQUISICAO_PROC_TITLE', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('SoftwareEstacao_form', 'SOFTWARE_TITLE', $this->oTranslator->_('Software') );
     	$this->addVar('SoftwareEstacao_form', 'PATRIMONIO_TITLE', $this->oTranslator->_('Patrimonio') );
     	$this->addVar('SoftwareEstacao_form', 'COMPUTADOR_TITLE', $this->oTranslator->_('Computador') );
     	$this->addVar('SoftwareEstacao_form', 'DATA_AUTORIZACAO_TITLE', $this->oTranslator->_('Data de autorizacao') );

     	$this->addVar('SoftwareEstacao_insert_edit', 'PATRIMONIO_INPUT_LABEL', $this->oTranslator->_('Patrimonio') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'PATRIMONIO_DESTINO_INPUT_LABEL', $this->oTranslator->_('Patrimonio de destino') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'SOFTWARE_INPUT_LABEL', $this->oTranslator->_('Software') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'AQUISICAO_INPUT_LABEL', $this->oTranslator->_('Processo de aquisicao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'COMPUTADOR_INPUT_LABEL', $this->oTranslator->_('Computador') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_FORMATO', $this->oTranslator->_('DD/MM/AAAA', T_SIGLA) );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_AUTORIZACAO_INPUT_LABEL', $this->oTranslator->_('Data de autorizacao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_EXPIRACAO_INPUT_LABEL', $this->oTranslator->_('Data de expiracao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_DESINSTALACAO_INPUT_LABEL', $this->oTranslator->_('Data de desinstalacao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'OBSERVACAO_INPUT_LABEL', $this->oTranslator->_('Observacao') );
     	
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_AUTORIZACAO_HELP', $this->oTranslator->_('Data de autorizacao da instalacao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_EXPIRACAO_HELP', $this->oTranslator->_('Data de expiracao da instalacao') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'DATA_DESINSTALACAO_HELP', $this->oTranslator->_('Data de desinstalacao do software do computador') );
     	
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO', $this->oTranslator->_('Informe esse campo') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO_COMPUTADOR_NAME', $this->oTranslator->_('Informe computador') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO_DATA', $this->oTranslator->_('Informe data valida') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO_PATRIMONIO', $this->oTranslator->_('Informe patrimonio') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO_SOFTWARE', $this->oTranslator->_('Informe software') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'MSG_VALIDACAO_AQUISICAO', $this->oTranslator->_('Informe processo de aquisicao') );
     	
     	$this->addVar('SoftwareEstacao_insert_edit', 'SELECT_OPTION', $this->oTranslator->_('--- Selecione ---') );
     	
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_INCLUIR_TITLE', $this->oTranslator->_('Incluir')." ". strtolower($this->oTranslator->_('software por estacao')));
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_INCLUIR', $this->oTranslator->_('Incluir registro') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_INCLUIR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_SALVAR_TITLE', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_SALVAR', $this->oTranslator->_('Gravar') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_SALVAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_CANCELAR_TITLE', $this->oTranslator->_('Cancelar alteracoes') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_CANCELAR', $this->oTranslator->_('Cancelar') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_CANCELAR_DENY',  $cacic_setup['acl_permission']);
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_RESET_TITLE', $this->oTranslator->_('Restaurar valores') );
     	$this->addVar('SoftwareEstacao_insert_edit', 'BTN_RESET', $this->oTranslator->_('Restaurar') );
     	
     	$this->addVar('SoftwareEstacao_list', 'ACTIONS_DELETE_TITLE', $this->oTranslator->_('Excluir registro') );
     	$this->addVar('SoftwareEstacao_list', 'ACTIONS_EDIT_TITLE', $this->oTranslator->_('Editar registro') );
     	$this->addVar('SoftwareEstacao_actions_acl', 'ACTIONS_TITLE', $this->oTranslator->_('Acoes') );

    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	global $cacic_common, $cacic_setup;
    	$this->setup(); // atribui dados globais
    	 
		if($cacic_setup['acl_permission']=='disabled') // desabilita acões caso sem permissão
		   $this->addVar('SoftwareEstacao_insert_edit', 'acl_permission', $cacic_setup['acl_permission'] );
		elseif($cacic_setup['btn_action_incluir'])
		   $this->addVar('SoftwareEstacao_insert_edit', 'acl_permission', true );
		elseif($cacic_setup['btn_action_edit'])
		   $this->addVar('SoftwareEstacao_insert_edit', 'acl_permission', true );
		else
		   $this->addVar('SoftwareEstacao_insert_edit', 'acl_permission', false );
		
		// desabilita acões caso sem permissão
     	$this->addVar('SoftwareEstacao_actions_acl', 'acl_permission', $cacic_setup['acl_permission'] );
		  
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
		$nr_patrimonio_aux = Security::getString('nr_patrimonio_aux');
		$id_software_aux = Security::getInt('id_software_aux');
		$nr_patrimonio = $nr_patrimonio_aux?$nr_patrimonio_aux:Security::getString('nr_patrimonio');
		$id_software = $id_software_aux?$id_software_aux:Security::getInt('id_software');
		$nm_computador = Security::getString('nm_computador');
		$id_aquisicao = Security::getInt('id_aquisicao');
		$dt_autorizacao = Security::getDate('dt_autorizacao');
		$nr_processo = Security::getString('nr_processo');
		$dt_expiracao_instalacao = Security::getDate('dt_expiracao_instalacao');
		$dt_desinstalacao = Security::getDate('dt_desinstalacao');
		$nr_patr_destino = Security::getString('nr_patr_destino');
		$te_observacao = Security::getString('observacao');
		
		list($dia, $mes, $ano) = explode("/", $dt_autorizacao);
		$dt_autorizacao= date('Y-m-d', strtotime($ano."-".$mes."-".$dia));
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql = '';
    	if($cacic_setup['btn_salvar']) {
		  	// verifica se Itens adiquiridos já está cadastrado
		  	$sql = "select * from softwares_estacao " .
			                           " where id_software = '" . $id_software ."'" .
			                           		" and nr_patrimonio= '" . $nr_patrimonio ."'; ";
		  $db_result = mysql_query($sql);
    	  if(mysql_num_rows($db_result))
			$sql = "update softwares_estacao set nm_computador = '" . $nm_computador ."'".
			                              ", dt_autorizacao = '".$dt_autorizacao."'".
			                              ", nr_processo = '".$nr_processo."'" .
			                              ", dt_expiracao_instalacao = '".$dt_expiracao_instalacao."'" .
			                              ", dt_desinstalacao = '".$dt_desinstalacao."'" .
			                              ", te_observacao = '".$te_observacao."'" .
			                              ", nr_patr_destino = '".$nr_patr_destino."'" .
			                              
			                           " where id_software = '" . $id_software ."'" .
			                           		" and nr_patrimonio= '" . $nr_patrimonio ."'; ";
		  else {
			$sql = "insert into softwares_estacao " .
					              "(nr_patrimonio, id_software, nm_computador, dt_autorizacao, nr_processo, dt_expiracao_instalacao," .
					              " dt_desinstalacao, te_observacao, nr_patr_destino) " .
					       "value (" .
					       		  "'".$nr_patrimonio."', " .
					       		  "'".$id_software."', " .
					       		  "'".$nm_computador."', " .
					       		  "'".$dt_autorizacao."', " .
					       		  "'".$nr_processo."', " .
					       		  "'".$dt_expiracao_instalacao."', " .
					       		  "'".$dt_desinstalacao."', " .
					       		  "'".$te_observacao."', " .
					       		  "'".$nr_patr_destino."' " .
					       		  ");";
		  }
    	}
			                           
    	if($cacic_setup['btn_action_excluir']) {
    		list($software_id, $nr_patrimonio) = explode("_", $cacic_setup['btn_action_excluir']);
    		if($software_id and $nr_patrimonio)
			   $sql = "delete from softwares_estacao " .
			                           " where id_software = '" . $software_id ."'" .
			                           		" and nr_patrimonio= '" . $nr_patrimonio ."'; ";
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
    	
		list($software_id, $nr_patrimonio) = explode("_", $cacic_setup['btn_action_edit']);
		    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$lista_softwares = $this->fillSoftwares($software_id);
    	$this->addRows('Software_insert_edit_list', $lista_softwares );
		
     	$list = array();
     	$count = 0;
     	
    	$sql = "select * from softwares_estacao order by id_software";
    	$db_result = mysql_query($sql);
    	while( $software_estacao = mysql_fetch_assoc($db_result) ) {
    		$count++;
            // monta linha de dados da licenca
			$_arrAux = array( array( 'AQUISICAO_PROC'=>$software_estacao['nr_processo'],
		                             'SOFTWARE_NAME'=> $this->getSoftwareName($software_estacao['id_software']),
		                             'SOFTWARE_ID'=> $software_estacao['id_software'],
		                             'PATRIMONIO_NR'=>$software_estacao['nr_patrimonio'],
		                             'COMPUTADOR_NAME'=>$software_estacao['nm_computador'],
		                             'DATA_AUTORIZACAO'=>date( $this->oTranslator->_('date view format', T_SIGLA), 
		                                                         strtotime($software_estacao['dt_autorizacao'] )),
		                             'SEQUENCIAL'=>$count,
		                             
		                             'SOFTWARE_ID'=>$software_estacao['id_software'],
		                             'PATRIMONIO_NR'=>$software_estacao['nr_patrimonio'],
		                             
		                             'acl_permission'=> $cacic_setup['acl_permission']
		                    ) );
		    $list = array_merge($list, $_arrAux);
		    
		    // Atribui ao formulario os dados a serem editados
		    if($cacic_setup['btn_action_edit'] and ($software_id==$software_estacao['id_software'] and
		                                            $nr_patrimonio==$software_estacao['nr_patrimonio'])) {
		    	
 		       $this->addVar('SoftwareEstacao_insert_edit', 'SOFTWARE_ID', $software_estacao['id_software']);
 		       $this->addVar('SoftwareEstacao_insert_edit', 'PATRIMONIO_NR_DISABLED', "disabled");
 		       $this->addVar('SoftwareEstacao_insert_edit', 'SOFTWARE_DISABLED', "disabled");
 		       $this->addVar('SoftwareEstacao_insert_edit', 'AQUISICAO_PROC', $software_estacao['nr_processo']);
 		       $this->addVar('SoftwareEstacao_insert_edit', 'SOFTWARE_NAME', $software_estacao['id_software']);
 		       $this->addVar('SoftwareEstacao_insert_edit', 'PATRIMONIO_NR', $software_estacao['nr_patrimonio']);		                                                         
 		       $this->addVar('SoftwareEstacao_insert_edit', 'PATRIMONIO_DESTINO', $software_estacao['nr_patr_destino']);		                                                         
		       $this->addVar('SoftwareEstacao_insert_edit', 'COMPUTADOR_NAME', $software_estacao['nm_computador'] );
		       $this->addVar('SoftwareEstacao_insert_edit', 'DATA_AUTORIZACAO', date($this->oTranslator->_('date view format', T_SIGLA), 
		                                                                      strtotime($software_estacao['dt_autorizacao'])) );
		       $this->addVar('SoftwareEstacao_insert_edit', 'DT_EXPIRACAO_INSTALACAO', date($this->oTranslator->_('date view format', T_SIGLA), 
		                                                                      strtotime($software_estacao['dt_expiracao_instalacao'])));
		       $this->addVar('SoftwareEstacao_insert_edit', 'DT_DESINSTALACAO', date($this->oTranslator->_('date view format', T_SIGLA), 
		                                                                      strtotime($software_estacao['dt_desinstalacao'])));
		       $this->addVar('SoftwareEstacao_insert_edit', 'OBSERVACAO', $software_estacao['te_observacao'] );
		       		                                                         
		    }
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('SoftwareEstacao_list', $list );
    	
    }


    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CacicCommon_head');
     	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('SoftwareEstacao');

    	$this->displayParsedTemplate('SoftwareEstacao_form');

    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CacicCommon_messages');
     	$this->displayParsedTemplate('CacicCommon_footer');
    }
 }

?>