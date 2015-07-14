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
		'post_type' => 'bp_media',
		'posts_per_page' => 12
	);
	
	$query = apply_filters( 'bp_media_loop_filter', $query );
	
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
		echo bp_media_get_userlink();

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
 * bp_media_create_album_link function.
 * 
 * @access public
 * @return void
 */
function bp_media_create_album_link() {
	echo bp_media_get_create_album_link();
}	

	
	/**
	 * bp_media_get_create_album_link function.
	 * 
	 * @access public
	 * @return void
	 */
	function bp_media_get_create_album_link() {
		return bp_media_userlink( bp_displayed_user_id() ) . 'create';
	}
	
	
/**
 * bp_media_edit_album_link function.
 * 
 * @access public
 * @return void
 */
function bp_media_edit_album_link() {
	echo bp_media_get_edit_album_link();
}	

	
	/**
	 * bp_media_get_edit_album_link function.
	 * 
	 * @access public
	 * @return void
	 */
	function bp_media_get_edit_album_link() {
		return bp_media_userlink( bp_displayed_user_id() ) . 'edit';
	}
	
	
	
/**
 * bp_media_create_album_link_ajax function.
 * 
 * @access public
 * @return void
 */
function bp_media_create_album_link_ajax() {

	$ajax_url = add_query_arg( 
	    array( 
	        'action' => 'bp_media_add_album' 
	    ), 
	    '/wp-admin/admin-ajax.php'
	); 

	return $ajax_url;
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
		echo bp_get_media_album_link();	
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
 * bp_media_time_since function.
 * 
 * @access public
 * @param mixed $photo_id
 * @return void
 */
function bp_media_time_since( $photo_id ) {

	$attachment = get_post( $photo_id );
	$post_date = sprintf( __( '%1$s ago', 'bp_media' ), human_time_diff( strtotime( $attachment->post_date ), current_time('timestamp') ) );
	
	return $post_date;
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
		'order'          => 'asc',
		'orderby'        => 'date',
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


/**
 * bp_album_image_count function.
 * 
 * @access public
 * @return void
 */
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
	
	$attachments = get_posts( $args );
	
	$count = 0;
	foreach ( $attachments as $attachment ) {
	    $count += count( $attachment );
	}
	
	$text = ( $count === 1 ) ? __( ' image', 'bp_media' ) : __( ' images', 'bp_media' ) ;
	
	echo $count . $text;
	
}



/**
 * bp_media_album_field function.
 * 
 * @access public
 * @param mixed $field (default: null)
 * @return void
 */
function bp_media_album_field( $field = null ) {
	echo bp_media_get_album_field( $field );
}
	/**
	 * bp_media_get_album_field function.
	 * 
	 * @access public
	 * @param mixed $field (default: null)
	 * @return void
	 */
	function bp_media_get_album_field( $field = null ) {
	
		if( ! $field ) return;
		
		$action_var = bp_action_variables();
		
		$album = get_post( $action_var[0] );
		
		switch ( $field ) {
			case 'title': 
				return esc_html( $album->post_title );
			break;
			case 'description': 
				return esc_html( $album->post_content );
			break;
			
		}
		
		return;	
	}
	
	
/**
 * bp_media_album_back_url function.
 * 
 * @access public
 * @return void
 */
function bp_media_album_back_url() {

	$action_var = bp_action_variables();
	
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/album/' . $action_var[0];
	
}



/**
 * bp_media_album_id function.
 * 
 * @access public
 * @return void
 */
function bp_media_album_id() {
	echo bp_media_get_album_id();	
}


	/**
	 * bp_media_get_album_id function.
	 * 
	 * @access public
	 * @return void
	 */
	function bp_media_get_album_id() {
	
		$action_var = bp_action_variables();
		
		return $action_var[0];
	}



/**
 * bp_media_enqueue_scripts function.
 * 
 * @access public
 * @return void
 */
function bp_media_enqueue_scripts() {
	 wp_enqueue_script('plupload-all');
}
add_action( 'wp_enqueue_scripts', 'bp_media_enqueue_scripts' );



/**
 * bp_media_comments function.
 * 
 * @access public
 * @param mixed $comment
 * @param mixed $args
 * @param mixed $depth
 * @return void
 */
function bp_media_comments( $comment ) {
	
	bp_media_get_template_part('comments' );

}