<div class="bp-media-admin-album">
	<a id="back-album" href="<?php bp_media_album_back_url() ;?>"><?php _e( 'Back to Album', 'bp_media' ) ; ?></a>

	<label><?php _e( 'Album Title (required)', 'bp_media' ) ;?></label>
	<input id="album-title" type="text" value="<?php bp_media_album_field('title') ;?>">

	<label><?php _e( 'Description', 'bp_media' ) ;?></label>
	<textarea id="album-description"><?php bp_media_album_field('description') ;?></textarea>
	
	<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
	<input id="album-post-id" type="hidden" value="<?php echo bp_media_album_id(); ?>">
	<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "edit-album" ); ?>">
	
	<div id="cleared"></div>
	<button id="edit-album"><?php _e( 'Save Album', 'bp_media' ) ;?></button>
	<a id="delete-album" class="error"><?php _e( 'Delete Album', 'bp_media' ) ;?></a>
</div>