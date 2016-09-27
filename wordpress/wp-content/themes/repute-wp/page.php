<?php get_header(); ?>

	<?php if( !is_home() && !is_front_page() ) : ?>
	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left"><?php the_title(); ?></h1>
			<ol class="breadcrumb">
				<?php 
					if( function_exists('bcn_display') ) {
						bcn_display_list();
					}
				?>
			</ol>
		</div>
	</div>
	<!-- END BREADCRUMBS -->
	<?php endif; ?>

	<!-- PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
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
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>