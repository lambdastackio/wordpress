<?php

///////////////////
// ADMIN PAGES
///////////////////

function slickc_get_featured_image($post_ID) {  
	$post_thumbnail_id = get_post_thumbnail_id($post_ID);  
	if ($post_thumbnail_id) {  
		$post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');  
		return $post_thumbnail_img[0];  
	}  
}
function slickc_columns_head($defaults) {  
	$defaults['featured_image'] = __('Featured Image', 'slick-carousel');  
	$defaults['category'] = __('Category', 'slick-carousel');  
	return $defaults;  
}  
function slickc_columns_content($column_name, $post_ID) {  
	if ($column_name == 'featured_image') {  
		$post_featured_image = slickc_get_featured_image($post_ID);  
		if ($post_featured_image) {  
			echo '<a href="' . get_edit_post_link($post_ID) . '"><img src="' . $post_featured_image . '" alt="" style="max-width:100%;" /></a>';  
		}  
	}
	if ($column_name === 'category') {  
		$post_categories = get_the_terms($post_ID, 'carousel_category');
		if ($post_categories) {
			$output = '';
			foreach ($post_categories as $cat) {
				$output .= $cat->name.', ';
			}
			echo trim($output, ', ');
		} else {
			echo 'No categories';
		}
	}
}

// Extra admin field for image URL
function slickc_image_url() {
	global $post;
	$custom = get_post_custom($post->ID);
	$slickc_image_url = isset($custom['slickc_image_url']) ?  $custom['slickc_image_url'][0] : '';
	$slickc_image_url_openblank = isset($custom['slickc_image_url_openblank']) ?  $custom['slickc_image_url_openblank'][0] : '0';
	?>
	<label><?php _e('Image URL', 'slick-carousel'); ?>:</label>
	<input name="slickc_image_url" value="<?php echo $slickc_image_url; ?>" /> <br />
	<small><em><?php _e('(optional - leave blank for no link)', 'slick-carousel'); ?></em></small><br /><br />
	<label><input type="checkbox" name="slickc_image_url_openblank" <?php if($slickc_image_url_openblank == 1){ echo ' checked="checked"'; } ?> value="1" /> <?php _e('Open link in new window?', 'slick-carousel'); ?></label>
	<?php
}
function slickc_admin_init_custpost() {
	add_meta_box('slickc_image_url', 'Image Link URL', 'slickc_image_url', 'slickc', 'side', 'low');
}

function slickc_mb_save_details() {
	global $post;
	if (isset($_POST['slickc_image_url'])) {
		$openblank = 0;
		if (isset($_POST['slickc_image_url_openblank']) && ($_POST['slickc_image_url_openblank'] === '1')) {
			$openblank = 1;
		}
		update_post_meta($post->ID, 'slickc_image_url', esc_url($_POST['slickc_image_url']));
		update_post_meta($post->ID, 'slickc_image_url_openblank', $openblank);
	}
}

add_filter('manage_slickc_posts_columns', 'slickc_columns_head');  
add_action('manage_slickc_posts_custom_column', 'slickc_columns_content', 10, 2);
add_action('save_post', 'slickc_mb_save_details');
add_action('add_meta_boxes', 'slickc_admin_init_custpost');
