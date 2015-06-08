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
	
		// Hook into the 'init' action
		add_action( 'init', array( $this, 'bp_media_post_type'), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_bp_media_metaboxes' ) );
		add_action('admin_menu', array( $this, 'remove_submenus' ) );
		add_action('admin_head', array( $this, 'hide_add_new_button' ) );
		
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
			'supports'            => array('title'),
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
		);
		register_post_type( 'bp_media', $args );
	
	}
	
	
	/**
	 * add_checkin_metaboxes function.
	 * 
	 * @access public
	 * @return void
	 */
	public function add_bp_media_metaboxes() {
		add_meta_box('user_media', 'Media', array( $this, 'user_media'), 'bp_media', 'normal', 'default');
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
		
		wp_update_post( array(
		        'ID' => 14,
		        'post_parent' => $post->ID
		    )
		);
		
		$attachments = get_attached_media( 'image', $post->ID );
		
		foreach( $attachments as $attachment ) {
					
			echo wp_get_attachment_image( $attachment->ID, 'thumbnail' );
			
		}
		
		
	}
		
	/**
	 * remove_submenus function.
	 * 
	 * @access public
	 * @return void
	 */
	function remove_submenus() {
		global $submenu;
		unset($submenu['edit.php?post_type=bp_media'][10]); // Removes 'Add New'.
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
				.tablenav{display:none;}
		    </style>';
		 }
	}
	


	
}

BP_Media_CPT::instance();