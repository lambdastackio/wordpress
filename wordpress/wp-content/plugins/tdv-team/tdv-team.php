<?php
/*
Plugin Name: TDV Team
Plugin URI: https://themeineed.com
Description: Plugin to list member of the team.
Version: 1.0.0
Author: The Develovers
Author URI: https://themeineed.com
*/
// no direct access
if ( !defined( 'ABSPATH' ) ) exit;

/* include scripts */ 
function tdv_include_team_scripts() {
	wp_register_style( 'tdv-team', plugins_url('/css/team.css', __FILE__ ) );
	wp_enqueue_style( 'tdv-team' );
}

add_action( 'init', 'tdv_include_team_scripts' );

/* create team post type */
function tdv_create_team_post_type() {

	register_post_type( 
		'tdv_team',
		array(
			'labels' => array(
				'name' => 'Team',
				'singular_name' => 'Member',
				'add_new' => 'Add New',
				'add_new_item' => 'Add New Member',
				'edit' => 'Edit',
				'edit_item' => 'Edit Member',
				'new_item' => 'New Member',
				'not_found' => 'No member found',
				'not_found_in_trash' => 'No member found in Trash'
			),
			'public' => true,
			'menu_position' => 20,
			'show_in_nav_menus' => false,
			'supports' => array( 'title', 'editor', 'thumbnail'),
			'taxonomies' => array( '' ),
			'menu_icon' => 'dashicons-groups',
			'has_archive' => true
		)
	);
}

add_action( 'init', 'tdv_create_team_post_type' );

/* add metabox */
function tdv_add_team_metabox() {
	add_meta_box( 'tdv_team_metabox', 'Team Member Info', 'tdv_team_build_fields' ,'tdv_team', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'tdv_add_team_metabox' );

/* build the metabox fields */
function tdv_team_build_fields( $post ) {
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'tdv_save_team_metabox_data', 'tdv_metabox_nonce' );

	include_once( 'team-fields.php' );

	$team_member = get_post_meta( $post->ID, 'tdv_team_member', true );

	echo '<table class="form-table meta_box">';
	foreach ( $tdv_team_fields as $field ) {

		if( empty( $field['type'] ) ) {
			$value = !empty( $team_member[$field['id']] ) ? $team_member[$field['id']] : '';

			echo '<tr>
					<th scope="row" style="width:20%"><label for="' . $field['id'] . '">' . $field['label'] . '</label></th>
					<td>';
					echo '<input type="text" id="' . $field['id'] .'" name="' . $field['id'] .'" value="' . esc_attr( $value ) . '" size="25" />';
					
			echo 	'</td>';
			echo '</tr>';
		} else {
			echo '<tr>' .
					'<th scope="row" style="width:20%"><label>' . $field['label'] . '</label></th>';
			echo 	'<td>';
						$member_social = !empty( $team_member[$field['id']] ) ? $team_member[$field['id']] : '';

						echo '<label>Social Icon Class</label>: ';
						echo '<input type="text" name="' . $field['id'] .'[]" value="' . esc_attr( $member_social[0] ) . '" size="15" />&nbsp;&nbsp;';

						echo '<label>Social URL</label>: ';
						echo '<input type="text" name="' . $field['id'] .'[]" value="' . esc_attr( $member_social[1] ) . '" size="35" />';
			echo 	'</td>';
			echo '</tr>';
		}
		
	}

	echo '</table>';
}

/* saving metabox data */
function tdv_save_team_metabox_data( $post_id ) {

	// check if our nonce is set.
	if( ! isset( $_POST['tdv_metabox_nonce'] ) ) {
		return;
	}

	// verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['tdv_metabox_nonce'], 'tdv_save_team_metabox_data' ) ) {
		return;
	}

	// if this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	include_once( 'team-fields.php' );

	/* saving data */
	foreach ( $tdv_team_fields as $field ) {

		// Make sure that it is set.
		if ( ! isset( $_POST[$field['id']] ) ) {
			return;
		}

		$arr_data[$field['id']] = $_POST[$field['id']];
	}

	update_post_meta( $post_id, 'tdv_team_member', $arr_data );
}

add_action( 'save_post', 'tdv_save_team_metabox_data' );

/* output for team shortcode */
function tdv_scode_team( $atts ) {
	ob_start();
?>
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
							$thumb_id = get_post_thumbnail_id( $loop->post->ID ); 
							$thumb_img = wp_get_attachment_image_src( $thumb_id , 'full' );
						}
					?>
					<img src="<?php echo $thumb_img[0]; ?>" class="media-object img-circle pull-left" alt="<?php the_title() ?>">
					<div class="media-body">
						<?php
							$team_member = get_post_meta( $loop->post->ID, 'tdv_team_member', true );
						?>
						<h3 class="media-heading team-name"><?php the_title(); ?></h3>
						<strong><?php echo $team_member['tdv-team_title'] ?></strong>
						<hr class="pull-left"><div class="clearfix"></div>
						<?php the_content(); ?>
						<ul class="list-inline social-icon">
							<li><a href="<?php echo $team_member['tdv-team_social_link1'][1]; ?>"><i class="<?php echo $team_member['tdv-team_social_link1'][0]; ?>"></i></a></li>
							<li><a href="<?php echo $team_member['tdv-team_social_link2'][1]; ?>"><i class="<?php echo $team_member['tdv-team_social_link2'][0]; ?>"></i></a></li>
							<li><a href="<?php echo $team_member['tdv-team_social_link3'][1]; ?>"><i class="<?php echo $team_member['tdv-team_social_link3'][0]; ?>"></i></a></li>
							<li><a href="<?php echo $team_member['tdv-team_social_link4'][1]; ?>"><i class="<?php echo $team_member['tdv-team_social_link4'][0]; ?>"></i></a></li>
							<li><a href="<?php echo $team_member['tdv-team_social_link5'][1]; ?>"><i class="<?php echo $team_member['tdv-team_social_link5'][0]; ?>"></i></a></li>
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
<?php 
	return ob_get_clean();
} 

add_shortcode('tdv_team', 'tdv_scode_team');



?>