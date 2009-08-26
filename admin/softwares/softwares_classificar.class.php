<?php
/**
 * @version $Id: softwares_classificar.class.php 2009-08-17 21:03 harpiain $
 * @package CACIC-Admin
 * @subpackage SoftwaresClassificar
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
 include_once('common/cacic_common.class.php');
 
/**
 * Implementa classificação/reclassificação de Softwares Inventariados
 */
 class Softwares_Classificar extends Cacic_Common {
 	
    function Softwares_Classificar() {
    	parent::Cacic_Common();
    	$titulo = $this->oTranslator->_('Classificacao de softwares');
    	/*
    	 * Inicializa template com textos basicos
    	 */
     	$this->setPageTitle( $titulo );
     	
    	$this->setNamespace('softwareClassificacao');
    	$this->setRoot(dirname(__FILE__));
    	$this->readTemplatesFromInput('softwares_classificar_01.tmpl.php');
    	
     	$this->addVar('SoftwaresClassificar', 'CACIC_URL', CACIC_URL );
     	$this->addVar('SoftwaresClassificar_form', 'TITULO', $titulo );
     	$this->addVar('SoftwaresClassificar_form', 'DESCRICAO', $this->oTranslator->_('Classificacao de softwares conforme tipos possiveis') );
     	$this->addVar('SoftwaresClassificar_form', 'SOFTWARE_CLASSIFICADO_SELECT', '<span class="Aviso">'.$this->oTranslator->_('Apenas os nao classificados?')."</span>" );
     	$this->addVar('SoftwaresClassificar_form', 'SOFTWARE_NAME_TITLE', $this->oTranslator->_('Nome do software') );
     	$this->addVar('SoftwaresClassificar_form', 'NO', $this->oTranslator->_('Nao') );     	
     	$this->addVar('SoftwaresClassificar_form', 'YES', $this->oTranslator->_('Sim') );
     	$this->addRows('SoftwaresType_list', $this->fillListSoftwaresType() );
     	$this->addVar('SoftwaresClassificar_form', 'COLSPAN', 20 );
     	$this->addVar('SoftwaresClassificar_form', 'BTN_SALVAR', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('SoftwaresClassificar_form', 'BTN_SALVAR_DENY',  ($this->isAdminUser()?'enabled':'disabled'));
     	$this->addVar('SoftwaresClassificar_form', 'BTN_RESET', $this->oTranslator->_('Restaurar valores') );
    }
    
	/**
	 * Armazena na "sessao" os dados de configuração padrao
	 * @access public
	 */
    function setup() {
    	global $cacic_common;
    	parent::setup();
 		$cacic_common['padrao'] = 'Definicoes padrao para pre-preenchimento de campos';
    }
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function run() {
    	$this->clearVar('SoftwaresClassificar_form', 'SET_FOCUS');
     	$this->addVar('SoftwaresClassificar_form', 'SET_FOCUS', 'nm_organizacao' );
    	$btn_salvar = Security::read('btn_salvar');
    	if(isset($btn_salvar) and ($btn_salvar)) {
			try {
				$this->salvarDados();
			}
			catch( Exception $erro ) {
				$msg = '<span class="ErroImg"></span>';
				$msg .= '<span class="Erro">'.$erro->getMessage()."</span>";
				$this->setMessageText($msg);
			}    		
    	}
    	$this->fillForm($btn_salvar);
    	$this->showForm();
    } 
    
    /**
     * Executa a configuracao padrão do CACIC
     * @access public
     */
    function salvarDados() {
    	$error = true;
    	$msg = $this->oTranslator->_('Ocorreu erro no processamento... ');
    	/*
    	 * Obtem dados do formulario
    	 */
		$software_classificado = Security::read('software_classificado');
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql_update = '';
		foreach($software_classificado as $id_software => $id_tipo) {
			$sql_update = "update softwares_inventariados set id_tipo_software = " . $id_tipo .
			                                            " where id_software_inventariado = ".$id_software."; ";
						
		/*
		 * Atualiza dados na tabela
		 */				
	    $db_result = mysql_query($sql_update);
	    $error = mysql_errno($this->db_link);
	    $msg .= $this->oTranslator->_('kciq_msg server msg').": ";
	    $msg .= mysql_error($this->db_link);
	    
    	/*
    	 *  Lança execeção se ocorrer erro
    	 */
    	($error) ? $this->throwError($msg):"";
		}

    	$this->setMessageText('<span class="OKImg">'.$this->oTranslator->_('Processamento realizado com sucesso')."</span>");
    }
    
    /**
     * Obtem e preenche dados de formulario - tipos de software
     * @access private
     */
    function fillListSoftwaresType() {
    	$sql = "select * from tipos_software order by id_tipo_software";
    	$db_result = mysql_query($sql);
     	$list = array();
    	while( $tipos = mysql_fetch_assoc($db_result) ) {
			$_arrAux = array( array('SOFTWARE_TYPE_NAME'=>$tipos['te_descricao_tipo_software'] ) );
			$list = array_merge($list, $_arrAux);
    	}
     	return $list;           
    }
    
    /**
     * Obtem e preenche dados de formulario
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function fillForm($_btn_salvar) {
    	$sql = "select * from tipos_software order by id_tipo_software";
    	$db_result = mysql_query($sql);
    	$_tipos_list = array();
    	while( $tipos = mysql_fetch_assoc($db_result) ) {
			$_tipos_list[ $tipos['id_tipo_software'] ]['te_descricao_tipo_software'] = $tipos['te_descricao_tipo_software'];
    	}
    	
    	/*
    	 * Obtem dados do formulario
    	 */
		$software_classificado = Security::read('software_classificado');
		$software_nao_classificado = Security::read('software_nao_classificado');
		
     	$this->addVar('SoftwaresClassificar_form', 'YES_CHECKED', ((!isset($software_nao_classificado) or $software_nao_classificado)?'checked':'') );

		$where = " where id_tipo_software=0 ";
		if (isset($software_nao_classificado) and ($software_nao_classificado==0)) {
		   $where = " "; // esvazia a condicional de listagem
		   $this->addVar('SoftwaresClassificar_form', 'NO_CHECKED', 'checked' );
		}

    	$sql_soft_count = "select count(*) as count from softwares_inventariados ".$where;
    	$db_result_soft = mysql_query($sql_soft_count);
    	$count = mysql_fetch_row($db_result_soft);
     	$this->setPageTotalItems($count[0]);
     	$this->setPageCurrent();
    	$sql_soft = "select * from softwares_inventariados ".$where." limit ".$this->getPageItems()." offset ".$this->getPageFristItem();
    	$db_result_soft = mysql_query($sql_soft);
     	$list = array();
    	while( $soft = mysql_fetch_assoc($db_result_soft) ) {
    		$softwares_classificar_tipo = "";
    		foreach($_tipos_list as $tipo_id => $tipo_valor) {
		    	if($_btn_salvar) {
			    	/* Obtem dados do formulario */
			    	if($software_classificado[$soft['id_software_inventariado']])
			    		$checked = ($tipo_id == $software_classificado[$soft['id_software_inventariado']] )?" checked ":"";
			    	else /* Obtem dados do banco de dados */
			    	    $checked = ($tipo_id == $soft['id_tipo_software'] )?" checked ":"";
			    	}
		    	else {
			    	/* Obtem dados do banco de dados */
			    	 $checked = ($tipo_id == $soft['id_tipo_software'] )?" checked ":"";
		    	}
		    	
    			$softwares_classificar_tipo .= '
							<td>
							  <input type="radio" name="software_classificado['.$soft['id_software_inventariado'].']"'
							                                 .' value="'.$tipo_id.'"'
							                                 .' title="'.$tipo_valor['te_descricao_tipo_software'].'" '
							                                 .$checked
							                                 .' />
							</td>
    					';
            }
            // monta linha de dados do software
			$_arrAux = array( array( 'SOFTWARE_NAME'=>$soft['nm_software_inventariado'],
		                             'SOFTWARE_ID'=>$soft['id_software_inventariado'],
		                             'SOFTWARESCLASSIFICAR_TIPO'=>$softwares_classificar_tipo
		                    ) );
			                          
		    $list = array_merge($list, $_arrAux);                
    	}
     	$software_tipos_list =  $list;           
    	
    	/*
    	 * Preenche formulário com dados
    	 */
    	$this->addRows('SoftwaresInventariados_list', $software_tipos_list );
    	
    }

    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
    	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('CacicCommon_head');
     	// Monta cabecalho da pagina
     	$this->displayParsedTemplate('SoftwaresClassificar');

    	// Monta corpo da pagina
     	$page_to_show = $this->fillPagination();
     	$this->addVar('SoftwaresClassificar_form', 'PAGE_CURRENT', $page_to_show);
     	$this->addVar('SoftwaresClassificar_form', 'PAGE_NEXT', $page_to_show+1);
    	$this->displayParsedTemplate('SoftwaresClassificar_form');
    	
    	// Mostra paginacao
     	$this->displayParsedTemplate('CacicCommon_pagination');

    	// Monta area de mensages e rodape da pagina
     	$this->displayParsedTemplate('CacicCommon_messages');
     	$this->displayParsedTemplate('CacicCommon_footer');
    }
 }

?>