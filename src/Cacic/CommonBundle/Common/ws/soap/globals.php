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
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more   |
// | Authors: Dietrich Ayala <dietrich@ganx4.com> Original Author         |
// +----------------------------------------------------------------------+
//
// $Id: globals.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//

/**
* Global variables and constants of the SOAP classes
* 
* @module   globals 
* @package  SOAP
* @version  $Id: globals.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @author   Shane Caraveo <Shane@Caraveo.com>   Port to PEAR and more
* @author   Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
// make errors handle properly in windows (thx, thong@xmethods.com)
error_reporting(2039);

/**
* Enable debugging informations?
*
* @const    SOAP_DEBUG
*/
define('SOAP_DEBUG', true);

if (!function_exists('version_compare') ||
    version_compare(phpversion(), '4.1', '<')) {
    die('requires PHP 4.1 or higher\n');
}
if (version_compare(phpversion(), '4.1', '>=') &&
    version_compare(phpversion(), '4.2', '<')) {
    define('FLOAT', 'double');
} else {
    define('FLOAT', 'float');
}

# for float support
# is there a way to calculate INF for the platform?
define('INF',   1.8e307); 
define('NAN',   0.0);

# define types for value
define('SOAP_VALUE_SCALAR',  1);
define('SOAP_VALUE_ARRAY',   2);
define('SOAP_VALUE_STRUCT',  3);


define('SOAP_LIBRARY_NAME', 'PEAR-SOAPx4 0.6');
// set schema version
define('SOAP_XML_SCHEMA_VERSION',   'http://www.w3.org/2001/XMLSchema');
define('SOAP_XML_SCHEMA_1999',      'http://www.w3.org/1999/XMLSchema');
define('SOAP_SCHEMA',               'http://schemas.xmlsoap.org/wsdl/SOAP/');
define('SOAP_SCHEMA_ENCODING',      'http://schemas.xmlsoap.org/SOAP/encoding/');
define('SOAP_ENVELOP',              'http://schemas.xmlsoap.org/SOAP/envelope/');
define('SOAP_INTEROPORG',           'http://soapinterop.org/xsd');

$SOAP_XMLSchema = array(SOAP_XML_SCHEMA_VERSION, SOAP_XML_SCHEMA_1999);
// load types into typemap array
/*
$SOAP_typemap['http://www.w3.org/2001/XMLSchema'] = array(
	'string','boolean','float','double','decimal','duration','dateTime','time',
	'date','gYearMonth','gYear','gMonthDay','gDay','gMonth','hexBinary','base64Binary',
	// derived datatypes
	'normalizedString','token','language','NMTOKEN','NMTOKENS','Name','NCName','ID',
	'IDREF','IDREFS','ENTITY','ENTITIES','integer','nonPositiveInteger',
	'negativeInteger','long','int','short','byte','nonNegativeInteger',
	'unsignedLong','unsignedInt','unsignedShort','unsignedByte','positiveInteger');
$SOAP_typemap['http://www.w3.org/1999/XMLSchema'] = array(
	'i4','int','boolean','string','double','float','dateTime',
	'timeInstant','base64Binary','base64','ur-type');
$SOAP_typemap[SOAP_INTEROPORG] = array('SOAPStruct');
$SOAP_typemap[SOAP_SCHEMA_ENCODING] = array('base64','array','Array');
*/
$SOAP_typemap[SOAP_XML_SCHEMA_VERSION] = array(
	'string' => 'string',
        'boolean' => 'boolean',
        'float' => FLOAT,
        'double' => 'double',
        'decimal' => 'integer',
        'duration' => 'integer',
        'dateTime' => 'string',
        'time' => 'string',
	'date' => 'string',
        'gYearMonth' => 'integer',
        'gYear' => 'integer',
        'gMonthDay' => 'integer',
        'gDay' => 'integer',
        'gMonth' => 'integer',
        'hexBinary' => 'string',
        'base64Binary' => 'string',
	// derived datatypes
	'normalizedString' => 'string',
        'token' => 'string',
        'language' => 'string',
        'NMTOKEN' => 'string',
        'NMTOKENS' => 'string',
        'Name' => 'string',
        'NCName' => 'string',
        'ID' => 'string',
	'IDREF' => 'string',
        'IDREFS' => 'string',
        'ENTITY' => 'string',
        'ENTITIES' => 'string',
        'integer' => 'integer',
        'nonPositiveInteger' => 'integer',
	'negativeInteger' => 'integer',
        'long' => 'integer',
        'int' => 'integer',
        'short' => 'integer',
        'byte' => 'string',
        'nonNegativeInteger' => 'integer',
	'unsignedLong' => 'integer',
        'unsignedInt' => 'integer',
        'unsignedShort' => 'integer',
        'unsignedByte' => 'integer',
        'positiveInteger'  => 'integer'
        );
$SOAP_typemap[SOAP_XML_SCHEMA_1999] = array(
	'i4' => 'integer',
        'int' => 'integer',
        'boolean' => 'boolean',
        'string' => 'string',
        'double' => 'double',
        'float' => FLOAT,
        'dateTime' => 'string',
	'timeInstant' => 'string',
        'base64Binary' => 'string',
        'base64' => 'string',
        'ur-type' => 'string'
        );
$SOAP_typemap[SOAP_INTEROPORG] = array('SOAPStruct' => 'array');
$SOAP_typemap[SOAP_SCHEMA_ENCODING] = array('base64' => 'string','array' => 'array','Array' => 'array', 'Struct'=>'array');

// load namespace uris into an array of uri => prefix
$SOAP_namespaces = array(
	SOAP_ENVELOP => 'SOAP-ENV',
	SOAP_XML_SCHEMA_VERSION => 'xsd',
	SOAP_XML_SCHEMA_VERSION.'-instance' => 'xsi',
	SOAP_SCHEMA_ENCODING => 'SOAP-ENC',
	SOAP_INTEROPORG=>'si');

$SOAP_xmlEntities = array('quot' => '"','amp' => '&',
	'lt' => '<','gt' => '>','apos' => "'");
    
?>