<?php
/*
Plugin Name: TDV Ajax Mailchimp
Plugin URI: https://themeineed.com
Description: Really simple Ajax mailchimp newsletter.
Version: 1.0.0
Author: The Develovers
Author URI: https://themeineed.com
*/

// no direct access
if ( !defined( 'ABSPATH' ) ) exit;

/* add required scripts */
function tdv_register_scripts() {
	wp_register_style( 'ajax-mailchimp-style', plugins_url( '/css/ajax-mailchimp.css', __FILE__ ));
	wp_register_script( 'ajax-mailchimp-js', plugins_url( '/js/ajax-mailchimp.js', __FILE__ ), array('jquery') );
	wp_localize_script( 'ajax-mailchimp-js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
}

function tdv_enqueue_styles() {
	wp_enqueue_style('ajax-mailchimp-style');
}

function tdv_enqueue_js() {
	wp_enqueue_script( 'ajax-mailchimp-js' );
}

add_action( 'init', 'tdv_register_scripts');
add_action( 'wp_enqueue_scripts', 'tdv_enqueue_styles', 1 );
add_action('wp_enqueue_scripts', 'tdv_enqueue_js');

/* do subscribing */
function tdv_subscribe_newsletter_callback(){
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "subscribe_newsletter_nonce")) {
		exit("invalid request");
	}

	if(!empty($_REQUEST['email'])) {

		// plugin options
		$options = get_option('ajax_mailchimp_options');

		// mailchimp API class
		require_once('includes/MCAPI.class.php');

		// Your API Key: http://admin.mailchimp.com/account/api/
		$api = new MCAPI($options['api_key']);

		// Your List Unique ID: http://admin.mailchimp.com/lists/ (Click "settings")
		$list_id = $options['list_id'];

		// Variables in your form that match up to variables on your subscriber
		// list. You might have only a single 'name' field, no fields at all, or more
		// fields that you want to sync up.
		// Example: 
		/*

		$merge_vars = array(
		'FNAME' => $_POST['firstName'],
		'LNAME' => $_POST['lastName']
		);

		*/

		$merge_vars = array();
		$result = false;

		/* subscribe to list */
		if ( $api->listSubscribe($list_id, $_REQUEST['email'], $merge_vars) === true ){

		$mailchimp_result = 'Success! Check your email to confirm sign up.';
		$result = true;

		} else {

		$mailchimp_result = 'Error. ' . $api->errorMessage;
		}

		echo json_encode( array( 'message' => $mailchimp_result, 'result' => $result ));

	} else {
		echo json_encode( array( 'message' => 'Please provide your email to subscribe to our newsletter.', 'result' => false ));
	}

	wp_die();
}

add_action( 'wp_ajax_rpt_subscribe_newsletter', 'tdv_subscribe_newsletter_callback' );
add_action( 'wp_ajax_nopriv_rpt_subscribe_newsletter', 'tdv_subscribe_newsletter_callback' );


/* admin settings */
function tdv_add_admin_options_page() {
	add_options_page('Really Simple Ajax Mailchimp', 'TDV Ajax Mailchimp', 'manage_options', 'ajax-mailchimp', 'tdv_build_options_page');
}

add_action('admin_menu', 'tdv_add_admin_options_page');

function tdv_build_options_page() {
?>

<div class="wrap">
	<h2>Really Simple Ajax Mailchimp</h2>
	<form action="options.php" method="post">
		<?php settings_fields('ajax_mailchimp_options'); ?>
		<?php do_settings_sections('ajax-mailchimp'); ?>

		<?php submit_button(); ?>
	</form>
</div>

<?php
}

function tdv_register_settings() {
	register_setting('ajax_mailchimp_options', 'ajax_mailchimp_options');
	add_settings_section('ajax_mailchimp_main', 'Mailchimp Settings', 'ajax_mailchimp_main_text', 'ajax-mailchimp');
	add_settings_field('api_key', 'API Key', 'build_input_apikey', 'ajax-mailchimp', 'ajax_mailchimp_main');
	add_settings_field('list_id', 'List ID', 'build_input_listid', 'ajax-mailchimp', 'ajax_mailchimp_main');
}

add_action( 'admin_init', 'tdv_register_settings' );

function ajax_mailchimp_main_text() {
	echo '<p>Provide your Mailchimp API Key and List ID of newsletter campaign</p>';
}

function build_input_apikey() {
	$options = get_option('ajax_mailchimp_options');
	echo "<input type='text' name='ajax_mailchimp_options[api_key]' size='50' value='{$options['api_key']}'>";
}

function build_input_listid() {
	$options = get_option('ajax_mailchimp_options');
	echo "<input type='text' name='ajax_mailchimp_options[list_id]' value='{$options['list_id']}'>";
}

?>
