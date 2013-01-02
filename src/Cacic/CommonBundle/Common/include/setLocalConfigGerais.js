function setLocal(objSelect)
	{
	var strAux  = '';	
	var idLocal = objSelect.options[objSelect.options.selectedIndex].id;
	var contaElementosListaHardwareDisponiveis   = 0;
	var contaElementosListaHardwareSelecionados  = 0;	
	var contaElementosListaUSBSelecionados  = 0;		
	var intIndice = 0;
	
	SelectAll(document.all.listaHardwareSelecionados);
	SelectAll(document.all.listaExibeGraficosSelecionados);	

	move(document.all.listaHardwareSelecionados,document.all.listaHardwareDisponiveis);
	move(document.all.listaExibeGraficosSelecionados,document.all.listaExibeGraficosDisponiveis);

	document.forma.frm_id_local.value = idLocal;			
	for (j=0; j < document.all.SELECTconfiguracoes_locais.options.length; j++)
		{
		if (document.all.SELECTconfiguracoes_locais.options[j].id == idLocal)
			{				
			arrValoresConfiguracoesLocais 						= (document.all.SELECTconfiguracoes_locais.options[j].value).split('#FD#');
			document.forma.nm_organizacao.value 				= arrValoresConfiguracoesLocais[0];			
			document.forma.te_notificar_mudanca_hardware.value 	= arrValoresConfiguracoesLocais[1];				
			document.forma.te_notificar_utilizacao_usb.value 	= arrValoresConfiguracoesLocais[5];	

			// Tratar o ítem 2 aqui...
			if (arrValoresConfiguracoesLocais[2] != '')
				{
				for (m=0; m < document.all.listaExibeGraficosDisponiveis.options.length; m++)			
					{
					if ((arrValoresConfiguracoesLocais[2]).indexOf(document.all.listaExibeGraficosDisponiveis.options[m].value) != -1)					
						{
						strAux = document.all.listaExibeGraficosDisponiveis.options[m].value;
						for (n=0; n < document.all.listaExibeGraficosDisponiveis.options.length; n++)								
							{
							if (document.all.listaExibeGraficosDisponiveis.options[n].value == strAux)
								{
								document.all.listaExibeGraficosDisponiveis.options[n].selected = true;
								move(document.all.listaExibeGraficosDisponiveis,document.all.listaExibeGraficosSelecionados);
								}
							else
								document.all.listaExibeGraficosDisponiveis.options[n].selected = false;																					
							}
						
						}
					}

				}
			document.forma.frm_te_serv_cacic_padrao.value 		= arrValoresConfiguracoesLocais[3];									
			document.forma.frm_te_serv_updates_padrao.value 	= arrValoresConfiguracoesLocais[4];			
			
			for (k=0; k < document.all.SELECTdescricao_hardware.options.length; k++)			
				{
				// Pesquiso o id_local nos valores de id_local separados por vírgula
				// Ex.: SELECTdescricao_hardware.id    => qt_mem_ram
				//      SELECTdescricao_hardware.value => 20,5,7,13
				//      SELECTdescricao_hardware.text  => Memória RAM				
				
				if ((','+((document.all.SELECTdescricao_hardware.options[k].value).replace(' ',''))+',').indexOf(','+idLocal+',') != -1)
					{		
					for (l=0; l < document.all.listaHardwareDisponiveis.options.length; l++)								
						{
						if (document.all.listaHardwareDisponiveis.options[l].value == document.all.SELECTdescricao_hardware.options[k].id)
							{
							document.all.listaHardwareDisponiveis.options[l].selected = true;
							move(document.all.listaHardwareDisponiveis,document.all.listaHardwareSelecionados);
							}
						else
							document.all.listaHardwareDisponiveis.options[l].selected = false;												
						}
					}
				}

				document.all.listaUSBselecionados.options.length = 0;
				document.all.listaUSBdisponiveis.options.length  = 0;	

				arrTeFiltroUSB	= arrValoresConfiguracoesLocais[6].split('#');														

				strAux = '';
				var boolSeleciona = false;
 							
				for (k=0; k < document.all.listaUSBDevicesUtilizados.options.length; k++)
					{
					// Pesquiso o id_local nos valores de id_local separados por vírgula
					if ((document.all.listaUSBDevicesUtilizados.options[k].value).indexOf('#'+idLocal+'#') != -1)
						{
						boolSeleciona = false;						

						for (m=0; m < arrTeFiltroUSB.length; m++)							
							if ((strAux.indexOf('#'+arrTeFiltroUSB[m]) == -1) && (arrTeFiltroUSB[m] == (document.all.listaUSBDevicesUtilizados.options[k].value).replace('#'+idLocal+'#','')))
								boolSeleciona = true;

						var NewOption   = new Option();									
						NewOption.value = (document.all.listaUSBDevicesUtilizados.options[k].value).replace('#'+idLocal+'#','');
						NewOption.text  = document.all.listaUSBDevicesUtilizados.options[k].text;

						strAux = strAux + '#' + NewOption.value;														
						if (boolSeleciona)
							document.all.listaUSBselecionados.options[document.all.listaUSBselecionados.options.length] = NewOption;
						else
							document.all.listaUSBdisponiveis.options[document.all.listaUSBdisponiveis.options.length] = NewOption;							
						}
					}
			}		
		}

	return true;

	}