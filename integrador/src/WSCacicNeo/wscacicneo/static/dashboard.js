Ext.onReady(function(){

	Ext.create('Ext.Panel', {
		layout: 'fit',
		height: '150px',
		style: {margin: '15px'},
	 	frame: true,
	 	draggable: true,
	 	title: 'menu1',
		titleAlign: 'center',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu2',
		titleAlign: 'center',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu3',
		titleAlign: 'center',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu4',
		titleAlign: 'center',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu5',
		titleAlign: 'center',
    	renderTo: 'g3'
	});

});
