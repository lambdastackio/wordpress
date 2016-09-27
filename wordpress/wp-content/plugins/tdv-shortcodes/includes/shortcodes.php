<?php

/* -------------------------------- 
 * Bootstrap row and column
 * -------------------------------*/

function tdv_scode_row( $atts, $content = null ){
	return '<div class="row">' . do_shortcode( $content ) . '</div>';
}

add_shortcode('tdv_row', 'tdv_scode_row' );

/* Bootstrap column */
function tdv_scode_column( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'width' => '1/2',
		'column_class' => ''
	), $atts ) );

	switch ( $width ) {
		case '1': $col = '12'; break;
		case '1/4': $col = '3'; break;
		case '1/2': $col = '6'; break;
		case '1/3': $col = '4'; break;
		case '2/3': $col = '8'; break;
		case '3/4': $col = '9'; break;
		case '4/4': $col = '12'; break;
		default: $col = '6'; break;
	}

	return '<div class="col-md-' . $col . ' ' . $column_class . '">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'tdv_col', 'tdv_scode_column' );


/* -------------------------------- 
 * Section with heading title
 * -------------------------------*/

function tdv_scode_section( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'section_title' => ''
	), $atts ) );

	if( $section_title == '' ) {
		return '<section><div class="container">' . do_shortcode( $content ) . '</div></section>';
	} else {
		return '<section><div class="container"><h2 class="section-heading">' . $section_title . '</h2>' . do_shortcode( $content ) . '</div></section>';
	}
}

add_shortcode( 'tdv_section', 'tdv_scode_section' );


/* -----------------------------------
 * Boxed content with icon and text
 * -----------------------------------*/

function tdv_scode_boxed_content( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => '',
		'text_align' => '',
		'icon' => 'fa fa-info-circle',
		'icon_size' => ''
	), $atts ) );

	$added_class = str_replace( 'left', 'left-aligned ', $text_align );
	$added_class .= str_replace( 'bigger', 'left-boxed-icon ', $icon_size );

	return '<div class="boxed-content ' .  $added_class .'">' .
				'<i class="' . $icon . '"></i>' .
				'<h2 class="boxed-content-title">' . $title . '</h2>' . 
				'<p>' . do_shortcode( $content ) . '</p>' .
			'</div>';
}

add_shortcode( 'tdv_boxed_content', 'tdv_scode_boxed_content' );


/* -----------------------------------
 * Almost similar to boxed content, 
 * but with full width background
 * -----------------------------------*/

function tdv_scode_main_feature($atts, $content = null ) {
	extract( shortcode_atts( array(
		'css_class' => ''
	), $atts) );

	return '<div class="main-features ' . $css_class . '">' .
				'<div class="container">' .
				do_shortcode( $content ) . 
				'</div>' . 
			'</div>';
}

add_shortcode('tdv_main_features', 'tdv_scode_main_feature');

function tdv_scode_feature_heading( $atts, $content = null ) {
	return '<h3 class="feature-heading">' . do_shortcode( $content ) . '</h3>';
}

add_shortcode('tdv_feature_heading', 'tdv_scode_feature_heading');


/* -----------------------------------
 * Bootstrap tabs
 * -----------------------------------*/

/* tabs nvavigation */
function tdv_scode_tabs( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'titles' => 'Tab A, Tab B, Tab C',
		'ids' => 'tabA, tabB, tabC',
		'type' => ''
	), $atts ) );

	global $tab_active, $ids_arr;

	$tabs_arr = explode( ',', trim( $titles ) );
	$ids_arr = explode( ',', trim( $ids ) );
	$tab_active = trim( $ids_arr[0] );
	$tabs_li = '';
	$active_class = '';

	foreach ( $tabs_arr as $index => $tab_title ) {
		if( $tab_active == trim( $ids_arr[$index] ) ) {
			$active_class = ' class="active"';
		} else {
			$active_class = '';
		}

		$tabs_li .= '<li' . $active_class . '><a href="#' . trim( $ids_arr[$index] ) . '" role="tab" data-toggle="tab">' . $tab_title . '</a></li>';
	}

	if( $type != '' ) {
		$added_class = '';

		switch ( $type ) {
			case 'line-top': $added_class = 'tabs-line-top'; break; // content on top
			case 'line-bottom': $added_class = 'tabs-line-bottom left-aligned'; break; // content on bottom
		}

		$tabs = '<div class="custom-tabs-line ' . $added_class . '">' .
				'<ul class="nav" role="tablist">' .
					$tabs_li .
				'</ul>' .
			'</div>';

	} else {
		$tabs = '<ul class="nav nav-tabs" role="tablist">' .
					$tabs_li .
				'</ul>';
	}

	return $tabs;
}

