<?php

/* output testimonial shortcode */
function tdv_scode_testimonial( $atts ) {
	ob_start();
	$id = rand( 0, 99999 );
?>
	<div id="testimonial_<?php echo $id; ?>" class="testimonial slick-carousel">
		<div class="testimonial-container">
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'tdv_testimonial', 'posts_per_page' => -1, 'showposts' => 9999999 ) ); 
		$count = 0;
	
		if ( $loop ) : while ( $loop->have_posts() ) : $loop->the_post();
	?>
			<div class="testimonial-body">
				<?php the_content(); ?>
				<div class="testimonial-author">
					<?php if ( has_post_thumbnail() ) {
							$thumb_id = get_post_thumbnail_id( $loop->post->ID ); 
							$thumb_img = wp_get_attachment_image_src( $thumb_id , 'thumbnail' );
						}
					?>
					<img src="<?php echo $thumb_img[0]; ?>" class="pull-left" alt="<?php the_title() ?>">
					<?php
						$testimonial = get_post_meta( $loop->post->ID, 'tdv_testimonial_item', true );
					?>
					<span>
						<span class="author-name"><?php the_title(); ?></span> 
						<em><?php echo $testimonial['tdv-author_title'] ?> 
						<?php echo ( $testimonial['tdv-company_name'] != '' ) ? ' of ' . $testimonial['tdv-company_name'] : '' ?>
						</em>
					</span>
				</div>
			</div>
			<?php endwhile; ?>
			<?php else: ?>
				<p> <?php _e( 'There are no testimonials to display', 'repute' ); ?> </p>
			<?php endif; ?>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#testimonial_<?php echo $id; ?> .testimonial-container').slick({
				speed: 500,
				fade: true,
				prevArrow: '<button type="button" data-role="none" class="btn slick-prev">Previous</button>',
				nextArrow: '<button type="button" data-role="none" class="btn slick-next">Next</button>',
			});
		});
	</script>
<?php 
	return ob_get_clean();
}

add_shortcode( 'tdv_testimonial', 'tdv_scode_testimonial' );

?>