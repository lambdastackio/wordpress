(function() {

	jQuery( function() {
		tinymce.PluginManager.add('tdv_shortcodes', function(editor, url) {
			editor.addButton('tdv_shortcodes_dropdownbutton', {
				title: 'Repute Shortcodes',
				image: '../wp-content/plugins/tdv-shortcodes/img/repute-icon.png',
				type: 'menubutton',
				menu: [
					{
						text: 'Column',
						onclick: function() {
							editor.insertContent(column);
						}
					},
					{
						text: 'Section',
						onclick: function() {
							editor.insertContent(section);
						}
					},
					{
						text: 'Boxed Content',
						onclick: function() {
							editor.insertContent(boxedContent);
						}
					},
					{
						text: 'Main Features',
						onclick: function() {
							editor.insertContent(mainFeatures);
						}
					},
					{
						text: 'Call To Action',
						onclick: function() {
							editor.insertContent(cta);
						}
					},
					{
						text: 'Team',
						onclick: function() {
							editor.insertContent(team);
						}
					},
					{
						text: 'Testimonial',
						onclick: function() {
							editor.insertContent(testimonial);
						}
					},
					{
						text: 'Tabs',
						menu: [
							{
								text: 'Default',
								onclick: function() {
									editor.insertContent(tabs);
								}
							},
							{
								text: 'Line Content Top',
								onclick: function() {
									editor.insertContent(tabsLineTop);
								}
							},
							{
								text: 'Line Content Bottom',
								onclick: function() {
									editor.insertContent(tabsLineBottom);
								}
							}
						]
					},
					{
						text: 'Alert',
						onclick: function() {
							editor.insertContent(alert);
						}
					},
					{
						text: 'Button',
						onclick: function() {
							editor.insertContent(button);
						}
					},
					{
						text: 'Video',
						onclick: function() {
							editor.insertContent(video);
						}
					},
					{
						text: 'Social Link',
						onclick: function() {
							editor.insertContent(socialLink);
						}
					},
					{
						text: 'Font Icon',
						onclick: function() {
							editor.insertContent(fontIcon);
						}
					},
					{
						text: 'Map',
						onclick: function() {
							editor.insertContent(map);
						}
					},

				]
			});
		});

		// bootstrap columns
		var column = '[tdv_row]\
					[tdv_col width="1/2"]content[/tdv_col]\
					[tdv_col width="1/2"]content[/tdv_col]\
					[/tdv_row]';

		// section
		var section = '[tdv_section section_title="Section Heading Title"]section content[/tdv_section]';

		// boxed content
		var boxedContent = '[tdv_boxed_content title="Content Title" icon="fa fa-info"]content[/tdv_boxed_content]';

		// main feature items
		var mainFeatures = '[tdv_main_features][tdv_row]\
							[tdv_col width="1/3"][tdv_icon class="fa fa-info"][tdv_feature_heading]FEATURE 1[/tdv_feature_heading][/tdv_col]\
							[tdv_col width="1/3"][tdv_icon class="fa fa-info"][tdv_feature_heading]FEATURE 2[/tdv_feature_heading][/tdv_col]\
							[tdv_col width="1/3"][tdv_icon class="fa fa-info"][tdv_feature_heading]FEATURE 3[/tdv_feature_heading][/tdv_col]\
							[/tdv_row][/tdv_main_features]';

		// call to action
		var cta = '[tdv_call_to_action url="#" button_text="BUTTON TEXT" title=""]content[/tdv_call_to_action]'

		// team, needs plugin activated
		var team = '[tdv_team]';

		// testimonial, needs plugin activated
		var testimonial = '[tdv_testimonial]';

		// default bootstrap tabs
		var tabs = '[tdv_tabs titles="Tab A, Tab B, Tab C" ids="tabA, tabB, tabC"][/tdv_tabs]\
							[tdv_tab_content][tdv_tab_pane id="tabA"]Content A[/tdv_tab_pane][tdv_tab_pane id="tabB"]Content B[/tdv_tab_pane][tdv_tab_pane id="tabC"]Content C[/tdv_tab_pane][/tdv_tab_content]';
		// content on top
		var tabsLineBottom = '[tdv_tabs titles="Tab A, Tab B, Tab C" ids="tabA, tabB, tabC" type="line-top"][/tdv_tabs]\
							[tdv_tab_content][tdv_tab_pane id="tabA"]Content A[/tdv_tab_pane][tdv_tab_pane id="tabB"]Content B[/tdv_tab_pane][tdv_tab_pane id="tabC"]Content C[/tdv_tab_pane][/tdv_tab_content]';

		// content on bottom
		var tabsLineTop = '[tdv_tab_content][tdv_tab_pane id="tabA"]Content A[/tdv_tab_pane][tdv_tab_pane id="tabB"]Content B[/tdv_tab_pane][tdv_tab_pane id="tabC"]Content C[/tdv_tab_pane][/tdv_tab_content]\
							[tdv_tabs titles="Tab A, Tab B, Tab C" ids="tabA, tabB, tabC" type="line-bottom"][/tdv_tabs]';

		// alert
		var alert = '[tdv_alert context="info" title="Alert Title" dismissible="true"]Alert content[/tdv_alert]';

		// button
		var button = '[tdv_button context="default" style="" rounded="" size="" icon_class=""]Button Text[/tdv_button]';

		// video embed
		var video = '[tdv_video type="youtube" url="" width="" height=""]';

		// social link
		var socialLink = '[tdv_social_link url="#" class="" size=""]';

		// font icon
		var fontIcon = '[tdv_icon class="" size=""]';

		// google map
		var map = '[tdv_gmap address="Google New York, 76 Ninth Ave, New York, NY, USA"]';

	});

})()