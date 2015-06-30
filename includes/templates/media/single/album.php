<?php

$variables = bp_action_variables();


if( $variables[0] === 'photo' ) {

	bp_media_get_template_part( 'single/photo');
	
} else {
		
		
	
$ajax_url = add_query_arg( 
    array( 
        'action' => 'bp_media_get_image' 
    ), 
    '/wp-admin/admin-ajax.php'
); 

?>
	

	<style>
	
	html, body {
		height: 100%;
	}

		.media-thumbnail {
			float: left;
			padding: 5px;
		}
		.media-thumbnail img {
			border-radius: 0px;
			box-shadow: none;
		}
		#album-personal-li {
			display: none;
		}
		
		#TB_window {
			overflow: hidden;
			background:	#000000 !important;
		}
				
		#TB_ajaxWindowTitle {
			display: none;
		}
		
		#TB_title {
			background:	#5c5c5c !important;
			border:	none !important;
			height: 0 !important;
		}
		
		#TB_ajaxContent {
			width: 100% !important;
			height: 100% !important;
			padding: 0 !important;
			overflow: scroll;
		}
		
		#TB_ajaxContent .media-pop-wrapper {
		  	margin-right: 400px;
		  	height: 100%;
		}
		#TB_ajaxContent .photo-column {
		  	float: left;
		  	width: 100%;
		  	height: 100%;
		}
		#TB_ajaxContent .comment-column {
			float: right;
			width: 400px;
			height: 100%;
			margin-right: -400px;
			padding: 15px;
			background-color: #ffffff;
		}
		#TB_ajaxContent #cleared {
		  	clear: both;
		}
		
		#TB_ajaxContent .media-image {
			padding: 10px;
			height: 100%;
		}
		
		#TB_ajaxContent .media-image img {
			margin: auto;
			display: table;
			max-width: 100%;
			top: 50%;
			-webkit-transform: translateY(25%);
			-ms-transform: translateY(25%);
			transform: translateY(25%);
		}
		
		#TB_ajaxContent .upload-author {
			margin: 0 0 20px 0;
		}
				
	</style>
	
	<script>
	
	function bp_media_get_image(tag, id, guid, user) {
		tb_show( tag, '<?php echo $ajax_url; ?>&id=' + id + '&guid=' + guid + '&user=' + user );
		bp_media_iframe_loaded();
	}
	

	/**
	 * Add ‘bp_media’ class to the Thickbox window. Called from inside the TB iframe.
	 */
	function bp_media_iframe_loaded() {
		jQuery('#TB_window').addClass('bp_media');
		setTimeout(function() {
			bp_media_resize_thickbox();
		}, 500);
		 
	}
	
	/**
	 * Checks how to resize the TB window. Called on window.resize.
	 */	
	function bp_media_window_resize() {
		if( jQuery('#TB_window').hasClass('bp_media') ) {
			bp_media_resize_thickbox();
		} else {
			tb_position();
		}
	}
	
	/**
	 * Resizes the TB window our way, not the highway.
	 */
	function bp_media_resize_thickbox() {

		//delete jQuery(window).data('events')['resize'];
		
		var bp_mediaWidth		= 1000;
		var TB_newWidth			= jQuery(window).width() < ( bp_mediaWidth + 40 ) ? jQuery(window).width() - 40 : bp_mediaWidth;
		var TB_newHeight		= jQuery(window).height() - 40;
		var TB_newMargin		= ( jQuery(window).width() - bp_mediaWidth ) / 2;
	
		jQuery('#TB_window').css({'marginLeft': -(TB_newWidth / 2)});
		jQuery('#TB_window').css({'marginTop': -(TB_newHeight / 2)});
		jQuery('#TB_window, #TB_iframeContent').width(TB_newWidth).height(TB_newHeight);
		jQuery('#TB_ajaxContent .media-pop-wrapper').height(TB_newHeight);
	}
	
	jQuery(document).ready(function() {
		/**
		 * window.resize event to resize modal.
	 	 */
		jQuery(window).resize( function( event ) {
				delete jQuery(window).data('events')['resize'];
				jQuery(window).bind('resize', bp_media_window_resize);
		});
	});

		
	</script>
	

	<p>
		<div class="generic-button bp-media-add-photo"><a><?php _e( 'Add Photos', 'bp_album' ); ?></a></div>
	</p>
		
		<? bp_media_get_template_part( 'upload-form' ); ?>
	

	<ul id="media-stream" class="media-list grid-list">

	<?php

	add_thickbox();


	$attachments = get_attached_media( 'image', $variables[0] );


	foreach( $attachments as $attachment ) {
	
		$user = get_user_by( 'id', (int) $attachments[$attachment->ID]->post_author );
				
		?>
		
		<a onclick="bp_media_get_image( 'pop', <?php echo $attachment->ID ?>, '<?php echo $attachments[$attachment->ID]->guid ?>', <?php echo $attachments[$attachment->ID]->post_author ?> );" href="#" data-id="<?php echo $attachment->ID; ?>">	
			<div class="media-thumbnail"><?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail' ); ?></div>
		</a>
		
		<div id="pop"></div>

	</ul>
	

		<?php
		
	}
	
}
