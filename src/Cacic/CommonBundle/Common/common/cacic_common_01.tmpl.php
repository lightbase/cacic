<?php
/**
 * @version $Id: cacic_common_01.tmpl.php,v 1.1.1.1 2012/09/14 16:01:08 d302112 Exp $
 * @package CACIC-Common
 * @subpackage CacicTemplate
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @copyright Copyright (C) 2008 Adriano dos Santos Vieira. All rights reserved.
 * @license GNU/GPL, see LICENSE.php
 * CACIC is free software and parts of it may contain or be derived from the
 * GNU General Public License or other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 * 
 * Templates padrão para o CACIC
 */

// direct access is denied
defined( 'CACIC' ) or die( 'Acesso restrito (Restricted access)!' );
?>
<cacicCommon:tmpl name="CacicCommon_head">
<html>
<head>
	<title>{CACIC_TITLE}</title>
	<meta http-equiv="Content-Language" content="{CACIC_LANG}" />
	<meta http-equiv="Content-Type" content="text/html; charset={CACIC_LANG_CHARSET}" />
	<link href="{CACIC_URL}/include/css/cacic.css" rel="stylesheet" type="text/css" />
	<link href="{CACIC_URL}/common/cacic_common_01.css" rel="stylesheet" type="text/css" />
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/include/js/cacic.js"></script>
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/common/cacic_common_01.js"></script>
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/bibliotecas/javascript/asv/asvAjax.js"></script>
	<script language="JavaScript" type="text/javascript" src="{CACIC_URL}/bibliotecas/javascript/asv/asvUtils.js"></script>
</head>
<body class="frameprincipal">
<FORM id="CacicCommon_form" name="CacicCommon_form" method="post">
</cacicCommon:tmpl>

<cacicCommon:tmpl name="CacicCommon_messages">
<div class="cabecalho">
<table>
  <tr> 
    <td>
	   <fieldset class="messages">
	     <legend>{MESSAGES}</legend>
	      <cacicCommon:tmpl name="CacicCommon_messages_cond" type="condition" conditionvar="msgtype">
		      <cacicCommon:sub condition="js">
					<script language="javascript">
					document.write('{MESSAGE}');
					</script>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="__default">
			     <span id='message' class="message">{MESSAGE}</span>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
	   </fieldset>
    </td>
  </tr>
</table>
</cacicCommon:tmpl>

<cacicCommon:tmpl name="CacicCommon_footer">
</FORM>
</body>
</html>
</cacicCommon:tmpl>

<cacicCommon:tmpl name="CacicCommon_pagination">
<div class="pagination">
<table>
  <tr> 
    <td>
	   <fieldset>
	     <legend>{PAGE_TITLE}</legend>
	     <ul>
	     
	      <cacicCommon:tmpl name="CacicCommon_pagination_first" type="condition" conditionvar="show">
			  <cacicCommon:sub condition="1">
			     <li>
			       <span class="prevnext{DISABLED}" title="{PAGE_TEXT_TITLE}" 
			             onClick="setDocVar( 'page', {PAGE_NUMBER} ); sendForm(document.CacicCommon_form);">
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="disabled">
			     <li>
			       <span class="prevnextdisabled" title="{PAGE_TEXT_TITLE}" >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
		  
	      <cacicCommon:tmpl name="CacicCommon_pagination_back" type="condition" conditionvar="show">
			  <cacicCommon:sub condition="1">
			     <li>
			       <span class="prevnext{DISABLED}" title="{PAGE_TEXT_TITLE}" 
			             onClick="setDocVar( 'page', {PAGE_NUMBER} ); sendForm(document.CacicCommon_form);">
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="disabled">
			     <li>
			       <span class="prevnextdisabled" title="{PAGE_TEXT_TITLE}" >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
		  
		  <cacicCommon:tmpl name="CacicCommon_pages_list_cond" type="condition" conditionvar="disabled">
			  <cacicCommon:sub condition="1" or condition="disabled">
			     <li>
			       <span class="{CLASS_CURRENT_PAGE}" title="{PAGE_TEXT_TITLE}" >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="__default">
			     <li>
			       <span class="{CLASS_CURRENT_PAGE}" title="{PAGE_TEXT_TITLE}" 
			             onClick="setDocVar( 'page', {PAGE_NUMBER} ); sendForm(document.CacicCommon_form);"
			             >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
		  
	      <cacicCommon:tmpl name="CacicCommon_pagination_next" type="condition" conditionvar="show">
			  <cacicCommon:sub condition="1">
			     <li>
			       <span class="prevnext" title="{PAGE_TEXT_TITLE}" 
			             onClick="setDocVar( 'page', {PAGE_NUMBER} ); sendForm(document.CacicCommon_form);">
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="disabled">
			     <li>
			       <span class="prevnextdisabled" title="{PAGE_TEXT_TITLE}" >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
		  
	      <cacicCommon:tmpl name="CacicCommon_pagination_last" type="condition" conditionvar="show">
			  <cacicCommon:sub condition="1">
			     <li>
			       <span class="prevnext{DISABLED}" title="{PAGE_TEXT_TITLE}" 
			             onClick="setDocVar( 'page', {PAGE_NUMBER} ); sendForm(document.CacicCommon_form);">
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
			  <cacicCommon:sub condition="disabled">
			     <li>
			       <span class="prevnextdisabled" title="{PAGE_TEXT_TITLE}" >
			         {PAGE_TEXT}
			       </span>
			     </li>
			  </cacicCommon:sub>
		  </cacicCommon:tmpl>
		  
		 </ul>		  
	   </fieldset>
	   <input type="hidden" id="page" name="page" value="{PAGE_CURRENT}" />
    </td>
  </tr>
</table>
</div>
</cacicCommon:tmpl>
