<?php
/**
 * The template for displaying Search Results pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package AmericanJournal
 * @since AmericanJournal 1.0
 */

get_header(); ?><div class="main">
		
		<div class="location-title">
			<h2><?php printf( __( 'Search Results for: %s', 'blakzr' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
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
						<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'blakzr' ); ?></p>
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