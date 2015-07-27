<?php

function bp_media_activity_button() {
	echo '<button id="bp-media-activity-upload-button">' . __( 'Add Image', 'bp_media' ) . '</button>';
	
}
//add_action( 'bp_activity_post_form_options', 'bp_media_activity_button' );
//add_action( 'bp_before_activity_post_form', 'bp_media_activity_button' );
//add_action( 'bp_after_activity_post_form', 'bp_media_activity_button' );
add_action( 'bp_activity_post_form_post_options', 'bp_media_activity_button' );


function bp_media_activity_image_upload_form() {
	bp_media_get_template_part( 'activity-upload-form');
}
add_action( 'bp_after_activity_post_form', 'bp_media_activity_image_upload_form' );