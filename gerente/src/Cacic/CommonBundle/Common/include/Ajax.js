var XMLHttp;
/* Cria o objeto XMLHttpRequest */
function fCriaXMLHttp()
	{
  	try 
		{
		XMLHttp = new XMLHttpRequest(); /* Especificação W3C */
  		} 
	catch(e) 
		{
	  	try 
			{
			XMLHttp = new ActiveXObject("Msxml2.XMLHTTP"); /* I.E. 6.x */
	  		} 
		catch(ee) 
			{
		  	try 
				{
				XMLHttp = new ActiveXObject("Microsoft.XMLHTTP"); /* I.E. 5.x */
		  		} 
			catch(eee) 
				{
			  	alert("Problemas criando objeto XMLHttpRequest: " + eee.description);
		  		}
	  		}
  		}
	}

/* Envia os argumentos */
function fAjaxEnviaArgumentos(pScript, pArgumentos)
	{
  	fCriaXMLHttp();
  	XMLHttp.onreadystatechange = fStateChange;
  	XMLHttp.open("GET","ajax2.php?pArgumentos="+pArgumentos,true);
  	XMLHttp.send(null);
	}

/* seila */
function fStateChange()
	{
  	if (XMLHttp.readyState == 4) 
		{
		if (XMLHttp.status == 200) 
			{
			var retorno = XMLHttp.responseText;
			var vetor1  = retorno.split(";");
			for (var i=0; i < vetor1.length; i ++)
				{
				alert(vetor1[i]);
				}
			document.formTeste.strFrase.focus();
			}
		else 
			{
	  		alert("Problemas recuperando os dados")
			}
  		}
	}