<?php
namespace tcw\enqueues;

/**
 * Enqueues required scripts and styles.
 */
function enqueue_scripts(){
  // Our custom styles
  $css_filename = TCW_PLUGIN_PATH . 'lib/' . TCW_CSS_DIR . '/main.css';
  if( file_exists( $css_filename ) )
    wp_enqueue_style( 'tka', TCW_PLUGIN_URL . 'lib/' . TCW_CSS_DIR . '/main.css', ['hello-elementor','elementor-frontend'], filemtime( $css_filename ) );

  wp_register_script( 'elementor-tab-enhancers', TCW_PLUGIN_URL . 'lib/js/elementor-tab-enhancers.js', [ 'elementor-frontend-modules', 'elementor-pro-webpack-runtime', 'pro-elements-handlers', 'elementor-frontend', 'elementor-pro-frontend' ], filemtime( TCW_PLUGIN_PATH . 'lib/js/elementor-tab-enhancers.js'), true );

  $calendar_js = ( TCW_DEV_ENV )? 'caleandar.js' : 'caleandar.min.js' ;
  wp_register_script( 'caleandar', TCW_PLUGIN_URL . 'lib/js/' . $calendar_js, null, filemtime( TCW_PLUGIN_PATH . 'lib/js/' . $calendar_js), true );
  wp_register_script( 'caleandar-init', TCW_PLUGIN_URL . 'lib/js/caleandar.init.js', [ 'caleandar' ], filemtime( TCW_PLUGIN_PATH . 'lib/js/caleandar.init.js'), true );
  wp_localize_script( 'caleandar-init', 'wpvars', [
    'apiEndpoint' => get_rest_url( null, '/tcw/v1/events' )
  ]);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Custom styles for the WP Admin
 */
function custom_admin_styles(){
  wp_enqueue_style( 'tka-admin-styles', TCW_PLUGIN_URL . 'lib/dist/admin.css', null, filemtime( TCW_PLUGIN_PATH . 'lib/dist/admin.css' ) );
}
add_action( 'admin_head', __NAMESPACE__ . '\\custom_admin_styles' );