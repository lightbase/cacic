Ext.define('KitchenSink.controller.Main', {
    extend: 'Ext.app.Controller',
    requires: [
        'KitchenSink.view.*',
        'Ext.window.Window'
    ],

    stores: [
        'Companies',
        'Restaurants',
        'Files',
        'States'
    ],

    refs: [
        {
            ref: 'viewport',
            selector: 'viewport'
        },
        {
            ref: 'navigation',
            selector: 'navigation'
        },
        {
            ref: 'contentPanel',
            selector: 'contentPanel'
        },
        {
            ref: 'descriptionPanel',
            selector: 'descriptionPanel'
        },
        {
            ref: 'codePreview',
            selector: 'codePreview'
        }
    ],

    exampleRe: /^\s*\/\/\s*(\<\/?example>)\s*$/,

    init: function() {
        this.control({
            'navigation': {
                selectionchange: 'onNavSelectionChange'
            },
            'viewport': {
                afterlayout: 'afterViewportLayout'
            },
            'codePreview tool[type=maximize]': {
                click: 'onMaximizeClick'
            },
            'contentPanel': {
                resize: 'centerContent'
            }
        });
    },

    afterViewportLayout: function() {
        if (!this.navigationSelected) {
            var id = location.hash.substring(1),
                navigation = this.getNavigation(),
                store = navigation.getStore(),
                node;

            node = id ? store.getNodeById(id) : store.getRootNode().firstChild.firstChild;

            navigation.getSelectionModel().select(node);
            navigation.getView().focusNode(node);
            this.navigationSelected = true;
        }
    },

    onNavSelectionChange: function(selModel, records) {
        var record = records[0],
            text = record.get('text'),
            xtype = record.get('id'),
            alias = 'widget.' + xtype,
            contentPanel = this.getContentPanel(),
            cmp;

        if (xtype) { // only leaf nodes have ids
            contentPanel.removeAll(true);

            var className = Ext.ClassManager.getNameByAlias(alias);
            var ViewClass = Ext.ClassManager.get(className);
            var clsProto = ViewClass.prototype;
            if (clsProto.themes) {
                clsProto.themeInfo = clsProto.themes[Ext.themeName] || clsProto.themes.classic;
            }

            cmp = new ViewClass();
            contentPanel.add(cmp);
            if (cmp.floating) {
                cmp.show();
            } else {
                this.centerContent();
            }

            contentPanel.setTitle(text);

            document.title = document.title.split(' - ')[0] + ' - ' + text;
            location.hash = xtype;

            this.updateDescription(clsProto);

            if (clsProto.exampleCode) {
                this.updateCodePreview(clsProto.exampleCode);
            } else {
                this.updateCodePreviewAsync(clsProto, xtype);
            }
        }
    },
    
    onMaximizeClick: function(){
        var preview = this.getCodePreview(),
            code = preview.getEl().down('.prettyprint').dom.innerHTML;
        
        var w = new Ext.window.Window({
            autoShow: true,
            title: 'Code Preview',
            modal: true,
            cls: 'preview-container',
            autoScroll: true,
            html: '<pre class="prettyprint">' + code + '</pre>'
        });
        w.maximize();
    },

    processCodePreview: function (clsProto, text) {
        var me = this,
            lines = text.split('\n'),
            removing = false,
            keepLines = [],
            tempLines = [],
            n = lines.length,
            i, line;

        // Remove all "example" blocks as they are fluff.
        //
        for (i = 0; i < n; ++i) {
            line = lines[i];
            if (removing) {
                if (me.exampleRe.test(line)) {
                    removing = false;
                }
            } else if (me.exampleRe.test(line)) {
                removing = true;
            } else {
                tempLines.push(line);
            }
        }

        // Inline any themeInfo values to clarify the code.
        //
        if (clsProto.themeInfo) {
            var path = ['this', 'themeInfo'];

            function process (obj) {
                for (var name in obj) {
                    var value = obj[name];

                    path.push(name);

                    if (Ext.isPrimitive(value)) {
                        if (Ext.isString(value)) {
                            value = "'" + value + "'";
                        }
                        me.replaceValues(tempLines, path.join('.'), value);
                    } else {
                        process(value);
                    }

                    path.pop();
                }
            }

            process(clsProto.themeInfo);
        }

        // Remove any lines with remaining (unused) themeInfo. These properties will
        // be "undefined" for this theme and so are useless to the example.
        //
        for (i = 0, n = tempLines.length; i < n; ++i) {
            line = tempLines[i];
            if (line.indexOf('themeInfo') < 0) {
                keepLines.push(line);
            }
        }

        var code = keepLines.join('\n');
        code = Ext.htmlEncode(code);
        clsProto.exampleCode = code;
    },

    replaceValues: function (lines, text, value) {
        var n = lines.length,
            i, pos, line;

        for (i = 0; i < n; ++i) {
            line = lines[i];
            pos = line.indexOf(text);
            if (pos >= 0) {
                lines[i] = line.split(text).join(String(value));
            }
        }
    },

    updateCodePreview: function (text) {
        this.getCodePreview().update(
            '<pre id="code-preview-container" class="prettyprint">' + text + '</pre>'
        );
        prettyPrint();
    },

    updateCodePreviewAsync: function(clsProto, xtype) {
        var me = this,
            className = Ext.ClassManager.getNameByAlias('widget.' + xtype),
            path = className.replace(/\./g, '/').replace('KitchenSink', 'app') + '.js';

        if (!Ext.repoDevMode) {
            path = '../../../kitchensink/' + path;
        }

        Ext.Ajax.request({
            url: path,
            success: function(response) {
                me.processCodePreview(clsProto, response.responseText);
                me.updateCodePreview(clsProto.exampleCode);
            }
        });
    },

    updateDescription: function (clsProto) {
        var description = clsProto.exampleDescription,
            descriptionPanel = this.getDescriptionPanel();

        if (Ext.isArray(description)) {
            clsProto.exampleDescription = description = description.join('');
        }

        descriptionPanel.update(description);
    },

    centerContent: function() {
        var contentPanel = this.getContentPanel(),
            body = contentPanel.body,
            item = contentPanel.items.getAt(0),
            align = 'c-c',
            overflowX,
            overflowY,
            offsets;

        if (item) {
            overflowX = (body.getWidth() < (item.getWidth() + 40));
            overflowY = (body.getHeight() < (item.getHeight() + 40));

            if (overflowX && overflowY) {
                align = 'tl-tl',
                offsets = [20, 20];
            } else if (overflowX) {
                align = 'l-l';
                offsets = [20, 0];
            } else if (overflowY) {
                align = 't-t';
                offsets = [0, 20];
            }

            item.alignTo(contentPanel.body, align, offsets);
        }
    }
});
