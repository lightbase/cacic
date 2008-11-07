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
    	 * Inicializa template com textos basicos
    	 */
    	$this->setNamespace('cfgPadrao');
    	$this->readTemplatesFromInput('configuracao_padrao_01.tmpl.php');
    	$this->clearVar('CommonSetup_head', 'CACIC_TITLE');
     	$this->addVar('CommonSetup_head', 'CACIC_TITLE', $this->oTranslator->_('Configuracao Padrao') );
     	
     	$this->addVar('StandardSetup', 'CACIC_URL', CACIC_URL );
     	$this->addVar('StandardSetup_form', 'TITULO', $this->oTranslator->_('Configuracao Padrao') );
     	$this->addVar('StandardSetup_form', 'DESCRICAO', $this->oTranslator->_('Definir as configuracoes para sugestoes em formularios') );
     	$this->addVar('StandardSetup_form', 'NM_ORGANIZACAO_TITLE', $this->oTranslator->_('Nome da organizacao') );
     	$this->addVar('StandardSetup_form', 'TE_SERVUPDT_STD_TITLE', $this->oTranslator->_('Nome ou IP do servidor de atualizacao (FTP)') );
     	$this->addVar('StandardSetup_form', 'TE_SERVCACIC_STD_TITLE', $this->oTranslator->_('Nome ou IP do servidor de aplicacao (gerente)') );
     	$this->addVar('StandardSetup_form', 'EXIBE_ERROS_CRITICOS_TITLE', $this->oTranslator->_('Exibir erros criticos aos usuarios') );
     	$this->addVar('StandardSetup_form', 'EXIBE_BANDEJA_TITLE', $this->oTranslator->_('Exibir o icone do CACIC na bandeja (systray)') );
     	$this->addVar('StandardSetup_form', 'TE_MACADDR_INVALID_TITLE', $this->oTranslator->_('Enderecos MAC a desconsiderar') );
     	$this->addVar('StandardSetup_form', 'TE_MACADDR_INVALID_HELP', $this->oTranslator->_('Atencao: informe os enderecos separados por virgulas') );
     	$this->addVar('StandardSetup_form', 'TE_JANELAS_EXCECAO_TITLE', $this->oTranslator->_('Aplicativos (janelas) a evitar') );
     	$this->addVar('StandardSetup_form', 'TE_JANELAS_EXCECAO_HELP', $this->oTranslator->_('Evita que o Gerente de Coletas seja acionado enquanto tais aplicativos (janelas) estiverem ativos') );
     	$this->addVar('StandardSetup_form', 'BTN_SALVAR', $this->oTranslator->_('Gravar alteracoes') );
     	$this->addVar('StandardSetup_form', 'BTN_RESET', $this->oTranslator->_('Restaurar valores') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_TITLE', $this->oTranslator->_('Graficos a serem exibidos') );
     	$this->addVar('StandardSetup_form', 'RESOLUCAO_GRAFICO_TITLE', $this->oTranslator->_('Resolucao dos graficos a serem exibidos') );
     	$this->addVar('StandardSetup_form', 'NU_RESOLUCAO_GRAFICO_H_TITLE', $this->oTranslator->_('Altura') );
     	$this->addVar('StandardSetup_form', 'NU_RESOLUCAO_GRAFICO_W_TITLE', $this->oTranslator->_('Largura') );
     	$this->addVar('StandardSetup_form', 'NU_REL_MAXLINHAS_TITLE', $this->oTranslator->_('Quantidade máxima de linhas em relatorios') );
     	$this->addVar('StandardSetup_form', 'NU_REL_MAXLINHAS_HELP', $this->oTranslator->_('Quantidade máxima de linhas em relatorios') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_SO_TITLE', $this->oTranslator->_('Sistemas operacionais') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_ACESSOS_TITLE', $this->oTranslator->_('Acessos') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_LOCAIS_TITLE', $this->oTranslator->_('Locais') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_ACESSOSLOCAIS_TITLE', $this->oTranslator->_('Acessos locais') );
     	$this->addVar('StandardSetup_form', 'EXIBE_JANELAPATR_TITLE', $this->oTranslator->_('Exibe janela de patrimonio') );
     	$this->addVar('StandardSetup_form', 'SENHA_AGENTE_TITLE', $this->oTranslator->_('Senha padrao para administrar o agente') );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEOUTROS_TITLE', $this->oTranslator->_('Outros ') );
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
    	$this->fillForm($btn_salvar);
    	if(isset($btn_salvar) and ($btn_salvar)) {
			try {
				$this->salvarDados();
			}
			catch( Exception $erro ) {
				$msg = '<span class="ErroImg"></span>';
				$msg .= '<span class="Erro">'.$erro->getMessage()."</span>";
				$this->showMessage($msg);
			}    		
    	}
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
    	$in_exibe_erros_criticos = (Security::read('in_exibe_erros_criticos'))?"S":"N"; 
    	$nu_rel_maxlinhas = (Security::read('nu_rel_maxlinhas'))?Security::read('nu_rel_maxlinhas'):0; 
    	$nu_resolucao_grafico_h = (Security::read('nu_resolucao_grafico_h'))?Security::read('nu_resolucao_grafico_h'):0; 
    	$nu_resolucao_grafico_w = (Security::read('nu_resolucao_grafico_w'))?Security::read('nu_resolucao_grafico_w'):0; 
    	$in_exibe_bandeja = (Security::read('in_exibe_bandeja'))?"S":"N"; 
    	$cs_abre_janela_patr = (Security::read('cs_abre_janela_patr'))?"S":"N"; 
    	$nm_organizacao = (Security::read('nm_organizacao'))?Security::read('nm_organizacao'):""; 
    	$te_senha_adm_agente = (Security::read('te_senha_adm_agente'))?Security::read('te_senha_adm_agente'):""; 
    	$te_serv_updates_padrao = (Security::read('te_serv_updates_padrao'))?Security::read('te_serv_updates_padrao'):""; 
    	$te_serv_cacic_padrao = (Security::read('te_serv_cacic_padrao'))?Security::read('te_serv_cacic_padrao'):""; 
    	$te_enderecos_mac_invalidos = (Security::read('te_enderecos_mac_invalidos'))?Security::read('te_enderecos_mac_invalidos'):""; 
    	$te_janelas_excecao = (Security::read('te_janelas_excecao'))?Security::read('te_janelas_excecao'):"";
    	$te_exibe_graficos = '';
    	$te_exibe_graficos .= (Security::read('te_exibe_graficos_so'))?"[so]":"";
    	$te_exibe_graficos .= (Security::read('te_exibe_graficos_acessos'))?"[acessos]":"";
    	$te_exibe_graficos .= (Security::read('te_exibe_graficos_acessoslocais'))?"[acessos_locais]":"";
    	$te_exibe_graficos .= (Security::read('te_exibe_graficos_locais'))?"[locais]":"";
    	
    	/*
    	 * monta sql de atualizacao dos dados padrao
    	 */
    	$sql_update = 'UPDATE configuracoes_padrao SET ';
    	$sql_update .= "in_exibe_erros_criticos = '$in_exibe_erros_criticos', 
						in_exibe_bandeja = '$in_exibe_bandeja',
						nu_exec_apos = 10,
						nu_resolucao_grafico_h = '$nu_resolucao_grafico_h', 
						nu_resolucao_grafico_w = '$nu_resolucao_grafico_w', 
						nu_rel_maxlinhas = $nu_rel_maxlinhas,
						nm_organizacao = '$nm_organizacao',
						nu_intervalo_exec = 4,
						nu_intervalo_renovacao_patrim = 0,
						te_senha_adm_agente = '$te_senha_adm_agente',
						te_serv_updates_padrao = '$te_serv_updates_padrao',
						te_serv_cacic_padrao =  '$te_serv_cacic_padrao',
						te_enderecos_mac_invalidos = '$te_enderecos_mac_invalidos',
						te_janelas_excecao = '$te_janelas_excecao', 
						cs_abre_janela_patr = '$cs_abre_janela_patr',
						id_default_body_bgcolor = '#EBEBEB',
						te_exibe_graficos = '$te_exibe_graficos'";
						
		$sql = 'select in_exibe_erros_criticos from configuracoes_padrao';
    	$db_link = mysql_query($sql);
		if(mysql_num_rows($db_link)<1) {
			$sql = "INSERT INTO configuracoes_padrao (`in_exibe_erros_criticos`) VALUES ('N')";
	    	$db_link = mysql_query($sql);
		}
		
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

    	$this->showMessage('<span class="OKImg">'.$this->oTranslator->_('Processamento realizado com sucesso')."</span>");
    }
    
    /**
     * Obtem e preenche dados de formulario
     * @access private
     * @param string $btn_salvar Se botao para salvar foi acionado
     */
    function fillForm($btn_salvar) {
    	if($btn_salvar) {
	    	/*
	    	 * Obtem dados do formulario
	    	 */
	    	$nm_organizacao = (Security::read('nm_organizacao'))?Security::read('nm_organizacao'):""; 
	    	$nu_rel_maxlinhas = (Security::read('nu_rel_maxlinhas'))?Security::read('nu_rel_maxlinhas'):""; 
	    	$nu_resolucao_grafico_h = (Security::read('nu_resolucao_grafico_h'))?Security::read('nu_resolucao_grafico_h'):0;
	    	$nu_resolucao_grafico_w = (Security::read('nu_resolucao_grafico_w'))?Security::read('nu_resolucao_grafico_w'):0; 
	    	$te_senha_adm_agente = (Security::read('te_senha_adm_agente'))?Security::read('te_senha_adm_agente'):""; 
	    	$te_serv_updates_padrao = (Security::read('te_serv_updates_padrao'))?Security::read('te_serv_updates_padrao'):""; 
	    	$te_serv_cacic_padrao = (Security::read('te_serv_cacic_padrao'))?Security::read('te_serv_cacic_padrao'):""; 
	    	$te_enderecos_mac_invalidos = (Security::read('te_enderecos_mac_invalidos'))?Security::read('te_enderecos_mac_invalidos'):""; 
	    	$te_janelas_excecao = (Security::read('te_janelas_excecao'))?Security::read('te_janelas_excecao'):"";
	    	$te_exibe_graficos_so = (Security::read('te_exibe_graficos_so'))?"checked":"";
	    	$te_exibe_graficos_acessos = (Security::read('te_exibe_graficos_acessos'))?"checked":"";
	    	$te_exibe_graficos_acessoslocais = (Security::read('te_exibe_graficos_acessoslocais'))?"checked":"";
	    	$te_exibe_graficos_locais = (Security::read('te_exibe_graficos_locais'))?"checked":"";
	    	$in_exibe_erros_criticos = (Security::read('in_exibe_erros_criticos'))?"checked":""; 
	    	$in_exibe_bandeja = (Security::read('in_exibe_bandeja'))?"checked":""; 
	    	$cs_abre_janela_patr = (Security::read('cs_abre_janela_patr'))?"checked":""; 
    	}
    	else {
	    	/*
	    	 * Obtem dados do banco de dados
	    	 */
	    	$sql = "select * from configuracoes_padrao";
	    	$db_result = mysql_query($sql);
	    	$cfgStdData = mysql_fetch_assoc($db_result);
	    	
	    	$nm_organizacao = $cfgStdData['nm_organizacao']; 
			$nu_rel_maxlinhas = $cfgStdData['nu_rel_maxlinhas'];
			$nu_resolucao_grafico_h = $cfgStdData['nu_resolucao_grafico_h'];
			$nu_resolucao_grafico_w = $cfgStdData['nu_resolucao_grafico_w']; 
	    	$te_senha_adm_agente = $cfgStdData['te_senha_adm_agente']; 
	    	$te_serv_updates_padrao = $cfgStdData['te_serv_updates_padrao']; 
	    	$te_serv_cacic_padrao = $cfgStdData['te_serv_cacic_padrao']; 
	    	$te_enderecos_mac_invalidos = $cfgStdData['te_enderecos_mac_invalidos']; 
	    	$te_janelas_excecao = $cfgStdData['te_janelas_excecao'];
	    	$te_exibe_graficos_so = (strpos($cfgStdData['te_exibe_graficos'], "[so]")===false)?"":"checked";
	    	$te_exibe_graficos_acessos = (strpos($cfgStdData['te_exibe_graficos'], "[acessos]")===false)?"":"checked";
	    	$te_exibe_graficos_acessoslocais = (strpos($cfgStdData['te_exibe_graficos'], "[acessos_locais]")===false)?"":"checked";
	    	$te_exibe_graficos_locais = (strpos($cfgStdData['te_exibe_graficos'], "[locais]")===false)?"":"checked";
	    	$in_exibe_erros_criticos = ($cfgStdData['in_exibe_erros_criticos']=="S")?"checked":""; 
	    	$in_exibe_bandeja = ($cfgStdData['in_exibe_bandeja']=="S")?"checked":""; 
	    	$cs_abre_janela_patr = ($cfgStdData['cs_abre_janela_patr']=="S")?"checked":""; 
    	}
    	
    	/*
    	 * Preenche formulário com dados
    	 */
     	$this->addVar('StandardSetup_form', 'NM_ORGANIZACAO', $nm_organizacao );
     	$this->addVar('StandardSetup_form', 'NU_REL_MAXLINHAS', $nu_rel_maxlinhas );
     	$this->addVar('StandardSetup_form', 'NU_RESOLUCAO_GRAFICO_H', $nu_resolucao_grafico_h );
     	$this->addVar('StandardSetup_form', 'NU_RESOLUCAO_GRAFICO_W', $nu_resolucao_grafico_w );
     	$this->addVar('StandardSetup_form', 'TE_SERVUPDT_STD', $te_serv_updates_padrao );
     	$this->addVar('StandardSetup_form', 'TE_SERVCACIC_STD', $te_serv_cacic_padrao );
     	$this->addVar('StandardSetup_form', 'EXIBE_ERROS_CRITICOS', $in_exibe_erros_criticos );
     	$this->addVar('StandardSetup_form', 'EXIBE_BANDEJA', $in_exibe_bandeja );
     	$this->addVar('StandardSetup_form', 'TE_MACADDR_INVALID', $te_enderecos_mac_invalidos );
     	$this->addVar('StandardSetup_form', 'TE_JANELAS_EXCECAO', $te_janelas_excecao );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_SO', $te_exibe_graficos_so );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_ACESSOS', $te_exibe_graficos_acessos );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_LOCAIS', $te_exibe_graficos_locais );
     	$this->addVar('StandardSetup_form', 'TE_EXIBEGRAFICOS_ACESSOSLOCAIS', $te_exibe_graficos_acessoslocais );
     	$this->addVar('StandardSetup_form', 'EXIBE_JANELAPATR', $cs_abre_janela_patr );
     	$this->addVar('StandardSetup_form', 'SENHA_AGENTE', $te_senha_adm_agente );
    }
    
    /**
     * Mostra formulario da configuracao padrao
     * @access private
     */
    function showForm() {
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
