<li id="div-comment-<?php comment_ID() ?>" class="comment-body">

	<div class="comment-author vcard">
	<?php echo get_avatar( $comment, 32 ); ?>
		<div class="comment-author-username"><?php printf( __( '%s' ), get_comment_author_link() ); ?></div>
		<?php comment_text(); ?>
	</div>
	<?php if ( $comment->comment_approved == '0' ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
		<br />
	<?php endif; ?>

</li>