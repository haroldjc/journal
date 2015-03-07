<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
						<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blakzr' ) . '</span>', 'after' => '</div>' ) ); ?>
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