<?php
/**
 * List Table API: BP_Media_Reports_List_Table class
 *
 * @package BuddyMedia
 * @since 1.0.2
 */
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );
/**
 * Core class used to implement displaying post comments in a list table.
 *
 * @since 1.0.2
 * @access private
 *
 * @see BP_Media_Reports_List_Table
 */
class BP_Media_Reports_List_Table extends WP_Comments_List_Table {

	/**
	 *
	 * @return array
	 */
	protected function get_column_info() {
		return array(
			array(
				'author'   => __( 'Author' ),
				'comment'  => _x( 'Comment', 'column name' ),
			),
			array(),
			array(),
			'comment',
		);
	}


	/**
	 *
	 * @global int    $post_id
	 * @global string $comment_status
	 * @global string $search
	 * @global string $comment_type
	 */
	public function prepare_items() {
		global $post_id, $comment_status, $search, $comment_type;

		$comment_status = isset( $_REQUEST['comment_status'] ) ? $_REQUEST['comment_status'] : 'all';
		if ( ! in_array( $comment_status, array( 'all', 'moderated', 'approved', 'spam', 'trash' ) ) ) {
			$comment_status = 'all';
		}

		$comment_type = ! empty( $_REQUEST['comment_type'] ) ? $_REQUEST['comment_type'] : '';

		$search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : '';

		$post_type = ( isset( $_REQUEST['post_type'] ) ) ? sanitize_key( $_REQUEST['post_type'] ) : '';

		$user_id = ( isset( $_REQUEST['user_id'] ) ) ? $_REQUEST['user_id'] : '';

		$orderby = ( isset( $_REQUEST['orderby'] ) ) ? $_REQUEST['orderby'] : '';
		$order = ( isset( $_REQUEST['order'] ) ) ? $_REQUEST['order'] : '';

		$comments_per_page = $this->get_per_page( $comment_status );

		$doing_ajax = wp_doing_ajax();

		if ( isset( $_REQUEST['number'] ) ) {
			$number = (int) $_REQUEST['number'];
		} else {
			$number = $comments_per_page + min( 8, $comments_per_page ); // Grab a few extra
		}

		$page = $this->get_pagenum();

		if ( isset( $_REQUEST['start'] ) ) {
			$start = $_REQUEST['start'];
		} else {
			$start = ( $page - 1 ) * $comments_per_page;
		}

		if ( $doing_ajax && isset( $_REQUEST['offset'] ) ) {
			$start += $_REQUEST['offset'];
		}

		$status_map = array(
			'moderated' => 'hold',
			'approved' => 'approve',
			'all' => '',
		);
		$comment_type = 'bp_media_flag';
		$args = array(
			'status' => isset( $status_map[ $comment_status ] ) ? $status_map[ $comment_status ] : $comment_status,
			'search' => $search,
			'user_id' => $user_id,
			'offset' => $start,
			'number' => $number,
			'post_id' => $post_id,
			'type' => $comment_type,
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => $post_type,
		);
		error_log( print_r( $args, true ) );

		$_comments = get_comments( $args );
		error_log( print_r( $_comments, true ) );
		if ( is_array( $_comments ) ) {
			update_comment_cache( $_comments );

			$this->items = array_slice( $_comments, 0, $comments_per_page );
			$this->extra_items = array_slice( $_comments, $comments_per_page );

			$_comment_post_ids = array_unique( wp_list_pluck( $_comments, 'comment_post_ID' ) );

			$this->pending_count = get_pending_comments_num( $_comment_post_ids );
		}

		$total_comments = get_comments( array_merge( $args, array(
			'count' => true,
			'offset' => 0,
			'number' => 0,
		) ) );

		$this->set_pagination_args( array(
			'total_items' => $total_comments,
			'per_page' => $comments_per_page,
		) );
	}
	/**
	 *
	 * @return array
	 */
	protected function get_table_classes() {
		$classes = parent::get_table_classes();
		$classes[] = 'wp-list-table';
		$classes[] = 'comments-box';
		return $classes;
	}

	/**
	 *
	 * @param bool $comment_status
	 * @return int
	 */
	public function get_per_page( $comment_status = false ) {
		return 10;
	}
}