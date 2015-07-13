<?php if( !$photo_id ) return; ?>

<div class="media-pop-wrapper">
	<div class="photo-column">
		<div id="photo" class="media-image"><?php echo wp_get_attachment_image( $photo_id, 'large' ); ?></div>
	</div>
	<div class="comment-column">
		<div class="upload-author">
			<div class="upload-author-avatar"><?php echo bp_core_fetch_avatar( 'item_id=' . $user_id ); ?></div>
			<div class="upload-author-username"><?php echo bp_core_get_username( $user_id ) ; ?></div>
			<div class="upload-time-since"><?php echo bp_media_time_since( $photo_id ) ; ?></div>
		</div>

		<ul class="commentlist">
		    <?php    
		        //Gather comments for a specific page/post 
		        $comments = get_comments(array(
		            'post_id' => $photo_id,
		            'status' => 'approve' //Change this to the type of comments to be displayed
		        ));
		        
		        //Display the list of comments
		        wp_list_comments(array(
		        	'type' => 'comment',
		        	'callback' => 'bp_media_comments',
		            'per_page' => 10, //Allow comment pagination
		            'reverse_top_level' => false //Show the latest comments at the top of the list
		        ), $comments);
		
		    ?>
		</ul>
		<div class="image-reply-form">
			<input id="upload-comment" type="text">
			<input id="upload-user-id" type="hidden" value="<?php echo $user_id; ?>">
			<input id="upload-post-id" type="hidden" value="<?php echo $photo_id; ?>">
			<button id="upload-comment-reply"><?php _e( 'reply', 'bp_media' ) ;?></button>
		</div>
	</div>
	<div id="cleared"></div>
</div>