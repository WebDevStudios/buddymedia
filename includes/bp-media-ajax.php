<?php
class BP_Media_AJAX {

	/**
	 * Holds a singleton instance of this class.
	 *
	 * @var null
	 *
	 * @since 1.0.2
	 */
	protected static $instance = null;

	/**
	 * Returns an instance of this class.
	 *
	 * @since 1.0.2
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Initiate hooks.
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function hooks() {
		add_action('wp_ajax_photo_gallery_upload', array( $this, 'bp_media_upload_photo' ) );
		add_action('wp_ajax_bp_media_photo_activity_attach', array( $this, 'bp_media_photo_activity_attach' ) );

		add_action('wp_ajax_bp_media_get_image', array( $this, 'bp_media_get_image' ) );
		add_action('wp_ajax_nopriv_bp_media_get_image', array( $this, 'bp_media_get_image' ) );

		add_action('wp_ajax_bp_media_add_album', array( $this, 'bp_media_add_album' ) );

		add_action('wp_ajax_bp_media_ajax_create_album', array( $this, 'bp_media_ajax_create_album' ) );
		add_action('wp_ajax_bp_media_ajax_edit_album', array( $this, 'bp_media_ajax_edit_album' ) );

		add_action('wp_ajax_bp_media_ajax_delete_album', array( $this, 'bp_media_ajax_delete_album' ) );
		add_action('wp_ajax_bp_media_ajax_delete_image', array( $this, 'bp_media_ajax_delete_image' ) );
		add_action('wp_ajax_bp_media_ajax_edit_image', array( $this, 'bp_media_ajax_edit_image' ) );
		add_action('wp_ajax_bp_media_ajax_add_comment', array( $this, 'bp_media_ajax_add_comment' ) );
		add_action('wp_ajax_bp_media_ajax_delete_comment', array( $this, 'bp_media_ajax_delete_comment' ) );
	}

	/**
	 * The bp_media_upload_photo function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_upload_photo() {

		check_ajax_referer('photo-upload');

		// Upload file.
		$file   = $_FILES['async-upload'];
		$status = wp_handle_upload( $file, array( 'test_form' => true, 'action' => 'photo_gallery_upload' ) );

		$wp_upload_dir = wp_upload_dir();

		// Adds file as attachment to WordPress.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $status['file'] ),
			'post_mime_type' => $status['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $status['file'] ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id  = wp_insert_attachment( $attachment, $status['file'], (int) $_POST['gallery_id'] );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $status['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		update_post_meta( $attach_id, 'description', $_POST['description'] );
		update_post_meta( $attach_id, 'bp_media', '1' );

		$image = wp_get_attachment_image_src( $attach_id, 'thumbnail');

		$data = array(
			'id'  => $attach_id,
			'url' => $image[0],
		);

		 /**
		  * Fires after a photo is uploaded.
		  *
		  * @param array {
		  *     @type int $id Attachment ID.
		  *     @type string   $url Image URL.
		  * }
		  */
		do_action( 'bp_media_after_media_photo_upload', $data );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_photo_activity_attach function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_photo_activity_attach() {

		check_ajax_referer('photo-upload');

		// Upload file.
		$file = $_FILES['async-upload'];
		$status = wp_handle_upload( $file, array( 'test_form' => true, 'action' => 'bp_media_photo_activity_attach' ) );

		if ( ! $album_id = $this->bp_media_get_activity_album_id( $_POST['user_id'] ) ) {
			exit;
		}

		$wp_upload_dir = wp_upload_dir();

		// Adds file as attachment to WordPress.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $status['file'] ),
			'post_mime_type' => $status['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $status['file'] ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		$attach_id = wp_insert_attachment( $attachment, $status['file'], (int) $album_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $status['file'] );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		update_post_meta( $attach_id, 'bp_media', '1' );
		update_post_meta( $attach_id, 'secondary_item_id', (int) $_POST['whats-new-post-in'] );

		$image = wp_get_attachment_image_src( $attach_id, 'thumbnail');

		$data = array(
			'id'       => $attach_id,
			'album_id' => $album_id,
			'url'      => $image[0],
		);

		/**
		 * Fires after Media is attached.
		 *
		 * @param array {
		 *     @type array $id Attachment ID.
		 *     @type int   $album_id Album ID.
		 *     @type string   $url Image URL.
		 * }
		 */
		do_action( 'bp_media_after_media_photo_activity_attach', $data );

		wp_send_json( $data );

		exit;
	}

	/**
	 * The bp_media_get_activity_album_id function.
	 *
	 * @param mixed $user_id User ID.
	 *
	 * @since 1.0.2
	 *
	 * @return int
	 */
	public function bp_media_get_activity_album_id( $user_id = 0 ) {

		if ( ! $user_id ) {
			return;
		}

		$post = get_posts( array(
		    'meta_key'  => '_activity_album',
		    'author'    => (int) $user_id,
		    'post_type' => 'bp_media',
		) );

		if ( $post ) {
			return $post[0]->ID;
		}

		// Create post object.
		$my_post = array(
		  'post_title'   => __( 'Activity Attachments', 'bp-media' ),
		  'post_content' => __( 'Images upload while posting activity.', 'bp-media' ),
		  'post_status'  => 'publish',
		  'post_author'  => (int) $user_id,
		  'post_type'    => 'bp_media',
		);

		add_filter('bp_activity_type_before_save', '__return_false', 9999 );

		// Insert the post into the database.
		$post = wp_insert_post( $my_post );

		add_post_meta( $post, '_activity_album', true, true );
		add_post_meta( $post, '_permission', 'public', true );

		return $post;
	}

	/**
	 * The bp_media_get_image function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_get_image() {

		$photo_id = ! empty( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		$guid     = ! empty( $_GET['guid'] ) ? (int) $_GET['guid'] : 0;
		$user_id  = ! empty( $_GET['user'] ) ? (int) $_GET['user'] : 0;
		$user     = get_user_by( 'id', (int) $user_id );

		include( bp_media_get_template_part( 'single/image') );

		die();
	}

	/**
	 * The bp_media_add_album function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_add_album() {

		include_once( bp_media_get_template_part( 'single/add-album') );

		die();
	}

	/**
	 * The bp_media_ajax_create_album function.
	 *
	 * @since 1.0.2
	 */
	function bp_media_ajax_create_album(){

		check_ajax_referer( 'create-album', 'nonce' );

		$title      = ! empty( $_GET['title'] ) ? sanitize_text_field( $_GET['title'] ) : '';
		$content    = ! empty( $_GET['description'] ) ? wp_kses_post( $_GET['description'] ) : '';
		$permission = ( ! empty( $_GET['permission'] ) ) ? $_GET['permission'] : 'public';
		$user_id    = ! empty( $_GET['user_id'] ) ? (int) $_GET['user_id'] : 0;

		// Create post object.
		$my_post = array(
			'post_title'   => sanitize_text_field( $title ),
			'post_content' => sanitize_text_field( $content ),
			'post_status'  => 'publish',
			'post_author'  => (int) $user_id,
			'post_type'    => 'bp_media',
		);

		// Dont post activity if album is not public.
		if ( 'public' !== $permission ) {
			add_filter('bp_activity_type_before_save', '__return_false', 9999 );
		}

		// Insert the post into the database.
		$post = wp_insert_post( $my_post );

		// Add permission meta.
		if ( $post ) {
			update_post_meta( $post, '_permission', $permission );
		}

		// Return album link.
		$data = array(
			'url' =>  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/album/' . $post . '?new=true'
		);

		/**
		 * Runs after album is created.
		 *
		 * @param int|WP_Error $post The post ID on success. The value 0 or WP_Error on failure.
		 */
		do_action( 'bp_media_after_album_created', $post );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_ajax_edit_album function.
	 *
	 * @since 1.0.2
	 */
	function bp_media_ajax_edit_album(){

		check_ajax_referer( 'edit-album', 'nonce' );

		$title      = ! empty( $_GET['title'] ) ? $_GET['title'] : '';
		$content    = ! empty( $_GET['description'] ) ? wp_kses_post( $_GET['description'] ) : '';
		$user_id    = ! empty( $_GET['user_id'] ) ? (int) $_GET['user_id'] : '';
		$post_id    = ! empty( $_GET['post_id'] ) ? (int) $_GET['post_id'] : '';
		$permission = ! empty( $_GET['permission'] ) ? $_GET['permission'] : '';

		// Update post.
		$my_post = array(
		  'ID'           => (int) $post_id,
		  'post_title'   => sanitize_text_field( $title ),
		  'post_content' => sanitize_text_field( $content ),
		);

		// Update the post into the database.
		$post = wp_update_post( $my_post );

		if ( $post ) {
			update_post_meta( $post, '_permission', $permission );
		}

		// Return post id.
		$data = array(
			'url' =>  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/album/' . $post . '/edit'
		);

		/**
		 * Runs after album is edited.
		 *
		 * @param int|false $post The post ID on success. The value 0 on failure.
		 */
		do_action( 'bp_media_after_album_edited', $post );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_ajax_delete_album function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_ajax_delete_album(){

		check_ajax_referer( 'edit-album', 'nonce' );

		$user_id = ! empty( $_GET['user_id'] ) ? (int) $_GET['user_id'] : 0;
		$post_id = ! empty( $_GET['post_id'] ) ? (int) $_GET['post_id'] : 0;

		// Delete the post.
		wp_delete_post( (int) $post_id, true );

		// Return post id.
		$data = array(
			'url' =>  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG
		);

		/**
		 * Fires after album is deleted.
		 *
		 * @param int $post_id Post ID.
		 * @param int $user_id User ID.
		 */
		do_action( 'bp_media_album_deleted', $post_id, $user_id );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_ajax_delete_image function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_ajax_delete_image(){

		check_ajax_referer( 'edit-album', 'nonce' );

		$user_id  = ( ! empty( $_GET['user_id'] ) ) ? $_GET['user_id'] : get_current_user_id();
		$image_id = $_GET['image_id'];
		$parent   = get_post_field( 'post_parent', $image_id );

		if ( $activity_id = get_post_meta( $image_id, 'activity_id', true ) ) {
			bp_activity_delete( array( 'id' => $activity_id ) );
		}

		// Delete the post.
		wp_delete_attachment( (int) $image_id, true );

		// Return post id.
		$data = array(
			'id'  => $image_id,
			'url' => bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/album/' . $parent,
		);

		/**
		 * Fires after photo is deleted.
		 *
		 * @param int $post_id Post ID.
		 * @param int $parent Parent Album.
		 * @param int $user_id User ID.
		 */
		do_action( 'bp_media_photo_deleted', $image_id, $parent, $user_id );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_ajax_edit_image function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_ajax_edit_image(){

		check_ajax_referer( 'edit-album', 'nonce' );

		$user_id     = $_GET['user_id'];
		$image_id    = $_GET['image_id'];
		$description = sanitize_text_field( $_GET['description'] );

		// Delete the post.
		update_post_meta( (int) $image_id, 'description', $description );

		$data = array(
			'id'  => $image_id,
			'url' => bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/image/' . $image_id,
		);

		/**
		 * Fires after photo is deleted.
		 *
		 * @param int $post_id Post ID.
		 * @param int $parent Parent Album.
		 * @param int $user_id User ID.
		 */
		do_action( 'bp_media_photo_deleted', $image_id, $parent, $user_id );

		wp_send_json( $data );
	}

	/**
	 * The bp_media_ajax_add_comment function.
	 *
	 * @since 1.0.2
	 */
	public function bp_media_ajax_add_comment(){

		check_ajax_referer( 'add-comment', 'nonce' );

		$user_id = $_GET['user_id'];
		$post_id = $_GET['post_id'];
		$comment = $_GET['upload_comment'];

		if ( empty( $comment ) ) {
			return;
		}

		$time = current_time('mysql');

		$data = array(
		    'comment_post_ID'      => $post_id,
		    'comment_author'       => '',
		    'comment_author_email' => '',
		    'comment_author_url'   => '',
		    'comment_content'      => $comment,
		    'comment_type'         => '',
		    'comment_parent'       => 0,
		    'user_id'              => $user_id,
		    'comment_author_IP'    => '',
		    'comment_agent'        => '',
		    'comment_date'         => $time,
		    'comment_approved'     => 1,
		);

		$data = apply_filters( 'bp_media_ajax_add_comment', $data );

		$comment_id = wp_insert_comment( $data );
		$comment    = array( get_comment( $comment_id ) );

		/**
		 * Fires after comment added.
		 *
		 * @param int $comment_id Comment ID.
		 * @param int $post_id Current Post.
		 * @param int $user_id User ID.
		 */
		do_action( 'bp_media_comment_updated', $comment_id, $post_id, $user_id );

		wp_list_comments( array(
			'type'              => 'comment',
			'callback'          => 'bp_media_comments',
			'per_page'          => 10, // Allow comment pagination.
			'reverse_top_level' => false,
		), $comment );

		die();
	}

	/**
	 * The bp_media_ajax_delete_comment function.
	 *
	 * @since 1.0.2
	 */
	function bp_media_ajax_delete_comment(){

		check_ajax_referer( 'add-comment', 'nonce' );

		$user_id    =  $_GET['user_id'];
		$comment_id =  $_GET['comment_id'];

		if ( empty( $comment_id ) || bp_loggedin_user_id() !== (int) $user_id ) {
			return;
		}

		$comment_id = wp_delete_comment( $comment_id );

		/**
		 * Fires after comment deleted.
		 *
		 * @param int $comment_id Comment ID.
		 * @param int $user_id User ID.
		 */
		do_action( 'bp_media_comment_deleted', $comment_id, $user_id );

		wp_send_json( $comment_id );
	}
}
BP_Media_AJAX::get_instance();
