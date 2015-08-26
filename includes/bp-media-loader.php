<?php

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Main Media Class.
 *
 * @since BuddyPress (1.5.0)
 */
class BP_Media_Component extends BP_Component {

	/**
	 * Start the media component setup process.
	 *
	 * @since BuddyPress (1.5.0)
	 */
	public function __construct() {

		global $bp;

		parent::start(
			'media',
			__( 'Media', 'buddypress' ),
			BP_MEDIA_PLUGIN_DIR . '/includes',
			array(
				'adminbar_myaccount_order' => 10
			)
		);

		$bp->active_components[$this->id] = '1';

		$this->includes();	
	}

	/**
	 * Include component files.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @see BP_Component::includes() for a description of arguments.
	 *
	 * @param array $includes See BP_Component::includes() for a description.
	 */
	public function includes( $includes = array() ) {
		// Files to include
		$includes = array(
			'template',
			'screens',
			'functions'
		);

		parent::includes( $includes );
	}

	/**
	 * Set up component global variables.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @see BP_Component::setup_globals() for a description of arguments.
	 *
	 * @param array $args See BP_Component::setup_globals() for a description.
	 */
	public function setup_globals( $args = array() ) {
		$bp = buddypress();

		// Define a slug, if necessary
		if ( !defined( 'BP_MEDIA_SLUG' ) )
			define( 'BP_MEDIA_SLUG', $this->id );

		// All globals for media component.
		// Note that global_tables is included in this array.
		$args = array(
			'slug'                  => BP_MEDIA_SLUG,
			'root_slug'             => BP_MEDIA_SLUG,
			'has_directory'         => true,
			'directory_title'       => _x( 'Media', 'component directory title', 'buddypress' ),
			'notification_callback' => 'bp_media_format_notifications',
			'search_string'         => __( 'Search Media...', 'buddypress' ),
		);

		parent::setup_globals( $args );
	}

	/**
	 * Set up component navigation.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @see BP_Component::setup_nav() for a description of arguments.
	 * @uses bp_is_active()
	 * @uses is_user_logged_in()
	 * @uses bp_get_friends_slug()
	 * @uses bp_get_groups_slug()
	 *
	 * @param array $main_nav Optional. See BP_Component::setup_nav() for description.
	 * @param array $sub_nav  Optional. See BP_Component::setup_nav() for description.
	 */
	public function setup_nav( $main_nav = array(), $sub_nav = array() ) {

		// Add 'Media' to the main navigation
		$main_nav = array(
			'name'                => _x( 'Media', 'Profile media screen nav', 'buddypress' ),
			'slug'                => $this->slug,
			'position'            => 10,
			'screen_function'     => 'bp_media_screen_user_media',
			'default_subnav_slug' => 'media',
			'item_css_id'         => $this->id
		);

		// Stop if there is no user displayed or logged in
		if ( !is_user_logged_in() && !bp_displayed_user_id() )
			return;

		// Determine user to use
		if ( bp_displayed_user_domain() ) {
			$user_domain = bp_displayed_user_domain();
		} elseif ( bp_loggedin_user_domain() ) {
			$user_domain = bp_loggedin_user_domain();
		} else {
			return;
		}

		// User link
		$media_link = trailingslashit( $user_domain . $this->slug );

		if ( !defined( 'BP_MEDIA_USER_SLUG' ) )
			define( 'BP_MEDIA_USER_SLUG', $media_link );

		// Add the subnav items to the media nav item if we are using a theme that supports this
		$sub_nav[] = array(
			'name'            => _x( 'Public', 'Profile media screen sub nav', 'buddypress' ),
			'slug'            => 'media',
			'parent_url'      => $media_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bp_media_screen_user_media',
			'position'        => 10
		);
		
		$sub_nav[] = array(
			'name'            => ' ',
			'slug'            => 'album',
			'parent_url'      => $media_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bp_media_screen_user_media',
			'position'        => 40
		);
		
		$sub_nav[] = array(
			'name'            => ' ',
			'slug'            => 'image',
			'parent_url'      => $media_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bp_media_screen_user_media',
			'position'        => 40
		);

		if( is_user_logged_in() ) {
			$sub_nav[] = array(
				'name'            => ' ',
				'slug'            => 'create',
				'parent_url'      => $media_link,
				'parent_slug'     => $this->slug,
				'screen_function' => 'bp_media_screen_user_media',
				'position'        => 50
			);
		}
		
		if( is_user_logged_in() ) {
			$sub_nav[] = array(
				'name'            => ' ',
				'slug'            => 'edit',
				'parent_url'      => $media_link,
				'parent_slug'     => $this->slug,
				'screen_function' => 'bp_media_screen_user_media',
				'position'        => 50
			);
		}
		
		if ( bp_is_active( 'friends' ) ) {
			$sub_nav[] = array(
				'name'            => _x( 'Friends', 'Friends media screen sub nav', 'bp-media' ),
				'slug'            => 'friend',
				'parent_url'      => $media_link,
				'parent_slug'     => $this->slug,
				'screen_function' => 'bp_media_screen_user_media',
				'position'        => 20,
				'user_has_access' => bp_is_friend_boolean() || bp_is_my_profile()
			);
		}
		
		$sub_nav[] = array(
			'name'            => _x( 'Private', 'Private media screen sub nav', 'bp-media' ),
			'slug'            => 'private',
			'parent_url'      => $media_link,
			'parent_slug'     => $this->slug,
			'screen_function' => 'bp_media_screen_user_media',
			'position'        => 30,
			'user_has_access' => bp_is_my_profile()
		);
		
		/*

		// Additional menu if groups is active
		if ( bp_is_active( 'groups' ) ) {
			$sub_nav[] = array(
				'name'            => _x( 'Groups', 'Profile group media screen sub nav', 'buddypress' ),
				'slug'            => bp_get_groups_slug(),
				'parent_url'      => $media_link,
				'parent_slug'     => $this->slug,
				'screen_function' => 'bp_media_screen_user_media',
				'position'        => 20
			);
		}


		// Additional menu if shared is active
		if ( bp_media_is_option( 'shared-gallery' ) ) {
			$sub_nav[] = array(
				'name'            => _x( 'Shared', 'Profile friend media screen sub nav', 'buddypress' ),
				'slug'            => 'shared',
				'parent_url'      => $media_link,
				'parent_slug'     => $this->slug,
				'screen_function' => 'bp_media_screen_user_media',
				'position'        => 30
			);
		}

		*/

		$main_nav = apply_filters( 'bp_media_filter_main_nav', $main_nav );
		$sub_nav = apply_filters( 'bp_media_filter_sub_nav', $sub_nav );

		parent::setup_nav( $main_nav, $sub_nav );
	}

