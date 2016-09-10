<?php

function bp_media_add_admin_menu() {
	add_submenu_page( 'edit.php?post_type=bp_media', 'Settings', 'Settings', 'manage_options', 'settings', 'bp_media_options_page' );
}
add_action( 'admin_menu', 'bp_media_add_admin_menu' );

function bp_media_settings_init() {

	register_setting( 'settings', 'bp_media_settings', 'bp_media_sanitize_callback' );

	/*
	 * File type options.
	 */
	add_settings_section(
		'bp_media_media_types_section',
		__( 'Media Types', 'buddymedia' ),
		'bp_media_media_types_section_callback',
		'settings'
	);
	add_settings_field(
		'bp_media_image_types',
		__( 'Allowed extensions for images', 'buddymedia' ),
		'bp_media_image_types_render',
		'settings',
		'bp_media_media_types_section'
	);
	add_settings_field(
		'bp_media_doc_types',
		__( 'Allowed extensions for docs', 'buddymedia' ),
		'bp_media_doc_types_render',
		'settings',
		'bp_media_media_types_section'
	);

	/*
	 * storage options
	 */
	add_settings_section(
		'bp_media_storage_options_section',
		__( 'Storage Options', 'buddymedia' ),
		'bp_media_storage_options_section_callback',
		'settings'
	);
	add_settings_field(
		'bp_media_file_size',
		__( 'Maximum Upload size per file(MB)?', 'buddymedia' ),
		'bp_media_file_size_render',
		'settings',
		'bp_media_storage_options_section'
	);
}
add_action( 'admin_init', 'bp_media_settings_init' );

/**
 * The bp_media_media_types_section_callback.
 *
 * Files types options.
 */
function bp_media_media_types_section_callback(  ) {
	echo __( 'Allowed media types.', 'buddymedia' );
}
		function bp_media_image_types_render(  ) {

			$options = get_option( 'bp_media_settings' );
			?>
			<input type='text' name='bp_media_settings[bp_media_image_types]' value='<?php echo esc_html( $options['bp_media_image_types'] ); ?>'>
			<?php

		}
		function bp_media_doc_types_render(  ) {

			$options = get_option( 'bp_media_settings' );
			?>
			<input type='text' name='bp_media_settings[bp_media_doc_types]' value='<?php echo esc_html( $options['bp_media_doc_types'] ); ?>'>
			<?php

		}

/**
 * The bp_media_storage_options_section_callback.
 *
 * File storage options.
 */
function bp_media_storage_options_section_callback(  ) {
	echo __( 'Allowed storage quotas.', 'buddymedia' );
}
	function bp_media_file_size_render(  ) {

		$options = get_option( 'bp_media_settings' );
		?>
		<input type='text' name='bp_media_settings[bp_media_file_size]' value='<?php echo esc_html( $options['bp_media_file_size'] ); ?>'>
		<?php

	}

function bp_media_sanitize_callback( $fields ) {

	// Initialize the new array that will hold the sanitize values.
	$input = array();
	// Loop through the input and sanitize each of the values.
	foreach ( $fields as $key => $val ) {
		switch ( $key ) {
			case 'bp_media_image_types':
				$input[ $key ] = sanitize_text_field( $val );
				break;
			case 'bp_media_doc_types':
				$input[ $key ] = sanitize_text_field( $val );
				break;
			case 'bp_media_file_size':
				$input[ $key ] = intval( sanitize_text_field( $val ) );
				break;
		}
	}
	return $input;
}

/**
 * The bp_media_options_page.
 *
 * BuddyMedia options page markup.
 */
function bp_media_options_page(  ) {

	?>
	<form action='options.php' method='post'>

		<h2>User Media</h2>

		<p>Settings for user media. Media upload by users on the front of the site.</p>

		<?php
		settings_fields( 'settings' );
		do_settings_sections( 'settings' );
		submit_button();
		?>

	</form>
	<?php

}
