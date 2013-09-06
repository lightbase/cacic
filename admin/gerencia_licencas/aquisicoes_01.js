/**
 * @version $Id: aquisicoes_01.js 2009-10-11 11:29 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Funcoes javascript para Controle de Aquisicoes
 */

// valor de retorno global
var g_resp = true;

function validaNomeProprietario(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nm_proprietario.value=="") || (document.CacicCommon_form.nm_proprietario.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nm_proprietario, 'inputError');
        document.getElementById('error_nm_proprietario').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nm_proprietario').innerHTML="";
        g_resp = true;
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
        g_resp = true;
    }
    return resp;
}

function validaNomeEmpresa(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nm_empresa.value=="") || (document.CacicCommon_form.nm_empresa.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nm_empresa, 'inputError');
        document.getElementById('error_nm_empresa').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nm_empresa').innerHTML="";
        g_resp = true;
    }
    return resp;
}

function validaNotaFiscal(_msg) {
    var resp = true;
    if((document.CacicCommon_form.nr_notafiscal.value=="") || (document.CacicCommon_form.nr_notafiscal.value.substring(0,1)==" ")){
        setClass(document.CacicCommon_form.nr_notafiscal, 'inputError');
        document.getElementById('error_nr_notafiscal').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_nr_notafiscal').innerHTML="";
        g_resp = true;
    }
    return resp;
}

function validaDataAquisicao(_msg) {
    var resp = true;
    if((document.CacicCommon_form.data_aquisicao.value=="")){
        setClass(document.CacicCommon_form.data_aquisicao, 'inputError');
        document.getElementById('error_data_aquisicao').innerHTML=_msg;
        resp = false;
        g_resp = false;
    }
    else {
        document.getElementById('error_data_aquisicao').innerHTML="";
        g_resp = true;
    }
    return resp;
}

function validaForm(_msg) {
	validaNomeProprietario(_msg);
	validaAquisicao(_msg);
	validaNomeEmpresa(_msg);
	validaNotaFiscal(_msg);
	validaDataAquisicao(_msg);
	
	return g_resp;
}