	/**
	 * Set up the component entries in the WordPress Admin Bar.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @see BP_Component::setup_nav() for a description of the $wp_admin_nav
	 *      parameter array.
	 * @uses is_user_logged_in()
	 * @uses trailingslashit()
	 * @uses bp_get_total_mention_count_for_user()
	 * @uses bp_loggedin_user_id()
	 * @uses bp_is_active()
	 * @uses bp_get_friends_slug()
	 * @uses bp_get_groups_slug()
	 *
	 * @param array $wp_admin_nav See BP_Component::setup_admin_bar() for a
	 *                            description.
	 */
	public function setup_admin_bar( $wp_admin_nav = array() ) {
		$bp = buddypress();

		// Menus for logged in user
		if ( is_user_logged_in() ) {

			// Setup the logged in user variables
			$user_domain   = bp_loggedin_user_domain();
			$media_link = trailingslashit( $user_domain . $this->slug );


			// Add the "Media" sub menu
			$wp_admin_nav[] = array(
				'parent' => $bp->my_account_menu_id,
				'id'     => 'my-account-' . $this->id,
				'title'  => _x( 'Media', 'My Account Media sub nav', 'buddypress' ),
				'href'   => trailingslashit( $media_link )
			);

			// Personal
			$wp_admin_nav[] = array(
				'parent' => 'my-account-' . $this->id,
				'id'     => 'my-account-' . $this->id . '-personal',
				'title'  => _x( 'Public', 'My private Media sub nav', 'buddypress' ),
				'href'   => trailingslashit( $media_link )
			);
			
			// Friends
			if ( bp_is_active( 'friends' ) ) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-friend',
					'title'  => _x( 'Friends', 'My friends Media sub nav', 'buddypress' ),
					'href'   => trailingslashit( $media_link ) . 'friend'
				);
			}
			
			// Private
			$wp_admin_nav[] = array(
				'parent' => 'my-account-' . $this->id,
				'id'     => 'my-account-' . $this->id . '-private',
				'title'  => _x( 'Private', 'My Account Media sub nav', 'buddypress' ),
				'href'   => trailingslashit( $media_link ) . 'private'
			);
			
			/*

			// Groups
			if ( bp_is_active( 'groups' ) ) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-groups',
					'title'  => _x( 'Groups', 'My group media sub nav', 'buddypress' ),
					'href'   => trailingslashit( $media_link . bp_get_groups_slug() )
				);
			}

			// shared
			if ( bp_media_is_option( 'shared-gallery' ) ) {
				$wp_admin_nav[] = array(
					'parent' => 'my-account-' . $this->id,
					'id'     => 'my-account-' . $this->id . '-shared',
					'title'  => _x( 'Shared', 'My shared media sub nav', 'buddypress' ),
					'href'   => trailingslashit( $media_link . 'shared' )
				);

			}
			
			*/

		}

		$wp_admin_nav = apply_filters( 'bp_media_filter_wp_admin_nav', $wp_admin_nav );

		parent::setup_admin_bar( $wp_admin_nav );
	}
	
	
}

/**
 * Bootstrap the Media component.
 */
function bp_setup_media() {
	global $bp;
	$bp->media = new BP_Media_Component();
}
add_action( 'bp_loaded', 'bp_setup_media' );
