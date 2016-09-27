<?php 
	get_header(); 
	/* Template Name: TDV_Team */
?>

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

			<!-- TEAM -->
			<section class="team">
				<div class="section-content">
					<?php 
						$loop = new WP_Query( array( 'post_type' => 'tdv_team', 'posts_per_page' => -1, 'showposts' => 9999999 ) ); 
						$count = 0;
					
						if ( $loop ) : while ( $loop->have_posts() ) : $loop->the_post();
						$added_class = ( $count % 2 ) ? 'col-md-offset-2' : '';
						$count++;
					?>
					
					<div class="col-md-5 <?php echo $added_class; ?>">
						<div class="team-member media">
							<?php if ( has_post_thumbnail() ) {
									$thumb_id = get_post_thumbnail_id( $post->ID ); 
									$thumb_img = wp_get_attachment_image_src( $thumb_id , 'full' );
								}
							?>
							<img src="<?php echo $thumb_img[0]; ?>" class="media-object img-circle pull-left" alt="<?php the_title() ?>">
							<div class="media-body">
								<?php
									$team_member = get_post_meta( $post->ID, 'tdv_team_member', true);
								?>
								<h3 class="media-heading team-name"><?php the_title(); ?></h3>
								<strong><?php echo $team_member['tdv-team_title']; ?></strong>
								<hr class="pull-left"><div class="clearfix"></div>

								<?php the_content(); ?>
								
								<ul class="list-inline social-icon">
									<li><a href="<?php echo $team_member['team_social_link1'][0]; ?>"><i class="<?php echo $team_member['team_social_link1'][1]; ?>"></i></a></li>
									<li><a href="<?php echo $team_member['team_social_link2'][0]; ?>"><i class="<?php echo $team_member['team_social_link2'][1]; ?>"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php else: ?>
						<p> <?php _e( 'There are no posts to display. Try using the search.', 'repute' ); ?> </p>
					<?php endif; ?>

				</div>
			</section>
			<!-- END TEAM -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->

<?php get_footer(); ?>