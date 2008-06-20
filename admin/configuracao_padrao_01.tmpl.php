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
<table>
  <tr> 
    <td class="cabecalho">{TITULO}</td>
  </tr>
  <tr> 
    <td class="descricao">
	  <fieldset>
	    <legend>{DESCRICAO}</legend>
	     <form id="StandardSetup_form" method="post">
	      <table>
		  	<tr class="header">
                <th>Diretiva</th>
                <th>Valor</th>
		  	</tr>
		  	<tr class="even">
		  		<td>{NM_ORGANIZACAO_TITLE}</td>
		  		<td>
		  		   <input {OU_ENABLED} type="text" id="nm_organizacao" name="nm_organizacao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="70" maxlength="150" value="{NM_ORGANIZACAO}" />
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
		  		<td>{EXIBE_BANDEJA_TITLE}</td>
		  		<td>
		  		   <input {EXIBE_BANDEJA_ENABLED} type="checkbox" id="in_exibe_bandeja" name="in_exibe_bandeja" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" {EXIBE_BANDEJA} />
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>{EXIBE_ERROS_CRITICOS_TITLE}</td>
		  		<td>
		  		   <input {EXIBE_ERROS_CRITICOS_ENABLED} type="checkbox" id="in_exibe_erros_criticos" name="in_exibe_erros_criticos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" size="20" maxlength="20" {EXIBE_ERROS_CRITICOS} />
		  		</td>
		  	</tr>
		  	<tr class="odd">
		  		<td>
		  		   {TE_MACADDR_INVALID_TITLE}<br />
		  		   <span class="help">{TE_MACADDR_INVALID_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_MACADDR_INVALID_ENABLED} id="te_enderecos_mac_invalidos" name="te_enderecos_mac_invalidos" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="70" wrap="off">{TE_MACADDR_INVALID}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="even">
		  		<td>
		  		   {TE_JANELAS_EXCECAO_TITLE}<br />
		  		   <span class="help">{TE_JANELAS_EXCECAO_HELP}</span>
		  		</td>
		  		<td>
		  		   <textarea {TE_JANELAS_EXCECAO_ENABLED} id="te_janelas_excecao" name="te_janelas_excecao" onFocus="setClass(this, 'inputFocus');" onBlur="setClass(this, 'input');" rows="3" cols="70" wrap="off">{TE_JANELAS_EXCECAO}</textarea>
		  		</td>
		  	</tr>
		  	<tr class="btnForm">
		  		<td colspan="2">
		  		   <input {EXIBE_ERROS_CRITICOS_ENABLED} type="submit" id="salvar" name="salvar" value="{BTN_SALVAR}" onClick="setDocVar( 'btn_salvar', 1 );" />
		  		   <input type="hidden" id="btn_salvar" name="btn_salvar" value="" />
		  		</td>
		  	</tr>
		 </table>
		</form>
	  </fieldset>
    </td>
  </tr>
</table>
<cfgPadrao:comment>
<pre>
SCHEMA 
  `in_exibe_erros_criticos` char(1) default NULL,
  `in_exibe_bandeja` char(1) default NULL,
  `nu_exec_apos` int(11) default NULL,
  `nm_organizacao` varchar(150) default NULL,
  `nu_intervalo_exec` int(11) default NULL,
  `nu_intervalo_renovacao_patrim` int(11) default NULL,
  `te_senha_adm_agente` varchar(30) default NULL,
  `te_serv_updates_padrao` varchar(20) default NULL,
  `te_serv_cacic_padrao` varchar(20) default NULL,
  `te_enderecos_mac_invalidos` text,
  `te_janelas_excecao` text,
  `cs_abre_janela_patr` char(1) NOT NULL default 'S',
  `id_default_body_bgcolor` varchar(10) NOT NULL default '#EBEBEB',
  `te_exibe_graficos` varchar(100) NOT NULL default '[acessos_locais][so][acessos][locais]'
DADOS 
   (`in_exibe_erros_criticos`, `in_exibe_bandeja`, `nu_exec_apos`, `nm_organizacao`, `nu_intervalo_exec`,
    `nu_intervalo_renovacao_patrim`, `te_senha_adm_agente`, `te_serv_updates_padrao`, `te_serv_cacic_padrao`,
    `te_enderecos_mac_invalidos`, `te_janelas_excecao`, `cs_abre_janela_patr`, `id_default_body_bgcolor`,
    `te_exibe_graficos`)
   ('N', 'S', 10, 'Nome da Organização - Tabela Configurações Padrão', 4, 0, '5a584f8a61b65baf', '10.71.0.121',
    '10.71.0.121', '00-00-00-00-00-00,44-45-53-54-00-00,44-45-53-54-00-01,\r\n00-53-45-00-00-00,00-50-56-C0-00-01,00-50-56-C0-00-08', 
    'openoffice.org, microsoft word, photoshop, hod, aor.exe, pc2003.exe, cpp.exe, sal.exe, sal.bat, girafa4.exe, dro.exe, plenus', 
    'N', '#EBEBEB', '[so][acessos][locais][acessos_locais]');
</pre>
</cfgPadrao:comment>

<script type="text/javascript">
  setFocus('{SET_FOCUS}');
</script>

</cfgPadrao:tmpl>
