Ext.onReady(function(){

	var painelContent=
			"Opção 1" + "<br>" +
			"Opção 2" + "<br>" +
			"Opção 3" + "<br>" +
			"Opção 4" + "<br>" +
			"Opção 5" + "<br>" +
			"Opção 6" + "<br>" +
			"Opção 7" + "<br>" +
			"Opção 8" + "<br>" +
			"<hr>"    +
			"Opção 1" + "<br>" +
			"Opção 2" + "<br>" +
			"Opção 3" + "<br>" +
			"Opção 4" + "<br>" +
			"Opção 5" + "<br>" +
			"Opção 6" + "<br>" +
			"Opção 7" + "<br>" +
			"Opção 8" + "<br>" +
			"<hr>"    +
			"Opção 1" + "<br>" +
			"Opção 2" + "<br>" +
			"Opção 3" + "<br>" +
			"Opção 4" ;


  	Ext.create('Ext.Container', {
		padding: '0 0 0 15',
	 	html: '<h1>Administração geral</h1>',
		renderTo: 'macro-panel-header'
	});

	Ext.create('Ext.panel.Panel', {
		padding: '15px',
		title: 'Painel de Administração',
		frame: true,
		collapsible: true,
		draggable: true,
		border : true,
		width: '50%',
        style: {
			marginLeft: 'auto',
		    marginRight: 'auto'
        },
    	renderTo: 'macro-panel-content',
		html: painelContent
	});

});
