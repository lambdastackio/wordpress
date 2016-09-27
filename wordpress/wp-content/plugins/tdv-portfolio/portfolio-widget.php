<?php

/* Recent Portfolio Widget */

Class Tdv_Recent_Portfolio extends WP_Widget {
	
	// the constructor, setup
	function Tdv_Recent_Portfolio() {
	
		$widget_ops = array( 
			'classname' => 'tdv-recent-portfolio',
			'description' => __( 'Display Recent Portfolio', 'tdv' )
		);
		
		parent::__construct( 'tdv-recent-portfolio', 'TDV Recent Portfolio', $widget_ops );
	}
	
	// build widget form
	function form( $instance ) {
		
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Recent Portfolio', 'fetch_num' => 5, 'with_carousel' => 1 ) );
		
		$title = esc_attr( $instance['title'] );
		$is_checked = ( $instance['with_carousel'] ) ? 'checked' : '';
	?>
	<!-- widget form -->
	
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title:', 'tdv' ); ?></label>
		<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>" class="widefat" >
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'fetch_num' ); ?>"><?php echo __( 'Count:', 'tdv' ); ?></label><br>
		<input type="text" id="<?php echo $this->get_field_id( 'fetch_num' ); ?>" name="<?php echo $this->get_field_name( 'fetch_num' ); ?>" value="<?php echo $instance['fetch_num']; ?>" size="2">
	</p>
	<p>
		<label for="<?php echo $this->get_field_id( 'with_carousel' ); ?>">
			<input type="checkbox" id="<?php echo $this->get_field_id( 'with_carousel' ); ?>" name="<?php echo $this->get_field_name( 'with_carousel' ); ?>" <?php echo $is_checked; ?> class="widefat" >
			<?php echo __( 'With Carousel', 'tdv' ); ?>
		</label>
	</p>

	<!-- end widget form -->
	<?php

	}

	// update options
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['fetch_num'] = strip_tags( $new_instance['fetch_num'] );
		$instance['with_carousel'] = $new_instance['with_carousel'];

		return $instance;
	}
	
	// display output
	function widget( $args, $instance ) {

		extract( $args );

		$title = $instance['title'];
		$fetch_num = $instance['fetch_num'];
		$with_carousel = $instance['with_carousel'];

		if( $with_carousel ) {
			wp_register_script( 'slick', plugins_url( 'includes/slick/slick.min.js', __FILE__ ), array( 'jquery' ), '1.5.8', true );
			wp_register_style( 'slick', plugins_url( 'includes/slick/slick.css', __FILE__ ) );

			wp_enqueue_script( 'slick' );
			wp_enqueue_style( 'slick' );
		}

		global $post;

		$loop_args = array(
			'post_type' => 'tdv_portfolio',
			'posts_per_page' => $fetch_num
		);

		query_posts( $loop_args );

		echo $before_widget;
		
		if( $title )
			echo $before_title . $title . $after_title;
?>

		<?php if( $with_carousel ) : ?>

			<div class="slick-carousel">
				<div class="portfolio-container">
				<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<div class="portfolio-item">
						<div class="overlay"></div>
						<div class="info">
							<h4 class="title"><?php the_title(); ?></h4>
							<a href="<?php the_permalink(); ?>" class="btn">Read More</a>
						</div>
						<div class="media-wrapper">
							<?php the_post_thumbnail( 'tdv_portfolio_thumbnail' ); ?>
						</div>
					</div>
				<?php endwhile; ?>
				<?php else: ?>
					<p><?php _e( 'There are no posts to display. Try using the search.', 'tdv' ); ?></p> 
				<?php endif; wp_reset_query(); ?>
				</div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('.slick-carousel .portfolio-container').slick({
						dots: true,
						slidesToShow: 3,
						cssEase: 'ease-in',
						prevArrow: '<button type="button" data-role="none" class="btn slick-prev">Previous</button>',
						nextArrow: '<button type="button" data-role="none" class="btn slick-next">Next</button>',
						responsive: [
							{
								breakpoint: 993,
								settings: {
									slidesToShow: 2
								}
							},
							{
								breakpoint: 481,
								settings: {
									slidesToShow: 1
								}
							}
						]
					});
				});
			</script>

		<?php else : ?>

			<div class="portfolio-static">
				<div class="row">
					<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<div class="col-md-4">
						<div class="portfolio-item">
							<div class="overlay"></div>
							<div class="info">
								<h4 class="title"><?php the_title(); ?></h4>
								<a href="<?php the_permalink(); ?>" class="btn">Read More</a>
							</div>
							<div class="media-wrapper">
								<?php the_post_thumbnail( 'large' ); ?>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php else: ?>
						<p><?php _e( 'There are no posts to display. Try using the search.', 'tdv' ); ?></p> 
					<?php endif; wp_reset_query(); ?>
				</div>
			</div>

		<?php endif; ?>

<?php
		echo $after_widget;
	}
}

?>