<?php

/**
 * BuddyPress - Media Stream (Single Item)
 *
 * This template is used by media-loop.php and AJAX functions to show
 * each gallery.
 *
 */

?>

<?php

/**
 * Fires before the display of an activity entry.
 *
 */
do_action( 'bp_media_before_entry' ); ?>

<li class="<?php bp_media_css_class(); ?>" id="media-<?php bp_media_css_id(); ?>">

	<a href="<?php bp_media_album_link(); ?>">
	
		<h2><?php the_title(); ?></h2>
		
	</a>
	
</li>