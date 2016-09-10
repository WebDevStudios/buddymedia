<?php


/**
 * The bp_media_is_option function.
 *
 * @param mixed $option Option to fetch.
 * @return boolean
 */
function bp_media_is_option( $option ) {

	$option = 'bp-media-' . $option;

	$option = bp_get_option( $option );

	if ( $option ) {
		return true;
	}

	return false;
}

/**
 * The bp_media_is_action_edit function.
 */
function bp_media_is_action_edit() {

	$variables = bp_action_variables();

	if ( isset( $variables[1] ) && 'edit' ===  $variables[1] ) {
		return true;
	}

	return false;
}


/**
 * The bp_media_loop_filter function.
 *
 * @access public
 */
function bp_media_loop_filter() {
	$bp = buddypress();

	$paged = ( isset( $_GET['mpage'] ) ) ? $_GET['mpage'] : 1;

	$query = array(
		'post_type'      => 'bp_media',
		'posts_per_page' => 12,
		'orderby'        => 'modified',
		'paged'          => $paged
	);

	$query = apply_filters( 'bp_media_loop_filter', $query );

	return $query;
}



/**
 * The bp_media_loop_profile_filter function.
 *
 * @param mixed $query Object.
 * @return object
 */
function bp_media_loop_profile_filter( $query ) {

	$paged = ( isset( $_GET['mpage'] ) ) ? $_GET['mpage'] : 1;

	if ( bp_is_user() ) {

		$author     = bp_displayed_user_id();
		$action     = bp_current_action();
		$action_var = bp_action_variables();

		$value = ( 'media' === $action ) ? 'public' : $action ;

		$query = array(
			'post_type'      => 'bp_media',
			'author'         => $author,
			'posts_per_page' => 11,
			'orderby'        => 'modified',
			'paged'          => $paged,
			'meta_query'     => array(
				array(
					'key'     => '_permission',
					'value'   => $value,
					'compare' => '='
				)
			)
		);


		if ( 'album' === $action ) {

			$query = array(
				'post_type' => 'attachment',
				'post_parent' => $action_var[0],
				'post_status' => 'inherit',
				'posts_per_page' => 12,
				'orderby' => 'modified',
				'paged' => $paged,
			);

		}

	}

	return $query;
}
add_filter( 'bp_media_loop_filter', 'bp_media_loop_profile_filter' );


/**
 * The bp_media_loop_permissions_filter function.
 *
 * @param mixed $query Object.
 * @return object
 */
function bp_media_loop_permissions_filter( $query ) {

	$action = bp_current_action();
	$paged  = ( isset( $_GET['mpage'] ) ) ? $_GET['mpage'] : 1;

	if ( ! bp_is_user() ) {

		$value = ( ! empty($_GET['permission']) ) ? $_GET['permission'] : 'public' ;

		$query = array(
			'post_type'      => 'bp_media',
			'posts_per_page' => 12,
			'orderby'        => 'modified',
			'paged'          => $paged,
			'meta_query'     => array(
				array(
					'key'     => '_permission',
					'value'   => $value,
					'compare' => '='
				)
			)
		);

	}


	if ( bp_is_group() ) {

		if ( 'media' === $action ) {

			$query = array(
				'post_type'      => 'attachment',
				'post_parent'    => false,
				'post_status'    => 'inherit',
				'posts_per_page' => 12,
				'orderby'        => 'modified',
				'paged'          => $paged,
				'meta_query'     => array(
					array(
						'key'     => 'secondary_item_id',
						'value'   => bp_get_group_id(),
						'compare' => '='
					)
				)
			);

		}

	}

	return $query;
}
add_filter( 'bp_media_loop_filter', 'bp_media_loop_permissions_filter' );


/**
 * The bp_media_css_class function.
 */
function bp_media_css_class() {
	echo bp_get_media_css_class();
}
	/**
	 * The bp_get_media_css_class function.
	 *
	 * @return string
	 */
	function bp_get_media_css_class() {
		return 'bp-media';
	}


/**
 * The bp_media_css_id function.
 */
function bp_media_css_id() {
	echo bp_get_media_css_id();
}
	/**
	 * The bp_get_media_css_id function.
	 *
	 * @return int
	 */
	function bp_get_media_css_id() {
		global $post;

		return $post->ID;
	}


