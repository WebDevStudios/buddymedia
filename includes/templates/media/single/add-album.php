<?php if( !is_user_logged_in() ) return; ?>

<div class="standard-form base">
	<div class="bp-media-admin-album">
		<label><?php _e( 'Album Title (required)', 'bp_media' ) ;?></label>
		<input id="album-title" type="text">
	
		<label><?php _e( 'Description', 'bp_media' ) ;?></label>
		<textarea id="album-description"></textarea>
		
		<label><?php _e( 'Permission', 'bp_media' ) ;?></label>
		<input type="radio" name="permission" value="public"/> Public<br />
		<input type="radio" name="permission" value="friends"/> Friends<br />
		<input type="radio" name="permission" value="private"/> Private<br />
		
		<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
		<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "create-album" ); ?>">
		
		<div id="cleared"></div>
		<div class="submit">
			<button id="create-album"><?php _e( 'Create Album', 'bp_media' ) ;?></button>
		</div>
	</div>
</div>