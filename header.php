<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till </header>
 *
 * @package WordPress
 * @subpackage Journal
 * @since Journal 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'blakzr' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( '' != get_option( 'blakzr_rss_url', '' ) ) : ?>
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo( 'name' ); ?> RSS 2.0" href="<?php echo get_option('blakzr_rss_url'); ?>">
<?php endif; ?>
<?php if ( '' != get_option( 'blakzr_favicon_url', '' ) ) : ?>
<link rel="icon" href="<?php echo get_option( 'blakzr_favicon_url' ); ?>">
<?php endif; ?>
<?php
	/* Enqueue Stylesheets
	 */
	wp_enqueue_style( 'gfonts' );
	wp_enqueue_style( 'flexslider-css' );

	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Load the jQuery plugin to validate the contact form.
	 */
	if ( is_page_template( 'template-contact.php' ) || 'true' == get_option( 'blakzr_static_footer', 'true' ) )
		wp_enqueue_script( 'validate' );

	wp_enqueue_script( 'gmaps' );
	wp_enqueue_script( 'fitvids' );
	wp_enqueue_script( 'flexslider' );
	wp_enqueue_script( 'colorbox' );
	//wp_enqueue_script( 'superfish' );
	//wp_enqueue_script( 'easing' );
	wp_enqueue_script( 'theme-custom-code' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
<?php

// Custom CSS generated for the Theme Options settings

$main_color = get_option( 'blakzr_main_color_code', '#FFCC33' );

if ( '#FFCC33' != $main_color ) : 
?>
<style type="text/css">
	.navfirstlevel > li > a:hover,
	.navfirstlevel > li.current-menu-item > a,
	.media-button:hover,
	figure:hover .media-button,
	a.search-button:hover,
	a.search-button-close:hover {
		color: <?php echo $main_color; ?>;
	}
</style>
<?php endif; ?>
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-extended-selectors.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/ie8.js" type="text/javascript"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>
	
	<header class="main">
		<nav class="top-nav double-border">
			<?php if ( has_nav_menu( 'primary' ) ) { ?>                    
		          <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container' => 'div', 'menu_class' => 'topmenu', 'depth' => 1 ) ); ?>
		    <?php } else { ?>
			<ul>
				<li><a href="<?php echo site_url( '/' ); ?>"><?php _e('Homepage', 'blakzr'); ?></a></li>
			</ul>
			<?php } ?>
			<span class="date"><?php echo date( _x( 'l, F d, Y', 'Header current date format', 'blakzr' ) ); ?></span>
		</nav>
		<?php
		$logo_url = get_option( 'blakzr_site_logo', '' );
		?>
		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"<?php echo '' == $logo_url ? '' : ' style="line-height:0;"' ?>>
			<?php if ( empty( $logo_url ) ) : ?>
			<span>
				<?php bloginfo( 'name' ); ?>
			</span>
			<?php else : ?>
				<?php if ( 'true' == get_option( 'blakzr_logo_width', 'false' ) ) : ?>
			<figure>
				<img src="<?php echo $logo_url; ?>" alt="<?php bloginfo( 'name' ); ?>">
			</figure>
				<?php else : ?>
				<img src="<?php echo $logo_url; ?>" alt="<?php bloginfo( 'name' ); ?>">
				<?php endif; ?>
			<?php endif; ?>
		</a>
		<nav class="main-nav">
			<a href="#" id="nav-toggle"><?php _e( 'Navigation', 'blakzr' ); ?></a>
			<?php if ( has_nav_menu( 'primary' ) ) { ?>                    
		          <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'div', 'menu_class' => 'navfirstlevel' ) ); ?>
		    <?php } else { ?>
			<ul class="navfirstlevel">
				<li><a href="<?php echo site_url( '/' ); ?>"><?php _e('Home', 'blakzr'); ?></a></li>
			</ul>
			<?php } ?>
			<a href="#" class="search-button">(</a>
			<div class="nav-search-box">
				<form action="<?php echo home_url(); ?>" method="get">
					<input type="text" name="s" value="" placeholder="<?php _e( 'Search', 'blakzr' ); ?>">
				</form>
			</div>
			<a href="#" class="search-button-close">x</a>
			<div class="submenu-box" data-url="<?php echo get_template_directory_uri(); ?>">
				<div class="category-submenu grid"></div>
			</div>
		</nav>
	</header>