/**
 * The bp_media_userlink function.
 */
function bp_media_userlink() {
		echo bp_media_get_userlink();

}

	/**
	 * The bp_media_get_userlink function.
	 */
	function bp_media_get_userlink() {
		global  $post;

		if ( $post->post_author ) {
			return bp_core_get_user_domain( $post->post_author ) . BP_MEDIA_SLUG;
		}

		return;
	}



/**
 * The bp_media_create_album_link function.
 */
function bp_media_create_album_link() {
	echo bp_media_get_create_album_link();
}


	/**
	 * The bp_media_get_create_album_link function.
	 */
	function bp_media_get_create_album_link() {
		return bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/create';
	}


/**
 * The bp_media_edit_album_link function.
 */
function bp_media_edit_album_link() {
	echo bp_media_get_edit_album_link();
}


	/**
	 * The bp_media_get_edit_album_link function.
	 */
	function bp_media_get_edit_album_link() {
		return bp_media_userlink( bp_displayed_user_id() ) . 'edit';
	}



/**
 * The bp_media_edit_image_link function.
 *
 * @param int|null $id ID.
 */
function bp_media_edit_image_link( $id = null ) {

	if ( ! $id ) {
		$action_var = bp_action_variables();
		$id = $action_var[0];
	}
	echo bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $id . '/edit';
}

/**
 * The bp_media_create_album_link_ajax function.
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
 * The bp_media_album_link function.
 */
function bp_media_album_link() {
		echo bp_get_media_album_link();
}

	/**
	 * The bp_get_media_album_link function.
	 *
	 * @access public
	 * @return string link
	 */
	function bp_get_media_album_link() {
		global $post;

		if ( $post->ID ) {
			return bp_media_userlink( $post->post_author ) . '/album/' . $post->ID;
		}

		return;

	}


/**
 * The bp_media_time_since function.
 *
 * @param mixed $photo_id Photo ID.
 * @return string
 */
function bp_media_time_since( $photo_id ) {

	$attachment = get_post( $photo_id );
	$post_date = sprintf( __( '%1$s ago', 'bp_media' ), human_time_diff( strtotime( $attachment->post_date ), current_time('timestamp') ) );

	return $post_date;
}



/**
 * The bp_album_cover_url function.
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
 * The bp_album_image_count function.
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
 * The bp_get_media_image_id function.
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
 * The bp_get_media_album_id function.
 *
 * @return mixed
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
 * The bp_media_album_field function.
 *
 * @param mixed $field (default: null).
 * @return void
 */
