<?php
/**
 * @version $Id: tipos_licenca_01.tmpl.php 2009-08-26 23:25 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para tipos de licenca
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<gerenciaLicencas:tmpl name="TiposLicenca">
	<link href="{CACIC_URL}/admin/gerencia_licencas/tipos_licenca_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/gerencia_licencas/tipos_licenca_01.js"></script>
</gerenciaLicencas:tmpl>

<gerenciaLicencas:tmpl name="TiposLicenca_form">
<div class="cabecalho">
<table>
  <tr> 
    <td class="cabecalho">
      <gerenciaLicencas:comment><img src="../../imgs/cacic_logo.png" /></gerenciaLicencas:comment>
      {TITULO}
    </td>
  </tr>
  <tr> 
    <td>
	   <fieldset class="corpo">
	     <legend>{DESCRICAO}</legend>
           <table cellspacing="01">
			<tr>
			 <td colspan="3">
			   <gerenciaLicencas:tmpl name="TiposLicenca_insert_edit" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="1">
			     <fieldset>
			      {TIPO_LICENCA_NAME_INPUT_LABEL}
			      <span class="necessario">*</span>
		  		  <input type="text" id="te_tipo_licenca" name="te_tipo_licenca" value="{TE_TIPO_LICENCA}" size="30" maxlength="30"
		  		         onFocus="setClass(this, 'inputFocus');" onBlur="if(validaForm('{MSG_VALIDACAO}')){setClass(this, 'input');}" />
		  		  <span class="erro" id="error_te_tipo_licenca"></span>

		  		  <input type="hidden" id="id_tipo_licenca" name="id_tipo_licenca" value="{TIPO_LICENCA_ID}" />
		  		  
		  		  <span class="botoes">
				      <span class='botoes{BTN_SALVAR_DENY}'>
				        <input class='botoes{BTN_SALVAR_DENY}' type='button' title="{BTN_SALVAR_TITLE}" name="{BTN_SALVAR}"
				               onClick="if(validaForm('{MSG_VALIDACAO}')){ setDocVar( 'btn_salvar', 1 ); sendForm(document.CacicCommon_form);}" value="{BTN_SALVAR}" {BTN_SALVAR_DENY} />
				      </span>
				      <span class='botoes{BTN_CANCELAR_DENY}'>
				        <input class='botoes{BTN_CANCELAR_DENY}' type='button' title="{BTN_CANCELAR_TITLE}" name="{BTN_CANCELAR}"
				               onClick="sendForm(document.CacicCommon_form);" value="{BTN_CANCELAR}" {BTN_CANCELAR_DENY} />
				      </span>
				      <input type='reset' title="{BTN_RESET_TITLE}" value="{BTN_RESET}" />
						<script type="text/javascript">
						  setFocus('te_tipo_licenca');
						</script>
				  </span>
		  		 </fieldset>
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="__default">
			       <div align="right">
				      <span class='botoes{BTN_INCLUIR_DENY}'>
				        <input class='botoes{BTN_INCLUIR_DENY}' type='button' title="{BTN_INCLUIR_TITLE}" name="{BTN_INCLUIR}"
				               onClick="setDocVar( 'btn_action_incluir', 1 ); sendForm(document.CacicCommon_form);" value="{BTN_INCLUIR}" {BTN_INCLUIR_DENY} />
				      </span>
				   </div>
		  		      <input type="hidden" id="btn_action_incluir" name="btn_action_incluir" />
				</gerenciaLicencas:sub>
			  </gerenciaLicencas:tmpl>
			 </td>
			</tr>
			<tr>
			 <th class="header" style='width: 3%;'>{TIPO_LICENCA_ID_TITLE}</th>
			 <th class="header">{TIPO_LICENCA_NAME_TITLE}</th>
             <gerenciaLicencas:tmpl name="TiposLicenca_actions_acl" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="__default">
			      <th class="header" style='width: 10%;'>{TIPO_LICENCA_ACTIONS_TITLE}</th>
				</gerenciaLicencas:sub>
			 </gerenciaLicencas:tmpl>
			</tr>
			<gerenciaLicencas:comment>
			  Condicional (via template) para não mostrar botoes de inclusão, edição ou exclusão
			</gerenciaLicencas:comment> 
            <gerenciaLicencas:tmpl name="TiposLicenca_list" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
					<td>
					  {TIPO_LICENCA_SEQ}
					</td>
					<td>
					  {TIPO_LICENCA_NAME}
					</td>
					</tr>
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="__default">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
					<td>
					  {TIPO_LICENCA_SEQ}
					</td>
					<td>
					  {TIPO_LICENCA_NAME}
					</td>
					<td>
					  <img style='width: 16px; height: 16px;' src="../../imgs/error.png" title="{TIPO_LICENCA_ACTIONS_DELETE_TITLE}" 
					       onClick="setDocVar( 'btn_action_excluir', {TIPO_LICENCA_ID} ); sendForm(document.CacicCommon_form);" />
					  <img style='width: 16px; height: 16px;' src="../../imgs/details.gif" title="{TIPO_LICENCA_ACTIONS_EDIT_TITLE}" 
					       onClick="setDocVar( 'btn_action_edit', {TIPO_LICENCA_ID} ); sendForm(document.CacicCommon_form);" />
					</td>
					</tr>
				</gerenciaLicencas:sub>
			 </gerenciaLicencas:tmpl>
		  	<tr>
		  		<td colspan="10" class="botoes">
		  		      <input type="hidden" id="btn_action_edit" name="btn_action_edit" />
		  		      <input type="hidden" id="btn_action_excluir" name="btn_action_excluir" />
		  		      <input type="hidden" id="btn_salvar" name="btn_salvar" value="" />
		  		</td>
		  	</tr>
		 </table>
	    </fieldset>
    </td>
  </tr>
</table>
</div>
</gerenciaLicencas:tmpl>
