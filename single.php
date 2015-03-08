<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Journal
 * @since Journal 1.0
 */

get_header(); ?><div class="main">
	
		<div class="location-title">
			<h2>
				<?php
				$category = get_the_category();
				echo $category[0]->cat_name;
				?>
			</h2>
		</div>

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
				
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					$format = get_post_format();
					if ( false === $format )
						$format = 'standard';

					?>

					<?php get_template_part( 'content', $format ); ?>
					
					<?php comments_template( '', true ); ?>

				<?php endwhile; ?>
			</div>

			<?php if ( 'right' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>

		</section>

	<?php get_footer(); ?>