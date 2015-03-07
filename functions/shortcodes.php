<?php

/**************************************/
/************* SHORTCODES *************/
/**************************************/

/* Video
-------------------------------------------------------------- */

// youtube [youtube title="" width="" height="" autoplay=""]$content[/youtube]
add_shortcode( 'youtube', 'shortcode_youtube' );
function shortcode_youtube( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title'		=> 'YouTube video player',
		'width'		=> '690',
		'height'	=> '388',
		'autoplay'	=> false
	), $atts ) );
	
	$embed_url = preg_replace( '/^http:\/\/www\.youtube\.com\/watch\?v=([\w-]+)&?(.{0,})/', 'http://www.youtube.com/embed/\1', $content );
	if ( $autoplay ) $embed_url .= '?autoplay=1';
	return '<iframe title="' . $title . '" width="' . $width . '" height="' . $height . '" src="' . $embed_url . '" frameborder="0" allowfullscreen></iframe>';
}

// vimeo [vimeo width="" height="" autoplay=""]$content[/vimeo]
add_shortcode( 'vimeo', 'shortcode_vimeo' );
function shortcode_vimeo( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'width'		=> '690',
		'height'	=> '388',
		'autoplay'	=> false
	), $atts ) );
	
	$main_color = substr( get_option( 'quartz_main_color_code', '#1d94df' ), 1);
	
	$embed_url = preg_replace( '/^http:\/\/vimeo\.com\/(\d+)/', 'http://player.vimeo.com/video/\1', $content );
	
	$embed_url .= '?portrait=0&amp;color=' . $main_color;
	if ( $autoplay ) $embed_url .= '&amp;autoplay=1';
	
	return '<iframe src="' . $embed_url . '" width="' . $width . '" height="' . $height . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
}

// self-hosted video [video controls="docked|floating|none" img="" width="" height="" autoplay=""]$content[/video]
add_shortcode( 'video', 'shortcode_video' );
function shortcode_video( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'controls'	=> 'docked',
		'img'		=> '',
		'width'		=> '690',
		'height'	=> '388',
		'autoplay'	=> false
	), $atts ) );
	
	$src = $content;
	
	if ( $autoplay ) $autoplay = '&autoPlay=true';
	else $autoplay = '';

	return '<object width="' . $width . '" height="' . $height . '"> <param name="movie" value="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf"></param><param name="flashvars" value="src=' . $src . '&controlBarMode=' . $controls . '&poster=' . $img . $autoplay . '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://fpdownload.adobe.com/strobe/FlashMediaPlayback.swf" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="' . $width . '" height="' . $height . '" flashvars="src=' . $src . '&controlBarMode=' . $controls . '&poster=' . $img . $autoplay . '"></embed></object>';
}


/* Columns
-------------------------------------------------------------- */

// two columns [half]$content[/half]
add_shortcode( 'half', 'shortcode_halfcolumns' );
function shortcode_halfcolumns( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'position'	=> 'normal'
	), $atts ) );
	
	return '<div class="half ' . $position . '">' . do_shortcode( $content ) . '</div>' . ($position == 'last' ? "\n" . '<div class="clear"></div>' : '');
}

// three columns [third]$content[/third]
add_shortcode( 'third', 'shortcode_threecolumns' );
function shortcode_threecolumns( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'position'	=> 'normal',
		'span'		=> '1'
	), $atts ) );
	
	$col_width = 31.3;
	if ( 2 == $span ) $span_value = ' style="width:' . ( ( $col_width * $span ) + 3 ) . '%"';
	else $span_value = '';

	return '<div class="third ' . $position . '"' . $span_value . '>' . do_shortcode( $content ) . '</div>' . ( $position == 'last' ? "\n" . '<div class="clear"></div>' : '' );
}

// four columns [fourth]$content[/fourth]
add_shortcode( 'fourth', 'shortcode_fourthcolumns' );
function shortcode_fourthcolumns( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'position'	=> 'normal',
		'span'		=> '1'
	), $atts ) );
	
	$span = (int) $span;
	$col_width = 22.75;
	if ( 2 == $span || 3 == $span ) $span_value = ' style="width:' . ( ( $col_width * $span ) + ( ( $span - 1 ) * 3 ) ) . '%"';
	else $span_value = '';

	return '<div class="fourth ' . $position . '"' . $span_value . '>' . do_shortcode( $content ) . '</div>' . ( $position == 'last' ? "\n" . '<div class="clear"></div>' : '' );
}

// fifth columns [fifth]$content[/fifth]
add_shortcode( 'fifth', 'shortcode_fifthcolumns' );
function shortcode_fifthcolumns( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'position'	=> 'normal',
		'span'		=> '1'
	), $atts ) );
	
	$span = (int) $span;
	$col_width = 17.6;
	if ( $span > 1 && $span < 5 ) $span_value = ' style="width:' . ( ( $col_width * $span ) + ( ( $span - 1 ) * 3 ) ) . '%"';
	else $span_value = '';

	return '<div class="fifth ' . $position . '"' . $span_value . '>' . do_shortcode( $content ) . '</div>' . ( $position == 'last' ? "\n" . '<div class="clear"></div>' : '' );
}

