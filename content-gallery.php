<?php
/**
 * The default template for displaying content for the Gallery post format
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
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
		
		<div class="entry-gallery flexslider">
			<ul class="slides">
				<?php
				$args = array(
					'post_type'		=> 'attachment',
					'post_status'	=> 'published',
					'order'			=> 'menu_order',
					'orderby'		=> 'ASC',
					'numberposts'	=> -1,
					'post_parent'	=> get_the_ID()
				);

				$attachments = get_posts( $args );

				if ( $attachments ) :
					foreach ( $attachments as $attachment ) :
						if ( wp_attachment_is_image( $attachment->ID ) ) :
							$img_full = wp_get_attachment_image_src( $attachment->ID, 'large' );
							$img_thumb = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
							$caption = get_post_field( 'post_excerpt', $attachment->ID );
							?>
					<li>
						<img src="<?php echo $img_full[0]; ?>" width="<?php echo $img_full[1]; ?>" height="<?php echo $img_full[2]; ?>" alt="<?php echo get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true); ?>">
						<?php if ( ! empty( $caption ) ) : ?>
						<p><?php echo $caption; ?></p>
						<?php endif; ?>
					</li>
							<?php
						endif;
					endforeach;
				endif;
				?>
			</ul>
			<ul class="gallery-controls group">
				<?php
				$args = array(
					'post_type'		=> 'attachment',
					'post_status'	=> 'published',
					'order'			=> 'menu_order',
					'orderby'		=> 'ASC',
					'numberposts'	=> -1,
					'post_parent'	=> get_the_ID()
				);

				$attachments = get_posts( $args );

				if ( $attachments ) :
					foreach ( $attachments as $attachment ) :
						if ( wp_attachment_is_image( $attachment->ID ) ) :
							$img_thumb = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
							?>
					<li>
						<a href="#"><img src="<?php echo $img_thumb[0]; ?>" width="<?php echo $img_thumb[1]; ?>" height="<?php echo $img_thumb[2]; ?>" alt="<?php echo get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true); ?>"></a>
					</li>
							<?php
						endif;
					endforeach;
				endif;
				?>
			</ul>
		</div>

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
