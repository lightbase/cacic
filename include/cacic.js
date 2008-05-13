
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
		for (j=0;j<window.document.forms[i].elements.length;j++)
			if (window.document.forms[i].elements[j].name == p_campo) window.document.forms[i].elements[j].focus();

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
		return true;

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
		document.all[strLayerName].style.visibility = "hidden";
	else if (ns4) 
		document.layers[strLayerName].visibility = "hide";
	else if (ns6) 
		document.getElementById([strLayerName]).style.display = "none";
	}

function EscreveNaLayer(strLayerName,strText) 
	{
	if      (ie4) 
		document.all[strLayerName].innerHTML = strText;
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
			over.removeChild(over.lastChild);

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
      		if (sDado.charAt(i) != " ") 
				break;

		if (i < sDado.length)
			{
      		for (f=sDado.length-1; f >= 0; f --)
        		if (sDado.charAt(f) != " ") 
					break;

      		Result = sDado.substring(i, f+1);
    		}
  		}
  	return Result;
	}	
function VerRedeMascara(strFormName,boolPreencheIPs,boolConfirma) 
	{
	alert('1');
	var frmForm 		 = document.getElementById(strFormName);
	var arrIdIpRede      = (frmForm.frm_id_ip_rede.value).split('.');
	var arrTeMascaraRede = (frmForm.frm_te_mascara_rede.value).split('.');
	
  	var intTesteIdIpRede = TestaIP(arrIdIpRede[0],arrIdIpRede[1],arrIdIpRede[2],arrIdIpRede[3]);  	
	if (intTesteIdIpRede > 0) 
		{
		alert("Endereço de Subrede Inválido!"); 
		frmForm.frm_id_ip_rede.select();			
		frmForm.frm_id_ip_rede.focus();		
		return false; 
		}
		
  	var intTesteTeMascaraRede = TestaMascara(arrTeMascaraRede[0],arrTeMascaraRede[1],arrTeMascaraRede[2],arrTeMascaraRede[3]);  		
  	if (intTesteTeMascaraRede > 0) 
		{ 
		alert("Máscara de Subrede Inválida!" ); 
		frmForm.frm_te_mascara_rede.select();			
		frmForm.frm_te_mascara_rede.focus();		
		return false; 
		}

	
  	// Cálculo do endereço de rede
  	var strOctetoRede1		=	arrIdIpRede[0] & arrTeMascaraRede[0];
  	var strOctetoRede2		=	arrIdIpRede[1] & arrTeMascaraRede[1];
  	var strOctetoRede3		=	arrIdIpRede[2] & arrTeMascaraRede[2];
  	var strOctetoRede4		=  (arrIdIpRede[3] & arrTeMascaraRede[3])+1;
	
  	// Cálculo do endereço de broadcast
  	var strOctetoMascara1	=	arrIdIpRede[0] | (arrTeMascaraRede[0] ^ 255);
  	var strOctetoMascara2	=	arrIdIpRede[1] | (arrTeMascaraRede[1] ^ 255);
  	var strOctetoMascara3	=	arrIdIpRede[2] | (arrTeMascaraRede[2] ^ 255);
  	var strOctetoMascara4	=  (arrIdIpRede[3] | (arrTeMascaraRede[3] ^ 255))-1;
	
	var strIPInicio      = strOctetoRede1    + '.' + strOctetoRede2    + '.' + strOctetoRede3    + '.' + strOctetoRede4;
	var strIPFim         = strOctetoMascara1 + '.' + strOctetoMascara2 + '.' + strOctetoMascara3 + '.' + strOctetoMascara4;

	if (boolPreencheIPs)
		PreencheIPs(strFormName,strIPInicio,strIPFim);

	if (boolConfirma && Confirma('ATENÇÃO:\n\nCom esta máscara, esta subrede atenderá à faixa "'+ strIPInicio+'" a "'+strIPFim+'"\n\n\nConfirma?'))
		{
		PreencheIPs(strFormName,strIPInicio,strIPFim);			
		return true;
		}
	else
		{
		if (!boolPreencheIPs)
			{
			frmForm.frm_te_mascara_rede.select();			
			frmForm.frm_te_mascara_rede.focus();
			}
		return false;
		}
	}

function PreencheIPs(strFormName,strIPInicio,strIPFim)
	{
	alert('Recebí "'+strFormName+'"');
	var frmForm 		 = document.getElementById(strFormName);		
	frmForm.frm_id_ip_inicio.value 	= strIPInicio;
	frmForm.frm_id_ip_fim.value 	= strIPFim;
	}
	
function TestaIP(IP1, IP2, IP3, IP4) 
	{
  	if ((IP1 > 255) || (IP1 < 1))
		return 1;

	if ((IP2 > 255) || (IP2 < 0))
		return 2;

	if ((IP3 > 255) || (IP3 < 0))
		return 3;

	if ((IP4 > 255) || (IP4 < 0))
		return 4;
		
  	return 0;
	}
	
function TestaMascara(IP1, IP2, IP3, IP4) 
	{
  	if ((IP1 > 255) || (IP1 < 0)) 
		return 1;
		
  	if ((IP2 > 255) || (IP2 < 0))
		return 2;
		
	if ((IP3 > 255) || (IP3 < 0)) 
		return 3;
		
  	if ((IP4 > 255) || (IP4 < 0))
		return 4;
		
  	var IPX =5;

	// Determine where IP changes
  	if (IP1 < 255) 
		{
    	if((IP2 > 0) || (IP3 > 0) || (IP4 > 0))
			return 5;
	    IPX = IP1;
    	} 
	else 
		{
      	if (IP2 < 255) 
			{
        	if((IP3 > 0) || (IP4 > 0))
				return 6;
        	IPX = IP2;
      		} 
		else 
			{
        	if (IP3 < 255) 
				{
          		if ((IP4 > 0))
					return 7;
	  			IPX = IP3;
        		} 
			else
				IPX = IP4;
      		}
    	}

	// Valida o IPX
    switch (IPX) 
		{
      	case "255":
      	case "128":
      	case "192":
      	case "224":
      	case "240":
      	case "248":
      	case "252":
      	case "254":
      	case "0":
        return 0;
      default:
        return 8;
    	} 
	return 0;
  }	

