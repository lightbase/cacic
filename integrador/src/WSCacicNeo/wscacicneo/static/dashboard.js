
Ext.onReady(function(){

  	Ext.create('Ext.Container', {
		padding: '0 0 0 15',
	 	html: '<h1>Dashboard</h1>',
		renderTo: 'macro-panel-header'
	});

  	Ext.create('Ext.Container', {
		items: []
	});


	Ext.create('Ext.Container', {
		width: '100%',
    	renderTo: 'macro-panel-content',
		layout: {
			type: 'table',
			columns: 2
		},
		defaults: {
			bodyStyle: 'padding:20px',
			margin: '10px',
			height: 150,
			width: 200,
			draggable: true
		},
		style: {
			//padding: '0px 0px 0px auto',
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
			html: 'Cell C content',
			cellCls: 'highlight'
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
			width: 415,
			colspan: 2
		}]
	});

});
