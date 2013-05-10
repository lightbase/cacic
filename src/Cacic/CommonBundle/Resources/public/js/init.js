/**
 * Eventos inicializados após renderização da página
 */
$(document).ready(function(){
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
						url: params.url, // URL para qual a requisição será enviada
						data: params, // Parâmetros enviados via POST
						cache: false,
						async: false,
						success: function( data )
						{
							/**
							 * Comportamento padrão: exibe mensagem de sucesso e remove item da GRID
							 */
						    System.Flash.show( 'Sucesso', 'Item excluído com sucesso!' );
						    $( '#item_' + params.id ).fadeOut(); // Remove o item da Grid
						},
						error: function( data )
						{
							System.Flash.show( 'Erro', 'Erro na exclusão do item!' );
						},
						complete: function( data )
						{
							/**
							 * Caso uma função de callback seja informada, ela será invocada neste ponto.
							 * A função de callback receberá 2 parâmetros:
							 * 	- o JSON com os parâmetros enviados via AJAX
							 * 	- o JSON com os dados de retorno da requisição AJAX
							 */
							if( params.callback )
						    	params.callback(params, data);
						}
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
    System.Form.reset(); // Inicializa o LISTENER para os botões (ou input) type=reset
    System.Menu.setActive( window.location.toString() ); // Configura o menu ativo
});