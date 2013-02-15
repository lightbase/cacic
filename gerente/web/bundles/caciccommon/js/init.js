/**
 * Eventos inicializados após renderização da página
 */
$(document).ready(function(){
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
						cache: false,
						async: false,
						success: function( data )
						{
						    System.Flash.show( 'Sucesso', 'Item excluído com sucesso!' );
						    $( '#item_' + params.id ).fadeOut(); // Remove o item da Grid
						},
						error: function( data )
						{
							System.Flash.show( 'Erro', 'Erro na exclusão do item!' );
						},
						data: params
					}
				);
			},
			"Cancelar" : function(){
				$( this ).dialog( "close" );
			}
		},
		show: { effect: "fade", duration: 500 },
		hide: { effect: "fade", duration: 500 }
	});
	
	System.Grid.excluir(); // Inicializa o LISTENER para os botões-padrão de exclusão de itens
});