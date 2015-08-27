<?php 
$action_var = bp_action_variables();
$photo_id = $action_var[0];
$post = get_post( $photo_id );

if( !$photo_id ) return; ?>

<div class="media-pop-wrapper">
	<div class="photo-column">
		<div class="image-options">
			<a href="<?php bp_media_album_back_url( $post->post_parent ) ;?>" class="left"><?php _e( 'back to album', 'bp_media' ) ; ?></a>
			<?php if ( bp_media_user_can_access() ) : ?>
			<a href="<?php bp_media_edit_image_link(); ?>" class="right"><?php _e( 'edit', 'bp_media' ) ;?></a>
			<?php endif; ?>
		</div>
		<div id="photo" class="media-image"><?php echo wp_get_attachment_image( $photo_id, 'large' ); ?></div>
	</div>
	<div class="comment-column">
		<div class="upload-author">
			<div class="upload-author-avatar"><?php echo bp_core_fetch_avatar( 'item_id=' . $post->post_author ); ?></div>
			<div class="upload-author-username">
				<?php echo bp_core_get_username( $post->post_author ) ; ?>
				<span class="upload-time-since">  <?php echo bp_media_time_since( $photo_id ) ; ?></span>
			</div>
			<div id="cleared"></div>
			<div class="upload-description"><p><?php echo bp_media_image_description( $photo_id ) ; ?></p></div>
			
			<div class="upload-posted-in"><?php bp_media_posted_in() ; ?></div>
		</div>
		
		<div class="activity-comments">
			<ul class="has-comments commentlist">
			    <?php    
			        //Gather comments for a photo 
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
			        ), $comments );
			
			    ?>
			</ul>
		</div>
		<?php if( bp_media_user_can_access() ) : ?>
		<div class="image-reply-form">
			<input id="upload-comment" type="text">
			<input id="upload-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
			<input id="upload-post-id" type="hidden" value="<?php echo $photo_id; ?>">
			<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "add-comment" ); ?>">
			<button id="upload-comment-reply"><?php _e( 'reply', 'bp_media' ) ;?></button>
		</div>
		<?php endif ; ?>
	</div>
	<div id="cleared"></div>
</div>