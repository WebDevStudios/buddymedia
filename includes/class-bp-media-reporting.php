<?php
/**
 * We will use WordPress default comment system for managing
 * reporting/flagging
 */

class BP_Media_Reporting {

	/**
	 * Custom Comment type
	 * @var string
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public $comment_type;

	/**
	 * Initiate Constructor
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function __construct() {
		$this->comment_type = 'bp_media_flag';
		$this->hooks();

		$this->title = __( 'Reports', 'buddymedia' );
	}

	/**
	 *  Initiate WordPress Hooks.
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'admin_comment_types_dropdown', array( $this, 'add_custom_comment_type_dropdown' ) );
		add_action( 'bp_media_photo_options', array( $this, 'add_flag' ) );
		//ajax command
		add_action( 'wp_ajax_bp_media_make_report', array( $this, 'bp_media_make_report' ) );
		//add_action('wp_ajax_nopriv_bp_media_make_report', array($this, 'bp_media_make_report') );
	}

	/**
	 * Setup Admin Menu section.
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function setup_menu() {
		add_submenu_page(
			'edit.php?post_type=bp_media',
			$this->title,
			$this->title,
			'manage_options',
			'bp_media_reports',
			array( $this, 'reports_page' )
		);
	}

	public function reports_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php
			$wp_list_table = new BP_Media_Reports_List_Table();
			$wp_list_table->prepare_items();
			$wp_list_table->get_columns();
			$wp_list_table->display( true );
			?>
		</div>
		<?php
	}

	/**
	 * Add custom comment type to comment type dropdown
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function add_custom_comment_type_dropdown( $comment_types = array() ) {
		$comment_types[ $this->comment_type ] = __( 'Flagged Media', 'bp-media' );
		return $comment_types;
	}

	/**
	 * Add flag for reporting item
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	public function add_flag() {
		if ( ! is_user_logged_in() ) {
			return;
		}
		global $post;
		?>
		<a href="#bp_media_report" class="right bp-report-item" data-item_id="<?php echo (int) $post->ID; ?>" data-nonce="<?php echo wp_create_nonce( 'report-item-' . (int) $post->ID ); ?>"><?php _e( 'report', 'bp_media' ); ?></a>
		<?php
	}

	/**
	 * AJAX function for flagging image as comment
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 *
	 * @return string JSON Object.
	 */
	public function bp_media_make_report() {
		$item_id = $_POST['item_id'];
		check_ajax_referer( 'report-item-' . $item_id, 'nonce' );

		$item_id = $_POST['item_id'];
		$user_id = get_current_user_id();

		if ( empty( $item_id ) ) {
			return;
		}

		$comment_content    = ! empty( $_POST['reason'] ) ? sanitize_text_field( $_POST['reason'] ) : '';
		$comment_content    = $comment_content . '<br />' . ( ! empty( $_POST['message'] ) ? wp_kses_post( $_POST['message'] ) : '' );
		$results = array();
		$commentdata = array(
			'comment_post_ID'  => $item_id, // To which post the comment will show up.
			'comment_content'  => $comment_content,
			'comment_type'     => $this->comment_type, // Use a custom comment type.
			'user_id'          => $user_id, // Passing current user ID or any predefined as per the demand.
		);

		// Allow duplicate comment.
		add_filter( 'duplicate_comment_id', '__return_false' );

		// Insert new comment and get the comment ID.
		$comment_id = wp_new_comment( $commentdata );

		// Disable previous filter.
		add_filter( 'duplicate_comment_id', '__return_true' );

		// If comment was saved then return success.
		if ( $comment_id ) {
			$results['success'] = true;
			$results['comment_id'] = $comment_id;
		}
		wp_send_json( $results );
	}
}

new BP_Media_Reporting();
