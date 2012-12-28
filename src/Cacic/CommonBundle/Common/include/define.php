<?php
/**
* @version $Id: index.php 2007-02-08 22:20 harpiain $
* @package Cacic-Install
* @subpackage Instalador
* @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
* @license GNU/GPL, see LICENSE.php
* CACIC-Install is free software and parts of it may contain or be derived from the
* GNU General Public License or other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

/**
 * atribui register_globals no servidor para o CACIC
 */
 if(isset($cacicDebug) and $cacicDebug)
    error_reporting(E_ALL);
 else
    error_reporting(E_ERROR);

/**
 * define o nome do agente principal do CACIC
 */
 define( 'CACIC_MAIN_PROGRAM_NAME', 'CACIC260');

/**
 * define chave para agentes CACIC
 */
 define( 'CACIC_KEY', 'CacicBrasil');
 
/**
 * define IV para agentes CACIC
 */
 define( 'CACIC_IV', 'abcdefghijklmnop');
 
/**
 * define o nome do arquivo SQL para criar DB do CACIC
 */
 define( 'CACIC_SQLFILE_CREATEDB', 'cacic_create_tables.sql');

/**
 * define o nome do arquivo SQL para inserir dados BASE do CACIC
 */
 define( 'CACIC_SQLFILE_STDDATA', 'cacic_dados_basicos-LANGUAGE.sql');

/**
 * define o nome do arquivo SQL para inserir dados de DEMONSTRA��O base do CACIC
 */
 define( 'CACIC_SQLFILE_DEMODATA', 'cacic_demonstracao.sql');

/**
 * define o prefixo do nome do arquivo SQL de atualiza��o do banco de dados do CACIC
 */
 define( 'CACIC_SQLFILE_PREFIX', 'cacic_');

/**
 * define a vers�o do CACIC
 */
 define( 'CACIC_VERSION', '2.6.0-Beta-2 (<b><i>Bocaina</i></b>)');

/**
 * define as vers�es atualizaveis do CACIC
 * Tamb�m � usado para formar o nome do arquivo SQL (ex: cacic_jun2005.sql, cacic_fev2006.sql)
 * que dever� existir na pasta "sql" do instalador 
 * Sintaxe: array( 'JUN2005'=>'Junho de 2005', 'FEV2006'=>'Fevereiro de 2006' )
 */
 $cacic_updateFromVersion = array( 'JUN2005'=>'junho de 2005',
                                   'FEV2006'=>'fevereiro de 2006',
                                   'v2.2.2'=>'Vers�o 2.2.2',
                                   'v2.4.0-rc1'=>'Vers�o v2.4.0-RC1',
                                   'v2.4.0-rc2'=>'Vers�o v2.4.0-RC2',
				   				   'v2.6.0-Alpha-1'=>'Vers�o v2.6.0-Alpha-1',
                                   'v2.6.0-Beta-1'=>'Vers�o v.2.6.0-Beta-1',
                                   'v2.6.0-Beta-2'=>'Vers�o v.2.6.0-Beta-2'								   
                                 );

/**
 * define a vers�o do PHP para o CACIC
 */
 define( 'CACIC_PHPVERSION', '5.1.0');

/**
 * define a vers�o do MySQL para o CACIC 
 * (mudar para for de array -> $cacic_db('db_type','db_min_version') )
 */
 define( 'CACIC_DBVERSION', '4.1.0');

/**
 * define a Register_Globals para executar PHP para o CACIC
 */
 if(isset($cacic_register_globals))
 	define( 'CACIC_PHPRG', $cacic_register_globals);
 else
 	define( 'CACIC_PHPRG', 'on');

/**
 * define a Short_open_tag para executar PHP para o CACIC
 */
 if(isset($cacic_short_open_tag))
 	define( 'CACIC_PHPSOT', $cacic_short_open_tag);
 else
 	define( 'CACIC_PHPSOT', 'on');

