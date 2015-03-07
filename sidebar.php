<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage AmericanJournal
 * @since AmericanJournal 1.0
 */
?>
<div class="col-1-6 first-sidebar">
	<ul class="sidebar">
		<?php if ( ! dynamic_sidebar( 'First Sidebar' ) ) : ?>
		<li class="widget">
			<h3 class="widgettitle"><?php _e('Sample Widget', 'blakzr'); ?></h3>
			<p><?php _e('This is not a real widget, just a placeholder. You can put any other available widgets in this area. Try it, what are you waiting for?', 'blakzr'); ?></p>
		</li>
		<?php endif; ?>
	</ul>
</div>
<div class="col-1-6 second-sidebar">
	<ul class="sidebar">
		<?php if ( ! dynamic_sidebar( 'Second Sidebar' ) ) : ?>
		<li class="widget">
			<h3 class="widgettitle"><?php _e('Sample Widget', 'blakzr'); ?></h3>
			<p><?php _e('This is not a real widget, just a placeholder. You can put any other available widgets in this area. Try it, what are you waiting for?', 'blakzr'); ?></p>
		</li>
		<?php endif; ?>
	</ul>
</div>