add_shortcode( 'tdv_tabs', 'tdv_scode_tabs' );

/* tab contents */
function tdv_scode_tab_content( $atts, $content = null) {
	// reset pane count, problem if there's more than one tabbed content element
	global $tab_pane_count;
	if( !empty( $tab_pane_count ) )
		unset( $GLOBALS['tab_pane_count'] );

	return '<div class="tab-content">' .
				do_shortcode( $content ) .
			'</div>';
}

add_shortcode( 'tdv_tab_content', 'tdv_scode_tab_content' );

/* tab pane */
function tdv_scode_tab_pane( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'id' => '',
		'css_class' => ''
	), $atts ) );

	global $tab_pane_count;
	$added_class = '';

	// determine that first tab always active first
	if( empty( $tab_pane_count ) )
		$tab_pane_count = 0;

	if( $tab_pane_count == 0 )
		$added_class = 'in active';

	$tab_pane_count++;

	return '<div class="tab-pane fade ' . $added_class . ' ' . $css_class . '" id="' . $id . '">' .
				do_shortcode( $content ) . 
			'</div>';
}

add_shortcode( 'tdv_tab_pane', 'tdv_scode_tab_pane' );


/* -----------------------------------
 * Bootstrap buttons
 * -----------------------------------*/

function tdv_scode_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'context' => 'default',
		'style' => '',
		'rounded' => '',
		'size' => '',
		'icon_class' => ''
	), $atts ) );
	
	$btn_context =  'btn-' . $context;

	if( $rounded == 'no' )
		$btn_rounded = 'btn-no-rounded';
	else if( $rounded != '')
		$btn_rounded = 'btn-rounded-' . $rounded;

	if( $size != '' )
		$btn_size = 'btn-' . $size;

	if( $style != '' )
		$btn_style = 'btn-' . $style;

	if( $icon_class != '' )
		$btn_icon = '<i class="' . $icon_class . '"></i>';

	return '<button type="button" class="btn ' . $btn_context . ' ' . $btn_rounded . ' ' . $btn_style . ' ' . $btn_size . '">' . $btn_icon . ' ' . do_shortcode( $content ) . '</button>';
}

add_shortcode( 'tdv_button', 'tdv_scode_button' );


/* -----------------------------------
 * Bootstrap alerts
 * -----------------------------------*/

function tdv_scode_alert( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'context' => 'info',
		'title' => 'Alert Title',
		'dismissible' => 'true',
	), $atts ) );

	if( $dismissible == 'true' )
		return '<div class="alert alert-' . $context . '" role="alert">' . 
			'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' .
			'<strong>' . $title . '</strong> ' . do_shortcode( $content ) . '</div>';
	else
		return '<div class="alert alert-' . $context . '" role="alert"><strong>' . $title . '</strong> ' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'tdv_alert', 'tdv_scode_alert' );


/* -----------------------------------
 * Google map
 * -----------------------------------*/

function tdv_scode_gmap( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'address' => '',
		'api_key' => '',
	), $atts ) );

	$map_id = rand( 1000, 9999 );

	wp_enqueue_script( 'gmap', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&callback=initializeMap' );

	$js = '<script type="text/javascript">';
	$js .= 'function codeAddress(geocoder, theMap, address) {
			geocoder.geocode( { \'address\': address}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					theMap.setCenter(results[0].geometry.location);
					var image = new google.maps.MarkerImage("../wp-content/plugins/tdv-shortcodes/img/location-pin.png", null, null, null, new google.maps.Size(32, 32));
					var beachMarker = new google.maps.Marker({
						map: theMap,
						icon: image,
						position: results[0].geometry.location
					});

				} else {
					alert("Geocode was not successful for the following reason: " + status);
				}
			});
			}';
	$js .= ' var mapOptions = {
				zoom: 15,
				scrollwheel: false,
				panControl: false,
				scaleControl: false,
				mapTypeControlOptions: {
					mapTypeIds: []
				}
			};';
	$js .= ' function initializeMap() {
				var geocoder = new google.maps.Geocoder(); 
				mapPlaceholder = document.getElementById("map-canvas_' . $map_id . '");

				if(mapPlaceholder) {
					googleMapIns = new google.maps.Map(mapPlaceholder, mapOptions);
					codeAddress(geocoder, googleMapIns, "' . $address . '");
				}
			}';
	$js .= '</script>';

	return $js . ' <div class="google-map">' .
				'<div id="map-canvas_' . $map_id . '"></div>' .
			'</div>';
}

