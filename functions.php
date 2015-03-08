<?php
/**
 * Welcome to the Functions.php file!
 *
 * @package WordPress
 * @subpackage Journal
 * @since Journal 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 705;

/**
 * Display navigation to next/previous pages when applicable
 */
function blakzr_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>" class="page-navigation group">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'blakzr' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'blakzr' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}

/**
 * Display Author Link
 */
if ( ! function_exists( 'blakzr_get_author_link' ) ) {
	/**
	 * Prints the name of the author with a link to the author posts page.
	 */
	function blakzr_get_author_link( $img = '' ) {
		echo 'avatar' == $img ? get_avatar( get_the_author_meta( 'ID' ), 15 ) : '';
		printf( '<a href="%1$s" title="%2$s" class="author-link">%3$s</a>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'blakzr' ), get_the_author()),
			get_the_author()
		);
	}
}

/**
 * Display Post Categories & Tags
 */
if ( ! function_exists( 'blakzr_get_taxonomies' ) ) {
	function blakzr_get_taxonomies() {
		// Retrieves tag list of current post, separated by commas.
		$tag_list = get_the_tag_list( '', ', ' );
		
		?><div class="entry-taxonomies">
			<?php if ( $tag_list ) : ?>
			<div class="entry-tags">
				<?php echo __( 'Tagged in: ' ) . $tag_list; ?>
			</div>
			<?php endif; ?>
		</div><!-- .entry-taxonomies -->
		<?php
	}
}


/**
 * Display Related Posts
 */
if ( ! function_exists( 'blakzr_get_related_posts' ) ) {
	function blakzr_get_related_posts() {

		$orig_post = $post;
		global $post;
		$tags = wp_get_post_tags($post->ID);
        
		if ( $tags ) :
			$tag_ids = array();
			foreach ( $tags as $individual_tag ) $tag_ids[] = $individual_tag->term_id;
			$args = array(
				'tag__in' 			=> $tag_ids,
				'post__not_in' 		=> array( $post->ID ),
				'posts_per_page'	=> 2,
				'caller_get_posts'	=> 1
			);  
        
			$my_query = new wp_query( $args );
			
			if ( $my_query->have_posts() ) :
				?>
			<div class="related-posts grid">
				<span><?php _e( 'Related Articles', 'blakzr' ) ?></span>
			<?php
				while ( $my_query->have_posts() ) :
					$my_query->the_post();
				?>
				<div class="col-half">
					<figure>
						<a href="<?php the_permalink(); ?>">
							<?php 
							if ( has_post_thumbnail() ) :
								the_post_thumbnail( 'thumbnail' );
							else :
							?>
							<img src="<?php echo get_template_directory_uri(); ?>/img/small-thumb.png" width="150" height="150" alt="">
							<?php endif; ?>
						</a>
					</figure>
					<div class="entry-details">
						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<div>
							<?php _e( 'By', 'blakzr' ); ?>
							<?php blakzr_get_author_link(); ?> -
							<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></a>
						</div>
						<p><?php echo trim( substr( get_the_excerpt(), 0, 90 ) ); ?>... <a href="#" class="more-link">Read more</a></p>
					</div>
				</div>
				<?php
				endwhile;
				?>
			</div>
			<?php
			endif;
        
		endif;
		$post = $orig_post;
		wp_reset_query();
		
	}
}

add_filter( 'the_content', 'shortcode_paragraph_fix' );

function shortcode_paragraph_fix( $content )
{
	$array = array (
		'<p>[' => '[', 
		']</p>' => ']', 
		']<br />' => ']'
	);

	$content = strtr( $content, $array );
	return $content;
}

/**
 * Template for comments and pingbacks.
 */
