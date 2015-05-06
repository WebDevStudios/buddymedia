<?php

/** Theme Compatibility *******************************************************/

/**
 * The main theme compat class for BuddyPress Media.
 *
 * This class sets up the necessary theme compatibility actions to safely output
 * media template parts to the_title and the_content areas of a theme.
 *
 * @since BuddyPress (1.7.0)
 */
class BP_Media_Theme_Compat {
 

    /**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct() {
    
    	$this->setup_actions();

    }
    
    
    /**
     * setup_actions function.
     * 
     * @access public
     * @return void
     */
    public function setup_actions() {
	    
	    // set page as a directory, flag it true
    	add_action( 'bp_screens', array( $this, 'media_screen_index' ) ); 
    	
        // hook bp_setup_theme_compat and swap post data with template
        add_action( 'bp_setup_theme_compat', array( $this, 'is_media' ) );
	    
    }
    

	/**
	 * media_screen_index function.
	 * 
	 * @access public
	 * @return void
	 */
	public function media_screen_index() {
	    // check if on media directory...
	    if ( !bp_displayed_user_id() && bp_is_current_component( 'media' ) && !bp_current_action() ) {
	    
	        bp_update_is_directory( true, 'media' );
	        bp_core_load_template( apply_filters( 'media_screen_index', 'media/index' ) );
	     
	    }
	}
	   
    
	/**
	 * template_hierarchy function.
	 * 
	 * @access public
	 * @param mixed $templates
	 * @return array $templates Array of custom templates.
	 */
	public function template_hierarchy( $templates ) {
	    // if on a page of  plugin, then we add our path to the template path array
	    if ( bp_is_current_component( 'media' ) ) {
	 
	        $templates[] = BP_MEDIA_PLUGIN_DIR . '/includes/templates';
	    }
	    
	    return $templates;
	}
	 
	   
    

    /**
     * is_media function.
     * 
     * @access public
     * @return void
     */
    public function is_media() {
        
        if ( ! bp_current_action() && !bp_displayed_user_id() && bp_is_current_component( 'media' ) ) {
        
        	do_action( 'bp_media_screen_index' );
        
        	// add plugin path to template stack
			add_filter( 'bp_get_template_stack', array( $this, 'template_hierarchy' ), 10, 1 ); 
            // first we reset the post
            add_action( 'bp_template_include_reset_dummy_post_data', array( $this, 'directory_dummy_post' ) );
            // then we filter 'the_content'
            add_filter( 'bp_replace_the_content', array( $this, 'directory_content' ) );
 
        }
    }
 


    /**
     * directory_dummy_post function.
     *
     * Update the global $post with directory data
     * 
     * @access public
     * @return void
     */
    public function directory_dummy_post() {
 
        bp_theme_compat_reset_post( array(
            'ID'             => 0,
            'post_title'     => 'Media Directory',
            'post_author'    => 0,
            'post_date'      => 0,
            'post_content'   => '',
            'post_type'      => 'media',
            'post_status'    => 'publish',
            'is_archive'     => true,
            'comment_status' => 'closed'
        ) );
    }


    /**
     * directory_content function.
     * 
     * @access public
     * @return void
     */
    public function directory_content() {
        bp_buffer_template_part( 'media/index');
    }
    
}
 
new BP_Media_Theme_Compat();



/**
 * bp_media_screen_my_media function.
 * 
 * @access public
 * @return void
 */
function bp_media_screen_my_media() {
	global $bp;

	do_action( 'bp_media_gallery' );

	add_action( 'bp_template_title', 'bp_media_gallery_title' );
	add_action( 'bp_template_content', 'bp_media_gallery_content' );

	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}


/**
 * bp_example_screen_one_title function.
 * 
 * @access public
 * @return void
 */
function bp_media_gallery_title() {
	_e( 'Media', 'bp-media' );
}


/**
 * bp_example_screen_one_content function.
 * 
 * @access public
 * @return void
 */
function bp_media_gallery_content() {
	_e( 'media loop', 'bp-media' );
}
