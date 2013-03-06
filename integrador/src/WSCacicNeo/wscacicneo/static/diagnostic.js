Ext.onReady(function(){

	var painelContent=
			"Diagnóstico 1" + "<br>" +
			"Diagnóstico 2" + "<br>" +
			"Diagnóstico 3" + "<br>" +
			"Diagnóstico 4" + "<br>" +
			"Diagnóstico 5" + "<br>" +
			"Diagnóstico 6" + "<br>" +
			"Diagnóstico 7" + "<br>" +
			"Diagnóstico 8" + "<br>" +
			"<hr>"    +
			"Diagnóstico 1" + "<br>" +
			"Diagnóstico 2" + "<br>" +
			"Diagnóstico 3" + "<br>" +
			"Diagnóstico 4" + "<br>" +
			"Diagnóstico 5" + "<br>" +
			"Diagnóstico 6" + "<br>" +
			"Diagnóstico 7" + "<br>" +
			"Diagnóstico 8" + "<br>" +
			"<hr>"    +
			"Diagnóstico 1" + "<br>" +
			"Diagnóstico 2" + "<br>" +
			"Diagnóstico 3" + "<br>" +
			"Diagnóstico 4" + "<br>" +
			"Diagnóstico 5" ;


  	Ext.create('Ext.Container', {
		padding: '0 0 0 15',
	 	html: '<h1>Diagnóstico de gerentes</h1>',
		renderTo: 'macro-panel-header'
	});

	Ext.create('Ext.panel.Panel', {
		padding: '15px',
		title: 'Relatórios',
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
