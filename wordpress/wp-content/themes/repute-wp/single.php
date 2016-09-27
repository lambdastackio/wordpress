<?php
	get_header();
	/* Template Name: TDV_Blog-MediumThumbnail */
?>
	<!-- BREADCRUMBS -->
	<div class="page-header">
		<div class="container">
			<!-- <?php the_title(); ?> -->
			<h1 class="page-title pull-left"></h1>
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
					<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
					<!-- BLOG SINGLE -->
					<div class="blog single full-thumbnail">
						<!-- blog post -->
						<article class="entry-post">
							<header class="entry-header">
								<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
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
								<?php endif; ?>
									<div class="content">
											<?php the_content(); ?>
									</div>
							</div>
						</article>
						<!-- end blog post -->
						<hr />

						<!-- author info -->
						<section class="author-info">
								<h3 class="section-heading">About The Author</h3>
								<div class="media">
										<a href="#" class="media-left">
												<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', get_the_author_meta( 'display_name' ) , array( 'class'=>'img-circle' ) ); ?>
										</a>
										<div class="media-body">
												<span class="author-name"><?php the_author_link(); ?></span>
												<p><?php the_author_meta( 'description' ) ?></p>
										</div>
								</div>
						</section>
						<!-- end author info -->
						<hr />
						<?php
								$tags = wp_get_post_tags( $post->ID );
								$tag_ids = array();

								if( $tags ) {
										foreach( $tags as $item_tag )
												$tag_ids[] = $item_tag->term_id;
								}

								$args = array(
										'tag__in' => $tag_ids,
										'post__not_in' => array( $post->ID ),
										'posts_per_page'=> 3
								);

								$query = new WP_Query( $args );
						?>
						<?php if( $query->post_count > 0 ) : ?>
						<!-- related post -->
						<section>
								<h3 class="section-heading">Related Articles</h3>
								<ul class="list-unstyled related-post-list row">
										<?php while( $query->have_posts() ) : $query->the_post(); ?>
										<li class="col-md-4 col-sm-4">
												<a href="#">
														<?php
																if ( has_post_thumbnail() ) {
																the_post_thumbnail('medium', array('class'=>'img-responsive'));
														} ?>
												</a>
												<h4><a href="<?php echo get_the_permalink(); ?>" class="post-title"><?php the_title(); ?></a></h4>
										</li>
										<?php endwhile; ?>
								</ul>

								<?php wp_reset_query(); ?>

						</section>
						<!-- end related post -->
						<hr />
						<?php endif; ?>

					<!-- removed comments here because they cause issues -->

					</div>
				<?php endwhile; ?>

					<!-- END BLOG SINGLE -->

					<?php else: ?>

						<p>
							<?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?>
						</p>

					<?php endif; ?>

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

<?php get_footer(); ?>
