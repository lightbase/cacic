
var ie4 = (document.all) ? true : false;
var ns4 = (document.layers) ? true : false;
var ns6 = (document.getElementById && !document.all) ? true : false;

function SetaClassDigitacao(obj)
	{
	obj.style.backgroundColor='#CCCCCC';
	}

function SetaClassNormal(obj)
	{	
	obj.style.backgroundColor='#FFFFFF';
	}
function preparaEnvio()
	{// Percorrerei os forms e encontrarei os campos NOME e SENHA para criptografia... (Anderson Peterle - 04/09/2006)
	for (i=0;i<window.document.forms.length;i++)
		for (j=0;j<window.document.forms[i].elements.length;j++)
			if ((window.document.forms[i].elements[j].name == 'frm_nm_usuario_acesso' ||
				 window.document.forms[i].elements[j].name == 'frm_te_senha') &&
				(window.document.forms[i].elements[j].type  == 'text' ||
				 window.document.forms[i].elements[j].type  == 'password') && 
				 window.document.forms[i].elements[j].value != '')
				// Codificação dos campos NOME e SENHA para submissão pelo método POST
				window.document.forms[i].elements[j].value = encode64(window.document.forms[i].elements[j].value);		
	}
	
function SetaCampo(p_campo)
	{
	for (i=0;i<window.document.forms.length;i++)
		{
		for (j=0;j<window.document.forms[i].elements.length;j++)
			{
			if (window.document.forms[i].elements[j].name == p_campo) window.document.forms[i].elements[j].focus();
			}
		}

	return true;
	}		


function Ajuda(p_campo_destino,p_frase) 
	{
	var formulario = window.document.forms[0];
	formulario[p_campo_destino].value = p_frase;
	return true;		
	}

function Confirma(p_pergunta) 
	{
	if (confirm(p_pergunta))
		{
		return true;
		}
	return false;
	}	

function MostraLayer(strLayerName) 
	{
	if      (ie4) 
		document.all[strLayerName].style.visibility = "visible";
	else if (ns4) 
		document.layers[strLayerName].visibility = "show";
	else if (ns6) 
		document.getElementById([strLayerName]).style.display = "block";
	}
	
function EscondeLayer(strLayerName)
	{
	if      (ie4) 
		{
		document.all[strLayerName].style.visibility = "hidden";
		}
	else if (ns4) 
		{
		document.layers[strLayerName].visibility = "hide";
		}
	else if (ns6) 
		{
		document.getElementById([strLayerName]).style.display = "none";
		}
	}

function EscreveNaLayer(strLayerName,strText) 
	{
	if      (ie4) 
		{
		document.all[strLayerName].innerHTML = strText;
		}
	else if (ns4) 
		{
		document[strLayerName].document.write(strText);
		document[strLayerName].document.close();
		}
	else if (ns6) 
		{
		over = document.getElementById([strLayerName]);
		range = document.createRange();
		range.setStartBefore(over);
		domfrag = range.createContextualFragment(strText);
		while (over.hasChildNodes()) 
			{
			over.removeChild(over.lastChild);
			}
		over.appendChild(domfrag);
	   }
	}
	
function Trim(Dado)
	{
  	var sDado, Result, i, f;

  	Result = "";
  	sDado = Dado.toString();
  	if (sDado.length > 0)
		{
    	for (i=0; i < sDado.length; i++)
			{
      		if (sDado.charAt(i) != " ") 
				break;
    		}
    	if (i < sDado.length)
			{
      		for (f=sDado.length-1; f >= 0; f --)
				{
        		if (sDado.charAt(f) != " ") 
					break;
      			}
      		Result = sDado.substring(i, f+1);
    		}
  		}
  	return Result;
	}	
