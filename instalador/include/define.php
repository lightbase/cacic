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
 define( 'CACIC_SQLFILE_STDDATA', 'cacic_dados_basicos.sql');

/**
 * define o nome do arquivo SQL para inserir dados de DEMONSTRAÇÃO base do CACIC
 */
 define( 'CACIC_SQLFILE_DEMODATA', 'cacic_demonstracao.sql');

/**
 * define o prefixo do nome do arquivo SQL de atualização do banco de dados do CACIC
 */
 define( 'CACIC_SQLFILE_PREFIX', 'cacic_');

/**
 * define a versão do CACIC
 */
 define( 'CACIC_VERSION', '2.2.2-RC3');

/**
 * define as versões atualizaveis do CACIC
 */
// $cacic_updateFromVersion = array( 'JUN2005'=>'Junho de 2005' );

/**
 * define a versão do PHP para o CACIC
 */
 define( 'CACIC_PHPVERSION', '4.2.0');

/**
 * define a versão do MySQL para o CACIC 
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

/**
 * Atribui Idioma padrao
 */
 if(isset($cacicLang))
    define( 'CACIC_LANG', $cacicLang);
 else 
    define( 'CACIC_LANG', 'pt_br');

/**
 * Atribui CHARSET padrao
 */
 if(isset($cacicLangCS))
    define( 'CACIC_LANG_CHARSET', $cacicLangCS);
 else 
    define( 'CACIC_LANG_CHARSET', 'ISO-8859-1');

/* ******************************************************
 *  NAO ALTERAR NADA DAQUI PARA BAIXO
 * ******************************************************/
 
/*
 * define caminho do pacote de instalacao do CACIC
 */
 define( 'CACIC_INSTALL_PATH', CACIC_PATH.'/instalador');
 
/*
 * define caminho do arquivo de configurações para o CACIC
 */
 define( 'CACIC_CFGFILE_PATH', CACIC_PATH.'/include');
 
/*
 * Atribui URL CACIC 
 */
 $urlRequest = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
 $urlRequest = str_replace("instalador/", '', $urlRequest);
 if(isset($cacicURL))
    define( 'CACIC_URL', $cacicURL);
 else 
    define( 'CACIC_URL', "http://" . $_SERVER['SERVER_NAME'] . $urlRequest);

/*
 * Atribui URL de instalação do CACIC 
 */
 if(isset($cacicURL))
    define( 'CACIC_URL_INSTALL', $cacicURL.'instalador');
 else 
    define( 'CACIC_URL_INSTALL', CACIC_URL.'instalador');

/*
 * Obtem DIRECTORY_SEPARATOR
 */
 define( 'CACIC_DS', DIRECTORY_SEPARATOR);

/*
 * Atribui tema padrao para o instalador
 */
 if(isset($cacicTema))
    define( 'CACIC_THEME', $cacicTema);
 else 
    define( 'CACIC_THEME', 'default');

/*
 * take the PATH_SEPARATOR
 */
 define( 'CACIC_PS', PATH_SEPARATOR);

/*
 * atribui os caminhos de inclusão para a aplicação 
 */
 ini_set("include_path", ini_get("include_path").CACIC_PS.
                         CACIC_INSTALL_PATH."/bibliotecas/"
         );

/*
 * define o header para o conjunto de caracteres padrão
 */
 header('Content-Type: text/html; charset='.CACIC_LANG_CHARSET);
?>
