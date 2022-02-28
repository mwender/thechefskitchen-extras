<?php

namespace tcw\enqueues;

function enqueue_scripts(){
  // Our custom styles
  $css_filename = TCW_PLUGIN_PATH . 'lib/' . TCW_CSS_DIR . '/main.css';
  if( file_exists( $css_filename ) )
    wp_enqueue_style( 'tka', TCW_PLUGIN_URL . 'lib/' . TCW_CSS_DIR . '/main.css', ['hello-elementor','elementor-frontend'], filemtime( $css_filename ) );

  wp_register_script( 'elementor-tab-enhancers', TCW_PLUGIN_URL . 'lib/js/elementor-tab-enhancers.js', [ 'elementor-frontend-modules', 'elementor-pro-webpack-runtime', 'pro-elements-handlers', 'elementor-frontend', 'elementor-pro-frontend' ], filemtime( TCW_PLUGIN_PATH . 'lib/js/elementor-tab-enhancers.js'), true );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_scripts' );

/**
 * Custom styles for the WP Admin
 */
function custom_admin_styles(){
  wp_enqueue_style( 'tka-admin-styles', TCW_PLUGIN_URL . 'lib/dist/admin.css', null, filemtime( TCW_PLUGIN_PATH . 'lib/dist/admin.css' ) );
}
add_action( 'admin_head', __NAMESPACE__ . '\\custom_admin_styles' );