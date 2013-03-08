Ext.onReady(function(){

	Ext.create('Ext.Panel', {
		layout: 'fit',
		height: '150px',
		style: {margin: '15px'},
	 	frame: true,
	 	draggable: true,
	 	title: 'menu1',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu2',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu3',
    	renderTo: 'g1'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu4',
    	renderTo: 'g2'
	});

	Ext.create('Ext.Panel', {
		layout: 'fit',
		style: {margin: '15px'},
		height: '150px',
	 	frame: true,
	 	draggable: true,
	 	title: 'menu5',
    	renderTo: 'g3'
	});

});
