/**
 * @version $Id: software_estacao_01.js 2009-10-12 14:07 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Funcoes javascript para Controle de Software por Estação
 */

// valor de retorno global
var g_resp = true;

function validaPatrimonio(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nr_patrimonio.value=="") || (document.CacicCommon_form.nr_patrimonio.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nr_patrimonio, 'inputError');
        document.getElementById('error_nr_patrimonio').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nr_patrimonio').innerHTML="";
    }
    return resp;
}

function validaAquisicao(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nr_processo.value=="") || (document.CacicCommon_form.nr_processo.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nr_processo, 'inputError');
        document.getElementById('error_nr_processo').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nr_processo').innerHTML="";
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

function validaComputador(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nm_computador.value=="") || (document.CacicCommon_form.nm_computador.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nm_computador, 'inputError');
        document.getElementById('error_nm_computador').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nm_computador').innerHTML="";
    }
    return resp;
}

function validaDataAutorizacao(_msg) {
    var resp = true;
    if((document.CacicCommon_form.dt_autorizacao.value=="")){
        setClass(document.CacicCommon_form.dt_autorizacao, 'inputError');
        document.getElementById('error_dt_autorizacao').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_dt_autorizacao').innerHTML="";
    }
    return resp;
}

function validaDataExpiracao(_msg) {
    var resp = true;
    if((document.CacicCommon_form.dt_expiracao_instalacao.value=="")){
        setClass(document.CacicCommon_form.dt_expiracao_instalacao, 'inputError');
        document.getElementById('error_dt_expiracao_instalacao').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_dt_expiracao_instalacao').innerHTML="";
    }
    return resp;
}

function validaForm(_msg) {
    g_resp = true;
	validaPatrimonio(_msg);
    validaSoftware(_msg);
	validaAquisicao(_msg);
	validaComputador(_msg);
	validaDataAutorizacao(_msg);
	
	return g_resp;
}
