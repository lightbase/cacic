<?php
/**
 * @version $Id: aquisicoes_01.tmpl.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para Controle de Aquisicoes
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<gerenciaLicencas:tmpl name="Aquisicoes">
	<link href="{CACIC_URL}/admin/gerencia_licencas/aquisicoes_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/gerencia_licencas/aquisicoes_01.js"></script>
	
    <script src="{CACIC_URL}/include/js/sniffer.js" type="text/javascript" language="javascript"></script>
    <script src="{CACIC_URL}/include/js/dyncalendar.js" type="text/javascript" language="javascript"></script>
    <link href="{CACIC_URL}/include/js/dyncalendar.css" media="screen" rel="stylesheet">
</gerenciaLicencas:tmpl>

<gerenciaLicencas:tmpl name="Aquisicoes_form">
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
			   <gerenciaLicencas:tmpl name="Aquisicoes_insert_edit" type="condition" conditionvar="acl_permission">
			   
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
		  		   <td width="30%">{AQUISICAO_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="nr_processo" name="nr_processo" value="{AQUISICAO_PROC}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaAquisicao('{MSG_VALIDACAO_AQUISICAO}')){setClass(this, 'input');}" >
		  		            
		  		    <span class="erro" id="error_nr_processo"></span>
		  		    <input type="hidden" id="id_aquisicao" name="id_aquisicao" value="{AQUISICAO_ID}" >
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{NOME_EMPRESA_NAME_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="nm_empresa" name="nm_empresa" value="{NOME_EMPRESA}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaNomeEmpresa('{MSG_VALIDACAO_NOME_EMPRESA}')){setClass(this, 'input');}" />
		  		    
		  		    <span class="erro" id="error_nm_empresa"></span>
		  		   </td>
		  		  </tr>
		  		  
			      <tr>
			       <td>{NOME_PROPRIETARIO_NAME_INPUT_LABEL}<span class="necessario">*</span></td>
			       <td>
		  		    <input type="text" id="nm_proprietario" name="nm_proprietario" value="{NOME_PROPRIETARIO}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaNomeProprietario('{MSG_VALIDACAO_LIC_TYPE}')){setClass(this, 'input');}" >
		  		            
		  		    <span class="erro" id="error_nm_proprietario"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{NR_NOTA_FISCAL_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="nr_notafiscal" name="nr_notafiscal" value="{NR_NOTA_FISCAL}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaNotaFiscal('{MSG_VALIDACAO_QTDE}')){setClass(this, 'input');}" />
		  		            
		  		    <span class="erro" id="error_nr_notafiscal"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{DATA_AQUISICAO_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="data_aquisicao" name="data_aquisicao" value="{DATA_AQUISICAO}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaDataAquisicao('{MSG_VALIDACAO_DATA}')){setClass(this, 'input');}else{return false;}" />
		  		     <script type="text/javascript" language="JavaScript">
						<!--
							function dataVencimentoCallback(date, month, year)	{
								if (String(month).length == 1) {
									month = '0' + month;
								}
								if (String(date).length == 1) {
									date = '0' + date;
								}
								document.CacicCommon_form.data_aquisicao.value = date + '/' + month + '/' + year;
							}
						  	data_aquisicao = new dynCalendar('data_aquisicao', 'dataVencimentoCallback');
						-->
					</script>
					{DATA_AQUISICAO_FORMATO}
		  		            
		  		    <span class="erro" id="error_data_aquisicao"></span>
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
						  //setFocus('nr_notafiscal');
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
			 <th class="header">{AQUISICAO_TITLE}</th>
			 <th class="header">{NOME_EMPRESA_NAME_TITLE}</th>
			 <th class="header">{NOME_PROPRIETARIO_TITLE}</th>
			 <th class="header">{NR_NOTA_FISCAL_TITLE}</th>
			 <th class="header">{DATA_AQUISICAO_TITLE}</th>
             <gerenciaLicencas:tmpl name="Aquisicoes_actions_acl" type="condition" conditionvar="acl_permission">
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
            <gerenciaLicencas:tmpl name="Aquisicoes_list" type="condition" conditionvar="acl_permission">
			    <gerenciaLicencas:sub condition="disabled">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
					<td>
					  {SEQUENCIAL}
					</td>
					<td>
					  {AQUISICAO_PROC}
					</td>
					<td>
					  {NOME_EMPRESA}
					</td>
					<td>
					  {NOME_PROPRIETARIO}
					</td>
					<td align="right">
					  {NR_NOTA_FISCAL}
					</td>
					<td align="center">
					  {DATA_AQUISICAO}
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
					  {NOME_EMPRESA}
					</td>
					<td>
					  {NOME_PROPRIETARIO}
					</td>
					<td align="right">
					  {NR_NOTA_FISCAL}
					</td>
					<td align="center">
					  {DATA_AQUISICAO}
					</td>
					<td>
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/error.png" title="{ACTIONS_DELETE_TITLE}" 
					       onClick="setDocVar( 'btn_action_excluir', '{AQUISICAO_ID}' ); sendForm(document.CacicCommon_form);" />
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/details.gif" title="{ACTIONS_EDIT_TITLE}" 
					       onClick="setDocVar( 'btn_action_edit', '{AQUISICAO_ID}' ); sendForm(document.CacicCommon_form);" />
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
