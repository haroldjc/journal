<?php
/**
 * The default template for displaying content in archive pages
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
 */
?><div <?php post_class('entry small-post grid'); ?>>
	<div class="col-1-4">
		<figure>
			<a href="<?php the_permalink(); ?>" class="media-button">
			<?php if ( 'video' == get_post_format() ) : ?>
				<span>r</span>
				<span><?php _e( 'Video', 'blakzr' ); ?></span>
			<?php elseif ( 'gallery' == get_post_format() ) : ?>
				<span>!</span>
				<span><?php _e( 'Gallery', 'blakzr' ); ?></span>
			<?php endif; ?>
			</a>
			<a href="<?php the_permalink(); ?>">
				<?php 
				if ( has_post_thumbnail() ) :
					the_post_thumbnail( 'small-thumb' );
				else :
				?>
				<img src="<?php echo get_template_directory_uri(); ?>/img/small-thumb.png" width="200" height="200" alt="">
				<?php endif; ?>
			</a>
		</figure>
	</div>
	<div class="entry-details col-3-4">
		<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="entry-meta">
			<?php _e( 'By', 'blakzr' ); ?>
			<?php blakzr_get_author_link(); ?> -
			<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a> -
			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<?php comments_popup_link( __( 'No comments', 'blakzr' ), __( '1 comment', 'blakzr' ), __( '% comments', 'blakzr' ), 'comment-link' ); ?>
			<?php endif; ?>
		</div>
		<p class="entry-excerpt"><?php echo get_the_excerpt(); ?> <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read more', 'blakzr' ); ?></a></p>
	</div>
</div>