    function valida_form() 
        {
    
        if ( document.form.frm_nm_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Nome é obrigatório.");
            document.form.frm_nm_servidor_autenticacao.focus();
            return false;
            }		
        else if ( document.form.frm_nm_servidor_autenticacao_dns.value == "" ) 
            {	
            alert("O campo Identificador no DNS é obrigatório.");
            document.form.frm_nm_servidor_autenticacao_dns.focus();
            return false;
            }					
        else if ( document.form.frm_te_ip_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Endereço IP é obrigatório.");
            document.form.frm_te_ip_servidor_autenticacao.focus();
            return false;
            }
        else if ( document.form.frm_nu_porta_servidor_autenticacao.value == "" ) 
            {	
            alert("O campo Porta é obrigatório.");
            document.form.frm_nu_porta_servidor_autenticacao.focus();
            return false;
            }			
        else if ( document.form.frm_nu_versao_protocolo.value == "" ) 
            {	
            alert("O campo Versão é obrigatório.");
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
            alert("O atributo para Retorno de Nome Completo é obrigatório.");
            document.form.frm_te_atributo_retorna_nome.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_retorna_email.value == "" ) 
            {	
            alert("O atributo para Retorno de Email é obrigatório.");
            document.form.frm_te_atributo_retorna_email.focus();
            return false;
            }
        else if ( document.form.frm_te_atributo_identificador.value == "" ) 
            {	
            alert("O atributo Identificação é obrigatório.");
            document.form.frm_te_atributo_identificador.focus();
            return false;
            }
        return true;	
        }
