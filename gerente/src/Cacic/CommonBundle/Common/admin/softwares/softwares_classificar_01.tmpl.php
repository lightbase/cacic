<?php
/**
 * @version $Id: softwares_classificar_01.tmpl.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Admin
 * @subpackage SoftwaresClassificar
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para configuração (Padrao) do CACIC
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<softwareClassificacao:tmpl name="SoftwaresClassificar">
	<link href="{CACIC_URL}/admin/softwares/softwares_classificacao_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/softwares/softwares_classificacao_01.js"></script>
</softwareClassificacao:tmpl>

<softwareClassificacao:tmpl name="SoftwaresClassificar_form">
<div class="cabecalho">
<table>
  <tr> 
    <td class="cabecalho">
      <softwareClassificacao:comment><img src="../imgs/cacic_logo.png" /></softwareClassificacao:comment>
      {TITULO}
    </td>
  </tr>
  <tr> 
    <td>
	   <fieldset class="corpo">
	     <legend>{DESCRICAO}</legend>
           <table>
			<tr>
			 <td align="right" class="botoes">{SOFTWARE_CLASSIFICADO_SELECT}
			 </td>
			 <td align="left" colspan={COLSPAN}>
			     <select name="software_nao_classificado" onChange="setDocVar( 'page', 1 ); sendForm(this.form);">
	             <softwareClassificacao:tmpl name="SoftwaresSelectType_list">
				  <option value="{SOFTWARE_TYPE_ID}" {SOFTWARE_TYPE_ID_SELECTED}>{SOFTWARE_TYPE_NAME}</option>
				 </softwareClassificacao:tmpl>
				 </select>
			 </td>
			</tr>
			<tr  height="70">
			 <th class="header">{SOFTWARE_NAME_TITLE}
			 </th>
             <softwareClassificacao:tmpl name="SoftwaresType_list">
			  <td width="5%" class="header">
			   <object data="softwares_classificar_tipo_{IMG_TYPE}image.php?texto={SOFTWARE_TYPE_NAME}" type="image/svg+xml"></object>
			  </td>
			 </softwareClassificacao:tmpl>
			</tr>
            <softwareClassificacao:tmpl name="SoftwaresInventariados_list">
			<tr class="even" onMouseOver="this.className = 'odd';" onMouseOut="this.className = 'even';">
			<td>
			  {SOFTWARE_NAME}
			</td>
            {SOFTWARESCLASSIFICAR_TIPO}
			</tr>
			 </softwareClassificacao:tmpl>
		  	<tr>
		  		<td colspan="{COLSPAN}" class="botoes">
		  		      <input type="hidden" id="btn_salvar" name="btn_salvar" value="" />
				      <span  class='botoes{BTN_SALVAR_DENY}'>
				        <input type='button' title="{BTN_SALVAR}" name="{BTN_SALVAR}" onClick="setDocVar( 'btn_salvar', 1 ); sendForm(this.form);" value="{BTN_SALVAR}" {BTN_SALVAR_DENY} />
				      </span>
				      <input type='reset' title="{BTN_RESET}" value="{BTN_RESET}" />
		  		</td>
		  	</tr>
		 </table>
	    </fieldset>
    </td>
  </tr>
</table>
</div>
</softwareClassificacao:tmpl>
