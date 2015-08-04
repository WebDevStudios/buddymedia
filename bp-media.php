<?php
/*
Plugin Name: BP Media Component
Plugin URI: https://github.com/WebDevStudios/WDS-BP-Media
Description: Media component for BuddyPress.
Version: 1.0
Tested up to: 4.2
Requires at least: 3.9
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: WebDevStudios
Author URI: https://webdevstudios.com
Network: True
Text Domain: bp_media
*/


// Exit if accessed directly
defined( 'ABSPATH' ) || exit;


/** Constants *****************************************************************/

if ( !class_exists( 'BP_Media' ) ) :

	/**
	 * Main BP_Media Class
	 *
	 */
	class BP_Media {


	/**
	 * instance function.
	 *
	 * @access public
	 * @static
	 * @return $instance
	 */
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been run previously
		if ( null === $instance ) {
			$instance = new BP_Media;
			$instance->constants();
			$instance->includes();
			$instance->setup_actions();
		}

		// Always return the instance
		return $instance;

		// The last transport is away! Rebel scum.
	}


	/**
	 * __construct function.
	 *
	 * @access private
	 * @return void
	 */
	private function __construct() { /* Do nothing here */ }


	/**
	 * Magic method to prevent notices and errors from invalid method calls.
	 *
	 * @access public
	 * @return void
	 */
	public function __call( $name = '', $args = array() ) { unset( $name, $args ); return null; }



	/**
	 * constants function.
	 *
	 * @access private
	 * @return void
	 */
	private function constants() {

		// Path and URL
		if ( ! defined( 'BP_MEDIA_PLUGIN_DIR' ) ) {
			define( 'BP_MEDIA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		if ( ! defined( 'BP_MEDIA_PLUGIN_URL' ) ) {
			define( 'BP_MEDIA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

	}


	/**
	 * includes function.
	 *
	 * @access private
	 * @return void
	 */
	private function includes() {

		require( dirname( __FILE__ ) . '/includes/bp-media-admin-settings.php' );
		require( dirname( __FILE__ ) . '/includes/bp-media-function.php' );
		require( dirname( __FILE__ ) . '/includes/bp-attachments-media.php' );
		require( dirname( __FILE__ ) . '/includes/bp-media-cpt.php' );
		require( dirname( __FILE__ ) . '/includes/bp-media-loader.php' );
		require( dirname( __FILE__ ) . '/includes/bp-media-ajax.php' );
		require( dirname( __FILE__ ) . '/includes/bp-media-activity.php' );

	}


	/**
	 * setup_actions function.
	 *
	 * @access private
	 * @return void
	 */
	private function setup_actions() {
		add_action( 'plugins_loaded', array( $this, 'bp_media_bp_check' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}



	/**
	 * enqueue_scripts function.
	 *
	 * @access public
	 * @return void
	 */
	public function enqueue_scripts() {

		if ( bp_current_component('media') ) {

			wp_enqueue_style( 'bp-media-css', plugins_url( 'includes/css/bp-media.css' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/css/bp-media.css' ) );
			wp_enqueue_script( 'bp-media-js', plugins_url( 'includes/js/bp-media.js' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/js/bp-media.js' ) );

		}

	}



	/**
	 * bp_media_bp_check function.
	 *
	 * @access public
	 * @return void
	 */
	public function bp_media_bp_check() {
		if ( !class_exists('BuddyPress') ) {
			add_action( 'admin_notices', array( $this, 'bp_media_install_buddypress_notice' ) );
		}
	}



	/**
	 * bp_media_install_buddypress_notice function.
	 *
	 * @access public
	 * @return void
	 */
	public function bp_media_install_buddypress_notice() {
		echo '<div id="message" class="error fade"><p style="line-height: 150%">';
		_e('<strong>BP Media</strong></a> requires the BuddyPress plugin to work. Please <a href="http://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate BP Media</a>.');
		echo '</p></div>';
	}


}

endif; // end of line...


/**
 * bpmedia function.
 *
 * fire bpmedia instance method
 *
 * @access public
 * @return void
 */
function bpmedia() {
	return BP_Media::instance();
}
add_action( 'bp_include', 'bpmedia');




/**
 * enqueue_scripts function.
 *
 * @access public
 * @return void
 */
function bp_media_enqueue_admin_scripts() {
	wp_enqueue_script( 'bp-media-admin-js', plugins_url( 'includes/js/bp-media-admin.js' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/js/bp-media-admin.js' ), true );
}
add_action( 'admin_enqueue_scripts', 'bp_media_enqueue_admin_scripts' );



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