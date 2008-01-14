<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Arquivo que contem a classe de traducao e outros para a traducao de aplicacoes
 *
 * As aplicacoes baseadas nesse arquivo/classe deverao adaptar-se ao modo como este
 * monta os arquivos de idiomas e seus requisitos de codificacao de idioma.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3 of the GNU/GPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/gpl.html.  If you did not receive a copy of
 * the GNU/GPL License and are unable to obtain it through the web, please
 * send a note to harpiain at users.sourceforge.net so I can mail you a copy immediately.
 *
 * Um exemplo de como usar a classe:
 * <code>
 *  
 * define(TRANSLATOR_PATH, "../include/phpTranslator/");
 * define(TRANSLATOR_PATH_URL, "../include/phpTranslator/");
 *
 * if(!@include_once( TRANSLATOR_PATH.'/Translator.php'))
 *   echo "<h1>There is a trouble with phpTranslator package. 
 *              It isn't found.</h1>";
 * 
 * $_objTranslator = new Translator( USER_LOCALE );
 *
 * $_objTranslator->setLangFilePath( "/myapplic_path_lang_files/" );
 * $_objTranslator->setURLPath( TRANSLATOR_PATH_URL );
 * $_objTranslator->initStdLanguages();
 * if(CODING)   
 *   $_objTranslator->translatorGUI( false );
 * elseif(TRANSLATING)
 *   $_objTranslator->translatorGUI();
 * else   
 *   echo $_objTranslator->getText( 'some text to translate and
 *                         be showed after had been registred.' );
 * </code>
 * 
 * @category   PHP-Tool
 * @package    phpTranslator
 * @author     Adriano dos Santos Vieira (harpiain) <harpiain@users.sourceforge.net>
 * @copyright  2005-2007 Adriano dos Santos Vieira
 * @license    http://www.gnu.org/copyleft/gpl.html  GNU/GPL License 3.0
 * @version    subVersion: $Id: phpTranslator, v 0.2.0-40 feb/23/2006 $
 * @link       http://obiblioopac4j.sourceforge.net
 * @see        Translator()
 * @since      File available since Release 0.0.1
 * @todo  The package TODO/Roadmap are: {@example ToDo.txt}
 */

/**
 * Constante que define o "ENTER" e o "SALTO DE LINHA" nos arquivos de idiomas
 * @access private
 */
define(T_CRLF, chr(13) . chr(10));

/**
 * Constante que define a cor do texto ainda nao traduzido
 * @access private
 */
define(T_BG_COLOR_TRADUZ, "#e7d4d4");

/**
 * Constrante que define se apenas traducao
 * @access private
 */
define(T_TRANSLATOR, true);

/**
 * Constante que define se codificacao e traducao
 * @access private
 */
define(T_CODER, false);

/**
 * Classe para realizar a traducao de um texto com base em arquivos pre-definidos
 *
 * As aplicacoes baseadas nessa classe deverao adaptar-se ao modo como esta classe <br>
 * monta os arquivos de idiomas e seus requisitos de codificacao de idioma.
 *
 * @category   PHP-Tool
 * @package    phpTranslator
 * @author     Adriano dos Santos Vieira (harpiain) <harpiain@users.sourceforge.net>
 */
Class Translator {

	/**
	* path para para a propria classe
	* @access private
	* @var string
	*/
	var $classSelfPath = "./";

	/**
	* path relativo (URL) para a classe
	* @access private
	* @var string
	*/
	var $translatorUrlPath = "./";

	/**
	* path para o arquivo de mensagens
	* @access private
	* @var sting
	*/
	var $languageFilePath = "./";

	/**
	* prefixo para o arquivo de mensagens
	* @access private
	* @var $languageFilePrefix
	*/
	var $languageFilePrefix = "language.";

	/**
	* sufixo para o arquivo de mensagens
	* @access private
	* @var string
	*/
	var $languageFileSufix = ".inc.php";

	/**
	* contera as mensagens a serem exibidas para a aplicacao de 2 idiomas (source and target)
	* 
	* @access private
	* @var array
	*/
	var $translated_text = "";

	/**
	* contera as mensagens padrao
	* 
	* @access private
	* @var array
	*/
	var $translatedTextStd = array ();

	/**
	* contera as mensagens traduzidas (ex: en-us)
	* 
	* @access private
	* @var array
	*/
	var $translatedTextTgt = array ();

	/**
	* contera as mensagens da propria classe traduzidas ou padrao
	* 
	* @access private
	* @var array
	* @since v 0.2.0-40
	*/
	var $translatedTextSelf = array ();

	/**
	* padrao da aplicacao
	* 
	* @access private
	* @var string
	*/
	var $languageStd = "pt-br";

	/**
	* preferencia do usuario
	* @access private
	* @var string
	*/
	var $languageUser = "";

	/**
	* se traduz textos padrao conforme idioma: 
	*    TRUE - tenta traduzir todos os textos;
	*   FALSE - nao traduz os textos
	* 
	* @access private
	* @var boolean
	*/
	var $translateMessage = true;

	/**
	* se os arquivos de idioma estarao em sub-diretorios, conforme abrevitura ISO I18N
	* ex: pt-br/language.pt-br.inc.php, en/language.en.inc.php ...
	*    TRUE - os arquivos estarao em subdiretorios;
	*   FALSE - os arquivos nao estarao em subdiretorios
	* 
	* @access private
	* @var boolean
	* @since v 0.2.0-40
	*/
	var $languageFilesubdir = false;

	/**
	* se os arquivos de idioma estarao seccionados, conforme contextualizacao
	* ex: prefix.SECTION.sufix == language.admin.pt-br.inc.php ...
	*    TRUE - os arquivos estarao seccionados;
	*   FALSE - os arquivos nao estarao seccionados
	* 
	* @access private
	* @var boolean
	* @since v 0.2.0-40
	*/
	var $languageFileInSections = false;

	/**
	* devera conter as secoes dos arquivos de idiomas, conforme contextualizacao
	*  
	* <code>
	*  ...
	*  $_lang_sections = array('phpTranslator' => 'textos da classe de traducao',
	*                                         'admin'             => 'Textos da secao administrativa',
	*                                         'home'              => 'textos da secao principal',
	*                                         'and son on'      => 'e assim por diante...');
	*  ...
	* </code>
	* @access private
	* @var array
	* @since v 0.2.0-40
	*/
	var $languageSections = array ();

	/**
	* devera conter a secao ativa a ser mostrada/traduzida, quando fizer uso de secoes
	*  
	* @access private
	* @var array
	* @since v 0.2.0-40
	*/
	var $languageSectionActive = "";

	/**
	* possiveis mensagens retornadas pela classe
	* @access private
	* @var string
	*/
	var $mensagem = "";

	/**
	* se ocorreu erro em um metodo - true = ocorreu erro; false = nao ocorreu erro
	* @access private
	* @var boolean
	*/
	var $error = false;

	/**
	* Tamanho para o codigo do idioma
	* @var numeric
	* @access private
	*/
	var $messageCountryLen = 10;

	/**
	* Tamanho para o codigo da mensagem
	* @var numeric
	* @access private
	*/
	var $messageCodeLen = 100;

	/**
	* Tamanho para o tipo da mensagem
	* @var numeric
	* @access private
	*/
	var $messageTypeLen = 10;

	/**
	* Tamanho para a abreviacao
	* @var numeric
	* @access private
	*/
	var $messageAbbrLen = 10;

	/**
	* Tamanho para o contextualizacao (ex: admin, config, preferences, front-end etc)
	* @var numeric
	* @access private
	*/
	var $messageContextLen = 10;

	/**
	* contera as mensagens a serem exibidas para traducao
	* 
	* @access private
	* @var array
	*/
	var $arrLanguageLang = array ();

	/**
	* contera as mensagens a serem listadas
	* 
	* @access private
	* @var array
	*/
	var $arrLanguageList = array ();

	/**
	* idioma do browser
	* @var string
	* @access private
	*/
	var $_browserLanguage = '';

	/**
	* Se mostra todas as mensagens geradas pela aplicacao
	* @var boolean
	* @access private
	*/
	var $_debugTranslator = false;

	/**
	* Objeto template
	* @var object
	* @access private
	*/
	var $_objTmpl;

	/**
	 * Metodo contrutor para realizar a traducao de um texto com base em arquivos pre-definidos
	 * @access public
	 * @name   Translator
	 * @param  string $_abbr_i18n_tgt - Deve ser informado o idioma destino para a aplicacao
	 * @param  string $_langFilePath  - path para o arquivo de mensagens
	 * @param  string $_abbr_i18n_src - Deve ser informado o idioma padrao da aplicacao 
	 *                                  (padrao "en" = English)
	 */
	function Translator($_abbr_i18n_tgt = '', $_langFilePath = '', $_abbr_i18n_src = 'en-us') {

		$_browserLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		if (strpos($_browserLanguage, ",")) {
			$_browserLanguage = substr($_browserLanguage, 0, strpos($_browserLanguage, ","));
		}
		$this->_browserLanguage = $_browserLanguage;

		$this->classSelfPath = dirname(__FILE__);

		if (!empty ($_abbr_i18n_src))
			$this->SetLanguage($_abbr_i18n_src);

		if (!empty ($_abbr_i18n_tgt))
			$this->SetLanguage($_abbr_i18n_tgt, 'target');

		/*
		 * busca a propia traducao
		 */
		$this->setLangFilePath($this->classSelfPath . "/languages/");
		$this->translatedTextSelf = $this->_getLangFile($_abbr_i18n_tgt);
		if ($this->error) {
			$this->translatedTextSelf = $this->_getLangFile($_abbr_i18n_src);
			if ($this->error) {
				$this->translatedTextSelf = $this->_getLangFile("en-us");
			}
		}

		if (!empty ($_langFilePath))
			$this->setLangFilePath($_langFilePath);

	} // end func: Translator

	/**
	 * Prove a interface de cadastramento de codigos de mensagems ou a de traducao
	 *
	 * @access public
	 * @param boolean $_translate_only FALSE - Se apenas para traduzir
	 *                                  TRUE - tambem para fazer manutencao (default)
	 */
	function translatorGUI($_translate_only = true) {

		// objeto para o template
		$_objTmpl = & $this->_createTemplate();
		$_objTmpl->setRoot($this->classSelfPath . '/templates');

		// Dados de inicializacao
		if (!isset ($_POST['mnt_lang_action']))
			$_langAction = 'listar';
		elseif (array_key_exists('tradutor', $_POST['mnt_lang_action'])) $_langAction = 'tradutor';
		elseif (array_key_exists('inserir', $_POST['mnt_lang_action'])) $_langAction = 'inserir';
		elseif (array_key_exists('alterar', $_POST['mnt_lang_action'])) $_langAction = 'alterar';
		elseif (array_key_exists('excluir', $_POST['mnt_lang_action'])) $_langAction = 'excluir';
		elseif (array_key_exists('listar', $_POST['mnt_lang_action'])) $_langAction = 'listar';
		elseif (array_key_exists('salvar', $_POST['mnt_lang_action'])) $_langAction = 'salvar';
		elseif (array_key_exists('create_lang', $_POST['mnt_lang_action'])) $this->_createLanguageDef($_objTmpl);

		if (!empty ($_POST['tag_active']))
			$this->languageSectionActive = $_POST['tag_active'];

		$this->initStdLanguages();

		$_list_lang = false;
		$_abbr_i18n_save = $this->languageStd;
		$_mnt_language = $_POST['mnt_language'];
		$_lang_code_selected = $_POST['mnt_code_selected'];
		$_lang_data_selected[$_lang_code_selected] = ($_POST['mnt_lang_data'][$_lang_code_selected]);

		// Variaveis para o Template

		// fim de variaveis para o template

		// inicio de Montagem pagina HTML
		$_objTmpl->readTemplatesFromInput('translate_mnt_tmpl.html');
		$_objTmpl->addVar('ini_page_form', 'classSelfPath', $this->classSelfPath);
		$_objTmpl->addVar('ini_page_form', 'TRANSLATOR_URL_PATH', $this->translatorUrlPath);
		$_objTmpl->addVar('tmplTagHeader', 'TRANSLATOR_URL_PATH', $this->translatorUrlPath);

		// Se acionado o botao para traduzir, retira-o e mostra o SALVAR/RESTAURAR
		if ($_translate_only) {
			$_objTmpl->addVar('ini_page_form_btn', 'mnt_btn_show', "translate");
		}
		if (isset ($_POST['save_translation'])) {
			$this->_langSave($_POST['lang_target'], $this->languageUser);
		}

		if (defined('_VALID_MOS'))
			$_objTmpl->addVar('ini_page_form_mos', 'mos_page', "inside");

		if ($_langAction == 'excluir') {
			$this->_langCodeDelete($_lang_data_selected);
			$_list_lang = true;
			$this->_langSave($this->translatedTextStd, $_abbr_i18n_save);
		}

		if ($_langAction == 'salvar') {
			$_list_lang = true;
			$this->translatedTextStd[$this->stripContraBarra($_mnt_language['code'])] = $_mnt_language;
			$this->_langSave($this->translatedTextStd, $_abbr_i18n_save);
		}

		// (re)constroi os arrays para o template
		if (!empty ($_POST['tag_active']))
			$this->languageSectionActive = $_POST['tag_active'];

		$this->initStdLanguages();
		$this->buildArrayVars();

		$titulo = $this->getText('#phpTranslator_page_title#');
		$_objTmpl->addVar('ini_page_form', 'titulo', $titulo);
		$_objTmpl->addVar('ini_page_form', 'PHPTRANSLATOR_EXCLUIR_CODIGO', $this->getText('#phpTranslator_excluir codigo#'));
		$_objTmpl->displayParsedTemplate('ini_page_form');
		$this->showTagHeader(& $_objTmpl);

		$_objTmpl->addVar('ini_page_form_btn', 'BTN_INSERIR', $this->getText('#phptranslator_inserir#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_CREATE_LANG', $this->getText('#phptranslator_criar idioma#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_ALTERAR', $this->getText('#phptranslator_alterar#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_EXCLUIR', $this->getText('#phptranslator_excluir#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_LISTAR', $this->getText('#phptranslator_listar#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_TRADUZIR', $this->getText('#phptranslator_traduzir#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_SALVAR', $this->getText('#phptranslator_salvar#'));
		$_objTmpl->addVar('ini_page_form_btn', 'BTN_RESTAURAR', $this->getText('#phptranslator_restaurar#'));

		$_objTmpl->addVar('ini_page_form_btn', 'MSG_SELECT_ONE_CHANGE', $this->getText('#phptranslator_falta selecionar mensagem a ser alterada#'));

		$_objTmpl->addVar('ini_page_form_btn', 'MSG_SELECT_ONE_DELETE', $this->getText('#phptranslator_falta selecionar mensagem a ser excluida#'));

		$_objTmpl->addVar('translate_mnt', 'BTN_SALVAR', $this->getText('#phptranslator_salvar#'));
		$_objTmpl->addVar('translate_mnt', 'BTN_RESTAURAR', $this->getText('#phptranslator_restaurar#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING', $this->getText('#phptranslator_codificacao de idioma padrao#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_CONTEXT', $this->getText('#phptranslator_contexto da mensagem#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_LANG', $this->getText('#phptranslator_idioma#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_MSG_CODE', $this->getText('#phptranslator_codigo da mensagem#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_MSG_TYPE', $this->getText('#phptranslator_tipo da mensagem#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_MSG_ABBR', $this->getText('#phptranslator_sigla para a mensagem#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_MSG', $this->getText('#phptranslator_mensagem#'));
		$_objTmpl->addVar('translate_mnt', 'STANDARD_LANG_CODING_ADVISE', $this->getText('#phptranslator_mnt_code_advise#'));

		$_objTmpl->addVar('translate_list_head', 'STANDARD_LANG_REPORTING', $this->getText('#phptranslator_listagem de mensagens padrao codificadas#'));

		$_objTmpl->displayParsedTemplate('form_buttons');

		if (($_langAction !== 'tradutor' and $_langAction !== 'excluir' and $_langAction !== 'listar' and !$_translate_only)) {

			$_show_form = true;

			$_objTmpl->addVar('translate_mnt', "LANG_COUNTRY_LEN", $this->messageCountryLen);
			$_objTmpl->addVar('translate_mnt', "LANG_CODE_LEN", $this->messageCodeLen);
			$_objTmpl->addVar('translate_mnt', "LANG_CONTEXT_LEN", $this->messageContextLen);
			$_objTmpl->addVar('translate_mnt', "LANG_TYPE_LEN", $this->messageTypeLen);
			$_objTmpl->addVar('translate_mnt', "LANG_ABBR_LEN", $this->messageAbbrLen);

			if (isset($_POST['mnt_lang_action']) and array_key_exists('alterar', $_POST['mnt_lang_action'])) {
				if (empty ($_lang_code_selected))
					$_show_form = false;

				$_objTmpl->addVar('translate_mnt', "LANG_COUNTRY", $this->stripContraBarra($_lang_data_selected[$_lang_code_selected]['lang']));
				$_objTmpl->addVar('translate_mnt', "LANG_CODE_DISABLED", 'disabled');
				$_objTmpl->addVar('translate_mnt', "LANG_CODE", $this->stripContraBarra($_lang_code_selected));
				$_objTmpl->addVar('translate_mnt', "LANG_CONTEXT", $this->stripContraBarra($_lang_data_selected[$_lang_code_selected]['context']));
				$_objTmpl->addVar('translate_mnt', "LANG_TYPE", $this->stripContraBarra($_lang_data_selected[$_lang_code_selected]['type']));
				$_objTmpl->addVar('translate_mnt', "LANG_ABBR", $this->stripContraBarra($_lang_data_selected[$_lang_code_selected]['abbr']));
				$_objTmpl->addVar('translate_mnt', "LANG_MESSAGE", $this->stripContraBarra($_lang_data_selected[$_lang_code_selected]['text']));
			} else {
				$_objTmpl->addVar('translate_mnt', "LANG_COUNTRY", $this->languageStd);
			}

			if ($_show_form)
				$_objTmpl->displayParsedTemplate('translate_mnt');
			$_list_lang = true;
		}

		if (!$_translate_only and ($_langAction == 'listar' or $_list_lang)) {
			$_objTmpl->displayParsedTemplate('translate_list_head');
			if ($this->arrLanguageList)
				foreach ($this->arrLanguageList as $_keys => $_key) {
					$_objTmpl->addVar('translate_list_body', 'TAB_CONTEXT', $_keys);

					$_objTmpl->addVar('translate_list_body', 'STANDARD_LANG_CODING_LANG', $this->getText('#phptranslator_idioma#'));
					$_objTmpl->addVar('translate_list_body', 'STANDARD_LANG_CODING_MSG_CODE', $this->getText('#phptranslator_codigo da mensagem#'));
					$_objTmpl->addVar('translate_list_body', 'STANDARD_LANG_CODING_MSG_TYPE', $this->getText('#phptranslator_tipo da mensagem#'));
					$_objTmpl->addVar('translate_list_body', 'STANDARD_LANG_CODING_MSG_ABBR', $this->getText('#phptranslator_sigla para a mensagem#'));
					$_objTmpl->addVar('translate_list_body', 'STANDARD_LANG_CODING_MSG', $this->getText('#phptranslator_mensagem#'));

					$_objTmpl->addRows('translate_list_by_context', $this->arrLanguageList[$_keys]);
					$_objTmpl->displayParsedTemplate('translate_list_body');
					// limpa dados de template para acrecentar outros dados
					$_objTmpl->clearTemplate('translate_list_body');
					$_objTmpl->clearTemplate('translate_list_by_context');
				} // end foreach
			else
				$_objTmpl->addVar('translate_list_foot', 'LIST_MESSAGE', $this->getText('#phptranslator_text#') . " " . $this->getText('#phptranslator_not found#'));

			$_objTmpl->displayParsedTemplate('translate_list_foot');
		}

		if ($_translate_only or $_langAction == 'tradutor') {
			// inicio de Montagem pagina do tradutor
			$_objTmpl->readTemplatesFromInput('translate_tmpl.html');
			$_objTmpl->addVar('translate_lang_head', 'STANDARD_LANG_TRANSLATION', $this->getText('#phptranslator_traducao do idioma padrao#'));

			$_objTmpl->displayParsedTemplate('translate_lang_head');
			if (is_array($this->arrLanguageLang))
				foreach ($this->arrLanguageLang as $_keys => $_key) {
					$_objTmpl->addVar('translate_lang_body', 'STANDARD_TEXT', $this->getText('#phptranslator_texto padrao#'));
					$_objTmpl->addVar('translate_lang_body', 'TARGET_TEXT', $this->getText('#phptranslator_traduzir para#'));
					$_objTmpl->addVar('translate_lang_body', 'TRANSLATED_TEXT', $this->getText('#phptranslator_texto traduzido#'));
					$_objTmpl->addVar('translate_lang_body', 'ABBREVIATION', $this->getText('#phptranslator_sigla#') .
					"/" .
					$this->getText('#phptranslator_simbolo#'));
					$_objTmpl->addVar('translate_lang_body', 'BTN_SALVAR', $this->getText('#phptranslator_salvar#'));
					$_objTmpl->addVar('translate_lang_body', 'BTN_RESTAURAR', $this->getText('#phptranslator_restaurar#'));

					$_objTmpl->addVar('translate_lang_body', 'TAB_CONTEXT', $_keys);
					$_objTmpl->addVar('translate_lang_body', 'LANGUAGE_SOURCE', "(" . $this->languageStd . ")");
					$_objTmpl->addVar('translate_lang_body', 'LANGUAGE_TARGET', "(" . $this->_getLanguage() . ")");
					$_objTmpl->addRows('translate_lang_list_entry', $this->arrLanguageLang[$_keys]);
					$_objTmpl->displayParsedTemplate('translate_lang_body');
					// limpa dados de template para acrecentar outros dados
					$_objTmpl->clearTemplate('translate_lang_body');
					$_objTmpl->clearTemplate('translate_lang_list_entry');
				} // end foreach

			$_objTmpl->displayParsedTemplate('translate_lang_foot');
			// fim de Montagem pagina do tradutor
		}
		$_objTmpl->displayParsedTemplate('end_page_form');

	} //end func: Translate

	/**
	 * Busca o texto a ser traduzido
	 *
	 * Busca a traducao do texto - caso o texto traduzido nao exista retorna o texto padrao e caso este 
	 * tambem nao exista retorna o codigo de pesquisa
	 *
	 * @access public
	 * @see getText()
	 * @param string $_msg_code O codigo da mensagem a ser traduzida
	 * @param boolean $_sigla Se retorna a sigla em lugar da mensagem completa
	 * @param boolean $_text_case Se o texto retorna o texto como cadastrado, em maiusculas ou minusculas
	 *                                                1 - maiuscula
	 *                                                2 - minuscula
	 *                                          outro - como estiver cadastrado
	 * @param array $_args Sao os argumentos que serÃ£o inseridos na mensagem nas posiÃ§Ãµes onde houver %N
	 *                     (onde N Ã© a quantidade sequencial de parÃ¢metros)
	 *
	 * @return string  O texto traduzido, o texto padrao ou o codigo da mensagem
	 *
	 */
	function _($_msg_code, $_sigla = false, $_text_case = 0, $_args = array ()) {
		return $this->getText($_msg_code, $_sigla, $_text_case, $_args);
	}

	/**
	 * Busca o texto a ser traduzido
	 *
	 * Busca a traducao do texto - caso o texto traduzido nao exista retorna o texto padrao e caso este 
	 * tambem nao exista retorna o codigo de pesquisa
	 *
	 * @access public
	 * @param string $_msg_code O codigo da mensagem a ser traduzida
	 * @param boolean $_sigla Se retorna a sigla em lugar da mensagem completa
	 * @param boolean $_text_case Se o texto retorna o texto como cadastrado, em maiusculas ou minusculas
	 *                                                1 - maiuscula
	 *                                                2 - minuscula
	 *                                          outro - como estiver cadastrado
	 * @param array $_args Sao os argumentos que serÃ£o inseridos na mensagem nas posiÃ§Ãµes onde houver %N
	 *                     (onde N Ã© a quantidade sequencial de parÃ¢metros)
	 *
	 * @return string  O texto traduzido, o texto padrao ou o codigo da mensagem
	 *
	 */
	function getText($_msg_code, $_sigla = false, $_text_case = 0, $_args = array ()) {

		if (is_array($_sigla)) {
			$_args = $_sigla;
			$_sigla = false;
		}
		if (is_array($_text_case)) {
			$_args = $_text_case;
			$_text_case = 0;
		}
		if (!is_bool($_sigla))
			$_sigla = false;

		if (!empty ($_msg_code) && ($this->translateMessage)) {
			$_msg_text = $this->_getStdText(& $_msg_code, & $_sigla, & $_text_case, & $_args);
		} else {
			$_msg_text = $_msg_code;
		}

		return $_msg_text;

	} // end func: getText

	/**
	 * Metodo para retornar a mensagens de erros ocorridas
	 * 
	 * @access public
	 * @name   getMessage
	 */
	function getMessage() {
		return $this->mensagem;
	} // end func: getMessage

	/**
	 * Metodo para retornar se houve erro ou nao na classe
	 *
	 * @access public
	 * @return boolean  TRUE  - houve erro ao processar a classe
	 *                             FALSE - nao houve erro
	 */
	function isError() {
		return $this->error;
	} // end func: isError

	/**
	 * Atribui o caminho fisico para os arquivos de idiomas
	 * 
	 * devera conter as secoes dos arquivos de idiomas, conforme contextualizacao
	 * <code>
	 *  ...
	 *  $_lang_sections = array(
	 *                      'phpTranslator' => 'textos da classe de traducao',
	 *                      'admin'             => 'Textos da secao administrativa',
	 *                      'home'              => 'textos da secao principal',
	 *                      'and son on'      => 'e assim por diante...');
	 *  
	 *  $objTranslator->setLangFileSections($_lang_sections);
	 *  ...
	 * </code>
	 * @param string $_languageFilePath O caminho para os arquivos de idiomas
	 * @var array
	 * @since v 0.2.0-40
	 */
	function setLangFileSections($_languageSections = array ()) {
		if (empty ($_languageSections)) {
			$this->message = "Impossivel processar traducao por secoes, secoes nao informadas.";
			$this->error = true;
		} else {
			$this->languageSections = $_languageSections;
			$this->error = false;
		}
		return !$this->error;
	}

	/**
	 * Atribui o caminho fisico para os arquivos de idiomas
	 * 
	 * @param string $_languageFilePath O caminho para os arquivos de idiomas
	 */
	function setLangFilePath($_languageFilePath = "") {
		if (!empty ($_languageFilePath))
			$this->languageFilePath = $_languageFilePath;
	} // end func: setLangFilePath

	/**
	 * Mostra o formulario de selecao de contextos
	 * @access private
	 * @since v 0.2.0-40
	 */
	function showTagHeader(& $_objTmpl) {
		if ($this->languageFileInSections)
			if (!is_object($_objTmpl)) {
				$this->mensagem = $this->getText('#phptranslator_objTemplate#') . $this->getText('#phptranslator_not found#');
				if ($this->inDebugMode())
					echo "phpTranslator: " . $this->getMessage();
				$this->error = true;
			} else {
				if (is_array($this->languageSections) and !empty ($this->languageSections)) {
					$_list = array ();
					foreach ($this->languageSections as $_tag => $_tag_title) {
						$_tag_current = '';
						if ($_tag == $this->languageSectionActive)
							$_tag_current = 'TagCurrent';

						$_arrAux = array (
							array (
								'TAG_NAME' => $_tag,
								'TAG_HEADER_ACTIVE' => $_tag_current,
								'TAG_HEADER' => $this->getText($_tag),
								'TAG_HEADER_TITLE' => $this->getText($_tag_title)
							)
						);
						$_list = array_merge($_list, $_arrAux);
					}
					/*
					echo $this->languageSectionActive;
					if('tradutor' == $this->languageSectionActive)
					    $_tag_current = 'TagCurrent';
					        
					 $_list = array_merge($_list,
					                                             array( 
					                                               array( 
					                                                      'TAG_NAME' => 'tradutor',
					                                                      'TAG_HEADER_ACTIVE' => $_tag_current,
					                                                      'TAG_HEADER' => $this->getText('tradutor'),
					                                                      'TAG_HEADER_TITLE' => $this->getText('Classe Tradutora')
					                                                     )
					                                             )
					                 );
					 */
					$_objTmpl->addRows('tmplTagHeader_list', $_list);
					$_objTmpl->addVar('tmplTagHeader', 'translatorUrlPath', $this->translatorUrlPath);
					$_objTmpl->addVar('tmplTagHeader', 'TAG_ACTIVE', $_POST['tag_active']);
					$_objTmpl->displayParsedTemplate('tmplTagHeader');
				} else {
					$this->mensagem = $this->getText('#phptranslator_section array not defined#');
					if ($this->inDebugMode())
						echo "phpTranslator: " . $this->getMessage();
					$this->error = true;
				}
			}
	} // end func: showTagHeader

	/**
	 * Atribui o caminho (URL) para a classe
	 *
	 * @param string $_translatorUrlPath A URL para a classe
	 * @since v 0.2.0-40
	 */
	function setURLPath($_translatorUrlPath = "") {
		if (!empty ($_translatorUrlPath))
			$this->translatorUrlPath = $_translatorUrlPath;
	} // end func: setURLPath

	/**
	 * Atribui o sufixo para o arquivo de idiomas
	 *
	 * @param string $_file_sufix O sufixo a ser atribuido ao arquivo
	 */
	function setLangFileSufix($_file_sufix = "") {
		if (!empty ($_file_sufix))
			$this->languageFileSufix = $_file_sufix;
	} // end func: setLangFileSufix

	/**
	 * Atribui o prefixo para o arquivo de idiomas
	 *
	 * @param string $_file_prefix O prefixo a ser atribuido ao arquivo
	 */
	function setLangFilePrefix($_file_prefix = "") {
		if (!empty ($_file_prefix))
			$this->languageFilePrefix = $_file_prefix;
	} // end func: setLangFilePrefix

	/**
	 * Atribui os idiomas a serem usado na aplicacao
	 * 
	 * @access private
	 * @param string $_abbr_i18n    ISO do Idioma em questao
	 * @param string $_lang_choice Qual o padrao a ser lido e armazenado
	 *                                    standard - o padrao para a aplicacao 
	 *                                    target   - o idioma destino 
	 *                                    user     - o idioma para o usuario 
	 */
	function setLanguage($_abbr_i18n, $_lang_choice = 'standard') {
		switch ($_lang_choice) {
			case 'standard' :
				{
					$this->setLangSrc($_abbr_i18n);
					break;
				}
			case 'target' :
				{
					$this->setLangTgt($_abbr_i18n);
					break;
				}
			case 'user' :
				{
					$this->setLangUser($_abbr_i18n);
					break;
				}
		}
	} // end func: setLanguage

	/**
	 * Inicializa os arrays de idiomas especificos (padrao ou destino)
	 * @access public
	 */
	function initStdLanguages() {
		$this->buildLangArray();
		$this->buildLangArray('target');
	}

	/**
	 * Le os arquivos de idioma e os armazena em array especificos (padrao ou destino)
	 * @access private
	 * @param string $_lang_choice Qual o padrao a ser lido e armazenado<br>
	 *                                    . standard - o padrao para a aplicacao<br>
	 *                                    . target   - o idioma a ser mostrado para o usuario<br>
	 */
	function buildLangArray($_lang_choice = 'standard') {
		switch ($_lang_choice) {
			case 'standard' :
				{
					$this->translatedTextStd = $this->_getLangFile();
					$this->translated_text[$this->languageStd] = $this->translatedTextStd;
					break;
				}
			case 'target' :
				{
					$this->translatedTextTgt = $this->_getLangFile($this->languageUser);
					$this->translated_text[$this->languageUser] = $this->translatedTextTgt;
					break;
				}
		}
	} // end func: buildLangArray

	/**
	 * Constroi o array de traducao de idiomas (DE -> PARA)
	 *
	 * @access private
	 */
	function getLangArray4Translate() {

		$_language_src = $this->translatedTextStd;
		$_language_tgt = $this->translatedTextTgt;

		if ($_language_src) {
			//Idioma a traduzir
			// cria array para cada context
			foreach ($_language_src as $_keys => $_key) {
				if ($_key['type'] != 'notran')
					$_list[$this->getText($_key['context'])] = array ();
			} // end foreach

			foreach ($_language_src as $_keys => $_key) {
				if (!empty ($_language_tgt[$_keys]['text']) or !empty ($_language_tgt[$_keys]['abbr']))
					$_cor = "";
				else
					$_cor = T_BG_COLOR_TRADUZ;

				if ($_key['type'] != 'notran')
					$_arrAux = array (
						array (
							'cor' => $_cor,
							'lang_src_key' => $_keys,
							'lang_language' => $this->languageUser,
							'lang_context' => $_key['context'],
							'lang_type' => $_key['type'],
							'lang_src' => $_key['text'],
							'lang_tgt' => $_language_tgt[$_keys]['text'],
							'lang_tgt_acr' => $_language_tgt[$_keys]['abbr']
						)
					);

				//$_list = array_merge($_list, $_arrAux);
				$_list[$this->getText($_key['context'])] = array_merge($_list[$this->getText($_key['context'])], $_arrAux);
			} // end foreach
		}

		if (empty ($_list))
			$_list = false;

		return $_list;
	} // end Function getLangArray4Translate

	/**
	 * Constroi o array de listagem de idioma padrao
	 *
	 * @access private
	 */
	function getLangArray4List() {
		$_language_src = $this->translatedTextStd;
		//Idioma a traduzir
		if (!empty ($_language_src)) {
			// cria array para cada context
			foreach ($_language_src as $_keys => $_key) {
				if ($_key['type'] != 'notran')
					$_list[$this->getText($_key['context'])] = array ();
			} // end foreach

			foreach ($_language_src as $_keys => $_key) {
				if ($_key['type'] != 'notran') {
					$_arrAux = array (
						array (
							'list_lang' => $this->languageStd,
							'list_lang_code' => $_keys,
							'list_lang_context' => $_key['context'],
							'list_lang_type' => $_key['type'],
							'list_lang_message' => $_key['text'],
							'list_lang_abbr' => $_language_src[$_keys]['abbr']
						)
					);

					$_list[$this->getText($_key['context'])] = array_merge($_list[$this->getText($_key['context'])], $_arrAux);
				}
			} // end foreach
		}
		if (empty ($_list))
			$_list = false;

		return $_list;
	} // end Function getLangArray4List

	/**
	 *  Constroi os arrays de traducao e listagem de idioma padrao
	 *
	 * @access private
	 */
	function buildArrayVars() {
		$this->arrLanguageLang = $this->getLangArray4Translate();
		$this->arrLanguageList = $this->getLangArray4List();
		if (!empty ($this->arrLanguageLang))
			ksort($this->arrLanguageLang);
		if (!empty ($this->arrLanguageList))
			ksort($this->arrLanguageList);
	} // end Function buildArrayVars

	/**
	 * Constroi string de conteudo do arquivo a ser salvo
	 * 
	 * @access private
	 * @param array $_lang_tgt_text_file Idioma a ser salvo
	 */
	function buildStr4File($_lang_tgt_text_file) {
		$_new_target_file = "";
		if (is_array($_lang_tgt_text_file)) {
			foreach ($_lang_tgt_text_file as $_keys => $_values) {
				$_text_test = $this->stripContraBarra(trim($_values['text']) . trim($_values['abbr']));
				$_keyCode = $this->stripContraBarra($_values['code']);
				if (!$_keyCode)
					$_keyCode = $this->stripContraBarra($_keys);
				if (!empty ($_text_test)) {
					$_new_target_file .= $this->stripContraBarra($_values['lang']) .
					$this->_spaces($this->messageCountryLen - strlen($this->stripContraBarra($_values['lang'])));
					$_new_target_file .= $_keyCode .
					$this->_spaces($this->messageCodeLen - strlen($_keyCode));

					$_new_target_file .= $this->stripContraBarra($_values['context']) .
					$this->_spaces($this->messageContextLen - strlen($this->stripContraBarra($_values['context'])));
					$_new_target_file .= $this->stripContraBarra($_values['type']) .
					$this->_spaces($this->messageTypeLen - strlen($this->stripContraBarra($_values['type'])));
					$_new_target_file .= $this->stripContraBarra($_values['abbr']) .
					$this->_spaces($this->messageAbbrLen - strlen($this->stripContraBarra($_values['abbr'])));
					$_new_target_file .= $this->stripContraBarra($_values['text']);
					$_new_target_file .= T_CRLF;
				}
			}
		} else {
			$this->Error = true;
			$this->mensagem = $this->getText('#phptranslator_array parameter required#');
			if ($this->inDebugMode())
				echo "phpTranslator: " . $this->getMessage();
			$_new_target_file = false;
		}

		return $_new_target_file;
	} // end Function buildStr4File

	/**
	 * Salva o arquivo de idioma conforme definido nos padroes
	 * 
	 * @access private
	 * @param string $_content_to_file Texto/conteudo do arquivo a ser salvo
	 * @param string $_abbr_i18n Idioma do arquivo a ser salvo- se nao informado busca o idioma padrao
	 * @param boolean $_file_increment Se o conteudo do arquivo sera incrementado - padrao eh sobrescrever
	 * @return boolean  se o arquivo foi salvo ou nao
	 */
	function saveLangFile($_content_to_file, $_abbr_i18n = "", $_file_increment = false) {

		if (empty ($_abbr_i18n))
			$_abbr_i18n = $this->languageStd;

		$_file_name = $this->_makeFileName($_abbr_i18n);

		$this->error = true;
		$this->mensagem = "";

		if (is_writable($this->languageFilePath)) {
			if (is_writable($_file_name) or !file_exists($_file_name)) {
				if (($_file_increment) and file_exists($_file_name))
					$_resource = @ fopen($_file_name, 'w+');
				else
					$_resource = @ fopen($_file_name, 'w');

				if ($_resource) {
					fwrite($_resource, $_content_to_file);
					fclose($_resource);
				} else {
					$this->mensagem = $this->getText('#phptranslator_file#') . " ($_file_name) " . $this->getText('#phptranslator_without write permition#');
					if ($this->inDebugMode())
						echo "phpTranslator: " . $this->getMessage();
					$this->error = false;
				}
			} else {
				$this->mensagem = $this->getText('#phptranslator_file#') . " ($_file_name) " . $this->getText('#phptranslator_without write permition#');
				if ($this->inDebugMode())
					echo "phpTranslator: " . $this->getMessage();
				$this->error = false;
			}
		} else {
			$this->mensagem = $this->getText('#phptranslator_directory#') . " ($this->languageFilePath) " . $this->getText('#phptranslator_without write permition#');
			if ($this->inDebugMode())
				echo "phpTranslator: " . $this->getMessage();
			$this->error = false;
		}

		return $this->error;
	} // end func: SaveFile

	/*
	 *     Metodos PRIVATE
	 */

	/**
	 *  Atribui o idioma padrao para a aplicacao
	 *
	 * @access private
	 * @param string $_abbr_i18n  ISO do Idioma da aplicacao
	 */
	function setLangSrc($_abbr_i18n) {
		$this->languageStd = $_abbr_i18n;
	} // end func: setLangSrc

	/**
	 *  Atribui o idioma do usuario
	 *
	 * @access private
	 * @param string $_abbr_i18n  ISO do Idioma do usuario
	 */
	function setLangUser($_abbr_i18n) {
		$this->languageUser = $_abbr_i18n;
	} // end func: setLangUser

	/**
	 *  Atribui o idioma do destino na traducao
	 *
	 * @access private
	 * @param string $_abbr_i18n  ISO do Idioma target
	 */
	function setLangTgt($_abbr_i18n) {
		$this->setLangUser($_abbr_i18n);
	} // end func: setLangTgt

	/**
	 * Monta o nome do arquivo de idiomas
	 * @access private
	 *
	 * @param string $_abbr_i18n Codigo ISO do idioma a ser usado
	 *
	 * @return string O nome do arquivo (incluindo o path)
	 */
	function _makeFileName($_abbr_i18n) {
		if (empty ($_abbr_i18n))
			$_abbr_i18n = $this->languageUser;

		$_file_name = $this->languageFilePath;
		if ($this->languageFilesubdir)
			$_file_name .= $_abbr_i18n . "/";

		$_file_name .= $this->languageFilePrefix;
		if ($this->languageFileInSections)
			if (!empty ($this->languageSectionActive))
				$_file_name .= $this->languageSectionActive . ".";

		$_file_name .= $_abbr_i18n;
		$_file_name .= $this->languageFileSufix;
		$this->error = false;

		if (!is_file($_file_name)) {
			$this->mensagem = $this->getText('#phptranslator_file#') . " ($_file_name) " . $this->getText('#phptranslator_not found#');
			if ($this->inDebugMode())
				echo "phpTranslator: " . $this->getMessage();
			$this->error = true;
		}

		return $_file_name;

	} // end func: MakeFileName

	/**
	 * Monta o nome do arquivo de idiomas
	 * @access private
	 *
	 * @param string $_abbr_i18n Codigo ISO do idioma a ser usado
	 *
	 * @return string O nome do arquivo (incluindo o path)
	 */
	function _getExternalFile($_filename, $_abbr_i18n) {
		$_file_content = "";
		$_file_name = $this->languageFilePath;
		if ($this->languageFilesubdir)
			$_file_name .= $_abbr_i18n . DIRECTORY_SEPARATOR;
		$_file_name .= $_filename;

		$this->error = false;

		if (!is_file($_file_name) and !is_readable($_file_name)) {
			$this->mensagem = $this->getText('#phptranslator_file#') . " ($_file_name) " . $this->getText('#phptranslator_not found#');
			$_file_content = $this->mensagem;
			if ($this->inDebugMode())
				echo "phpTranslator: " . $this->getMessage();
			$this->error = true;
		} else {
			$_file_content = file_get_contents($_file_name);
		}

		return $_file_content;

	} // end func: getExternalFile

	/**
	 * Idioma a ser traduzido para a aplicacao. 
	 * Se o preferido pelo usuario ou o do Browser em uso ou o padrao da aplicacao
	 * @access private
	 */
	function _getLanguage() {

		// faz insercao dos textos padroes para a aplicacao (conforme o idioma
		$_language = "";
		if (!empty ($this->languageUser))
			$_language = $this->languageUser;
		elseif (!empty ($this->_browserLanguage)) $_language = $this->_browserLanguage;
		else
			$_language = $this->languageStd;

		return $_language;
	} // end func: _getLanguage

	/**
	 * Busca o texto a ser traduzido dentro do array de idiomas
	 *
	 * Busca a traducao do texto - caso o texto traduzido nao exista retorna o texto padrao e caso este 
	 * tambem nao exista retorna o codigo de pesquisa
	 *
	 * @access private
	 * @param string $_msg_code O codigo da mensagem a ser traduzida
	 * @param boolean $_sigla Se retorna a sigla em lugar da mensagem completa
	 * @param boolean $_text_case Se o texto retorna o texto como cadastrado, em maiusculas ou minusculas
	 *                                                1 - maiuscula
	 *                                                2 - minuscula
	 *                                          outro - como estiver cadastrado
     * @param array $_args Sao os argumentos que serÃ£o inseridos na mensagem nas posiÃ§Ãµes onde houver %N
     *                     (onde N Ã© a quantidade sequencial de parÃ¢metros)
	 *
	 * @return string  O texto traduzido, o texto padrao ou o codigo da mensagem
	 *
	 */
	function _getStdText($_msg_code = '', $_sigla = false, $_text_case = 0, $_args = array ()) {
		if (!empty ($_msg_code))
			/*
			 * - A chave de procura deve estar em minusculas bem como o conteudo do arquivo de idiomas
			 */
			$_key_lower_text = strtolower($_msg_code);

		$_lang_array_aux = $this->translated_text[$this->_getLanguage()];
		if (@ array_key_exists($_key_lower_text, $_lang_array_aux)) {
			if (@ array_key_exists('type', $_lang_array_aux[$_key_lower_text]) and ($_lang_array_aux[$_key_lower_text]['type'] == 'arquivo' or $_lang_array_aux[$_key_lower_text]['type'] == 'file')) {
				if (@ array_key_exists('text', $_lang_array_aux[$_key_lower_text])) {
					$_file_name = $_lang_array_aux[$_key_lower_text]['text'];
					$_msg_code_aux = $this->_getExternalFile($_file_name, $this->languageUser);
				}
			}
			elseif (@ array_key_exists('text', $_lang_array_aux[$_key_lower_text])) {
				$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['text'];
				if ($_sigla)
					if (@ array_key_exists('abbr', $_lang_array_aux[$_key_lower_text]))
						$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['abbr'];
			}
		} else {
			$_lang_array_aux = $this->translatedTextStd;
			if (@ array_key_exists($_key_lower_text, $_lang_array_aux)) {
				if (@ array_key_exists('type', $_lang_array_aux[$_key_lower_text]) and ($_lang_array_aux[$_key_lower_text]['type'] == 'arquivo' or $_lang_array_aux[$_key_lower_text]['type'] == 'file')) {
					if (@ array_key_exists('text', $_lang_array_aux[$_key_lower_text])) {
						$_file_name = $_lang_array_aux[$_key_lower_text]['text'];
						$_msg_code_aux = $this->_getExternalFile($_file_name, $this->languageStd);
					}
				}
				elseif (@ array_key_exists('text', $_lang_array_aux[$_key_lower_text])) {
					$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['text'];
					if ($_sigla)
						if (@ array_key_exists('abbr', $_lang_array_aux[$_key_lower_text]))
							$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['abbr'];
				}
			} else {
				$_lang_array_aux = $this->translatedTextSelf;
				if (@ array_key_exists($_key_lower_text, $_lang_array_aux)) {
					if (@ array_key_exists('text', $_lang_array_aux[$_key_lower_text])) {
						$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['text'];
						if ($_sigla)
							if (@ array_key_exists('abbr', $_lang_array_aux[$_key_lower_text]))
								$_msg_code_aux = $_lang_array_aux[$_key_lower_text]['abbr'];
					}
				}
			}
		}

		switch ($_text_case) {
			case 1 : // tudo em minusculas
				$_msg_code_aux = strtolower($_msg_code_aux);
				break;
			case 2 : // tudo em maiusculas
				$_msg_code_aux = strtoupper($_msg_code_aux);
				break;
		}

		if (empty ($_msg_code_aux))
			$_msg_code_aux = $_msg_code;

		$_argcPercentage = substr_count($_msg_code_aux, "%");
		if ($_argcPercentage > 0) { // tem % no texto
			// substitui os %n do texto pelos parametros do array de argumentos
			for ($i = 0; $i < $_argcPercentage; $i++) {
				$_argsValue = "%" . ($i +1);
				if (strpos($_msg_code_aux, $_argsValue) <> 0) {
					$_msg_code_aux = str_replace($_argsValue, $_args[$i], $_msg_code_aux);
				}
			}
		}

		return $_msg_code_aux;
	} // end func: _getStdText

	/**
	 * Monta uma string contendo espacos em branco
	 * @access private
	 * @param int $_num Numero de espacos que a string contera
	 * @return string Espacos em branco
	 */
	function _spaces($_num = 0) {
		if ($_num > 0)
			for ($_inc = 1; $_inc <= $_num; $_inc++) {
				$_spaces .= " ";
			}
		return $_spaces;
	} // end func: _spaces

	/**
	 * cria template para a propria classe usar
	 * @access private
	 * @return patTemplate
	 */
	function & _createTemplate() {
		global $option, $mosConfig_absolute_path;

		if (defined('_VALID_MOS')) {
			require_once ($mosConfig_absolute_path . '/includes/patTemplate/patTemplate.php');
			$tmpl = & patFactory :: createTemplate();
			$tmpl->setRoot(dirname(__FILE__) . '/templates');
		} else {
			require_once ($this->classSelfPath . '/classes/pat/patErrorManager.php');
			require_once ($this->classSelfPath . '/classes/pat/patTemplate.php');
			$tmpl = new patTemplate();
			$tmpl->setNamespace("mos");
		}
		$this->_objTmpl = $tmpl;
		return $tmpl;
	} // end func: _createTemplate

	/**
	 * Salvar dados apos alteracoes ou exclusoes de mensagens codificadas
	 * 
	 * @access private
	 */
	function _langSave($_lang_target, $_abbr_i18n_save) {
		$_text4file = $this->buildStr4File($_lang_target);
		if (!is_bool($_text4file)) {
			$_saved = $this->saveLangFile($_text4file, $_abbr_i18n_save);
			if (!$_saved)
				if ($this->inDebugMode())
					echo $this->getMessage();
		}
		elseif (!($_text4file)) if ($this->inDebugMode())
			echo $this->getMessage();
	} // end func: _langSave

	/**
	 * Excluir mensagem do idioma padrao
	 *
	 * @access private
	 * @param array $_lang_data_selected Dado do idioma padrao a ser excluido
	 * 
	 */
	function _langCodeDelete($_lang_data_selected) {
		foreach ($_lang_data_selected as $_keys => $_key) {
			unset ($this->translatedTextStd[$this->stripContraBarra($_keys)]);
		}
	} // end func: _langCodeDelete

	/**
	 * Busca o arquivo de idiomas
	 *
	 * @access private
	 * @param string $_abbr_i18n O codigo ISO do idioma a ser tratado
	 *
	 * @return array Contem os dados do idioma
	 */
	function _getLangFile($_abbr_i18n = "") {

		$this->error = false;

		if (empty ($_abbr_i18n))
			$_abbr_i18n = $this->languageStd;

		$_src_file_name = $this->_makeFileName($_abbr_i18n);

		if (!file_exists($_src_file_name)) {
			$this->mensagem = $this->getText('#phptranslator_language file#') . " ($_src_file_name) " .
			$this->getText('#phptranslator_not found#');
			if ($this->inDebugMode())
				echo "phpTranslator: " . $this->getMessage();
			$this->error = true;
			return false;
		}

		$_src_file = file($_src_file_name);

		// monta idioma padrao
		foreach ($_src_file as $_keys => $_values) {
			$_lang = trim(substr($_values, 0, $this->messageCountryLen));
			$_msg_code = strtolower(trim(substr($_values, $this->messageCountryLen, $this->messageCodeLen)));

			$_str_start_aux = $this->messageCountryLen + $this->messageCodeLen;
			$_msg_context = trim(substr($_values, $_str_start_aux, $this->messageContextLen));

			$_str_start_aux = $this->messageCountryLen + $this->messageCodeLen + $this->messageContextLen;
			$_msg_type = trim(substr($_values, $_str_start_aux, $this->messageTypeLen));

			$_str_start_aux = $this->messageCountryLen + $this->messageCodeLen + $this->messageContextLen + $this->messageTypeLen;
			$_msg_abbr = trim(substr($_values, $_str_start_aux, $this->messageAbbrLen));

			$_str_start_aux = $this->messageCountryLen + $this->messageCodeLen + $this->messageContextLen + $this->messageTypeLen + $this->messageAbbrLen;
			$_msg_text = trim(substr($_values, $_str_start_aux));

			$_language_data[$_lang][$_msg_code]['text'] = $_msg_text;
			$_language_data[$_lang][$_msg_code]['context'] = $_msg_context;
			$_language_data[$_lang][$_msg_code]['type'] = $_msg_type;
			$_language_data[$_lang][$_msg_code]['abbr'] = $_msg_abbr;
			$_language_data[$_lang][$_msg_code]['lang'] = $_abbr_i18n;
		}

		@ asort($_language_data[$_abbr_i18n]);

		return $_language_data[$_abbr_i18n];

	} // end func: _getLangFile

   /**
    * Insere novos idiomas a traduzir 
    * @access private
    * @param boolean $_length Tamnho a ser atribuido
    */ 
    function _createLanguageDef($_objTmpl) {
       $_objTmpl->readTemplatesFromInput( 'translate_createlang.html' );
       $_objTmpl->addVar( 'translate_langdef', 'BTN_SALVAR', 
                                          $this->getText('#phptranslator_salvar#') );
       $_objTmpl->addVar( 'translate_langdef', 'BTN_CANCELAR', 
                                          $this->getText('#phptranslator_cancelar#') );
       $_objTmpl->addVar( 'translate_langdef', 'BTN_RESTAURAR', 
                                          $this->getText('#phptranslator_restaurar#') );
                                          
       $_objTmpl->displayParsedTemplate('translate_langdef');
    } // end func: _insertLanguageDef

	/**
	 * Atribui o tamanho do campo de codigo (abbrI18N) do idioma
	 * @access public
	 * @param boolean $_length Tamnho a ser atribuido
	 */
	function setLangCountryLength($_length) {
		$this->messageCountryLen = $_length;
	} // end func: setLangCountryLength

	/**
	 * Atribui o tamanho do campo de codigo da messagem
	 * @access public
	 * @param boolean $_length Tamnho a ser atribuido
	 */
	function setLangMsgCodeLength($_length) {
		$this->messageCodeLen = $_length;
	} // end func: setLangMsgCodeLength

	/**
	 * Atribui o tamanho do campo de tipo da messagem
	 * @access public
	 * @param boolean $_length Tamnho a ser atribuido
	 */
	function setLangMsgTypeLength($_length) {
		$this->messageTypeLen = $_length;
	} // end func: setLangMsgTypeLength

	/**
	 * Atribui o tamanho do campo de contexto da messagem
	 * @access public
	 * @param boolean $_length Tamnho a ser atribuido
	 */
	function setLangMsgContextLength($_length) {
		$this->messageContextLen = $_length;
	} // end func: setLangMsgContextLength

	/**
	 * Atribui o tamanho do campo da abreviatura  da messagem
	 * @access public
	 * @param boolean $_length Tamnho a ser atribuido
	 */
	function setLangMsgAbbrLength($_length) {
		$this->messageAbbrLen = $_length;
	} // end func: setLangMsgAbbrLength

	/**
	 * Atribui que o caminho dos arquivos de idiomas ficarao em subdiretorios abbrI18N
	 * @access public
	 * @param boolean $_filesInSubDirs TRUE - em subdiretorio abbrI18N 
	 * 						 	     FALSE - sem subdiretorio abbrI18N (default)
	 */
	function setLangFilesInSubDirs($_filesInSubDirs = false) {
		$this->languageFilesubdir = $_filesInSubDirs;
	} // end func: setLangFilesInSubDir

	/**
	 * Atribui que os arquivos de idiomas serao seccionados conforme contextualizacao
	 * @access public
	 * @param boolean $_filesInSections TRUE - seccionados 
	 * 							      FALSE -  nao seccionados (default)
	 */
	function setLangFilesInSections($_filesInSections = false) {
		$this->languageFileInSections = $_filesInSections;
	} // end func: setLangFilesInSections

	/**
	 * Atribui a secao ativa a ser mostrada a TAG em "showTagHeader"
	 * @access public
	 * @param string $_activeSection O nome da seccao ativa (ex: admin ou setup)
	 * 								Obs: Deve ser o nome interno e nao a traducao
	 */
	function setActiveSection($_activeSection = "") {
		$this->languageSectionActive = $_activeSection;
	} // end func: setActiveSection

	/**
	 * Busca a secao ativa a ser mostrada a TAG em "showTagHeader"
	 * @access public
	 * @return string $_activeSection O nome da seccao ativa (ex: admin ou setup)
	 */
	function getActiveSection() {
		return $this->languageSectionActive;
	} // end func: getActiveSection

	/**
	 * Se necessario remove a contra barra do texto
	 * @access private
	 * @param string $_text O texto a ser tratado
	 */
	function stripContraBarra($_text) {
		if (get_magic_quotes_gpc()) {
			$_text = stripslashes($_text);
		}

		return $_text;
	} // end stripContraBarra

	/**
	 * Ativa o modo "debug" do phpTranslator passando a mostrar as mensagens internas
	 * @access public
	 */
	function debugOn() {
		$this->_debugTranslator = true;
	} // end debugOn

	/**
	 * Desativa o modo "debug" do phpTranslator deixando de mostrar as mensagens internas
	 * @access public
	 */
	function debugOff() {
		$this->_debugTranslator = false;
	} // end debugOff

	/**
	 * Desativa o modo "debug" do phpTranslator deixando de mostrar as mensagens internas
	 * @access public
	 * @return boolean - True = em modo "debug"; FALSE = nao esta em modo "debug"
	 */
	function inDebugMode() {
		return $this->_debugTranslator;
	} // end inDebugMode

    /**
     * Dump de variavies
	 * @access public
     */
	function varDump($arg) {
		echo "<pre>";
		var_dump($arg);
		echo "</pre>";
	}

	/*
	 * Retorna a extensão de um arquivo qualquer.
	 * 
     * @since v 0.2.1
	 * @access private
	 */
	function _findexts($filename) {
	  $filename = strtolower($filename) ;
	  $exts = split("[/\\.]", $filename) ;
	  $n = count($exts)-1;
	  $exts = $exts[$n];
	  return $exts;
	}

	/**
	 * Le o diretorio de idiomas em busca dos arquivos de configuracao das traducoes
	 * 
     * @since v 0.2.1
	 * @access public
	 * @return array Matriz de dados de cada idioma no qual a aplicação foi traduzida.
	 *
	 *       Formato da matriz:   
	 *          $language_set = array( 'pt_BR' => ('descr' => 'Português Brasileiro',
	 *                                         'charset' => 'iso-8859-1',
	 *                                         'directition => '0', // 0=direita, 1=esquerda
	 *                                         'versao' => '0.1',
	 *                                         'versao_cacic' => '2.4.0'
	 *                                        )
	 *                               )
	 */
	function getLanguagesSetup() {
	   $_dir = dir($this->languageFilePath);
	   $_lang = array();
	   $language_abbr = '';
	   $language_def = '';
	   $language_charset = '';
	   $language_direction = '';
	   $language_version = '';
	   $language_cacic_version = '';
	   while (false !== ($_valor = $_dir->read())) {
	     if(is_file($_dir->path.$_valor))
	       if($this->_findexts($_valor) === 'php' ) {
	         $_file_name = $_valor;
	         @require($_dir->path.$_file_name);
	         $_lang = array_merge($_lang, array($language_abbr => array('abbr' => $language_abbr,
	                                                                    'descr' => $language_def,
	                                                                    'charset' => $language_charset,
	                                                                    'direction' => $language_direction,
	                                                                    'version' => $language_version,
	                                                                    'version_cacic' => $language_cacic_version
	                                                              ) ) );
	       }
	   }   
	   $_dir->close();
	   return $_lang;
	} // end func: getLanguagesSetup

	/**
	 * Diretório dos idiomas no qual a aplicação foi traduzida
	 * 
     * @since v 0.2.1
	 * @access public
	 * @return string Diretorio dos idiomas no qual a aplicação foi traduzida.
	 * 
	 */
	function getLanguagePath() {
	   return $this->languageFilePath;
	}

	/*
	 *     Deprecated Methods 
	 */

	/*  END OF DEPRECATED METHODS */
} // end Class: Translator
?>
