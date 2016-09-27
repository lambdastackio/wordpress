<?php
require_once('src/SettingsPage.php');

function slickc_set_options() {
	$defaults = array(
		'accessibility' => 'true',
		'adaptiveHeight' => '',
		'autoplay' => '',
		'autoplaySpeed' => '3000',
		'arrows' => 'true',
		'asNavFor' => '',
		'appendArrows' => '',
		'prevArrow' => '',
		'nextArrow' => '',
		'centerMode' => '',
		'centerPadding' => '50px',
		'cssEase' => 'ease',
		'dots' => '',
		'draggable' => 'true',
		'fade' => 'true',
		'focusOnSelect' => '',
        'easing' => 'linear',
        'infinite' => 'true',
        'initialSlide' => '0',
        'lazyLoad' => 'ondemand',
        'pauseOnHover' => 'true',
        'pauseOnDotsHover' => '',
        'respondTo' => 'window',
        'responsive' => '',
        'slidesToShow' => '1',
        'slidesToScroll' => '1',
        'speed' => '300',
        'swipe' => 'true',
        'swipeToSlide' => 'true',
        'touchMove' => 'true',
        'touchThreshold' => '5',
        'useCSS' => 'true',
        'variableWidth' => '',
        'vertical' => '',
        'rtl' => '',
	);
	add_option('slickc_settings', $defaults);
}

function slickc_deactivate(){
	delete_option('slickc_settings');
}

if (is_admin()) {
    $slickc_settings_page = new SlickC_SettingsPage();
}

// Add settings link on plugin page
function slickc_settings_link($links) { 
	$settings_link = '<a href="edit.php?post_type=slickc&page=slick-carousel">' .
        __('Settings', 'slick-carousel') .
        '</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
}

$slickc_plugin = plugin_basename(__FILE__); 

register_activation_hook(__FILE__, 'slickc_deactivate');
register_activation_hook(__FILE__, 'slickc_set_options');

add_filter("plugin_action_links_${slickc_plugin}", 'slickc_settings_link');