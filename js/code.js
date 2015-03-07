/**
 *
 * Slide
 * Custom jQuery
 *
 */

/**
* hoverIntent r6 // 2011.02.26 // jQuery 1.5.1+
* <http://cherne.net/brian/resources/jquery.hoverIntent.html>
* @author    Brian Cherne brian(at)cherne(dot)net
*/
(function($){$.fn.hoverIntent=function(f,g){var cfg={sensitivity:7,interval:100,timeout:600};cfg=$.extend(cfg,g?{over:f,out:g}:f);var cX,cY,pX,pY;var track=function(ev){cX=ev.pageX;cY=ev.pageY};var compare=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);if((Math.abs(pX-cX)+Math.abs(pY-cY))<cfg.sensitivity){$(ob).unbind("mousemove",track);ob.hoverIntent_s=1;return cfg.over.apply(ob,[ev])}else{pX=cX;pY=cY;ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}};var delay=function(ev,ob){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t);ob.hoverIntent_s=0;return cfg.out.apply(ob,[ev])};var handleHover=function(e){var ev=jQuery.extend({},e);var ob=this;if(ob.hoverIntent_t){ob.hoverIntent_t=clearTimeout(ob.hoverIntent_t)}if(e.type=="mouseenter"){pX=ev.pageX;pY=ev.pageY;$(ob).bind("mousemove",track);if(ob.hoverIntent_s!=1){ob.hoverIntent_t=setTimeout(function(){compare(ev,ob)},cfg.interval)}}else{$(ob).unbind("mousemove",track);if(ob.hoverIntent_s==1){ob.hoverIntent_t=setTimeout(function(){delay(ev,ob)},cfg.timeout)}}};return this.bind('mouseenter',handleHover).bind('mouseleave',handleHover)}})(jQuery);

/***/

