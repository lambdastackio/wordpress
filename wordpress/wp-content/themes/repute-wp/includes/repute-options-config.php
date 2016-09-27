<?php 
	/**
	 * ReduxFramework Config File
	 * For full documentation, please visit: http://docs.reduxframework.com/
	 */

	if ( ! class_exists( 'Redux' ) ) {
		return;
	}


	// This is your option name where all the Redux data is stored.
	$opt_name = "repute-options";

	/*
	 *
	 * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
	 *
	 */

	$sampleHTML = '';
	if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
		Redux_Functions::initWpFilesystem();

		global $wp_filesystem;

		$sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
	}

	// Background Patterns Reader
	$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
	$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
	$sample_patterns      = array();

	if ( is_dir( $sample_patterns_path ) ) {

		if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
			$sample_patterns = array();

			while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

				if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
					$name              = explode( '.', $sample_patterns_file );
					$name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
					$sample_patterns[] = array(
						'alt' => $name,
						'img' => $sample_patterns_url . $sample_patterns_file
					);
				}
			}
		}
	}

	/**
	 * ---> SET ARGUMENTS
	 * All the possible arguments for Redux.
	 * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
	 * */

	$theme = wp_get_theme(); // For use with some settings. Not necessary.

	$args = array(
		// TYPICAL -> Change these values as you need/desire
		'opt_name'             => $opt_name,
		// This is where your data is stored in the database and also becomes your global variable name.
		'display_name'         => $theme->get( 'Name' ),
		// Name that appears at the top of your panel
		'display_version'      => $theme->get( 'Version' ),
		// Version that appears at the top of your panel
		'menu_type'            => 'menu',
		//Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
		'allow_sub_menu'       => true,
		// Show the sections below the admin menu item or not
		'menu_title'           => __( 'Repute Options', 'repute-backend' ),
		'page_title'           => __( 'Repute Options', 'repute-backend' ),
		// You will need to generate a Google API key to use this feature.
		// Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
		'google_api_key'       => '',
		// Set it you want google fonts to update weekly. A google_api_key value is required.
		'google_update_weekly' => false,
		// Must be defined to add google fonts to the typography module
		'async_typography'     => true,
		// Use a asynchronous font on the front end or font string
		//'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
		'admin_bar'            => true,
		// Show the panel pages on the admin bar
		'admin_bar_icon'       => 'dashicons-admin-generic',
		// Choose an icon for the admin bar menu
		'admin_bar_priority'   => 50,
		// Choose an priority for the admin bar menu
		'global_variable'      => '',
		// Set a different name for your global variable other than the opt_name
		'dev_mode'             => false,
		// Show the time the page took to load, etc
		'update_notice'        => true,
		// If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
		'customizer'           => true,
		// Enable basic customizer support
		//'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
		//'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

		// OPTIONAL -> Give you extra features
		'page_priority'        => null,
		// Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
		'page_parent'          => 'themes.php',
		// For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
		'page_permissions'     => 'manage_options',
		// Permissions needed to access the options panel.
		'menu_icon'            => '',
		// Specify a custom URL to an icon
		'last_tab'             => '',
		// Force your panel to always open to a specific tab (by id)
		'page_icon'            => 'icon-themes',
		// Icon displayed in the admin panel next to your menu_title
		'page_slug'            => '',
		// Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
		'save_defaults'        => true,
		// On load save the defaults to DB before user clicks save or not
		'default_show'         => false,
		// If true, shows the default value next to each field that is not the default value.
		'default_mark'         => '',
		// What to print by the field's title if the value shown is default. Suggested: *
		'show_import_export'   => true,
		// Shows the Import/Export panel when not used as a field.

		// CAREFUL -> These options are for advanced use only
		'transient_time'       => 60 * MINUTE_IN_SECONDS,
		'output'               => true,
		// Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
		'output_tag'           => true,
		// Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
		// 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

		// FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
		'database'             => '',
		// possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
		'use_cdn'              => true,
		// If you prefer not to use the CDN for Select2, Ace Editor, and others, you may download the Redux Vendor Support plugin yourself and run locally or embed it in your code.

		// HINTS
		'hints'                => array(
			'icon'          => 'el el-question-sign',
			'icon_position' => 'right',
			'icon_color'    => 'lightgray',
			'icon_size'     => 'normal',
			'tip_style'     => array(
				'color'   => 'red',
				'shadow'  => true,
				'rounded' => false,
				'style'   => '',
			),
			'tip_position'  => array(
				'my' => 'top left',
				'at' => 'bottom right',
			),
			'tip_effect'    => array(
				'show' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'mouseover',
				),
				'hide' => array(
					'effect'   => 'slide',
					'duration' => '500',
					'event'    => 'click mouseleave',
				),
			),
		)
	);

	// ADMIN BAR LINKS -> Setup custom links in the admin bar menu as external items.
	$args['admin_bar_links'][] = array(
		'id'    => 'redux-docs',
		'href'  => 'http://docs.reduxframework.com/',
		'title' => __( 'Documentation', 'repute-backend' ),
	);

	$args['admin_bar_links'][] = array(
		//'id'    => 'redux-support',
		'href'  => 'https://github.com/ReduxFramework/redux-framework/issues',
		'title' => __( 'Support', 'repute-backend' ),
	);

	$args['admin_bar_links'][] = array(
		'id'    => 'redux-extensions',
		'href'  => 'reduxframework.com/extensions',
		'title' => __( 'Extensions', 'repute-backend' ),
	);

	// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
	$args['share_icons'][] = array(
		'url'   => 'https://www.facebook.com/thedevelovers',
		'title' => 'Like us on Facebook',
		'icon'  => 'el el-facebook'
	);
	$args['share_icons'][] = array(
		'url'   => 'http://twitter.com/thedevelovers',
		'title' => 'Follow us on Twitter',
		'icon'  => 'el el-twitter'
	);
	
	// Panel Intro text -> before the form
	if ( ! isset( $args['global_variable'] ) || $args['global_variable'] !== false ) {
		if ( ! empty( $args['global_variable'] ) ) {
			$v = $args['global_variable'];
		} else {
			$v = str_replace( '-', '_', $args['opt_name'] );
		}
		$args['intro_text'] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'repute-backend' ), $v );
	} else {
		$args['intro_text'] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'repute-backend' );
	}

	// Add content after the form.
	$args['footer_text'] = __( '<p>A theme by <a href="https://themeineed.com/" target="_blank">The Develovers</a></p>', 'repute-backend' );

	Redux::setArgs( $opt_name, $args );

	/*
	 * ---> END ARGUMENTS
	 */


	/*
	 * ---> START HELP TABS
	 */

	$tabs = array(
		array(
			'id'      => 'redux-help-tab-1',
			'title'   => __( 'Theme Information 1', 'repute-backend' ),
			'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'repute-backend' )
		),
		array(
			'id'      => 'redux-help-tab-2',
			'title'   => __( 'Theme Information 2', 'repute-backend' ),
			'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'repute-backend' )
		)
	);
	//Redux::setHelpTab( $opt_name, $tabs );

	// Set the help sidebar
	$content = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'repute-backend' );
	//Redux::setHelpSidebar( $opt_name, $content );


	/*
	 * <--- END HELP TABS
	 */


	/*
	 *
	 * ---> START SECTIONS
	 *
	 */

	/*

		As of Redux 3.5+, there is an extensive API. This API can be used in a mix/match mode allowing for


	 */

	// -> START Basic Fields
	Redux::setSection( $opt_name, array(
		'title'            => __( 'Basic Setup', 'repute-backend' ),
		'id'               => 'basic-setup',
		'desc'             => __( 'Basic theme setup.', 'repute-backend' ),
		'customizer_width' => '400px',
		'icon'             => 'el el-home',
		'fields'           => array(
			array(
				'id'       => 'rpt-logo',
				'type'     => 'media',
				'url'      => true,
				'title'    => __( 'Upload Logo', 'repute-backend' ),
				'compiler' => 'true',
			),
			array(
				'id'       => 'rpt-favicon',
				'type'     => 'media',
				'url'      => true,
				'title'    => __( 'Upload Favicon', 'repute-backend' ),
				'compiler' => 'true',
				'desc'     => __( 'Dimension: 20px x 20px', 'repute-backend' ),
			),
			array(
				'id'       => 'rpt-layout',
				'type'     => 'radio',
				'title'    => __( 'Select Layout Option', 'repute-backend' ),
				'options'  => array(
					'layout-wide' => 'Wide',
					'layout-boxed' => 'Boxed',
				),
				'default'  => 'layout-wide'
			),
			array(
				'id'       => 'rpt-navigation',
				'type'     => 'radio',
				'title'    => __( 'Select Navigation Option', 'repute-backend' ),
				'options'  => array(
					'nav-default' => 'Default',
					'nav-fixed' => 'Fixed',
					'nav-auto-hiding' => 'Auto Hiding',
					'nav-fixed-shrink' => 'Fixed & Shrink',
				),
				'default'  => 'nav-default'
			),
			array(
				'id'       => 'rpt-copyright',
				'type'     => 'editor',
				'title'    => __( 'Copyright Info', 'repute-backend' ),
				'default'  => '&copy; Repute 2015. All rights reserved.',
			),
			array(
				'id'       => 'rpt-custom-css',
				'type'     => 'ace_editor',
				'title'    => __( 'Custom CSS Code', 'repute-backend' ),
				'subtitle' => __( 'Paste your custom CSS code here.', 'repute-backend' ),
				'mode'     => 'css',
				'theme'    => 'monokai',
			),
			array(
				'id'       => 'rpt-tracking-code',
				'type'     => 'ace_editor',
				'title'    => __( 'Tracking Code', 'repute-backend' ),
				'subtitle' => __( 'Paste your analytics tracking code here', 'repute-backend' ),
				'mode'     => 'javascript',
				'theme'    => 'chrome',
			),
		)
	) );

	Redux::setSection( $opt_name, array(
		'title'            => __( 'Colors', 'repute-backend' ),
		'id'               => 'colors-setup',
		'desc'             => __( 'Theme color setup.', 'repute-backend' ),
		'customizer_width' => '400px',
		'icon'             => 'el el-brush',
		'fields'           => array(
			array(
				'id'       => 'rpt-skin',
				'type'     => 'button_set',
				'title'    => __( 'Theme skin', 'repute-backend' ),
				'subtitle' => __( 'Select your theme color scheme', 'repute-backend' ),
				'options'  => array(
					'' => '<span style="background:#406da4;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'lightgreen' => '<span style="background:#8AA026;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'orange' => '<span style="background:#FF4500;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'deepskyblue' => '<span style="background:#00BFFF;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'goldenrod' => '<span style="background:#DAA520;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'indianred' => '<span style="background:#CD5C5C;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'seagreen' => '<span style="background:#3F7577;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'slategray' => '<span style="background:#708090;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
					'brown' => '<span style="background:#CD853F;width:15px;height:15px;display:inline-block;position:relative;top:3px; border: 1px solid #fff;"></span>',
				),
				'default'  => ''
			),
			array(
				'id'       => 'rpt-header-color',
				'type'     => 'color',
				'title'    => __( 'Header Background Color', 'repute-backend' ),
				'subtitle' => __( 'Background color of header (navbar)', 'repute-backend' ),
				'default'  => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-header-font-color',
				'type'     => 'color',
				'title'    => __( 'Header Font Color', 'repute-backend' ),
				'subtitle' => __( 'Font color at header (navbar) area, including navigation text', 'repute-backend' ),
				'default'  => '#474747',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-nav-bg-active-color',
				'type'     => 'color',
				'title'    => __( 'Nav Background Color (Active/Hover)', 'repute-backend' ),
				'subtitle' => __( 'Nav background color for menu level 1 when active/hovered', 'repute-backend' ),
				'default'  => '#f9f9f9',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-footer-color',
				'type'     => 'color',
				'title'    => __( 'Footer Background Color', 'repute-backend' ),
				'subtitle' => __( 'Background color of footer', 'repute-backend' ),
				'default'  => '#49494b',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-footer-font-color',
				'type'     => 'color',
				'title'    => __( 'Footer Font Color', 'repute-backend' ),
				'subtitle' => __( 'Font color at footer area', 'repute-backend' ),
				'default'  => '#ffffff',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-copyright-color',
				'type'     => 'color',
				'title'    => __( 'Copyright Background Color', 'repute-backend' ),
				'subtitle' => __( 'Background color of copyright area at the bottom', 'repute-backend' ),
				'default'  => '#323232',
				'validate' => 'color',
			),
			array(
				'id'       => 'rpt-copyright-font-color',
				'type'     => 'color',
				'title'    => __( 'Copyright Font Color', 'repute-backend' ),
				'subtitle' => __( 'Font color of copyright area at the bottom', 'repute-backend' ),
				'default'  => '#ffffff',
				'validate' => 'color',
			),
		)
	) );

	Redux::setSection( $opt_name, array(
		'title'            => __( 'Social', 'repute-backend' ),
		'id'               => 'rpt-social-link',
		'desc'             => __( 'Social network icon with url link.', 'repute-backend' ),
		'customizer_width' => '400px',
		'icon'             => 'el el-thumbs-up',
		'fields'           => array(
			array(
				'id'       => 'rpt-social-links',
				'type'     => 'sortable',
				'title'    => __( 'Social Network Links', 'repute-backend' ),
				'subtitle'     => __( 'Change the order of your social icon links by dragging the control on the right. Recommended: max 5 icons ', 'repute-backend' ),
				'label'    => true,
				'options'  => array(
					'Delicious'   => '',
					'Dribbble'   => '',
					'Facebook'   => '',
					'Flickr'   => '',
					'Github'   => '',
					'Google Plus' => '',
					'Instagram'   => '',
					'LinkedIn'   => '',
					'Pinterest'   => '',
					'Reddit'   => '',
					'Skype'   => '',
					'Soundcloud'   => '',
					'Spotify'   => '',
					'Stack Overflow'   => '',
					'Steam'   => '',
					'Tumblr'   => '',
					'Twitter'   => '',
					'Vimeo'   => '',
					'Vine'   => '',
					'Yahoo'   => '',
					'Youtube'   => '',
				)
			),
		)
	) );

	Redux::setSection( $opt_name, array(
		'title'            => __( 'Typography', 'repute-backend' ),
		'id'               => 'typography-setup',
		'desc'             => __( 'Typography setup.', 'repute-backend' ),
		'customizer_width' => '400px',
		'icon'             => 'el el-font',
		'fields'           => array(
			array(
				'id'       => 'rpt-font-body',
				'type'     => 'typography',
				'title'    => __( 'Body Font', 'repute-backend' ),
				'subtitle' => __( 'Specify the body font properties.', 'repute-backend' ),
				'google'   => true,
				'all_styles'   => true,
				'text-align' => false,
				'default'  => array(
					'color'       => '#7b7b7b',
					'font-size'   => '13px',
					'font-family' => 'Open Sans',
					'font-weight' => '400',
					'font-style'  => 'Normal'
				),
			),
			array(
				'id'       => 'rpt-font-heading',
				'type'     => 'typography',
				'title'    => __( 'Heading Font', 'repute-backend' ),
				'subtitle' => __( 'Specify the heading (H1 - H6) font properties.', 'repute-backend' ),
				'google'   => true,
				'all_styles' => true,
				'text-align' => false,
				'line-height' => false,
				'font-size' => false,
				'font-weight' => false,
				'default'  => array(
					'color'       => '#474747',
					'font-family' => 'Roboto Condensed',
				),
			),
		)
	) );

	/*
	 * <--- END SECTIONS
	 */


	/*
	 *
	 * YOU MUST PREFIX THE FUNCTIONS BELOW AND ACTION FUNCTION CALLS OR ANY OTHER CONFIG MAY OVERRIDE YOUR CODE.
	 *
	 */

	/*
	*
	* --> Action hook examples
	*
	*/

	// If Redux is running as a plugin, this will remove the demo notice and links
	//add_action( 'redux/loaded', 'remove_demo' );

	// Function to test the compiler hook and demo CSS output.
	// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
	//add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

	// Change the arguments after they've been declared, but before the panel is created
	//add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

	// Change the default value of a field after it's been set, but before it's been useds
	//add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

	// Dynamically add a section. Can be also used to modify sections/fields
	//add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');

	/**
	 * This is a test function that will let you see when the compiler hook occurs.
	 * It only runs if a field    set with compiler=>true is changed.
	 * */
	if ( ! function_exists( 'compiler_action' ) ) {
		function compiler_action( $options, $css, $changed_values ) {
			echo '<h1>The compiler hook has run!</h1>';
			echo "<pre>";
			print_r( $changed_values ); // Values that have changed since the last save
			echo "</pre>";
			//print_r($options); //Option values
			//print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
		}
	}

	/**
	 * Custom function for the callback validation referenced above
	 * */
	if ( ! function_exists( 'redux_validate_callback_function' ) ) {
		function redux_validate_callback_function( $field, $value, $existing_value ) {
			$error   = false;
			$warning = false;

			//do your validation
			if ( $value == 1 ) {
				$error = true;
				$value = $existing_value;
			} elseif ( $value == 2 ) {
				$warning = true;
				$value   = $existing_value;
			}

			$return['value'] = $value;

			if ( $error == true ) {
				$return['error'] = $field;
				$field['msg']    = 'your custom error message';
			}

			if ( $warning == true ) {
				$return['warning'] = $field;
				$field['msg']      = 'your custom warning message';
			}

			return $return;
		}
	}

	/**
	 * Custom function for the callback referenced above
	 */
	if ( ! function_exists( 'redux_my_custom_field' ) ) {
		function redux_my_custom_field( $field, $value ) {
			print_r( $field );
			echo '<br/>';
			print_r( $value );
		}
	}

	/**
	 * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
	 * Simply include this function in the child themes functions.php file.
	 * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
	 * so you must use get_template_directory_uri() if you want to use any of the built in icons
	 * */
	if ( ! function_exists( 'dynamic_section' ) ) {
		function dynamic_section( $sections ) {
			//$sections = array();
			$sections[] = array(
				'title'  => __( 'Section via hook', 'repute-backend' ),
				'desc'   => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'repute-backend' ),
				'icon'   => 'el el-paper-clip',
				// Leave this as a blank section, no options just some intro text set above.
				'fields' => array()
			);

			return $sections;
		}
	}

	/**
	 * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
	 * */
	if ( ! function_exists( 'change_arguments' ) ) {
		function change_arguments( $args ) {
			//$args['dev_mode'] = true;

			return $args;
		}
	}

	/**
	 * Filter hook for filtering the default value of any given field. Very useful in development mode.
	 * */
	if ( ! function_exists( 'change_defaults' ) ) {
		function change_defaults( $defaults ) {
			$defaults['str_replace'] = 'Testing filter hook!';

			return $defaults;
		}
	}

	/**
	 * Removes the demo link and the notice of integrated demo from the redux-framework plugin
	 */
	if ( ! function_exists( 'remove_demo' ) ) {
		function remove_demo() {
			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
				remove_filter( 'plugin_row_meta', array(
					ReduxFrameworkPlugin::instance(),
					'plugin_metalinks'
				), null, 2 );

				// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
				remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
			}
		}
	}