if ( ! function_exists( 'blakzr_comment' ) ) {
	function blakzr_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<div <?php comment_class( 'comment' ); ?>>
			<p><?php _e( 'Pingback:', 'blakzr' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'blakzr' ), '<span class="edit-link">(', ')</span>' ); ?></p>
		</div>
		<?php
				break;
			default :
		?>
		<div <?php comment_class('comment group'); ?> id="comment-<?php comment_ID(); ?>">
			<div class="vcard">
				<?php echo get_avatar($comment, 80); ?>
			</div>
			<div class="comment-content">
				<p><cite class="fn" data-auth="[<?php _e('Author', 'blakzr'); ?>]"><?php comment_author_link(); ?> </cite> <span class="date"><?php _e( 'on', 'blakzr' ) ?> <?php
				printf( '<a href="%1$s" title="%3$s"><time pubdate datetime="%2$s">%3$s</time></a>',
					esc_url( get_comment_link( $comment->comment_ID ) ),
					get_comment_time( 'c' ),
					/* translators: 1: date, 2: time */
					sprintf( __( '%1$s at %2$s', 'blakzr' ), get_comment_date(), get_comment_time() )
				)
				?></span></p>
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( '&rarr; Your comment is awaiting moderation.', 'blakzr' ); ?></em>
				<?php endif; ?>
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'blakzr' ), '<span class="edit-link">', '</span>' ); ?>
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'blakzr' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div>
		</div><!-- .comment -->
	
		<?php
				break;
		endswitch;
	}
}

/**
 * Post Formats Custom Meta Boxes
 */

add_action( 'admin_init', 'add_post_format_meta_boxes' );

function add_post_format_meta_boxes(){
	
	add_meta_box( 'format-video-meta', __( 'Video Format', 'blakzr' ), 'format_video_meta_data', 'post', 'normal', 'high' );
		
}

// Callbacks
function format_video_meta_data(){
	global $post;
	$custom = get_post_custom( $post->ID );
	$custom_video_type = $custom['post_format_video_type'][0];
	$custom_video = $custom['post_format_video'][0];
	?>
	<table width="100%" class="post-meta-boxes">
		<tr>
			<td class="meta-description" colspan="3">
				<strong><?php _e( 'Insert Video URL', 'blakzr' ); ?></strong>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<input type="radio" name="post_format_video_type" value="0" id="post_format_video_type_0"<?php echo '' != $var ? ('0' == $custom_video_type ? ' checked="checked"' : '') : ' checked="checked"'; ?>>
			</td>
			<td width="15%">
				<label for="post_format_video_type_0"><?php _e( 'YouTube:', 'blakzr' ); ?></label>
			</td>
			<td width="82%">
				<input type="text" name="post_format_video_0" id="post_format_video_0" value="<?php echo '0' == $custom_video_type ? $custom_video : ''; ?>"<?php echo '0' != $custom_video_type ? ' disabled="disabled"' : ''; ?>>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<input type="radio" name="post_format_video_type" value="1" id="post_format_video_type_1"<?php echo '1' == $custom_video_type ? ' checked="checked"' : ''; ?>>
			</td>
			<td width="15%">
				<label for="post_format_video_type_1"><?php _e( 'Vimeo:', 'blakzr' ); ?></label>
			</td>
			<td width="82%">
				<input type="text" name="post_format_video_1" id="post_format_video_1" value="<?php echo '1' == $custom_video_type ? $custom_video : ''; ?>"<?php echo '1' != $custom_video_type ? ' disabled="disabled"' : ''; ?>>
			</td>
		</tr>
		<tr>
			<td width="3%">
				<input type="radio" name="post_format_video_type" value="2" id="post_format_video_type_2"<?php echo '2' == $custom_video_type ? ' checked="checked"' : ''; ?>>
			</td>
			<td width="15%">
				<label for="post_format_video_type_2"><?php _e( 'Self-hosted:', 'blakzr' ); ?></label>
			</td>
			<td width="82%">
				<input type="text" name="post_format_video_2" id="post_format_video_2" value="<?php echo '2' == $custom_video_type ? $custom_video : ''; ?>"<?php echo '2' != $custom_video_type ? ' disabled="disabled"' : ''; ?>>
			</td>
		</tr>
	</table>
	<?php
}

// Save Meta Boxes
add_action( 'save_post', 'save_meta_boxes' );

function save_meta_boxes(){
	global $post;
	
	if ( 'post' == get_post_type( $post->ID ) ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post->ID;
		update_post_meta( $post->ID, 'post_format_video_type', $_POST['post_format_video_type'] );
		update_post_meta( $post->ID, 'post_format_video', $_POST['post_format_video_' . $_POST['post_format_video_type']] );
	}
}

/**
 * Get First Post Embeded Image
 */

// Get first image

if ( ! function_exists( 'blakzr_get_first_image' ) ) {
	
	function blakzr_get_first_image() {
		
		global $post, $posts;
		$first_img = '';
		ob_start();
		ob_end_clean();
		if ( preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches ) ) :
			$first_img = $matches[1][0];
			return $first_img;
		else :
			return false;
		endif;
		
	}
	
}

