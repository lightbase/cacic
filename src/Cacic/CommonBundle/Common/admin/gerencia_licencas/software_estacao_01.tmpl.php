<?php
/**
 * @version $Id: software_estacao_01.tmpl.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage GerenciaLicencas
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para Controle de Software por Estação
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<softwareEstacao:tmpl name="SoftwareEstacao">
	<link href="{CACIC_URL}/admin/gerencia_licencas/aquisicoes_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/gerencia_licencas/software_estacao_01.js"></script>
	
    <script src="{CACIC_URL}/include/js/sniffer.js" type="text/javascript" language="javascript"></script>
    <script src="{CACIC_URL}/include/js/dyncalendar.js" type="text/javascript" language="javascript"></script>
    <link href="{CACIC_URL}/include/css/dyncalendar.css" media="screen" rel="stylesheet">
</softwareEstacao:tmpl>

<softwareEstacao:tmpl name="SoftwareEstacao_form">
<div class="cabecalho">
<table>
  <tr> 
    <td class="cabecalho">
      <softwareEstacao:comment><img src="../../imgs/cacic_logo.png" /></softwareEstacao:comment>
      {TITULO}
    </td>
  </tr>
  <tr> 
    <td>
	   <fieldset class="corpo">
	     <legend>{DESCRICAO_TITLE} <b>{DESCRICAO_TITLE_MODE}</b></legend>
           <table cellspacing="01">
			<tr>
			 <td colspan="10">
			   <softwareEstacao:tmpl name="SoftwareEstacao_insert_edit" type="condition" conditionvar="acl_permission">
			   
			    <softwareEstacao:sub condition="disabled">
		  		  <softwareEstacao:comment><!-->
		  		  ** Caso o usuario nao tem permissao (ACL) não mostra formulário ou botoes de inserção
		  		  <--></softwareEstacao:comment>
				  </softwareEstacao:sub>
				
		  		<softwareEstacao:comment><!-->
		  		** Formulário para inserção de dados
		  		<--></softwareEstacao:comment>
			    <softwareEstacao:sub condition="1">
			     <fieldset>
			      <table>
			      <tr>
			       <td>{PATRIMONIO_INPUT_LABEL}<span class="necessario">*</span></td>
			       <td>
			        <input type="hidden" id="nr_patrimonio_aux" name="nr_patrimonio_aux" value="{PATRIMONIO_NR}">
		  		    <input type="text" id="nr_patrimonio" name="nr_patrimonio" value="{PATRIMONIO_NR}" {PATRIMONIO_NR_DISABLED}
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaPatrimonio('{MSG_VALIDACAO_PATRIMONIO}')){setClass(this, 'input');}" >
		  		            
		  		    <span class="erro" id="error_nr_patrimonio"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{SOFTWARE_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="hidden" id="id_software_aux" name="id_software_aux" value="{SOFTWARE_ID}">
		  		    <select id="id_software" name="id_software" value="{SOFTWARE_ID}" {SOFTWARE_DISABLED}
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaSoftware('{MSG_VALIDACAO_SOFTWARE}')){setClass(this, 'input');}" >
		  		            
		  		      <option value="">{SELECT_OPTION}</option>
		  		      
			         <softwareEstacao:tmpl name="Software_insert_edit_list">
			          <option value="{SOFTWARE_ID}" {SOFTWARE_SELECTED}>{SOFTWARE_NAME}</option>
 			         </softwareEstacao:tmpl>
 			         
		  		    </select>

		  		    <span class="erro" id="error_id_software"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td width="30%">{AQUISICAO_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="nr_processo" name="nr_processo" value="{AQUISICAO_PROC}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="if(validaAquisicao('{MSG_VALIDACAO_AQUISICAO}')){setClass(this, 'input');}" >
		  		            
		  		    <span class="erro" id="error_nr_processo"></span>
		  		    <input type="hidden" id="id_aquisicao" name="id_aquisicao" value="{SOFTWARE_ID}" >
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{COMPUTADOR_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="nm_computador" name="nm_computador" value="{COMPUTADOR_NAME}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaComputador('{MSG_VALIDACAO_COMPUTADOR_NAME}')){setClass(this, 'input');}" />
		  		            
		  		    <span class="erro" id="error_nm_computador"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{DATA_AUTORIZACAO_INPUT_LABEL}<span class="necessario">*</span></td>
		  		   <td>
		  		    <input type="text" id="dt_autorizacao" name="dt_autorizacao" value="{DATA_AUTORIZACAO}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="if(validaDataAutorizacao('{MSG_VALIDACAO_DATA}')){setClass(this, 'input');}else{return false;}" />
		  		     <span class="HelpImg" title="{DATA_AUTORIZACAO_HELP}"> </span>
		  		     <script type="text/javascript" language="JavaScript">
						<!--
							function dataCallback(date, month, year)	{
								if (String(month).length == 1) {
									month = '0' + month;
								}
								if (String(date).length == 1) {
									date = '0' + date;
								}
								document.CacicCommon_form.dt_autorizacao.value = date + '/' + month + '/' + year;
							}
						  	dt_autorizacao = new dynCalendar('dt_autorizacao', 'dataCallback');
						-->
					</script>
					{DATA_FORMATO}
		  		            
		  		    <span class="erro" id="error_dt_autorizacao"></span>
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{DATA_EXPIRACAO_INPUT_LABEL}</td>
		  		   <td>
		  		    <input type="text" id="dt_expiracao_instalacao" name="dt_expiracao_instalacao" value="{DT_EXPIRACAO_INSTALACAO}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="setClass(this, 'input');" />
		  		     <span class="HelpImg" title="{DATA_EXPIRACAO_HELP}"> </span>
		  		     <script type="text/javascript" language="JavaScript">
						<!--
							function dataExpiracaoCallback(date, month, year)	{
								if (String(month).length == 1) {
									month = '0' + month;
								}
								if (String(date).length == 1) {
									date = '0' + date;
								}
								document.CacicCommon_form.dt_expiracao_instalacao.value = date + '/' + month + '/' + year;
							}
						  	dt_expiracao_instalacao = new dynCalendar('dt_expiracao_instalacao', 'dataExpiracaoCallback');
						-->
					</script>
					{DATA_FORMATO}
					
		  		    <span class="erro" id="error_dt_expiracao_instalacao"></span>        
		  		   </td>
		  		  </tr>
		  		  
		  		  <tr>
		  		   <td>{DATA_DESINSTALACAO_INPUT_LABEL}</td>
		  		   <td>
		  		    <input type="text" id="dt_desinstalacao" name="dt_desinstalacao" value="{DT_DESINSTALACAO}"
		  		            onFocus="setClass(this, 'inputFocus');" 
		  		            onBlur="setClass(this, 'input');" />
		  		     <span class="HelpImg" title="{DATA_DESINSTALACAO_HELP}"> </span>
		  		     <script type="text/javascript" language="JavaScript">
						<!--
							function dataDesinstalacaoCallback(date, month, year)	{
								if (String(month).length == 1) {
									month = '0' + month;
								}
								if (String(date).length == 1) {
									date = '0' + date;
								}
								document.CacicCommon_form.dt_desinstalacao.value = date + '/' + month + '/' + year;
							}
						  	dt_desinstalacao = new dynCalendar('dt_desinstalacao', 'dataDesinstalacaoCallback');
						-->
					</script>
					{DATA_FORMATO}
					
		  		    <span class="erro" id="error_dt_desinstalacao"></span>        
		  		   </td>
		  		  </tr>
		  		  
			      <tr>
			       <td>{PATRIMONIO_DESTINO_INPUT_LABEL}</td>
			       <td>
		  		    <input type="text" id="nr_patr_destino" name="nr_patr_destino" value="{PATRIMONIO_DESTINO}"
		  		    		onClick="setClass(this, 'inputFocus');"
		  		            onBlur="setClass(this, 'input');" >
		  		            
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
		  		  
		  		  <softwareEstacao:comment><!-->
		  		  ** Botoes para salvar/cancelar dados inseridos no formulario
		  		  <--></softwareEstacao:comment>
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
		  		 
				</softwareEstacao:sub>
				
		  		<softwareEstacao:comment><!-->
		  		** Botao para entrar em modo de inserção de dados no formulario
		  		<--></softwareEstacao:comment>
			    <softwareEstacao:sub condition="__default">
			       <div align="right">
				      <span class='botoes{BTN_INCLUIR_DENY}'>
				        <input class='botoes{BTN_INCLUIR_DENY}' type='button' title="{BTN_INCLUIR_TITLE}" name="{BTN_INCLUIR}"
				               onClick="setDocVar( 'btn_action_incluir', 1 ); sendForm(document.CacicCommon_form);" value="{BTN_INCLUIR}" {BTN_INCLUIR_DENY} />
				      </span>
				   </div>
		  		      <input type="hidden" id="btn_action_incluir" name="btn_action_incluir" />
				</softwareEstacao:sub>
				
			  </softwareEstacao:tmpl>
			 </td>
			</tr>
			
		  	<softwareEstacao:comment><!-->
		  	** Formulario para mostrar dados cadastrados no banco
		  	<--></softwareEstacao:comment>
			<tr>
			 <th class="header" style='width: 3%;'>{SEQUENCIAL_TITLE}</th>
			 <th class="header">{PATRIMONIO_TITLE}</th>
			 <th class="header">{SOFTWARE_TITLE}</th>
			 <th class="header">{AQUISICAO_PROC_TITLE}</th>
			 <th class="header">{COMPUTADOR_TITLE}</th>
			 <th class="header">{DATA_AUTORIZACAO_TITLE}</th>
             <softwareEstacao:tmpl name="SoftwareEstacao_actions_acl" type="condition" conditionvar="acl_permission">
			    <softwareEstacao:sub condition="disabled">
				</softwareEstacao:sub>
			    <softwareEstacao:sub condition="__default">
			      <th class="header" style='width: 10%;'>{ACTIONS_TITLE}</th>
				</softwareEstacao:sub>
			 </softwareEstacao:tmpl>
			</tr>
			
			<softwareEstacao:comment><!-->
			  Condicional (ACL), via template, para não mostrar botoes de inclusão, edição ou exclusão
			<--></softwareEstacao:comment> 
            <softwareEstacao:tmpl name="SoftwareEstacao_list" type="condition" conditionvar="acl_permission">
			    <softwareEstacao:sub condition="disabled">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
					<td>
					  {SEQUENCIAL}
					</td>
					<td>
					  {PATRIMONIO_NR}
					</td>
					<td>
					  {SOFTWARE_NAME}
					</td>
					<td>
					  {AQUISICAO_PROC}
					</td>
					<td>
					  {COMPUTADOR_NAME}
					</td>
					<td align="center">
					  {DATA_AUTORIZACAO}
					</td>
					</tr>
				</softwareEstacao:sub>
			    <softwareEstacao:sub condition="__default">
					<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';"
					    title="{OBSERVACAO}">
					<td>
					  {SEQUENCIAL}
					</td>
					<td>
					  {PATRIMONIO_NR}
					</td>
					<td>
					  {SOFTWARE_NAME}
					</td>
					<td>
					  {AQUISICAO_PROC}
					</td>
					<td>
					  {COMPUTADOR_NAME}
					</td>
					<td align="center">
					  {DATA_AUTORIZACAO}
					</td>
					<td>
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/error.png" title="{ACTIONS_DELETE_TITLE}" 
					       onClick="setDocVar( 'btn_action_excluir', '{SOFTWARE_ID}_{PATRIMONIO_NR}' ); sendForm(document.CacicCommon_form);" />
					  <img style='width: 16px; height: 16px; cursor:pointer;' src="../../imgs/details.gif" title="{ACTIONS_EDIT_TITLE}" 
					       onClick="setDocVar( 'btn_action_edit', '{SOFTWARE_ID}_{PATRIMONIO_NR}' ); sendForm(document.CacicCommon_form);" />
					</td>
					</tr>
				</softwareEstacao:sub>
			 </softwareEstacao:tmpl>
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
</softwareEstacao:tmpl>
