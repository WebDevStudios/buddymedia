<?php
if (
	! bp_get_media_image_id() ||
	! bp_media_can_edit() ||
	! wp_get_attachment_image( bp_get_media_image_id() )
) : ?>

		<?php _e( 'Nothing to see here.', 'bp-media'); ?>

<?php else : ?>

	<div class="photo-column">
		<a id="back-album" href="<?php bp_media_image_back_url() ;?>"><?php _e( 'Back to image', 'bp_media' ) ; ?></a>
		<div id="photo" class="media-image"><?php echo wp_get_attachment_image( bp_get_media_image_id(), 'large' ); ?></div>
	</div>

	<?php if( bp_media_is_action_edit() ) : ?>

		<div class="standard-form base">
			<label><?php _e( 'Description', 'bp_media' ) ;?></label>
			<textarea id="image-description"><?php bp_media_image_description(); ?></textarea>

			<input id="image-user-id" type="hidden" value="<?php echo bp_displayed_user_id(); ?>">
			<input id="image-post-id" type="hidden" value="<?php echo bp_get_media_image_id(); ?>">
			<input id="album-user-id" type="hidden" value="<?php echo bp_displayed_user_id(); ?>">
			<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "edit-album" ); ?>">

			<div id="cleared"></div>
			<div class="submit" data-id="<?php echo bp_get_media_image_id() ?>">
				<button class="image-action-edit"><?php _e( 'Save Image', 'bp_media' ) ;?></button>
				<a class="image-action-delete error"><?php _e( 'delete', 'bp_media' ) ;?></a>
			</div>
		</div>

	<?php endif ; ?>

<?php endif ; ?>
