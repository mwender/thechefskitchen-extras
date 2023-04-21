<?php

namespace tcw\events;

/**
 * Updates an event's title using the values for the start_date, end_date, and location
 *
 * @param      mixed  $value    The value
 * @param      int    $post_id  The post identifier
 * @param      object $field    The field
 *
 * @return     mixed  The value of the field.
 */
function update_event_title( $value, $post_id, $field ){
  /*
  $start_date = get_field( 'start_date', $post_id );
  $start_date_object = new \DateTime( $start_date );

  $end_date = get_field( 'end_date', $post_id );
  $end_date_object = new \DateTime( $end_date );

  $location = get_field( 'location', $post_id );
  $location_name = $location->post_title;

  $start_am_pm = $start_date_object->format('a');
  $end_am_pm = $end_date_object->format('a');

  $times = ( $start_am_pm == $end_am_pm )? $start_date_object->format( 'g' ) . ' - ' . $end_date_object->format( 'g' ) . $end_am_pm : $start_date_object->format( 'g' ) . $start_am_pm . ' - ' . $end_date_object->format( 'g' ) . $end_am_pm;

  $title = $start_date_object->format( 'D, M j, Y' ) . ', ' . $times . ' at '. $location_name;
  */

  $location = get_field( 'location', $post_id );
  $location_name = $location->post_title;
  $title = $location_name;

  if( ! empty( $title ) ){
    $slug = sanitize_title( $title );
    $postdata = array(
      'ID'          => $post_id,
      'post_title'  => $title,
      'post_type'   => 'event',
      'post_name'   => $slug,
    );

    wp_update_post( $postdata );
  }

  return $value;
}
add_filter('acf/update_value/name=start_date', __NAMESPACE__ . '\\update_event_title', 10, 3);
add_filter('acf/update_value/name=end_date', __NAMESPACE__ . '\\update_event_title', 10, 3);
add_filter('acf/update_value/name=location', __NAMESPACE__ . '\\update_event_title', 10, 3);

function event_thumbnail_meta_box() {
  add_meta_box(
    'event-thumbnail',
    'Event Thumbnail',
    __NAMESPACE__ . '\\event_thumbnail_meta_box_callback',
    'event',
    'normal',
    'default'
  );
}

function event_thumbnail_meta_box_callback() {
  global $post;
  if( 'publish' == get_post_status( $post ) ){
    echo '<div style="">';
    submit_button( 'Generate an Event Thumbnail', 'primary', 'submit', false, [ 'id' => 'generate-event-thumbnail' ] );
    echo '</div>';
  } else {
    echo '<p>You must first publish this event in order to generate an Event Thumbnail.</p>';
  }
}

add_action( 'add_meta_boxes', __NAMESPACE__ . '\\event_thumbnail_meta_box' );
