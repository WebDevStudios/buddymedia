<?php if ( ! bp_get_media_album_id() || ! bp_media_can_edit() ) : ?>

		<?php _e( 'Nothing to see here.', 'bp-media'); ?>
		
<?php else : ?>

	<div class="bp-media-admin-album">
	
		<a id="back-album" href="<?php bp_media_album_back_url() ;?>"><?php _e( 'back to album', 'bp_media' ) ; ?></a>
		
		<h3><?php _e( 'Editing ', 'bp_media' ); ; ?><?php bp_media_album_field( 'title' ) ;?></h3>
			
		<?php if( bp_media_is_action_edit() ) : ?>
		
			<div class="standard-form base">
				<label><?php _e( 'Album Title (required)', 'bp_media' ) ;?></label>
				<input id="album-title" type="text" value="<?php bp_media_album_field('title') ;?>">
			
				<label><?php _e( 'Description', 'bp_media' ) ;?></label>
				<textarea id="album-description"><?php bp_media_album_field('description') ;?></textarea>
				
				<label><?php _e( 'Permission', 'bp_media' ) ;?></label>
				<input type="radio" name="permission" value="public" <?php bp_media_album_permission('public') ;?>/><?php _e( 'Public', 'bp_media' ) ; ?><br />
				<?php if ( bp_is_active( 'friends' ) ) : ?>
					<input type="radio" name="permission" value="friend" <?php bp_media_album_permission('friend') ;?>/><?php _e( 'Friends', 'bp_media' ) ; ?><br />
				<?php endif ; ?>
				<input type="radio" name="permission" value="private" <?php bp_media_album_permission('private') ;?>/><?php _e( 'Private', 'bp_media' ) ; ?><br />
				
				<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
				<input id="album-post-id" type="hidden" value="<?php echo bp_media_album_id(); ?>">
				<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "edit-album" ); ?>">
				
				<div id="cleared"></div>
				<div class="submit">
					<button id="edit-album"><?php _e( 'Save Album', 'bp_media' ) ;?></button>
					<a id="delete-album" class="error"><?php _e( 'Delete Album', 'bp_media' ) ;?></a>
				</div>
			</div>
			
			<div id="cleared"></div>
			<h3><?php _e( 'Edit or Delete Images', 'bp_media' ); ; ?></h3>
			<?php bp_media_get_template_part('single/image-loop') ; ?>
	</div>
		
		<?php endif ; ?>

<?php endif ; ?>
