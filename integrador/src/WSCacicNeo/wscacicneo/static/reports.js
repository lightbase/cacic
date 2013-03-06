
Ext.onReady(function(){

	fakeHtml = '<p>Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. Donec non enim in turpis pulvinar facilisis. Ut felis. Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus</p>';

  	Ext.create('Ext.Container', {
		padding: '0 0 0 15',
	 	html: '<h1>Relatórios</h1>',
		renderTo: 'macro-panel-header'
	});

	report1 = Ext.create('Ext.panel.Panel', {
		title: 'Relatório1',
	 	width: '400px',
		frame: true,
		html: fakeHtml,
		style: {
			margin: '0px auto 15px auto'
				},
		draggable: true,
		tools: [{
        	type: 'print',
        	handler: function(){
        	}
    	}]
	});

	report2 = Ext.create('Ext.panel.Panel', {
		title: 'Relatório2',
	 	width: '400px',
		frame: true,
		html: fakeHtml,
		draggable: true,
		style: {
		    margin: '0px auto 15px auto'
				},
		tools: [{
        	type: 'print',
        	handler: function(){
        	}
    	}]
	});

	Ext.create('Ext.Container', {
		padding: '15px',
		items: [report1, report2],
    	renderTo: 'macro-panel-content'
	});

});
