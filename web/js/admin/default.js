Ext.onReady(function(){
   // NOTE: This is an example showing simple state management. During development,
   // it is generally best to disable state management as dynamically-generated ids
   // can change across page loads, leading to unpredictable results.  The developer
   // should ensure that stable state ids are set for stateful components in real apps.
   Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

   var viewport = new Ext.Viewport({
      layout: 'border',
      items: [{
            region: 'north',
            xtype: 'panel',
            tbar: [{
               xtype:'splitbutton',
               text: 'Menu Button',
               iconCls: 'add16',
               menu: [{text: 'Menu Button 1'}]
            },'-',{
               xtype:'splitbutton',
               id: 'user-id-tbar',
               text: 'Użytkownicy',
               iconCls: 'users',
               module: 'user',
               handler: function(btn, e) { core.loadModule(btn); },
               menu: [{
                  text: 'Dodaj nowego',
                  iconCls: 'user-add',
                  handler: function(btn, e) { 
                     obj = Ext.getCmp('user-id-tbar');
                     core.loadModule(obj);
                     userModule.registerPanel();
                  },
               },{
                  text: 'Wyszukaj'
               }]
            },{
               xtype: 'splitbutton',
               id: 'group-id-tbar',
               text: 'Grupy',
               iconCls: 'groups',
               module: 'group',
               handler: function(btn, e) { core.loadModule(btn); },
               menu: [{
                  text: 'Dodaj grupe',
                  iconCls: 'group-add',
               }]
            },'->',{
               text: 'Zalogowany',
               iconCls: 'add16'
            }]
      },{
            region: 'west',
            id: 'west-panel', // see Ext.getCmp() below
            title: 'West',
            split: true,
            width: 200,
            minSize: 175,
            maxSize: 400,
            collapsible: true,
            margins: '0 0 0 5',
            layout: {
               type: 'accordion',
               animate: true
            },
            items: [{
               title: 'Navigation',
               border: false,
               iconCls: 'nav' // see the HEAD section for style used
            }, {
               title: 'Settings',
               border: false,
               iconCls: 'settings'
            }]
      },
      // in this instance the TabPanel is not wrapped by another panel
      // since no title is needed, this Panel is added directly
      // as a Container
      new Ext.TabPanel({
            region: 'center', // a center region is ALWAYS required for border layout
            id: 'center-panel',
            deferredRender: false,
      })]
   });

});
