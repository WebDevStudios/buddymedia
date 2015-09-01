<?php $query = new WP_Query( bp_media_loop_filter() ); ?>

<div class="media no-ajax" role="main">

	<ul id="media-stream" class="media-list grid-list">
			
		<?php if ( $query->have_posts() ) : ?>
			
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
							
				<a href="<?php bp_media_image_link(get_the_ID()) ; ?>" data-id="<?php echo get_the_ID(); ?>">	
					<div class="media-thumbnail">
				
						<?php echo wp_get_attachment_image( get_the_ID(), 'thumbnail' ); ?>
						
						<?php if( bp_media_is_action_edit() ) : ?>
							<div class="image-action-links" data-id="<?php echo get_the_ID(); ?>">
								<a href="<?php bp_media_edit_image_link( get_the_ID() ); ?>" class="image-action-edit"><?php _e( 'edit', 'bp_media' ) ;?></a> 
								<a class="image-action-delete error"><?php _e( 'delete', 'bp_media' ) ;?></a>
							</div>
						<?php endif ; ?>
						
					</div>
				</a>
	
			<?php endwhile; ?>
			
	
		<?php endif; ?>
	
	</ul>


</div>

<div id="pag-bottom" class="pagination">

	<div class="pag-count" id="group-dir-count-bottom">

		<?php bp_media_pagination_count( $query ); ?>

	</div>

	<div class="pag-links" id="group-dir-pag-bottom">

		<?php bp_media_pagination_links( $query ); ?>

	</div>

</div>