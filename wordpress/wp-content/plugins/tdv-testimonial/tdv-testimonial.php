<?php
/*
Plugin Name: TDV Testimonial
Plugin URI: https://themeineed.com
Description: Simple testimonial slider 
Version: 1.0.0
Author: The Develovers
Author URI: https://themeineed.com
*/
// no direct access
if ( !defined( 'ABSPATH' ) ) exit;

/* include scripts */ 
function tdv_include_testimonial_scripts() {
	wp_register_script( 'slick', plugins_url( 'includes/slick/slick.min.js', __FILE__ ), array( 'jquery' ), '1.5.8', true );
	wp_register_style( 'slick', plugins_url( 'includes/slick/slick.css', __FILE__ ) );
	wp_register_style( 'tdv-testimonial', plugins_url( 'css/testimonial.css', __FILE__ ) );
	
	wp_enqueue_script( 'slick' );
	wp_enqueue_style( 'slick' );
	wp_enqueue_style( 'tdv-testimonial' );
}

add_action( 'init', 'tdv_include_testimonial_scripts' );

/* register testimonial post type */
function tdv_create_testimonial_post_type() {

	register_post_type( 
		'tdv_testimonial',
		array(
			'labels' => array(
				'name' => 'Testimonial',
				'singular_name' => 'Testimonial',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Testimonial',
				'edit' => 'Edit',
				'edit_item' => 'Edit Testimonial',
				'new_item' => 'New Testimonial',
				'not_found' => 'No testimonial found',
				'not_found_in_trash' => 'No testimonial found in Trash'
			),
			'public' => true,
			'menu_position' => 20,
			'show_in_nav_menus' => false,
			'supports' => array( 'title', 'editor', 'thumbnail'),
			'taxonomies' => array( '' ),
			'menu_icon' => 'dashicons-testimonial',
			'has_archive' => true
		)
	);
}

add_action( 'init', 'tdv_create_testimonial_post_type' );

/* add metabox */
function tdv_add_testimonial_metabox() {
	add_meta_box('tdv_testimonial_metabox', 'Testimonial Info', 'tdv_testimonial_build_fields' ,'tdv_testimonial', 'normal', 'high');
}

add_action( 'add_meta_boxes', 'tdv_add_testimonial_metabox' );

/* build the metabox fields */
function tdv_testimonial_build_fields( $post ) {
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'tdv_save_testimonial_metabox_data', 'tdv_metabox_nonce' );

	require_once( 'testimonial-fields.php' );
	$testimonial = get_post_meta( $post->ID, 'tdv_testimonial_item', true );

	echo '<table class="form-table meta_box">';
	foreach ( $tdv_testimonial_fields as $field ) {
		echo '<tr>
				<th scope="row" style="width:20%"><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
				<td>';

				$value = !empty( $testimonial[$field['id']] ) ? $testimonial[$field['id']] : '';
				echo '<input type="text" id="' . $field['id'] .'" name="' . $field['id'] .'" value="' . esc_attr( $value ) . '" size="25" />';
				
		echo 	'</td>';
		echo '</tr>';
	}

	echo '</table>';
}
/* saving metabox data */
function tdv_save_testimonial_metabox_data( $post_id ) {

	// check if our nonce is set.
	if( ! isset( $_POST['tdv_metabox_nonce'] ) ) {
		return;
	}

	// verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['tdv_metabox_nonce'], 'tdv_save_testimonial_metabox_data' ) ) {
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

	require_once('testimonial-fields.php');

	// saving data
	foreach ( $tdv_testimonial_fields as $field ) {

		// Make sure that it is set.
		if ( ! isset( $_POST[$field['id']] ) ) {
			return;
		}

		// Sanitize user input.
		$arr_data[$field['id']] = $_POST[$field['id']];

		// Update the meta field in the database.
		update_post_meta( $post_id, 'tdv_testimonial_item' , $arr_data );
	}
}

add_action( 'save_post', 'tdv_save_testimonial_metabox_data' );

require_once( 'testimonial-frontend.php' );

?>