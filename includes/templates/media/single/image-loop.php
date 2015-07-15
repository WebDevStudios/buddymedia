<?php

$variables = bp_action_variables();

$attachments = get_attached_media( 'image', $variables[0] );


if( !$attachments ) {

	_e( '<p class="center">There are no images in this album.</p>', 'bp_media' );
	
} else {
	
	foreach( $attachments as $attachment ) {
	
		$user = get_user_by( 'id', (int) $attachments[$attachment->ID]->post_author );
				
		?>
		
		<a onclick="bp_media_get_image( 'pop', <?php echo $attachment->ID ?>, '<?php echo $attachments[$attachment->ID]->guid ?>', <?php echo $attachments[$attachment->ID]->post_author ?> );" data-id="<?php echo $attachment->ID; ?>">	
			<div class="media-thumbnail"><?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail' ); ?></div>
		</a>
		
		<div id="pop"></div>


<?php
		
	}
	
}