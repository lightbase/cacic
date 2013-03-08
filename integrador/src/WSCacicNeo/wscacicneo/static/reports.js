fakeHtml = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin placerat placerat convallis. Nullam diam nunc, auctor mollis adipiscing id, tristique in velit. Proin eu sodales ipsum. Nunc vel erat orci. Maecenas quis tortor sit amet elit interdum vehicula. Mauris vitae urna neque, in tincidunt mi. Praesent sapien neque, imperdiet in lobortis in, tempus ut dolor. Aliquam pretium eleifend diam sit amet mattis. In tincidunt fringilla nunc, in feugiat tellus dignissim in. Nam faucibus nisi vel metus pulvinar nec commodo sapien condimentum. Nullam rutrum, lacus a aliquam tincidunt, elit lectus dapibus felis, nec mattis mauris sapien sed ante. Suspendisse suscipit lorem et orci egestas sodales. Phasellus scelerisque fermentum est id lobortis.<br>Morbi eu mauris nibh, id faucibus tellus. ';

report1 = Ext.create('Ext.panel.Panel', {
	title: 'Relatório1',
	width: '75%',
	frame: true,
	html: fakeHtml,
	draggable: true,
	collapsible: true,
	style: {
		margin: '0px auto 15px auto'
	},
	tools: [{
		type: 'print',
		handler: function(){
		}
	}]
});

report2 = Ext.create('Ext.panel.Panel', {
	title: 'Relatório2',
	width: '75%',
	frame: true,
	html: fakeHtml  + fakeHtml,
	draggable: true,
	collapsible: true,
	border : true,
	style: {
		margin: '0px auto 15px auto'
	},
	tools: [{
		type: 'print',
		handler: function(){
		}
	}]
});

Ext.onReady(function(){

	Ext.create('Ext.Container', {
		padding: '15px',
		items: [report1, report2],
    	renderTo: 'widgets'
	});

});