function bp_media_album_field( $field = null ) {
	echo bp_media_get_album_field( $field );
}
	/**
	 * The bp_media_get_album_field function.
	 *
	 * @param mixed $field (default: null).
	 * @return string
	 */
	function bp_media_get_album_field( $field = null ) {

		if( ! $field ) return;

		$action_var = bp_action_variables();

		$album = get_post( $action_var[0] );

		if( ! $album ) {
			die();
		}

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
 * The bp_media_image_description function.
 */
function bp_media_image_description() {
	echo bp_media_get_image_description();
}

	/**
	 * The bp_media_get_image_description function.
	 *
	 * @return string
	 */
	function bp_media_get_image_description() {

		$action_var = bp_action_variables();
		$meta = get_post_meta( $action_var[0], 'description', true );

		if( $meta ) return esc_html( $meta );

		return;
	}

function bp_media_album_meta( $field = null ) {
	echo bp_media_get_album_meta( $field );
}
	/**
	 * The bp_media_get_album_field function.
	 *
	 * @param mixed $field (default: null).
	 * @return mixed
	 */
	function bp_media_get_album_meta( $field = null ) {

		if( ! $field ) return;

		$action_var = bp_action_variables();

		$album = get_post_meta( $action_var[0], $field, true );

		if( $album ) return $album;

		return;
	}

function bp_media_posted_in() {
	echo bp_get_media_posted_in();
}

	function bp_get_media_posted_in() {
		global $bp;

		$action_var = bp_action_variables();
		$meta = get_post_meta( $action_var[0], 'secondary_item_id', true );

		if( !$meta ) return;

		if( $meta !== '0' ) {

			$group = groups_get_group( array( 'group_id' => (int) $meta ) );
			return __( 'Posted in the group <a href="' . home_url( $bp->groups->slug . '/' . $group->slug ) . '">'. $group->name .'</a>' , 'bp-media' );
		}

		return;
	}


/**
 * The bp_media_album_permission function.
 *
 * @param mixed $permission Unknown.
 */
function bp_media_album_permission( $permission ) {

	$action_var = bp_action_variables();

	$meta = get_post_meta( $action_var[0], '_permission', true );

	if( $meta === $permission) {
		echo 'checked="checkd"';
	}

}

/**
 * The bp_media_album_back_url function.
 *
 * @param string|null $album_id Album ID to show.
 */
function bp_media_album_back_url( $album_id = null ) {

	$action_var = bp_action_variables();

	if( !$album_id ) {
		$album_id = $action_var[0];
	}

	echo bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/album/' . $album_id;

}


/**
 * The bp_media_image_back_url function.
 */
function bp_media_image_back_url() {
	$action_var = bp_action_variables();
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $action_var[0];

}


/**
 * The bp_media_image_link function.
 *
 * @param mixed $id Media image ID.
 */
function bp_media_image_link( $id ) {
	echo  bp_core_get_user_domain( bp_displayed_user_id() ) . BP_MEDIA_SLUG . '/image/' . $id;
}



/**
 * The bp_media_group_image_link function.
 *
 * @param mixed $id      Media image ID.
 * @param mixed $user_id User ID.
 */
function bp_media_group_image_link( $id, $user_id ) {
	echo bp_media_get_group_image_link( $id, $user_id );
}

	function bp_media_get_group_image_link( $id, $user_id ) {
		return  bp_core_get_user_domain( $user_id ) . BP_MEDIA_SLUG . '/image/' . $id;
	}



/**
 * The bp_media_album_id function.
 */
function bp_media_album_id() {
	echo bp_media_get_album_id();
}


	/**
	 * The bp_media_get_album_id function.
	 *
	 * @return array|bool
	 */
	function bp_media_get_album_id() {
		$action_var = bp_action_variables();
		return $action_var[0];
	}



/**
 * The bp_media_enqueue_scripts function.
 */
function bp_media_enqueue_scripts() {
	 wp_enqueue_script('plupload-all');
}
add_action( 'wp_enqueue_scripts', 'bp_media_enqueue_scripts' );



/**
 * The bp_media_comments function.
 *
 * @param mixed $comment Comments.
 */
function bp_media_comments( $comment ) {
	bp_media_get_template_part('comments' );
}


/**
 * The bp_media_can_edit function.
 */
function bp_media_can_edit() {
	if ( is_user_logged_in() && bp_loggedin_user_id() === bp_displayed_user_id() || is_super_admin() || is_admin() ) {
		return true;
	}
	return false;
}



/**
 * The bp_media_add_activity_meta function.
 *
 * @param mixed $activity Activity object.
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
 * The bp_media_display_attachment_image function.
 */
function bp_media_display_attachment_image() {

	if ( $attachment_id = bp_activity_get_meta( bp_get_activity_id(), 'bp_media_attachment_id', true ) ) {

		/**
		 * Filter activity attachment media size.
		 *
		 * @since 1.0.0
		 *
		 * @param string media size
		*/
		$attachment_size = apply_filters( 'bp_media_display_attachment_size', 'medium' );

		$attachment_src = wp_get_attachment_image_src( $attachment_id, $attachment_size );
		$attachment_url = bp_core_get_user_domain( bp_get_activity_user_id() ) . BP_MEDIA_SLUG . '/image/' . $attachment_id;

		/**
		 * Filter activity attachment markup and parameters.
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
 * The bp_media_delete_attachments_before_delete_post function.
 *
 * This is album clean up, deletes attachents/images when album is deleted
 *
 * @param mixed $id Attachemnt ID.
 */
function bp_media_delete_attachments_before_delete_post( $id ){
	global $post;

	if( $post && 'bp_media' !== $post->post_type ) return;

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
// Till wp 3.1.
add_action( 'delete_post', 'bp_media_delete_attachments_before_delete_post' );
// Trom wp 3.2.
add_action( 'before_delete_post', 'bp_media_delete_attachments_before_delete_post' );



/**
 * The bp_media_filter_album_attachments function.
 *
 * @param mixed $args Media args.
 * @param mixed $type Media type.
 * @param mixed $post Post object.
 * @return array
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
 * The bp_media_user_can_delete function.
 *
 * @param mixed $user_id User ID.
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
 * The bp_media_user_can_access function.
 *
 * @param int $user_id (default: 0).
 * @return boolean
 */
function bp_media_user_can_access( $user_id = 0 ) {

	if( !is_user_logged_in() ) return;

	if( 0 === $user_id  ) {
		$user_id = bp_displayed_user_id();
	}

	if( ! apply_filters( 'bp_media_user_can_access', $user_id ) ) return;

	if( bp_loggedin_user_id() === (int) $user_id ) {
		return true;
	}
	return;
}


/**
 * The bp_is_friend_boolean function.
 *
 * Thank you for being a friend.
 *
 * @return boolean
 */
function bp_is_friend_boolean() {
	$is_friend = bp_is_friend();

	if( 'is_friend' === $is_friend ) {
		return true;
	}
	return false;
}


/**
 * The bp_media_pagination_count function.
 *
 * @param object $query Query object.
 */
function bp_media_pagination_count( $query ) {
	echo bp_media_get_pagination_count( $query );
}
	/**
	 * Generate the "Viewing x-y of z albums" pagination message.
	 *
	 * @param object $query Query object.
	 * @return string
	 */
	function bp_media_get_pagination_count( $query ) {

		$action = ( 'album' !== bp_current_action() && bp_is_user() ) ? __('album', 'bp-media') : __('image', 'bp-media') ;

		if( bp_is_directory() && !bp_current_action() ) {
			$action = __('album', 'bp-media');
		}

		$paged = ( isset($_GET['mpage']) ) ? $_GET['mpage'] : 1;
		$posts_per_page = $query->query['posts_per_page'];

		$start_num = intval( ( $paged - 1 ) * $posts_per_page ) + 1;
		$from_num  = bp_core_number_format( $start_num );
		$to_num    = bp_core_number_format( ( $start_num + ( $posts_per_page - 1 ) > $query->found_posts ) ? $query->found_posts : $start_num + ( $posts_per_page - 1 ) );
		$total     = bp_core_number_format( $query->found_posts );

		if ( 1 == $query->found_posts ) {
			$message = __( 'Viewing 1 ' . $action, 'bp-media' );
		} else {
			$message = sprintf( _n( 'Viewing %1$s - %2$s of %3$s '.$action.'s', 'Viewing %1$s - %2$s of %3$s '.$action.'s', $query->found_posts, 'bp-media' ), $from_num, $to_num, $total );
		}

		/**
		 * Filters the "Viewing x-y of z albums" pagination message.
		 *
		 * @param string $message  "Viewing x-y of z album" text.
		 * @param string $from_num Total amount for the low value in the range.
		 * @param string $to_num   Total amount for the high value in the range.
		 * @param string $total    Total amount of albums found.
		 */
		return apply_filters( 'bp_media_get_pagination_count', $message, $from_num, $to_num, $total );
	}


/**
 * The bp_media_pagination_links function.
 *
 * @param mixed $query Query object.
 */
function bp_media_pagination_links( $query ) {
	echo bp_media_get_pagination_links( $query );
}

	function bp_media_get_pagination_links( $query ) {

			$paged = ( isset($_GET['mpage']) ) ? $_GET['mpage'] : 1;

			$pag_args = array(
				'mpage' => '%#%'
			);

			if ( defined( 'DOING_AJAX' ) && true === (bool) DOING_AJAX ) {
				$base = remove_query_arg( 's', wp_get_referer() );
			} else {
				$base = '';
			}

			echo paginate_links( array(
				'base'      => add_query_arg( $pag_args, $base ),
				'format'    => '',
				'total'     => ceil( (int) $query->found_posts / (int)  $query->query['posts_per_page'] ),
				'current'   => $paged,
				'prev_text' => _x( '&larr;', 'Media pagination previous text', 'bp-media' ),
				'next_text' => _x( '&rarr;', 'Media pagination next text', 'bp-media' ),
			) );
	}
