// closure to avoid namespace collision
(function(){
    // creates the plugin
    tinymce.create('tinymce.plugins.mygallery', {
        // creates control instances based on the control's id.
        // our button's id is &quot;mygallery_button&quot;
        /*createControl : function(n, cm){
			return null;
		},*/
		createControl: function(n, cm) {
		        switch (n) {
		            case 'mygallery_button':
		                var mlb = cm.createListBox('mygallery_button', {
		                     title : 'Shortcodes',
		                     onselect : function(v) {
								switch (v){
									case 'youtube':
									case 'vimeo':
									case 'video':
										tinyMCE.activeEditor.selection.setContent('[' + v + ']' + tinyMCE.activeEditor.selection.getContent() + '[/' + v + ']');
										break;
									case 'half':
										tinyMCE.activeEditor.selection.setContent('[half][/half] [half position="last"][/half]');
										break;
									case 'third':
										tinyMCE.activeEditor.selection.setContent('[third][/third] [third][/third] [third position="last"][/third]');
										break;
									case 'fourth':
										tinyMCE.activeEditor.selection.setContent('[fourth][/fourth] [fourth][/fourth] [fourth][/fourth] [fourth position="last"][/fourth]');
										break;
									case 'fifth':
										tinyMCE.activeEditor.selection.setContent('[fifth][/fifth] [fifth][/fifth] [fifth][/fifth] [fifth][/fifth] [fifth position="last"][/fifth]');
										break;
									case 'sixth':
										tinyMCE.activeEditor.selection.setContent('[sixth][/sixth] [sixth][/sixth] [sixth][/sixth] [sixth][/sixth] [sixth][/sixth] [sixth position="last"][/sixth]');
										break;
									case 'blockquote':
										tinyMCE.activeEditor.selection.setContent('[blockquote align="right"]' + tinyMCE.activeEditor.selection.getContent() + '[/blockquote]');
										break;
									case 'button':
										tinyMCE.activeEditor.selection.setContent('[button url=""]' + tinyMCE.activeEditor.selection.getContent() + '[/button]');
										break;
									case 'gmap':
										tinyMCE.activeEditor.selection.setContent('[gmap lat="" lng=""]');
										break;
									case 'tab':
										tinyMCE.activeEditor.selection.setContent('[tabbed] [tab title="Tab 1"][/tab] [/tabbed]');
										break;
									case 'accordion':
										tinyMCE.activeEditor.selection.setContent('[accordion] [section title="Section 1"][/section] [/accordion]');
										break;
								}
		                     }
		                });

		                // Add some values to the list box
		                mlb.add('YouTube', 'youtube');
		                mlb.add('Vimeo', 'vimeo');
		                mlb.add('Self-hosted Video', 'video');
						mlb.add('Google Map', 'gmap');
						mlb.add('Blockquote', 'blockquote');
						mlb.add('Button', 'button');
						mlb.add('Tabbed Content', 'tab');
						mlb.add('Accordion Content', 'accordion');
						mlb.add('Columns: Half', 'half');
						mlb.add('Columns: Third', 'third');
						mlb.add('Columns: Fourth', 'fourth');
						mlb.add('Columns: Fifth', 'fifth');
						mlb.add('Columns: Sixth', 'sixth');

		                // Return the new listbox instance
		                return mlb;
		        }

		        return null;
		    }
    });
 
    // registers the plugin.
    tinymce.PluginManager.add('mygallery', tinymce.plugins.mygallery);
})();