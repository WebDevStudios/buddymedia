<?php
/**
 * RTMedia Importer class for Buddymedia.
 *
 * @author  Kailan W.
 *
 * @since  1.0.0
 */
class BuddyMedia_Rtmedia_Importer extends BuddyMedia_Importer {

	public function __construct() {
		parent::__construct();
	}
	/**
	 * Get users with media items.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function get_users_with_media() {
		global $wpdb;
		$media = $wpdb->results( 'SELECT ' );
		return $media;
	}

	/**
	 * Get an array of media item by user ID.
	 *
	 * @author  Kailan W.
	 *
	 * @since  1.0.2
	 */
	public function get_media_by_user( $user_id = 0 ) {}
}
new BuddyMedia_Rtmedia_Importer();