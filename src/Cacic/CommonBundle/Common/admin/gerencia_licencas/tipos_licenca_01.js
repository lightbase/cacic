/**
 * @version $Id: tipos_licenca_01.js,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Funcoes javascript para Tipos de Licença
 */

function validaTipoLicenca(_msg) {
	resp = true;
	if((document.CacicCommon_form.te_tipo_licenca.value=="") || (document.CacicCommon_form.te_tipo_licenca.value.substring(0,1)==" ")){
		setClass(document.CacicCommon_form.te_tipo_licenca, 'inputError');
		document.getElementById('error_te_tipo_licenca').innerHTML=_msg;
		resp = false;
	}
	else {
	    document.getElementById('error_te_tipo_licenca').innerHTML="";
	}
	return resp;
}

function validaForm(_msg) {
	resp = true;
	resp = validaTipoLicenca(_msg);
	return resp;
}
