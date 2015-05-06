<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * BP Attachment class to manage your avatar upload needs
 *
 * @since BuddyPress (2.3.0)
 */
class Buddypics_Attachment_Gallery extends BP_Attachment {

	public function __construct() {
	
		parent::__construct( array(
			// Upload action
			'action' => 'buddypics_upload',

			// Specific errors for avatars
			'upload_error_strings'  => array(
				sprintf( __( 'That photo is too big. Please upload one smaller than %s', 'buddypress' ), size_format( bp_core_avatar_original_max_filesize() ) ),
				__( 'Please upload only JPG, GIF or PNG photos.', 'buddypress' ),
			),
		) );
	}
	
	


}