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
		return bp_core_get_user_domain() . 'create';
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
 * bp_media_upload_photo function.
 * 
 * @access public
 * @return void
 */
function bp_media_upload_photo() {

	check_ajax_referer('photo-upload');
	
	// upload file
	$file = $_FILES['async-upload'];
	$status = wp_handle_upload( $file, array( 'test_form' => true, 'action' => 'photo_gallery_upload' ) );
	
	// output the results to console
	echo 'Uploaded to: ' . $status['file'];
	
	$wp_upload_dir = wp_upload_dir();
	
	//Adds file as attachment to WordPress
	$attachment = array(
		'guid'           	=> $wp_upload_dir['url'] . '/' . basename( $status['file'] ), 
		'post_mime_type' 	=> $status['type'],
		'post_title' 		=> preg_replace( '/\.[^.]+$/', '', basename( $status['file'] ) ),
		'post_content' 		=> '',
		'post_status' 		=> 'inherit'
	);
	$attach_id = wp_insert_attachment( $attachment, $status['file'], (int) $_POST['gallery_id'] );
	
	// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	
	// Generate the metadata for the attachment, and update the database record.
	$attach_data = wp_generate_attachment_metadata( $attach_id, $status['file'] );
	wp_update_attachment_metadata( $attach_id, $attach_data );
	
	// output the results to console
	echo "\n Attachment ID: " . $attach_id;
	
	exit;
}
add_action('wp_ajax_photo_gallery_upload', 'bp_media_upload_photo' );


/**
 * bp_media_get_image function.
 * 
 * @access public
 * @return void
 */
function bp_media_get_image(){
	
	$photo_id =  $_GET['id'];
	$guid =  $_GET['guid'];
	$user_id =  $_GET['user'];
	
	$user = get_user_by( 'id', (int) $user_id );
	
	include( bp_media_get_template_part( 'single/photo') );
	
	die();
}
add_action('wp_ajax_bp_media_get_image', 'bp_media_get_image');
add_action('wp_ajax_nopriv_bp_media_get_image', 'bp_media_get_image');



/**
 * bp_media_add_album function.
 * 
 * @access public
 * @return void
 */
function bp_media_add_album(){
	
	include_once( bp_media_get_template_part( 'single/add-album') );
	
	die();
}
add_action('wp_ajax_bp_media_add_album', 'bp_media_add_album');
add_action('wp_ajax_nopriv_bp_media_add_album', 'bp_media_add_album');



/**
 * bp_media_ajax_create_album function.
 * 
 * @access public
 * @return void
 */
function bp_media_ajax_create_album(){

	$title =  $_GET['title'];
	$content =  $_GET['description'];
	$user_id =  $_GET['user_id'];

	// Create post object
	$my_post = array(
	  'post_title'    => sanitize_text_field( $title ),
	  'post_content'  => sanitize_text_field( $content ),
	  'post_status'   => 'publish',
	  'post_author'   => (int) $user_id,
	  'post_type' => 'bp_media'
	);
	
	// Insert the post into the database
	$post = wp_insert_post( $my_post );	
	
	// return post id
	
	$data = array(
		'url' =>  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/album/' . $post
	);
	
	
	wp_send_json( $data );

}
add_action('wp_ajax_bp_media_ajax_create_album', 'bp_media_ajax_create_album');
add_action('wp_ajax_nopriv_bp_media_ajax_create_album', 'bp_media_ajax_create_album');



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