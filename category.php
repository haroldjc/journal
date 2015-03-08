<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Journal
 * @since Journal 1.0
 */

get_header(); ?><div class="main">
		
		<div class="location-title">
			<h2>
				<?php printf( __( '%s', 'blakzr' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
			</h2>
		</div>
		
		<?php if ( ! is_paged() ) : ?>
		<section class="featured grid">
			<div <?php post_class('main-feature col-half'); ?>>
				<?php
				global $query_string;
				$main_query = $query_string;
				query_posts( $main_query . '&posts_per_page=1' );
				
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						if ( 'video' == get_post_format() ) :
				?>
				<div class="entry-media">
					<?php
					$custom = get_post_custom( get_the_ID() );
					$custom_video_type = $custom['post_format_video_type'][0];
					$custom_video = $custom['post_format_video'][0];

					if ( ! empty( $custom_video ) ) :

						switch ( $custom_video_type ) :
							case '0':
								echo do_shortcode('[youtube width="525" height="295"]' . $custom_video . '[/youtube]');
								break;
							case '1':
								echo do_shortcode('[vimeo width="525" height="295"]' . $custom_video . '[/vimeo]');
								break;
							case '2':
								echo do_shortcode('[video width="525" height="295"]' . $custom_video . '[/video]');
								break;
							default:
								# Don't do nothing, please.
								break;
						endswitch;

					endif;
					?>
				</div>
						<?php else : ?>
				<figure>
					<a href="<?php the_permalink(); ?>" class="media-button">
						<span>!</span>
						<span><?php _e( 'Gallery', 'blakzr' ); ?></span>
					</a>
					<a href="<?php the_permalink(); ?>">
						<?php 
						if ( has_post_thumbnail() ) :
							the_post_thumbnail( 'featured-thumb' );
						else :
						?>
						<img src="<?php echo get_template_directory_uri(); ?>/img/featured-thumb.png" width="575" height="307" alt="">
						<?php endif; ?>
					</a>
				</figure>
						<?php endif; ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="entry-meta">
					<?php _e( 'By', 'blakzr' ); ?>
					<?php blakzr_get_author_link(); ?> -
					<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
				</div>
				<p><?php echo get_the_excerpt(); ?> <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read more', 'blakzr' ); ?></a></p>
				<?php
					endwhile;
				endif;
				?>
			</div>
			<div class="col-half category-aside">
				<?php
				query_posts( $main_query . '&posts_per_page=2&offset=1' );
				
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
				?>
				<div <?php post_class('entry grid'); ?>>
					<div class="col-2-6">
						<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div class="entry-meta">
							<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
						</div>
						<p class="entry-excerpt"><?php echo trim( substr( get_the_excerpt(), 0, 150 ) ); ?>... </p>
					</div>
					<div class="col-4-6">
						<?php
						if ( has_post_thumbnail() ) :
							$img_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'medium-thumb' );
							$img_url = $img_url[0];
						else :
							$img_url = get_template_directory_uri() + '/img/medium-thumb.png';
						endif;
						?>
						<a href="<?php the_permalink(); ?>">
							<figure style="background-image: url('<?php echo $img_url; ?>')">
								<a href="<?php the_permalink(); ?>" class="media-button">
								<?php if ( 'video' == get_post_format() ) : ?>
									<span>r</span>
									<span><?php _e( 'Video', 'blakzr' ); ?></span>
								<?php elseif ( 'gallery' == get_post_format() ) : ?>
									<span>!</span>
									<span><?php _e( 'Gallery', 'blakzr' ); ?></span>
								<?php endif; ?>
								</a>
							</figure>
						</a>
					</div>
				</div>
				<?php
					endwhile;
				endif;
				?>
			</div>
		</section>
		<?php endif; ?>
		
		
		<section class="grid">
			
			<?php if ( 'left' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
			<div class="section col-4-6">
				
				<h4 class="widgettitle">
					<?php printf( __( 'More \'%s\' news', 'blakzr' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?>
				</h4>
				
				<?php
				if ( ! is_paged() ) :
					$posts_per_page = get_option( 'posts_per_page' );
					$this_category = get_category( get_query_var('cat'), false );
					
					if ( $this_category->count > $posts_per_page )
						$posts_q = $posts_per_page - 3;
					
					query_posts( $main_query . '&offset=3&posts_per_page=' . $posts_q );
				endif;
				?>
				
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