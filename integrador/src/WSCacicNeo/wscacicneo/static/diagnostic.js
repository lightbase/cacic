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

relatorio = Ext.create('Ext.panel.Panel', {
	title: 'Relatórios',
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
		items: [relatorio],
    	renderTo: 'widgets'
	});		
	
});