<?php
/*
Plugin Name: TDV Twitter
Plugin URI: https://themeineed.com
Description: A simple Twitter widget
Version: 1.1.0
Author: The Develovers
Author URI: https://themeineed.com
*/
// no direct access
if ( !defined( 'ABSPATH' ) ) exit;


/* a widget class definition */
Class Tdv_Twitter extends WP_Widget {

	// the constructor, setup
	function Tdv_Twitter() {
		$widget_ops = array( 
			'classname' => 'tdv-twitter-widget',
			'description' => __( 'Display Twitter stream', 'tdv' )
		);
		
		parent::__construct( 'tdv-twitter-widget', 'TDV Twitter', $widget_ops );
	}
	
	// build widget form
	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Twitter', 'widget_id' => '', 'count' => 5 ) );
		
		$title = esc_attr( $instance['title'] );
	?>

	<!-- widget form: tdv twitter -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'tdv' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" class="widefat" >
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'widget_id' ); ?>"><?php echo __( 'Twitter Widget ID:', 'tdv' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'widget_id' ); ?>" name="<?php echo $this->get_field_name( 'widget_id' ); ?>" value="<?php echo $instance['widget_id']; ?>" class="widefat" >
		<a href="#" id="get-widget-id">How To Get Twitter Widget ID?</a>
		<ul id="guide-widget-id">
			<li>- Go to www.twitter.com and sign in as normal, go to your settings page.</li>
			<li>- Go to "Widgets" on the left hand side.</li>
			<li>- Create a new widget for what you need eg "user timeline" or "search" etc.</li>
			<li>- Feel free to check "exclude replies" if you dont want replies in results.</li>
			<li>- Now go back to settings page, and then go back to widgets page, you should see the widget you just created. Click edit.</li>
			<li>- Now look at the URL in your web browser, you will see a long number like this: 441767385733668865</li>
			<li>- Use this as your ID below instead!</li>
		</ul>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo __( 'Count:', 'tdv' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $instance['count']; ?>" class="widefat" >
	</p>
	<!-- end widget form: tdv twitter -->
	<?php
	
	}
	
	// update options
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['widget_id'] = $new_instance['widget_id'];
		$instance['count'] = $new_instance['count'];
		
		return $instance;
	}
	
	// display output
	function widget( $args, $instance ) {

		extract( $args );

		wp_register_script( 'moment', plugins_url( 'js/moment/moment.min.js', __FILE__ ), array( 'jquery' ), '2.12.0', true );
		wp_deregister_script( 'tdv-tweet' );
		wp_register_script( 'tdv-tweet', plugins_url( 'js/twitter-fetcher.js', __FILE__ ), array( 'jquery' ), '7.0', true );
		wp_register_style( 'tdv-twitter', plugins_url( 'css/twitter-list.css', __FILE__ ) );

		wp_enqueue_script( 'moment' );
		wp_enqueue_script( 'tdv-tweet' );
		wp_enqueue_style( 'tdv-twitter' );
		
		$title = $instance['title'];
		
		$twitter_param = array(
			'widget_id' => $instance['widget_id'],
			'count' => $instance['count'],
		);

		wp_localize_script( 'tdv-tweet', 'TDV_TWITTER', $twitter_param );

		echo $before_widget;
		
		if( $title )
			echo $before_title . $title . $after_title;
			
		echo '<div id="tdv-tweet"></div>';
		echo '<script type="text/javascript">';
		echo 'jQuery(document).ready(function(){' .
				'if( typeof TDV_TWITTER != \'undefined\' ){' .
					'twitterFetcher.fetch({ "id": TDV_TWITTER.widget_id, "maxTweets": TDV_TWITTER.count, enableLinks: true, "dateFunction": momentDateFormatter, "dataOnly": true, "customCallback": populateTpl });' .
				'}' .
			'});';
		echo '</script>';

		echo $after_widget;
	}
}

/* register widget */
function tdv_register_twitter_widget() {
	register_widget( "Tdv_Twitter" );
}

add_action( 'widgets_init', 'tdv_register_twitter_widget' );

?>