<?php
/*
 Copyright 2000, 2001, 2002, 2003, 2004, 2005 Dataprev - Empresa de Tecnologia e Informações da Previdência Social, Brasil

 Este arquivo é parte do programa CACIC - Configurador Automático e Coletor de Informações Computacionais

 O CACIC é um software livre; você pode redistribui-lo e/ou modifica-lo dentro dos termos da Licença Pública Geral GNU como 
 publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença, ou (na sua opnião) qualquer versão.

 Este programa é distribuido na esperança que possa ser  util, mas SEM NENHUMA GARANTIA; sem uma garantia implicita de ADEQUAÇÂO a qualquer
 MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU para maiores detalhes.

 Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, escreva para a Fundação do Software
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
 * Templates para configuração (Padrao) do CACIC
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
		  		   <input {OU_ENABLED} type="text" id="nm_organizacao" name="nm_organizacao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="55" maxlength="150" value="{NM_ORGANIZACAO}" />
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>{TE_SERVUPDT_STD_TITLE}</td>
		  		<td>
		  		   <input {SRVUPDT_ENABLED} type="text" id="te_serv_updates_padrao" name="te_serv_updates_padrao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="20" maxlength="20" value="{TE_SERVUPDT_STD}" />
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>{TE_SERVCACIC_STD_TITLE}</td>
		  		<td>
		  		   <input {TE_SERVCACIC_STD_ENABLED} type="text" id="te_serv_cacic_padrao" name="te_serv_cacic_padrao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="20" maxlength="20" value="{TE_SERVCACIC_STD}" />
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
		  		   {TE_MACADDR_INVALID_TITLE}<br />
		  		   <span class="help">{TE_MACADDR_INVALID_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_MACADDR_INVALID_ENABLED} id="te_enderecos_mac_invalidos" name="te_enderecos_mac_invalidos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="65" wrap="off">{TE_MACADDR_INVALID}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>
		  		   {TE_JANELAS_EXCECAO_TITLE}<br />
		  		   <span class="help">{TE_JANELAS_EXCECAO_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_JANELAS_EXCECAO_ENABLED} id="te_janelas_excecao" name="te_janelas_excecao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="65" wrap="off">{TE_JANELAS_EXCECAO}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="even">
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
				      <input type='button' title="{BTN_SALVAR}" name="{BTN_SALVAR}" onClick="setDocVar( 'btn_salvar', 1 ); sendForm(this.form);" value="{BTN_SALVAR}" />
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
<cfgPadrao:comment>
<pre>
SCHEMA 
--  `in_exibe_erros_criticos` char(1) default NULL,
--  `in_exibe_bandeja` char(1) default NULL,
  `nu_exec_apos` int(11) default NULL,
--  `nm_organizacao` varchar(150) default NULL,
  `nu_intervalo_exec` int(11) default NULL,
  `nu_intervalo_renovacao_patrim` int(11) default NULL,
--  `te_senha_adm_agente` varchar(30) default NULL,
--  `te_serv_updates_padrao` varchar(20) default NULL,
--  `te_serv_cacic_padrao` varchar(20) default NULL,
--  `te_enderecos_mac_invalidos` text,
--  `te_janelas_excecao` text,
--  `cs_abre_janela_patr` char(1) NOT NULL default 'S',
  `id_default_body_bgcolor` varchar(10) NOT NULL default '#EBEBEB',
--  `te_exibe_graficos` varchar(100) NOT NULL default '[acessos_locais][so][acessos][locais]'
DADOS 
   (`in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`, `nm_organizacao`, `nu_intervalo_exec`,
    `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`, `te_serv_cacic_padrao`,
    `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `cs_abre_janela_patr`, `id_default_body_bgcolor`,
    `te_exibe_graficos`)
   ('N', 'S', 10, 'Nome da Organização - Tabela Configurações Padrão', 4, 0, '5a584f8a61b65baf', '10.71.0.121',
    '10.71.0.121', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08', 
    'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 
    'N', '#EBEBEB', '[so][acessos][locais][acessos_locais]');
    
  "in_exibe_erros_criticos = 'N', 
  in_exibe_bandeja = 'S',
  nu_exec_apos = 10,
  nm_organizacao = 'Nome da Organização - Tabela Configurações Padrão',
  nm_organizacao = 4,
  nu_intervalo_renovacao_patrim = 0,
  te_senha_adm_agente = '5a584f8a61b65baf',
  te_serv_updates_padrao = '10.71.0.121',
  te_serv_cacic_padrao =  '10.71.0.121',
  te_enderecos_mac_invalidos = '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08',
  te_janelas_excecao = 'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 
  cs_abre_janela_patr = 'N',
  id_default_body_bgcolor = '#EBEBEB',
  te_exibe_graficos = '[so][acessos][locais][acessos_locais]'";
</pre>
</cfgPadrao:comment>

<script type="text/javascript">
  setFocus('{SET_FOCUS}');
</script>

</cfgPadrao:tmpl>
