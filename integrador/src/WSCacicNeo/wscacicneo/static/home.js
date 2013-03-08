var store = Ext.create('Ext.data.JsonStore', {
    fields: ['name', 'data'],
    data: [
        { 'name': 'dado1', 'data':  2 },
        { 'name': 'dado2', 'data':  2 },
        { 'name': 'dado3', 'data':  4 },
        { 'name': 'dado4', 'data':  8 }
    ]
});

var chart = Ext.create('Ext.chart.Chart', {
    width: 500,
    height: 350,
    animate: true,
    store: store,
    theme: 'Base:gradients',
    shadow: true,
    legend: {
        position: 'right'
    },
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

widget = Ext.create('Ext.panel.Panel', {
	title: 'Widgets',
	width: '75%',
	frame: true,
	draggable: true,
	collapsible: true,
	border : true,
	style: {
		"text-align": 'center',
		margin: '0px auto 15px auto'
	},
	items: chart
});

Ext.onReady(function(){


	Ext.create('Ext.Container', {
		padding: '15px',
		items: [widget],
    	renderTo: 'widgets'
	});

});
