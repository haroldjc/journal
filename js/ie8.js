jQuery(document).ready(function(){
	
	// :last-child
	if ( jQuery('.entry-aside > div:last-child').length ) jQuery('.entry-aside > div:last-child').css('border-bottom', '1px solid #ccc');
	if ( jQuery('.tabbed-container .tabs li:last-child a').length ) jQuery('.tabbed-container .tabs li:last-child a').css('border-top-right-radius', '4px');
	
	// :nth-child
	if ( jQuery('.content .entry-meta a span:nth-child(2)').length ) jQuery('.content .entry-meta a span:nth-child(2)').css('display', 'none');
	if ( jQuery('.flickr_photos ul li:nth-child(3n)').length ) jQuery('.flickr_photos ul li:nth-child(3n)').css('margin-right', '0');
	if ( jQuery('.gallery-controls li:nth-child(5n)').length ) jQuery('.gallery-controls li:nth-child(5n)').css('margin-right', '0');
	if ( jQuery('').length ) jQuery('').css('border-bottom', '1px solid #ccc');
	
	// *-of-type
	if ( jQuery('.category-posts .section:nth-of-type(2n)').length ) jQuery('.category-posts .section:nth-of-type(2n)').css('margin-right', '0');
	if ( jQuery('.comment:nth-of-type(2n)').length ) jQuery('.comment:nth-of-type(2n)').css('background-color', '#fff');
	if ( jQuery('.children .comment:nth-of-type(2n)').length ) jQuery('.children .comment:nth-of-type(2n)').css('background-color', '#f1f1f1');
	if ( jQuery('[class*="col-"]:first-of-type').length ) jQuery('[class*="col-"]:first-of-type').css('padding-left', '0');
	if ( jQuery('[class*="col-"]:last-of-type').length ) jQuery('[class*="col-"]:last-of-type').css('padding-right', '0');
	if ( jQuery('.entry-content p:last-of-type, .entry-excerpt p:last-of-type').length ) jQuery('.entry-content p:last-of-type, .entry-excerpt p:last-of-type').css('margin-bottom', '0');
	if ( jQuery('.tabbed-container .tab p:last-of-type').length ) jQuery('.tabbed-container .tab p:last-of-type').css('margin-bottom', '0');
	if ( jQuery('.tabbed-container .tab:first-of-type').length ) jQuery('.tabbed-container .tab:first-of-type').css('display', 'block');
	if ( jQuery('.accordion-container h4:first-of-type a').length ) jQuery('.accordion-container h4:first-of-type a').css('border-top', 'none');
	if ( jQuery('.acc-item:first-of-type').length ) jQuery('.acc-item:first-of-type').css('display', 'block');
	if ( jQuery('.acc-item p:last-of-type, .comment-content p:last-of-type').length ) jQuery('.acc-item p:last-of-type, .comment-content p:last-of-type').css('margin-bottom', '0');
	
});