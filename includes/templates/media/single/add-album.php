<div class="bp-media-admin-album">
	<label><?php _e( 'Album Title (required)', 'bp_media' ) ;?></label>
	<input id="album-title" type="text">

	<label><?php _e( 'Description', 'bp_media' ) ;?></label>
	<textarea id="album-description"></textarea>
	
	<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
	<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "create-album" ); ?>">
	
	<div id="cleared"></div>
	<button id="create-album"><?php _e( 'Create Album', 'bp_media' ) ;?></button>
</div>