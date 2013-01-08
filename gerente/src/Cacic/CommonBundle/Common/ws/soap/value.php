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
// $Id: value.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
//
require_once 'Base.php';
require_once 'globals.php';
//require_once 'Type/dateTime.php';
//require_once 'Type/hexBinary.php';

/**
*  SOAP::Value
* this class converts values between PHP and SOAP
*
* originaly based on SOAPx4 by Dietrich Ayala http://dietrich.ganx4.com/soapx4
*
* @access public
* @version $Id: value.php,v 1.1.1.1 2012/09/14 16:01:15 d302112 Exp $
* @package SOAP::Client
* @author Shane Caraveo <shane@php.net> Conversion to PEAR and updates
* @author Dietrich Ayala <dietrich@ganx4.com> Original Author
*/
class SOAP_Value extends SOAP_Base
{
    /**
    *
    * @var  boolean
    */
    var $convert_strings = FALSE;
    
    /**
    *
    *
    * @var  string
    */
    var $value = '';
    
    /**
    * 
    * @var  int
    */
    var $type_code = 0;
    
    /**
    *
    * @var  boolean
    */
    var $type_prefix = false;
    
    /**
    * 
    * @var  string
    */
    var $array_type = '';
    
    /**
    *
    * @var  string
    */
    var $name = '';
    
    /**
    *
    * @var  string
    */
    var $type = '';
    
    /**
    *
    * @var  
    */
    var $soapTypes;
    
    /**
    * Namespace
    *
    * @var  string
    */
    var $namespace = '';
    
    /**
    *
    * @var  string
    */
    var $prefix = '';
    
    /**
    *
    * @var  
    */
    var $wsdl;

    /**
    *
    * @var string
    */
    var $arrayType = '';
    
    /**
    *
    *
    * @param    string  name of the soap-value <value_name>
    * @param    mixed   soap value type, if not set an automatic 
    * @param    int
    * @param    mixed
    * @param    mixed
    * @param    mixed
    * @global   $SOAP_typemap, $SOAP_namespaces
    */
    function SOAP_Value($name = '', $type = false, $value = -1, $methodNamespace = NULL, $type_namespace = NULL, $wsdl = NULL)
    {
        global $SOAP_typemap, $SOAP_namespaces;
        parent::SOAP_Base('Value');
        // detect type if not passed
        
        #print("Entering SOAP_Value - name: '$name' type: '$type' value: $value\n");

        $this->name = $name;
        $this->wsdl = $wsdl;
        $this->type = $this->_getSoapType($value, $type);

        #$this->debug("Entering SOAP_Value - name: '$name' type: '$type' value: $value");
        
        if ($methodNamespace) {
            $this->namespace = $methodNamespace;

            if (!isset($SOAP_namespaces[$methodNamespace])) {
                $SOAP_namespaces[$methodNamespace] = 'ns' . (count($SOAP_namespaces) + 1);
            }

            $this->prefix = $SOAP_namespaces[$methodNamespace];
        }
        
        // get type prefix
        if (strpos($type , ':') !== false) {
            $qname = new QName($type);
            $this->type = $qname->name;
            $this->type_prefix = $qname->ns;
            
        } elseif ($type_namespace) {
        
            if (!isset($SOAP_namespaces[$type_namespace])) {
                $SOAP_namespaces[$type_namespace] = 'ns'.(count($SOAP_namespaces)+1);
            }
            $this->type_prefix = $SOAP_namespaces[$type_namespace];
            
        // if type namespace was not explicitly passed, and we're not in a method struct:
        } elseif (!$this->type_prefix && $type != 'Struct' /*!isset($type_namespace)*/) {
        
            // try to get type prefix from typeMap
            if ($ns = $this->verifyType($this->type)) {
                $this->type_prefix = $SOAP_namespaces[$ns];
            } else if ($methodNamespace) {
                // else default to method namespace
                $this->type_prefix = $SOAP_namespaces[$methodNamespace];
            }
        }
        
        if (in_array($this->type, $SOAP_typemap[SOAP_XML_SCHEMA_VERSION])) {
            // scalar

            $this->type_code = SOAP_VALUE_SCALAR;
            $this->addScalar($value, $this->type, $name);

        } elseif (strcasecmp('Array', $this->type) == 0 || strcasecmp('ur-type', $this->type) == 0) {
            // array
            
            $this->type_code = SOAP_VALUE_ARRAY;
            $this->addArray($value);
            
        } elseif (stristr($this->type, 'Struct')) {
            // struct
            
            $this->type_code = SOAP_VALUE_STRUCT;
            $this->addStruct($value);
            
        } elseif (is_array($value)) {
        
            $this->type_code = SOAP_VALUE_STRUCT;
            $this->addStruct($value);
            
        } else {
        
            $this->type_code = SOAP_VALUE_SCALAR;
            $this->addScalar($value, 'string', $name);
            
        }
    }
    
