/**
 * Biblioteca com métodos comuns a todo o Sistema
 */

var System = {
	Grid : {
		excluir : function(){
			$( ".bt-excluir" ).bind( 'click', function(e){
				e.preventDefault();
				var url = $( this ).attr( 'url' );
				var id = $( this ).parent().parent().attr( 'id' ).replace( /.*?(\d+)$/, '$1' );
				$( "#System_Excluir" ).data( 'params', { 'url' : url, 'id' : id } ).dialog( "open" );
			});
		}
	},
	Flash : {
		show : function( type, msg ){
			$( '#msgErro' ).empty().hide();
			$( '#msgAviso' ).empty().hide();
			$( '#msgSucesso' ).empty().hide();
			/**
			 * @todo alterar para permitir array de mensagens
			 */
			$( '#msg' +type ).text( msg ).fadeIn();
		}
	}
}