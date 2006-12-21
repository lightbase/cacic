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