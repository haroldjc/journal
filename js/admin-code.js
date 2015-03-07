/**
 *
 * Blakzr Framework
 * Admin jQuery
 *
 */

jQuery(document).ready(function(){
	
	/* ---------- Show/Hide Post Format Meta Boxes ---------- */
	if ( jQuery('#post-formats-select').length ) {
		
		$default_format = jQuery('#post-formats-select input:checked').val();
		jQuery('#format-' + $default_format + '-meta').show();
		
		jQuery('#post-formats-select input').click(function() {
			$format = jQuery('#post-formats-select input:checked').val();
			jQuery('#format-link-meta, #format-quote-meta, #format-video-meta').hide();
			jQuery('#format-' + $format + '-meta').show();
			
		});
		
	}
	
	/* ---------- Video Format Options ---------- */
	if ( jQuery('#format-video-meta').length ) {
		
		$default_radio = jQuery('#format-video-meta input[type="radio"]:checked').val();
		jQuery('#post_format_video_' + $default_radio).removeAttr('disabled');
		
		jQuery('#format-video-meta input[type="radio"]').each(function() {
			jQuery(this).click(function() {
				jQuery('#format-video-meta input[type="text"]').attr('disabled', 'disabled');
				jQuery('#post_format_video_' + jQuery(this).val()).removeAttr('disabled');
			});
		});
	}
	
	/* ---------- Portfolio Media Type ---------- */
	
	if ( jQuery('#media_type_meta').length ) {
		
		if ( '1' == jQuery('#media_type_meta input:checked').val() )
			jQuery('#media-video-meta').show();
		
		jQuery('#media_type_meta input').click(function() {
			
			if ( '1' == jQuery('#media_type_meta input:checked').val() )
				jQuery('#media-video-meta').show();
			else
				jQuery('#media-video-meta').hide();
			
		});
		
		$default_radio = jQuery('#media-video-meta input[type="radio"]:checked').val();
		jQuery('#media_video_' + $default_radio).removeAttr('disabled');
		
		jQuery('#media-video-meta input[type="radio"]').each(function() {
			jQuery(this).click(function() {
				jQuery('#media-video-meta input[type="text"]').attr('disabled', 'disabled');
				jQuery('#media_video_img').hide();
				
				jQuery('#media_video_' + jQuery(this).val()).removeAttr('disabled');
				
				if ( '2' == jQuery(this).val() ) {
					jQuery('#media_video_img').show().removeAttr('disabled');
				}
					
				
			});
		});
	}
	
	
	
});