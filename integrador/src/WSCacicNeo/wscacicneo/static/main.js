
Ext.onReady(function() {

	var menuItems = Ext.create('Ext.menu.Menu', {
		floating: false,  // usually you want this set to True (default)
		items: [{
			text: 'Opção 1'
		},{
			text: 'Opção 2'
		},{
			text: 'Opção 3'
		},{
			text: 'Opção 4'
		},{
			text: 'Opção 5'
		},{
			text: 'Opção 6'
		},{
			text: 'Opção 7'
		},{
			text: 'Opção 8'
		},{
			text: 'Opção 9'
		},{
			text: 'Opção 10'
		}]
	});

	var mainMenu = Ext.create('Ext.panel.Panel', {
	  	renderTo: 'menu-principal',
		layout: 'fit',
        	collapsible: true,
		border: true,
		frame: true,
		title: 'Menu Principal',
        	titleAlign: 'center',
		items: menuItems
	});

	fav_html = '<div id="favoriteItems">'+
	 	'<div class="fav"><a><img src="/static/icons/estatisticas.png"><p>Estatísticas</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/busca.png"><p>Busca</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/downloads.png"><p>Downloads</p></a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="/static/icons/relatorios.png"><p>Relatórios</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/mensagens.png"><p>Mensagens</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/ajuda.png"><p>Ajuda</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/usuario.png"><p>Usuário</p></a></div>'+
	 	'<div class="fav"><a><img src="/static/icons/ferramentas.png"><p>Ferramentas de sistema</p></a></div>'+
	'</div>';

	var favoriteMenu = Ext.create('Ext.panel.Panel', {
		layout: 'fit',
	  	renderTo: 'menu-favoritos',
      	  	collapsible: true,
        	title: 'Favoritos',
        	titleAlign: 'center',
		border: true,
		frame: true,
		html: fav_html
	});

});

