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
	
	
function bp_media_album_back_url() {

	$action_var = bp_action_variables();
	
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/album/' . $action_var[0];
	
}



function bp_media_album_id() {
	echo bp_media_get_album_id();	
}

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
function bp_media_comments($comment, $args, $depth) {

	$GLOBALS['comment'] = $comment;
?>
	
	
	<li id="div-comment-<?php comment_ID() ?>" class="comment-body">

		<div class="comment-author vcard">
		<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<div class="comment-author-username"><?php printf( __( '%s' ), get_comment_author_link() ); ?></div>
			<?php comment_text(); ?>
		</div>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
			<br />
		<?php endif; ?>
	
		

	</li>
<?php
}


	$time = current_time('mysql');
	
	$data = array(
	    'comment_post_ID' => 49,
	    'comment_author' => 'admin',
	    'comment_author_email' => 'admin@admin.com',
	    'comment_author_url' => 'http://',
	    'comment_content' => 'content here two',
	    'comment_type' => '',
	    'comment_parent' => 0,
	    'user_id' => 1,
	    'comment_author_IP' => '127.0.0.1',
	    'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
	    'comment_date' => $time,
	    'comment_approved' => 1,
	);
	
	//wp_insert_comment($data);