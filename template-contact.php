<?php
/**
 * Template Name: Contact
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
 */

get_header(); ?><div class="main">

		<section class="grid">
			
			<?php if ( 'left' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
			<div class="content col-4-6">
				<nav class="breadcrumbs">
					<?php
					// Code to display breadcrumbs from the plugin 'Breadcrumbs NavXT'
					if ( function_exists( 'bcn_display' ) ) {
						bcn_display();
					}
					?>
				</nav>

				<?php the_post(); ?>
				
				<article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>
					<h1 class="entry-title"><?php the_title(); ?></h1>

					<div class="entry-content">
						<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'blakzr' ) ); ?>
						
						<div id="contact-form">
							<?php if ( isset( $_POST['ct_submit'] ) ) : ?>
								<?php
								// Form data
								$name = $_POST['ct_name'];
								$email = $_POST['ct_email'];
								$comment = $_POST['ct_comment'];

								// Mail details
								$to = get_option( 'blakzr_contact_email', get_option( 'admin_email' ) );
								$subject = __( 'Message from ', 'blakzr' ) . get_option( 'blogname' );

								$message = __('Name:', 'blakzr') . ' ' . $name . "\n";
								$message .= __('E-mail:', 'blakzr') . ' ' . $email . "\n";
								$message .= __('Message:', 'blakzr') . " \n\n";
								$message .= $comment;

								$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";

								wp_mail( $to, $subject, $message, $headers );
								?>
								<center>
									<h3><?php _e('Your request has been sent.', 'blakzr'); ?></h3>
								</center>
							<?php else: ?>
								<form action="" method="post" id="template-form" class="group">
									<p>
										<input type="text" name="ct_name" value="" id="ct_name" class="required" tabindex="1" placeholder="<?php _e( 'Name*' , 'blakzr'); ?>">
										<em><?php _e( 'This field is required.', 'blakzr' ); ?></em>
									</p>
									<p>
										<input type="text" name="ct_email" value="" id="ct_email" class="required email" tabindex="2" placeholder="<?php _e( 'E-mail*' , 'blakzr'); ?>">
										<em><?php _e( 'Provide a valid e-mail address.', 'blakzr' ); ?></em>
									</p>
									<p>
										<textarea id="ct_comment" rows="8" cols="45" name="ct_comment" id="ct_comment" class="required" tabindex="3" placeholder="<?php _e( 'Your comment' , 'blakzr'); ?>"></textarea>
										<em><?php _e( 'This field is required.', 'blakzr' ); ?></em>
									</p>
									<p><input type="submit" value="<?php _e('Send', 'blakzr'); ?>" name="ct_submit" id="ct_submit" class="button" tabindex="4"></p>
								</form>
							<?php endif; ?>
						</div><!-- #contact-form -->
						
						<?php edit_post_link( __( 'Edit this page', 'blakzr' ), '<span class="edit-link">', '</span>' ); ?>
					</div><!-- .entry-content -->
				</article>

				<?php comments_template( '', true ); ?>

			</div>

			<?php if ( 'right' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
		</section>

	<?php get_footer(); ?>