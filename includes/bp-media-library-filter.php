<?php

class BP_MEDIA_LIBRARY_FILTER {
	/**
	 * Defines media types/
	 *
	 * @var array
	 */
	protected static $media_types = array();

	/**
	 * Holds a singleton instance of this class.
	 *
	 * @var null
	 */
	protected static $instance = null;

	/**
	 * Returns an instance of this class.
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
	 */
	private function __construct() {
		add_action( 'pre_get_posts' , array( $this, 'bp_media_pre_get_posts' ) );
		add_filter( 'ajax_query_attachments_args', array( $this, 'bp_media_ajax_pre_get_posts' ), 10, 1 );
	}

	/**
	 * The bp_media_pre_get_posts function.
	 *
	 * @access public
	 *
	 * @param WP_Query $query Query object.
	 * @return WP_Post $query
	 */
	public function bp_media_pre_get_posts( $query ) {
		global $pagenow;

		if ( ( 'edit.php' != $pagenow && 'upload.php' != $pagenow   ) || !$query->is_admin && $query->is_main_query() ) {
			return $query;
		}

		// Filter out user media by default.
		$query->set( 'meta_query', array(
				array(
					'key' => 'bp_media',
					'compare' => 'NOT EXISTS'
				)
			)
		);

		return $query;
	}

	/**
	 * The bp_media_ajax_pre_get_posts function.
	 *
	 * @access public
	 * @param array $query (default: array()).
	 * @return array
	 */
	public function bp_media_ajax_pre_get_posts( $query = array() ) {
		// Filter out user media by default.
		$query['meta_query'] = array(
				array(
					'key' => 'bp_media',
					'compare' => 'NOT EXISTS'
				)
			);
		return $query;
	}

}
BP_MEDIA_LIBRARY_FILTER::get_instance();
