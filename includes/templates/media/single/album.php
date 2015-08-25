<?php if( bp_media_can_edit() ) : ?>

	<div class="bp-media-buttons">
		<div id="bp-media-add-photo" class="generic-button">
			<a><?php _e( 'Add Photos', 'bp_media' ); ?></a>
		</div>
		<div id="bp-media-edit-album" class="generic-button">
			<a href="<?php bp_media_edit_album_link() ;?>" ><?php _e( 'Edit Album', 'bp_media' ); ?></a>
		</div>
	</div>
	
	<?php bp_media_get_template_part( 'upload-form' ); ?>

<?php endif ; ?>

<h3 class="album-title"><?php bp_media_album_field( 'title' ) ;?><span class="album-type"><?php bp_media_album_meta( 'permission' ) ;?></span></h3>


<p class="album-description"><?php bp_media_album_field( 'description' ) ;?></p>


<ul id="media-stream" class="media-list grid-list">

	<?php
	
	add_thickbox();
	
	bp_media_get_template_part( 'single/image-loop' );
	
	?>

</ul>