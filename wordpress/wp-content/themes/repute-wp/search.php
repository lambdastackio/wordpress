<?php
	get_header();

	global $wp_query;

	if( empty( $paged ) )
		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
?>
	<?php
		$args = array(
			's' => $s,
			'orderby' => 'type',
			'order' => 'DESC',
			'paged' => $paged
		);

		$allsearch = new WP_Query( $args );
		$count = $allsearch->found_posts;
	?>

	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left"><? echo $count . ' ' . sprintf( __('search result for &#39%s&#39', 'repute'), esc_html($s, 1) ) ?></h1>
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
	<div class="page-content search-results">
		<div class="container">
			<div class="row">
				<div class="col-md-9">
					<?php if( $allsearch->have_posts() ) : ?>

						<?php while( $allsearch->have_posts() ) : $allsearch->the_post(); ?>
							<?php if( $post->post_type == 'post' ) : ?>
							<!-- BLOG -->
							<div class="blog blog-result">
								<!-- blog post -->
								<article class="entry-post">
									<header class="entry-header">
										<h2 class="entry-title"><a href="<?php get_permalink(); ?>"><?php the_title(); ?></a></h2>
										<div class="meta-line clearfix">
											<div class="meta-author-category pull-left">
												<span class="post-author">by <?php the_author_link(); ?></span>
                        <span class="post-category"><?php echo get_the_date(); ?></span>
												<span class="post-category">In: <?php echo get_the_category_list( ', ' ); ?></span>
											</div>
											<div class="meta-tag-comment pull-right">
												<span class="post-tags"><i class="fa fa-tag"></i> <?php the_tags(); ?></span>
												<span class="post-comment"><i class="fa fa-comments"></i>
												<?php
													comments_popup_link(
														__( '0 Comment','repute' ),
														__( '1 Comment','repute' ),
														__( '% Comments','repute' ), '',
														__( 'Comments are off','repute' )
													);
												?>
												</span>
											</div>
										</div>
									</header>
									<div class="entry-content clearfix">
                    <?php if ( has_post_thumbnail() ) : ?>
                      <div class="col-sm-5">
                      <figure class="featured-image">
                          <?php
                              $date = get_the_date( 'M,d,Y' );
                              $arr_date = explode( ',', $date );
                          ?>
                          <div class="post-date-info clearfix"><span class="post-month"><?php echo $arr_date[0]; ?></span><span class="post-date"><?php echo $arr_date[1]; ?></span><span class="post-year"><?php echo $arr_date[2]; ?></span></div>
                          <?php if ( has_post_thumbnail() ) {
                                  $thumb_id = get_post_thumbnail_id( $post->ID );
                                  $thumb_img = wp_get_attachment_image_src( $thumb_id , 'large' );
                              }
                          ?>
                                                              <!-- alt="<?php the_title() ?>" -->
                          <img src="<?php echo $thumb_img[0]; ?>" class="img-responsive" >
                      </figure>
                    </div>
                    <div class="col-sm-7">
                    <?php else: ?>
                    <div class="col-sm-12">
                    <?php endif; ?>
										<div class="excerpt">
											<?php the_excerpt(); ?>
											<p class="read-more">
												<a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More <i class="fa fa-long-arrow-right"></i></a>
											</p>
										</div>
									</div>
								</article>
								<!-- end blog post -->
								<hr>
							</div>
							<!-- END BLOG -->
							<?php elseif( $post->post_type == 'page' ) : ?>
								<div class="page-result">
									<h2 class="page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
									<span class="badge">Page</span>
								</div>
							<?php endif; ?>
						<?php endwhile; ?>

					<?php else : ?>

						<p>
							<?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'repute' ); ?>
						</p>

					<?php endif; ?>


					<?php
						$max_num_pages = $wp_query->max_num_pages;
						if( $max_num_pages > 1 ) :
					?>
					<div class="text-center">
						<ul class="pagination pagination-sm">
						<li <?php echo ( $paged == 1 ) ? 'class="disabled"' : ''; ?>><a href="<?php echo ( $paged > 1 ) ? get_pagenum_link( $paged - 1 ) : '#'; ?>"><i class="fa fa-angle-left"></i><span class="sr-only">Previous</span></a></li>
						<?php
							for( $i=1; $i <= $max_num_pages; $i++ ) :
						?>
							<?php if( $i == $paged ) : ?>
								<li class="active"><a href="#"><?php echo $i; ?></a></li>
								<?php else: ?>
								<li><a href="<?php echo get_pagenum_link( $i ); ?>"><?php echo $i; ?></a></li>
							<?php endif; ?>
						<?php endfor; ?>
						<li <?php echo ( $paged == $max_num_pages ) ? 'class="disabled"' : ''; ?>><a href="<?php echo ( $paged < $max_num_pages ) ? get_pagenum_link( $paged + 1 ) : '#'; ?>"><i class="fa fa-angle-right"></i><span class="sr-only">Next</span></a></li>
						</ul>
					</div>
					<?php endif; ?>

					<?php wp_reset_query(); ?>
				</div>
				<div class="col-md-3">
					<?php
						if( function_exists( 'dynamic_sidebar' ) ){
							if ( !dynamic_sidebar( 'tdv-page' ) && current_user_can( 'edit_theme_options' ) ) :
								printf( __( 'Your theme supports sidebar, please go to Appearance &raquo <a href="%s">Widgets</a> in admin area.' ), admin_url( 'widgets.php' ) );
							endif;
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>
