$(function ()
	{
	$("#frm_btPesquisa").click(function () 
		{
		if ($("#frm_te_ip_rede").val().length > 0)
			{
			$("#frm_te_mascara_rede"						).empty();
			$("#frm_nm_rede"								).empty();														
			$("#frm_nm_local"								).empty();														
			$("#frm_nm_servidor_autenticacao"				).empty();														
			$("#frm_te_serv_cacic"							).empty();														
			$("#frm_te_serv_updates"						).empty();														
			$("#frm_nu_porta_serv_updates"					).empty();														
			$("#frm_nu_limite_ftp"							).empty();														
			$("#frm_te_path_serv_updates"					).empty();														
			$("#frm_nm_usuario_login_serv_updates_gerente"	).empty();														
			$("#frm_te_senha_login_serv_updates_gerente"	).empty();														
			$("#frm_nm_usuario_login_serv_updates"			).empty();
			$("#frm_te_senha_login_serv_updates"			).empty();																					
			$("#frm_te_acoes"								).empty();
			$("#frm_te_modulos"								).empty();
			$("#frm_te_estacoes_windows"					).empty();
			$("#frm_te_estacoes_linux"						).empty();
				
			$.ajax({
					type: "GET",
					url: "admin_queries.php",
					data: "te_ip_rede=" + $("#frm_te_ip_rede").val(),
					success: function(subrede)
						{
						subredeFields = subrede.split("_FD_");
						if ($.trim(subredeFields[0]) != "")						
							{								
							$("#frm_te_mascara_rede"						).val(subredeFields[0]);
							$("#frm_nm_rede"								).val(subredeFields[1]);														
							$("#frm_nm_local"								).val(subredeFields[2]);														
							$("#frm_nm_servidor_autenticacao"				).val(subredeFields[3]);														
							$("#frm_te_serv_cacic"							).val(subredeFields[4]);														
							$("#frm_te_serv_updates"						).val(subredeFields[5]);														
							$("#frm_nu_porta_serv_updates"					).val(subredeFields[6]);														
							$("#frm_nu_limite_ftp"							).val(subredeFields[7]);														
							$("#frm_te_path_serv_updates"					).val(subredeFields[8]);														
							$("#frm_nm_usuario_login_serv_updates_gerente"	).val(subredeFields[9]);														
							$("#frm_te_senha_login_serv_updates_gerente"	).val(subredeFields[10]);														
							$("#frm_nm_usuario_login_serv_updates"			).val(subredeFields[11]);																					
							$("#frm_te_senha_login_serv_updates"			).val(subredeFields[12]);	
							
							var arrAcoesRecords = subredeFields[13].split('_ACR_');
							if (arrAcoesRecords.length > 1)
								{
								$("#frm_te_mensagens").html('Montando Lista de Acoes...');
								// create table
								var $table = $('<table border="1" cellspacing=0 cellpadding=0>');
								
								// caption
								$table.append('<caption>Acoes</caption>')
								
								//tbody
								var $tbody = $table.append('<tbody />').children('tbody');

								var CorLinha = 0;
								// add rows
								for (i=0; i<arrAcoesRecords.length; i++)
									{
									arrAcoesFields = arrAcoesRecords[i].split('_ACF_');
									$tbody.append('<tr ' + (CorLinha ? 'bgcolor="E1E1E1"' : '') + '" />').children('tr:last')
									.append("<td align='left'>" + arrAcoesFields[0] + (arrAcoesFields[1] != 'NADA' ? ' (Forcada em ' + arrAcoesFields[1] + ')' : '') + "</td>")
									CorLinha = !CorLinha;
									}
								// add table to dom
								$table.appendTo('#frm_te_acoes');									
								}
								
							var arrModulosRecords = subredeFields[14].split('_MOR_');
							if (arrModulosRecords.length > 1)
								{
								$("#frm_te_mensagens").html('Montando Lista de Modulos...');									
								// create table
								var $table = $('<table border="1" cellspacing=0 cellpadding=0>');
								// caption
								$table.append('<caption>Modulos</caption>')
								// thead
								.append('<thead>').children('thead')
								.append('<tr />').children('tr').append('<th align="right">Seq.</th><th>&nbsp;</th><th>Nome</th><th>&nbsp;&nbsp</th><th>Versao</th><th>&nbsp;&nbsp</th><th>Atualizacao</th><th>&nbsp;</th><th>Hash</th>');
								
								//tbody
								var $tbody = $table.append('<tbody />').children('tbody');
								
								var CorLinha = 0;
								var intSequencia = 1;
								// add rows
								for (i=0; i<arrModulosRecords.length; i++)
									{
									arrModulosFields = arrModulosRecords[i].split('_MOF_');
									$tbody.append('<tr ' + (CorLinha ? 'bgcolor="E1E1E1"' : '') + '" />').children('tr:last')
									.append("<td align='right'>" + intSequencia + "</td>")
                                                                        .append("<td align='left'></td>")
									.append("<td align='left'>" + arrModulosFields[0] + "</td>")
									.append("<td align='left'></td>")
									.append("<td align='left'>" + arrModulosFields[1] + "</td>")									
									.append("<td align='left'></td>")																		
									.append("<td align='left'>" + arrModulosFields[2] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrModulosFields[3] + "</td>")			
									CorLinha = !CorLinha;
									intSequencia ++;
									}
								// add table to dom
								$table.appendTo('#frm_te_modulos');									
								}

							var arrEstacoesWindowsRecords = subredeFields[15].split('_ESR_');
							if (arrEstacoesWindowsRecords.length > 1)
								{
								$("#frm_te_mensagens").html('Montando Lista de Estacoes de Trabalho com MS-Windows...');									
								// create table
								var $table = $('<table border="1" cellspacing=0 cellpadding=0>');
								// caption
								$table.append('<caption>Estacoes de Trabalho - MS-Windows</caption>')
								// thead
								.append('<thead>').children('thead')
								.append('<tr />').children('tr').append('<th align="right">Seq.</th><th>&nbsp;</th><th align="left">Host</th><th>&nbsp;&nbsp</th><th align="left">IP</th><th>&nbsp;&nbsp</th><th align="left">S.O.</th><th>&nbsp;&nbsp</th><th align="left">MAC Address</th><th>&nbsp;&nbsp</th><th align="left">Dominio Windows</th><th>&nbsp;&nbsp</th><th align="left">Dominio DNS</th><th>&nbsp;</th><th align="center">Ultimo Acesso</th>');
								
								//tbody
								var $tbody = $table.append('<tbody />').children('tbody');
								
								intSequencia = 1;
								CorLinha = 0;
								// add rows
								for (i=0; i<arrEstacoesWindowsRecords.length; i++)
									{
									arrEstacoesWindowsFields = arrEstacoesWindowsRecords[i].split('_ESF_');
									$tbody.append('<tr ' + (CorLinha ? 'bgcolor="E1E1E1"' : '') + '" />').children('tr:last')
									.append("<td align='right'>" + intSequencia + "</td>")
                                                                        .append("<td align='left'></td>")
									.append("<td align='left'>" + arrEstacoesWindowsFields[0] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesWindowsFields[1] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesWindowsFields[2] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesWindowsFields[3] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesWindowsFields[4] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesWindowsFields[5] + "</td>")																		
									.append("<td align='left'></td>")                                                              
                                    .append("<td align='center'>" + arrEstacoesWindowsFields[6] + "</td>")
									CorLinha = !CorLinha;
									intSequencia++;
									}
								// add table to dom
								$table.appendTo('#frm_te_estacoes_windows');									
								}
								
							var arrEstacoesLinuxRecords = subredeFields[16].split('_ESR_');
							if (arrEstacoesLinuxRecords.length > 1)
								{
								$("#frm_te_mensagens").html('Montando Lista de Estacoes de Trabalho com GNU/Linux...');																		
								// create table
								var $table = $('<table border="1" cellspacing=0 cellpadding=0>');
								// caption
								$table.append('<caption>Estacoes de Trabalho - GNU/Linux</caption>')
								// thead
								.append('<thead>').children('thead')
								.append('<tr />').children('tr').append('<th align="right">Seq.</th><th>&nbsp;</th><th align="left">Host</th><th>&nbsp;&nbsp</th><th align="left">IP</th><th>&nbsp;&nbsp</th><th align="left">S.O.</th><th>&nbsp;&nbsp</th><th align="left">MAC Address</th><th>&nbsp;&nbsp</th><th align="left">Dominio Windows</th><th>&nbsp;&nbsp</th><th align="left">Dominio DNS</th><th>&nbsp;</th><th align="center">Ultimo Acesso</th>');
								
								//tbody
								var $tbody = $table.append('<tbody />').children('tbody');
								
								intSequencia = 1;
								CorLinha = 0;
								// add rows
								for (i=0; i<arrEstacoesLinuxRecords.length; i++)
									{
									arrEstacoesLinuxFields = arrEstacoesLinuxRecords[i].split('_ESF_');
									$tbody.append('<tr ' + (CorLinha ? 'bgcolor="E1E1E1"' : '') + '" />').children('tr:last')
									.append("<td align='right'>" + intSequencia + "</td>")
                                                                        .append("<td align='left'></td>")
									.append("<td align='left'>" + arrEstacoesLinuxFields[0] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesLinuxFields[1] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesLinuxFields[2] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesLinuxFields[3] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesLinuxFields[4] + "</td>")
									.append("<td align='left'></td>")									
									.append("<td align='left'>" + arrEstacoesLinuxFields[5] + "</td>")																		
									.append("<td align='left'></td>")                                                              
                                    .append("<td align='center'>" + arrEstacoesLinuxFields[6] + "</td>")
									CorLinha = !CorLinha;
									intSequencia++;
									}
								// add table to dom
								$table.appendTo('#frm_te_estacoes_linux');									
								}								
							$("#frm_te_mensagens").html('');																										
							}
						else
							alert('Sub-Rede Não Encontrada!');
						}
				});
			}
		$("#frm_te_ip").focus();																																																			
		});
	});
