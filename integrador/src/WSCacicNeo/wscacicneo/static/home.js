var store = Ext.create('Ext.data.JsonStore', {
    fields: ['name', 'data'],
    data: [
        { 'name': 'metric one',   'data': 10 },
        { 'name': 'metric two',   'data':  7 },
        { 'name': 'metric three', 'data':  5 },
        { 'name': 'metric four',  'data':  2 },
        { 'name': 'metric five',  'data': 27 }
    ]
});

var chart = Ext.create('Ext.chart.Chart', {
    width: 500,
    height: 350,
    animate: true,
    store: store,
    theme: 'Base:gradients',
    series: [{
        type: 'pie',
        angleField: 'data',
        showInLegend: true,
        tips: {
            trackMouse: true,
            width: 140,
            height: 28,
            renderer: function(storeItem, item) {
                // calculate and display percentage on hover
                var total = 0;
                store.each(function(rec) {
                    total += rec.get('data');
                });
                this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get('data') / total * 100) + '%');
            }
        },
        highlight: {
            segment: {
                margin: 20
            }
        },
        label: {
            field: 'name',
            display: 'rotate',
            contrast: true,
            font: '18px Arial'
        }
    }]
});


Ext.onReady(function(){

  	Ext.create('Ext.Container', {
		padding: '15px',
	 	html: '<h1>Home</h1>',
		renderTo: 'macro-panel-header'
	});

	Ext.create('Ext.panel.Panel', {
		height: '500px',
		padding: '15px',
		title: 'Widgets',
		draggable: true,
    		renderTo: 'macro-panel-content',
		items: chart
	});

});
