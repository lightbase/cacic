<?php
/**
 * @version $Id: softwares_classificar.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage SoftwaresClassificar
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

session_start();
$time_start = microtime(true);
/*
 * verifica se houve login e também regras para outras verificações (ex: permissões do usuário)!
 */
if(!isset($_SESSION['id_usuario'])) 
  die('Acesso restrito (Restricted access)!');

// Ativa modo de depuração (mostra todas as mensagens na página)
// $cacicDebug = true;

include_once('../../include/library.php');
AntiSpy('1,2,3'); // Permitido somente a estes cs_nivel_administracao...

require_once('softwares_classificar.class.php');

$oCacicAdminRede = new Softwares_Classificar();
$oCacicAdminRede->setup();
$oCacicAdminRede->run();

/*
 * Contabiliza tempo de processamento da página
 */
$time_end = microtime(true);
$time_proc = ($time_end-$time_start);
echo '<!-- in '.($time_proc)."ms -->";

?>