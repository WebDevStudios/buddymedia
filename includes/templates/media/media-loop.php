<?php $query = new WP_Query( bp_media_loop_filter() ); add_thickbox(); ?>

<div class="media no-ajax" role="main">

	<ul id="media-stream" class="media-list grid-list">
	
		<?php if( bp_media_can_edit() ) : ?>
			<li class="<?php bp_media_css_class(); ?> create-album" id="media-<?php bp_media_css_id(); ?>">
			
				<a href="<?php bp_media_create_album_link() ; ?>">
					
					<div class="album-title"><?php _e( '+ Create Album', 'bp_album'); ?></div>
					
				</a>
				
			</li>
		<?php endif ; ?>
		
		<?php if ( $query->have_posts() ) : ?>
			
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	
				<?php bp_media_get_template_part( 'album' ); ?>
	
			<?php endwhile; ?>
			
		<?php endif; ?>
	
	</ul>
	
	<div id="pop"></div>
</div>