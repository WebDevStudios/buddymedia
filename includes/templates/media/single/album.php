<?php

	add_thickbox();

	$variables = bp_action_variables();
	
	if( ! $variables ) return;


	$attachments = get_attached_media( 'image', $variables[0] );


	foreach( $attachments as $attachment ) {
	
		$user = get_user_by( 'id', (int) $attachments[$attachment->ID]->post_author );
				
		?>
		
		<style>

			.media-thumbnail {
				float: left;
				padding: 5px;
			}
			.media-thumbnail img {
				border-radius: 0px;
				box-shadow: none;
			}
			#album-personal-li {
				display: none;
			}

		</style>
		
		<a href="<?php echo $attachments[$attachment->ID]->guid; ?>" class="thickbox">		
			<div class="media-thumbnail"><?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail' ); ?></div>
		</a>

			
		
		<?php
		
	}