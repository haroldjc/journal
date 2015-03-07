<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to blakzr_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Slide
 * @since Slide 1.0
 */

if ( comments_open() ) :

?>
	<div class="comments">
	<?php if ( post_password_required() ) : ?>
		<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'blakzr' ); ?></p>
	</div><!-- #comments -->
	<?php
			/* Stop the rest of comments.php from being processed,
			 * but don't kill the script entirely -- we still have
			 * to fully load the template.
			 */
			return;
		endif;
	?>

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3 id="comments-title">
			<?php
				printf( _n( 'One Comment', '%1$s Comments', get_comments_number(), 'blakzr' ),
					number_format_i18n( get_comments_number() ) );
			?>
		</h3>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation group">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'blakzr' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'blakzr' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<?php
			/* Loop through and list the comments. Tell wp_list_comments()
			 * to use blakzr_comment() to format the comments.
			 * If you want to overload this in a child theme then you can
			 * define blakzr_comment() and that will be used instead.
			 * See blakzr_comment() in blakzr/functions.php for more.
			 */
			wp_list_comments( array( 'callback' => 'blakzr_comment' ) );
		?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation group">
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'blakzr' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'blakzr' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php
		/* If there are no comments and comments are closed, let's leave a little note, shall we?
		 * But we don't want the note on pages or post types that do not support comments.
		 */
		elseif ( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="nocomments comment-notes"><?php _e( 'Comments are closed.', 'blakzr' ); ?></p>
	<?php endif; ?>

	<?php // Custom Comment form ?>
	
	<?php if ( comments_open() ) : ?>
		<?php do_action( 'comment_form_before' ); ?>
			<div id="respond">
				<h3 id="reply-title"><?php comment_form_title( __( 'Leave a Reply', 'blakzr' ), __( 'Leave a Reply to %s', 'blakzr' ) ); ?> <small><?php cancel_comment_reply_link( __( 'Cancel reply', 'blakzr' ) ); ?></small></h3>
				<?php if ( get_option( 'comment_registration' ) && ! is_user_logged_in() ) : ?>
					<p class="must-log-in">
						<?php printf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'blakzr' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ); ?>
					</p>
					<?php do_action( 'comment_form_must_log_in_after' ); ?>
				<?php else : ?>
					<?php do_action( 'comment_form_top' ); ?>
					<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
						<?php if ( is_user_logged_in() ) : ?>
							<div class="group">
								<p class="logged-in-as comment-notes"><?php printf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'blakzr' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post->ID ) ) ) ); ?></p>
								<label for="comment"><?php _e( 'Comment' , 'blakzr'); ?></label>
								<p>
								<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" placeholder="<?php _e( 'Your Comment*' , 'blakzr'); ?>" aria-required="true"></textarea>
								</p>
								<p class="form-submit">
									<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e( 'Post Comment', 'blakzr' ); ?>" class="button">
									<?php comment_id_fields(); ?>
								</p>
							</div>
						<?php else : ?>
							<p class="comment-notes"><?php printf( __( 'Your email address will not be published. Required fields are marked %1$s*%2$s', 'blakzr' ), '<span class="required">', '</span>' ); ?></p>
							<div class="group">
								<p class="user-data">
									<label for="author"><?php _e( 'Name*' , 'blakzr'); ?></label><input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" tabindex="1" placeholder="<?php _e( 'Name*' , 'blakzr'); ?>" aria-required="true">
									<label for="email"><?php _e( 'E-mail*' , 'blakzr'); ?></label><input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" tabindex="2" placeholder="<?php _e( 'E-mail*' , 'blakzr'); ?>" aria-required="true">
									<label for="url"><?php _e( 'Website' , 'blakzr'); ?></label><input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" tabindex="3" placeholder="<?php _e( 'Website' , 'blakzr'); ?>" aria-required="true">
								</p>
								<p class="comment-area">
									<label for="comment">Comment</label>
									<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" placeholder="<?php _e( 'Your Comment*' , 'blakzr'); ?>" aria-required="true"></textarea>
									<p class="form-submit">
										<input name="submit" type="submit" id="submit" tabindex="5" value="<?php _e( 'Post Comment', 'blakzr' ); ?>" class="button">
										<?php comment_id_fields(); ?>
									</p>
								</p>
							</div>
						<?php endif; ?>
						<?php do_action( 'comment_form', $post->ID ); ?>
					</form>
				<?php endif; ?>
			</div><!-- #respond -->
			<?php do_action( 'comment_form_after' ); ?>
	<?php else : ?>
		<?php do_action( 'comment_form_comments_closed' ); ?>
	<?php endif; ?>

</div><!-- #comments -->

<?php
endif;
?>