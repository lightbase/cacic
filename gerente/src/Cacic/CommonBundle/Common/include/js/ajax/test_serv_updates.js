function testServUpdatesAJAX(pStrServ, pStrUser, pStrPass, pStrPath, pStrPort)
		{
		var oDivMsgTeServUpdates = returnObjById("divMsgTeServUpdates");
		var strResult = null;
		
		$.ajax({url:"test_serv_updates.php",
			    type:'get',
				dataType:'html',
				async:false,
				data:{pStrServName:pStrServ,
				 		pStrUserName:pStrUser,
				 		pStrUserPass:pStrPass,
				 		pStrServPath:pStrPath,
			 			pStrNuPort:pStrPort},
				success: function(pStrRetorno)
							{								
							strResult = pStrRetorno;
							}					
			   });
		return strResult;
		};

	function testServUpdates()
		{
		var oTeServUpdates		= returnObjById('frm_te_serv_updates');
		var oTePathServUpdates	= returnObjById('frm_te_path_serv_updates');		
		var oNuPortaServUpdates	= returnObjById('frm_nu_porta_serv_updates');					
			
		if ($(oTeServUpdates).val() != '') 
			{
			var arrResult 			 = testServUpdatesAJAX($(oTeServUpdates).val(),'','',$(oTePathServUpdates).val(),$(oNuPortaServUpdates).val()).split('_=_');
			var oDivMsgTeServUpdates = returnObjById("divMsgTeServUpdates");		
			$(oDivMsgTeServUpdates).html('Testando acesso ao Servidor...');				
			
			if (arrResult[2] != -1)		
				{
				$(oDivMsgTeServUpdates).addClass('div_sucesso').removeClass('div_insucesso');											
				$(oDivMsgTeServUpdates).html('Teste de acesso ao servidor => OK!');			
				}
			else
				{
				$(oDivMsgTeServUpdates).addClass('div_insucesso').removeClass('div_sucesso');											
				$(oDivMsgTeServUpdates).html('Teste de acesso ao servidor => ERRO: Servidor Inexistente ou Servico de FTP Indisponivel!');			
				}		
			oDivMsgTeServUpdates = null;
			arrResult = null;
			}
			
		if (($(oTeServUpdates).val() != '')&&($(oTePathServUpdates).val() != '')) 
			{	
			var oNmUsuarioLoginServUpdates 			= returnObjById('frm_nm_usuario_login_serv_updates');
			var oTeSenhaLoginServUpdates			= returnObjById('frm_te_senha_login_serv_updates');		
			if  ($(oNmUsuarioLoginServUpdates).val() != '' && $(oTeSenhaLoginServUpdates).val() != '')
				{
				var arrResult = testServUpdatesAJAX($(oTeServUpdates).val(),
													$(oNmUsuarioLoginServUpdates).val(),
													$(oTeSenhaLoginServUpdates).val(),
													$(oTePathServUpdates).val(),
													$(oNuPortaServUpdates).val()).split('_=_');										
	
				var oDivMsgNmUsuarioLoginServUpdates = returnObjById("divMsgNmUsuarioLoginServUpdates");		
				$(oDivMsgNmUsuarioLoginServUpdates).html('Testando acesso com o usuario para AGENTES...');					
				
				var oDivMsgTePathServUpdates 		 = returnObjById("divMsgTePathServUpdates");								
				$(oDivMsgTePathServUpdates).html('Testando acesso ao diretorio...');								
				
				if (arrResult[2] == '550')	// ChDir nao funfou!	
					{
					$(oDivMsgTePathServUpdates).html('');											
					
					$(oDivMsgTePathServUpdates).addClass('div_insucesso').removeClass('div_sucesso');											
					$(oDivMsgTePathServUpdates).html('Teste de acesso ao diretorio => ERRO: Sem permissao de acesso ao diretorio ou diretorio inexistente!');											
					}				
				else if (arrResult[2] == '530') // Usuario Nao logou!					
					{
					$(oDivMsgTePathServUpdates).html('');											
						
					$(oDivMsgNmUsuarioLoginServUpdates).addClass('div_insucesso').removeClass('div_sucesso');											
					$(oDivMsgNmUsuarioLoginServUpdates).html('Teste de acesso com o usuario para AGENTES => ERRO: Nome de usuario ou senha incorretos!!!');			
					}
				else if (arrResult[2] == '226') // Tudo Funfou!					
					{
					$(oDivMsgNmUsuarioLoginServUpdates).addClass('div_sucesso').removeClass('div_insucesso');											
					$(oDivMsgNmUsuarioLoginServUpdates).html('Teste de acesso com o usuario para AGENTES => OK!');							
					
					$(oDivMsgTePathServUpdates).addClass('div_sucesso').removeClass('div_insucesso');											
					$(oDivMsgTePathServUpdates).html('Teste de acesso ao diretorio => OK!');											
					}					
					
				oDivMsgNmUsuarioLoginServUpdates = null;
				oDivMsgTePathServUpdates 		 = null;			
				arrResult 						 = null;			
				}
	
			var oNmUsuarioLoginServUpdatesGerente 	= returnObjById('frm_nm_usuario_login_serv_updates_gerente');
			var oTeSenhaLoginServUpdatesGerente		= returnObjById('frm_te_senha_login_serv_updates_gerente');
			if ($(oNmUsuarioLoginServUpdatesGerente).val() != '' && $(oTeSenhaLoginServUpdatesGerente).val() != '')
				{
				var arrResult = testServUpdatesAJAX($(oTeServUpdates).val(),
													$(oNmUsuarioLoginServUpdatesGerente).val(),
													$(oTeSenhaLoginServUpdatesGerente).val(),
													$(oTePathServUpdates).val(),
													$(oNuPortaServUpdates).val()).split('_=_');										
	
				var oDivMsgNmUsuarioLoginServUpdatesGerente = returnObjById("divMsgNmUsuarioLoginServUpdatesGerente");		
				$(oDivMsgNmUsuarioLoginServUpdatesGerente).html('Testando acesso com o usuario para GERENTE...');					
				
				var oDivMsgTePathServUpdates 		 = returnObjById("divMsgTePathServUpdates");		
				$(oDivMsgTePathServUpdates).html('Testando acesso ao diretorio...');								
	
				if (arrResult[2] == '550')	// ChDir nao funfou!	
					{
					$(oDivMsgTePathServUpdates).addClass('div_insucesso').removeClass('div_sucesso');											
					$(oDivMsgTePathServUpdates).html('Teste de acesso ao diretorio => ERRO: Sem permissao de acesso ao diretorio ou diretorio inexistente!');											
					}				
				else if (arrResult[2] == '530') // Usuario Nao logou!					
					{
					$(oDivMsgTePathServUpdates).html('');																	
					$(oDivMsgNmUsuarioLoginServUpdatesGerente).addClass('div_insucesso').removeClass('div_sucesso');											
					$(oDivMsgNmUsuarioLoginServUpdatesGerente).html('Teste de acesso com o usuario para GERENTE => ERRO: Nome de usuario ou senha incorretos!!!');			
					}
				else if (arrResult[2] == '226') // Tudo Funfou!					
					{
					$(oDivMsgNmUsuarioLoginServUpdatesGerente).addClass('div_sucesso').removeClass('div_insucesso');											
					$(oDivMsgNmUsuarioLoginServUpdatesGerente).html('Teste de acesso com o usuario para GERENTE => OK!');							
					
					$(oDivMsgTePathServUpdates).addClass('div_sucesso').removeClass('div_insucesso');											
					$(oDivMsgTePathServUpdates).html('Teste de acesso ao diretorio => OK!');											
					}					
					
				oDivMsgNmUsuarioLoginServUpdatesGerente = null;
				oDivMsgTePathServUpdates 			    = null;			
				arrResult 							    = null;			
				}
			}
			
		oTeServUpdates		= null;
		oTePathServUpdates	= null;
		oNuPortaServUpdates	= null;
		};