/**
 * Automatically add rel="lightbox" to image links
 */

add_filter( 'the_content', 'blakzr_addcolorboxtitle_replace', 99 );
add_filter( 'the_excerpt', 'blakzr_addcolorboxtitle_replace', 99 );

function blakzr_addcolorboxtitle_replace( $content ){
	global $post;
	// [0] <a xyz href="...(.bmp|.gif|.jpg|.jpeg|.png)" zyx>yx</a> --> <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz zyx>yx</a>
	$pattern[0]		= "/(<a)([^\>]*?) href=('|\")([^\>]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)>(.*?)<\/a>/i";
	$replacement[0]	= '$1 href=$3$4$5$6$2$7>$8</a>';
	// [1] <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz zyx>yx</a> --> <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" rel="lightbox[POST-ID]" xyz zyx>yx</a>
	$pattern[1]		= "/(<a href=)('|\")([^\>]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)(>)(.*?)(<\/a>)/i";
	$replacement[1]	= '$1$2$3$4$5 rel="lightbox['.$post->ID.']"$6$7$8$9';
	// [2] <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" rel="lightbox[POST-ID]" xyz rel="(lightbox|nolightbox)yxz" zyx>yx</a> --> <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz rel="(lightbox|nolightbox)yxz" zyx>yx</a>
	$pattern[2]		= "/(<a href=)('|\")([^\>]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\") rel=('|\")lightbox([^\>]*?)('|\")([^\>]*?) rel=('|\")(lightbox|nolightbox)([^\>]*?)('|\")([^\>]*?)(>)(.*?)(<\/a>)/i";
	$replacement[2]	= '$1$2$3$4$5$9 rel=$10$11$12$13$14$15$16$17';
	// [3] <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz>yx title=yxz xy</a> --> <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz title=yxz>yx title=yxz xy</a>
	$pattern[3]		= "/(<a href=)('|\")([^\>]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?)(>)(.*?) title=('|\")(.*?)('|\")(.*?)(<\/a>)/i";
	$replacement[3]	= '$1$2$3$4$5$6 title=$9$10$11$7$8 title=$9$10$11$12$13';
	// [4] <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz title=zxy xzy title=yxz>yx</a> --> <a href="...(.bmp|.gif|.jpg|.jpeg|.png)" xyz title=zxy xzy>yx</a>
	$pattern[4]		= "/(<a href=)('|\")([^\>]*?)(\.bmp|\.gif|\.jpg|\.jpeg|\.png)('|\")([^\>]*?) title=([^\>]*?) title=([^\>]*?)(>)(.*?)(<\/a>)/i";
	$replacement[4]	= '$1$2$3$4$5$6 title=$7$9$10$11';
	$content = preg_replace( $pattern, $replacement, $content );
	return $content;
}

/**
 * Social Links
 */

function blakzr_social_links() {

	$social_links = array(
		'fb'	=>	array( 'Facebook', get_option( 'blakzr_social_facebook', '' ) ),
		'tw'	=>	array( 'Twitter', get_option( 'blakzr_social_twitter', '' ) ),
		'gp'	=>	array( 'Google+', get_option( 'blakzr_social_gplus', '' ) ),
		'fk'	=>	array( 'Flickr', get_option( 'blakzr_social_flickr', '' ) ),
		'is'	=>	array( 'Instagram', get_option( 'blakzr_social_instagram', '' ) ),
		'tb'	=>	array( 'Tumblr', get_option( 'blakzr_social_tumblr', '' ) ),
		'db'	=>	array( 'Dribbble', get_option( 'blakzr_social_dribbble', '' ) ),
		'in'	=>	array( 'LinkedIn', get_option( 'blakzr_social_linkedin', '' ) ),
		'vm'	=>	array( 'Vimeo', get_option( 'blakzr_social_vimeo', '' ) ),
		'pt'	=>	array( 'Pinterest', get_option( 'blakzr_social_pinterest', '' ) ),
		'yt'	=>	array( 'YouTube', get_option( 'blakzr_social_youtube', '' ) )
	);
	
	$social_filtered = array();
	
	foreach ( $social_links as $id => $social ) :
		if ( '' != $social[1] ) :
		
			$url = $social[1];
			
			switch ( $id ) :
				case 'tw' :
					$url = 'https://twitter.com/' . $url;
					break;
				case 'db' :
					$url = 'http://dribbble.com/' . $url;
					break;
				case 'vm' :
					$url = 'https://vimeo.com/' . $url;
					break;
				case 'pt' :
					$url = 'https://pinterest.com/' . $url;
					break;
				case 'yt' :
					$url = 'https://www.youtube.com/user/' . $url;
					break;
				case 'is' :
					$url = 'http://instagram.com/' . $url;
					break;
				default:
					$url = $social[1];
					break;
			endswitch;
			
			$social_filtered[$id] = array( $social[0], $url );
			
		endif;
	endforeach;
	
	return $social_filtered;
	
}

