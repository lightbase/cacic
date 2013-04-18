Ext.onReady(function() {

	
	var mainMenu = Ext.create('Ext.menu.Menu', {
	  	renderTo: 'menu-principal',
	  	floating: false,
        collapsible: true,
		border: true,
		frame: true,
		title: 'Home',
        titleAlign: 'center',
		items: [

			{text: 'Estatisticas' , href: 'estatisticas' },
                        {text: 'Busca', href: 'busca' },
                        {text: 'Downloads', href: 'downloads' },
                        {text: 'Relatorios', href: 'relatorios'},
                        {text: 'Mensagens', href: 'mensagens' },
                        {text: 'Ajuda', href: 'ajuda' },
                        {text: 'Usuario', href: 'usuario' },
                        {text: 'Ferramentas', href: 'ferramentas' },
								]	
	});

   var	fav_html = '<div id="favoriteItems">'+
	 	'<div class="fav"><a href="estatisticas"><img src="static/icons/estatisticas.png">Estatísticas</a></div>'+
	 	'<div class="fav"><a href="busca"><img src="static/icons/busca.png">Busca</a></div>'+
	 	'<div class="fav"><a href="downloads"><img src="static/icons/downloads.png">Downloads</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/relatorios.png">Relatórios</a></div>'+
	 	'<div class="fav"><a href="mensagens"><img src="static/icons/mensagens.png">Mensagens</a></div>'+
	 	'<div class="fav"><a href="ajuda"><img src="static/icons/ajuda.png">Ajuda</a></div>'+
	 	'<div class="fav"><a href="usuario"><img src="static/icons/usuario.png">Usuário</a></div>'+
	 	'<div class="fav"><a href="ferramentas"><img src="static/icons/ferramentas.png">Ferramentas de sistema</a></div>'+
	'</div>';

	var favoriteMenu = Ext.create('Ext.panel.Panel', {
		layout: 'fit',
	  	renderTo: 'menu-favoritos',
      	  	collapsible: true,
		border: true,
		frame: true,
		heigth: "100%",
		width: "100%",
        	title: 'Favoritos',
        	titleAlign: 'center',
		html: fav_html
	});

});

