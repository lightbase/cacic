Ext.onReady(function() {

	var links_html = '<a href="relatorios"></a>';	


	var mainMenu = Ext.create('Ext.menu.Menu', {
	  	renderTo: 'menu-principal',
	  	floating: false,
        collapsible: true,
		border: true,
		frame: true,
		title: 'Home',
        titleAlign: 'center',
		items: [
			{ xtype: 'box',
  			  autoEl: {
    			  tag: 'a',
    			  href: 'home',
    			  cn: 'Home'
  					}},
			{xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'dashboard',
    			cn: 'Dashboard'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'diagnostico',
    			cn: 'Diagnosticos'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'administracao',
    			cn: 'Administraçao'
  					}},
			{xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'pagina1',
    			cn: 'Pagina1'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'pagina2',
    			cn: 'Pagina2'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'pagina3',
    			cn: 'Pagina3'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'pagina4',
    			cn: 'Pagina4'
  					}},
			{ xtype: 'box',
  			autoEl: {
    			tag: 'a',
    			href: 'pagina5',
    			cn: 'Pagina5'
  					}},
			{ xtype: 'box',
  			autoEl: {	
    			tag: 'a',
    			href: 'pagina6',
    			cn: 'Pagina6'
  					}}]
	});

   var	fav_html = '<div id="favoriteItems">'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/estatisticas.png">Estatísticas</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/busca.png">Busca</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/downloads.png">Downloads</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/relatorios.png">Relatórios</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/mensagens.png">Mensagens</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/ajuda.png">Ajuda</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/usuario.png">Usuário</a></div>'+
	 	'<div class="fav"><a href="relatorios"><img src="static/icons/ferramentas.png">Ferramentas de sistema</a></div>'+
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

