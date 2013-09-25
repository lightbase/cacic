<SCRIPT LANGUAGE="JavaScript">
function MarcaDesmarcaTodos(field) 
	{
	var v_valor = '';
	if (field[0].checked == false)
		{
		v_valor = '.';
		}	
	
	for (i = 1; i < field.length; i++) 
		{
		if (field[i].type == 'checkbox')
			{
			field[i].checked = field[0].checked;			
			if (v_valor != '')
				{
				v_valor = field[i].value;
				}			
			}
		else
			{
			field[i].value = v_valor;
			}
		}
	return true;
	}

function SelectAll_Forca_Coleta() 
	{
	var formulario = window.document.forms[0];
	var v_field_name = '';
    for (var i = 0; i < formulario.elements.length; i++) 
		{	
		v_field_name = formulario[i].name;
		if (v_field_name.substring(0,4) == "col_" && formulario[i].type == "checkbox" && formulario[i].checked == true && formulario[i+1].type == "hidden") 
			{			
			formulario[i+1].value = formulario[i].value;
			formulario[i+1].name  = formulario[i].name + '#' + formulario[i].value;			
			}
      	}	
	return true;
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
	
function MarcaDesmarcaTodasAcoesRedes(p_campo)
	{
	var formulario = window.document.forms[0];
	var v_field_name = '';

    for (var i = 0; i < formulario.elements.length; i++) 
		{	
		v_field_name = formulario[i].name;
		if (v_field_name.substring(0,4) == "col_" && formulario[i].type == "checkbox") 
			{			
			formulario[i].checked = p_campo;
			}
      	}	
	return true;
	}
</script>
