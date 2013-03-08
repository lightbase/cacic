
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
        collapsible: true,
		border: true,
		frame: true,
		title: 'Menu Principal',
        titleAlign: 'center',
		items: menuItems
	});

	fav_html = '<div id="favoriteItems">'+
	 	'<div id="fav1" class="fav"><a>estatísticas</a></div>'+
	 	'<div id="fav2" class="fav"><a href="diagnostico">diagnóstico</a></div>'+
	 	'<div id="fav3" class="fav"><a>downloads</a></div>'+
	 	'<div id="fav4" class="fav"><a href="relatorios">relatórios</a></div>'+
	 	'<div id="fav5" class="fav"><a>mensagens</a></div>'+
	 	'<div id="fav6" class="fav"><a>ajuda</a></div>'+
	 	'<div id="fav7" class="fav"><a>usuário</a></div>'+
	 	'<div id="fav8" class="fav"><a>ferramentas do sistema</a></div>'+
	'</div>';

	var favoriteMenu = Ext.create('Ext.panel.Panel', {
	  	renderTo: 'menu-favoritos',
        collapsible: true,
        title: 'Favoritos',
        titleAlign: 'center',
		border: true,
		frame: true,
		html: fav_html
	});

});

