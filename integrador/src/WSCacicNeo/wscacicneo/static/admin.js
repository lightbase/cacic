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

admin = Ext.create('Ext.panel.Panel', {
		title: 'Painel de Administração',
	 	width: '75%',
		frame: true,
		collapsible: true,
		draggable: true,
		border : true,
		style: {
			margin: '0px auto 15px auto'
				},
		html: painelContent
	});

Ext.onReady(function(){

		Ext.create('Ext.Container', {
			padding: '15px',
			items: [admin],
	    	renderTo: 'widgets'
		});		
		
});