jQuery(document).ready(function(){
	
	
	/* Navigation: Category Submenu 
	-------------------------------------------------------------- */
	
	if ( jQuery('.menu-item-object-category').length ) {
		
		var submenu = jQuery('.submenu-box').html();
		var wpPath = jQuery('.submenu-box').attr('data-url');
		
		jQuery('.navfirstlevel > li.menu-item-object-category').each(function() {
			jQuery(this).append(submenu);
			var catName = jQuery(this).children('a').text();
			jQuery(this).find('.category-submenu').load(wpPath + '/inc/category-submenu.php?catname=' + catName);
		});
		
		/*submenuHover();
		jQuery(window).bind('resize', submenuHover);
		
		function submenuHover() {
			var windowWidth = jQuery(window).width();
			if ( windowWidth < 768 ) {
				jQuery('.navfirstlevel > li.menu-item-object-category').hoverIntent(function() {
					jQuery(this).find('.category-submenu').fadeIn();
				}, function() {
					jQuery(this).find('.category-submenu').fadeOut();
				});
			}
		}*/
		
		jQuery('.navfirstlevel > li.menu-item-object-category').hoverIntent(function() {
			jQuery(this).find('.category-submenu').fadeIn();
		}, function() {
			jQuery(this).find('.category-submenu').fadeOut();
		});
		
	}
	
	/* Responsive Navigation
	-------------------------------------------------------------- */
	
	if ( jQuery('.navfirstlevel').length ) {
		
		jQuery('#nav-toggle').click(function() {
			jQuery('.navfirstlevel').slideToggle();
			return false;
		});
		
		jQuery(window).resize(function() {
			if ( jQuery(window).width() > 768 && jQuery('.navfirstlevel').not(':visible') ) {
				jQuery('.navfirstlevel').show();
			}
		});
		
	}
	
	
	/* Navigation: Search
	-------------------------------------------------------------- */
	
	// Open
	if ( jQuery('.search-button').length ) {
		
		jQuery('.search-button').click(function() {
			jQuery(this).fadeOut(300);
			jQuery('.navfirstlevel > li').fadeOut(300);
			jQuery('.nav-search-box, .search-button-close').fadeIn(300);
			jQuery('#nav-toggle').css('z-index', '-999');
			jQuery('.nav-search-box input').val('').focus();
			return false;
		});
		
	}
	
	// Close
	if ( jQuery('.search-button-close').length ) {
		
		jQuery('.search-button-close').click(function() {
			jQuery('.nav-search-box, .search-button-close').fadeOut(300);
			jQuery('#nav-toggle').css('z-index', 'auto');
			jQuery('.navfirstlevel > li, .search-button').fadeIn(300);
			return false;
		});
		
		if ( jQuery('.nav-search-box input:focus') ) {
			jQuery(document).keyup(function(e) {
				if ( e.keyCode == 27 ) jQuery('.search-button-close').click();
			});
		}
		
	}
	
	
	/* Responsive Videos
	-------------------------------------------------------------- */
	
	if ( jQuery('.entry-content').length )
		jQuery('.entry-content').fitVids();
		
	if ( jQuery('.widget_video').length )
		jQuery('.widget_video').fitVids();
		
	if ( jQuery('.video-container').length )
   		jQuery('.video-container').fitVids();

	if ( jQuery('.entry-media').length )
		jQuery('.entry-media').fitVids();
		
		
	/* Flexslider: responsive sliders
	-------------------------------------------------------------- */
		
	/* Post Image Gallery (Widget) */
	if ( jQuery('.post-image-gallery').length ) {
		jQuery('.post-image-gallery').flexslider({
			slideshow: false,
			directionNav: false,
			manualControls: '.post-image-gallery .gallery-controls li'
		});
	}
	
	/* Gallery Post Format */
	if ( jQuery('.entry-gallery').length ) {
		jQuery('.entry-gallery').flexslider({
			slideshow: false,
			directionNav: false,
			manualControls: '.entry-gallery .gallery-controls li'
		});
	}
	
	
	/* Flexslider: responsive sliders
	-------------------------------------------------------------- */
	
	jQuery('a[rel^="lightbox"]').colorbox({
		maxWidth: '90%',
		maxHeight: '90%',
		opacity: 0.6
	});
	
	
	/* Contact Form Validation
	-------------------------------------------------------------- */
	
	if ( jQuery('#template-form').length ) {
		jQuery('#template-form').validate({
			errorElement: "em",
			errorPlacement: function(error, element) {
				//error.appendTo(element.prev());
				element.next('em').show();
			}
		});
	}
	
	
	/* Tabbed Container
	-------------------------------------------------------------- */
    
	if ( jQuery('.tabbed-container').length ) {
    
		jQuery('.tabbed-container').each(function(){
    
			var $tabbed = jQuery(this);
			var $tabs = '';
			var $counter = 1;
			var $thetab_id = $tabbed.attr('id');
    
			// Create tab unordered list and prepend it to container
			$tabbed.find('.tab').each(function(){
				$tab_title = jQuery(this).attr('data-title');
				jQuery(this).attr('id', $thetab_id + 'tab-' + $counter);
				$tabs += '<li><a href="#" data-id="' + $thetab_id + 'tab-' + $counter + '">' + $tab_title + '</a></li>';
				$counter++;
			});
    
			$tabbed.prepend('<ul class="tabs">' + $tabs + '</ul>');
			$tabbed.find('.tabs li:first-child a').addClass('current-tab');
    
			// Enable tab functionality for each 'tabbed container'
			jQuery('.tabbed-container#' + $thetab_id + ' .tabs a').each(function() {
				jQuery(this).click(function() {
					jQuery('.tabbed-container#' + $thetab_id + ' .tab').hide();
					jQuery('.tabbed-container#' + $thetab_id + ' #' + jQuery(this).attr('data-id')).show();
					jQuery('.tabbed-container#' + $thetab_id + ' .tabs a').removeClass('current-tab');
					jQuery(this).addClass('current-tab');
					return false;
				});
			});
		});
    
	}
    
    /* Accordion
	-------------------------------------------------------------- */
    
	if ( jQuery('.accordion-container').length ) {
    
		// Function to set custom width to the item container
		function accordionWidth() {
			jQuery('.accordion-container .acc-item').each(function(){
				$width = jQuery(this).parent().width();
				jQuery(this).width($width - 40);
			});
		}
    
		// Trigger function on page load or window is resized
		accordionWidth();
    
		jQuery(window).resize(function() {
			accordionWidth();
		});
    
		// Add 'current' class to the first item
		jQuery('.accordion-container h4:first-of-type a').addClass('current-acc');
    
		// Accordion functionality
		jQuery('.accordion-container').each(function(){
    
			var $acc_id = jQuery(this).attr('id');
    
			jQuery('.accordion-container#' + $acc_id + ' h4 a').each(function() {
    
				jQuery(this).click(function() {
    
					if ( ! jQuery(this).hasClass('current-acc') ) {
    
						jQuery('.accordion-container#' + $acc_id + ' .acc-item').slideUp();
						jQuery(this).parent().next().slideDown();
    
						jQuery('.accordion-container#' + $acc_id + ' h4 a').removeClass('current-acc');
						jQuery(this).addClass('current-acc');
    
					}else{
    
						jQuery('.accordion-container#' + $acc_id + ' .acc-item').slideUp();
						jQuery('.accordion-container#' + $acc_id + ' h4 a').removeClass('current-acc');
    
					}
    
					return false;
    
				})
    
			});
    
		});
    
	}
	
	/* Footer Sidebar Column Height Fix
	-------------------------------------------------------------- */
	
	if ( jQuery('.footer-sidebar').length ) {
		
		// these are (ruh-roh) globals. You could wrap in an
		// immediately-Invoked Function Expression (IIFE) if you wanted to...
		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = new Array();
        
		function setConformingHeight(el, newHeight) {
			// set the height to something new, but remember the original height in case things change
			el.data("originalHeight", (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight")));
			el.height(newHeight);
		}
        
		function getOriginalHeight(el) {
			// if the height has changed, send the originalHeight
			return (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight"));
		}
        
		function columnConform() {
        
			// find the tallest DIV in the row, and set the heights of all of the DIVs to match it.
			jQuery('.footer-sidebar .widget').each(function() {
        
				// "caching"
				var $el = jQuery(this);
        
				var topPosition = $el.position().top;
        
				if (currentRowStart != topPosition) {
        
					// we just came to a new row.  Set all the heights on the completed row
					for(currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);
        
					// set the variables for the new row
					rowDivs.length = 0; // empty the array
					currentRowStart = topPosition;
					currentTallest = getOriginalHeight($el);
					rowDivs.push($el);
        
				} else {
        
					// another div on the current row.  Add it to the list and check if it's taller
					rowDivs.push($el);
					currentTallest = (currentTallest < getOriginalHeight($el)) ? (getOriginalHeight($el)) : (currentTallest);
        
				}
				// do the last row
				for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) setConformingHeight(rowDivs[currentDiv], currentTallest);
        
			});
        
		}
        
        
		jQuery(window).resize(function() {
			if ( jQuery(window).width() < 513 ) {
				jQuery('.footer-sidebar .widget').removeAttr('style');
			} else {
				columnConform();
			}
		});
        
		jQuery(window).load(function() {
			columnConform();
		});
		
	}
	
	
});