/**
 * Localization Support
 */

load_theme_textdomain( 'blakzr', TEMPLATEPATH . '/lang' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/lang/$locale.php";
if ( is_readable( $locale_file ) ) require_once( $locale_file );

/**
 * Add Theme Support
 */

/* Post Formats */
if ( function_exists( 'add_theme_support' ) ) { 
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-formats', array( 'aside', 'image', 'gallery', 'video' ) );
}

/* Image Sizes / Thumbnails */
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'small-thumb', 200, 200, true );
	add_image_size( 'medium-thumb', 380, 200, true );
	add_image_size( 'large-thumb', 705, 9999 );
	add_image_size( 'featured-thumb', 575, 307, true );
}

/* Editor Style */
if ( function_exists( 'add_editor_style' ) ) { 
	add_editor_style();
}

/* Custom Menus */
add_action( 'init', 'register_menus' );

function register_menus(){
	if ( function_exists( 'register_nav_menus' ) ) {
		register_nav_menus(
			array(
				'primary'	=> __( 'Primary Navigation', 'blakzr' ),
				'secondary'	=> __( 'Header Top Navigation', 'blakzr' )
			)
		);
	}
}

/* Custom Background */
$bg_defaults = array(
	'default-color'          => 'ffffff',
	'default-image'          => '',
	'wp-head-callback'       => '_custom_background_cb',
	'admin-head-callback'    => '',
	'admin-preview-callback' => ''
);

add_theme_support( 'custom-background', $bg_defaults );


/**
 * Register Scripts and Styles 
 */

add_action( 'init', 'register_scripts' );

function register_scripts(){
	
	/* Register Scripts */
	wp_register_script( 'gmaps', 'http://maps.google.com/maps/api/js?sensor=true', array( 'jquery' ), '3.0' );
	wp_register_script( 'colorbox', get_template_directory_uri() . '/js/jquery.colorbox-min.js', array('jquery'), '1.3.20' );
	wp_register_script( 'validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), '1.10.0' );
	wp_register_script( 'superfish', get_template_directory_uri() . '/js/superfish.js', array('jquery'), '1.4.8' );
	wp_register_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array( 'jquery' ), '2.1' );
	wp_register_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.js', array( 'jquery' ), '1.3' );
	wp_register_script( 'theme-custom-code', get_template_directory_uri() . '/js/code.js', array( 'jquery' ), '1.0' );
	wp_register_script( 'theme-custom-admin-code', get_template_directory_uri() . '/js/admin-code.js', array( 'jquery' ), '1.0' );
	wp_register_script( 'fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array( 'jquery' ), '1.0' );
	
	/* Register Styles */
	wp_register_style( 'gfonts', 'http://fonts.googleapis.com/css?family=Merriweather:400,900,700,300|Oswald:400,300,700', false, '1.0', 'all' );
	wp_register_style( 'flexslider-css', get_template_directory_uri() . '/css/flexslider.css', false, '2.1', 'all' );
	wp_register_style( 'admin-style', get_template_directory_uri() . '/css/admin-style.css', false, '1.0', 'all' );
	
}

/* Add script to Admin area */

add_action( 'admin_enqueue_scripts', 'add_custom_admin_script' );
function add_custom_admin_script( $hook ){
	
	global $post;
	
	if ( 'post-new.php' == $hook || 'post.php' == $hook ) :
		if ( 'post' == $post->post_type || 'portfolio' == $post->post_type ) :
			wp_enqueue_style( 'gfonts' );
			wp_enqueue_style( 'admin-style' );
			wp_enqueue_script( 'theme-custom-admin-code' );
		endif;
	endif;
}

/* Include scripts */

include_once( get_stylesheet_directory() . '/functions/widgets.php' );
include_once( get_stylesheet_directory() . '/functions/shortcodes.php' );
include_once( get_stylesheet_directory() . '/functions/panel.php' );
include_once( get_stylesheet_directory() . '/functions/editor.php' );

?>