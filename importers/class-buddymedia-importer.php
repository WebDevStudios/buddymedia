<?php
/**
 * Main Importer class for Buddymedia.
 *
 * @author  Kailan W.
 *
 * @since  1.0.2
 */
class BuddyMedia_Importer {
	/**
	 * Images database table.
	 *
	 * @author  Kailan W.
	 *
	 * @var string
	 */
	public $image_table;

	/**
	 * Albums database table.
	 *
	 * @author  Kailan W.
	 *
	 * @var string
	 */
	public $album_table;

	/**
	 * Does the imported media come from the WP Media.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 *
	 * @var boolean
	 */
	public $uses_wp_media;

	/**
	 *  Constructor
	 */
	public function __construct() {
		error_log( 123 );
		$this->hooks();
	}

	/**
	 * Initiate hooks.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function hooks() {
		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'wp_ajax_bp_media_import', array( $this, '' ) );
		$this->title = __( 'Import', 'buddymedia' );
	}

	/**
	 * Setup Admin Menu section.
	 *
	 * @author Kailan W.
	 *
	 * @since 1.0.2
	 */
	function setup_menu() {
		add_submenu_page(
			'edit.php?post_type=bp_media',
			$this->title,
			$this->title,
			'manage_options',
			'bp_media_importer',
			array( $this, 'import_page' )
		);
	}

	/**
	 * Get users with media items.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function import_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<div class="bp-media-importer-wrapper">
			<div class="bp-media-import-type">
				<h2>Plugin to import</h2>
				<a href="#" class="button"><?php esc_html_e( 'Import', 'buddymedia' ); ?></a>
				<progress value="0" max="100">
			</div>
			<div>
		</div>
		<?php
	}

	/**
	 * Get users with media items.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function import() {
		$users = $this->get_users_with_media();
		if ( ! empty( $users ) ) {
			foreach ( $users as $user_id ) {
				$media_items = $this->get_media_by_user( $user_id );
				if ( ! empty( $media_items ) ) {

				}
			}
		}
	}

	/**
	 * Get users with media items.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function get_users_with_media() {}

	/**
	 * Get an array of media item by user ID.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function get_media_by_user( $user_id = 0 ) {}

	/**
	 * Get an array of users that has albums.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function get_albums_by_user( $user_id = 0 ) {}

	/**
	 * Create album.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function create_album( $album_data = array() ) {}

	/**
	 * Create media item.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function create_media( $image_data = array() ) {}

	/**
	 * Migrate media files.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function import_media_files() {}
}
