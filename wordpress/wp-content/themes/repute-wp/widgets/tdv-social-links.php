<?php
/*
Plugin Name: TDV Social Links
Plugin URI: https://themeineed.com
Description: Display social icon links based on theme option, intended for use with Repute theme
Version: 1.0
Author: The Develovers
Author URI: https://themeineed.com
*/

Class Tdv_Social_Links extends WP_Widget {

	// the constructor, setup
	function Tdv_Social_Links() {
		$widget_ops = array( 
			'classname' => 'tdv-social-links-widget',
			'description' => __('Display social icon links based on theme option', 'repute')
		);
		
		parent::__construct('tdv-social-links-widget', 'TDV Social Links', $widget_ops);
		
	}

	// build widget form
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, 
			array( 'title' => 'Social Links', 'order_by' => 'name_asc') );
		
		$title = esc_attr( $instance['title'] );

	?>
	<!-- widget form -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'repute' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" >
	</p>
	<p>
		<?php $value = $instance['order_by']; ?>

		<label for="<?php echo $this->get_field_id( 'order_by' ); ?>"><?php echo __( 'Order By:', 'repute' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'order_by' ); ?>" id="<?php echo $this->get_field_id( 'order_by' ); ?>">
			<option value="name_asc" <?php echo ( $value == 'name_asc' ) ? 'selected="selected"' : ''; ?>>Name ASC</option>
			<option value="name_desc" <?php echo ( $value == 'name_desc' ) ? 'selected="selected"' : ''; ?>>Name DESC</option>
			<option value="custom" <?php echo ( $value == 'custom' ) ? 'selected="selected"' : ''; ?>>Custom (from theme options)</option>
		</select>
	</p>
	<!-- end widget form -->
	<?php
	
	}

	// update options
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['order_by'] = $new_instance['order_by'];

		return $instance;
	}

	// display output
	function widget( $args, $instance ){

		extract( $args );

		$order_by = $instance['order_by'];

		global $repute_options;
		$arr_social_links = $repute_options['rpt-social-links'];
		
		if( $order_by == 'name_asc' ) {
			ksort( $arr_social_links );
		} else if( $order_by == 'name_desc' ) {
			krsort( $arr_social_links );
		}

		echo $before_widget;

		if( $instance['title'] )
			echo $before_title . $instance['title'] . $after_title;

?>
		<div class="tdv-social-links">
			<ul class="list-inline social-icons">
			<?php foreach ( $arr_social_links as $social_name => $social_link ) :

					if( $social_link == '' )
						continue;

					$icon_class = '';
					switch ( strtolower($social_name) ) {
						case 'facebook': $icon_class = 'fa-facebook'; break;
						case 'twitter': $icon_class = 'fa-twitter'; break;
						case 'instagram': $icon_class = 'fa-instagram'; break;
						case 'flickr': $icon_class = 'fa-flickr'; break;
						case 'linkedin': $icon_class = 'fa-linkedin'; break;
						case 'pinterest': $icon_class = 'fa-pinterest'; break;
						case 'delicious': $icon_class = 'fa-delicious'; break;
						case 'google plus': $icon_class = 'fa-google-plus'; break;
						case 'stumbleupon': $icon_class = 'fa-stumbleupon'; break;
						case '500px': $icon_class = 'fa-500px'; break;
						case 'foursquare': $icon_class = 'fa-foursquare'; break;
						case 'digg': $icon_class = 'fa-digg'; break;
						case 'spotify': $icon_class = 'fa-spotify'; break;
						case 'reddit': $icon_class = 'fa-reddit'; break;
						case 'dribbble': $icon_class = 'fa-dribbble'; break;
						case 'rss': $icon_class = 'fa-rss'; break;
						case 'skype': $icon_class = 'fa-skype'; break;
						case 'youtube': $icon_class = 'fa-youtube'; break;
						case 'github': $icon_class = 'fa-github'; break;
						case 'soundcloud': $icon_class = 'fa-soundcloud'; break;
						case 'stack overflow': $icon_class = 'fa-stack-overflow'; break;
						case 'steam': $icon_class = 'fa-steam'; break;
						case 'tumblr': $icon_class = 'fa-tumblr'; break;
						case 'vimeo': $icon_class = 'fa-vimeo'; break;
						case 'vine': $icon_class = 'fa-vine'; break;
						case 'yahoo': $icon_class = 'fa-yahoo'; break;
					}

					$bg_class = str_replace( 'fa-', '', $icon_class );

					if( $social_link != '' )
						$link = str_replace( array( 'http://','https://' ), array('',''), $social_link );
			?>
					<li><a href="//<?php echo $link; ?>" class="bgcolor-<?php echo $bg_class; ?>"><i class="fa <?php echo $icon_class ?>"></i></a></li>
			<?php endforeach; ?>
			</ul>
		</div>

<?php
		echo $after_widget;
	}

}

?>