<?php
/**
 * We will use WordPress default comment system for managing
 * reporting/flagging
 */

if( ! class_exists( 'BP_Media_Reporting' ) ) {

    class BP_Media_Reporting{
        /**
         * [$comment_type description]
         * @var [type]
         */
        public $comment_type;
        /**
         * Initiate Constructor
         *
         * @since 1.0.1
         */
        public function __construct() {

            $this->comment_type = "bp_media_flag";
            $this->hooks();
        }
        /**
         * [hooks description]
         * @return [type] [description]
         */
        public function hooks() {
            add_action('admin_comment_types_dropdown', array($this, 'add_custom_comment_type_dropdown') );
            add_action('bp_media_photo_options', array($this, 'add_flag') );
            //ajax command
            add_action('wp_ajax_bp_media_make_report', array($this, 'bp_media_make_report') );
            add_action('wp_ajax_nopriv_bp_media_make_report', array($this, 'bp_media_make_report') );
        }
        /**
         * Add custom comment type to comment type dropdown
         *
         * @return array
         *
         * @since 1.0.1
         */
        public function add_custom_comment_type_dropdown( $comment_types = array() ) {
            $comment_types[$this->comment_type] = __('Flagged Media','bp-media');
            return $comment_types;
        }
        /**
         * Add flag for reporting item
         */
        public function add_flag() {
            global $post;
            ?>
            <a href="#bp_media_report" class="right bp-report-item" data-item_id="<?php echo $post->ID; ?>" data-nonce="<?php echo wp_create_nonce( 'report-item-' . (int)$post->ID ); ?>"><?php _e( 'report', 'bp_media' ) ;?></a>
            <?php
        }
        /**
         * [bp_media_make_report description]
         * @return [type] [description]
         */
        public function bp_media_make_report(){
            $item_id =  $_POST['item_id'];
            check_ajax_referer( 'report-item-' . $item_id, 'nonce' );

        	$item_id =  $_POST['item_id'];
        	$user_id =  get_current_user_id();

        	if ( empty( $item_id ) ) {
        		return;
        	}

        	$results = array();
            $commentdata = array(
            	'comment_post_ID' => $item_id, // to which post the comment will show up
            	'comment_content' => __('Flagged',''),
            	'comment_type' => $this->comment_type, //use a custom comment type
            	'user_id' => $user_id, //passing current user ID or any predefined as per the demand
            );

            //Insert new comment and get the comment ID
            $comment_id = wp_new_comment( $commentdata );
            if( $comment_id ) {
                $results['success'] = true;
                $results['comment_id'] = $comment_id;
            }
        	wp_send_json( $results );
        }
    }
}

new BP_Media_Reporting();
