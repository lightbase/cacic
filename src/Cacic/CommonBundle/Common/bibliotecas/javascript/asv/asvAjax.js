/**
 * A sample AJAX technologie implementation
 * @copyright Copyright (C) 2007 Adriano dos Santos Vieira. All rights reserved.
 * @author Adriano dos Santos Vieira <harpiain at gmail.com>
 * @version 0.0.1
 * @license GNU/GPL
 * @description A sample AJAX resource
 *  
 */ 

/**
 * @acess private
 */
var url;

/**
 * @acess private
 */
var UrlVars;

/**
 * @acess private
 */
var responseFieldID;
	
/**
 * @acess private
 */
var dataRequested = '';


/**
 * @acess private
 */
var oXMLHttp  = false;

/**
 * @acess private
 */
var method = 'GET';

/**
 * @acess private
 */
var charset = 'UTF-8';

/**
 * @acess private
 */
var error = false;

/**
 * @acess private
 */
var errorNbr = 0;

/**
 * @acess private
 */
var errorMsg = "" ;

function asvAjaxInit( url, optionID, itemID, taskID ) {
	this.url = url;
	this.oXMLHttp  = xmlHttpNavResource();
	this.UrlVars = "option="+optionID+"&Itemid="+itemID+"&task="+taskID;
	this.asvAjaxSetPOSTMethod();
	
}	

function asvAjaxSetGETMethod() {
	this.method = 'GET';
}

function asvAjaxSetPOSTMethod() {
	this.method = 'POST';
}

function asvAjaxSetCharset( charset ) {
	if( charset != null )
		this.charset = charset;
}

function asvAjaxGetFieldData( fieldName, responseFieldID )
{
	var field = document.getElementsByName(fieldName);
	if(field.length != 0) {
		fieldData = field[0];
		if(fieldData.value != '')
			if(fieldData.name != '')
				this.dataRequested = "&" + fieldData.name +"="+fieldData.value;
			else
				this.dataRequested = "&" + fieldData.id +"="+fieldData.value;
				
		this.responseFieldID = responseFieldID;
		requestXMLDoc();
	}
	else {
		error = true;
		errorNbr = 99;
        errorMsg = "Ajax request message error: (status) " + "Field named as '"+fieldName+"' doesn't exist!";
	}
}

function asvAjaxGetFormData( formName, responseFieldID, fieldIdActive )
{
	var form = document.getElementsByName(formName);
	// Mostra a mensagem "carregando..." antes de mostrar os dados
	document.getElementById(responseFieldID).innerHTML = "<span class='buildingAjax'>Carregando...</span>";
	if(form.length != 0) {
		var frmData = form[0].elements;
		this.dataRequested = '';
		for(var i=0;i<frmData.length;i++){
		    if(frmData[i].type != 'submit' && frmData[i].type != 'reset' ) {
		    	if(frmData[i].value != '')
			    	if(frmData[i].name != '')
						this.dataRequested += "&" + frmData[i].name + "=" +frmData[i].value;
					else
						this.dataRequested += "&" + frmData[i].id + "=" +frmData[i].value;
			}
			else if(fieldIdActive == frmData[i].id) {
			    	if(frmData[i].value != '')
				    	if(frmData[i].name != '')
							this.dataRequested += "&" + frmData[i].name +"="+frmData[i].value;
				    	else
							this.dataRequested += "&" + frmData[i].id +"="+frmData[i].value;
			}
		}
		
		this.responseFieldID = responseFieldID;
		requestXMLDoc();
	}
	else {
		error = true;
		errorNbr = 99;
        errorMsg = "Ajax request message error: (status) " + "Form named as '"+formName+"' doesn't exist!";
	}
}

function asvAjaxError()
{
	return error;
}

function asvAjaxErrorNumber()
{
	return errorNbr;
}

function asvAjaxErrorMsg()
{
	return errorMsg;
}

/**
 * @acess private
 */
function xmlHttpNavResource()
{
    var xmlhttp = false;
    try
    {
        // try IE
        xmlhttp =new ActiveXObject("Msxml2.XMLHTTP")
    }
    catch(e){
        try{
            // try IE again
            xmlhttp =new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch(sc){
                xmlhttp = false;
            }
        }
        if(!xmlhttp &&typeof XMLHttpRequest!="undefined")
        {
            // else a better browser ;-)
            xmlhttp =new XMLHttpRequest()
        }
        return xmlhttp 
    }


/**
 * @acess private
 */
function requestXMLDoc()
{ 
	urlRequest =  this.url+"?"+this.UrlVars + this.dataRequested ;
	if(! oXMLHttp)
		return false;
	
	if( method == 'GET') {
        oXMLHttp.open("GET", urlRequest, true);
        oXMLHttp.onreadystatechange = processReqChanges;
        oXMLHttp.send(null);
    }	
	else if(  method == 'POST') {
        oXMLHttp.open("POST", this.url);
		oXMLHttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset='+this.charset);
        oXMLHttp.onreadystatechange = processReqChanges;
        oXMLHttp.send( this.UrlVars + this.dataRequested );
	}	
}

/**
 * @acess private
 */
function processReqChanges()
{
    // apenas quando o estado for "completado"
    if (oXMLHttp.readyState == 4) {
        // apenas se o servidor retornar "OK"
        if (oXMLHttp.status == 200) {
            // procura pela div id="responseFieldID" e insere o conteudo
            // retornando nela, como texto HTML
            document.getElementById(responseFieldID).innerHTML = oXMLHttp.responseText;
        } else {
			error = true;
			errorNbr = oXMLHttp.status;
            errorMsg = "Ajax request message error: (status)" + oXMLHttp.statusText;
        }
    } else {
			error = true;
			errorNbr = oXMLHttp.readyState;
            errorMsg = "Ajax request message error: (readyState)" + oXMLHttp.readyState;
        }
}

//window.status = 'A Sample AJAX technologie implementation!';
