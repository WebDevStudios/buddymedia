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
 * bp_media_is_action_edit function.
 * 
 * @access public
 * @return void
 */
function bp_media_is_action_edit() {

	$variables = bp_action_variables();
	
	if( isset( $variables[1] ) && 'edit' ===  $variables[1] ) {
		return true;
	}
	
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
		'posts_per_page' => 12,
		'orderby' => 'modified'
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
 * bp_media_edit_image_link function.
 * 
 * @access public
 * @return void
 */
function bp_media_edit_image_link( $id = null ) {

	if( !$id ) {
		$action_var = bp_action_variables();
		$id = $action_var[0];
	}
	echo bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $id . '/edit';
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
 * bp_get_media_image_id function.
 * 
 * @access public
 * @return void
 */
function bp_get_media_image_id() {

	$action_var = bp_action_variables();
	$photo_id = $action_var[0];
	
	if ( FALSE === get_post_status( $photo_id ) ) {
	  $photo_id = FALSE;
	} 
	
	return $photo_id;
}


/**
 * bp_get_media_album_id function.
 * 
 * @access public
 * @return void
 */
function bp_get_media_album_id() {

	$action_var = bp_action_variables();
	$album_id = $action_var[0];
	
	if ( FALSE === get_post_status( $album_id ) ) {
	  $album_id = FALSE;
	} 
		
	return $album_id;
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
 * bp_media_image_description function.
 * 
 * @access public
 * @return void
 */
function bp_media_image_description() {
	echo bp_media_get_image_description();
}

	/**
	 * bp_media_get_image_description function.
	 * 
	 * @access public
	 * @return void
	 */
	function bp_media_get_image_description() {
		
		$action_var = bp_action_variables();	
		$meta = get_post_meta( $action_var[0], 'description', true );
		
		if( $meta ) return esc_html( $meta );
		
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
 * bp_media_image_back_url function.
 * 
 * @access public
 * @return void
 */
function bp_media_image_back_url() {
	$action_var = bp_action_variables();
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $action_var[0];
	
}


/**
 * bp_media_image_link function.
 * 
 * @access public
 * @param mixed $id
 * @return void
 */
function bp_media_image_link( $id ) {
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $id;
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


/**
 * bp_media_can_edit function.
 * 
 * @access public
 * @return void
 */
function bp_media_can_edit() {
	if( is_user_logged_in() && bp_loggedin_user_id() === bp_displayed_user_id() || is_super_admin() || is_admin() ) return true;
}



/**
 * bp_media_add_activity_meta function.
 * 
 * @access public
 * @param mixed $activity
 * @return void
 */
function bp_media_add_activity_meta( $activity ) {

	 if ( ! empty( $_POST['attachment_id'] ) ) {
          bp_activity_update_meta( $activity->id, 'bp_media_attachment_id', $_POST['attachment_id'] );
          update_post_meta( $_POST['attachment_id'], 'description', $_POST['content'] );
          update_post_meta( $_POST['attachment_id'], 'activity_id', $activity->id );
     }	
}
add_action( 'bp_activity_after_save', 'bp_media_add_activity_meta' );



/**
 * bp_media_display_attachment_image function.
 * 
 * @access public
 * @return void
 */
function bp_media_display_attachment_image() {
	
	if( $attachment_id = bp_activity_get_meta( bp_get_activity_id(), 'bp_media_attachment_id', true ) ) {
	
		/**
		 * filter activity attachment media size.
		 *
		 * @since 1.0.0
		 *
		 * @param string media size
		*/
		$attachment_size = apply_filters( 'bp_media_display_attachment_size', 'medium' );
		
		$attachment_src = wp_get_attachment_image_src( $attachment_id, $attachment_size );	
		$attachment_url = bp_core_get_user_domain( bp_get_activity_user_id() ) . BP_MEDIA_SLUG . '/image/' . $attachment_id;
		
		/**
		 * filter activity attachment markup and parameters.
		 *
		 * @since 1.0.0
		 *
		 * @param string $attachment_url link to attachment %1$s.
		 * @param string $attachment_src src of attachment %2$s.
		*/
		$attachment_html_string = apply_filters( 'bp_media_attachment_html', '<div class="bp-media-activity-attachment"><a href="%1$s"><img src="%2$s"></a></div>', $attachment_url, $attachment_src[0] );
		
		$attachment = sprintf( $attachment_html_string, $attachment_url, $attachment_src[0] );
		
		echo $attachment;
	}
	
}
add_action( 'bp_activity_entry_content', 'bp_media_display_attachment_image' );


/**
 * bp_media_delete_attachments_before_delete_post function.
 *
 * this is album clean up, deletes attachents/images when album is deleted
 * 
 * @access public
 * @param mixed $id
 * @return void
 */
function bp_media_delete_attachments_before_delete_post( $id ){
	global $post;
	
	if( 'bp_media' !== $post->post_type ) return;
	
	$subposts = get_children(array( 
	    'post_parent' => $id,
	    'post_type'   => 'any', 
	    'numberposts' => -1,
	    'post_status' => 'any'
	));
	
	if ( is_array( $subposts ) && count( $subposts ) > 0 ){
		$uploadpath = wp_upload_dir();
	 	
		foreach( $subposts as $subpost ){
			
			$_wp_attached_file = get_post_meta( $subpost->ID, '_wp_attached_file', true );
			
			$original = basename( $_wp_attached_file );
			$pos = strpos( strrev( $original ), '.' );
			if (strpos( $original, '.' ) !== false ){
				$ext = explode( '.', strrev( $original ) );
				$ext = strrev( $ext[0] );
			} else {
				$ext = explode( '-', strrev( $original ) );
				$ext = strrev( $ext[0] );
			}
			
			$pattern = $uploadpath['basedir'].'/'.dirname( $_wp_attached_file ).'/'.basename( $original, '.'.$ext ).'-[0-9]*x[0-9]*.'.$ext;
			$original= $uploadpath['basedir'].'/'.dirname( $_wp_attached_file ).'/'.basename( $original, '.'.$ext ).'.'.$ext;
			if ( getimagesize( $original ) ){
				$thumbs = glob( $pattern );
				if ( is_array( $thumbs ) && count( $thumbs ) > 0 ){
					foreach( $thumbs as $thumb )
						unlink( $thumb );
				}
			}
			wp_delete_attachment( $subpost->ID, true );
		}
	}
}
// till wp 3.1
add_action( 'delete_post', 'bp_media_delete_attachments_before_delete_post' );
// from wp 3.2
add_action( 'before_delete_post', 'bp_media_delete_attachments_before_delete_post' );



/**
 * bp_media_filter_album_attachments function.
 * 
 * @access public
 * @param mixed $args
 * @param mixed $type
 * @param mixed $post
 * @return void
 */
function bp_media_filter_album_attachments( $args, $type, $post ) {
	
	if( 'bp_media' === $post->post_type ) {
		
		$args['orderby'] = 'date';
		$args['order'] = 'desc';
		
	}
	return $args;
}
add_filter( 'get_attached_media_args', 'bp_media_filter_album_attachments', 10, 3 );




/**
 * bp_media_user_can_delete function.
 * 
 * @access public
 * @param mixed $user_id
 * @return boolean
 */
function bp_media_user_can_delete( $user_id = 0 ) {

	if( 0 === $user_id  ) {
		$user_id = bp_displayed_user_id();
	}
	
	if( bp_loggedin_user_id() === (int) $user_id ) {
		return true;
	}
	return;
}


/**
 * bp_media_user_can_access function.
 * 
 * @access public
 * @param int $user_id (default: 0)
 * @return boolean
 */
function bp_media_user_can_access( $user_id = 0 ) {

	if( 0 === $user_id  ) {
		$user_id = bp_displayed_user_id();
	}
	
	if( ! apply_filters( 'bp_media_user_can_access', $user_id ) ) return;

	if( bp_loggedin_user_id() === (int) $user_id ) {
		return true;
	}
	return;
}