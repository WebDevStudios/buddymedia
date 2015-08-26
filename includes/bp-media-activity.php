<?php

/**
 * bp_media_activity_button function.
 * 
 * @access public
 * @return void
 */
function bp_media_activity_button() {
	echo '<div id="plupload-upload-ui">';
		echo '<input id="plupload-browse-button" class="activity-attach-button" type="button" value="' . esc_attr__("Add Image") . '" class="button" />';	
	echo '</div>';
	echo '<input type="hidden" id="bp-media-attachment-id" name="bp-media-attachment-id" value=""/>';
	echo '<input type="hidden" id="bp-media-user-id" name="bp-media-user-id" value="'. bp_loggedin_user_id() .'"/>';
}
add_action( 'bp_activity_post_form_whats-new-options', 'bp_media_activity_button' );


/**
 * bp_media_activity_image_upload_form function.
 * 
 * @access public
 * @return void
 */
function bp_media_activity_image_upload_form() {
	bp_media_get_template_part( 'activity-upload-form');
}
add_action( 'bp_after_activity_post_form', 'bp_media_activity_image_upload_form' );