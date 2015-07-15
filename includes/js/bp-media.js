function bp_media_get_image(tag, id, guid, user) {
	tb_show( tag, ajaxurl + '?action=bp_media_get_image&id=' + id + '&guid=' + guid + '&user=' + user );
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


function bp_media_createAlbum(tag, link) {
	tb_show( tag, link );
}

function getURLParameter(name) {
  return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
}

jQuery(document).ready(function() {
	/**
	 * window.resize event to resize modal.
 	 */
	jQuery(window).resize( function( event ) {
			delete jQuery(window).data('events')['resize'];
			jQuery(window).bind('resize', bp_media_window_resize);
	});
	
	jQuery( "#bp-media-add-photo" ).click(function() {     
    	jQuery('#plupload-upload-ui').slideToggle();        
	});
	
	jQuery('#create-album').on( 'click', function( event ) {
					
		jQuery.ajax({
		   url: ajaxurl,
		   data: {
		      'action':'bp_media_ajax_create_album',
		      'title': jQuery('#album-title').val(),
		      'description': jQuery('#album-description').val(),
		      'user_id': jQuery('#album-user-id').val(),
		      'nonce': jQuery('#nonce').val()
		   },
		   error: function() {
		     alert('nope');
		   },
		   success: function(data) {
		   	console.log(data);
		   	window.location = data.url;
		   }
		});
		
	});
	
	jQuery('#edit-album').on( 'click', function( event ) {
					
		jQuery.ajax({
		   url: ajaxurl,
		   data: {
		      'action':'bp_media_ajax_edit_album',
		      'title': jQuery('#album-title').val(),
		      'description': jQuery('#album-description').val(),
		      'user_id': jQuery('#album-user-id').val(),
		      'post_id': jQuery('#album-post-id').val(),
		      'nonce': jQuery('#nonce').val()
		   },
		   error: function() {
		     alert('nope');
		   },
		   success: function(data) {
		   	console.log(data);
		   	window.location = data.url;
		   }
		});
		
	});
	


	jQuery('#delete-album').on( 'click', function( event ) {
	
		if( confirm('Are you sure you want to delete this album?') ) { 
						
			jQuery.ajax({
			   url: ajaxurl,
			   data: {
			      'action':'bp_media_ajax_delete_album',
			      'user_id': jQuery('#album-user-id').val(),
			      'post_id': jQuery('#album-post-id').val(),
			      'nonce': jQuery('#nonce').val()
			   },
			   error: function() {
			     alert('nope');
			   },
			   success: function(data) {
			   	console.log(data);
			   	window.location = data.url;
			   }
			});
			
		}
		
	});
	
	jQuery('body').on( 'click', 'button#upload-comment-reply', function( event ) {
	
		var comment = jQuery('#upload-comment').val();
		
		console.log(comment);
	
		if( comment !== '' ) { 
						
			jQuery.ajax({
			   url: ajaxurl,
			   data: {
			      'action':'bp_media_ajax_add_comment',
			      'user_id': jQuery('#upload-user-id').val(),
			      'post_id': jQuery('#upload-post-id').val(),
			      'upload_comment': comment,
			      'nonce': jQuery('#nonce').val()
			   },
			   error: function() {
			     alert('nope');
			   },
			   success: function(data) {
			   	console.log(data);
			   	jQuery('ul.commentlist').prepend(data);
			   }
			});
			
		}
		
	});
	
	if( getURLParameter('new') ) {
		jQuery('#plupload-upload-ui').slideToggle(); 
	}
	
});