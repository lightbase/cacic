/**
 * Biblioteca com métodos comuns a todo o Sistema
 */

var System = {
	Grid : { // Comportamentos relacionados a itens de grids de listagens de itens
		excluir : function(){ // Método executado ao acionar a funcionalidade de exclusão
			$( ".bt-excluir" ).bind( 'click', function(e){
				e.preventDefault();
				var url = $( this ).attr( 'url' );
				var id = $( this ).parent().parent().attr( 'id' ).replace( /.*?(\d+)$/, '$1' );
				$( "#System_Excluir" ).data( 'params', { 'url' : url, 'id' : id } ).dialog( "open" );
			});
		}
	},
	Flash : { // Comportamentos relacionados a mensagens
		show : function( type, msg ){ // Método executado na exibição de mensagens do sistema
			$( '#msgErro' ).empty().hide();
			$( '#msgAviso' ).empty().hide();
			$( '#msgSucesso' ).empty().hide();
			/**
			 * @todo alterar para permitir array de mensagens
			 */
			$( '#msg' +type ).text( msg ).fadeIn();
		}
	},
    Form : { // Comportamentos relacionados a formulários
        reset : function(){ // Método executado ao se acionar a funcionalidade de "limpar valores" do formulário
            $( 'button[type=reset],input[type=reset]' ).click(function(){
                $( 'input[type=text]').attr('placeholder', '');

            });
        }
    }
}