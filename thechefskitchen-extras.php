<?php
/**
 * Plugin Name:     The Chef's Workshop Extras
 * Plugin URI:      https://github.com/mwender/thechefskitchen-extras
 * Description:     Various extensions for The Chef's Workshop website
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     thechefskitchen-extras
 * Domain Path:     /languages
 * Version:         1.7.1
 *
 * @package         TCW_Extras
 */

// Your code starts here.
$css_dir = ( stristr( site_url(), '.local' ) || SCRIPT_DEBUG )? 'css' : 'dist' ;
define( 'TCW_CSS_DIR', $css_dir );
$dev_env = ( '.local' == stristr( site_url(), '.local' ) ) ? true : false ;
define( 'TCW_DEV_ENV', $dev_env );
define( 'TCW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TCW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Composer dependencies
if( file_exists( TCW_PLUGIN_PATH . 'vendor/autoload.php' ) ){
  require_once TCW_PLUGIN_PATH . 'vendor/autoload.php';
} else {
  add_action( 'admin_notices', function(){
    $class = 'notice notice-error';
    $message = __( 'Missing required Composer libraries. Please run `composer install` from the root directory of this plugin.', 'tka' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  } );
}


// Load required files
require_once( TCW_PLUGIN_PATH . 'lib/fns/api.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/acf-json-save-point.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/admin-custom-columns.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/enqueues.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/events.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/search.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/shortcodes.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/templates.php' );
require_once( TCW_PLUGIN_PATH . 'lib/fns/utilities.php' );

/**
 * Google Maps API Key
 *
 * @param      array  $api    The api
 *
 * @return     array  Array with API Key.
 */
function thechefskitchen_acf_google_map_api( $api ){
  if( GOOGLE_MAPS_API_KEY )
    $api['key'] = GOOGLE_MAPS_API_KEY;
  return $api;
}
add_filter('acf/fields/google_map/api', 'thechefskitchen_acf_google_map_api');

/**
 * Enhanced logging.
 *
 * @param      string  $message  The log message
 */
if( ! function_exists( 'uber_log' ) ){
  function uber_log( $message = null ){
    static $counter = 1;

    $bt = debug_backtrace();
    $caller = array_shift( $bt );

    if( 1 == $counter )
      error_log( "\n\n" . str_repeat('-', 25 ) . ' STARTING DEBUG [' . date('h:i:sa', current_time('timestamp') ) . '] ' . str_repeat('-', 25 ) . "\n\n" );
    error_log( "\n" . $counter . '. ' . basename( $caller['file'] ) . '::' . $caller['line'] . "\n" . $message . "\n---\n" );
    $counter++;
  }
}

if( ! function_exists( 'is_elementor_edit_mode') ){
  /**
   * Determines if Elementor Edit Mode is active.
   *
   * @return     bool  True if elementor edit mode, False otherwise.
   */
  function is_elementor_edit_mode(){
    $edit_mode = \Elementor\Plugin::$instance->editor->is_edit_mode();
    return $edit_mode;
  }
}

