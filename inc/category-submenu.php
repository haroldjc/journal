<?php
require_once('../../../../wp-load.php');
	
	// Get Category ID by name
	$term = get_term_by( 'name', $_GET['catname'], 'category' );
 	$cat_ID = $term->term_id;

?>
<div class="last-entry col-half grid">
	<?php
	// Get Last Post
	$args = array(
		'posts_per_page' 	=> 1,
		'order'				=> 'DESC',
		'orderby' 			=> 'date',
		'post_type' 		=> 'post',
		'category__in' 		=> array( $cat_ID ),
		'post_status' 		=> 'publish'
	);

	$show_last_post = new WP_Query( $args );

	if ( $show_last_post->have_posts() ) :
		while ( $show_last_post->have_posts() ) : $show_last_post->the_post();
	?>
	<div class="col-2-6">
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
	<div class="col-4-6">
		<h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		<div class="entry-meta">
			<?php _e( 'By', 'blakzr' ); ?>
			<?php blakzr_get_author_link(); ?> -
			<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
		</div>
		<p class="entry-excerpt"><?php echo trim( substr( get_the_excerpt(), 0, 200 ) ); ?>... </p>
	</div>
	<?php
		endwhile;
		wp_reset_postdata();
	endif;
	?>
</div>
<div class="entry-list col-half">
	<ul>
		<?php
		$args = array(
			'posts_per_page' 	=> 10,
			'order'				=> 'DESC',
			'orderby' 			=> 'date',
			'post_type' 		=> 'post',
			'category__in' 		=> array( $cat_ID ),
			'post_status' 		=> 'publish',
			'offset'			=> 1
		);

		$show_more_posts = new WP_Query( $args );

		if ( $show_more_posts->have_posts() ) :
			while ( $show_more_posts->have_posts() ) : $show_more_posts->the_post(); ?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
	</ul>
</div>