// sixth columns [sixth]$content[/sixth]
add_shortcode( 'sixth', 'shortcode_sixthcolumns' );
function shortcode_sixthcolumns( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'position'	=> 'normal',
		'span'		=> '1'
	), $atts ) );
	
	$span = (int) $span;
	$col_width = 14.16;
	if ( $span > 1 && $span < 6 ) $span_value = ' style="width:' . ( ( $col_width * $span ) + ( ( $span - 1 ) * 3 ) ) . '%"';
	else $span_value = '';

	return '<div class="sixth ' . $position . '"' . $span_value . '>' . do_shortcode( $content ) . '</div>' . ( $position == 'last' ? "\n" . '<div class="clear"></div>' : '' );
}


/* Blockquote
-------------------------------------------------------------- */

// Blockquote [blockquote]$content[/blockquote]
add_shortcode( 'blockquote', 'shortcode_blockquote' );
function shortcode_blockquote( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'align' => 'right'
	), $atts ));
	
	return '<blockquote' . ( ! empty( $align ) ? ' class="sided b' . $align . '"' : '' ) . '>
			<p>' . $content . '</p>
			</blockquote>';
}


/* Buttons
-------------------------------------------------------------- */

//Regular Button [button]$content[/button]
add_shortcode( 'button', 'shortcode_button' );
function shortcode_button( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'url'		=> '#',
		'color'		=> ''
	), $atts ));
	
	// Add attributes
	$style = ! empty( $color ) ? 'background-color:' . $color . ';' : '';
	
	if ( ! empty( $style ) ) $style = 'style="' . $style . '"';
	
	return '<a class="button" href="' . $url . '"' . $style . '>' . $content . '</a>';
}


/* Maps
-------------------------------------------------------------- */

//Google Map [gmap]
add_shortcode( 'gmap', 'shortcode_gmap' );
function shortcode_gmap( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'lat'					=> '53.406278',
		'lng'					=> '-2.987914',
		'zoom'					=> 8,
		'height'				=> 300,
		'type'					=> 'ROADMAP',
		'pancontrol'			=> 'false',
		'zoomcontrol'			=> 'true',
		'maptypecontrol'		=> 'true',
		'scalecontrol'			=> 'false',
		'streetviewcontrol'		=> 'true',
		'overviewmapcontrol'	=> 'true'
	), $atts ));
	
	$map_id = 'map_0' . rand( 1, 999 );
	
	return '<div style="width:auto;">
				<script type="text/javascript">
					/* <![CDATA[ */
					jQuery(document).ready(function(){
						var latlng = new google.maps.LatLng(' . $lat . ', ' . $lng . ');
						var myOptions = {
							zoom: ' . $zoom . ',
							center: latlng,
							panControl: ' . $pancontrol . ',
							zoomControl: ' . $zoomcontrol . ',
							mapTypeControl: ' . $maptypecontrol . ',
							scaleControl: ' . $scalecontrol . ',
							streetViewControl: ' . $streetviewcontrol . ',
							overviewMapControl: ' . $overviewmapcontrol . ',
							mapTypeId: google.maps.MapTypeId.' . strtoupper($type) . '
					    };
						var map = new google.maps.Map(document.getElementById("' . $map_id . '"), myOptions);
					});
					/* ]]> */
				</script>
				<div class="gmap" id="' . $map_id . '" style="width:100%;height:' . $height . 'px;">
				</div>
			</div>';
}


/* Text Highlight
-------------------------------------------------------------- */

// Text Highlight [highlight]$content[/highlight]
add_shortcode( 'highlight', 'shortcode_highlight' );

function shortcode_highlight( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'color'		=> '#99CC00',
		'textcolor' => '#FFF'
	), $atts ));
	
	return '<span class="highlight" style="background-color:' . $color . ';color:' . $textcolor . '">' . do_shortcode($content) . '</span>';
}


/* Tabs
-------------------------------------------------------------- */

add_shortcode( 'tabbed', 'shortcode_tabbed' );

function shortcode_tabbed ( $atts, $content = null ) {
	$tab_id = rand( 1, 999 );
	return '<div class="tabbed-container" id="' . $tab_id  . '-">' . "\n\t" . do_shortcode( $content ) . "\n" . '</div>';
}

add_shortcode( 'tab', 'shortcode_tab' );

function shortcode_tab ( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title'	=> __( 'Tab', 'blakzr' )
	), $atts ));
	
	return '<div class="tab" data-title="' . $title . '">' . "\n\t" . wpautop( do_shortcode( $content ) ) . "\n" . '</div>';
}


/* Accordion
-------------------------------------------------------------- */

add_shortcode( 'accordion', 'shortcode_accordion' );

function shortcode_accordion ( $atts, $content = null ) {
	$acc_id = rand( 1, 999 );
	return '<div class="accordion-container" id="acc-' . $acc_id . '">' . "\n\t" . do_shortcode( $content ) . "\n" . '</div>';
}

add_shortcode( 'section', 'shortcode_section' );

function shortcode_section ( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'title'	=> __( 'Section', 'blakzr' )
	), $atts ));
	
	return '<h4><a href="#">' . $title . '</a></h4>' . "\n" . '<div class="acc-item">' . "\n\t" . wpautop( do_shortcode( $content ) ) . "\n" . '</div>';
}



?>