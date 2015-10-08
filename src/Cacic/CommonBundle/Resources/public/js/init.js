/**
 * Eventos inicializados após renderização da página
 * @todo necessário verificar tradução das mensagens de texto
 */
$(document).ready(function(){
    /**
     * Lista de notificações
     */
    var homepage = $( '#homepage' ).attr('href');
	if (homepage !== undefined) {
        var not_url = homepage + 'notifications/get';
        var not_req = {
            'limit': 5
        };
        System.Notifications.get(not_url, not_req);
	}

	/**
	 * Remove o COOKIE com informação da imagem de BACKGROUND
	 */
	$.cookie("AdminIntensoBackground", null );
	
	/**
	 * Animação AJAX
	 */
	$( document ).ajaxStart( function() {
		$( '#ajaxLoading' ).fadeIn();
		$( 'body' ).css( 'overflow', 'hidden' );
	}) 
	.ajaxStop( function() {
		$( '#ajaxLoading' ).fadeOut();
		$( 'body' ).css( 'overflow', 'auto' );
	});
	
	/**
	 * Modal de confirmação de exclusão de item
	 */
	$( "#System_Excluir" ).dialog({
		autoOpen: false,
		height: 190,
		width: 350,
		modal: true,
		closeText: 'Fechar',
		buttons: {
			"Excluir" : function(){
				$( this ).dialog( "close" );
				var params = $( this ).data( 'params' );
                $.ajax(
					{
						type: "POST",
						url: params.url,
						data: params,
						cache: false,
						async: false,
						success: function( data )
						{
							if ( data.status == 'error' )
							{
								var msg = data.message;
                                if (msg == undefined) {
                                    switch( data.code )
                                    {
                                        case '23503':
                                            msg = 'Ítem não pode ser excluído porque contem dados relacionados!';
                                            break;

                                        default:
                                            msg = 'Erro desconhecido!';
                                            break;
                                    }
                                }

								System.Flash.show( 'Erro', msg );
								return false;
							}
							
							/**
							 * Verifica quantidade de colunas e quantos elementos ainda restam a listar após a exclusão
							 */
							var _tbody = $( '#item_' + params.id ).parent();
							var _thead = $( 'thead', _tbody.parent() );
							var _colspan = _thead.children().first().children().length; // Qtde colunas
							
							$( '#item_' + params.id ).remove(); // Remove o item da Grid
							
						    if ( _tbody.children().length < 1 )
					    	{ // Se for o último registro a ser excluído
						    	_tbody.append('<tr><td style="text-align: center" colspan="' + _colspan + '"><b>NENHUM REGISTRO ENCONTRADO</b></td></tr>');
					    	}
                            
                            System.Flash.show( 'Sucesso', 'Item excluído com sucesso!' );
						},
						error: function( data )
						{
                            System.Flash.show( 'Erro', 'Erro na exclusão do item!' );
						},
						complete: function( data )
						{
							/**
							 * Caso uma função de CallBack tenha sido definida, invoca-a neste ponto e passa como parâmetros:
							 * - Os próprios parâmetros já enviados na requisição AJAX
							 * - O "response" devolvido pelo servidor como resposta à requisição AJAX
							 */
							if ( params.callback )
								params.callback( params, data );

                            window.location.reload(true);
						}
					}
				);
			},
			"Cancelar" : function(){
				$( this ).dialog( "close" );
			}
		},
        open: function(event,ui){
            // Impede que o overlay trave a tela
            $('.ui-widget-overlay').addClass('overlay-hidden');
        },
        beforeClose: function(event,ui){
            $('.ui-widget-overlay').removeClass('overlay-hidden');
        },
		show: { effect: "fade", duration: 500 },
		hide: { effect: "fade", duration: 500 }
	});

    /**
     * Ativa item fornecido
     */
    $( "#System_Ativar" ).dialog({
        autoOpen: false,
        height: 190,
        width: 350,
        modal: true,
        closeText: 'Fechar',
        buttons: {
            "Ativar" : function(){
                $( this ).dialog( "close" );
                var params = $( this ).data( 'params' );
                $.ajax(
                    {
                        type: "POST",
                        url: params.url,
                        data: params,
                        cache: false,
                        async: false,
                        success: function( data )
                        {
                            if ( data.status == 'error' )
                            {
                                var msg = data.message;
                                if (msg == undefined) {
                                    msg = "Erro ao ativar o item";
                                }

                                System.Flash.show( 'Erro', msg );
                                return false;
                            }

                            /**
                             * Verifica quantidade de colunas e quantos elementos ainda restam a listar após a exclusão
                             */
                            var _tbody = $( '#item_' + params.id ).parent();
                            var _thead = $( 'thead', _tbody.parent() );
                            var _colspan = _thead.children().first().children().length; // Qtde colunas

                            $( '#item_' + params.id ).remove(); // Remove o item da Grid

                            if ( _tbody.children().length < 1 )
                            { // Se for o último registro a ser excluído
                                _tbody.append('<tr><td style="text-align: center" colspan="' + _colspan + '"><b>NENHUM REGISTRO ENCONTRADO</b></td></tr>');
                            }

                            System.Flash.show( 'Sucesso', 'Item ativado com sucesso!' );

                            window.location.reload(true);
                        },
                        error: function( data )
                        {
                            System.Flash.show( 'Erro', 'Erro na ativaçao do item!' );
                        },
                        complete: function( data )
                        {
                            /**
                             * Caso uma função de CallBack tenha sido definida, invoca-a neste ponto e passa como parâmetros:
                             * - Os próprios parâmetros já enviados na requisição AJAX
                             * - O "response" devolvido pelo servidor como resposta à requisição AJAX
                             */
                            if ( params.callback )
                                params.callback( params, data );
                        }
                    }
                );
            },
            "Cancelar" : function(){
                $( this ).dialog( "close" );
            }
        },
        open: function(event,ui){
            // Impede que o overlay trave a tela
            $('.ui-widget-overlay').addClass('overlay-hidden');
        },
        beforeClose: function(event,ui){
            $('.ui-widget-overlay').removeClass('overlay-hidden');
        },
        show: { effect: "fade", duration: 500 },
        hide: { effect: "fade", duration: 500 }
    });
	
	/**
	 * Abre a MODAL com o formulário para troca de Senha
	 */
	$( "#trocarPropriaSenha" ).dialog({
		autoOpen: false,
		height: 250,
		width: 350,
		dialogClass: "noOverlayDialog",
		modal: true,
		buttons: {
			"Salvar" : function(){
				// Realiza a validação dos campos
				var validation = function(){
					/**
					 * Verifica se a senha informa é valida
					 * - Se as 2 senhas conferem entre si
					 * - Se possui o mínimo de caracteres (6)
					 * - Se possui o máximo de caracteres (10)
					 * @return boolean|string
					 */
					var min = 6; // Mínimo de caracteres para a senha
					var max = 10; // Máximo de caracteres para a senha
					
					var senhaAtual = $( '#troca_propria_senha_te_senha_atual' ).val();
					var senhaNova = $( '#troca_propria_senha_te_senha_nova' ).val();
					var senhaConfirma = $( '#troca_propria_senha_te_senha_nova_confirma' ).val();
					
					if ( senhaAtual.length < min || senhaAtual.length > max )	return 'Senha atual inválida';
					if ( senhaNova != senhaConfirma )		return 'Senhas não conferem';
					if ( senhaNova.length < min )	return 'Nova Senha não pode conter menos que ' +min+ ' caracteres';
					if ( senhaNova.length > max )	return 'Nova Senha não pode conter mais que ' +max+ ' caracteres';
					
					return true;
				};
				var validation_result = validation();
				
				if( validation_result === true )
				{
					var params = {
						'senha_nova' : $( '#troca_propria_senha_te_senha_nova' ).val(),
						'senha_atual': $( '#troca_propria_senha_te_senha_atual' ).val()
					};
					
					$.ajax(
						{
							type: "POST",
							url: $( this ).data( 'params' ).url,
							cache: false,
							async: false,
							success: function( data )
							{
								System.Flash.show( 'Sucesso', 'Senha alterada com sucesso!' );
							},
							error: function( data )
							{
								var response = JSON.parse(data.responseText);
								System.Flash.show( 'Erro', 'Erro na solicitação: ' + response.status );
							},
							data: params
						}
					);
				}
				else
				{
					System.Flash.show( 'Erro', validation_result );
				}
				
				$( this ).dialog( "close" );
			},
			"Cancelar" : function(){
				$( this ).dialog( "close" );
			}
		},
		show: { effect: "fade", duration: 500 },
		hide: { effect: "fade", duration: 500 },
        open: function(event,ui){
            // Impede que o overlay trave a tela
            $('.ui-widget-overlay').addClass('overlay-hidden');
        },
        beforeClose: function(event,ui){
            $('.ui-widget-overlay').removeClass('overlay-hidden');
        },
		close: function( event, ui ){ 
			// Limpa os campos do formulário
			$( "#troca_propria_senha_te_senha_atual" ).val( '' );
			$( "#troca_propria_senha_te_senha_nova" ).val( '' );
			$( "#troca_propria_senha_te_senha_nova_confirma" ).val( '' );
		}
	});
	
	/**
	 * Ativa a ABA correta, caso esta seja passada via URL
	 */
	if ( window.location.hash != '' && window.location.hash != undefined )
	{
		var _activeTab = window.location.hash;
		_activeTab && $('ul.nav a[href="' + _activeTab + '"]').tab('show');
	}
	
	/**
	 * Configura a propriedade css Z-INDEX do MODAL
	 */
	$.modal.defaults.zIndex = 1030;
	$.modal.defaults.showSpinner = false;
	
	/**
	 * Listeners do sistema
	 */
	System.Grid.excluir(); // Inicializa o LISTENER para os botões-padrão de exclusão de itens
	System.Grid.ativar(); // Inicializa o LISTENER para os botões-padrão de ativação de itens
    System.Form.reset(); // Inicializa o LISTENER para os botões (ou input) type=reset
    System.Menu.changeOwnPass(); // Inicializa o LISTENER para funcionalidade de troca de senha
    
    /**
     * Adiciona o calendário aos campos de data (contendo a classe datepicker_on)
     * e já configura a máscara no formato xx/xx/xxxx
     */
    $(".datepicker_on").datepicker({ altFormat: "dd/mm/yy" }).mask('99/99/9999');
    /*$('#datatable').dataTable( {
        "iDisplayLength": 100,
        "iDisplayStart": 1,
        "bJQueryUI": true,
        "sPaginationType": "full_numbers",
        "bDestroy": true
    } );*/


});