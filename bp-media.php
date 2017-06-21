<?php
/*
Plugin Name: BuddyMedia
Plugin URI: https://github.com/WebDevStudios/WDS-BP-Media
Description: Media component for BuddyPress from WebDevStudios.
Version: 1.0.1
Tested up to: 4.7
Requires at least: 3.9
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Author: WebDevStudios
Author URI: https://webdevstudios.com
Text Domain: bp-media
*/


// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


if ( ! class_exists( 'BP_Media' ) ) :

	/**
	 * Main BP_Media Class
	 */
	class BP_Media {


		/**
		 * The instance function.
		 *
		 * @access public
		 * @static
		 * @return object $instance
		 */
		public static function instance() {

			// Store the instance locally to avoid private static replication.
			static $instance = null;

			// Only run these methods if they haven't been run previously.
			if ( null === $instance ) {
				$instance = new BP_Media;
				$instance->constants();
				$instance->libs();
				$instance->includes();
				$instance->setup_actions();

				define( 'BP_MEDIA_DIR', dirname( __FILE__ ) );

			}

			// Always return the instance.
			return $instance;

			// The last transport is away! Rebel scum.
		}


		/**
		 * __construct function.
		 *
		 * @access private
		 */
		private function __construct() {
			/* Do nothing here */ }


		/**
		 * Magic method to prevent notices and errors from invalid method calls.
		 *
		 * @access public
		 */
		public function __call( $name = '', $args = array() ) {
			unset( $name, $args );
			return null; }

		/**
		 * The constants function.
		 *
		 * @access private
		 */
		private function constants() {

			// Path and URL.
			if ( ! defined( 'BP_MEDIA_PLUGIN_DIR' ) ) {
				define( 'BP_MEDIA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'BP_MEDIA_PLUGIN_URL' ) ) {
				define( 'BP_MEDIA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

		}


		/**
		 * The includes function.
		 *
		 * @access private
		 * @return void
		 */
		private function includes() {

			require( dirname( __FILE__ ) . '/includes/bp-media-admin-settings.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-function.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-cpt.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-loader.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-ajax.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-activity.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-library-filter.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-groups.php' );
			require( dirname( __FILE__ ) . '/includes/bp-media-options.php' );

			$this->ajax = new BP_Media_AJAX();
		}

		/**
		 * The libs function.
		 *
		 * @access private
		 * @return void
		 */
		private function libs() {

			if ( file_exists( __DIR__ . '/vendor/CMB2/init.php' ) ) {
				require_once  __DIR__ . '/vendor/CMB2/init.php';
			}
		}


		/**
		 * The setup_actions function.
		 *
		 * @access private
		 */
		private function setup_actions() {
			add_action( 'plugins_loaded', array( $this, 'bp_media_bp_check' ), 999 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		}



		/**
		 * The enqueue_scripts function.
		 *
		 * @access public
		 */
		public function enqueue_scripts() {

			wp_enqueue_script('plupload-all');

			wp_register_script( 'bp-media-js', plugins_url( 'includes/js/bp-media.js' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/js/bp-media.js' ) );

			// Localize the script with new data.
			$translation_array = array(
			'bp_media_ajax_create_album_error' => __( 'Error creating album', 'bp-media' ),
			'bp_media_ajax_delete_album_error' => __( 'Error deleteing album', 'bp-media' ),
			'bp_media_ajax_edit_album_error' => __( 'Error editing album', 'bp-media' ),
			);
			wp_localize_script( 'bp-media-js', 'bp_media', $translation_array );

			wp_enqueue_script( 'bp-media-js' );

			wp_enqueue_style( 'bp-media-css', plugins_url( 'includes/css/bp-media.css' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/css/bp-media.css' ) );

		}



		/**
		 * The bp_media_bp_check function.
		 *
		 * @access public
		 */
		public function bp_media_bp_check() {
			if ( ! class_exists( 'BuddyPress' ) ) {
				add_action( 'admin_notices', array( $this, 'bp_media_install_buddypress_notice' ) );
			}
		}



		/**
		 * The bp_media_install_buddypress_notice function.
		 *
		 * @access public
		 */
		public function bp_media_install_buddypress_notice() {
			echo '<div id="message" class="error fade"><p style="line-height: 150%">';
			_e( '<strong>BP Media</strong></a> requires the BuddyPress plugin to work. Please <a href="http://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate BP Media</a>.' );
			echo '</p></div>';
		}

	}
endif; // End of line...


/**
 * The bpmedia function.
 *
 * Fire bpmedia instance method
 *
 * @return object
 */
function buddymedia() {
	return BP_Media::instance();
}
add_action( 'bp_include', 'buddymedia', 999 );




/**
 * The enqueue_scripts function.
 */
function bp_media_enqueue_admin_scripts() {
	wp_enqueue_script( 'bp-media-admin-js', plugins_url( 'includes/js/bp-media-admin.js' , __FILE__ ), array(), filemtime( plugin_dir_path( __FILE__ ) . 'includes/js/bp-media-admin.js' ), true );
}
add_action( 'admin_enqueue_scripts', 'bp_media_enqueue_admin_scripts' );
