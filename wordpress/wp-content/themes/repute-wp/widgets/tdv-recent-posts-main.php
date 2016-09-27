<?php
/*
Plugin Name: TDV Recent Posts (Main Content)
Plugin URI: https://themeineed.com
Description: A custom recent posts, more suitable for main content
Version: 1.0
Author: The Develovers
Author URI: https://themeineed.com
*/

Class Tdv_Recent_Posts_Main extends WP_Widget {

	// the constructor, setup
	function Tdv_Recent_Posts_Main() {
		$widget_ops = array( 
			'classname' => 'tdv-recent-posts-main',
			'description' => __('Display Recent Posts with small thumbnail, more suitable for main content', 'repute')
		);

		parent::__construct( 'tdv-recent-posts-main', 'TDV Recent Posts (Main Content)', $widget_ops );
	}
	
	// build widget form
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Latest News' ) );
		$title = esc_attr( $instance['title'] );
	?>
	<!-- widget form -->
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'repute' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" >
	</p>
	<!-- end widget form -->
	<?php
	}
	
	// update options
	function update( $new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}
	
	// display output
	function widget( $args, $instance ) {
		extract( $args );
		
		echo $before_widget;
		
		if( $instance['title'] )
			echo $before_title . $instance['title'] . $after_title;
			
		// fetch recent posts
		$recent = new WP_Query( apply_filters( 'widget_posts_args', array( 'posts_per_page' => 6, 'no_found_rows' => true, 'post_status' => 'publish', 'ignore_sticky_posts' => true ) ) );
		if( $recent->have_posts() ) :

			$count = 0;
	?>
		<div class="row">
		
		<?php while( $recent->have_posts() ) : $recent->the_post(); ?>
		
		<?php

			if( has_post_thumbnail() ) {
				$thumb_id = get_post_thumbnail_id( get_the_ID() ); 
				$thumbnail = wp_get_attachment_image_src( $thumb_id, 'medium' ); 
				$thumb_url = $thumbnail[0];
			}
		?>

		<?php if( $count == 0 ) : ?>
			<div class="col-md-4">
				<div class="news-item news-featured">
					<a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><img src="<?php echo $thumb_url; ?>" class="img-responsive" alt="<?php the_title(); ?>"></a>
					<h3 class="news-title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
					<p><?php echo substr( get_the_excerpt(), 0, 150 ); ?> ...</p>
					<div class="news-meta">
						<span class="news-datetime"><?php echo get_the_date(); ?></span>
						<span class="news-comment-count pull-right">
						<?php
							comments_popup_link(
								__( '0 Comment','repute' ),
								__( '1 Comment','repute' ),
								__( '% Comments','repute' ), '',
								__( 'Comments are off','repute' ) 
							);
						?></span>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<div class="row">
		<?php 
			$count++;
			continue; 
		?>
		<?php else : ?>
					<div class="col-md-12 col-lg-6">
						<div class="news-item margin-bottom-30px clearfix">
							<a href="#"><img src="<?php echo $thumb_url; ?>" class="img-responsive pull-left" alt="<?php the_title(); ?>"></a>
							<div class="right">
								<h3 class="news-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p><?php echo substr( get_the_excerpt(), 0, 90 ); ?> ...</p>
							</div>
						</div>
					</div>
		<?php endif; ?>
		<?php
			endwhile; 
		?>
					<div class="col-md-12 col-lg-6">
						<div class="see-all-news">
							<a href="<?php echo bloginfo('url') . '/blog/'; ?>">See all news <i class="fa fa-long-arrow-right"></i></a>
						</div>
					</div>
				</div>
			</div>

		</div>
	
	<?php
		wp_reset_query();

		endif;
			
		echo $after_widget;
	}

}

?>