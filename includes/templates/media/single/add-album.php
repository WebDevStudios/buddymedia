<style>
	.bp-media-create-album label {
		width: 100%;
		display: block;
	}
</style>

<script>
	jQuery(document).ready(function() {

		jQuery('#create-album').on( 'click', function( event ) {
						
			jQuery.ajax({
			   url: '/wp-admin/admin-ajax.php',
			   data: {
			      'action':'bp_media_ajax_create_album',
			      'title': jQuery('#album-title').val(),
			      'description': jQuery('#album-description').val(),
			      'user_id': jQuery('#album-user-id').val()
			   },
			   error: function() {
			     alert('nope');
			   },
			   success: function(data) {
			   	console.log(data);
			   	//window.reload(data.url);
			   }
			});
			
		});
	});
</script>

<div class="bp-media-create-album">
	<label>Album Title (required)</label>
	<input id="album-title" type="text">

	<label>Description</label>
	<textarea id="album-description"></textarea>
	
	<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
	
	<div id="cleared"></div>
	<button id="create-album">Create Album</button>
</div>
