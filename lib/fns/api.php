<?php
namespace tcw\api;
use function tcw\shortcodes\{event_list};

add_action( 'rest_api_init', function(){
  register_rest_route( 'tcw/v1', '/events', [
    'methods'   => 'GET',
    'callback'  => __NAMESPACE__ . '\\calendar_api',
  ], $override = false );
});

function calendar_api(){
  //return event_list( [ 'dataonly' => true ] );

  $args = [
    'limit'     => -1,
    'tag'       => null,
    'template'  => 'event-list-01',
    'tag_id'    => null,
    'dataonly'  => false,
  ];
  $today = date('Y-m-d');

  $get_posts_args = [
    'post_type'       => 'event',
    'posts_per_page'  => $args['limit'],
    'meta_key'        => 'start_date',
    'meta_value'      => date('Y-m-d'),
    'orderby'         => 'meta_value',
    'order'           => 'ASC',
    'meta_compare'    => '>=',
    'value'           => $today,
    'type'            => 'DATE',
  ];
  if( ! is_null( $args['tag'] ) )
    $get_posts_args['tag'] = $args['tag'];

  if( ! is_null( $args['tag_id'] ) )
    $get_posts_args['tag_id'] = $args['tag_id'];

  $futureposts = get_posts( $get_posts_args );
  if( $futureposts ){
    $current_day = false;
    $x = 0;
    $events = [];
    foreach( $futureposts as $post ){
      $start_date = new \DateTime( get_post_meta( $post->ID, 'start_date', true ) );
      $starttime = $start_date->format( 'g:i' );
      if( stristr( $starttime, ':00' ) )
        $starttime = str_replace( ':00', '', $starttime );

      $end_date = new \DateTime( get_post_meta( $post->ID, 'end_date', true ) );
      $endtime = $end_date->format( 'g:ia' );
      if( stristr( $endtime, ':00' ) )
        $endtime = str_replace( ':00', '', $endtime );

      // Get all Tags
      $tags = get_the_terms( $post, 'post_tag' );
      $css_classes = [];
      $terms = [];
      if( $tags ){
        foreach( $tags as $tag ){
          $css_classes[] = $tag->slug;
          $terms[] = '<span class="' . $tag->slug .'">' . $tag->name . '</span>';
          if( 'cancelled' == $tag->slug )
            $events[$x]['cancelled'] = true;
        }
      }
      $events[$x]['css_classes'] = implode( ' ', $css_classes );
      $events[$x]['terms'] = implode( ' ' , $terms );

      $events[$x]['date'] = [
        'year'      => intval( $start_date->format( 'Y' ) ),
        'day'       => intval( $start_date->format( 'd' ) ),
        'month'     => intval( $start_date->format( 'm' ) ) - 1,
        'starttime' => $starttime,
        'endtime'   => $endtime,
      ];
      $location_id = get_post_meta( $post->ID, 'location', true );
      $events[$x]['title'] = "<span class=\"event-time\">$starttime-$endtime</span> " . $events[$x]['terms'];
      $events[$x]['permalink'] = get_the_permalink( $post->ID );
      $x++;
    }
  }

  return $events;
}