<?php

/***********************************/
/************* WIDGETS *************/
/***********************************/

/***** Sidebars *****/

/* First Widget Area */
$args = array(
	"name"			=> __( 'First Sidebar', 'blakzr' ),
	"id"			=> "first-sidebar",
	"description"	=> __('Main sidebar Widget area. First column.', 'blakzr'),
	'before_title'	=> '<h4 class="widgettitle">',
	'after_title'   => '</h4>'
);
if (function_exists('register_sidebar')) register_sidebar($args);

/* Second Widget Area */
$args = array(
	"name"			=> __( 'Second Sidebar', 'blakzr' ),
	"id"			=> "second-sidebar",
	"description"	=> __('Main sidebar Widget area. Second column.', 'blakzr'),
	'before_title'	=> '<h4 class="widgettitle">',
	'after_title'   => '</h4>',
);
if (function_exists('register_sidebar')) register_sidebar($args);

/* Footer Widget Area */
$args = array(
	"name"			=> __( 'Footer', 'blakzr' ),
	"id"			=> "footer-sidebar",
	"description"	=> __('Widget area for the Footer', 'blakzr'),
	'before_title'	=> '<h4 class="widgettitle">',
	'after_title'   => '</h4>',
	'before_widget' => '<li id="%1$s" class="widget col-2-6 %2$s">',
);
if (function_exists('register_sidebar')) register_sidebar($args);

/***** Widgets *****/

/*****************************************/
/********** Widget: Tweets **********/
/*****************************************/

class widget_show_tweets extends WP_Widget {
	function widget_show_tweets() {
		$widget_options = array( 'description' => __( 'Show your Twitter stream', 'blakzr' ), 'classname' => 'tweets' );
		parent::WP_Widget( false, $name = 'Journal - Tweets', $widget_options );	
	}
	
