<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Journal
 * @since Journal 1.0
 */
?><article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>
		<?php if ( is_single() ) : ?>
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php else : ?>
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blakzr' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		<?php endif; ?>
		<div class="entry-meta">
			<?php blakzr_get_author_link(); ?>
			<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blakzr' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark" class="date-link">
				<span><?php the_time('F j, Y'); ?></span> <span><?php the_time('M j, Y'); ?></span>
			</a>
			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<?php comments_popup_link( __( '<span>No comments</span> <span>0</span>', 'blakzr' ), __( '<span>1 comment</span> <span>1</span>', 'blakzr' ), __( '<span>% comments</span> <span>%</span>', 'blakzr' ), 'comment-link' ); ?>
			<?php endif; ?>
		</div>
		
		<?php 
		if ( has_post_thumbnail() ) :
		?>
		<div class="entry-thumbnail">
			<figure>
				<?php the_post_thumbnail( 'large-thumb' ); ?>
			</figure>
		</div>
		<?php endif; ?>

		<?php if ( is_search() || ! is_single() ) : // Only display Excerpts for Search ?>
		<div class="entry-excerpt">
			<p><?php echo get_the_excerpt(); ?> <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read more &rarr;', 'blakzr' ); ?></a></p>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'blakzr' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blakzr' ) . '</span>', 'after' => '</div>' ) ); ?>
			<?php edit_post_link( __( 'Edit this post', 'blakzr' ), '<span class="edit-link">', '</span>' ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<?php
		if ( is_single() ) :
			blakzr_get_taxonomies();
		endif;
		?>
		
		<?php if ( is_single() ) : ?>
		<aside class="entry-aside">
			<div class="share">
				<span><?php _e( 'Share:', 'blakzr' ); ?></span>
				<ul>
					<li><a href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="facebook"><?php _e( 'Facebook', 'blakzr' ); ?></a></li>
					<li><a href="http://twitter.com/share" target="_blank" class="twitter"><?php _e( 'Twitter', 'blakzr' ); ?></a></li>
					<li><a href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank" class="gplus"><?php _e( 'Google+', 'blakzr' ); ?></a></li>
					<?php 
					if ( has_post_thumbnail() ) :
						$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'large' );
						$description_econded = urlencode( trim( substr( get_the_excerpt(), 0, 120 ) ) );
					?>
					<li>
						<a class="pinterest" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=<?php echo $thumbnail_src[0]; ?>&amp;description=<?php echo $description_econded; ?>">
							<?php _e( 'Pinterest', 'blakzr' ); ?>
						</a>
					</li>
					<?php endif; ?>
					<li>
						<a target="_blank" class="tumblr" href="http://www.tumblr.com/share/link?url=<?php echo urlencode( get_permalink() ) ?>&amp;name=<?php echo urlencode( get_the_title() ) ?>&amp;description=<?php echo urlencode( get_the_excerpt() ) ?>"><?php _e( 'Tumblr', 'blakzr' ); ?></a>
					</li>
					<li>
						<a href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo urlencode( get_permalink() ) ?>&amp;title=<?php echo urlencode( get_the_title() ) ?>&amp;summary=<?php echo urlencode( trim( substr( get_the_excerpt(), 0, 256 ) ) ); ?>" target="_blank" class="linkedin">
							<?php _e( 'LinkedIn', 'blakzr' ); ?></a>
					</li>
				</ul>
			</div>
			<div class="prev-next-entries">
				<?php previous_post_link( '%link', '&laquo; Previous article' ); ?>
				<?php next_post_link( '%link', 'Next article &raquo;' ); ?>
			</div>
			
			<?php blakzr_get_related_posts(); ?>
			
		</aside>
		<?php endif; ?>
		
	</article><!-- #post-<?php the_ID(); ?> -->
