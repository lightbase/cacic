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
						url: params.url,
						data: params,
						cache: false,
						async: false,
						success: function( data )
						{
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
						    
						    /**
						     * @todo colocar esta rotina no callback
						     */
                            if( params.arrId != undefined ) // verifica se elemento arrId foi definido
                            {
                                $("input.tipo-check:checked").parent().parent().parent().remove();  // Remove o item da Grid
                            }
                            
                            System.Flash.show( 'Sucesso', 'Item excluído com sucesso!' );
						},
						error: function( data )
						{
							System.Flash.show( 'Erro', 'Erro na exclusão do item! ' );
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
});