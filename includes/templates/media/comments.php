


<li id="acomment-<?php comment_ID(); ?>">
	<div class="acomment-avatar">
		<?php echo get_avatar( $comment, 32 ); ?>
	</div>

	<div class="acomment-meta">
		<?php
			$comment_id = get_comment_ID();
			$id = get_comment( $comment_id );
			$link = bp_core_get_user_domain( $id->user_id );
			
			echo sprintf( '<a href="%s">%s</a>', $link, get_comment_author() );
		?>
	</div>

	<div class="acomment-content"><?php comment_text(); ?></div>


</li>
