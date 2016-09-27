<?php

/* theme options config */
require_once( 'includes/repute-options-config.php' );
$repute_options = get_option( 'repute-options' );
global $skin;

$skin = $repute_options['rpt-skin'];

/* register scripts, css and js */
function register_scripts() {
	wp_register_style( 'fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '3.3.5' );
	wp_register_style( 'rpt-style', get_stylesheet_uri() );

	wp_register_style( 'rpt-skin-orange', get_template_directory_uri() . '/css/skins/orange.css' );
	wp_register_style( 'rpt-skin-deepskyblue', get_template_directory_uri() . '/css/skins/deepskyblue.css' );
	wp_register_style( 'rpt-skin-goldenrod', get_template_directory_uri() . '/css/skins/goldenrod.css' );
	wp_register_style( 'rpt-skin-indianred', get_template_directory_uri() . '/css/skins/indianred.css' );
	wp_register_style( 'rpt-skin-lightgreen', get_template_directory_uri() . '/css/skins/lightgreen.css' );
	wp_register_style( 'rpt-skin-seagreen', get_template_directory_uri() . '/css/skins/seagreen.css' );
	wp_register_style( 'rpt-skin-slategray', get_template_directory_uri() . '/css/skins/slategray.css' );
	wp_register_style( 'rpt-skin-brown', get_template_directory_uri() . '/css/skins/brown.css' );

	wp_register_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap/bootstrap.min.js', array( 'jquery' ), '3.3.5', true );
	wp_register_script( 'autohidingnavbar', get_template_directory_uri() . '/js/autohidingnavbar/jquery.bootstrap-autohidingnavbar.min.js', array( 'jquery' ), '1.0.0', true );
	wp_register_script( 'easing', get_template_directory_uri() . '/js/easing/jquery.easing.min.js', array( 'jquery' ), '1.3.0', true );
	wp_register_script( 'theme', get_template_directory_uri() . '/js/theme.js', array( 'jquery' ), '1.0.0', true );
}

/* enqueue css */
function enqueue_styles() {
	if(is_admin())
		return;

	wp_enqueue_style( 'bootstrap' );
	wp_enqueue_style( 'fontawesome' );
	wp_enqueue_style( 'rpt-style');
}

/* enqueue js */
function enqueue_js() {
	if(is_admin())
		return;

	wp_enqueue_script( 'bootstrap' );
	wp_enqueue_script( 'easing' );
	wp_enqueue_script( 'theme' );
}

/* enqueue skin style based on theme option */
function enqueue_skin_style() {
	global $skin;
	wp_enqueue_style( 'rpt-skin-' . $skin );
}

/* enqueue autohidingnavbar plugin script */
function enqueue_autohiding_script() {
	wp_enqueue_script( 'autohidingnavbar' );
}

add_action( 'init', 'register_scripts');
add_action( 'wp_enqueue_scripts', 'enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'enqueue_js' );

/* conditional scripts */
if ( $repute_options['rpt-navigation'] == 'nav-auto-hiding' ) {
	// if theme navigation is auto-hiding
	add_action( 'wp_enqueue_scripts', 'enqueue_autohiding_script' );
}

if( $repute_options['rpt-skin'] != '' ) {
	add_action('wp_enqueue_scripts', 'enqueue_skin_style' );
}

/* register sidebars */
register_sidebar(array(
	'name' => __( 'Footer Left', 'repute' ),
	'id' => 'footer-left',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<h3 class="footer-heading">',
	'after_title' => '</h3>'
));

register_sidebar(array(
	'name' => __( 'Footer Right', 'repute' ),
	'id' => 'footer-right',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<h3 class="footer-heading">',
	'after_title' => '</h3>'
));

register_sidebar(array(
	'name' => __( 'Page', 'repute' ),
	'id' => 'tdv-page',
	'before_widget' => '<div id="tdv-widget-%1$s" class="tdv-widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>'
));

register_sidebar(array(
	'name' => __( 'Blog', 'repute' ),
	'id' => 'tdv-blog',
	'before_widget' => '<div id="tdv-widget-%1$s" class="tdv-widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>'
));

register_sidebar( array(
	'name' => __( 'TDV Recent Posts Main Section', 'tdv' ),
	'id' => 'tdv-recent-posts-main-section',
	'before_widget' => '',
	'after_widget' => '',
	'before_title' => '<h2 class="section-heading">',
	'after_title' => '</h2>'
));

/* format page title */
function tdv_title_home( $title ) {
	if( empty( $title ) && ( is_home() || is_front_page() ) ) {
		return __( 'Home', 'repute' ) . ' | ' . get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
	}

		return $title . ' | ' . get_bloginfo( 'name' );
}

add_filter( 'wp_title', 'tdv_title_home' );

/* included in search result */
function tdv_include_in_search_result( $query ) {
	if ( $query->is_search )
		$query->set( 'post_type', array( 'post', 'page' ) );

	return $query;
}

add_filter( 'pre_get_posts', 'tdv_include_in_search_result' );

/* add more TinyMCE buttons */
function tdv_register_additional_button($buttons) {
	array_unshift($buttons, 'fontsizeselect');
	return $buttons;
}

add_filter('mce_buttons_2', 'tdv_register_additional_button');

/* enable WP theme supports */
add_theme_support( 'post-thumbnails' );
add_theme_support( 'custom-background' );
add_theme_support( 'custom-header' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'menus' );
register_nav_menus( array(
	'topnav' =>'Primary Top Navigation', 
	'footernavleft' => 'Footer Navigation 1',
	'footernavright' => 'Footer Navigation 2'
));

/* add custom image thumbnail for portfolio */
if ( function_exists( 'add_image_size' ) ) { 
	add_image_size( 'tdv_portfolio_thumbnail', 800, 500, true ); // for carousel
	add_image_size( 'tdv_portfolio_grid_thumbnail', 800, 800, true ); // for isotope grid
}

/* allow shortcode on widget */
add_filter('widget_text', 'do_shortcode');

/* remove width and height in image thumbnail tag */
function tdv_remove_thumbnail_size( $html ) {
	$html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
	return $html;
}

add_filter( 'post_thumbnail_html', 'tdv_remove_thumbnail_size', 10 );

/* enable widget on post/page */
function tdv_scode_widget( $atts ){
	extract( shortcode_atts(
		array(
			'name' => '',
		), $atts ));

	ob_start();
	
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar( $name )) {}
	
	$result = ob_get_contents();
	ob_end_clean();
	
	return $result;
}

add_shortcode('tdv_widget', 'tdv_scode_widget');

/* include theme widgets */
include_once('widgets/tdv-recent-posts.php');
include_once('widgets/tdv-recent-posts-main.php');
include_once('widgets/tdv-social-links.php');

/* register widgets */
function tdv_register_theme_widgets() {
	register_widget("Tdv_Recent_Posts");
	register_widget("Tdv_Recent_Posts_Main");
	register_widget("Tdv_Social_Links");
}

add_action('widgets_init', 'tdv_register_theme_widgets');

/* remove unwanted empty p tag */
function tdv_remove_empty_p( $content ) {
	$array = array (
		'<p>[' => '[', 
		']</p>' => ']', 
		']<br />' => ']',
		']<br />' => ']'
	);

	$content = strtr( $content, $array );
	
	return $content;
}

add_filter('the_content', 'tdv_remove_empty_p', 10);

function tdv_add_search_icon( $items, $args ) {
	if ( $args->theme_location == 'topnav' ) {
		$items .= '<li><a href="#" class="top-searchbox-toggle"><i class="fa fa-search"></i> <span class="sr-only">Search</span></a></li>';
	}
	
	return $items;
}

add_filter( 'wp_nav_menu_items', 'tdv_add_search_icon', 10, 2 );

/* include custom navigation walker */
require_once('includes/wp_bootstrap_navwalker.php');

/* include demo importer */
require_once('includes/demo-importer.php');

/* include tgm plugin activation */
require_once('includes/class-tgm-plugin-activation.php');

/* register required plugins */
function tdv_register_required_plugins() {
	/* array of plugins */
	$plugins = array(
		// include a plugin bundled with a theme.
		array(
			'name'               => 'Redux Framework', // The plugin name.
			'slug'               => 'redux-framework', // The plugin slug (typically the folder name).
			'source'             => get_stylesheet_directory() . '/lib/plugins/redux-framework.3.5.7.zip', // The plugin source.
			'required'           => true, // If false, the plugin is only 'recommended' instead of required.
			'version'            => '3.5.7', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
			'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
			'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
		),
		array(
			'name'               => 'TDV Ajax Mailchimp',
			'slug'               => 'tdv-ajax-mailchimp',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-ajax-mailchimp.1.0.0.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Carousel',
			'slug'               => 'tdv-carousel',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-carousel.1.0.1.zip',
			'required'           => true,
			'version'            => '1.0.1',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Portfolio',
			'slug'               => 'tdv-portfolio',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-portfolio.1.0.0.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Shortcodes',
			'slug'               => 'tdv-shortcodes',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-shortcodes.1.0.1.zip',
			'required'           => true,
			'version'            => '1.0.1',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Team',
			'slug'               => 'tdv-team',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-team.1.0.0.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Testimonial',
			'slug'               => 'tdv-testimonial',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-testimonial.1.0.0.zip',
			'required'           => true,
			'version'            => '1.0.0',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'TDV Twitter',
			'slug'               => 'tdv-twitter',
			'source'             => get_stylesheet_directory() . '/lib/plugins/tdv-twitter.1.1.0.zip',
			'required'           => true,
			'version'            => '1.1.0',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'AddThis',
			'slug'               => 'addthis',
			'source'             => get_stylesheet_directory() . '/lib/plugins/addthis.5.1.1.zip',
			'required'           => true,
			'version'            => '5.1.1',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'Breadcrumb Navxt',
			'slug'               => 'breadcrumb-navxt',
			'source'             => get_stylesheet_directory() . '/lib/plugins/breadcrumb-navxt.5.2.2.zip',
			'required'           => true,
			'version'            => '5.2.2',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'Contact Form 7',
			'slug'               => 'contact-form-7',
			'source'             => get_stylesheet_directory() . '/lib/plugins/contact-form-7.4.2.2.zip',
			'required'           => true,
			'version'            => '4.2.2',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
		array(
			'name'               => 'Responsive Lightbox',
			'slug'               => 'responsive-lightbox',
			'source'             => get_stylesheet_directory() . '/lib/plugins/responsive-lightbox.1.6.1.zip',
			'required'           => true,
			'version'            => '1.6.1',
			'force_activation'   => false,
			'force_deactivation' => true,
		),
	);

	/* array of configs */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}

add_action( 'tgmpa_register', 'tdv_register_required_plugins' );


?>
