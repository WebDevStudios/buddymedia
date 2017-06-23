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
				'author'       => __( 'Reporter', 'buddymedia' ),
				'media_item'   => __( 'Media', 'buddymedia' ),
				'album'        => __( 'Album', 'buddymedia' ),
				'comment'      => _x( 'Report Message', 'column name', 'buddymedia' ),
				'actions'      => __( 'Actions', 'buddymedia' ),
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
		$post_type    = '';
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

		$_comments = get_comments( $args );

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
	 * @global int $post_id
	 *
	 * @return array
	 */
	public function get_columns() {
		global $post_id;
		$columns = array();
		if ( $this->checkbox ) {
			$columns['cb'] = '<input type="checkbox" />';
		}
		$columns['author'] = __( 'Reporter', 'buddymedia' );
		$columns['comment'] = _x( 'Report Message', 'column name' );
		if ( ! $post_id ) {
			/* translators: column name or table row header */
			$columns['response'] = __( 'In Response To' );
		}
		$columns['date'] = _x( 'Submitted On', 'column name' );
		return $columns;
	}

	/**
	 * Display the media comment album.
	 *
	 * @param WP_Comment $comment The comment object.
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function column_album( $comment ) {
		$parent_id  = wp_get_post_parent_id( $comment->comment_post_ID );
		echo '<a href="' . get_edit_post_link( $parent_id ) . '">' . get_the_title( $parent_id ) . '</a>';
	}

	/**
	 * Display the media comment album.
	 *
	 * @param WP_Comment $comment The comment object.
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function column_media_item( $comment ) {
		if ( wp_attachment_is_image( $comment->comment_post_ID ) ) {
			echo '<a href="' . get_edit_post_link( $comment->comment_post_ID ) . '">' . wp_get_attachment_image( $comment->comment_post_ID, 'thumbnail' ) . '</a>';
		}
	}

	/**
	 * Display the media comment album.
	 *
	 * @param WP_Comment $comment The comment object.
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function column_actions( $comment ) {
		$parent_id  = wp_get_post_parent_id( $comment->comment_post_ID );
		?>
		<a href="<?php echo esc_url( get_edit_post_link( $parent_id ) ); ?>" class="button"><?php esc_html_e( 'Moderate', 'buddymedia' ); ?></a>
		<?php
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
	 * @since  1.0.2
	 * @author  Kailan W.
	 *
	 * @global string $comment_status
	 *
	 * @return array
	 */
	protected function get_bulk_actions() {}

	/**
	 * @since  1.0.2
	 * @author  Kailan W.
	 *
	 * @global string $comment_status
	 * @global string $comment_type
	 *
	 * @param string $which
	 */
	protected function extra_tablenav( $which ) {}

	/**
	 *
	 * @global string $comment_status
	 */
	public function no_items() {
		global $comment_status;
		if ( 'moderated' === $comment_status ) {
			_e( 'No reports awaiting moderation.', 'buddymedia' );
		} else {
			_e( 'No reports found.', 'buddymedia' );
		}
	}
}