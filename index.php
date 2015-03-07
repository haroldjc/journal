<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage AmericanJournal
 */

get_header(); ?><div class="main">
		
		<div class="location-title">
			<h2><?php _e( 'Archives', 'blakzr' ); ?></h2>
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