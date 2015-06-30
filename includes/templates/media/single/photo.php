<?php if( !$photo_id ) return; ?>

<div class="media-pop-wrapper">
	<div class="photo-column">
		<div id="photo" class="media-image"><?php echo wp_get_attachment_image( $photo_id, 'large' ); ?></div>
	</div>
	<div class="comment-column">
		<div class="upload-author">
			<div class="upload-author-avatar"><?php echo bp_core_fetch_avatar( 'item_id=' . $user_id ); ?></div>
			<div class="upload-author-username"><?php echo $user->user_nicename; ?></div>
			<?php var_dump($post); ?>
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
		<div class="image-reply-form"><input type="text"><button>reply</button></div>
	</div>
	<div id="cleared"></div>
</div>