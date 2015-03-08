<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage journal
 * @since journal 1.0
 */

get_header(); ?><div class="main">
		
		<div class="location-title">
			<h2><?php _e( 'Page not found', 'blakzr' ); ?></h2>
		</div>
		
		<section class="grid">
			
			<?php if ( 'left' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
			<div class="section col-4-6">
            	
				<article id="post-0" class="post no-results not-found">
					<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'blakzr' ); ?></h1>
					
					<div class="entry-content">
						<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching, or one of the links below, can help.', 'blakzr' ); ?></p>

						<?php get_search_form(); ?>

						<?php the_widget( 'WP_Widget_Recent_Posts', array( 'number' => 10 ), array( 'widget_id' => '404', 'before_title' => '<h2>', 'after_title' => '</h2>' ) ); ?>

						<div class="widget">
							<h2><?php _e( 'Most Used Categories', 'blakzr' ); ?></h2>
							<ul>
							<?php wp_list_categories( array( 'orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10 ) ); ?>
							</ul>
						</div>

						<?php
						/* translators: %1$s: smilie */
						$archive_content = '<p>' . sprintf( __( 'Try looking in the monthly archives. %1$s', 'blakzr' ), convert_smilies( ':)' ) ) . '</p>';
						the_widget( 'WP_Widget_Archives', array('count' => 0 , 'dropdown' => 1 ), array( 'before_title' => '<h2>', 'after_title' => '</h2>'.$archive_content ) );
						?>

						<?php the_widget( 'WP_Widget_Tag_Cloud', array(), array( 'before_title' => '<h2>', 'after_title' => '</h2>' ) ); ?>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			</div>
			
			<?php if ( 'right' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
		</section>
		
	<?php get_footer(); ?>