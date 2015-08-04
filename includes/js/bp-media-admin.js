jQuery(document).ready(function() {

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
			   	jQuery(that).parent().parent().slideUp(300);
			   }
			});
			
		}
		
	});
		
});