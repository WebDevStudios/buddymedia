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


function bp_media_loop_filter() {
	
	$query = 'post_type=bp_media';
	
	return $query;
}


function bp_media_css_class() {
	echo bp_get_media_css_class();
}


function bp_get_media_css_class() {
	return 'bp-media';
}


function bp_media_id() {
	global $post;
	
	echo $post->ID;
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