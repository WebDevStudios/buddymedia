<?php $query = new WP_Query( bp_media_loop_filter() ); ?>

<div class="media no-ajax" role="main">

	<ul id="media-stream" class="media-list grid-list">
			
		<?php if ( $query->have_posts() ) : ?>
			
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
	
				<?php bp_media_get_template_part( 'album-index' ); ?>
	
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