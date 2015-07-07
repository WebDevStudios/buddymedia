<?php 

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
		'url' =>  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/album/' . $post . '?new=true'
	);
	
	
	wp_send_json( $data );

}
add_action('wp_ajax_bp_media_ajax_create_album', 'bp_media_ajax_create_album');
add_action('wp_ajax_nopriv_bp_media_ajax_create_album', 'bp_media_ajax_create_album');