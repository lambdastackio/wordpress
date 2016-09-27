<?php
/*
Plugin Name: TDV Shortcodes
Plugin URI: https://themeineed.com
Description: Shortcodes plugin created based on Bootstrap framework.  Mainly used for Repute WordPress theme, some shortcodes need certain plugin activated
Version: 1.0.1
Author: The Develovers
Author URI: https://themeineed.com

Note: Shortcode below need plugin activated
- [tdv_team] needs TDV Team plugin
- [tdv_testimonial] needs TDV Testimonial plugin
*/

// no direct access
if ( !defined( 'ABSPATH' ) ) exit;

function tdv_include_shortcodes_scripts() {
	wp_register_style('tdv-shortcodes', plugins_url('css/shortcodes.css', __FILE__));
	wp_enqueue_style('tdv-shortcodes');
}

add_action( 'init', 'tdv_include_shortcodes_scripts');

include('includes/shortcodes.php');


/* add dropdown to TinyMCE */
function tdv_shortcodes_tinymce_plugin($plugin_array) {
	$plugin_array['tdv_shortcodes'] = plugins_url('/js/tinymce-dropdown.js', __FILE__);
	return $plugin_array;
}

function tdv_register_shortcodes_button($buttons) {
	array_push($buttons, "tdv_shortcodes_dropdownbutton");
	return $buttons;
}

function tdv_shortcodes_addbutton() {
	if( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
		return;

	if( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "tdv_shortcodes_tinymce_plugin");
		add_filter("mce_buttons", "tdv_register_shortcodes_button");
	}
}

add_action('init', 'tdv_shortcodes_addbutton');



?>