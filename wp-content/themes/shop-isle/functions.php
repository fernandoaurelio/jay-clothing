<?php
/**
 * Main functions file
 *
 * @package WordPress
 * @subpackage Shop Isle
 */

function wpdocs_theme_name_scripts() {
	wp_enqueue_script( 'script-utils', get_template_directory_uri() . '/library/utils.js', array(), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'wpdocs_theme_name_scripts' );


$vendor_file = trailingslashit( get_template_directory() ) . 'vendor/autoload.php';
if ( is_readable( $vendor_file ) ) {
	require_once $vendor_file;
}

if ( ! defined( 'WPFORMS_SHAREASALE_ID' ) ) {
	define( 'WPFORMS_SHAREASALE_ID', '848264' );
}

add_filter( 'themeisle_sdk_products', 'shopisle_load_sdk' );
/**
 * Loads products array.
 *
 * @param array $products All products.
 *
 * @return array Products array.
 */
function shopisle_load_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}
/**
 * Initialize all the things.
 */
require get_template_directory() . '/inc/init.php';

/**
 * Note: Do not add any custom code here. Please use a child theme so that your customizations aren't lost during updates.
 * http://codex.wordpress.org/Child_Themes
 */

//Adicionando SHORTCODES
function jc_add_shortcodes() {
    require_once( 'library/shortcodes/jc-shortcode-cliente-login.php' );    
    require_once( 'library/shortcodes/jc-shortcode-quantidade.php' );    
    require_once( 'library/shortcodes/jc-shortcode-estrelas.php' );    
    require_once( 'library/shortcodes/jc-shortcode-preco.php' );    
}
add_action( 'after_setup_theme', 'jc_add_shortcodes' );

