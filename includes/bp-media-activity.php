<?php

/**
 * The bp_media_activity_button function.
 */
function bp_media_activity_button() {

	if( bp_is_group() && !groups_get_groupmeta( bp_get_group_id(), 'enable_media' ) && !defined('ENABLE_MEDIA') ) {
		return;
	}

	echo '<div id="plupload-upload-ui">';
		echo '<input id="plupload-browse-button" class="activity-attach-button" type="button" value="' . esc_attr__("Add Image") . '" class="button" />';
	echo '</div>';
	echo '<input type="hidden" id="bp-media-attachment-id" name="bp-media-attachment-id" value=""/>';
	echo '<input type="hidden" id="bp-media-user-id" name="bp-media-user-id" value="'. bp_loggedin_user_id() .'"/>';
}
add_action( 'bp_activity_post_form_whats-new-options', 'bp_media_activity_button' );


/**
 * The bp_media_activity_image_upload_form function.
 */
function bp_media_activity_image_upload_form() {
	bp_media_get_template_part( 'activity-upload-form');
}
add_action( 'bp_activity_post_form_content', 'bp_media_activity_image_upload_form' );



/**
 * The bp_media_register_template_location function.
 */
function bp_media_register_template_location() {
    return BP_MEDIA_DIR . '/includes/templates/';
}


/**
 * The bp_media_replace_get_post_form_template function.
 *
 * @param mixed $templates Templates.
 * @param mixed $slug      Template slug.
 * @param mixed $name      Name.
 * @return array
 */
function bp_media_replace_get_post_form_template( $templates, $slug, $name ) {

	 if ( 'activity/post-form' != $slug ) {
		 return $templates;
	 }

    return array( 'media/post-form.php' );
}


/**
 * The bp_media_replace_post_form function.
 */
function bp_media_replace_post_form() {

    if ( function_exists( 'bp_register_template_stack' ) ) {
	    bp_register_template_stack( 'bp_media_register_template_location' );
    }

    add_filter( 'bp_get_template_part', 'bp_media_replace_get_post_form_template', 10, 3 );

}
add_action( 'bp_init', 'bp_media_replace_post_form' );