    /**
    *
    *
    * @param    
    * @param    
    * @param
    * @return   boolean
    */
    function addScalar($value, $type, $name = '')
    {
        $this->debug("adding scalar '$name' of type '$type'");
        
        $this->value = $value;
        return true;
    }
    
    /**
    * 
    * @param    array
    * @return   boolean
    */
    function addArray($vals)
    {
        
        $this->debug("adding array '$this->name' with ".count($vals).' vals');
        $this->value = array();
        
        if (is_array($vals) && count($vals) >= 1) {
            foreach ($vals as $k => $v) {
                $this->debug("checking value $k : $v");

                // if SOAP_Value, add..
                if (strcasecmp(get_class($v), 'SOAP_Value' ) == 0) {
                    $this->value[] = $v;
                    $this->debug($v->debug_data);
                // else make obj and serialize
                } else {
                    #$type = $this->arrayType;
                    #$type = $this->_getSoapType($v, $type);
                    $new_val =  new SOAP_Value('item', $this->arrayType, $v);
                    $this->debug($new_val->debug_data);
                    $this->value[] = $new_val;
                }
            }
        }
        return true;
    }

    /**
    *
    * @param   array
    * @param    boolean
    */
    function addStruct($vals)
    {
        $this->debug("adding struct '$this->name' with " . count($vals) . ' vals');
        if (is_array($vals) && count($vals) >= 1) {
            foreach ($vals as $k => $v) {
                // if serialize, if SOAP_Value
                if (strcasecmp(get_class($v), 'SOAP_Value') == 0) {
                    $this->value[] = $v;
                    $this->debug($v->debug_data);
                // else make obj and serialize
                } else {
                    $type = NULL;
                    $type = $this->_getSoapType($v, $type);
                    $new_val = new SOAP_Value($k, $type, $v);
                    $this->debug($new_val->debug_data);
                    $this->value[] = $new_val;
                }
            }
        } else {
            $this->value = array();
        }
        return true;
    }
    
    /**
    *
    * @param    
    * @param    
    * @param    
    * @param    
    * @return   int
    */
    function _getArrayType(&$value, &$type, &$size, &$xml)
    {
        foreach ($value as $array_val) {
            $array_types[$array_val->type] = 1;
            #$xml .= $this->serializeval($array_val);
        }

        if ($array_val->type_prefix) {
            $type = $array_val->type_prefix . ':' . $array_val->type;
        } else {
            $type = $array_val->type;
        }

        $sz = count($value);

        if (count($array_types) == 1) {
            if (array_key_exists('Array', $array_types)) {
                // seems we have a multi dimensional array, figure it out if we do
                foreach ($value as $array_val) {
                    $numtypes = $this->_getArrayType($array_val->value, $type, $size, $xml);
                    if ($numtypes > 1) 
                        return $numtypes;
                }

                if ($sz) {
                    $size = $sz.','.$size;
                } else {
                    $size = $sz;
                }
                return 1;
            } else {
                foreach ($value as $array_val) {
                    #$array_types[$array_val->type] = 1;
                    $xml .= $this->serializeval($array_val);
                }
            }
        }
        $size = $sz;
        return count($array_types);
    }
    
