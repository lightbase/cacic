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
 * Templates para configura��o do CACIC
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );
?>
<cfgCommon:tmpl name="CommonSetup_head">
<html>
<head>
	<title>{CACIC_TITLE}</title>
	<meta http-equiv="Content-Language" content="{CACIC_LANG}" />
	<meta http-equiv="Content-Type" content="text/html; charset={CACIC_LANG_CHARSET}" />
	<link href="{CACIC_URL}/include/cacic.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/include/cacic.js"></script>
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/bibliotecas/javascript/asv/asvAjax.js"></script>
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/bibliotecas/javascript/asv/asvUtils.js"></script>
</head>
<body class="frameprincipal">
</cfgCommon:tmpl>

<cfgCommon:tmpl name="CommonSetup_messages">
<div class="cabecalho">
<table>
  <tr> 
    <td>
	   <fieldset class="messages">
	     <legend>{MESSAGES}</legend>
	      <cfgCommon:tmpl name="CommonSetup_messages_cond" type="condition" conditionvar="msgtype">
		      <cfgCommon:sub condition="js">
					<script language="javascript">
					document.write('{MESSAGE}');
					</script>
			  </cfgCommon:sub>
			  <cfgCommon:sub condition="__default">
			     <span id='message' class="message">{MESSAGE}</span>
			  </cfgCommon:sub>
		  </cfgCommon:tmpl>
	   </fieldset>
    </td>
  </tr>
</table>
</cfgCommon:tmpl>

<cfgCommon:tmpl name="CommonSetup_foot">
</body>
</html>
</cfgCommon:tmpl>
