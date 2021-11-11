<?php

namespace tck\customcolumns;

/**
 * Adds columns to the Event CPT
 *
 * @param      array  $columns  The columns
 *
 * @return     array  Filtered $columns array
 */
function set_event_edit_columns($columns) {
  $columns['event_date']        = __( 'Event Date', 'tck' );
  $columns['food_trucks'] = __( 'Food Trucks', 'tck' );
  $columns['location']    = __( 'Location', 'tck' );

  // Re-order columns
  $columns = [
    'cb' => $columns['cb'],
    'title' => $columns['title'],
    'event_date' => $columns['event_date'],
    'food_trucks' => $columns['food_trucks'],
    'location' => $columns['location'],
    'tags' => $columns['tags'],
  ];
  return $columns;
}
add_filter( 'manage_event_posts_columns', __NAMESPACE__ . '\\set_event_edit_columns' );

/**
 * Adds columns to the Food Truck CPT
 *
 * @param      array  $columns  The columns
 *
 * @return     array  Filtered $columns array
 */
function set_food_trucks_edit_columns($columns) {
  $columns['short_description'] = __( 'Short Description', 'tck' );
  uber_log('$columns = ' . print_r( $columns, true ) );

  // Re-order columns
  $columns = [
    'cb' => $columns['cb'],
    'title' => $columns['title'],
    'short_description' => $columns['short_description'],
    'date' => $columns['date'],
  ];
  return $columns;
}
add_filter( 'manage_food_trucks_posts_columns', __NAMESPACE__ . '\\set_food_trucks_edit_columns' );

/**
 * Sort `Event` CPTs in admin by start_date.
 *
 * @param      <type>  $query  The query
 */
function custom_post_order($query){
  if( is_admin() ){
    if( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] == 'event' ){
      $post_status = get_query_var( 'post_status' );
      if( in_array( $post_status, [ 'publish', 'draft' ]) ){
        $query->set( 'meta_key', 'start_date' );
      } else {
        $query->set( 'meta_query', [
          [
            'key'     => 'start_date',
            'value'   => current_time( 'Y-m-d H:i:s' ),
            'compare' => '>=',
            'type'    => 'DATETIME',
          ]
        ]);
      }
      $query->set( 'orderby', 'meta_value' );
      $query_order = $query->get( 'order' );
      $order = ( $query_order )? $query_order : 'ASC' ;
      $query->set( 'order', $order );
    }
  }
}
add_action('pre_get_posts', __NAMESPACE__ . '\\custom_post_order');

/**
 * Make Event CPT columns sortable.
 *
 * @param      array  $columns  The columns
 *
 * @return     array  The columns array filtered to include our sortable columns.
 */
function event_sortable_columns( $columns ){
  $columns['event_date'] = 'event_date';

  return( $columns );
}
add_filter( 'manage_edit-event_sortable_columns', __NAMESPACE__ . '\\event_sortable_columns' );

/**
 * Populates the custom columns for the Team Member CPT admin listing.
 *
 * @param      string  $column   The column
 * @param      int     $post_id  The post identifier
 */
function custom_event_column( $column, $post_id ){
  switch( $column ){
    case 'event_date':
      $start_date = get_post_meta( $post_id, 'start_date', true );
      $start_date = new \DateTime( $start_date );
      $end_date = get_post_meta( $post_id, 'end_date', true );
      $end_date = new \DateTime( $end_date );
        echo $start_date->format( 'm/d/y ga' ) . '-' . $end_date->format( 'ga' );
      break;

    case 'food_trucks':
      $food_trucks = get_post_meta( $post_id, 'food_trucks' );
      if( $food_trucks && is_array( $food_trucks ) ){
        $food_trucks = $food_trucks[0];
        foreach( $food_trucks as $food_truck_id ){
          $food_truck_list[] = get_the_title( $food_truck_id );
        }
        echo '<ul style="margin: 0;"><li>' . implode( '</li><li>', $food_truck_list ) . '</li></ul>';
      }
      break;

    case 'location':
      $location_id = get_post_meta( $post_id, 'location', true );
      if( $location_id && is_numeric( $location_id ) )
        echo get_the_title( $location_id );
      break;
  }
}
add_action( 'manage_event_posts_custom_column', __NAMESPACE__ . '\\custom_event_column', 10, 2 );

/**
 * Adds column content to Food Truck CPT custom columns
 *
 * @param      string  $column   The column
 * @param      int     $post_id  The post identifier
 */
function custom_food_trucks_column( $column, $post_id ){
  switch( $column ){
    case 'short_description':
      echo get_post_meta( $post_id, 'short_description', true );
      break;
  }
}
add_action( 'manage_food_trucks_posts_custom_column', __NAMESPACE__ . '\\custom_food_trucks_column', 10, 2 );