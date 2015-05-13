<?php $query = new WP_Query( bp_media_loop_filter() ); ?>

<div class="media no-ajax" role="main">
	<?php if ( $query->have_posts() ) : ?>

		<ul id="media-stream" class="media-list grid-list">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>

			<?php bp_media_get_template_part( 'entry' ); ?>

		<?php endwhile; ?>
		</ul>

	<?php endif; ?>
</div>