/**
 * define a register_long_arrays para executar PHP para o CACIC
 */
 if(isset($cacic_register_long_arrays))
 	define( 'CACIC_PHPRLA', $cacic_register_long_arrays);
 else
 	define( 'CACIC_PHPRLA', 'on');

/**
 * define a memoria para executar PHP para o CACIC
 */
 define( 'CACIC_PHPMEM', '32M');

/* ******************************************************
 *  NAO ALTERAR NADA DAQUI PARA BAIXO
 * ******************************************************/

/*
 * Obtem PATH_SEPARATOR
 */
 define( 'CACIC_PS', PATH_SEPARATOR);

/*
 * Obtem DIRECTORY_SEPARATOR
 */
 define( 'CACIC_DS', DIRECTORY_SEPARATOR);
 
/**
 * Atribui Idioma padrao
 */
/*
 * CACIC application language
 */
 if(isset($cacic_language) and !empty($cacic_language))
    define( 'CACIC_LANGUAGE', $cacic_language );
 else
    define( 'CACIC_LANGUAGE', 'pt_BR');

/*
 * CACIC application standard language
 * (Language to be used if the above one fail)
 */
 define( 'CACIC_LANGUAGE_STANDARD', 'pt_BR' );

/*
 * CACIC application standard language path
 */
 define( 'CACIC_LANGUAGE_PATH', CACIC_DS.'language'.CACIC_DS );

/**
 * Atribui CHARSET padrao
 */
 if(isset($cacicLangCS))
    define( 'CACIC_LANG_CHARSET', $cacicLangCS);
 else
    define( 'CACIC_LANG_CHARSET', 'ISO-8859-1');

/*
 * path for CACIC
 */
 define('CACIC_PATH', $path_aplicacao );

/*
 * path para componentes de instala��o, coleta de dados de patrim�nio e cliente de Suporte Remoto do CACIC
 */
 $path_relativo_repositorio_instalacao = 'repositorio/install';
 define('CACIC_PATH_RELATIVO_REPOSITORIO_INSTALACAO', $path_relativo_repositorio_instalacao);

/*
 * Atribui URL CACIC
 */
 $urlRequest = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
 $urlRequest = str_replace("instalador/", '', $urlRequest);
 if(isset($url_aplicacao))
    define( 'CACIC_URL', $url_aplicacao);
 else
    define( 'CACIC_URL', "http://" . $_SERVER['SERVER_NAME'] . $urlRequest);

/*
 * define caminho do pacote de instalacao do CACIC
 */
 define( 'CACIC_INSTALL_PATH', CACIC_PATH.CACIC_DS.'instalador');

/*
 * define caminho do arquivo de configura��es para o CACIC
 */
 define( 'CACIC_CFGFILE_PATH', CACIC_PATH.CACIC_DS.'include');
 
/*
 * Atribui URL de instala��o do CACIC 
 */
 if(isset($cacicURL))
    define( 'CACIC_URL_INSTALL', $cacicURL.'/instalador');
 else 
    define( 'CACIC_URL_INSTALL', CACIC_URL.'/instalador');

/*
 * PATH for phpTranslator class
 */
 define('TRANSLATOR_PATH', CACIC_PATH.CACIC_DS."bibliotecas".CACIC_DS."phpTranslator".CACIC_DS);
/*
 * URL for phpTranslator class
 */
 define('TRANSLATOR_PATH_URL', CACIC_URL."/bibliotecas/phpTranslator/");

/*
 * Atribui tema padrao para o instalador
 */
 if(isset($cacicTema))
    define( 'CACIC_THEME', $cacicTema);
 else 
    define( 'CACIC_THEME', 'default');

/*
 * atribui os caminhos de inclus�o para a aplica��o 
 */
 ini_set("include_path", ini_get("include_path").CACIC_PS.CACIC_PATH.
                         CACIC_PS.CACIC_PATH.CACIC_DS."bibliotecas".CACIC_DS
         );

/*
 * define o header para o conjunto de caracteres padr�o
 */
 header('Content-Type: text/html; charset='.CACIC_LANG_CHARSET);
?>
