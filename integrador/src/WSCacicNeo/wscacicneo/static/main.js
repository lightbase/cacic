
Ext.onReady(function() {
	var mainHeader = Ext.create('Ext.toolbar.Toolbar', {
	  	renderTo: 'header',
        items: [
            {xtype: 'text', text: 'SUPER GERENTE'},
            '->',
            {xtype: 'textfield'},
            {xtype: 'text', text: 'usuário: aaaa'},
            {xtype: 'text', text: 'nível:aaaaaaa'},
            '-',
            {
				xtype: 'button',
				text: 'logoff',
				handler: function (){
					window.location = 'login';
				}
			}

        ]
    });

	var menuItems = Ext.create('Ext.menu.Menu', {
		floating: false,  // usually you want this set to True (default)
		items: [{
			text: 'Opção 1'
		},{
			text: 'Opção 2'
		},{
			text: 'Opção 3'
		},{
			text: 'Opção 4'
		},{
			text: 'Opção 5'
		},{
			text: 'Opção 6'
		},{
			text: 'Opção 7'
		},{
			text: 'Opção 8'
		},{
			text: 'Opção 9'
		},{
			text: 'Opção 10'
		}]
	});

	var mainMenu = Ext.create('Ext.panel.Panel', {
	  	renderTo: 'menu-principal',
        collapsible: true,
		title: 'Menu Principal',
        titleAlign: 'center',
		items: menuItems
	});


	favoriteItems = Ext.create('Ext.panel.Panel', {
		tbar: [{
			xtype: 'buttongroup',
			width: '100%',
			defaults: {buttonAlign : 'center'},
			columns: 3,
			items: [{
				text: 'Estatísticas',
				scale: 'large',
				flex: 1
			},{
				text: 'Busca',
				scale: 'large',
				flex: 1
			},{
				text: 'Downloads',
				scale: 'large',
				flex: 1
			},{
				text: 'Relatórios',
				scale: 'large',
				flex: 1,
				handler: function() {
					window.location = 'relatorios';
				}
			},{
				text: 'Mensagens',
				scale: 'large',
				flex: 1
			},{
				text: 'Ajuda',
				scale: 'large',
				flex: 1
			},{
				text: 'Usuário',
				scale: 'large',
				flex: 1
			},{
				text: 'Ferramentas<br>de sistema',
				scale: 'large',
				flex: 1
			}]
		}]
	});

	var favoriteMenu = Ext.create('Ext.panel.Panel', {
	  	renderTo: 'menu-favoritos',
        collapsible: true,
        title: 'Favoritos',
        titleAlign: 'center',
		items: favoriteItems
	});

});

