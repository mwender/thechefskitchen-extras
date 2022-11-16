<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert};
use function tcw\templates\{render_template};

/**
 * Renders a listing of Events.
 *
 * @return     string  HTML and CSS for the Event Calendar.
 */
function event_list( $atts ){
  global $post;

  $args = shortcode_atts([
    'limit'     => -1,
    'tag'       => null,
    'template'  => 'event-list-01',
    'tag_id'    => null,
    'dataonly'  => false,
    'weeks'     => 3,
  ], $atts );

  $data = [];

  $timestr = ( is_numeric( $args['weeks'] ) )? sprintf( '-%d weeks', $args['weeks'] ) : '-3 weeks' ;
  $start_date = date( 'Y-m-d', strtotime( $timestr ) );
  $today = current_time( 'U' );

  $get_posts_args = [
    'post_type'       => 'event',
    'posts_per_page'  => $args['limit'],
    'meta_key'        => 'start_date',
    'meta_value'      => $start_date,
    'orderby'         => 'meta_value',
    'order'           => 'ASC',
    'meta_compare'    => '>=',
    'value'           => $start_date,
    'type'            => 'DATE',
  ];

  $single_event = false;

  if( 'location' == get_post_type( $post ) ){
    $location_tag_id = get_post_meta( $post->ID, 'location_tag', true );
    if( $location_tag_id && is_numeric( $location_tag_id ) )
      $args['tag_id'] = $location_tag_id;
  } else if( 'event' == get_post_type( $post ) ){
    $single_event = true;
    $location_id = get_post_meta( $post->ID, 'location', true );
    $location_tag_id = get_post_meta( $location_id, 'location_tag', true );
    if( $location_tag_id && is_numeric( $location_tag_id ) )
      $args['tag_id'] = $location_tag_id;

    // Remove data based query
    unset( $get_posts_args['meta_key'], $get_posts_args['meta_value'], $get_posts_args['orderby'], $get_posts_args['order'], $get_posts_args['meta_compare'], $get_posts_args['value'], $get_posts_args['type']  );
    $get_posts_args['p'] = $post->ID;
  }

  wp_enqueue_script( 'elementor-tab-enhancers' );

  $data['no_events'] = get_alert([
    'title'       => 'More Foodie Events Coming Soon!',
    'type'        => 'info',
    'description' => 'We will be adding new events to our calendar soon. Until then, <a href="#get-connected">sign up to be notified</a>.',
  ]);

  if( ! is_null( $args['tag'] ) )
    $get_posts_args['tag'] = $args['tag'];

  if( ! is_null( $args['tag_id'] ) )
    $get_posts_args['tag_id'] = $args['tag_id'];

  $futureposts = get_posts( $get_posts_args );
  if( $futureposts ){
    $current_day = false;
    $x = 0;
    foreach( $futureposts as $post ){
      $events[$x]['title'] = get_the_title( $post->ID );

      // Get the location
      $location_id = get_post_meta( $post->ID, 'location', true );
      $events[$x]['location']['name'] = ( $location_id )? get_the_title( $location_id ) : false ;

      $events[$x]['location']['thumbnail'] = ( has_post_thumbnail( $location_id ) )? get_the_post_thumbnail_url( $location_id, 'full' ) : false ;

      // Get all Tags
      $tags = get_the_terms( $post, 'post_tag' );
      $css_classes = [];
      if( $tags ){
        foreach( $tags as $tag ){
          $css_classes[] = $tag->slug;
          if( 'cancelled' == $tag->slug )
            $events[$x]['cancelled'] = true;
        }
      }
      $events[$x]['css_classes'] = implode( ' ', $css_classes );

      // Build the address
      $address_field = get_field( 'address', $location_id );
      $format = '<span class="segment-street_number">%s</span> <span class="segment-street_name_short">%s</span><br><span class="segment-city">%s</span>, <span class="segment-state_short">%s</span> <span class="segment-post_code">%s</span>';
      $street_name = ( isset( $address_field['street_name_short'] ) && ! empty( $address_field['street_name_short'] ) )? $address_field['street_name_short'] : $address_field['street_name'];
      $address = sprintf( $format, $address_field['street_number'], $street_name, $address_field['city'], $address_field['state_short'], $address_field['post_code'] );
      $events[$x]['location']['address'] = $address;

      // Google Map Link
      $events[$x]['location']['link'] = 'https://www.google.com/maps/search/?api=1&query=' . urlencode( $address_field['street_number'] . ' ' . $street_name . ' ' . $address_field['city'] . ' ' . $address_field['state_short'] . ' ' . $address_field['post_code'] );

      // Get Food Trucks
      $food_trucks = get_post_meta( $post->ID, 'food_trucks' );
      if( $food_trucks && is_array( $food_trucks ) ){
        $food_trucks = $food_trucks[0];
        $food_truck_list = [];
        foreach( $food_trucks as $food_truck_id ){
          $food_truck_list[] = [
            'name' => get_the_title( $food_truck_id ),
            'short_description' => get_post_meta( $food_truck_id, 'short_description', true ),
            'website' => get_post_meta( $food_truck_id, 'website', true ),
            'thumbnail' => get_the_post_thumbnail_url( $food_truck_id, 'full' ),
          ];
        }
        $events[$x]['food_trucks'] = $food_truck_list;
        $total_foodtrucks = count( $food_truck_list );
        if( $total_foodtrucks <= 4 ){
          $parent_col_class = 'col-sm-12';
          $foodtruck_col_class = 'col-sm-6';
        } else if( $total_foodtrucks > 4 ){
          $parent_col_class = 'col-sm-12';
          $foodtruck_col_class = 'col-sm-4';
        }
        $events[$x]['parent_col_class'] = $parent_col_class;
        $events[$x]['foodtruck_col_class'] = $foodtruck_col_class;
      }

      $start_date = new \DateTime( get_post_meta( $post->ID, 'start_date', true ) );
      $end_date = new \DateTime( get_post_meta( $post->ID, 'end_date', true ) );
      //$fulldate_format = ( 1 == $args['limit'] )? 'l, M j, Y' : 'm/d/y';
      //$events[$x]['current_day']['fulldate'] = $start_date->format( $fulldate_format );

      if( $start_date->format( 'U') < $today )
        $events[$x]['css_classes'] .= ' past-event';

      if( $single_event ){
        $events[$x]['current_day']['fulldate'] = $start_date->format( 'M j, Y' ) . ' • ' . $start_date->format( 'g' ) . '-' . $end_date->format( 'ga' );
      } else {
        $events[$x]['current_day']['fulldate'] = $start_date->format( 'm/d/y' );
      }

      $events[$x]['current_day']['day'] = $start_date->format( 'D' );
      $events[$x]['current_day']['date'] = $start_date->format( 'j' );
      $events[$x]['current_day']['month'] = $start_date->format( 'M' );
      if( ! $current_day || $current_day != $start_date->format( 'm/d/y' ) ){
        $events[$x]['current_day']['display'] = true;
        $current_day = $start_date->format( 'm/d/y' );
      } else {
        $events[$x]['current_day']['display'] = false;
      }

      $events[$x]['start_time'] = $start_date->format( 'ga' );
      $events[$x]['end_time'] = $end_date->format( 'ga' );

      $x++;
    }
    $data['events'] = $events;
  }
  //uber_log('🔔 $data = ' . print_r( $data, true ) );

  wp_reset_postdata();
  if( $args['dataonly'] )
    return $data;

  $template = render_template( $args['template'], $data );
  return $template;
}
add_shortcode( 'event_list', __NAMESPACE__ . '\\event_list' );