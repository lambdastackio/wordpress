<?php
/*
Plugin Name: TDV Recent Posts
Plugin URI: https://themeineed.com
Description: A custom recent posts widget with thumbnail
Version: 1.0
Author: The Develovers
Author URI: https://themeineed.com
*/

Class Tdv_Recent_Posts extends WP_Widget {

	// the constructor, setup
	function Tdv_Recent_Posts() {
		$widget_ops = array( 
			'classname' => 'tdv-recent-posts-widget',
			'description' => __('Display Recent Posts with small thumbnail taken from post featured image', 'repute')
		);

		parent::__construct( 'tdv-recent-posts-widget', 'TDV Recent Posts', $widget_ops );
	}
	
	// build widget form
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Recent Posts', 'fetch_num' => 5, 'display_date' => true) );
		$title = esc_attr( $instance['title'] );
	?>
	<!-- widget form -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'repute' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" >
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'fetch_num' ); ?>"><?php echo __( 'Fetch Num:', 'repute' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'fetch_num' ); ?>" name="<?php echo $this->get_field_name( 'fetch_num' ); ?>" value="<?php echo $instance['fetch_num']; ?>" class="widefat" >
	</p>
	<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'display_date' ); ?>" name="<?php echo $this->get_field_name( 'display_date' ); ?>" value="<?php echo $instance['display_date']; ?>" <?php echo  isset($instance['display_date']) ?  'checked="checked"' : '' ?> >
		<label for="<?php echo $this->get_field_id( 'display_date' ); ?>"><?php echo __( 'Display Post Date','repute' ); ?></label>
	</p> 
	<!-- end widget form -->
	<?php
	}
	
	// update options
	function update( $new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fetch_num'] = $new_instance['fetch_num'];
		$instance['display_date'] = $new_instance['display_date'];

		return $instance;
	}
	
	// display output
	function widget( $args, $instance ) {
		extract( $args );
		
		echo $before_widget;
		
		if( $instance['title'] )
			echo $before_title . $instance['title'] . $after_title;
			
		// fetch recent posts
		$recent = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => $instance['fetch_num'], 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if ( $recent->have_posts() ) :
	?>
		<ul class="list-unstyled recent-posts">
		
		<?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
		
		<?php

			if ( has_post_thumbnail() ) {
				$thumb_id = get_post_thumbnail_id( get_the_ID() ); 
				$thumbnail = wp_get_attachment_image_src( $thumb_id, 'thumbnail' ); 
				$thumb_url = $thumbnail[0];
			}
		?>
			<li>
				<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>">
					<figure class="post-thumb"><img src="<?php echo $thumb_url ?>" class="img-responsive" alt="<?php the_title(); ?>"></figure>
				</a>
				<figcaption class="clearfix">
					<h5 class="post-header"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title() ?></a></h5>
				
				<?php if( isset($instance['display_date']) ) : ?>
				
					<span class="text-muted post-meta-time"><?php echo get_the_date(); ?></span>

				<?php endif; ?>
				
				</figcaption>
				
			</li>
		
		<?php endwhile; ?>
			
		</ul>
	
	<?php
		wp_reset_query();

		endif;
			
		echo $after_widget;
	}

}

?>