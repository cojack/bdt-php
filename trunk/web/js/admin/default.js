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
                  text: 'Cut',
                  iconCls: 'add16',
                  menu: [{text: 'Cut Menu Item'}]
            },{
                  text: 'Copy',
                  iconCls: 'add16'
            },{
                  text: 'Paste',
                  iconCls: 'add16',
                  menu: [{text: 'Paste Menu Item'}]
            },'-',{
                  text: 'Format',
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
               contentEl: 'west',
               title: 'Navigation',
               border: false,
               iconCls: 'nav' // see the HEAD section for style used
            }, {
               title: 'Settings',
               html: '<p>Some settings in here.</p>',
               border: false,
               iconCls: 'settings'
            }]
      },
      // in this instance the TabPanel is not wrapped by another panel
      // since no title is needed, this Panel is added directly
      // as a Container
      new Ext.TabPanel({
            region: 'center', // a center region is ALWAYS required for border layout
            deferredRender: false,
      })]
   });
   // get a reference to the HTML element with id "hideit" and add a click listener to it 
   Ext.get("hideit").on('click', function(){
      // get a reference to the Panel that was created with id = 'west-panel' 
      var w = Ext.getCmp('west-panel');
      // expand or collapse that Panel based on its collapsed property state
      w.collapsed ? w.expand() : w.collapse();
   });
});
