function setLocal(objSelect)
	{
	var myForm = document.forma;

	myForm.frm_id_local.value = objSelect.options[objSelect.options.selectedIndex].id;			
	for (j=0; j < document.all.SELECTconfiguracoes_locais.options.length; j++)
		{
		if (document.all.SELECTconfiguracoes_locais.options[j].id == objSelect.options[objSelect.options.selectedIndex].id)
			{				
			arrValoresConfiguracoesLocais 			   	= (document.all.SELECTconfiguracoes_locais.options[j].value).split('#');
			myForm.in_exibe_bandeja[0].checked 			= (arrValoresConfiguracoesLocais[0]=='S'?true:false);			
			myForm.in_exibe_bandeja[1].checked 			= (arrValoresConfiguracoesLocais[0]=='N'?true:false);						
			myForm.in_exibe_erros_criticos[0].checked 	= (arrValoresConfiguracoesLocais[1]=='S'?true:false);						
			myForm.in_exibe_erros_criticos[1].checked 	= (arrValoresConfiguracoesLocais[1]=='N'?true:false);									
			myForm.te_senha_adm_agente.value 			= arrValoresConfiguracoesLocais[2];				

			for (i=0;i < myForm.length;i++)
				if (myForm.elements[i].type == 'radio' && myForm.elements[i].name == 'nu_exec_apos' && myForm.elements[i].value == arrValoresConfiguracoesLocais[3])
					myForm.elements[i].checked = true;
					
			for (i=0;i < myForm.length;i++)
				if (myForm.elements[i].type == 'radio' && myForm.elements[i].name == 'nu_intervalo_exec' && myForm.elements[i].value == arrValoresConfiguracoesLocais[4])
					myForm.elements[i].checked = true;
			
			myForm.te_enderecos_mac_invalidos.value 	= arrValoresConfiguracoesLocais[5];			
			myForm.te_janelas_excecao.value 			= arrValoresConfiguracoesLocais[6];						
			}		
		}

	return true;

	}