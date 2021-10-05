<?php
/**
 * Plugin Name:     The Chef's Kitchen Extras
 * Plugin URI:      https://github.com/mwender/thechefskitchen-extras
 * Description:     Various extensions for The Chef's Kitchen website
 * Author:          TheWebist
 * Author URI:      https://mwender.com
 * Text Domain:     thechefskitchen-extras
 * Domain Path:     /languages
 * Version:         0.2.0
 *
 * @package         TCK_Extras
 */

// Your code starts here.
$css_dir = ( stristr( site_url(), '.local' ) || SCRIPT_DEBUG )? 'css' : 'dist' ;
define( 'TCK_CSS_DIR', $css_dir );
define( 'TCK_DEV_ENV', stristr( site_url(), '.local' ) );
define( 'TCK_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'TCK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Composer dependencies
if( file_exists( TCK_PLUGIN_PATH . 'vendor/autoload.php' ) ){
  require_once TCK_PLUGIN_PATH . 'vendor/autoload.php';
} else {
  add_action( 'admin_notices', function(){
    $class = 'notice notice-error';
    $message = __( 'Missing required Composer libraries. Please run `composer install` from the root directory of this plugin.', 'tka' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
  } );
}


// Load required files
require_once( TCK_PLUGIN_PATH . 'lib/fns/acf-json-save-point.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/admin-custom-columns.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/enqueues.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/search.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/shortcodes.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/templates.php' );
require_once( TCK_PLUGIN_PATH . 'lib/fns/utilities.php' );

/**
 * Google Maps API Key
 *
 * @param      array  $api    The api
 *
 * @return     array  Array with API Key.
 */
function thechefskitchen_acf_google_map_api( $api ){
  if( defined( GOOGLE_MAPS_API_KEY ) )
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

