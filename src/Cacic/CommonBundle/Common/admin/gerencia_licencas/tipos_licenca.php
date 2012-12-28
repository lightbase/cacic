<?php
/**
 * @version $Id: tipos_licenca.php 2009-08-26 23:26 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Tipos de licenca
 */

session_start();
$time_start = microtime(true);
/*
 * verifica se houve login e tamb�m regras para outras verifica��es (ex: permiss�es do usu�rio)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');

// Ativa modo de depura��o (mostra todas as mensagens na p�gina)
// $cacicDebug = true;

include_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...

require_once('tipos_licenca.class.php');

$oCacic = new Tipos_Licenca();
$oCacic->run();

/*
 * Contabiliza tempo de processamento da p�gina
 */
$time_end = microtime(true);
$time_proc = ($time_end-$time_start);
echo '<!-- in '.($time_proc)."ms -->";

?>
