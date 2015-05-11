<?php


/**
 * bp_media_is_option function.
 * 
 * @access public
 * @param mixed $option
 * @return boolean
 */
function bp_media_is_option( $option ) {

	$option = 'bp-media-' . $option;

	$option = bp_get_option( $option );
	
	if( $option ) 
		return true;
	
	
	return;
}

