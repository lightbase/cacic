/**
 * @version $Id: aquisicoes_itens_01.js,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Funcoes javascript para Itens Adquiridos
 */

// valor de retorno global
var g_resp = true;

function validaTipoLicenca(_msg) {
    var resp = true;
    if((document.CacicCommon_form.id_tipo_licenca.value=="") || (document.CacicCommon_form.id_tipo_licenca.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.id_tipo_licenca, 'inputError');
        document.getElementById('error_id_tipo_licenca').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_id_tipo_licenca').innerHTML="";
    }
    return resp;
}

function validaAquisicao(_msg) {
    var resp = true;
    if((document.CacicCommon_form.id_aquisicao.value=="") || (document.CacicCommon_form.id_aquisicao.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.id_aquisicao, 'inputError');
        document.getElementById('error_id_aquisicao').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_id_aquisicao').innerHTML="";
    }
    return resp;
}

function validaSoftware(_msg) {
    var resp = true;
    if((document.CacicCommon_form.id_software.value=="") || (document.CacicCommon_form.id_software.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.id_software, 'inputError');
        document.getElementById('error_id_software').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_id_software').innerHTML="";
    }
    return resp;
}

function validaQtdeLicenca(_msg) {
    var resp = true;
    if((document.CacicCommon_form.qtde_licenca.value=="") || (document.CacicCommon_form.qtde_licenca.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.qtde_licenca, 'inputError');
        document.getElementById('error_qtde_licenca').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else if(!isInt(document.CacicCommon_form.qtde_licenca.value)) {
        setClass(document.CacicCommon_form.qtde_licenca, 'inputError');
        document.getElementById('error_qtde_licenca').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_qtde_licenca').innerHTML="";
    }
    return resp;
}

function validaDataVencimento(_msg) {
    var resp = true;
    if((document.CacicCommon_form.data_vencimento.value=="")){
        setClass(document.CacicCommon_form.data_vencimento, 'inputError');
        document.getElementById('error_data_vencimento').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_data_vencimento').innerHTML="";
    }
    return resp;
}

function validaForm(_msg) {
    g_resp = true;
	validaTipoLicenca(_msg);
	validaAquisicao(_msg);
	validaSoftware(_msg);
	validaQtdeLicenca(_msg);
	
	return g_resp;
}
