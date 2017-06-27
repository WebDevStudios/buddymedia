// pop image into thickbox
function bp_media_get_image(tag, id, guid, user) {
	tb_show( tag, ajaxurl + '?action=bp_media_get_image&id=' + id + '&guid=' + guid + '&user=' + user );
	bp_media_iframe_loaded();
}


/**
 * Add ‘bp_media’ class to the Thickbox window. Called from inside the TB iframe.
 */
function bp_media_iframe_loaded() {
	if( jQuery('#TB_window') ) {
		jQuery('#TB_window').addClass('bp_media');
		setTimeout(function() {
			//bp_media_resize_thickbox();
		}, 500);
	}
}

/**
 * Checks how to resize the TB window. Called on window.resize.
 */
function bp_media_window_resize() {
	if( jQuery('#TB_window') ) {
		if( jQuery('#TB_window').hasClass('bp_media') ) {
			//bp_media_resize_thickbox();
		} else {
			//tb_position();
		}
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



// ajax functions
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

	jQuery( "#bp-media-activity-upload-button" ).click(function(e) {
		e.preventDefault();
    	jQuery('#plupload-upload-ui').slideToggle();
	});

	jQuery('#create-album').on( 'click', function( event ) {

		if( jQuery.trim( jQuery("#album-title").val() ) === '' ) {
			alert('Title required');
			return false;
		}

		jQuery.ajax({
		   url: ajaxurl,
		   data: {
		      'action':'bp_media_ajax_create_album',
		      'title': jQuery('#album-title').val(),
		      'description': jQuery('#album-description').val(),
		      'permission': jQuery('input[name=permission]:radio:checked').val(),
		      'user_id': jQuery('#album-user-id').val(),
		      'nonce': jQuery('#nonce').val()
		   },
		   error: function() {
		     alert(bp_media.bp_media_ajax_create_album_error);
		   },
		   success: function(data) {
		   	console.log(data);
		   	if(data.url) window.location = data.url;
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
		      'permission': jQuery('input[name=permission]:radio:checked').val(),
		      'user_id': jQuery('#album-user-id').val(),
		      'post_id': jQuery('#album-post-id').val(),
		      'nonce': jQuery('#nonce').val()
		   },
		   error: function() {
		     alert(bp_media.bp_media_ajax_edit_album_error);
		   },
		   success: function(data) {
		   	console.log(data);
		   	if(data.url) window.location = data.url;
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
			     alert(bp_media.bp_media_ajax_create_album_error);
			   },
			   success: function(data) {
			   	console.log(data);
			   	if(data.url) window.location = data.url;
			   }
			});

		}

	});


	jQuery('.image-action-delete').on( 'click', function( event ) {

		var id = jQuery( event.target ).parent().data('id');
		var that = this;

		if( confirm('Are you sure you want to delete this image?') ) {

			jQuery.ajax({
			   url: ajaxurl,
			   data: {
			      'action':'bp_media_ajax_delete_image',
			      'user_id': jQuery('#album-user-id').val(),
			      'image_id': id,
			      'nonce': jQuery('#nonce').val()
			   },
			   error: function() {
			     alert('nope');
			   },
			   success: function(data) {
			   	console.log(data);

			   	if( jQuery(that).parent().hasClass('submit') ) {
				   	if(data.url) window.location = data.url;
			   	} else {
				   	jQuery(that).parent().parent().slideUp(300);
			   	}


			   }
			});

		}

	});

	jQuery('body').on( 'click', '.activity-remove-attachment', function( event ) {

		var id = jQuery( '#bp-media-attachment-id' ).val();
		var that = this;

		if( confirm('Are you sure you want to remove this image?') ) {

			jQuery.ajax({
			   url: ajaxurl,
			   data: {
			      'action':'bp_media_ajax_delete_image',
			      'user_id': jQuery('#bp-media-user-id').val(),
			      'image_id': id,
			      'nonce': jQuery('#nonce').val()
			   },
			   error: function() {
			     alert('nope');
			   },
			   success: function(data) {
			   	console.log(data);
				jQuery(that).parent().slideUp(300);
				jQuery( '#bp-media-attachment-id' ).val('');
			   }
			});

		}

	});

	jQuery('.image-action-edit').on( 'click', function( event ) {

		jQuery.ajax({
		   url: ajaxurl,
		   data: {
		      'action':'bp_media_ajax_edit_image',
		      'user_id': jQuery('#image-user-id').val(),
		      'image_id': jQuery('#image-post-id').val(),
		      'description': jQuery('#image-description').val(),
		      'nonce': jQuery('#nonce').val()
		   },
		   error: function() {
		     alert('nope');
		   },
		   success: function(data) {
		   	console.log(data);
		   	if(data.url) window.location = data.url;
		   }
		});

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
			     alert('Sorry, error posting comment.');
			   },
			   success: function(data) {
			   	console.log(data);
			   	jQuery('#upload-comment').val('');
			   	jQuery('ul.commentlist').prepend(data);
			   }
			});

		}

	});

	jQuery('body').on( 'click', '.comment-options a.delete', function( event ) {

		event.preventDefault();

		var comment_id = jQuery(event.target).parent().parent().data('id');
		var user_id = jQuery('#upload-user-id').val();
		var that = this;

		console.log(comment_id);

		if( confirm('Are you sure you want to remove this comment?') ) {

			jQuery.ajax({
			   url: ajaxurl,
			   data: {
			      'action':'bp_media_ajax_delete_comment',
			      'user_id': user_id,
			      'comment_id': comment_id,
			      'nonce': jQuery('#nonce').val()
			   },
			   error: function() {
			     alert('Sorry, error deleting comment.');
			   },
			   success: function(data) {
			   	console.log(data);
			   	jQuery(that).parent().parent().slideUp(300);
			   }
			});
		}


	});

	if( getURLParameter('new') ) {
		jQuery('#plupload-upload-ui').slideToggle();
	}



	var extraParam = function(e, data) {

		var action = get_var_in_query( 'action', data.data ) ;

		if( 'post_update' === action ) {

			var id = jQuery('#bp-media-attachment-id').val();

			if(id) {
				data.data += '&attachment_id=' + encodeURIComponent( id );
			}

	    }
	    return true;
	};
	jQuery.ajaxSetup( {beforeSend: extraParam} );


	jQuery(document).ajaxComplete( function( event, xhr, data ) {

		var action = get_var_in_query( 'action', data.data ) ;

		if( 'post_update' === action ) {
			jQuery( '.media-activity' ).slideUp(300);
			jQuery( '#bp-media-attachment-id' ).val('');
		}
	});
	jQuery('body').on( 'click', '#bp_media_cancel_report', function( event ) {
		event.preventDefault();
		jQuery("#bp_report_modal_overlay,#bp_report_modal").fadeOut(200).remove();
	});
	jQuery('body').on( 'click', '#bp_media_submit_report', function( event ) {

		event.preventDefault();
		var message		= jQuery('#bp_media_report_comment').val();
		var item_id		= jQuery('#bp_media_report_media_id').val();
		var reason		= jQuery('input[name="bp_report_reason"]').val();
		var nonce		= jQuery('#bp_media_report_nonce').val();
		jQuery.ajax({
		   url: ajaxurl,
		   method: 'POST',
		   data: {
			  'action'	:	'bp_media_make_report',
			  'item_id'	: 	item_id,
			  'message'	:	message,
			  'reason'	:	reason,
			  'nonce'	:	nonce
		   },
		   error: function() {
			   alert(bp_media.bp_media_ajax_reporting_error);
		   },
		   success: function( response ) {
			   if( response.success && response.success == true ){
				   jQuery('.bp_report_modal_body').html( bp_media.report_success_message );
				   setTimeout(function() {
				        jQuery("#bp_report_modal_overlay,#bp_report_modal").fadeOut(200).remove();
				    }, 3000);
			   }
		   }
		});
	});
	jQuery('body').on( 'click', '.bp-report-item', function( event ) {

		event.preventDefault();
		jQuery("body").append("<div id='bp_report_modal_overlay'></div>");
		//get image/comment id
		var item_id = jQuery(this).data('item_id');
		//current selector object
		var obj = jQuery(this);
		//get JSON list of reasons
		var reasons = jQuery.parseJSON( bp_media.bp_media_reporting_reasons );
		var modal_content = ''
		modal_content += '<div id="bp_report_modal">'
			modal_content += '<div class="bp_report_modal_header">'+ bp_media.bp_media_reporting_header +'</div>'
			modal_content += '<div class="bp_report_modal_body">'
				modal_content += '<div class="">'+ bp_media.bp_media_reporting_body
				if( reasons ){
					jQuery.each(reasons, function(index, reason) {
						modal_content += '<div><label><input type="radio" name="bp_report_reason" value="'+ reason +'" '+ ( index == 0 ? ' checked="checked" ' : '') +'  />'+ reason +'</label></div>';
					});
				}
				modal_content += '<div class="bp-media-report-message"><textarea id="bp_media_report_comment" placeholder="Comments"></textarea></div>'
				modal_content += '<input type="hidden" id="bp_media_report_media_id" value="'+ item_id +'" />'
				modal_content += '<input type="hidden" id="bp_media_report_nonce" value="'+  obj.data('nonce') +'" />'
				modal_content += '<input type="submit" id="bp_media_submit_report" value="'+ bp_media.submit_text +'" />';
				modal_content += '<input type="button" id="bp_media_cancel_report" value="'+ bp_media.cancel_text +'" />';
			modal_content += '<div class="bp_media_clear"></div></div>'
		modal_content += '</div>'
		jQuery("body").append( modal_content );
		var report_window = jQuery('.bp-media-report-window');


		var modal_params = {
			modal_id : '#bp_report_modal',
			top: 100,
            overlay: 0.5,
		}
		open_report_modal( modal_params );
	});

	function get_var_in_query( item,  str ){
	   var items;
	   if( !str )
	       return false;
	   var data_fields = str.split('&');
	   for( var i=0; i< data_fields.length; i++ ){

	       items = data_fields[i].split('=');
	       if( items[0] == item )
	           return items[1];
		}

	       return false;
	}

	function open_report_modal( params ) {
		var modal_id = params.modal_id;
		var modal_height = jQuery(modal_id).outerHeight();
	  	var modal_width = jQuery(modal_id).outerWidth();

		jQuery('#bp_report_modal_overlay').css({ 'display' : 'block', opacity : 0 });

		jQuery('#bp_report_modal_overlay').fadeTo(200, params.overlay);

		jQuery(modal_id).css({
			'display' : 'block',
			'position' : 'fixed',
			'opacity' : 0,
			'z-index': 11000,
			'left' : 50 + '%',
			'margin-left' : -(modal_width/2) + "px",
			'top' : params.top + "px"
		});

		jQuery(modal_id).fadeTo(200,1);
	}

});
