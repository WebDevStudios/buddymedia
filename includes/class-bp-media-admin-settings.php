<?php
class BP_Media_Settings {

	/**
	 * Option key, and option page slug
	 *
	 * @var    string
	 * @since  1.0.2
	 */
	public $key = 'bp_media_settings';

	/**
	 * Options page metabox id
	 *
	 * @var    string
	 * @since  1.0.2
	 */
	protected $metabox_id = 'buddymedia_settings_metabox';

	/**
	 * Options Page title
	 *
	 * @var    string
	 * @since  1.0.2
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function __construct() {
		$this->hooks();

		$this->title = __( 'Settings', 'buddymedia' );
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'cmb2_init', array( $this, 'add_options_page_metabox' ) );
	}

	/**
	 * Register our setting to WP
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function add_options_page() {
		$this->options_page = add_submenu_page(
			'edit.php?post_type=bp_media',
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' )
		);

		// Include CMB CSS in the head to avoid FOUC.
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function admin_page_display() {
		$active_tab = 'general';
		if ( isset( $_GET['tab'] ) ) {
			$active_tab = $_GET['tab'];
		}
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo admin_url( 'admin.php?page=' . $this->key . '&tab=general' ); ?>" class="nav-tab <?php echo 'general' == $active_tab  ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'um-learndash' ); ?></a>
				<a href="<?php echo admin_url( 'admin.php?page=' . $this->key . '&tab=reporting' ); ?>" class="nav-tab <?php echo 'reporting' == $active_tab  ? 'nav-tab-active' : ''; ?>"><?php _e( 'Reporting', 'um-learndash' ); ?></a>
			</h2>
			<?php
			switch( $active_tab ) {
				case 'reporting':
					cmb2_metabox_form( $this->metabox_id . '-reporting', $this->key );
				break;
				default:
					cmb2_metabox_form( $this->metabox_id, $this->key );
			}
			?>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  1.0.2
	 * @return void
	 */
	public function add_options_page_metabox() {

		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove.
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

        $cmb->add_field( array(
        	'name'    => __( 'Media Types', 'buddymedia' ),
        	'desc'    => __( 'Allowed media types.', 'buddymedia' ),
        	'default' => '',
        	'id'      => 'media_types',
        	'type'    => 'title',
        ) );

        $cmb->add_field( array(
        	'name'    => __( 'Allowed extensions for images.', 'buddymedia' ),
        	'desc'    => '',
        	'default' => '',
        	'id'      => 'bp_media_image_types',
        	'type'    => 'text',
        ) );

        $cmb->add_field( array(
        	'name'    => __( 'Allowed extensions for docs.', 'buddymedia' ),
        	'desc'    => '',
        	'default' => '',
        	'id'      => 'bp_media_doc_types',
        	'type'    => 'text',
        ) );

        $cmb->add_field( array(
        	'name'    => __( 'Storage Types', 'buddymedia' ),
        	'desc'    => __( 'Allowed storage quotas.', 'buddymedia' ),
        	'default' => '',
        	'id'      => 'storage_types',
        	'type'    => 'title',
        ) );

        $cmb->add_field( array(
        	'name'    => __( 'Maximum Upload size per file(MB)?', 'buddymedia' ),
        	'desc'    => '',
        	'default' => '',
        	'id'      => 'bp_media_file_size',
        	'type'    => 'text',
        ) );

		/*
		Add your fields here
		*/

        $cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id . '-reporting',
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove.
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		$cmb->add_field( array(
        	'name'    => __( 'Turn on reporting', 'buddymedia' ),
        	'id'      => 'enable_reporting',
        	'desc'    => __( 'Enable repoting on media items', 'buddymedia' ),
        	'type'    => 'checkbox',
        ) );

        $cmb->add_field( array(
        	'name'       => __( 'Reporting Reasons', 'buddymedia' ),
        	'id'         => 'bp_media_reporting_reasons',
        	'desc'       => __( 'Enable repoting on media items', 'buddymedia' ),
        	'type'       => 'text',
        	'repeatable' => true,
        ) );

	}

}
/**
 * The bp_media_admin_settings function.
 */
function bp_media_admin_settings() {

    /* This is how you add a new section to BuddyPress settings */
    add_settings_section(
        /* the id of your new section */
        'bp_media_section',

        /* the title of your section */
        __( 'Media Settings',  'bp-media' ),

        /* the display function for your section's description */
        'bp_plugin_setting_callback_section',

        /* BuddyPress settings */
        'buddypress'
    );

    /* This is how you add a new field to your plugin's section */
    add_settings_field(
        /* the option name you want to use for your plugin */
        'bp-media-shared-gallery',

        /* The title for your setting */
        __( 'Shared Galleries', 'bp-media' ),

        /* Display function */
        'bp_media_setting_field_callback',

        /* BuddyPress settings */
        'buddypress',

        /* Your plugin's section id */
        'bp_media_section'
    );

    /*
       This is where you add your setting to BuddyPress ones
       Here you are directly using intval as your validation function
    */
    register_setting(
        /* BuddyPress settings */
        'buddypress',

        /* the option name you want to use for your plugin */
        'bp-media-shared-gallery',

        /* the validatation function you use before saving your option to the database */
        'intval'
    );
}
add_action( 'bp_register_admin_settings', 'bp_media_admin_settings', 999 );

/**
 * The bp_plugin_setting_callback_section function.
 */
function bp_plugin_setting_callback_section() {
	?>
	<p class="description"><?php _e( 'Media Component Settings', 'bp-media' );?></p>
	<?php
}


/**
 * The bp_media_setting_field_callback function.
 */
function bp_media_setting_field_callback() {
	$bp_media_shared_gallery = bp_get_option( 'bp-media-shared-gallery' );
	?>
	<input id="bp-media-shared-gallery" name="bp-media-shared-gallery" type="checkbox" value="1" <?php checked( $bp_media_shared_gallery ); ?> />
	<label for="bp-media-shared-gallery"><?php _e( 'Allow shared media galleries.', 'bp-media' ); ?></label>
	<p class="description"><?php _e( 'Shared galleries allow users to contribute to the same gallery.', 'bp-media' ); ?></p>
	<?php
}
