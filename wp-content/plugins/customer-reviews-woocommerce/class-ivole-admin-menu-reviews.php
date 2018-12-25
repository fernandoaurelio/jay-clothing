<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Ivole_Reviews_Admin_Menu' ) ):

/**
 * Reviews admin menu controller class
 */
class Ivole_Reviews_Admin_Menu {

    /**
     * Constructor
     *
     * @since 3.36
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_reviews_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'include_scripts' ) );
    }

    /**
     * Register the top-level admin menu
     *
     * @since 3.36
     */
    public function register_reviews_menu() {
        add_menu_page(
            __( 'Reviews', IVOLE_TEXT_DOMAIN ),
            __( 'Reviews', IVOLE_TEXT_DOMAIN ),
            'manage_options',
            'ivole-reviews',
            array( $this, 'display_reviews_admin_page' ),
            'dashicons-star-filled',
            56
        );
    }

    public function display_reviews_admin_page() {
        global $post_id;

        $list_table = new Ivole_Reviews_List_Table( [
            'screen' => get_current_screen()
        ] );

        $pagenum  = $list_table->get_pagenum();
        $doaction = $list_table->current_action();

        if ( $doaction ) {
            check_admin_referer( 'bulk-comments' );

            if ( 'delete_all' == $doaction && ! empty( $_REQUEST['pagegen_timestamp'] ) ) {
                $comment_status = wp_unslash( $_REQUEST['comment_status'] );
                $delete_time    = wp_unslash( $_REQUEST['pagegen_timestamp'] );
                $comment_ids    = $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = %s AND %s > comment_date_gmt", $comment_status, $delete_time ) );
                $doaction       = 'delete';
            } elseif ( isset( $_REQUEST['delete_comments'] ) ) {
                $comment_ids = $_REQUEST['delete_comments'];
                $doaction    = ( $_REQUEST['action'] != -1 ) ? $_REQUEST['action'] : $_REQUEST['action2'];
            } elseif ( isset( $_REQUEST['ids'] ) ) {
                $comment_ids = array_map( 'absint', explode( ',', $_REQUEST['ids'] ) );
            } elseif ( wp_get_referer() ) {
                wp_safe_redirect( wp_get_referer() );
                exit;
            }

            $approved = $unapproved = $spammed = $unspammed = $trashed = $untrashed = $deleted = 0;
            $redirect_to = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'spammed', 'unspammed', 'approved', 'unapproved', 'ids' ), wp_get_referer() );
            $redirect_to = add_query_arg( 'paged', $pagenum, $redirect_to );
	        wp_defer_comment_counting( true );

            foreach ( $comment_ids as $comment_id ) { // Check the permissions on each
		        if ( ! current_user_can( 'edit_comment', $comment_id ) ) {
			        continue;
		        }

                switch ( $doaction ) {
			        case 'approve':
				        wp_set_comment_status( $comment_id, 'approve' );
				        $approved++;
				        break;
			        case 'unapprove':
				        wp_set_comment_status( $comment_id, 'hold' );
				        $unapproved++;
				        break;
			        case 'spam':
				        wp_spam_comment( $comment_id );
				        $spammed++;
				        break;
			        case 'unspam':
				        wp_unspam_comment( $comment_id );
				        $unspammed++;
				        break;
			        case 'trash':
				        wp_trash_comment( $comment_id );
				        $trashed++;
				        break;
			        case 'untrash':
				        wp_untrash_comment( $comment_id );
				        $untrashed++;
				        break;
			        case 'delete':
			        	wp_delete_comment( $comment_id );
				        $deleted++;
				        break;
		        }
	        }

            if ( ! in_array( $doaction, array( 'approve', 'unapprove', 'spam', 'unspam', 'trash', 'delete' ), true ) ) {
		        $screen = get_current_screen()->id;
                /**
                 * Fires when a custom bulk action should be handled.
                 *
                 * The redirect link should be modified with success or failure feedback
                 * from the action to be used to display feedback to the user.
                 *
                 * The dynamic portion of the hook name, `$screen`, refers to the current screen ID.
                 *
                 * @since 4.7.0
                 *
                 * @param string $redirect_url The redirect URL.
                 * @param string $doaction     The action being taken.
                 * @param array  $items        The items to take the action on.
                 */
                $redirect_to = apply_filters( "handle_bulk_actions-{$screen}", $redirect_to, $doaction, $comment_ids );
	        }

            wp_defer_comment_counting( false );

	        if ( $approved ) {
		        $redirect_to = add_query_arg( 'approved', $approved, $redirect_to );
            }

	        if ( $unapproved ) {
		        $redirect_to = add_query_arg( 'unapproved', $unapproved, $redirect_to );
            }

	        if ( $spammed ) {
		        $redirect_to = add_query_arg( 'spammed', $spammed, $redirect_to );
	        }

            if ( $unspammed ) {
		        $redirect_to = add_query_arg( 'unspammed', $unspammed, $redirect_to );
            }

	        if ( $trashed ) {
	            $redirect_to = add_query_arg( 'trashed', $trashed, $redirect_to );
            }

	        if ( $untrashed ) {
		        $redirect_to = add_query_arg( 'untrashed', $untrashed, $redirect_to );
            }

	        if ( $deleted ) {
		        $redirect_to = add_query_arg( 'deleted', $deleted, $redirect_to );
            }

	        if ( $trashed || $spammed ) {
		        $redirect_to = add_query_arg( 'ids', join( ',', $comment_ids ), $redirect_to );
            }

	        wp_safe_redirect( $redirect_to );
	        exit;
        } elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
	        wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
	        exit;
        }

        $list_table->prepare_items();

        include plugin_dir_path( __FILE__ ) . 'templates/all-reviews-admin-page.php';
    }

    public function include_scripts() {
        $assets_version = '3.6';

        if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] === 'ivole-reviews' ) {
            wp_enqueue_script( 'photoswipe', plugins_url( 'assets/js/photoswipe/photoswipe.min.js', WC_PLUGIN_FILE ), array(), $assets_version );
            wp_enqueue_script( 'photoswipe-ui-default', plugins_url( 'assets/js/photoswipe/photoswipe-ui-default.min.js', WC_PLUGIN_FILE ), array(), $assets_version );

            wp_enqueue_style( 'photoswipe', plugins_url( 'assets/css/photoswipe/photoswipe.css', WC_PLUGIN_FILE ), array(), $assets_version );
            wp_enqueue_style( 'photoswipe-default-skin', plugins_url( 'assets/css/photoswipe/default-skin/default-skin.css', WC_PLUGIN_FILE ), array(), $assets_version );

            wp_enqueue_script( 'ivole-all-reviews', plugins_url( 'js/all-reviews.js', __FILE__ ), array( 'jquery' ), $assets_version );
            wp_enqueue_style( 'ivole-all-reviews', plugins_url( 'css/all-reviews.css', __FILE__ ), array(), $assets_version );

            add_action( 'admin_footer', 'woocommerce_photoswipe' );
        }
    }

}

endif;
