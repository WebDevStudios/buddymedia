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
	
	$query = array(
		'post_type' => 'bp_media'
	);
	
	apply_filters( 'bp_media_loop_filter', $query );
	
	return $query;
}



/**
 * bp_media_loop_profile_filter function.
 * 
 * @access public
 * @param mixed $query
 * @return void
 */
function bp_media_loop_profile_filter( $query ) {

	if( bp_is_user() ) { 

		$author = bp_displayed_user_id();
		
		$query = array(
			'post_type' => 'bp_media',
			'author' => $author
		);
	
	}

	return $query;
}
add_filter( 'bp_media_loop_filter', 'bp_media_loop_profile_filter' );


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


// include js
function bp_media_enqueue_scripts() {
	 wp_enqueue_script('plupload-all');
}
add_action( 'wp_enqueue_scripts', 'bp_media_enqueue_scripts' );




add_action('wp_ajax_photo_gallery_upload', function(){

  check_ajax_referer('photo-upload');

  // you can use WP's wp_handle_upload() function:
  $file = $_FILES['async-upload'];
  $status = wp_handle_upload( $file, array('test_form'=>true, 'action' => 'photo_gallery_upload') );

  // and output the results or something...
  echo 'Uploaded to: '. $status['file'];
  
  $wp_upload_dir = wp_upload_dir();

  //Adds file as attachment to WordPress
  
  $attachment = array(
  		'guid'           => $wp_upload_dir['url'] . '/' . basename( $status['file'] ), 
     'post_mime_type' => $status['type'],
     'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $status['file'] ) ),
     'post_content' => '',
     'post_status' => 'inherit'
  );
  
  $attach_id = wp_insert_attachment( $attachment, $status['file'], (int) $_POST['gallery_id'] );
  
  // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
  require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $status['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	
	echo "\n Attachment ID: " . $attach_id;

  exit;
});