<?php

if ( class_exists( 'BP_Group_Extension' ) ) :

	class Group_Extension_BP_MEDIA extends BP_Group_Extension {
	    /**
	     * Fire it up.
	     */
	    function __construct() {
	        $args = array(
	            'slug' => 'media',
	            'name' => 'Media',
	        );
	        parent::init( $args );
	    }

	    /**
	     * The display() contains the markup that will be displayed on the main plugin tab.
	     *
	     * @param int|null $group_id Group ID.
	     */
	    function display( $group_id = null ) {
	        $group_id = bp_get_group_id();
	        bp_media_get_template_part( 'group/image-loop');
	    }

	}
endif;

/**
 * The bp_media_group_settings function.
 *
 * @access public
 */
function bp_media_group_settings() {
        ?>
       <h4><?php _e( 'Media Settings', 'bp-media' ); ?></h4>

		<label><input type="checkbox" name="group-enable-media" id="group-enable-media" value="1"<?php bp_media_show_media_setting(); ?> /> <?php _e( 'Allow Group Attachments:', 'bp-media' ); ?></label>

        <?php
}
add_action( 'bp_after_group_settings_admin', 'bp_media_group_settings' );

/**
 * The bp_media_show_media_setting function.
 *
 * @access public
 */
function bp_media_show_media_setting() {

	$meta = groups_get_groupmeta( bp_get_group_id() );

	if ( isset( $meta['enable_media'] ) && '1' === $meta['enable_media'][0] ) {
		echo ' checked="checked"';
	}
}


/**
 * The bp_media_save_enable_media function.
 *
 * @param int $group_id Default: integer 0.
 */
function bp_media_save_enable_media( $group_id = 0 ) {

	$enable_media  = ( isset($_POST['group-enable-media'] ) ) ? 1 : 0;
	groups_update_groupmeta( $group_id, 'enable_media', $enable_media );

}
add_action( 'groups_group_settings_edited', 'bp_media_save_enable_media' );


/**
 * The bp_media_group_is_enabled function.
 */
function bp_media_group_is_enabled() {
	$bp = buddypress();
	if ( isset( $bp->groups->current_group->slug ) && $bp->groups->current_group->slug == $bp->current_item ) {
         $meta = groups_get_groupmeta( $bp->groups->current_group->id );
         if ( '1' === $meta['enable_media'][0] || defined('ENABLE_MEDIA') ) {
	         bp_register_group_extension( 'Group_Extension_BP_MEDIA' );
         }
	}
}
add_action( 'bp_setup_nav', 'bp_media_group_is_enabled' );