    /**
    * Turn SOAP_Value's into xml, woohoo!
    * 
    * @param    mixed
    * @return   string  xml representation
    */
    function serializeval($soapval = false)
    {
        if (!$soapval) {
            $soapval = $this;
        }

        $this->debug("serializing '$soapval->name' of type '$soapval->type'");

        if (is_int($soapval->name)) {
            $soapval->name = 'item';
        }
        
        $xml = '';

        switch ($soapval->type_code) {
        case SOAP_VALUE_STRUCT:
            // struct
            $this->debug('got a struct');

            # XXX we should be able to do this, but ASP.Net fails if we do
            #if ($soapval->prefix && $soapval->type_prefix) {
            #    $xml .= "<$soapval->prefix:$soapval->name xsi:type=\"$soapval->type_prefix:$soapval->type\">\n";
            #} else
            if ($soapval->type_prefix) {
                $xml .= "<$soapval->name xsi:type=\"$soapval->type_prefix:$soapval->type\">\n";
            } elseif ($soapval->prefix) {
                $xml .= "<$soapval->prefix:$soapval->name>\n";
            } else {
                $xml .= "<$soapval->name>\n";
            }
            if (is_array($soapval->value)) {
                foreach ($soapval->value as $k => $v) {
                    $xml .= $this->serializeval($v);
                }
            }
            if ($soapval->type_prefix) {
                $xml .= "</$soapval->name>\n";
            } else if ($soapval->prefix) {
                $xml .= "</$soapval->prefix:$soapval->name>\n";
            } else {
                $xml .= "</$soapval->name>\n";
            }
            break;
            
        case SOAP_VALUE_ARRAY:
            // array
            $offset = '';

            // XXX this will be slow on larger array's.  We can probably move this
            // out to an external helper function.  Basicly, it flattens array's to allow us
            // to serialize multi-dimensional array's
            $numtypes = $this->_getArrayType($soapval->value, $array_type, $ar_size, $xml);
            #$numtypes = 0;
            $array_type_prefix = '';
            if ($numtypes != 1) {
                $xml ='';
                foreach ($soapval->value as $array_val) {
                    $array_types[$array_val->type] = 1;
                    $xml .= $this->serializeval($array_val);
                }

                $ar_size = count($soapval->value);
                $numtypes = count($array_types);
                $array_type = $array_val->type;
                $array_type_prefix = $array_val->type_prefix;
                $offset = " SOAP-ENC:offset=\"[0]\"";
            }
            if ($numtypes > 1) {
                $array_type = 'xsd:ur-type';
            } elseif ($numtypes == 1) {
                if ($array_type_prefix != '') {
                    $array_type = $array_type_prefix . ':' . $array_type;
                } elseif ($array_type_prefix = $this->getPrefix($array_type)) {
                    $array_type = $array_type_prefix . ':' . $array_type;
                }
            }
            
            $xml = "<$soapval->name xsi:type=\"SOAP-ENC:Array\" SOAP-ENC:arrayType=\"".$array_type."[$ar_size]\"$offset>\n".$xml."</$soapval->name>\n";
            break;
            
        case SOAP_VALUE_SCALAR:
            # XXX we should be able to do this, but ASP.Net fails if we do
            #if ($soapval->prefix && $soapval->type_prefix) {
            #    $xml .= "<$soapval->prefix:$soapval->name xsi:type=\"$soapval->type_prefix:$soapval->type\">$soapval->value</$soapval->prefix:$soapval->name>\n";
            #} else
            if ($soapval->type_prefix) {
                $xml .= "<$soapval->name xsi:type=\"$soapval->type_prefix:$soapval->type\">$soapval->value</$soapval->name>\n";
            # XXX we should be able to do this, but ASP.Net fails if we do
            #} elseif ($soapval->prefix) {
            #    $xml .= "<$soapval->prefix:$soapval->name>$soapval->value</$soapval->prefix:$soapval->name>\n";
            } else
            if ($soapval->type) {
                $xml .= "<$soapval->name xsi:type=\"$soapval->type\">$soapval->value</$soapval->name>\n";
            } else {
                $xml .= "<$soapval->name>$soapval->value</$soapval->name>\n";
            }
            break;
        default:
            break;
        }
        return $xml;
    }
    
    /**
    * Serialize
    * 
    * @return   string  xml representation
    */
    function serialize()
    {
        return $this->serializeval($this);
    }
    
