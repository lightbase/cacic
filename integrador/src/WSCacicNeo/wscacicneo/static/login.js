Ext.require(['*']);
Ext.onReady(function() {

	var mainHeader = Ext.create('Ext.toolbar.Toolbar', {
        items: [
            '->',
            {xtype: 'text', text: 'SUPER GERENTE'},
            '->'
        ]
    });

	var login = Ext.widget({
	padding: '15px',
        xtype: 'form',
        layout: 'form',
        frame: true,
        width: 300,
        style: {
        	margin: '150px auto 150px auto'
        },
        items: [{
        	labelAlign: 'top',
            fieldLabel: 'Nome',
            name: 'nome',
            xtype: 'textfield',
            allowBlank: false
        },{
        	labelAlign: 'top',
            fieldLabel: 'Senha',
            name: 'senha',
            xtype: 'textfield',
            inputType: 'password',
            allowBlank: false
        }, {
            xtype: 'checkboxfield',
            name: 'lembrarsenha',
            boxLabel: 'Lembrar a minha senha'
        }],
        buttons: [{
            text: 'Entrar',
            handler: function() {
	     	window.location = 'home';
            }
        }]
    });
	
	var viewport = Ext.create('Ext.Viewport', {
        layout: {
            type: 'border',
            padding: 5
        },
        defaults: {
            split: true
        },
        items: [{
            region: 'north',
            split: true,
			items: mainHeader
        },{
            region: 'center',
			autoScroll: true,
	        style: {
	        	verticalAlign: 'center'
	        },
			items: login,
            minHeight: 80
        },{
            region: 'south',
			html: '<p align="right">Desenvolvido por:</p>' ,
            minHeight: 80
        }]
    });
});
