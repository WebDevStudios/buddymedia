<?php $query = new WP_Query( bp_media_loop_filter() ); ?>

<div class="media no-ajax" role="main">

	<ul id="media-stream" class="media-list grid-list">
			
		<?php if ( $query->have_posts() ) : ?>
			
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	
				<?php bp_media_get_template_part( 'album' ); ?>
	
			<?php endwhile; ?>
			
	
		<?php endif; ?>
	
	</ul>
</div>