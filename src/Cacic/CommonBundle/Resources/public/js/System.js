/**
 * Biblioteca com métodos comuns a todo o Sistema
 */

var System = {
	Grid : { // Comportamentos relacionados a itens de grids de listagens de itens
		excluir : function(){ // Método executado ao acionar a funcionalidade de exclusão
			$( 'body' ).delegate( 'a.bt-excluir', 'click', function(e){
				e.preventDefault();
				var url = $( this ).attr( 'href' );

                var elm = $( this ).parent().parent();

                // Tenta encontrar o id pelo ID do elemento
                if (typeof elm.attr('id') === "undefined") {
                    id = $( this ).attr('id');
                } else {
                    var id = elm.attr( 'id' ).replace( /.*?(\d+)$/, '$1' );
                }

				var callback = $( this ).attr( 'data-callback' );
				$( "#System_Excluir" ).data( 'params', { 'url': url, 'id': id, 'callback': callback } ).dialog( "open" );
			});

            $( 'body' ).delegate( 'a.bt-excluir-compositekey', 'click', function(e){ // Exclusão de itens com CHAVE COMPOSTA
				e.preventDefault();
				var url = $( this ).attr( 'href' );
				var id = $( this ).parent().parent().attr( 'id' ).replace( /^item_(.*?)$/, '$1' ); // Utilizado para REMOÇÃO do item da GRID
				var callback = $( this ).attr( 'data-callback' );
				var params = { 'url': url, 'id': id, 'compositeKeys': JSON.parse( $( this ).attr('data-composite-keys') ), 'callback': callback };
				$( "#System_Excluir" ).data( 'params', params ).dialog( "open" );
			});
		},
		ativar: function(){
            $( 'body' ).delegate( 'a.bt-ativar', 'click', function(e){
                e.preventDefault();
                var url = $( this ).attr( 'href' );

                var elm = $( this ).parent().parent();

                // Tenta encontrar o id pelo ID do elemento
                if (typeof elm.attr('id') === "undefined") {
                    id = $( this ).attr('id');
                } else {
                    var id = elm.attr( 'id' ).replace( /.*?(\d+)$/, '$1' );
                }

                var callback = $( this ).attr( 'data-callback' );
                $( "#System_Ativar" ).data( 'params', { 'url': url, 'id': id, 'callback': callback } ).dialog( "open" );
            });

            $( 'body' ).delegate( 'a.bt-ativar-compositekey', 'click', function(e){ // Exclusão de itens com CHAVE COMPOSTA
                e.preventDefault();
                var url = $( this ).attr( 'href' );
                var id = $( this ).parent().parent().attr( 'id' ).replace( /^item_(.*?)$/, '$1' ); // Utilizado para REMOÇÃO do item da GRID
                var callback = $( this ).attr( 'data-callback' );
                var params = { 'url': url, 'id': id, 'compositeKeys': JSON.parse( $( this ).attr('data-composite-keys') ), 'callback': callback };
                $( "#System_Ativar" ).data( 'params', params ).dialog( "open" );
            });
        }
	},
	Flash : { // Comportamentos relacionados a mensagens
		show : function( type, msg ){ // Método executado na exibição de mensagens do sistema
			$( '.alert' ).fadeOut();
			/**
			 * @todo alterar para permitir array de mensagens
			 */
			$( '#msg' +type ).append( msg + '<br />' ).fadeIn();
			$('html, body').animate({ scrollTop: $('body').offset().top }, 1000);
		}
	},
    Form : { // Comportamentos relacionados a formulários
        reset : function(){ // Método executado ao se acionar a funcionalidade de "limpar valores" do formulário
            $( 'button[type=reset],input[type=reset]' ).click(function(){
                $( 'input[type=text]').attr('placeholder', '');
            });
        },
        toggleCheck: function() { // Invocado ao "checkar"/"descheckar" checkbox para marcar/desmarcar todos
        	$( 'input[type=checkbox].toggleCheck' ).bind('click', function(){
                var elm = $(this)[0];
        		var _isChecked = elm.checked; // Verifica se o o toggleCheck está sendo "checkado" ou "descheckado"
        		var _name = $(this).val(); // O atributo value do checkbox toggleCheck indica qual grupo de checkboxes deve ser considerado
        		$('input[type=checkbox][name*='+ _name + ']').each(function(){
        			$(this)[0].checked = _isChecked;
        		})
        	});
        },
        bootStrapTransfer : {
        	handle : function( aData ){ // Antes de submeter o formulário, recupera os valores selecionados nos transfers informados e os encapsula para envio
        		var $form = aData.form;
        		$form.submit(function(){
        			for( i in aData.elms ) {
        				$('<input>').attr({
        				    type: 'hidden',
        				    id: aData.fieldsPrefix + '_' + aData.elms[i].inputHiddenName,
        				    name: aData.fieldsPrefix + '[' + aData.elms[i].inputHiddenName + ']'
        				})
        				.val( aData.elms[i].transferElement.get_values() )
        				.appendTo('form');
        			}
        		});
        	}
        },
        focusFirstTabOnError : function( formId ){
        	var $errors = $( 'div.control-group.error', $(formId) );
        	if ( $errors.length > 0 ) // Verifica se há erros no formulário
    		{
        		var firstErrorTab = $errors.first().parent().attr('id');
        		$('ul.nav a[href="#' + firstErrorTab + '"]').tab('show');
    		}
        }
    },
	Menu : {
		setActive : function( url ){
			//alert( url );
		},
		changeOwnPass : function( url ){
			$( "a.bt-trocar-propria-senha" ).bind( 'click', function(e){
				e.preventDefault();
				var params = { 'url': $( this ).attr( 'href' ) };
				$( "#trocarPropriaSenha" ).data( 'params', params ).dialog( "open" ); // Abre a "Modal" com o formulário de troca de senha
			});
		}
	},
	Notifications: {
        get: function(url, dados) {
            // Envia requisição Ajax de notificação
            $.ajax({
                type: "GET",
                url: url,
                data: dados,
                dataType: "json",
                cache: true,
                async: true,
                success: function(result) {
                    var elm = "";
                    for (var i = 0; i < result.length; i++) {
                        var date = new Date(result[i].creationDate);
                        elm += "<a href='" + url + "/" + result[i].idNotification + "'>" +
                            result[i].subject +
                            "</a>" +
                            "<p>" +
                            "<ul>" +
                            "<li><b>Computador: </b>" + result[i].nmComputador + "</li>" +
                            "<li><b>IP: </b>" + result[i].teIpComputador + "</li>" +
                            "<li><b>Data: </b>" + date.toLocaleDateString() + " " + date.toLocaleTimeString() + "</li>" +
                            "</ul>" +
                            //result[i].body.substr(0, 100) +
                            "</p>" +
                            "<hr>" +
                            "\n";
                    }

                    // Add more link to the end
                    elm += "<a href='" + url + "/list' target='_blank'>Mais...</a>";

                    var number = $( '#cacic_notifications_number');
                    var top = $( '#notifications_top');
                    number.text(result.length);
                    top.text(result.length);
                    if (result.length > 0) {
                        var notifications = $( '#cacic_notifications' );
                        notifications.attr('data-content', elm);

                        number.removeClass('label-info');
                        number.addClass('label-warning');
                        top.removeClass('label-info');
                        top.addClass('label-warning');
                    } else {
                        number.removeClass('label-warning');
                        number.addClass('label-info');
                        top.removeClass('label-warning');
                        top.addClass('label-info');
                    }
                },
                error: function(result, status, error) {
                    console.log("Erro na leitura das notificações");
                    //console.log("Status: " + status);
                    console.log("Error message: " + error);
                    $( '#cacic_notifications_number').text(0);
                }
            });
        },
    }
}