<?php get_header(); ?>

	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<h1 class="page-title pull-left">
				<?php
					if( is_tag() ) {
					
						single_tag_title( __('Posts Tagged: ', 'repute' ) );
					
					} elseif( is_category() ) {
					
						single_cat_title( __('Posts In Category: ', 'repute' ) );
					
					} elseif( is_day() ) {
						
						echo  __('Daily Archives: ', 'repute')  . get_the_time(get_option('date_format'));
						
					} elseif( is_month() ) {
					
						echo  __('Monthly Archives: ', 'repute')  . get_the_time('F Y');
					
					} elseif( is_year() ) {

						echo  __('Yearly Archives: ', 'repute')  . get_the_time('Y');
					}
				?>
			</h1>
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
				<div class="col-md-9">
					<!-- BLOG -->
					<div class="blog full-thumbnail">

					<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
						<!-- blog post -->
						<article class="entry-post">
							<header class="entry-header">
								<h2 class="entry-title">
									<a href="<?php get_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
								<div class="meta-line clearfix">
									<div class="meta-author-category pull-left">
										<span class="post-author">by <?php the_author_link(); ?></span>
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
									<a href="<?php the_permalink(); ?>"><img src="<?php echo $thumb_img[0]; ?>" class="img-responsive" alt="<?php the_title() ?>"></a>
								</figure>
								<div class="content">
									<?php the_excerpt(); ?>
									<p class="read-more">
										<a href="<?php the_permalink(); ?>" class="btn btn-primary">Read More <i class="fa fa-long-arrow-right"></i></a>
									</p>
								</div>
							</div>
						</article>
						<!-- end blog post -->

						<hr>
					<?php endwhile; ?>
					
					</div>
					<!-- END BLOG -->
					
					<?php else: ?>
					
						<p>
							<?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?>
						</p>
					
					<?php endif; ?>

					<?php
						$prev_link = get_previous_posts_link( __( 'Newer &rarr;', 'repute' ) );
						$next_link = get_next_posts_link( __( '&larr; Older', 'repute' ) );
					?>
					<!-- pagination -->
					<?php if( $prev_link && $next_link ) : ?>
					<ul class="pager">
						<?php echo ( $prev_link != '' ) ? '<li class="previous">' . $next_link . '</li>' : ''; ?>
						<?php echo ( $prev_link != '' ) ? '<li class="next">' . $prev_link . '</li>' : ''; ?>
					</ul>
					<?php elseif ( $prev_link ) : ?>
					<ul class="pager">
						<?php echo ( $prev_link != '' ) ? '<li class="next">' . $prev_link . '</li>' : ''; ?>
					</ul>
					<?php elseif ( $next_link ) : ?>
					<ul class="pager">
						<?php echo ( $next_link != '' ) ? '<li class="previous">' . $next_link . '</li>' : ''; ?>
					</ul>
					<?php endif; ?>
					<!-- end pagination -->

					<?php wp_reset_query(); ?>

				</div>
				<div class="col-md-3">
					<!-- SIDEBAR -->
					<?php
						if( function_exists( 'dynamic_sidebar' ) ){
							if ( !dynamic_sidebar( 'tdv-page' ) && current_user_can('edit_theme_options') ) :
								printf( __( 'Your theme supports sidebar, please go to Appearance &raquo <a href="%s">Widgets</a> in admin area.' ), admin_url('widgets.php') );
							endif;
						}
					?>
					<!-- END SIDEBAR -->
				</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<? get_footer(); ?>