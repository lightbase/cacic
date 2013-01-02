<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informa��es da Previd�ncia Social, Brasil

 Este arquivo � parte do programa CACIC - Configurador Autom�tico e Coletor de Informa��es Computacionais

 O CACIC � um software livre; voc� pode redistribui-lo e/ou modifica-lo dentro dos termos da Licen�a P�blica Geral GNU como 
 publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a, ou (na sua opni�o) qualquer vers�o.

 Este programa � distribuido na esperan�a que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUA��O a qualquer
 MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU para maiores detalhes.

 Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "LICENCA.txt", junto com este programa, se n�o, escreva para a Funda��o do Software
 Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * @version $Id: configuracao_padrao.class.tmpl.php 2008-06-18 22:10 harpiain $
 * @package CACIC-Admin
 * @subpackage AdminSetup
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates para configura��o (Padrao) do CACIC
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );

?>
<cfgPadrao:tmpl name="StandardSetup">
	<link href="{CACIC_URL}/admin/configuracao_padrao.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/admin/configuracao_padrao.js"></script>
</cfgPadrao:tmpl>

<cfgPadrao:tmpl name="StandardSetup_form">
<div class="cabecalho">
<table>
  <tr> 
    <td class="cabecalho">
      <cfgPadrao:comment><img src="../imgs/cacic_logo.png" /></cfgPadrao:comment>
      {TITULO}
    </td>
  </tr>
  <tr> 
    <td>
      <form id="StandardSetup_form" name="StandardSetup_form" method="post">
	   <fieldset class="corpo">
	     <legend>{DESCRICAO}</legend>
	      <table>
		  	<tr class="header">
                <th width="37%">Diretiva</th>
                <th>Valor</th>
		  	</tr>
		  	<tr class="even">
		  		<td>{NM_ORGANIZACAO_TITLE}</td>
		  		<td>
		  		   <input {OU_ENABLED} type="text" id="nm_organizacao" name="nm_organizacao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="60" maxlength="150" value="{NM_ORGANIZACAO}" />
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>{TE_SERVUPDT_STD_TITLE}</td>
		  		<td>
		  		   <input {SRVUPDT_ENABLED} type="text" id="te_serv_updates_padrao" name="te_serv_updates_padrao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="60" maxlength="60" value="{TE_SERVUPDT_STD}" />
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>{TE_SERVCACIC_STD_TITLE}</td>
		  		<td>
		  		   <input {TE_SERVCACIC_STD_ENABLED} type="text" id="te_serv_cacic_padrao" name="te_serv_cacic_padrao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="60" maxlength="60" value="{TE_SERVCACIC_STD}" />
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>{SENHA_AGENTE_TITLE}</td>
		  		<td>
		  		   <input {SENHA_AGENTE_ENABLED} type="text" id="te_senha_adm_agente" name="te_senha_adm_agente" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="30" maxlength="30" value="{SENHA_AGENTE}" />
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>
		  		   {NU_REL_MAXLINHAS_TITLE}<br />
		  		</td>
		  		<td>
		  		   <input {NU_REL_MAXLINHAS_ENABLED} type="text" id="nu_rel_maxlinhas" name="nu_rel_maxlinhas" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="10" maxlength="10" value="{NU_REL_MAXLINHAS}" />
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>
		  		   {RESOLUCAO_GRAFICO_TITLE}<br />
		  		</td>
		  		<td>
		  		   {NU_RESOLUCAO_GRAFICO_H_TITLE}
		  		   <input {NU_RESOLUCAO_GRAFICO_H_ENABLED} type="text" id="nu_resolucao_grafico_h" name="nu_resolucao_grafico_h" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="10" maxlength="10" value="{NU_RESOLUCAO_GRAFICO_H}" />
		  		   {NU_RESOLUCAO_GRAFICO_W_TITLE}
		  		   <input {NU_RESOLUCAO_GRAFICO_W_ENABLED} type="text" id="nu_resolucao_grafico_w" name="nu_resolucao_grafico_w" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="10" maxlength="10" value="{NU_RESOLUCAO_GRAFICO_W}" />
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>
		  		   {TE_MACADDR_INVALID_TITLE}<br />
		  		   <span class="help">{TE_MACADDR_INVALID_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_MACADDR_INVALID_ENABLED} id="te_enderecos_mac_invalidos" name="te_enderecos_mac_invalidos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="65" wrap="off">{TE_MACADDR_INVALID}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>
		  		   {TE_JANELAS_EXCECAO_TITLE}<br />
		  		   <span class="help">{TE_JANELAS_EXCECAO_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_JANELAS_EXCECAO_ENABLED} id="te_janelas_excecao" name="te_janelas_excecao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="65" wrap="off">{TE_JANELAS_EXCECAO}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>
		  		  <fieldset>
		  		   <legend>{TE_EXIBEGRAFICOS_TITLE}</legend>
		  		   <table>
				  	<tr>
				  		<td>
				  		   <input {TE_EXIBEGRAFICOS_SO_ENABLED} type="checkbox" id="te_exibe_graficos_so" name="te_exibe_graficos_so" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {TE_EXIBEGRAFICOS_SO} />
				  		   {TE_EXIBEGRAFICOS_SO_TITLE}
				  		</td>
				  		<td>
				  		   <input {TE_EXIBEGRAFICOS_ACESSOS_ENABLED} type="checkbox" id="te_exibe_graficos_acessos" name="te_exibe_graficos_acessos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {TE_EXIBEGRAFICOS_ACESSOS} />
				  		   {TE_EXIBEGRAFICOS_ACESSOS_TITLE}
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>
				  		   <input {TE_EXIBEGRAFICOS_ACESSOSLOCAIS_ENABLED} type="checkbox" id="te_exibe_graficos_acessoslocais" name="te_exibe_graficos_acessoslocais" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {TE_EXIBEGRAFICOS_ACESSOSLOCAIS} />
				  		   {TE_EXIBEGRAFICOS_ACESSOSLOCAIS_TITLE}
				  		</td>
				  		<td>
				  		   <input {TE_EXIBEGRAFICOS_LOCAIS_ENABLED} type="checkbox" id="te_exibe_graficos_locais" name="te_exibe_graficos_locais" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {TE_EXIBEGRAFICOS_LOCAIS} />
				  		   {TE_EXIBEGRAFICOS_LOCAIS_TITLE}
				  		</td>
				  	</tr>
		  		   </table>
		  		  </fieldset>
		  		</td>
		  		<td>
		  		  <fieldset>
		  		   <legend>{TE_EXIBEOUTROS_TITLE}</legend>
		  		   <table>
				  	<tr>
				  		<td>
				  		   <input {EXIBE_BANDEJA_ENABLED} type="checkbox" id="in_exibe_bandeja" name="in_exibe_bandeja" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {EXIBE_BANDEJA} />
				  		   {EXIBE_BANDEJA_TITLE}
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>
				  		   <input {EXIBE_JANELAPATR_ENABLED} type="checkbox" id="cs_abre_janela_patr" name="cs_abre_janela_patr" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {EXIBE_JANELAPATR} />
				  		   {EXIBE_JANELAPATR_TITLE}
				  		</td>
				  	</tr>
				  	<tr>
				  		<td>
				  		   <input {EXIBE_ERROS_CRITICOS_ENABLED} type="checkbox" id="in_exibe_erros_criticos" name="in_exibe_erros_criticos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {EXIBE_ERROS_CRITICOS} />
				  		   {EXIBE_ERROS_CRITICOS_TITLE}
				  		</td>
				  	</tr>
		  		   </table>
		  		  </fieldset>
		  		</td>
		  	</tr>
		  	<tr>
		  		<td colspan="2" class="botoes">
		  		      <input type="hidden" id="btn_salvar" name="btn_salvar" value="" />
				      <span  class='botoes{BTN_SALVAR_DENY}'><input type='button' title="{BTN_SALVAR}" name="{BTN_SALVAR}" onClick="setDocVar( 'btn_salvar', 1 ); sendForm(this.form);" value="{BTN_SALVAR}" {BTN_SALVAR_DENY} /></span>
				      <input type='reset' onClick="setFocus('nm_organizacao');" title="{BTN_RESET}" value="{BTN_RESET}" />
		  		</td>
		  	</tr>
		 </table>
	    </fieldset>
	  </form>
    </td>
  </tr>
</table>
</div>

<script type="text/javascript">
  setFocus('{SET_FOCUS}');
</script>

</cfgPadrao:tmpl>
