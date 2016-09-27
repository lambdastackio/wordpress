<?php get_header(); ?>

	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left">Portfolio</h1>
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
			<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
			<!-- PORTFOLIO ITEM -->
			<div class="portfolio-media">
				<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail();
					}
				?>
			</div>
			<div class="row">
				<div class="col-md-8">
					<section>
						<h2 class="section-heading">PROJECT DESCRIPTION</h2>
						<?php the_content() ?>
					</section>
				</div>
				<div class="col-md-4">
					<?php
						$arr_data = get_post_meta( $post->ID, 'tdv_portfolio', true );
					?>
					<section>
						<h2 class="section-heading">PROJECT DETAIL</h2>
						<ul class="list-unstyled project-detail-list">
							<li><strong>Title:</strong> <?php echo get_the_title(); ?></li>
							<li><strong>Client:</strong> <?php echo $arr_data['tdv_portfolio_client_name']; ?></li>
							<li><strong>Skills:</strong> <?php echo $arr_data['tdv_portfolio_skills']; ?></li>
							<li><strong>Categories:</strong> 
							<?php
								$terms = wp_get_post_terms( $post->ID, 'tdv_portfolio_category' );
								$cat_arr = array();

								foreach ( $terms as $term ) {
									$cat_arr[] = '<a href="' . get_term_link( $term->slug, 'tdv_portfolio_category' ) . '">'.$term->name.'</a>';
								}

								echo implode( ', ', $cat_arr );
							?> 
							</li>
						</ul>
						<?php
							$url = $arr_data['tdv_portfolio_website_url'];

							if( $url != '' ) : 
								$link = str_replace( array( 'http://','https://' ), array('',''), $url );
						?>
							<a href="http://<?php echo $link; ?>" class="btn btn-default"><i class="fa fa-external-link"></i> Visit Website</a>
						<?php endif; ?>
					</section>
				</div>
			</div>
			<!-- END PORTFOLIO ITEM -->
			<?php endwhile; ?>
			<?php else: ?>
				<p> <?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?> </p>
			<?php endif; ?>

			<hr>
			<!-- RELATED PORTFOLIO -->
			<div class="portfolio-item-wrapper portfolio-related">
				<h2 class="section-heading">RELATED WORKS</h2>
				<?php

					$terms = wp_get_post_terms( $post->ID, 'tdv_portfolio_category' );
					$arr_cat_slug = array();

					foreach ( $terms as $term ) {
						$arr_cat_slug[] = $term->slug;
					}

					$args = array(
						'post_type' => 'tdv_portfolio',
						'tax_query' => array(
							array(
								'taxonomy' => 'tdv_portfolio_category',
								'field' => 'slug',
								'terms' => $arr_cat_slug
							)),
						'post__not_in' => array( $post->ID ),
						'showposts' => 3
						);
					$loop = query_posts( $args );
				?>
				<ul class="portfolio-item-list spaced row">
				<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<li class="col-md-4">
						<div class="portfolio-item">
							<div class="overlay"></div>
							<div class="info">
								<h4 class="title"><?php the_title(); ?></h4>
								<a href="<?php the_permalink(); ?>" class="btn">Read More</a>
							</div>
							<div class="media-wrapper">
								<?php if ( has_post_thumbnail() ) {
										$thumb_id = get_post_thumbnail_id( $post->ID ); 
										$thumb_img_full = wp_get_attachment_image_src( $thumb_id , 'tdv_portfolio_thumbnail' );
									}
								?>
								<img src="<?php echo $thumb_img_full[0]; ?>" alt="<?php the_title() ?>">
							</div>
						</div>
					</li>
				<?php
					endwhile;
					endif;
					wp_reset_query();
				?>
				</ul>
			</div>
			<!-- END RELATED PORTFOLIO -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>