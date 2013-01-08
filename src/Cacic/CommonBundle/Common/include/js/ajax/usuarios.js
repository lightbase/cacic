$(function ()
	{
	$("#frm_nm_usuario_acesso").blur(function () 
		{
		var nm_usuario_acesso = $(this).val();
		$.ajax({
				type: "GET",
				url: "search_user.php",
				data: "nm_usuario_acesso=" + nm_usuario_acesso,
				success: function(usuario)
					{
					usuarioRecords = usuario.split("_RC_");
					for (a = 0; (a < usuarioRecords.length); a++)
						{
						usuarioFields = usuarioRecords[a].split("_FD_");
						if ($.trim(usuarioFields[0]) != "")						
							{								
							$("#frm_nm_usuario_completo").val(usuarioFields[0]);														
							$("#frm_te_emails_contato").val(usuarioFields[1]);
							$("#frm_te_telefones_contato").val(usuarioFields[2]);							
							$("#te_origem_label").html("Origem dos Dados: ");
							$("#te_origem_value").html(usuarioFields[7]);														
	
							$("#frm_id_usuario_ldap").val((($.trim(usuarioFields[8]) != "0") ? nm_usuario_acesso : ""));			
							}
						else
							{
							$("#frm_id_usuario_ldap").val("");																													
							$("#te_origem_label").html("");
							$("#te_origem_value").html("");															
							$("#frm_nm_usuario_completo").focus();													
							}
						}
					}
				});
		});
	});