<?php 
	get_header(); 
	/* Template Name: TDV_Portfolio-2Columns */
?>

	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left">Portfolio</h1>
			<ol class="breadcrumb">
				<?php 
					if(function_exists( 'bcn_display' )) {
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
			<?php 
				if( have_posts()) : while( have_posts() ) : the_post();
					the_content();
					endwhile;
				else: ?>

					<p> <?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?> </p>

			<?php endif; ?>

			<!-- ITEM FILTERS -->
			<?php
				$terms = get_terms( "tdv_portfolio_category" );
				$count = count( $terms );
			?>
			<ul class="list-inline portfolio-item-filters">
				<li><a class="active" href="#" data-filter="*">ALL</a></li>
				<?php if( $count > 0 ) : ?>
					<?php foreach ( $terms as $term ) : ?>
						<li><a href="#" data-filter=".<?php echo $term->slug; ?>"><?php echo strtoupper( $term->name ); ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<!-- END ITEM FILTERS -->

			<!-- PORTFOLIO ITEM WRAPPER -->
			<div class="portfolio-item-wrapper">
				<ul class="portfolio-item-list portfolio-isotope portfolio-nospace list-col-2">
					<?php $loop = new WP_Query(array('post_type' => 'tdv_portfolio', 'posts_per_page' => -1, 'showposts' => 9999999 )); ?>
					<?php if ( $loop ) : while ( $loop->have_posts() ) : $loop->the_post(); ?>
					<?php
						$terms = wp_get_post_terms( $post->ID, 'tdv_portfolio_category');
						$cat_slugs = '';
						foreach( $terms as $term ) {
							$cat_slugs .= $term->slug . ' ';
						} 
					?>
					<li class="portfolio-item <?php echo $cat_slugs; ?>">
						<div class="overlay"></div>
						<div class="info">
							<h4 class="title"><?php the_title(); ?></h4>
							<a href="<?php the_permalink(); ?>" class="btn">Read More</a>
						</div>
						<div class="media-wrapper">
							<?php if ( has_post_thumbnail() ) {
									$thumb_id = get_post_thumbnail_id( $post->ID ); 
									$thumb_img = wp_get_attachment_image_src( $thumb_id , 'tdv_portfolio_grid_thumbnail' );
								}
							?>
							<img src="<?php echo $thumb_img[0]; ?>" alt="<?php the_title() ?>">
						</div>
					</li>
					<?php endwhile; endif; ?>
				</ul>
			</div>
			<!-- END PORTFOLIO ITEM WRAPPER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>