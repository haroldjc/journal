<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
 */
?>
		<?php if ( 'true' == get_option( 'blakzr_display_footer_sidebar', 'true' ) ) : ?>
		<section class="footer-sidebar group">
			<ul class="sidebar grid">
				<?php if ( ! dynamic_sidebar( 'Footer' ) ) : ?>
				<li class="widget col-2-6">
					<h3 class="widgettitle"><?php _e('Sample Widget', 'blakzr'); ?></h3>
					<p><?php _e('This is not a real widget, just a placeholder. You can put any other available widgets in this area. Try it, what are you waiting for?', 'blakzr'); ?></p>
				</li>
				<?php endif; ?>
			</ul>
		</section>
		<?php endif; ?>
		
	</div>
	
	<footer>
		<div class="wrapper">
			<?php $display_menu = get_option( 'blakzr_display_menu', 'true' ); ?>
			<?php if ( 'true' == $display_menu ) : ?>
			<span class="disclaimer"><?php echo get_option( 'blakzr_footer_disclaimer', '&copy; 2013 - Designed by Harold Coronado' ); ?></span>
			<?php endif; ?>
			<h4 class="footer-logo"><?php bloginfo( 'name' ); ?></h4>
			<?php if ( 'true' != $display_menu ) : ?>
			<span class="disclaimer" style="float:none;"><?php echo get_option( 'blakzr_footer_disclaimer', '&copy; 2013 - Designed by Harold Coronado' ); ?></span>
			<?php endif; ?>
			<?php if ( 'true' == get_option( 'blakzr_display_menu', 'true' ) ) : ?>
			<nav class="footer-menu">
				<?php if ( has_nav_menu( 'primary' ) ) { ?>                    
			          <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'container' => 'div', 'menu_class' => 'topmenu', 'depth' => 1 ) ); ?>
			    <?php } else { ?>
				<ul>
					<li><a href="<?php echo site_url( '/' ); ?>"><?php _e('Homepage', 'blakzr'); ?></a></li>
				</ul>
				<?php } ?>
			</nav>
			<?php endif; ?>
		</div>
	</footer>
	
	<?php if ( '' != get_option( 'blakzr_tracking_code', '' ) ) :
		echo get_option( 'blakzr_tracking_code' );
	endif; ?>
	<?php
	   /* Always have wp_footer() just before the closing </body>
	    * tag of your theme, or you will break many plugins, which
	    * generally use this hook to reference JavaScript files.
	    */
	    wp_footer();
	?>
</body>

</html>