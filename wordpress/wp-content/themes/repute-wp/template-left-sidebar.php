<?php 
	get_header(); 
	/* Template Name: TDV_Page-LeftSidebar */
?>

	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left"><?php the_title(); ?></h1>
			<ol class="breadcrumb">
				<?php 
					if( function_exists( 'bcn_display' ) ) { 
						bcn_display_list();
					}
				?>
			</ol>
		</div>
	</div>
	<!-- END BREADCRUMBS -->

	<!-- PAGE CONTENT -->
	<div class="page-content">
		<div class="container">
			<div class="row">
				<div class="col-md-3">
					<?php
						if( function_exists( 'dynamic_sidebar' ) ){
							if ( !dynamic_sidebar( 'tdv-page' ) && current_user_can( 'edit_theme_options' ) ) :
								printf( __( 'Your theme supports sidebar, please go to Appearance &raquo <a href="%s">Widgets</a> in admin area.' ), admin_url( 'widgets.php' ) );
							endif;
						}
					?>
				</div>
				<div class="col-md-9">
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
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>