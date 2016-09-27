<?php

// check permission for password protected comments
if( post_password_required() )
	return;
?>

<article class="comments" id="comments">

	<?php if( have_comments() ) : ?>
		<h3 class="section-heading"><?php comments_number(__( 'No Comment', 'repute' ), __( 'Comment (1)', 'repute' ), __( 'Comments (%)', 'repute' ) ); ?></h3>

		<!-- conversation -->
		<ul class="media-list">
			<?php wp_list_comments( array( 'callback' => 'tdv_list_comments' ) ); ?>
		</ul>
		<!-- end conversation -->
	
		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="nocomments"><?php _e( 'Comments are closed.' , 'repute' ); ?></p>
		<?php endif; ?>
	
	<?php endif; ?>
	
	<nav class="comment-pagination text-right">
	<?php 
		paginate_comments_links(
			array('prev_text' => __( '<i class="fa fa-angle-double-left" title="Previous"></i>', 'repute'), 'next_text' => __('<i class="fa fa-angle-double-right" title="Next"></i>', 'repute' ) )
		); 
	?>
	</nav>
</article>

<?php

function tdv_list_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		$comment_type = $comment->comment_type;

		if( $comment_type == 'trackback' ||  $comment_type == 'pingback' ) :
?>
			<li class="post">
				<p>
					<?php __( 'Pingback:', 'repute' ); ?>
					<?php comment_author_link(); ?>
					<?php edit_comment_link( __( '(Edit)' , 'repute' ), ' ' ); ?>
				</p>

		<?php else : ?>

			<li <?php comment_class(); ?> class="media" id="comment-<?php comment_ID(); ?>">

			<?php if ( $comment->comment_approved == '1' ) : ?>

				<a href="#" class="media-left">

					<?php echo get_avatar( $comment, 64, '', '', array('class'=>'avatar') ); ?>

				</a>
				<div class="media-body">
					<h4 class="media-heading comment-author"><?php echo get_comment_author_link(); ?></h4><span class="timestamp text-muted"><?php echo get_comment_date() . ' ' . get_comment_time(); ?></span>
					<div class="comment-text"><?php echo get_comment_text(); ?></div>
					<?php $args['reply_text'] = '<i class="fa fa-reply"></i> Reply'; ?>

					<?php if( comments_open() && ($depth < $args['max_depth']) ) : ?>
					
						<p><?php $reply_link = comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></p>
					
					<? endif; ?>
					<hr>
				</div>

			<?php else: ?>

				<p><span class="alert alert-success comment-awaiting-moderation"><i class="fa fa-info-circle"></i> <?php _e('Your comment is awaiting moderation.') ?></span></p>

			<?php endif; ?>

		<?php endif; ?>
<?php } ?>

<section class="comment-form margin-bottom">

<?php

	$defaults = array( 
		'fields' => apply_filters( 'comment_form_default_fields', array(
			'author' => '<div class="form-group">' .
						'<label for="author" class="control-label">' . __( 'Name', 'repute' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'<input id="author" name="author" type="text" class="form-control" value="' .
						esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1" ' . $aria_req . '/>' .
						'</div>',
			'email'  => '<div class="form-group">' .
						'<label for="email" class="control-label">' . __( 'Email', 'repute' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'<input id="email" name="email" type="text" class="form-control" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
						'</div>',
			'url'    => '<div class="form-group">' .
						'<label for="url" class="control-label">' . __( 'Website', 'repute' ) . '</label>' .
						'<input id="url" name="url" type="text" class="form-control" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
						'</div>' ) ),
			'comment_field' => '<div class="form-group">' .
						'<label for="comment" class="control-label">' . __( 'Comment' ) . '</label>' .
						'<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" class="form-control" aria-required="true"></textarea>' .
						'</div>',
			'must_log_in' => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'repute' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ) . '</p>',
			'logged_in_as' => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%s">%s</a>. <a href="%s" title="Log out of this account">Log out?</a></p>', 'repute' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ),
			'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email is <em>never</em> published nor shared.', 'repute' ) . ( $req ? __( ' Required fields are marked <span class="required">*</span>', 'repute' ) : '' ) . '</p>',
			'comment_notes_after' => '<dl class="form-allowed-tags"><dt>' . __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:', 'repute' ) . '</dt> <dd><code>' . allowed_tags() . '</code></dd></dl>',
			'id_form' => 'commentform',
			'id_submit' => 'submit',
			'title_reply' => '',
			'title_reply_to' => '',
			'cancel_reply_link' => __( 'Cancel reply', 'repute' ),
			'label_submit' => __( 'Submit Comment', 'repute' ),
			'class_submit' => 'btn btn-primary'
		);

	?>
	<h3 class="section-heading"><?php comment_form_title( 'Leave a Comment', 'Leave a Reply to %s' ); ?></h3>
	<?php comment_form( $defaults ); ?>
</section>


