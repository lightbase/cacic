/**
 * @version $Id: cacic_common_01.js 2009-10-11 11:33 harpiain $
 * @package CACIC-Common
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Funcoes javascript para Cacic
 */

// valor de retorno global
var g_resp = true;

function isInt(x) {
   var y=parseInt(x);
   if (isNaN(y)) return false;
   return x==y && x.toString()==y.toString();
} 
