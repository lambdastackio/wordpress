<?php 
	get_header(); 
	/* Template Name: TDV_Page-Home */
?>

<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
	<?php 
		if ( has_post_thumbnail() ) {
			the_post_thumbnail();
		}
	?>

	<?php the_content(); ?>

	<?php endwhile; else: ?>

	<p>
		<?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?>
	</p>

<?php endif; ?>

<?php get_footer(); ?>
