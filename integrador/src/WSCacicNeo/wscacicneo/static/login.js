var login = Ext.widget({
        xtype: 'form',
        layout: 'form',
        frame: true,
        border : false,
        fieldDefaults: {
            labelAlign: 'top',
        },
        items: [{
            xtype: 'textfield',
            name: 'nome',
            fieldLabel: 'Nome',
        }, {
            xtype: 'textfield',
            name: 'senha',
            inputType: 'password',
            fieldLabel: 'Senha'
        }, {
            xtype: 'checkboxfield',
            name: 'lembrasenha',
            boxLabel: 'Lembrar minha senha'
        }],

        buttons: [{
            text: 'Entrar',
            handler: function() {
                window.location = 'home';
            }
        }]
    });

Ext.onReady(function(){

        Ext.create('Ext.Container', {
                items: [login],
        renderTo: 'login'
		});

});
