<?php
/*
Plugin Name: TDV Portfolio
Plugin URI: https://themeineed.com
Description: Portfolio plugin, mainly used for Repute WordPress theme.
Version: 1.0.0
Author: The Develovers
Author URI: https://themeineed.com
*/
// no direct access
if ( !defined( 'ABSPATH' ) ) exit;

/* include scripts */ 
function tdv_include_portfolio_scripts() {
	wp_register_script( 'isotope', plugins_url( 'js/isotope.pkgd.min.js', __FILE__ ), array( 'jquery' ) );
	wp_register_script( 'tdv-portfolio', plugins_url( 'js/tdv-portfolio.js', __FILE__ ), array( 'isotope' ) );
	wp_register_style( 'tdv-portfolio-styles', plugins_url( 'css/portfolio.css', __FILE__ ) );
	
	wp_enqueue_script( 'isotope' );
	wp_enqueue_script( 'tdv-portfolio' );
	wp_enqueue_style( 'tdv-portfolio-styles' );
}

add_action( 'init', 'tdv_include_portfolio_scripts' );

/*	register portfolio post type */
function tdv_create_portfolio_post_type() {

	register_post_type( 
		'tdv_portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio',
				'singular_name' => 'Portfolio',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Portfolio Item',
				'edit' => 'Edit',
				'edit_item' => 'Edit Portfolio Item',
				'new_item' => 'New Portfolio Item',
				'view' => 'View',
				'view_item' => 'View Portfolio',
				'search_items' => 'Search Portfolio',
				'not_found' => 'No Portfolio found',
				'not_found_in_trash' => 'No Portfolio found in Trash',
				'parent' => 'Parent Portfolio'
			),
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'menu_position' => 20,
			'show_in_nav_menus' => true,
			'supports' => array( 'title', 'editor', 'comments', 'thumbnail'),
			'taxonomies' => array( '' ),
			'rewrite' => array('slug' => 'portfolio-item'),
			'menu_icon' => 'dashicons-grid-view'
		)
	);
}

add_action( 'init', 'tdv_create_portfolio_post_type' );

/*	create custom taxonomy for Portfolio */
function tdv_create_portfolio_taxonomy() {

	register_taxonomy(
		'tdv_portfolio_category',
		'tdv_portfolio',
		array(
			'labels' => array(
				'name' => 'Portfolio Category',
				'add_new_item' => 'Add New Portfolio Category',
				'new_item_name' => 'New Portfolio Category'
			),
			'show_ui' => true,
			'show_tagcloud' => true,
			'hierarchical' => true
		)
	);
	
	register_taxonomy_for_object_type( 'tdv_portfolio_category', 'tdv_portfolio' );
}

add_action( 'init', 'tdv_create_portfolio_taxonomy', 0 );

/* add metabox */
function tdv_add_portfolio_metabox() {
	add_meta_box( 'tdv_portfolio_metabox', 'Portfolio Info', 'tdv_portfolio_build_fields' ,'tdv_portfolio', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'tdv_add_portfolio_metabox' );

/* build the metabox fields */
function tdv_portfolio_build_fields( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'tdv_save_portfolio_metabox_data', 'tdv_metabox_nonce' );

	include_once( 'portfolio-fields.php' );
	$portfolio = get_post_meta( $post->ID, 'tdv_portfolio', true );

	echo '<table class="form-table meta_box">';
	foreach ( $tdv_portfolio_fields as $field ) {
		echo '<tr>
				<th scope="row" style="width:20%"><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
				<td>';

				$value = !empty( $portfolio[$field['id']] ) ? $portfolio[$field['id']] : '';
				echo '<input type="text" id="' . $field['id'] .'" name="' . $field['id'] .'" value="' . esc_attr( $value ) . '" size="25" />';
				
		echo 	'</td>';
		echo '</tr>';
	}

	echo '</table>';
}

function tdv_save_portfolio_metabox_data( $post_id ) {

	// check if our nonce is set.
	if( ! isset( $_POST['tdv_metabox_nonce'] ) ) {
		return;
	}

	// verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['tdv_metabox_nonce'], 'tdv_save_portfolio_metabox_data' ) ) {
		return;
	}

	// if this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	include_once( 'portfolio-fields.php' );

	/* saving data */
	foreach ( $tdv_portfolio_fields as $field ) {

		// Make sure that it is set.
		if ( ! isset( $_POST[$field['id']] ) ) {
			return;
		}

		// Sanitize user input.
		$arr_data[$field['id']] = $_POST[$field['id']];

		// Update the meta field in the database.
		update_post_meta( $post_id, 'tdv_portfolio' , $arr_data );
	}
}

add_action( 'save_post', 'tdv_save_portfolio_metabox_data' );

register_sidebar( array(
	'name' => __( 'TDV Recent Portfolio Section', 'tdv' ),
	'id' => 'tdv-recent-portfolio-section',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<h2 class="section-heading">',
	'after_title' => '</h2>'
));

/* add widget */
require_once( 'portfolio-widget.php' );

/* register widget */
function tdv_register_recent_portfolio_widget() {
	register_widget( "Tdv_Recent_Portfolio" );
}

add_action( 'widgets_init', 'tdv_register_recent_portfolio_widget' );

?>