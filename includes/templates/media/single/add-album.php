<?php if ( ! is_user_logged_in() ) {
	return;
} ?>

<div class="standard-form base">
	<div class="bp-media-admin-album">
		<?php
		/**
		 * Fires after the album post form.
		 *
		 * @since 1.0.2
		 */
		do_action( 'bp_media_before_album_post_form' ); ?>

		<label><?php esc_html_e( 'Album Title (required)', 'bp_media' ) ;?></label>
		<input id="album-title" type="text">

		<label><?php esc_html_e( 'Description', 'bp_media' ) ;?></label>
		<textarea id="album-description"></textarea>

		<label><?php esc_html_e( 'Permission', 'bp_media' ) ;?></label>
		<input type="radio" name="permission" value="public"/><?php esc_html_e( 'Public', 'bp_media' ) ; ?><br />
		<?php if ( bp_is_active( 'friends' ) ) : ?>
			<input type="radio" name="permission" value="friend"/><?php esc_html_e( 'Friends', 'bp_media' ) ; ?><br />
		<?php endif ; ?>
		<input type="radio" name="permission" value="private"/><?php esc_html_e( 'Private', 'bp_media' ) ; ?><br />
		<?php
		/**
		 * Fires after the album post form.
		 *
		 * @since 1.0.2
		 */
		do_action( 'bp_media_after_album_post_form' ); ?>
		<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
		<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "create-album" ); ?>">

		<div id="cleared"></div>
		<div class="submit">
			<button id="create-album"><?php esc_html_e( 'Create Album', 'bp_media' ) ;?></button>
		</div>
	</div>
</div>
