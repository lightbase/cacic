Ext.onReady(function(){

	Ext.create('Ext.Panel', {
		height: '150px',
		style: {margin: '15px'},
	 	frame: true,
	 	draggable: true,
	 	title: 'menu1',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu2',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu3',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu4',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu5',
    	renderTo: 'g3'
	});

});

/*Ext.create('Ext.Container', {
    	renderTo: 'widgets',
		layout: {
			type: 'table',
			columns: 2
		},
		defaults: {
			bodyStyle: 'padding:20px',
			margin: '10px',
			height: 150,
			draggable: true
		},
		items: [{
			xtype: 'panel',
			title: 'menu1',
			frame: true,
			html: 'Cell A content'
		},{
			xtype: 'panel',
			title: 'menu2',
			frame: true,
			html: 'Cell B content'
		},{
			xtype: 'panel',
			title: 'menu3',
			frame: true,
			html: 'Cell C content'
		},{
			xtype: 'panel',
			title: 'menu4',
			frame: true,
			html: 'Cell D content'
		},{
			xtype: 'panel',
			title: 'menu5',
			frame: true,
			html: 'Cell E content',
			//width: 415,
			colspan: 2
		}]
	});*/