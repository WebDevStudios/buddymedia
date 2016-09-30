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

            add_action('bp_media_photo_options', array($this, 'add_flag') );
            //ajax command
            add_action('wp_ajax_bp_media_make_report', array($this, 'bp_media_make_report') );
            add_action('wp_ajax_nopriv_bp_media_make_report', array($this, 'bp_media_make_report') );
        }
        /**
         * Add flag for reporting item
         */
        public function add_flag() {
            ?>
            <a href="#bp_media_report" class="right"><?php _e( 'report', 'bp_media' ) ;?></a>
            <?php
        }
        /**
         * [bp_media_make_report description]
         * @return [type] [description]
         */
        public function bp_media_make_report(){
            check_ajax_referer( 'report-item', 'nonce' );

        	$item_id =  $_POST['item_id'];
        	$user_id =  $_POST['user_id'];

        	if ( empty( $item_id ) ) {
        		return;
        	}

        	$results = array();

        	wp_send_json( $results );
        }
    }
}

new BP_Media_Reporting();
