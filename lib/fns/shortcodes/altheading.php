<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert};
use function tcw\templates\{render_template};

/**
 * Returns the "Alternate Heading" if one exists.
 *
 * @return     string  The alternate heading.
 */
function get_alt_heading(){
  global $post;

  if( 'location' == get_post_type( $post ) ){
    $alternate_heading = get_post_meta( $post->ID, 'alternate_heading', true );
  } else if( 'event' == get_post_type( $post ) ){
    $location_id = get_post_meta( $post->ID, 'location', true );
    $alternate_heading = get_post_meta( $location_id, 'alternate_heading', true );
  }

  if( $alternate_heading )
    return $alternate_heading;

  return get_the_title( $post );
}
add_shortcode( 'altheading', __NAMESPACE__ . '\\get_alt_heading' );