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
Text Domain: buddypress
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
			require( dirname( __FILE__ ) . '/includes/bp-media-loader.php' );
			require( dirname( __FILE__ ) . '/includes/bp-attachments-media.php' );
			

		}
		
		
		/**
		 * setup_actions function.
		 * 
		 * @access private
		 * @return void
		 */
		private function setup_actions() {
			add_action( 'plugins_loaded', array( $this, 'bp_media_bp_check' ), 999 );
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