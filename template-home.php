<?php
/**
 * Template Name: Home
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
 */

get_header(); ?><div class="main">

		<section class="featured grid">
				<?php
				global $query_string;
				$main_query = $query_string;
				$sticky_posts = get_option( 'sticky_posts' );
				
				$args = array(
					'posts_per_page' 		=> 1,
					'post__in'				=> $sticky_posts,
					'post_type'	 			=> 'post',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'	=> 1
				);
				
				$show_last_post = new WP_Query( $args );
				
				if ( $show_last_post->have_posts() ) :
					while ( $show_last_post->have_posts() ) : $show_last_post->the_post();
				?>
			<div <?php post_class('main-feature col-half'); ?>>
						<?php if ( 'video' == get_post_format() ) : ?>
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
					wp_reset_postdata();
				endif;
				?>
			</div>
			<div class="aside-features col-1-6">
				<?php
				$args = array(
					'posts_per_page' 		=> 2,
					'post__not_in'			=> $sticky_posts,
					'post_type'	 			=> 'post',
					'post_status' 			=> 'publish'
				);
				
				if ( empty( $sticky_posts ) ) $args['offset'] = 1;
				
				$show_last_posts = new WP_Query( $args );
				
				if ( $show_last_posts->have_posts() ) :
					while ( $show_last_posts->have_posts() ) : $show_last_posts->the_post();
				?>
				<div class="entry">
					<span class="entry-cat">
						<?php
						$category = get_the_category();
						echo $category[0]->cat_name;
						?>
					</span>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="entry-meta">
						<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
					</div>
					<p class="entry-excerpt"><?php echo trim( substr( get_the_excerpt(), 0, 150 ) ); ?>... </p>
				</div>
				<?php
					endwhile;
				endif;
				?>
			</div>
			<div class="widget-column-1 col-1-6">
				<ul class="sidebar">
					<?php the_widget( 'WP_Widget_Recent_Posts', array( 'title' => __( 'Recent News', 'blakzr' ), 'number' => 11 ), array( 'widget_id' => 'home_recent_posts', 'before_title' => '<h4 class="widgettitle">', 'after_title' => '</h4>' ) ); ?>
				</ul>
			</div>
			<div class="widget-column-2 col-1-6">
				<ul class="sidebar">
					<li class="widget category_widget">
						<h4 class="widgettitle"><?php _e( 'Topics', 'blakzr' ) ?></h4>
						<ul>
							<?php
							$cat_args = array(
								'order' 		=> 'ASC',
								'orderby'		=> 'name'
							);
							$categories = get_categories( $cat_args );
							foreach ( $categories as $category ) : ?>
							<li><a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" title="<?php echo $category->name; ?>"><?php echo $category->name; ?></a></li>
							<?php endforeach; ?>
						</ul>	
					</li>
				</ul>
			</div>
		</section>
		
		<section class="categories grid">
			
			<?php if ( 'left' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
			<div class="category-posts col-4-6 grid">
				<?php
				// Get default categories
				$cat_args = array(
					'order' 	=> 'DESC',
					'orderby'	=> 'count'
				);
				
				$categories = get_categories( $cat_args );
				foreach ( $categories as $key => $category ) :
					$default_cats[$key] = array(
						'id'		=> $category->term_id,
						'name'		=> $category->name,
						'count'		=> $category->count
					);
				endforeach;
				$default_cats_q = count( $default_cats );
				
				// Category order as in Options Panel
				$cat_items = get_option( 'blakzr_home_cat_order', $default_cats );
				$cat_items_q = count( $cat_items );
				$cat_number = get_option( 'blakzr_home_cat_number', 8 );
				$cat_counter = 0;
				
				// Check if there's new categories available
				if ( $default_cats_q > $cat_items_q ) :
					// If there's more categories, add them to the end of the array
					foreach ( $default_cats as $key1 => $allcat ) :
						$i = 0;
						foreach ( $cat_items as $key2 => $cat ) :
							if ( $allcat['id'] != $cat['id'] ) $i++;
							if ( $i == $cat_items_q )
								$new_cat_order[] = array( 'id' => $allcat['id'], 'name' => $allcat['name'] );
						endforeach;
					endforeach;
					
					foreach ( $new_cat_order as $key => $cat ) :
						$cat_items[] = array( 'id' => $cat['id'], 'name' => $cat['name'] );
					endforeach;
				endif;
				
				foreach ( $cat_items as $key => $category ) :
					$cat_counter++;
					
					if ( $cat_number != -1 ) 
						if ( $cat_counter > $cat_number ) break;
					
					$args = array(
						'posts_per_page' 	=> 1,
						'order'				=> 'DESC',
						'orderby' 			=> 'date',
						'post_type' 		=> 'post',
						'category__in' 		=> array( $category['id'] ),
						'post_status' 		=> 'publish'
					);
					
					$show_last_post = new WP_Query( $args );
					
					if ( $show_last_post->have_posts() ) :
						while ( $show_last_post->have_posts() ) : $show_last_post->the_post(); ?>
				<div <?php post_class('section'); ?>>
					<h4 class="section-title">
						<a href="<?php echo esc_url( get_category_link( $category['id'] ) ); ?>" title="<?php echo $category['name']; ?>"><?php echo $category['name']; ?></a>
					</h4>
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
								the_post_thumbnail( 'medium-thumb' );
							else :
							?>
							<img src="<?php echo get_template_directory_uri(); ?>/img/medium-thumb.png" width="380" height="200" alt="">
							<?php endif; ?>
						</a>
					</figure>
					<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<div class="entry-meta">
						<?php _e( 'By', 'blakzr' ); ?>
						<?php blakzr_get_author_link(); ?> -
						<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a> -
						<?php if ( comments_open() && ! post_password_required() ) : ?>
						<?php comments_popup_link( __( 'No comments', 'blakzr' ), __( '1 comment', 'blakzr' ), __( '% comments', 'blakzr' ), 'comment-link' ); ?>
						<?php endif; ?>
					</div>
					<p class="entry-excerpt"><?php echo trim( substr( get_the_excerpt(), 0, 220 ) ); ?>... <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read more', 'blakzr' ); ?></a></p>
					<?php
						endwhile;
						wp_reset_postdata();
					endif;
					?>
					<ul class="more-articles">
						<?php
						$args = array(
							'posts_per_page' 	=> 3,
							'order'				=> 'DESC',
							'orderby' 			=> 'date',
							'post_type' 		=> 'post',
							'category__in' 		=> array( $category['id'] ),
							'post_status' 		=> 'publish',
							'offset'			=> 1
						);

						$show_more_posts = new WP_Query( $args );

						if ( $show_more_posts->have_posts() ) :
							while ( $show_more_posts->have_posts() ) : $show_more_posts->the_post(); ?>
						<li>
							<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
							<div class="entry-meta">
								<?php _e( 'By', 'blakzr' ); ?>
								<?php blakzr_get_author_link(); ?> -
								<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
							</div>
						</li>
						<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</ul>
				</div>
				<?php
				endforeach;
				?>
			</div>
			
			<?php if ( 'right' == get_option( 'blakzr_sidebar_position', 'right' ) ) : ?>
				<?php get_sidebar(); ?>
			<?php endif; ?>
			
		</section>
		

	<?php get_footer(); ?>