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
 * Templates para configuração do CACIC
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
<span id='message' class="message"></span>
</cfgCommon:tmpl>

<cfgCommon:tmpl name="CommonSetup_foot">
</body>
</html>
</cfgCommon:tmpl>
