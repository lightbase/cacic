    function valida_form() 
        {
    
        if ( document.form.frm_nm_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Nome � obrigat�rio.");
            document.form.frm_nm_servidor_autenticacao.focus();
            return false;
            }		
        else if ( document.form.frm_nm_servidor_autenticacao_dns.value == "" ) 
            {	
            alert("O campo Identificador no DNS � obrigat�rio.");
            document.form.frm_nm_servidor_autenticacao_dns.focus();
            return false;
            }					
        else if ( document.form.frm_te_ip_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Endere�o IP � obrigat�rio.");
            document.form.frm_te_ip_servidor_autenticacao.focus();
            return false;
            }
        else if ( document.form.frm_nu_porta_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Porta � obrigat�rio.");
            document.form.frm_nu_porta_servidor_autenticacao.focus();
            return false;
            }			
        else if ( document.form.frm_nu_versao_protocolo.value == "" ) 
            {	
            alert("O campo Vers�o � obrigat�rio.");
            document.form.frm_nu_versao_protocolo.focus();
            return false;
            }						
        else if ( document.form.frm_id_tipo_protocolo.value == "" ) 
            {	
            alert("Selecione o Protocolo.");
            document.form.frm_id_tipo_protocolo.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_nome.value == "" ) 
            {	
            alert("O atributo para Retorno de Nome Completo � obrigat�rio.");
            document.form.frm_te_atributo_retorna_nome.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_email.value == "" ) 
            {	
            alert("O atributo para Retorno de Email � obrigat�rio.");
            document.form.frm_te_atributo_retorna_email.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_identificador.value == "" ) 
            {	
            alert("O atributo Identifica��o � obrigat�rio.");
            document.form.frm_te_atributo_identificador.focus();
            return false;
            }
        return true;	
        }
