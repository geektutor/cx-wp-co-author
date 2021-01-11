<?php
/**
 * Plugin Name: Geektutor WP Post Co-Author
 * Plugin URI: https://github.com/geektutor/cx-wp-co-author
 * Description: Geektutor WP Post Multiple Author with Ads.
 * Author: Sodiq Akinjobi (Geektutor)
 * Author URI: https://geektutor.xyz
 * Version: 1.0.0
 * Requires at least: 5.0
 * Tested up to: 5.5
 *
 * Text Domain: cx-coa
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

// Make sure you update the version values when necessary.
define( 'CX_COA_PLUGIN_DIR',  plugin_dir_path( __FILE__ ) );
define( 'CX_COA_PLUGIN_FILE', __FILE__ );

// Include the main class.
if ( ! class_exists( 'CX_COA' ) ) {
    include_once dirname(__FILE__) . '/includes/class-cx-coa.php';
}

/**
 * Return instance of the func.
 * 
 * @return Instanace 
 */
function cx_coa() {
    return CX_COA::instance();
}

add_action( 'plugins_loaded', 'cx_coa' );

$GLOBALS['cx_coa'] = cx_coa();
