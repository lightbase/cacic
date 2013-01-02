<?php
/**
 * @version $Id: aquisicoes_itens_01.tmpl.php 2009-08-31 20:21 harpiain $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para Itens Adquiridos
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<gerenciaLicencas:tmpl name="AquisicoesItens">
	<link href="{CACIC_URL}/admin/gerencia_licencas/aquisicoes_itens_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/gerencia_licencas/aquisicoes_itens_01.js"></script>
	
    <script src="{CACIC_URL}/include/sniffer.js" type="text/javascript" language="javascript"></script>
    <script src="{CACIC_URL}/include/dyncalendar.js" type="text/javascript" language="javascript"></script>
    <link href="{CACIC_URL}/include/dyncalendar.css" media="screen" rel="stylesheet">
</gerenciaLicencas:tmpl>

<gerenciaLicencas:tmpl name="AquisicoesItens_form">
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
	     <legend>{DESCRICAO_TITLE}</legend>
           <table cellspacing="01">
			<tr>
			 <td colspan="10">
			   <gerenciaLicencas:tmpl name="AquisicoesItens_insert_edit" type="condition" conditionvar="acl_permission">
			   
			    <gerenciaLicencas:sub condition="disabled">
		  		  <gerenciaLicencas:comment><!-->
		  		  ** Caso o usuario nao tem permissao (ACL) não mostra formulário ou botoes de inserção
		  		  <--></gerenciaLicencas:comment>
				  </gerenciaLicencas:sub>
				
		  		<gerenciaLicencas:comment><!-->
		  		** Formulário para inserção de dados
		  		<--></gerenciaLicencas:comment>
			    <gerenciaLicencas:sub condition="1">
			     <fieldset>
			      <table>
		  		  <tr>
		  		   <td width="30%">{AQUISICAO_NAME_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <select id="id_aquisicao" name="id_aquisicao" value="{AQUISICAO_ID}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaAquisicao('{MSG_VALIDACAO_AQUISICAO}')){setClass(this, 'input');}" >
		  		            
		  		      <option value="">{SELECT_OPTION}</option>
		  		      
			         <gerenciaLicencas:tmpl name="Aquisicao_insert_edit_list">
			          <option value="{AQUISICAO_ID}" {AQUISICAO_SELECTED}>{AQUISICAO_PROC}</option>
 			         </gerenciaLicencas:tmpl>
 			         
		  		    </select>
		  		    
		  		    <span class="erro" id="error_id_aquisicao"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{SOFTWARE_NAME_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <select id="id_software" name="id_software" value="{SOFTWARE_ID}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaSoftware('{MSG_VALIDACAO_SOFTWARE}')){setClass(this, 'input');}" >
		  		            
		  		      <option value="">{SELECT_OPTION}</option>
		  		      
			         <gerenciaLicencas:tmpl name="Software_insert_edit_list">
			          <option value="{SOFTWARE_ID}" {SOFTWARE_SELECTED}>{SOFTWARE_NAME}</option>
 			         </gerenciaLicencas:tmpl>
 			         
		  		    </select>
		  		    
		  		    <span class="erro" id="error_id_software"></span>
		  		   </td>
		  		  </tr>
		  		  
			      <tr>
			       <td>{TIPO_LICENCA_NAME_INPUT_LABEL}<span class="necessario">*</span></td>
			       <td>
		  		    <select id="id_tipo_licenca" name="id_tipo_licenca" value="{TE_TIPO_LICENCA}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaTipoLicenca('{MSG_VALIDACAO_LIC_TYPE}')){setClass(this, 'input');}" >
		  		            
		  		      <option value="">{SELECT_OPTION}</option>
		  		      
			         <gerenciaLicencas:tmpl name="TipoLicenca_insert_edit_list">
			          <option value="{TIPO_LICENCA_ID}" {TIPO_LICENCA_SELECTED}>{TIPO_LICENCA_NAME}</option>
 			         </gerenciaLicencas:tmpl>
 			         
		  		    </select>
		  		    
		  		    <span class="erro" id="error_id_tipo_licenca"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{QTDE_LICENCA_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="qtde_licenca" name="qtde_licenca" value="{QTDE_LICENCA}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaQtdeLicenca('{MSG_VALIDACAO_QTDE}')){setClass(this, 'input');}" />
		  		            
		  		    <span class="erro" id="error_qtde_licenca"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{DATA_VENCIMENTO_INPUT_LABEL}
		  		   <td>
		  		    <input type="text" id="data_vencimento" name="data_vencimento" value="{DATA_VENCIMENTO}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaDataVencimento('{MSG_VALIDACAO_DATA}')){setClass(this, 'input');}else{return false;}" />
		  		     <script type="text/javascript" language="JavaScript">
						<!--
							function dataVencimentoCallback(date, month, year)	{
								if (String(month).length == 1) {
									month = '0' + month;
								}
								if (String(date).length == 1) {
									date = '0' + date;
								}
								document.CacicCommon_form.data_vencimento.value = date + '/' + month + '/' + year;
							}
						  	data_vencimento = new dynCalendar('data_vencimento', 'dataVencimentoCallback');
						-->
					</script>
					{DATA_VENCIMENTO_FORMATO}
		  		            
		  		    <span class="erro" id="error_data_vencimento"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td style="vertical-align: top;">{OBSERVACAO_INPUT_LABEL}<td>
		  		    <textarea id="observacao" name="observacao" rows="3" cols="40"
		  		              onFocus="setClass(this, 'inputFocus');" 
		  		              onBlur="setClass(this, 'input');" />{OBSERVACAO}</textarea>
		  		            
		  		    <span class="erro" id="error_observacao"></span>
		  		   </td>
		  		  </tr>
		  		  </table>
		  		  
		  		  <gerenciaLicencas:comment><!-->
		  		  ** Botoes para salvar/cancelar dados inseridos no formulario
		  		  <--></gerenciaLicencas:comment>
		  		  <span class="botoes">
				      <span class='botoes{BTN_SALVAR_DENY}'>
				        <input class='botoes{BTN_SALVAR_DENY}' type='button' title="{BTN_SALVAR_TITLE}" name="{BTN_SALVAR}"
				               onClick="if(validaForm('{MSG_VALIDACAO}')){ setDocVar( 'btn_salvar', 1 ); sendForm(document.CacicCommon_form);}"
				               value="{BTN_SALVAR}" {BTN_SALVAR_DENY} />
				      </span>
				      <span class='botoes{BTN_CANCELAR_DENY}'>
				        <input class='botoes{BTN_CANCELAR_DENY}' type='button' title="{BTN_CANCELAR_TITLE}" name="{BTN_CANCELAR}"
				               onClick="sendForm(document.CacicCommon_form);" value="{BTN_CANCELAR}" {BTN_CANCELAR_DENY} />
				      </span>
				      <input type='reset' title="{BTN_RESET_TITLE}" value="{BTN_RESET}" />
						<script type="text/javascript">
						  //setFocus('qtde_licenca');
						</script>
				  </span>
				  
		  		 </fieldset>
		  		 
				</gerenciaLicencas:sub>
				
		  		<gerenciaLicencas:comment><!-->
		  		** Botao para entrar em modo de inserção de dados no formulario
		  		<--></gerenciaLicencas:comment>
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
			
		  	<gerenciaLicencas:comment><!-->
		  	** Formulario para mostrar dados cadastrados no banco
		  	<--></gerenciaLicencas:comment>
			<tr>
			 <th class="header" style='width: 3%;'>{AQUISICAO_ITEM_ID_TITLE}</th>
			 <th class="header">{AQUISICAO_ITEM_NAME_TITLE}</th>
			 <th class="header">{SOFTWARE_NAME_TITLE}</th>
			 <th class="header">{TIPO_LICENCA_TITLE}</th>
			 <th class="header">{QUANTIDADE_LICENCA_TITLE}</th>
			 <th class="header">{VENCIMENTO_LICENCA_TITLE}</th>
             <gerenciaLicencas:tmpl name="AquisicoesItens_actions_acl" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="__default">
			      <th class="header" style='width: 10%;'>{ACTIONS_TITLE}</th>
				</gerenciaLicencas:sub>
			 </gerenciaLicencas:tmpl>
			</tr>
			
			<gerenciaLicencas:comment><!-->
			  Condicional (ACL), via template, para não mostrar botoes de inclusão, edição ou exclusão
			<--></gerenciaLicencas:comment> 
            <gerenciaLicencas:tmpl name="AquisicoesItens_list" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
					<td>
					  {SEQUENCIAL}
					</td>
					<td>
					  {AQUISICAO_PROC}
					</td>
					<td>
					  {SOFTWARE}
					</td>
					<td>
					  {TIPO_LICENCA}
					</td>
					<td align="right">
					  {QUANTIDADE_LICENCA}
					</td>
					<td align="center">
					  {VENCIMENTO_LICENCA}
					</td>
					</tr>
				</gerenciaLicencas:sub>
			    <gerenciaLicencas:sub condition="__default">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';"
					    title="{OBSERVACAO}">
					<td>
					  {SEQUENCIAL}
					</td>
					<td>
					  {AQUISICAO_PROC}
					</td>
					<td>
					  {SOFTWARE}
					</td>
					<td>
					  {TIPO_LICENCA}
					</td>
					<td align="right">
					  {QUANTIDADE_LICENCA}
					</td>
					<td align="center">
					  {VENCIMENTO_LICENCA}
					</td>
					<td>
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/error.png" title="{TIPO_LICENCA_ACTIONS_DELETE_TITLE}" 
					       onClick="setDocVar( 'btn_action_excluir', '{AQUISICAO_ID}_{SOFTWARE_ID}_{TIPO_LICENCA_ID}' ); sendForm(document.CacicCommon_form);" />
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/details.gif" title="{TIPO_LICENCA_ACTIONS_EDIT_TITLE}" 
					       onClick="setDocVar( 'btn_action_edit', '{AQUISICAO_ID}_{SOFTWARE_ID}_{TIPO_LICENCA_ID}' ); sendForm(document.CacicCommon_form);" />
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