add_shortcode( 'tdv_gmap', 'tdv_scode_gmap' );


/* -----------------------------------
 * Font Icons
 * -----------------------------------*/

function tdv_scode_icon( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'class' => '',
		'size' => '',
	), $atts ) );

	switch ( $size ) {
		case '2x': $size = 'fa-2x'; break;
		case '3x': $size = 'fa-3x'; break;
		case '4x': $size = 'fa-4x'; break;
		case '5x': $size = 'fa-5x'; break;
	}

	return '<i class="' . $class . ' ' . $size . '"></i>';
}

add_shortcode( 'tdv_icon', 'tdv_scode_icon' );


/* -----------------------------------
 * Social icon link
 * -----------------------------------*/

function tdv_scode_social_link( $atts, $content ) {
	extract( shortcode_atts(array(
		'url' => '#',
		'class' => '',
		'size' => '',
	), $atts ) );

	if( $url != '' )
		$link = str_replace( array( 'http://', 'https://' ), array('',''), $url );

	switch ( $size ) {
		case '2x': $size = 'fa-2x'; break;
		case '3x': $size = 'fa-3x'; break;
		case '4x': $size = 'fa-4x'; break;
		case '5x': $size = 'fa-5x'; break;
	}

	return '<a href="http://'.$link.'" class="tdv-scode-social-link icon-bgcolor-' . $class . ' ' . $size . '"><i class="fa fa-' . $class . '"></i></a>';
}

add_shortcode('tdv_social_link', 'tdv_scode_social_link');


/* -----------------------------------
 * Youtube and Vimeo video
 * -----------------------------------*/

function tdv_scode_video( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 'youtube',
		'url' => '',
		'width' => '',
		'height' => ''
	), $atts ) );

	$video = '';

	if( $type == "youtube" ) {
		if( strpos( $url, 'youtube' ) ) {
			$embed_src = $url;
			parse_str( parse_url( $embed_src, PHP_URL_QUERY ), $arr_vars );
			$youtube_id = $arr_vars['v']; 
		} else {
			$youtube_id = $url;
		}

		$video = '<div class="tdv-scode-video"><iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$youtube_id.'" frameborder="0" allowfullscreen></iframe></div>'; 

	} else if( $type == "vimeo" ) {
		$result = preg_match( '/(\d+)/', $url, $matches );
		if( $result ) {
			$vimeo_id = $matches[0];
		} else {
			$vimeo_id = $url;
		}

		$video = '<div class="tdv-scode-video"><iframe src="http://player.vimeo.com/video/'.$vimeo_id.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe></div>'; 
	}

	$fitvids = '';

	if( $width == '' && $height == '' ) {
		wp_register_script( 'fitvids', plugins_url('../js/jquery.fitvids.js', __FILE__ ), array( 'jquery') );
		wp_print_scripts( 'fitvids' );

		$fitvids = '<script type="text/javascript">' . 
						'jQuery(document).ready(function(){' . 
							'jQuery(".tdv-scode-video").fitVids();' . 
						'});' . 
					'</script>';
	}

	return $fitvids . $video;
}

add_shortcode( 'tdv_video', 'tdv_scode_video' );


/* -----------------------------------
 * Call to action
 * -----------------------------------*/

function tdv_scode_call_to_action( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title' => '',
		'url' => '#',
		'button_text' => 'BUTTON TEXT',
	), $atts ) );

	$heading = ($title != '') ? '<h2 class="call-to-action-heading">' . $title . '</h2>' : '';
	$link = str_replace( array( 'http://', 'https://' ), array('',''), $url );

	return '<section class="call-to-action">' .
			'<div class="container">' .
				'<div class="pull-left">' . 
					$heading . 
					'<p class="lead">' . do_shortcode( $content ) . '</p>' .
				'</div>' .
				'<div class="pull-right">' .
					'<a href="//' . $link . '" class="btn btn-lg btn-primary">' . $button_text . '</a>' . 
				'</div>' .
			'</div>' .
		'</section>';
}

add_shortcode( 'tdv_call_to_action', 'tdv_scode_call_to_action' );




?>