	function widget( $args, $instance ) {		
	    extract( $args );
		$title = empty( $instance[ 'title' ] ) ? __( 'Latest Tweets', 'blakzr' ) : apply_filters( 'widget_title', $instance[ 'title' ] );
		$username = empty( $instance[ 'username' ] ) ? '@twitter' : apply_filters( 'widget_username', $instance[ 'username' ] );
		$tweets_number = empty( $instance[ 'tweets_number' ] ) ? 3 : apply_filters( 'widget_tweets_number', $instance[ 'tweets_number' ] );
		$retweet = empty( $instance[ 'retweet' ] ) ? 'false' : esc_attr( $instance[ 'retweet' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				if ( substr( $username, 0, 1 ) == '@' ) {
					$username = explode( "@", $username );
					$username = $username[1];
				}
				
				// Patterns and Replace strings
				$patterns = array();
				$replace = array();
				$patterns[0] = '@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@';
				$patterns[1] = '/(^|\s)@(\w+)/';
				$patterns[2] = '/(^|\s)#(\w+)/';
				$replace[0] = '<a href="\1" target="_blank">\1</a>';
				$replace[1] = '\1@<a href="http://twitter.com/\2" target="_blank">\2</a>';
				$replace[2] = '\1<a href="http://twitter.com/#!/search?q=%23\2" target="_blank">#\2</a>';
				
				// Set or Get transient
				$transient_name = 'blakzr_widget_tweets' . $this->number;
				$tweets = get_transient( $transient_name );
								
				if ( false === $tweets ) :
					$data_url = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $username . '&count=' . $tweets_number . '&include_rts=' . $retweet;
					$tweets_data = wp_remote_retrieve_body( wp_remote_get( $data_url ) );
					
					if ( is_wp_error( $tweets_data ) ) :
						_e( "Tweets can't be loaded", 'blakzr' );
					else :
						$tweets_json = json_decode( $tweets_data, true );
						$tweets = $tweets_json;
						set_transient( $transient_name, $tweets_json, 60 * 60 * 2 );
					endif;
				endif;
				
				foreach ( $tweets as $tweet ) :
					$tweet_text = ( string ) $tweet['text'];
					$tweet_text = preg_replace( $patterns, $replace, $tweet_text ); ?>
					<p class="tweet">
						<?php echo $tweet_text; ?>
						<cite><?php _e( 'By', 'blakzr' ); ?> <a href="http://twitter.com/#!/<?php echo $username; ?>">@<?php echo $username; ?></a> <a href="http://twitter.com/#!/<?php echo $username; ?>/status/<?php echo (string) $tweet['id_str']; ?>" target="_blank"><?php echo human_time_diff( strtotime( ( string ) $tweet['created_at'] ), current_time('timestamp') ) . ' ago'; ?></a></cite>
					</p>
					<?php
				endforeach;
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
						
		// Update Transient
		$data_url = 'http://api.twitter.com/1/statuses/user_timeline.json?screen_name=' . $new_instance[ 'username' ] . '&count=' . $new_instance[ 'tweets_number' ] . '&include_rts=' . $new_instance[ 'retweet' ];
		$tweets_data = wp_remote_retrieve_body( wp_remote_get( $data_url ) );
												
		if ( is_wp_error( $tweets_data ) ) :
			echo 'Something went wrong!';
		else :
			$tweets_json = json_decode( $tweets_data, true );
			set_transient( 'blakzr_widget_tweets' . $this->number, $tweets_json, 60 * 60 * 2 );
		endif;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'username' ] = strip_tags( $new_instance[ 'username' ] );
		$instance[ 'retweet' ] = empty( $new_instance[ 'retweet' ] ) ? 'false' : 'true';
		if ( ( int ) strip_tags( $new_instance[ 'tweets_number' ] ) < 1) {
			$instance[ 'tweets_number' ] = 1;
		} else {
			$instance[ 'tweets_number' ] = ( int ) strip_tags( $new_instance[ 'tweets_number' ] );
		}
		return $instance;
	}
	
	function form( $instance ) {
		$title = esc_attr( $instance[ 'title' ] );
		$username = empty( $instance[ 'username' ] ) ? '@twitter' : esc_attr( $instance[ 'username' ] );
		$tweets_number = empty( $instance[ 'tweets_number' ] ) ? 3 : esc_attr( $instance[ 'tweets_number' ] );
		$retweet = empty( $instance[ 'retweet' ] ) ? 'true' : esc_attr( $instance[ 'retweet' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username', 'blakzr' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" type="text" value="<?php echo $username; ?>" name="<?php echo $this->get_field_name( 'username' ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tweets_number' ); ?>"><?php _e( 'Number of tweets to show', 'blakzr' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'tweets_number' ); ?>" type="text" size="3" value="<?php echo $tweets_number; ?>" name="<?php echo $this->get_field_name( 'tweets_number' ); ?>">
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'retweet' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'retweet' ); ?>" value="true"<?php echo $retweet == 'true' ? '  checked="checked"' : ''; ?> />
			<label for="<?php echo $this->get_field_id( 'retweet' ); ?>"><?php _e( 'Show retweets', 'blakzr' ); ?></label>
		</p>
	    <?php 
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("widget_show_tweets");' ) );

/******************************************/
/************* Widget: Flickr *************/
/******************************************/

class widget_flicker_photos extends WP_Widget {
	function widget_flicker_photos() {
		$widget_options = array( 'description' => __( 'Show your latest photos from Flickr', 'blakzr' ), 'classname' => 'flickr_photos' );
		parent::WP_Widget( false, $name = 'Journal - ' . __( 'Flickr Photos', 'blakzr' ), $widget_options );	
	}
	
	function widget( $args, $instance ) {		
	    extract( $args );
		$title = empty( $instance[ 'title' ] ) ? __( 'Flickr Photostream', 'blakzr' ) : apply_filters( 'widget_title', $instance[ 'title' ] );
		$user_id = empty( $instance[ 'user_id' ] ) ? '' : apply_filters( 'widget_user_id', $instance[ 'user_id' ] );
		$photos_number = empty( $instance[ 'photos_number' ] ) ? 6 : apply_filters( 'widget_photos_number', $instance[ 'photos_number' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				// Set or Get transient
				$transient_name = 'blakzr_widget_flickr' . $this->number;
				$flickr_photos = get_transient( $transient_name );
				
				if ( false === $flickr_photos ) :
					$data_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $user_id;
					$flickr_data = wp_remote_retrieve_body( wp_remote_get( $data_url ) );
					
					if ( is_wp_error( $flickr_data ) ) :
						echo 'Something went wrong! Unable to load the photos.';
					else :
						$flickr_xml_object = simplexml_load_string( $flickr_data );
						$flickr_photos = array();
						
						// Parse XML data and save them into an array
						if ( ! empty( $flickr_xml_object->entry ) ) :
							$counter = 0;
							foreach ( $flickr_xml_object->entry as $photo ):
								if ( $counter == $photos_number ) break;

								foreach ( $photo->link as $linktag ):
									if ( $linktag->attributes()->rel == 'enclosure' )
										$flickr_photos[ $counter ][ 'src' ] = (string) $linktag->attributes()->href;
								endforeach;

								if ( preg_match( '<img src="([^"]*)" [^/]*/>', ( string ) $photo->content, $content_matches ) ) {
									$baseurl = str_replace( "_m.jpg", "_s.jpg", $content_matches[ 1 ] );
									$flickr_photos[ $counter ][ 'src_small' ] = $baseurl;
								}

								$flickr_photos[ $counter ][ 'title' ] = ( string ) $photo->title;
								$counter++;
							endforeach;
						else :
							$flickr_photos[0]['title'] = 'Couldn\'t load the images';
						endif;
						
						set_transient( $transient_name, $flickr_photos, 60 * 60 * 24 );
					endif;
				endif;
				
				// Print results from array
				echo '<ul class="group">';
				foreach ( $flickr_photos as $photo ) :
					?>
					<li><a href="<?php echo $photo[ 'src' ]; ?>" rel="lightbox[flickr]" title="<?php echo $photo[ 'title' ]; ?>">
							<img width="90" height="90	" title="<?php echo $photo[ 'title' ]; ?>" alt="<?php echo $photo[ 'title' ]; ?>" src="<?php echo $photo[ 'src_small' ]; ?>">
						</a></li>
					<?php
				endforeach;
				echo '</ul>';
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update( $new_instance, $old_instance ) {				
		$instance = $old_instance;
		
		// Update transient
		$data_url = 'http://api.flickr.com/services/feeds/photos_public.gne?id=' . $new_instance[ 'user_id' ];
		$flickr_data = wp_remote_retrieve_body( wp_remote_get( $data_url ) );
		
		if ( is_wp_error( $flickr_data ) ) :
			echo 'Something went wrong! Unable to load the photos.';
		else :
			$flickr_xml_object = simplexml_load_string( $flickr_data );
			$flickr_photos = array();
			
			// Parse XML data and save them into an array
			if ( ! empty( $flickr_xml_object->entry ) ) :
				$counter = 0;
				foreach ( $flickr_xml_object->entry as $photo ):
					if ( $counter == $new_instance[ 'photos_number' ] ) break;

					foreach( $photo->link as $linktag ):
						if ( $linktag->attributes()->rel == 'enclosure' )
							$flickr_photos[ $counter ][ 'src' ] = (string) $linktag->attributes()->href;
					endforeach;

					if ( preg_match( '<img src="([^"]*)" [^/]*/>', ( string ) $photo->content, $content_matches ) ) {
						$baseurl = str_replace( "_m.jpg", "_s.jpg", $content_matches[ 1 ] );
						$flickr_photos[ $counter ][ 'src_small' ] = $baseurl;
					}

					$flickr_photos[ $counter ][ 'title' ] = ( string ) $photo->title;
					$counter++;
				endforeach;
			else :
				$flickr_photos[0]['title'] = 'Couldn\'t load the images';
			endif;
			
			set_transient( 'blakzr_widget_flickr' . $this->number, $flickr_photos, 60 * 60 * 24 );
		endif;
		
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'user_id' ] = strip_tags( $new_instance[ 'user_id' ] );
		if ( ( int ) strip_tags( $new_instance[ 'photos_number' ] ) < 1 ) {
			$instance[ 'photos_number' ] = 1;
		} elseif ( ( int ) strip_tags( $new_instance[ 'photos_number' ] ) > 20 ) {
			$instance[ 'photos_number' ] = 20;
		} else {
			$instance[ 'photos_number' ] = ( int ) strip_tags( $new_instance[ 'photos_number' ] );
		}
		return $instance;
	}
	
	function form( $instance ) {				
		$title = esc_attr( $instance[ 'title' ] );
		$user_id = empty( $instance[ 'user_id' ] ) ? '' : esc_attr( $instance[ 'user_id' ] );
		$photos_number = empty( $instance[ 'photos_number' ] ) ? 6 : esc_attr( $instance[ 'photos_number' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'user_id' ); ?>"><?php _e( 'Your Flickr User ID', 'blakzr' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'user_id' ); ?>" type="text" value="<?php echo $user_id; ?>" name="<?php echo $this->get_field_name( 'user_id' ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'photos_number' ); ?>"><?php _e( 'Number of photos to show', 'blakzr' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'photos_number' ); ?>" type="text" size="3" value="<?php echo $photos_number; ?>" name="<?php echo $this->get_field_name( 'photos_number' ); ?>">
		</p>
	    <?php 
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget("widget_flicker_photos");' ) );

/*************************/
/***** Widget: Video *****/
/*************************/

class widget_video extends WP_Widget {
	function widget_video() {
		$widget_options = array( 'description' => __( 'Display a YouTube or Vimeo video', 'blakzr' ) );
		parent::WP_Widget( false, $name = 'Journal - ' . __( 'Video', 'blakzr' ), $widget_options );	
	}
	
	function widget ( $args, $instance ) {		
	    extract( $args );
		$title = empty( $instance[ 'title' ] ) ? __( 'Featured Video', 'blakzr' ) : apply_filters( 'widget_title', $instance[ 'title' ] );
		$video_type = empty( $instance[ 'video_type' ] ) ? 'youtube' : apply_filters( 'widget_video_type', $instance['video_type'] );
		$video_url = empty( $instance[ 'video_url' ] ) ? '' : apply_filters( 'widget_video_url', $instance[ 'video_url' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				echo do_shortcode('[' . $video_type . ' width="200" height="113"]' . $video_url . '[/' . $video_type . ']');
				
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update( $new_instance, $old_instance ) {				
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'video_type' ] = strip_tags( $new_instance[ 'video_type' ] );
		$instance[ 'video_url' ] = strip_tags( $new_instance[ 'video_url' ] );
		return $instance;
	}
	
	function form( $instance ) {				
		$title = esc_attr( $instance[ 'title' ] );
		$video_type = empty( $instance[ 'video_type' ] ) ? 'youtube' : esc_attr( $instance[ 'video_type' ] );
		$video_url = empty( $instance[ 'video_url' ] ) ? '' : esc_attr( $instance[ 'video_url' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<input type="radio" name="<?php echo $this->get_field_name( 'video_type' ); ?>" value="youtube" id="<?php echo $this->get_field_id( 'video_type' ); ?>_0"<?php echo 'youtube' == $video_type ? ' checked="checked"' : ''; ?>> <label for="<?php echo $this->get_field_id( 'video_type' ); ?>_0">YouTube</label><br />
			<input type="radio" name="<?php echo $this->get_field_name( 'video_type' ); ?>" value="vimeo" id="<?php echo $this->get_field_id( 'video_type' ); ?>_1"<?php echo 'vimeo' == $video_type ? ' checked="checked"' : ''; ?>> <label for="<?php echo $this->get_field_id( 'video_type' ); ?>_1">Vimeo</label>
	    </p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'video_url' ); ?>"><?php _e( 'URL:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'video_url' ); ?>" name="<?php echo $this->get_field_name( 'video_url' ); ?>" type="text" value="<?php echo $video_url; ?>" />
	    </p>
	    <?php 
	}
}

add_action ( 'widgets_init', create_function( '', 'return register_widget ( "widget_video" );' ) );

/************************/
/***** Widget: Maps *****/
/************************/

class widget_map extends WP_Widget {
	function widget_map() {
		$widget_options = array( 'description' => __( 'Display a map from Google Maps', 'blakzr' ) );
		parent::WP_Widget( false, $name = 'Journal - ' . __( 'Map', 'blakzr' ), $widget_options );	
	}
	
	function widget ( $args, $instance ) {		
	    extract( $args );
		$title = empty( $instance[ 'title' ] ) ? __( 'Map', 'blakzr' ) : apply_filters( 'widget_title', $instance[ 'title' ] );
		$map_lat = empty( $instance[ 'map_lat' ] ) ? '51.481784' : apply_filters( 'widget_map_lat', $instance['map_lat'] );
		$map_lng = empty( $instance[ 'map_lng' ] ) ? '-0.144246' : apply_filters( 'widget_map_lng', $instance['map_lng'] );
		$map_height = empty( $instance[ 'map_height' ] ) ? '180' : apply_filters( 'widget_map_height', $instance['map_height'] );
		$map_type = empty( $instance[ 'map_type' ] ) ? 'ROADMAP' : apply_filters( 'widget_map_type', $instance['map_type'] );
		$map_zoom = empty( $instance[ 'map_zoom' ] ) ? '8' : apply_filters( 'widget_map_zoom', $instance['map_zoom'] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				echo do_shortcode('[gmap lat=' . $map_lat . ' lng=' . $map_lng . ' width="200" height="' . $map_height . '" type="' . $map_type . '" zoom="' . $map_zoom . '"]');
				
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update( $new_instance, $old_instance ) {				
		$instance = $old_instance;
		$instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
		$instance[ 'map_lat' ] = strip_tags( $new_instance[ 'map_lat' ] );
		$instance[ 'map_lng' ] = strip_tags( $new_instance[ 'map_lng' ] );
		$instance[ 'map_height' ] = strip_tags( $new_instance[ 'map_height' ] );
		$instance[ 'map_type' ] = strip_tags( $new_instance[ 'map_type' ] );
		$instance[ 'map_zoom' ] = strip_tags( $new_instance[ 'map_zoom' ] );
		return $instance;
	}
	
	function form( $instance ) {				
		$title = esc_attr( $instance[ 'title' ] );
		$map_lat = empty( $instance[ 'map_lat' ] ) ? '51.481784' : esc_attr( $instance['map_lat'] );
		$map_lng = empty( $instance[ 'map_lng' ] ) ? '-0.144246' : esc_attr( $instance['map_lng'] );
		$map_height = empty( $instance[ 'map_height' ] ) ? '180' : esc_attr( $instance['map_height'] );
		$map_type = empty( $instance[ 'map_type' ] ) ? 'ROADMAP' : esc_attr( $instance['map_type'] );
		$map_zoom = empty( $instance[ 'map_zoom' ] ) ? '8' : esc_attr( $instance['map_zoom'] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'map_lat' ); ?>"><?php _e( 'Latitude:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'map_lat' ); ?>" name="<?php echo $this->get_field_name( 'map_lat' ); ?>" type="text" value="<?php echo $map_lat; ?>" />
	    </p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'map_lng' ); ?>"><?php _e( 'Longitude:', 'blakzr' ); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id( 'map_lng' ); ?>" name="<?php echo $this->get_field_name( 'map_lng' ); ?>" type="text" value="<?php echo $map_lng; ?>" />
	    </p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'map_type' ); ?>"><?php _e( 'Map Type:', 'blakzr' ); ?></label> 
	    	<select name="<?php echo $this->get_field_name( 'map_type' ); ?>" id="<?php echo $this->get_field_id( 'map_type' ); ?>">
				<?php
				
				$map_types = array('Roadmap', 'Satellite', 'Hybrid', 'Terrain');
				
				foreach ( $map_types as $type ) :
				?>
				<option value="<?php echo $type; ?>"<?php echo $map_type == $type ? ' selected="selected"' : '' ?>><?php echo $type; ?></option>
				<?php
				endforeach;
				
				?>
			</select>
	    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'map_zoom' ); ?>"><?php _e( 'Zoom:', 'blakzr' ); ?></label> 
	    	<select name="<?php echo $this->get_field_name( 'map_zoom' ); ?>" id="<?php echo $this->get_field_id( 'map_zoom' ); ?>">
				<?php
				
				for ( $i = 1; $i < 19 ; $i++ ) { 
					?>
				<option value="<?php echo $i; ?>"<?php echo $map_zoom == $i ? ' selected="selected"' : '' ?>><?php echo $i; ?></option>	
					<?php
				}
				
				?>
			</select>
		</p>
		<p>
	    	<label for="<?php echo $this->get_field_id( 'map_height' ); ?>"><?php _e( 'Map Height:', 'blakzr' ); ?></label> 
	    	<input id="<?php echo $this->get_field_id( 'map_height' ); ?>" name="<?php echo $this->get_field_name( 'map_height' ); ?>" type="text" value="<?php echo $map_height; ?>" style="width:50px;" /> pixels
	    </p>
	    <?php 
	}
}

add_action ( 'widgets_init', create_function( '', 'return register_widget ( "widget_map" );' ) );


/*********************************************/
/*********** Widget: Social Links ************/
/*********************************************/

class widget_social_links extends WP_Widget {
	
	function widget_social_links() {
		$widget_options = array('description' => __('Display your social networks. Set them on the \'Social Links\' tab in the Theme Options page', 'blakzr'));
		parent::WP_Widget(false, $name = 'Journal - ' . __('Social Links', 'blakzr'), $widget_options);	
	}
	
	function widget($args, $instance) {		
	    extract($args);
		$title = empty($instance['title']) ? __('Follow us on', 'blakzr') : apply_filters('widget_title', $instance['title']);
		$description = empty($instance['description']) ? '' : apply_filters('widget_description', $instance['description']);
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				if ( '' != $description ) :
					echo '<p>' . $description . '</p>';
				endif;
				
				?>
				<ul class="buttons-wrapper group">
				<?php
				$social_links = blakzr_social_links();
				foreach ( $social_links as $id => $social ) :
				?>
					<li id="<?php echo $id; ?>"><a href="<?php echo $social[1]; ?>" target="_blank"><?php echo $social[0]; ?></a></li>
				<?php
				endforeach;
				?>
					<?php if ( 'true' == get_option( 'blakzr_social_rss', 'true' ) ) : ?>
						<?php if ( '' != get_option( 'blakzr_rss_url', '' ) ) : ?>
					<li id="rss"><a href="<?php echo get_option( 'blakzr_rss_url' ); ?>" ><?php _e( 'RSS', 'blakzr' ); ?></a></li>
						<?php else : ?>
					<li id="rss"><a href="<?php bloginfo( 'rss2_url' ); ?>"><?php _e( 'RSS', 'blakzr' ); ?></a></li>
						<?php endif; ?>
					<?php endif; ?>
				</ul>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['description'] = strip_tags($new_instance['description']);
		return $instance;
	}
	
	function form($instance) {				
		$title = esc_attr($instance['title']);
		$description = empty($instance['description']) ? '' : esc_attr($instance['description']);
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blakzr'); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description:', 'blakzr'); ?></label>
			<textarea name="<?php echo $this->get_field_name('description'); ?>" id="<?php echo $this->get_field_id('description'); ?>" class="widefat" cols="20" rows="5"><?php echo $description; ?></textarea>
		</p>
	    <?php 
	}
}

add_action('widgets_init', create_function('', 'return register_widget("widget_social_links");'));

/*********************************************/
/*********** Widget: Latest Video ************/
/*********************************************/

class widget_latest_video extends WP_Widget {
	
	function widget_latest_video() {
		$widget_options = array('description' => __('Display the latest post that features a video (video format)', 'blakzr'));
		parent::WP_Widget(false, $name = 'Journal - ' . __('Latest Video', 'blakzr'), $widget_options);	
	}
	
	function widget($args, $instance) {		
	    extract($args);
		$title = empty($instance['title']) ? __('Latest Video', 'blakzr') : apply_filters('widget_title', $instance['title']);
		$description = empty($instance['description']) ? '' : apply_filters('widget_description', $instance['description']);
		$display_title = empty( $instance[ 'display_title' ] ) ? 'false' : esc_attr( $instance[ 'display_title' ] );
		$display_excerpt = empty( $instance[ 'display_excerpt' ] ) ? 'false' : esc_attr( $instance[ 'display_excerpt' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				$args = array(
					'posts_per_page' 		=> 1,
					'post_type'	 			=> 'post',
					'post_status' 			=> 'publish',
					'tax_query' 			=> array(
					    						array(
						        					'taxonomy'  => 'post_format',
						        					'field' 	=> 'slug',
						        					'terms' 	=> array( 'post-format-video')
						    						)
												)
				);
				
				$show_last_video = new WP_Query( $args );
				
				if ( $show_last_video->have_posts() ) :
					while ( $show_last_video->have_posts() ) : $show_last_video->the_post();
				?>
				<div class="video-container">
					<?php
					$custom = get_post_custom( get_the_ID() );
					$custom_video_type = $custom['post_format_video_type'][0];
					$custom_video = $custom['post_format_video'][0];
            	
					if ( ! empty( $custom_video ) ) :
            	
						switch ( $custom_video_type ) :
							case '0':
								echo do_shortcode('[youtube]' . $custom_video . '[/youtube]');
								break;
							case '1':
								echo do_shortcode('[vimeo]' . $custom_video . '[/vimeo]');
								break;
							case '2':
								echo do_shortcode('[video]' . $custom_video . '[/video]');
								break;
							default:
								# Don't do nothing, please.
								break;
						endswitch;
            	
					endif;
					?>
				</div>
				<?php if ( 'true' == $display_title ) : ?>
				<h3 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="entry-meta">
					<a href="<?php the_permalink(); ?>" class="date-link"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . __( ' ago', 'blakzr' ); ?></a>
				</div>
				<?php endif; ?>
				<?php if ( 'true' == $display_excerpt ) : ?>
				<p class="entry-excerpt"><?php echo trim( substr( get_the_excerpt(), 0, 280 ) ); ?>... <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Read more', 'blakzr' ); ?></a></p>
				<?php endif; ?>
				<?php
					endwhile;
					wp_reset_postdata();
				endif;
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance[ 'display_title' ] = empty( $new_instance[ 'display_title' ] ) ? 'false' : 'true';
		$instance[ 'display_excerpt' ] = empty( $new_instance[ 'display_excerpt' ] ) ? 'false' : 'true';
		return $instance;
	}
	
	function form($instance) {				
		$title = esc_attr($instance['title']);
		$display_title = empty( $instance[ 'display_title' ] ) ? 'true' : esc_attr( $instance[ 'display_title' ] );
		$display_excerpt = empty( $instance[ 'display_excerpt' ] ) ? 'true' : esc_attr( $instance[ 'display_excerpt' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blakzr'); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<input id="<?php echo $this->get_field_id( 'display_title' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_title' ); ?>" value="true"<?php echo $display_title == 'true' ? '  checked="checked"' : ''; ?> />
			<label for="<?php echo $this->get_field_id( 'display_title' ); ?>"><?php _e( 'Show Title', 'blakzr' ); ?></label>
		</p>
		<p>
			<input id="<?php echo $this->get_field_id( 'display_excerpt' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_excerpt' ); ?>" value="true"<?php echo $display_excerpt == 'true' ? '  checked="checked"' : ''; ?> />
			<label for="<?php echo $this->get_field_id( 'display_excerpt' ); ?>"><?php _e( 'Show Excerpt', 'blakzr' ); ?></label>
		</p>
	    <?php 
	}
}

add_action('widgets_init', create_function('', 'return register_widget("widget_latest_video");'));

/*********************************************/
/*********** Widget: Post Gallery ************/
/*********************************************/

class widget_post_gallery extends WP_Widget {
	
	function widget_post_gallery() {
		$widget_options = array('description' => __('Show the latest post that feature an image or gallery (based on post formats)', 'blakzr'));
		parent::WP_Widget(false, $name = 'Journal - ' . __('Post Gallery', 'blakzr'), $widget_options);	
	}
	
	function widget($args, $instance) {		
	    extract($args);
		$title = empty($instance['title']) ? __('Post Gallery', 'blakzr') : apply_filters('widget_title', $instance['title']);
		$posts_number = empty( $instance[ 'posts_number' ] ) ? 10 : apply_filters( 'widget_posts_number', $instance[ 'posts_number' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				?>
				<div class="entry-thumbnail post-image-gallery flexslider group">
					<ul class="slides">
						<?php
						$args = array(
							'posts_per_page' 		=> $posts_number,
							'post_type'	 			=> 'post',
							'post_status' 			=> 'publish',
							'tax_query' 			=> array(
														'relation'	=> 'OR',
							    						array(
								        					'taxonomy'  => 'post_format',
								        					'field' 	=> 'slug',
								        					'terms' 	=> array( 'post-format-image')
								    						),
														array(
									        				'taxonomy'  => 'post_format',
									        				'field' 	=> 'slug',
									        				'terms' 	=> array( 'post-format-gallery')
									    					)
														)
						);

						$show_latest_images = new WP_Query( $args );

						if ( $show_latest_images->have_posts() ) :
							while ( $show_latest_images->have_posts() ) : $show_latest_images->the_post();
						?>
						<li>
							<figure>
								<a href="<?php the_permalink(); ?>">
									<?php 
									if ( has_post_thumbnail() ) :
										the_post_thumbnail( 'medium-thumb' );
									?>
									<?php endif; ?>
								</a>
								<figcaption>
									<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<p><?php echo trim( substr( get_the_excerpt(), 0, 50 ) ); ?></p>
								</figcaption>
							</figure>
						</li>
						<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</ul>
					<ul class="gallery-controls">
						<?php
						$args = array(
							'posts_per_page' 		=> $posts_number,
							'post_type'	 			=> 'post',
							'post_status' 			=> 'publish',
							'tax_query' 			=> array(
														'relation'	=> 'OR',
							    						array(
								        					'taxonomy'  => 'post_format',
								        					'field' 	=> 'slug',
								        					'terms' 	=> array( 'post-format-image')
								    						),
														array(
									        				'taxonomy'  => 'post_format',
									        				'field' 	=> 'slug',
									        				'terms' 	=> array( 'post-format-gallery')
									    					)
														)
						);

						$show_latest_images = new WP_Query( $args );

						if ( $show_latest_images->have_posts() ) :
							while ( $show_latest_images->have_posts() ) : $show_latest_images->the_post();
						?>
						<li>
							<a href="#">
								<?php 
								if ( has_post_thumbnail() ) :
									the_post_thumbnail( 'thumbnail' );
								?>
								<?php endif; ?>
							</a>
						</li>
						<?php
							endwhile;
							wp_reset_postdata();
						endif;
						?>
					</ul>
				</div>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( ( int ) strip_tags( $new_instance[ 'posts_number' ] ) < 1) {
			$instance[ 'posts_number' ] = 1;
		} else {
			$instance[ 'posts_number' ] = ( int ) strip_tags( $new_instance[ 'posts_number' ] );
		}
		return $instance;
	}
	
	function form($instance) {				
		$title = esc_attr($instance['title']);
		$posts_number = empty( $instance[ 'posts_number' ] ) ? 10 : esc_attr( $instance[ 'posts_number' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blakzr'); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'posts_number' ); ?>"><?php _e( 'Number of posts to show', 'blakzr' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'posts_number' ); ?>" type="text" size="3" value="<?php echo $posts_number; ?>" name="<?php echo $this->get_field_name( 'posts_number' ); ?>">
		</p>
	    <?php 
	}
}

add_action('widgets_init', create_function('', 'return register_widget("widget_post_gallery");'));


/*********************************************/
/************ Widget: Columnists *************/
/*********************************************/

class widget_columnists extends WP_Widget {
	
	function widget_columnists() {
		$widget_options = array('description' => __('Show a list of columnists (\'Author\' user role) and their latest post', 'blakzr'));
		parent::WP_Widget(false, $name = 'Journal - ' . __('Columnists', 'blakzr'), $widget_options);	
	}
	
	function widget($args, $instance) {		
	    extract($args);
		$title = empty( $instance['title']) ? __('Columnists', 'blakzr') : apply_filters('widget_title', $instance['title']);
		$display_excerpt = empty( $instance[ 'display_excerpt' ] ) ? 'false' : esc_attr( $instance[ 'display_excerpt' ] );
	    ?>
	    	<?php echo $before_widget; ?>
				<?php
				echo $before_title . $title . $after_title;
				
				$user_args = array(
					'role'		=> 'author',
					'orderby'	=> 'display_name'
				);
				
				$columnists = get_users( $user_args );
				foreach ( $columnists as $columnist ) :
					$author_link = get_author_posts_url( $columnist->ID  )
				?>
				<div class="entry">
					<a href="<?php echo $author_link ?>" class="author-link">
						<?php echo get_avatar( $columnist->ID, 20 ); ?>
						<?php echo $columnist->display_name; ?>
					</a>
						<?php
						$args = array(
							'posts_per_page' 	=> 1,
							'order'				=> 'DESC',
							'orderby' 			=> 'date',
							'post_type' 		=> array ( 'post', 'page' ),
							'post_status' 		=> 'publish',
							'author'			=> $columnist->ID
						);

						$show_last_post = new WP_Query( $args );

						if ( $show_last_post->have_posts() ) :
							$show_last_post->the_post();
						?>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<?php if ( 'true' == $display_excerpt ) : ?>
					<p><?php echo trim( substr( get_the_excerpt(), 0, 150 ) ); ?>...</p>
					<?php endif; ?>
						<?php
							wp_reset_postdata();
						endif;
						?>
				</div>
				<?php
				endforeach;
				?>
			<?php echo $after_widget; ?>
	    <?php
	}
	
	function update($new_instance, $old_instance) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance[ 'display_excerpt' ] = empty( $new_instance[ 'display_excerpt' ] ) ? 'false' : 'true';
		return $instance;
	}
	
	function form($instance) {				
		$title = esc_attr($instance['title']);
		$display_excerpt = empty( $instance[ 'display_excerpt' ] ) ? 'true' : esc_attr( $instance[ 'display_excerpt' ] );
	    ?>
	    <p>
	    	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'blakzr'); ?></label> 
	    	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	    </p>
		<p>
			<input id="<?php echo $this->get_field_id( 'display_excerpt' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'display_excerpt' ); ?>" value="true"<?php echo $display_excerpt == 'true' ? '  checked="checked"' : ''; ?> />
			<label for="<?php echo $this->get_field_id( 'display_excerpt' ); ?>"><?php _e( 'Show Excerpt', 'blakzr' ); ?></label>
		</p>
	    <?php 
	}
}

add_action('widgets_init', create_function('', 'return register_widget("widget_columnists");'));


?>