    /**
    *
    * @param    mixed
    * @global   $SOAP_typemap
    */
    function decode($soapval = false)
    {
        global $SOAP_typemap;
        
        if (!$soapval) {
            $soapval = $this;
        }
        
        $this->debug("inside SOAP_Value->decode for $soapval->name of type $soapval->type and value: $soapval->value");
        // scalar decode
        if ($soapval->type_code == SOAP_VALUE_SCALAR) {
            if ($soapval->type == 'boolean') {
                #echo strcasecmp($soapval->value,'false');
                if ($soapval->value != '0' && strcasecmp($soapval->value,'false') !=0) {
                    $soapval->value = TRUE;
                } else {
                    $soapval->value = FALSE;
                }
            #} else if ($soapval->type == 'dateTime') {
            #    # we don't realy know what a user want's in return,
            #    # but we'll just do unix time stamps for now
            #    # THOUGHT: we could return a class instead.
            #    $dt = new SOAP_Type_dateTime($soapval->value);
            #    $soapval->value = $dt->toUnixtime();
            } else if (in_array($soapval->type, array_keys($SOAP_typemap[SOAP_XML_SCHEMA_VERSION]), TRUE)) {
                # if we can, lets set php's variable type
                settype($soapval->value, $SOAP_typemap[SOAP_XML_SCHEMA_VERSION][$soapval->type]);
            }
            #print "value: $soapval->value type: $soapval->type phptype: {$SOAP_typemap[SOAP_XML_SCHEMA_VERSION][$soapval->type]}\n";
            return $soapval->value;
        // array decode
        } elseif ($soapval->type_code == SOAP_VALUE_ARRAY) {
            if (is_array($soapval->value)) {
                foreach ($soapval->value as $item) {
                    $return[] = $this->decode($item);
                }
                return $return;
            }
            return $soapval->value;
        // struct decode
        } elseif ($soapval->type_code == SOAP_VALUE_STRUCT) {
            if (is_array($soapval->value)) {
                $counter = 1;
                foreach ($soapval->value as $item) {
                    if (isset($return[$item->name])) {
                        if (!is_array($return[$item->name])) {
                            $val = $return[$item->name];
                            $return[$item->name] = array();
                            $return[$item->name][] = $val;
                        }
                        $return[$item->name][] = $this->decode($item);
                    } else {
                        $return[$item->name] = $this->decode($item);
                    }
                }
                return $return;
            }
            return $soapval->value;
        }
        # couldn't decode, return a fault!
        return $this->raiseSoapFault("couldn't decode response, invalid type_code");
    }
    
    /**
    * pass it a type, and it attempts to return a namespace uri
    *
    * @param    
    * @global   $SOAP_typemap, $SOAP_namespaces
    */
    function verifyType($type)
    {
        global $SOAP_typemap, $SOAP_namespaces;
        /*foreach ($SOAP_typemap as $namespace => $types) {
            if (is_array($types) && in_array($type,$types)) {
                return $namespace;
            }
        }*/
        #if ($this->wsdl && array_key_exists($type, $this->wsdl->complexTypes)) {
        #    # XXX should return the import uri if the complex type was imported
        #    return $this->wsdl->uri;
        #}
        foreach ($SOAP_namespaces as $uri => $prefix) {
            if (is_array($SOAP_typemap[$uri]) && isset($SOAP_typemap[$uri][$type])) {
                #print "returning: $uri for type $type\n";
                return $uri;
            }
            #print "$type not in: $uri\n";
        }
        #print "$type not found\n";
        return false;
    }
    
