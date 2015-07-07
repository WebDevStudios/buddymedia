<div class="bp-media-admin-album">
	<label><?php _e( 'Album Title (required)', 'bp_album' ) ;?></label>
	<input id="album-title" type="text" value="<?php bp_media_album_field('title') ;?>">

	<label><?php _e( 'Description', 'bp_album' ) ;?></label>
	<textarea id="album-description"><?php bp_media_album_field('title') ;?></textarea>
	
	<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
	
	<div id="cleared"></div>
	<button id="create-album"><?php _e( 'Save Album', 'bp_album' ) ;?></button>
</div>