<style>
	.bp-media-create-album label {
		width: 100%;
		display: block;
	}
</style>

<div class="bp-media-create-album">
	<label>Album Title (required)</label>
	<input id="album-title" type="text">

	<label>Description</label>
	<textarea id="album-description"></textarea>
	
	<input id="album-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
	
	<div id="cleared"></div>
	<button id="create-album">Create Album</button>
</div>