    /** 
    * alias for verifyType() - pass it a type, and it returns it's prefix
    *
    * @brother  varityType()
    */
    function getPrefix($type)
    {
        global $SOAP_namespaces;
        if ($uri = $this->verifyType($type)) {
            return $SOAP_namespaces[$uri];
        }
        return NULL;
    }
    
    
    /**
    * SOAP::Value::_getSoapType
    *
    * convert php type to soap type
    * @param    string  value
    * @param    string  type  - presumed php type
    *
    * @return   string  type  - soap type
    * @access   private
    */
    function _getSoapType(&$value, &$type) {
    
        $doconvert = FALSE;
        if (0 && $this->wsdl) {
            # see if it's a complex type so we can deal properly with SOAPENC:arrayType
            if (!$type && $this->name) {
                # XXX TODO:
                # look up the name in the wsdl and validate the type
                $this->debug("SOAP_VALUE no type for $this->name!");
            } else if ($type) {
                # XXX TODO:
                # this code currently handles only one way of encoding array types in wsdl
                # need to do a generalized function to figure out complex types
                if (array_key_exists($type, $this->wsdl->complexTypes)) {
                    if ($this->arrayType = $this->wsdl->complexTypes[$type]['arrayType']) {
                        $type = 'Array';
                    } else if ($this->wsdl->complexTypes[$type]['order']=='sequence' &&
                               array_key_exists('elements', $this->wsdl->complexTypes[$type])) {
                        reset($this->wsdl->complexTypes[$type]['elements']);
                        # assume an array
                        if (count($this->wsdl->complexTypes[$type]['elements']) == 1) {
                            $arg = current($this->wsdl->complexTypes[$type]['elements']);
                            $this->arrayType = $arg['type'];
                            $type = 'Array';
                        } else {
                            foreach($this->wsdl->complexTypes[$type]['elements'] as $element) {
                                if ($element['name'] == $type) {
                                    $this->arrayType = $element['type'];
                                    $type = $element['type'];
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!$type || !$this->verifyType($type)) {
            if ($type && $this->wsdl && array_key_exists($type, $this->wsdl->complexTypes)) {
                # do nothing, this preserves our complex types 
            } else
            if (is_object($value)) {
                # allows for creating special classes to handle soap types
                $type = get_class($value);
                # this may return a different type that we process below
                $value = $value->toSOAP();
            } elseif (isArray($value)) {
                $type = isHash($value)?'Struct':'Array';
            } elseif (isInt($value)) {
                $type = 'int';
            } elseif (isFloat($value)) {
                $type = 'float';
            } elseif (SOAP_Type_hexBinary::is_hexbin($value)) {
                $type = 'hexBinary';
            } elseif (isBase64($value)) {
                $type = 'base64Binary';
            } elseif (isBoolean($value)) {
                $type = 'boolean';
            } else {
                $type = gettype($value);
                # php defaults a lot of stuff to string, if we have no
                # idea what the type realy is, we have to try to figure it out
                # this is the best we can do if the user did not use the SOAP_Value class
                if ($type == 'string') $doconvert = TRUE;
            }
        }
        # we have the type, handle any value munging we need
        if ($doconvert) {
            $dt = new SOAP_Type_dateTime($value);
            if ($dt->toUnixtime() != -1) {
                $type = 'dateTime';
                $value = $dt->toSOAP();
            }
        } else
        if ($type == 'dateTime') {
            # encode a dateTime to ISOE
            $dt = new SOAP_Type_dateTime($value);
            $value = $dt->toSOAP();
        } else
        // php type name mangle
        if ($type == 'integer') {
            $type = 'int';
        } else
        if ($type == 'boolean') {
            if (($value != 0 && $value != '0') || strcasecmp($value, 'true') == 0) 
                $value = 'true';
            else 
                $value = 'false';
        }
        return $type;
    }

}

// support functions
/**
*
* @param    string
* @return   string
*/
function isBase64(&$value)
{
    return $value[strlen($value)-1]=='=' && preg_match("/[A-Za-z=\/\+]+/",$value);
}

/**
*
* @param    mixed
* @return   boolean
*/
function isBoolean(&$value)
{
    return gettype($value) == 'boolean' || strcasecmp($value, 'true')==0 || strcasecmp($value, 'false') == 0;
}

/**
* 
* @param    mixed
* @return   boolean
*/
function isFloat(&$value)
{
    return gettype($value) == FLOAT ||
                $value === 'NaN' ||  $value === 'INF' || $value === '-INF' ||
                (is_numeric($value) && strstr($value, '.'));
}

/**
* 
* @param    mixed
* @return   boolean
*/
function isInt(&$value)
{
    return gettype($value) == 'integer' || (is_numeric($value) && !strstr($value,'.'));
}

/**
*
* @param    array
* @return   boolean
*/
function isArray(&$value)
{
    return is_array($value) && count($value) >= 1;
}

/**
*
* @param    mixed
* @return   boolean
*/
function isDateTime(&$value)
{
    $dt = new SOAP_Type_dateTime($value);
    return $dt->toUnixtime() != -1;
}

/**
*
* @param    mixed
* @return   boolean
*/
function isHash(&$a) {
    # XXX I realy dislike having to loop through this in php code,
    # realy large arrays will be slow.  We need a C function to do this.
    $names = array();
    foreach ($a as $k => $v) {
        # checking the type is faster than regexp.
        if (gettype($k) != 'integer') {
            return TRUE;
        } else if (gettype($v) == 'object' && get_class($v) == 'soap_value') {
            $names[$v->name] = 1;
        }
    }
    return count($names)>1;
}

?>