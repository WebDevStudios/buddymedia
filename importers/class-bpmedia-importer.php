<?php
/**
 * Main Importer class for Buddymedia.
 *
 * @author  Kailan W.
 *
 * @since  1.0.0
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
	 * @var boolean
	 */
	public $uses_wp_media;
	/**
	 *  Constructor
	 */
	public function __construct() {}

	public function  get_users_with_media() {}
}