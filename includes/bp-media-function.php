<?php


/**
 * bp_media_is_option function.
 * 
 * @access public
 * @param mixed $option
 * @return boolean
 */
function bp_media_is_option( $option ) {

	$option = 'bp-media-' . $option;

	$option = bp_get_option( $option );
	
	if( $option ) 
		return true;
	
	
	return;
}


/**
 * bp_media_loop_filter function.
 * 
 * @access public
 * @return void
 */
function bp_media_loop_filter() {

	$author = bp_displayed_user_id();
	
	$query = array(
		'post_type' => 'bp_media',
		'author' => $author
	);
	
	apply_filters( 'bp_media_loop_filter', $query );
	
	return $query;
}


/**
 * bp_media_css_class function.
 * 
 * @access public
 * @return void
 */
function bp_media_css_class() {
	echo bp_get_media_css_class();
}
	/**
	 * bp_get_media_css_class function.
	 * 
	 * @access public
	 * @return void
	 */
	function bp_get_media_css_class() {
		return 'bp-media';
	}


/**
 * bp_media_css_id function.
 * 
 * @access public
 * @return void
 */
function bp_media_css_id() {
	echo bp_get_media_css_id();
}
	/**
	 * bp_get_media_css_id function.
	 * 
	 * @access public
	 * @return post id
	 */
	function bp_get_media_css_id() {
		global $post;
		
		return $post->ID;
	}


/**
 * bp_media_userlink function.
 * 
 * @access public
 * @param mixed $user_id (default: null)
 * @return void
 */
function bp_media_userlink() {
	global $post;
	
	if( $post->post_author )
		echo bp_media_get_userlink( $post->post_author );
	
	return; 
}

	/**
	 * bp_media_get_userlink function.
	 * 
	 * @access public
	 * @param mixed $user_id (default: null)
	 * @return void
	 */
	function bp_media_get_userlink() {
		global  $post;
		
		if( $post->post_author )
			return bp_core_get_user_domain( $post->post_author ) . BP_MEDIA_SLUG;
	
		return; 
	}
	
function bp_media_add_album_link() {
	
}


/**
 * bp_media_album_link function.
 * 
 * @access public
 * @param mixed $user_id
 * @param mixed $post_id
 * @return void
 */
function bp_media_album_link() {
	global $post;
		
	if( $post->ID )
		echo bp_get_media_album_link( $post->post_author, $post->ID );
		
	return;
	
}

	/**
	 * bp_get_media_album_link function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @param mixed $post_id
	 * @return album link
	 */
	function bp_get_media_album_link() {
		global $post;
		
		if( $post->ID )
			return bp_media_userlink( $post->post_author ) . '/album/' . $post->ID;
			
		return;
		
	}
	

	
/**
 * bp_album_cover_url function.
 * 
 * @access public
 * @return void
 */
function bp_album_cover_url() {
	global $post;
	
	$args = array(
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'post_type'      => 'attachment',
		'post_parent'    => $post->ID,
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => 1,
	);
	
	$attachments = get_posts($args);
	
  	if ( $attachments ) {
		foreach ($attachments as $attachment) {
			$cover =  wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
			echo $cover[0];
		}
	} else {
		echo BP_MEDIA_PLUGIN_URL . 'includes/images/no-image.png';
	}
	
}


function bp_album_image_count() {
	global $post;
	
	$args = array(
		'order'          => 'ASC',
		'orderby'        => 'menu_order',
		'post_type'      => 'attachment',
		'post_parent'    => $post->ID,
		'post_mime_type' => 'image',
		'post_status'    => null,
		'numberposts'    => -1,
	);
	
	$attachments = get_posts($args);
	
	$count = 0;
	foreach ( $attachments as $attachment ) {
	    $count += count( $attachment );
	}
	
	echo $count;
	
}