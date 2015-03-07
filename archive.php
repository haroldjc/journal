<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package AmericanJournal
 * @since AmericanJournal 1.0
 */

get_header(); ?><div class="main">
		
		<div class="location-title">
			<h2>
				<?php
					if ( is_tag() ) {
						printf( __( 'Tag Archives: %s', 'blakzr' ), '<span>' . single_tag_title( '', false ) . '</span>' );

					} elseif ( is_author() ) {
						/* Queue the first post, that way we know
						 * what author we're dealing with (if that is the case).
						*/
						the_post();
						printf( __( 'Author Archives: %s', 'blakzr' ), '<span class="vcard">' . get_the_author() . '</span>' );
						/* Since we called the_post() above, we need to
						 * rewind the loop back to the beginning that way
						 * we can run the loop properly, in full.
						 */
						rewind_posts();

					} elseif ( is_day() ) {
						printf( __( 'Daily Archives: %s', 'blakzr' ), '<span>' . get_the_date() . '</span>' );

					} elseif ( is_month() ) {
						printf( __( 'Monthly Archives: %s', 'blakzr' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

					} elseif ( is_year() ) {
						printf( __( 'Yearly Archives: %s', 'blakzr' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

					} else {
						_e( 'Archives', 'blakzr' );
					}
				?>
			</h2>
		</div>
		
		<section class="grid">
			
			<?php if ( 'left' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
			<div class="section col-4-6">
				<?php if ( have_posts() ) : ?>
            	
					<?php /* Start the Loop */ ?>
					<?php while ( have_posts() ) : the_post(); ?>
            	
						<?php
						$format = get_post_format();
						if ( false === $format )
							$format = 'standard';
            	
						?>
            	
						<?php get_template_part( 'post', $format ); ?>
            	
					<?php endwhile; ?>
            	
					<?php blakzr_content_nav( 'nav-below' ); ?>
            	
				<?php else : ?>
            	
				<article id="post-0" class="post no-results not-found">
					<h1 class="entry-title"><?php _e( 'Nothing Found', 'blakzr' ); ?></h1>
					
					<div class="entry-content">
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'blakzr' ); ?></p>
						<?php get_search_form(); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->
            	
				<?php endif; ?>
			</div>
			
			<?php if ( 'right' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
		</section>
		
	<?php get_footer(); ?>