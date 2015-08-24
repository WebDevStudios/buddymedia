<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * AppBuddy_Ajax class.
 */
class BP_Media_CPT {
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {}
	
	
	public static function instance() {

		// Store the instance locally to avoid private static replication
		static $instance = null;

		// Only run these methods if they haven't been run previously
		if ( null === $instance ) {
			$instance = new BP_Media_CPT;
			$instance->setup_actions();
		}

		// Always return the instance
		return $instance;

	}


	/**
	 * setup_actions function.
	 * 
	 * @access private
	 * @return void
	 */
	private function setup_actions() {
	
		add_action( 'init', array( $this, 'bp_media_post_type'), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_bp_media_metaboxes' ) );
		add_action(	'admin_menu', array( $this, 'remove_submenus' ) );
		add_action(	'admin_init', array( $this, 'add_columns' ) );
		add_action(	'admin_head', array( $this, 'hide_add_new_button' ) );
		add_action( 'bp_init', array( $this, 'customize_media_tracking_args' ) );
		add_action( 'template_redirect', array( $this, 'bp_media_redirect_cpt_to_album' ) );
		
		add_filter( 'bp_activity_custom_post_type_post_action', array( $this, 'bp_media_filter_activity_action' ), 10, 2 );
		add_filter( 'bp_activity_permalink', array( $this, 'bp_media_filter_activity_action_permalink' ), 10, 2 );
		
	}
	
	
	/**
	 * checkin_post_type function.
	 * 
	 * @access public
	 * @return void
	 */
	public function bp_media_post_type() {
	
		$labels = array(
			'name'                => _x( 'Media', 'Media', 'bp-media' ),
			'singular_name'       => _x( 'Media', 'Media', 'bp-media' ),
			'menu_name'           => __( 'User Media', 'bp-media' ),
			'name_admin_bar'      => __( 'Media', 'bp-media' ),
			'parent_item_colon'   => __( 'Parent Media:', 'bp-media' ),
			'all_items'           => __( 'All Media', 'bp-media' ),
			'add_new_item'        => __( 'Add media gallery', 'bp-media' ),
			'add_new'             => __( 'Add New', 'bp-media' ),
			'new_item'            => __( 'New Media', 'bp-media' ),
			'edit_item'           => __( 'Edit Media', 'bp-media' ),
			'update_item'         => __( 'Update Media', 'bp-media' ),
			'view_item'           => __( 'View Media', 'bp-media' ),
			'search_items'        => __( 'Search Media', 'bp-media' ),
			'not_found'           => __( 'Not found', 'bp-media' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'bp-media' ),
		);
		$args = array(
			'label'               => __( 'media', 'bp-media' ),
			'description'         => __( 'User Media', 'bp-media' ),
			'labels'              => $labels,
			'supports'            => array('title', 'buddypress-activity'),
			'taxonomies'          => array(''),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 65,
			'menu_icon'           => 'dashicons-format-gallery',
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => false,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
	        'bp_activity' => array(
	            'component_id' => buddypress()->activity->id,
	            'action_id'    => 'new_album',
	            'contexts'     => array( 'activity', 'member' ),
	            'position'     => 40,
	        ),
		);
		register_post_type( 'bp_media', $args );
		
	}
	
	/**
	 * add_columns function.
	 * 
	 * @access public
	 * @return void
	 */
	public function add_columns() {
		add_filter( 'manage_edit-bp_media_columns', array( $this, 'add_new_gallery_columns' ) );
	}
	
	
	/**
	 * add_new_gallery_columns function.
	 * 
	 * @access public
	 * @param mixed $gallery_columns
	 * @return void
	 */
	public function add_new_gallery_columns( $gallery_columns ) {
		
	    $new_columns['cb'] = '<input type="checkbox" />';
	    $new_columns['title'] = _x('Album Name', 'bp-media');
	    $new_columns['author'] = __('Author');
	    $new_columns['date'] = _x('Date', 'bp-media');
	 
	    return $new_columns;
	}
	
	
	/**
	 * customize_media_tracking_args function.
	 * 
	 * @access public
	 * @return void
	 */
	public function customize_media_tracking_args() {
	    // Check if the Activity component is active before using it.
	    if ( ! bp_is_active( 'activity' ) ) {
	        return;
	    }
	 
	    bp_activity_set_post_type_tracking_args( 'bp_media', array(
	        'component_id'             => buddypress()->media->id,
	        'action_id'                => 'new_album',
	        'bp_activity_admin_filter' => __( 'Created a new album', 'bp_media' ),
	        'bp_activity_front_filter' => __( 'Media', 'bp_media' ),
	        'contexts'                 => array( 'activity', 'member' ),
	        'activity_comment'         => true,
	        'bp_activity_new_post'     => __( '%1$s created a new <a href="%2$s">album</a>', 'bp_media' ),
	        'bp_activity_new_post_ms'  => __( '%1$s created a new <a href="%2$s">album</a>, on the site %3$s', 'bp_media' ),
	        'position'                 => 100,
	    ) );
	}
	
	
	/**
	 * bp_media_filter_activity_action function.
	 *
	 * this filters the CPT post link to link to the album on users profile 
	 * 
	 * @access public
	 * @param mixed $action
	 * @param mixed $activity
	 * @return string
	 */
	public function bp_media_filter_activity_action( $action, $activity ) {
	
		if( 'media' === $activity->component && 'new_album' === $activity->type ) {

			$user_link = '<a href="'.bp_core_get_user_domain( $activity->user_id ).'">'. bp_core_get_username( $activity->user_id ).'</a>';
			$album_link = bp_core_get_user_domain( $activity->user_id ) . BP_MEDIA_SLUG . '/album/' . $activity->secondary_item_id;
			
			return sprintf( __( '%1$s created a new <a href="%2$s">album</a>', 'bp_media' ), $user_link, $album_link  );
			
		}
	
		return $action;
	}
	


	/**
	 * bp_media_redirect_cpt_to_album function.
	 *
	 * redirect cpt page to user album
	 * 
	 * @access public
	 * @return void
	 */
	function bp_media_redirect_cpt_to_album() {
		global $post;
		
		if( 'bp_media' === $post->post_type ) {
			$redirect_url = bp_core_get_user_domain( $post->post_author ) . BP_MEDIA_SLUG . '/album/' . $post->ID;
			wp_safe_redirect( $redirect_url );
		}
		
	}
	
	
	
	/**
	 * bp_media_filter_activity_action_permalink function.
	 *
	 * this filter remove the link on the time stamp
	 * 
	 * @access public
	 * @param mixed $activity_meta
	 * @param mixed $activity
	 * @return string
	 */
	public function bp_media_filter_activity_action_permalink( $activity_meta, $activity ) {
	
		if( 'media' === $activity->component && 'new_album' === $activity->type ) {
		
			$date_recorded  = bp_core_time_since( $activity->date_recorded );
			return $activity->action . ' ' . $date_recorded;
			
		}
			
		return $activity_meta;
		
	}

	

	/**
	 * add_checkin_metaboxes function.
	 * 
	 * @access public
	 * @return void
	 */
	public function add_bp_media_metaboxes() {
		add_meta_box( 'user_media', 'Media', array( $this, 'user_media' ), 'bp_media', 'normal', 'default' );
	}
	
	
	/**
	 * checkin_user function.
	 * 
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	public function user_media( $post ) {
	
		if( ! $post ) return;
		
		$attachments = get_attached_media( 'image', $post->ID );
		
		?>	
			<style>
				.user-media li {
					overflow: hidden;
					border-bottom: 1px solid #efefef;
					border-top: 0px !important;
					padding: 10px 0 !important;
					margin: 0 !important;
				}
				.media-thumbnail {
					width: 75px;
					height: 75px;
					float: left;
					padding: 0 10px;
				}
				.media-thumbnail img {
					width: 75px;
					height: 75px;
				}
				.media-info {
					float: left;
					position: relative;
					height: 75px;
					width: 60%;
				}
				.media-info div {
					padding: 5px 0;
				}
				.media-author {
					position: absolute;
					bottom: 0;
				}
				.image-action-delete {
					cursor: pointer;
				}
			</style> 
			<?php
		
		echo '<ul class="user-media">';
		
			foreach( $attachments as $attachment ) {
			
				$user = get_user_by( 'id', (int) $attachments[$attachment->ID]->post_author );
								
				?>
				
				<li>
					<div class="media-thumbnail">
						<a href="<?php echo bp_core_get_userlink( $user->ID, false, true )  . BP_MEDIA_SLUG . '/image/' . $attachment->ID; ?>">
							<?php echo wp_get_attachment_image( $attachment->ID, 'thumbnail' ); ?>
						</a>
					</div>
					
					<div class="media-info">						
						<div class="media-description">
							<?php _e( 'Description: ', 'bp_media' ); ?>
							<?php echo $attachments[$attachment->ID]->post_content; ?>
						</div>
						
						<div class="media-author">
							<?php _e( 'Uploaded By: ', 'bp_media' ); ?>
							<a href="<?php echo bp_core_get_userlink( $user->ID, false, true ); ?>"><?php echo $user->user_login; ?></a>
						</div>
					</div>
					
					<?php if( bp_media_can_edit() ) : ?>
						<div class="image-action-links" data-id="<?php echo $attachment->ID; ?>">
							<a class="image-action-delete error"><?php _e( 'delete', 'bp_media' ) ;?></a>
							<input id="image-user-id" type="hidden" value="<?php echo bp_loggedin_user_id(); ?>">
							<input id="nonce" type="hidden" value="<?php echo wp_create_nonce( "edit-album" ); ?>">
						</div>
					<?php endif ; ?>
				
				</li>
				
				<?php
				
			}
		
		echo '</ul>';
		
		
	}
		
	/**
	 * remove_submenus function.
	 * 
	 * @access public
	 * @return void
	 */
	function remove_submenus() {
		global $submenu;
		unset( $submenu['edit.php?post_type=bp_media'][10] ); // Removes 'Add New'.
	}
	
	
	/**
	 * hide_add_new_button function.
	 * 
	 * @access public
	 * @return void
	 */
	function hide_add_new_button() {
	
    	if( 'bp_media' == get_post_type() ) {
		  echo '<style type="text/css">
		    	#favorite-actions {display:none;}
				.add-new-h2{display:none;}
				
		    </style>';
		 }
	}
	


	
}
BP_Media_CPT::instance();





function record_cpt_activity_content( $cpt ) {

	if ( 'new_album' === $cpt['type'] ) {
		$cpt['content'] = 'what you need';
	}
	
	return $cpt;
}
//add_filter('bp_before_activity_add_parse_args', 'record_cpt_activity_content');