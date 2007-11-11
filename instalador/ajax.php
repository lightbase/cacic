<?php
/**
 * @version $Id: index.php 2007-02-08 22:20 harpiain $
 * @package Cacic-Installer
 * @subpackage Instalador
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC-Install is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// direct access is denied
//defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

session_start();
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso negado!');
else { // Inserir regras para outras verificações (ex: permissões do usuário)!
}

if( ! defined( 'CACIC' ) )
    define( 'CACIC', 1 );
    
// define Path para o CACIC
$cacic_path = str_replace("instalador", '', dirname(__FILE__));
define('CACIC_PATH', $cacic_path);

/*
 * atribuições para o ambiente
 */
if( ! @include("include/define.php") )
{
   die("Install mal definido (Install miss-defined)!");
}

/*
 * classe para instanciar a instalação
 */
if( ! @include_once("classes/install.ajax.php") )
{
   die("Install mal construí­do (Install miss-built)!");
}

InstallAjax::processAjax();

?>
