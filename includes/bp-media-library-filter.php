<?php

class BP_MEDIA_LIBRARY_FILTER {
	/**
	 * Defines media types
	 * @var array
	 */
	protected static $media_types = array();

	/**
	 * Holds a singleton instance of this class
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Returns an instance of this class
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	/**
	 * __construct function.
	 *
	 * @access private
	 * @return void
	 */
	private function __construct() {

		self::$media_types = array(
			'all_user_media' => __( 'All User Media', 'bp-media-custom-fields' ),
			'my_media' => __( 'My Media', 'bp-media-custom-fields' ),
		);

		add_filter( 'restrict_manage_posts', array( $this, 'bp_media_restrict_manage_posts' ) );
		add_filter( 'request', array( $this, 'bp_media_request_admin' ) );
		add_filter( 'post_mime_types', array( $this, 'bp_media_modify_post_mime_types' ) );
		add_action( 'pre_get_posts' , array( $this, 'bp_media_pre_get_posts' ) );
	}


	/**
	 * modify_post_mime_types function.
	 *
	 * @access public
	 * @param mixed $post_mime_types
	 * @return void
	 */
	public function bp_media_modify_post_mime_types( $post_mime_types ) {
		global $pagenow;

		if( 'upload.php' === $pagenow  ) {
			//set meme type to empty
			$post_mime_types['bpmedia'] = array( '', '', '' );
		}
		return $post_mime_types;
	}


	/**
	 * request_admin function.
	 *
	 * @access public
	 * @param mixed $request
	 * @return void
	 */
	public function bp_media_request_admin( $request ) {
		// bail if not the admin screen
		if ( ! is_admin() ) {
			return $request;
		}

		// add a mime type so items display
		if ( ! empty( $_GET['media_type'] ) ) {
			$request['post_mime_types'] = 'bpmedia';
		}

		return $request;
	}


	/**
	 * restrict_manage_posts function.
	 *
	 * @access public
	 * @return void
	 */
	public function bp_media_restrict_manage_posts() {
		global $pagenow;

		if( 'upload.php' != $pagenow  ) return;
?>
		<select name="media_type">
			<option value=""><?php _e( 'User Media', 'bp-media-type-search' ); ?></option>

			<?php foreach ( self::$media_types as $type => $name ): ?>
				<option value="<?php echo esc_attr( $type ); ?>" <?php selected( $_GET['media_type'], $type ); ?>><?php echo esc_attr( $name ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}



	/**
	 * bp_media_pre_get_posts function.
	 *
	 * @access public
	 * @param mixed $query
	 * @return void
	 */
	public function bp_media_pre_get_posts( $query ) {
		global $pagenow;


		if( 'upload.php' != $pagenow || !$query->is_admin && $query->is_main_query() ) return;

		// filter out user media by default
		$query->set( 'meta_query', array(
				array(
					'key' => 'bp_media',
					'compare' => 'NOT EXISTS'
				)
			)
		);

		// filter based on select
		switch ( $_REQUEST['media_type'] ) {

		// show all user media
		case 'all_user_media':
			$query->set( 'meta_query', array(
					array(
						'key' => 'bp_media',
						'value' => '1'
					)
				)
			);
		break;
		
		// show logged in user media
		case 'my_media':
			$query->set( 'meta_query', array(
					array(
						'key' => 'bp_media',
						'value' => '1'
					)
				)
			);
			$query->set( 'author', bp_loggedin_user_id() );
		break;

		}


	}



}
BP_MEDIA_LIBRARY_FILTER::get_instance();