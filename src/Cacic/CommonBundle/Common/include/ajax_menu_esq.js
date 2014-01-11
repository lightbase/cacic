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
                  	alert("Problemas criando objeto XMLHttpRequest: "+ eee.description);
              		}
          		}
      		}
    	}

    /* Envia */
    function fEnvia(_strEnvio)
		{
      	fCriaXMLHttp();
      	XMLHttp.onreadystatechange = fStateChange;
      	XMLHttp.open("GET","ajax2.php?strFrase="+_strEnvio,true);
      	XMLHttp.send(null);
    	}
		
    /* ???*/
    function fStateChange()
		{
      	if (XMLHttp.readyState == 4)
	  		{
        	if (XMLHttp.status == 200) 
				{
          		/* Algum código interessante aqui... */
           		document.formTeste.strFrase.focus();
           		document.formTeste.strFraseRecebida.value = XMLHttp.responseText	;
        		}
        	else 
				{
          		alert("Problemas recuperando os dados")
        		}
      		}
    	}
