/**
 * Biblioteca com métodos comuns a todo o Sistema
 */

var System = {
	Grid : { // Comportamentos relacionados a itens de grids de listagens de itens
		excluir : function(){ // Método executado ao acionar a funcionalidade de exclusão
			$( "a.bt-excluir" ).bind( 'click', function(e){
				e.preventDefault();
				var url = $( this ).attr( 'href' );
				var id = $( this ).parent().parent().attr( 'id' ).replace( /.*?(\d+)$/, '$1' );
				var callback = $( this ).attr( 'data-callback' );
				$( "#System_Excluir" ).data( 'params', { 'url': url, 'id': id, 'callback': callback } ).dialog( "open" );
			});
			
			$( "a.bt-excluir-compositekey" ).bind( 'click', function(e){ // Exclusão de itens com CHAVE COMPOSTA
				e.preventDefault();
				var url = $( this ).attr( 'href' );
				var id = $( this ).parent().parent().attr( 'id' ).replace( /^item_(.*?)$/, '$1' ); // Utilizado para REMOÇÃO do item da GRID
				var callback = $( this ).attr( 'data-callback' );
				var params = { 'url': url, 'id': id, 'compositeKeys': JSON.parse( $( this ).attr('data-composite-keys') ), 'callback': callback };
				$( "#System_Excluir" ).data( 'params', params ).dialog( "open" );
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
        		var _isChecked = ( $(this).attr('checked') == "checked" ); // Verifica se o o toggleCheck está sendo "checkado" ou "descheckado"
        		var _name = $(this).val(); // O atributo value do checkbox toggleCheck indica qual grupo de checkboxes deve ser considerado
        		$('input[type=checkbox][name^='+ _name + ']').each(function(){
        			$(this).attr('checked', _isChecked);
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
	}
}