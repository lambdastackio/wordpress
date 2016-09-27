<?php

/* register custom redux extensions */
if(!function_exists('redux_register_custom_extension_loader')) :
	function redux_register_custom_extension_loader($ReduxFramework) {
		$path = dirname( __FILE__ ) . '/extensions/';
		$folders = scandir( $path, 1 );
		foreach($folders as $folder) {
			if ($folder === '.' or $folder === '..' or !is_dir($path . $folder) ) {
				continue;	
			} 
			$extension_class = 'ReduxFramework_Extension_' . $folder;
			if( !class_exists( $extension_class ) ) {
				// In case you wanted override your override, hah.
				$class_file = $path . $folder . '/extension_' . $folder . '.php';
				$class_file = apply_filters( 'redux/extension/'.$ReduxFramework->args['opt_name'].'/'.$folder, $class_file );
				if( $class_file ) {
					require_once( $class_file );
					$extension = new $extension_class( $ReduxFramework );
				}
			}
		}
	}
	
	add_action("redux/extensions/repute-options/before", 'redux_register_custom_extension_loader', 0);
endif;


/************************************************************************
* Set menus, homepage, blog page, set permalink, disable default widgets,
* import plugin settings
*************************************************************************/

if ( !function_exists( 'wbc_do_after_content_import' ) ) {
	function wbc_do_after_content_import( $demo_active_import , $demo_directory_path ) {
		reset( $demo_active_import );
		$current_key = key( $demo_active_import );

		/************************************************************************
		* Setting Menus
		*************************************************************************/

		$wbc_menu_array = array( 'reputewp-demo');
		if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && in_array( $demo_active_import[$current_key]['directory'], $wbc_menu_array ) ) {
			$nav_menu1 = get_term_by( 'name', 'Top Nav Menu', 'nav_menu' );
			$nav_menu2 = get_term_by( 'name', 'Footer Nav Menu (left)', 'nav_menu' );
			$nav_menu3 = get_term_by( 'name', 'Footer Nav Menu (right)', 'nav_menu' );

			if ( isset( $nav_menu1->term_id ) && isset( $nav_menu2->term_id ) && isset( $nav_menu3->term_id ) ) {
				set_theme_mod( 'nav_menu_locations', array(
						'topnav' => $nav_menu1->term_id,
						'footernavleft'  => $nav_menu2->term_id,
						'footernavright' => $nav_menu3->term_id
					)
				);
			}
		}

		/************************************************************************
		* Set HomePage
		*************************************************************************/

		// array of demos/homepages to check/select from
		$wbc_home_pages = array(
			'reputewp-demo' => 'Home',
		);
		if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
			$page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
			if ( isset( $page->ID ) ) {
				update_option( 'page_on_front', $page->ID );
				update_option( 'show_on_front', 'page' );
			}
		}

		/************************************************************************
		* Set Blog Page
		*************************************************************************/

		$wbc_home_pages = array(
			'reputewp-demo' => 'Blog',
		);
		if ( isset( $demo_active_import[$current_key]['directory'] ) && !empty( $demo_active_import[$current_key]['directory'] ) && array_key_exists( $demo_active_import[$current_key]['directory'], $wbc_home_pages ) ) {
			$page = get_page_by_title( $wbc_home_pages[$demo_active_import[$current_key]['directory']] );
			if ( isset( $page->ID ) ) {
				update_option( 'page_for_posts', $page->ID );
			}
		}

		/************************************************************************
		* Delete default post and its comment, sample page
		*************************************************************************/

		$post = get_page_by_path( 'hello-world', OBJECT, 'post' );
		if ( $post ) {
			wp_delete_post( $post->ID, true );
		}

		$page = get_page_by_path( 'sample-page', OBJECT, 'page' );
		if ( $page ) {
			wp_delete_post( $page->ID, true );
		}

		/************************************************************************
		* Set permalink
		*************************************************************************/

		if ( get_option( 'permalink_structure' ) != '/%postname%/' ) { 
			update_option( 'permalink_structure', '/%postname%/' );
		}

		/************************************************************************
		* Set posts per page
		*************************************************************************/

		if ( get_option( 'posts_per_page' ) ) {
			update_option( 'posts_per_page', 5 );
		}

		/************************************************************************
		* Import plugins setting
		*************************************************************************/

		$json_file = dirname(__FILE__) . '/extensions/wbc_importer/demo-data/reputewp-demo/plugin-options.json';
		if ( file_exists( $json_file ) ) {
			$data = file_get_contents( $json_file );
			$data = json_decode( $data );

			if ( !empty( $data ) || is_object( $data ) ) {
				foreach ($data as $key => $value) {
					//update plugin option
					update_option( $key, maybe_unserialize( $value ) );
				}
			}
		}

		/************************************************************************
		* Remove default widgets attached to footer-left on theme activation
		*************************************************************************/

		if( get_option( 'sidebars_widgets' ) ) {
			$arr_widgets = get_option( 'sidebars_widgets' );
			$arr_widgets_pattern = array( 'search-*', 'recent-posts-*', 'recent-comments-*', 'archives-*', 'categories-*', 'meta-*' );

			foreach( $arr_widgets_pattern as $pattern ) {
				foreach ( $arr_widgets['footer-left'] as $key => $value ) {
					if( fnmatch( $pattern, $value ) ) {
						unset($arr_widgets['footer-left'][$key]);
					}
				}
			}

			// update sidebar_widgets with new value
			update_option( 'sidebars_widgets', $arr_widgets );
		}
	}
	
	add_action( 'wbc_importer_after_content_import', 'wbc_do_after_content_import', 10, 2 );
}

?>