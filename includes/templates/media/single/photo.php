	
	<?php
	
	//var_dump($user);
		
	if( ! $photo_id ) return;

				
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
		
		<div class="media-pop-wrapper">
			<div class="photo-column">
				<div id="photo" class="media-image"><?php echo wp_get_attachment_image( $photo_id, 'large' ); ?></div>
			</div>
			<div class="comment-column">
				<div class="upload-author">
					<?php echo bp_core_fetch_avatar( 'item_id=' . $user_id); ?>
					<?php echo $user->user_nicename; ?>
				</div>
				<ul>
					<li>comments</li>
					<li>comments</li>
					<li>comments</li>
					<li>comments</li>
				</ul>
			</div>
			<div id="cleared"></div>
		</div>
