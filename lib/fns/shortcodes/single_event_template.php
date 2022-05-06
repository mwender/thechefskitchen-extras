<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert};
use function tcw\templates\{render_template};

function single_event_template( $atts ){
  $args = shortcode_atts([
    'id' => null
  ], $atts );

  if( is_null( $args['id'] ) )
    return get_alert( ['title' => 'Missing ID', 'description' => 'Please specify a Post ID by adding an <code>id</code> attribute.'] );

  $location_id = get_post_meta( $args['id'], 'location', true );
  $single_event_template_id = get_post_meta( $location_id, 'single_event_template', true );

  if( is_elementor_edit_mode() ){
    return get_alert( ['title' => '[single_event_template /] Shortcode','description' => 'You have successfully added the <code>[single_event_template/]</code> shortcode.'] );
  } else {
    return do_shortcode( '[elementor-template id="' . $single_event_template_id . '"]' );
  }
}
add_shortcode( 'single_event_template', __NAMESPACE__ . '\\single_event_template' );