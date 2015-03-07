/**
 *
 * Blakzr Panel jQuery
 * Version: 1.0
 * Author: Harold Coronado
 * Author URI: http://blakzr.com
 *
**/

jQuery(document).ready(function(){
	
	/* ---------- Sections ---------- */
	
	if (jQuery('.nav-tab-wrapper').length){
		jQuery('.nav-tab-wrapper a').each(function(){
			jQuery(this).click(function(){
				jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active');
				jQuery(this).addClass('nav-tab-active');
				
				dataid = jQuery(this).attr('data-id');
				jQuery('#options-table tr').hide();
				jQuery('#options-table tr[data-id="' + dataid + '"]').fadeIn();
				return false;
			});
		});
	}
	
	/* ---------- Options Saving ---------- */
	
	jQuery('.options-save').click(function() {
		jQuery('.saving').css('opacity', '1');
		jQuery.post('admin.php?page=blakzrpanel', jQuery('#options_form').serialize(), function(){
			jQuery('.saving').css('opacity', '0');
			jQuery('.saved').slideDown().delay(3000).slideUp(300);
		});
		return false;
	});
	
	jQuery('.options-save-slider').click(function() {
		jQuery('.saving').css('opacity', '1');
		jQuery.post('admin.php?page=blakzrpanel_slider', jQuery('#options_form').serialize(), function(){
			jQuery('.saving').css('opacity', '0');
			jQuery('.saved').slideDown().delay(3000).slideUp(300);
		});
		return false;
	});
	
	/* ---------- Options Reset ---------- */
	
	jQuery('#options_reset').submit(function(){
		if ( ! confirm( 'Do you really want to reset all the theme options?' ) ) return false;
	});
	
	/* ---------- ColorPicker ---------- */
	
	jQuery('.colorchoose').each(function(){
		var current = jQuery(this);
		current.ColorPicker({
			flat: false,
			color: current.attr('data-color'),
			onChange: function (hsb, hex, rgb) {
				current.css('backgroundColor', '#' + hex);
				current.next('input').attr('value','#' + hex);
			},
			onSubmit: function(hsb, hex, rgb, el) {
				current.css('backgroundColor', '#' + hex);
				current.next('input').attr('value','#' + hex);
			}
		});
	});
	
	/* ---------- Pattern Control ---------- */
	
	if (jQuery('.patternselect').length){
		jQuery('.patternselect').each(function(){
			currentDiv = jQuery(this);
			currentDiv.children('select').change(function(){
				source = currentDiv.find('select option:selected').attr('data-src');
				currentDiv.find('.showpattern span').css('background-image', 'url(' + source + ')');
			});
		});
	}
	
	/* ---------- Thickbox Uploader ---------- */
	
	jQuery('.upload').each(function(){
		field_id = jQuery(this).attr('id');
		jQuery('#bt_' + field_id).click(function(){
			field_id = jQuery(this).prev().attr('id');
			formfield = jQuery('#' + field_id).attr('name');
			tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
			return false;
		});
		
		window.send_to_editor = function(html) {
			imgurl = jQuery('img', html).attr('src');
			jQuery('#' + field_id).val(imgurl);
			tb_remove();
		}
	});
	
	/* ---------- Slider ---------- */
	
	if ( jQuery("#blakzr_slider").length ) {
		
		// Make Slides sortable
		jQuery("#blakzr_slider").sortable({
			placeholder: 'slide_placeholder'
		});
		
		// Enable appendo plugin
		jQuery('#blakzr_slider').appendo({
			allowDelete: false,
			labelAdd: 'Add New Slide',
			subSelect: 'li.homeslide:last'
		});
		
		function resetSlideFields() {
			var last_form = jQuery('#blakzr_slider li.homeslide:last');
			last_form.find('input:text, input:hidden').val('');
			last_form.find('.slide-image').attr('style', '').removeClass('over-image');
		}
		
		// Remove Slide button
		jQuery('#blakzr_slider li.homeslide .remove').live('click', function(){
			if ( jQuery('#blakzr_slider li.homeslide').size() == 1 ) {
				alert('Sorry, you need at least one slide');	
			}else{
				jQuery(this).parents('.homeslide').slideUp(300, function() {
					jQuery(this).remove();
				});
			}
			if ( jQuery('#blakzr_slider li.homeslide').size() == 2) jQuery('#blakzr_slider li.homeslide .remove:first').hide();
			return false;
		});
		
		jQuery('.appendoButtons button').click(function(){
			jQuery('#blakzr_slider li.homeslide .remove').show();
			slideImage();
			resetSlideFields();
		});
		
		// Upload Slide image
		jQuery(window).load(function() {
			slideImage();
		});
		
		function slideImage() {
			jQuery('.slide-image').each(function(){

				jQuery(this).live('click', function(){
					$index = jQuery('.slide-image').index(this);
					$img_field = jQuery('.slide-image:eq(' + $index + ')').find('.image-url');
					tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				});

				window.send_to_editor = function(html) {

					var class_string    = jQuery( 'img', html ).attr( 'class' );
					var image_url       = jQuery( 'img', html ).attr( 'src' );
					var classes         = class_string.split( /\s+/ );
					var image_id        = 0;

					for ( var i = 0; i < classes.length; i++ ) {
						var source = classes[i].match(/wp-image-([0-9]+)/);
						if ( source && source.length > 1 ) {
							image_id = parseInt( source[1] );
						}
					}

					jQuery('.slide-image:eq(' + $index + ')').css('background-image', 'url(' + image_url + ')').addClass('over-image');
					$img_field.val(image_id);
					tb_remove();
				}
			});
		}
		
	}
	
	/* ---------- Category Arrange ---------- */
	
	if ( jQuery('#cat-sortable').length ) {
		
		// Make Categories sortable
		jQuery('#cat-sortable').sortable({
			placeholder: 'placeholder',
			stop: function() {
				jQuery('#cat-sortable li:nth-child(n)').css({'color' : '#ccc'});
				jQuery('#cat-sortable li:nth-child(-n+' + jQuery('#blakzr_home_cat_number').val() + ')').css({'color' : '#333'});
			}
		});
		
		if ( jQuery('#blakzr_home_cat_number').length ) {
			jQuery('#blakzr_home_cat_number').change(function() {
				if ( jQuery(this).val() != '-1' ) {
					jQuery('#cat-sortable li:nth-child(n)').css({'color' : '#ccc'});
					jQuery('#cat-sortable li:nth-child(-n+' + jQuery(this).val() + ')').css({'color' : '#333'});
				} else {
					jQuery('#cat-sortable li:nth-child(n)').css({'color' : '#333'});
				}
			});
		}
	}
	
	
	
	
	
	
	
});