<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert,posts_orderby_lastname};
use function tcw\templates\{render_template};

/**
 * Renders an Elementor button.
 *
 * @param      array  $atts {
 *   @type  string  $icon       Font Awesome icon class name.
 *   @type  string  $icon_align Aligns the icon `left` or `right`. Default `left`.
 *   @type  string  $link       The URL the button will point to.
 *   @type  string  $target     Value of the target attribute for the anchor tag. Defaults to `_self`.
 *   @type  string  $text       The text for the button. Default "Click Here".
 *   @type  string  $size       The size of the button ( xs, sm, md, lg, xl ). Defaults to `sm`.
 *   @type  string  $style      Styling applied to the style attribute of the parent anchor.
 * }
 *
 * @return     string  The HTML for the button.
 */
function button( $atts ){
  $args = shortcode_atts( [
    'icon'        => null,
    'icon_align'  => 'left',
    'link'        => '#',
    'target'      => '_self',
    'text'        => 'Click Here',
    'size'        => 'sm',
    'style'       => null,
  ], $atts );

  $data = $args;

  return render_template( 'button', $data );
}
add_shortcode( 'button', __NAMESPACE__ . '\\button' );

/**
 * Renders the Event Calendar
 *
 * @return     string  HTML and CSS for the Event Calendar.
 */
function event_list( $atts ){
  $args = shortcode_atts([
    'tag'       => null,
    'template'  => 'event-list-01',
  ], $atts );

  wp_enqueue_script( 'elementor-tab-enhancers' );

  $data = [];
  $today = date('Y-m-d');

  $data['no_events'] = get_alert([
    'title'       => 'More Foodie Events Coming Soon!',
    'type'        => 'info',
    'description' => 'We will be adding new events to our calendar soon. Until then, <a href="#get-connected">sign up to be notified</a>.',
  ]);

  $get_posts_args = [
    'post_type'       => 'event',
    'posts_per_page'  => -1,
    'meta_key'        => 'start_date',
    'meta_value'      => date('Y-m-d'),
    'orderby'         => 'meta_value',
    'order'           => 'ASC',
    'meta_compare'    => '>=',
    'value'           => $today,
    'type'            => 'DATE',
  ];
  if( ! is_null( $args['tag'] ) ){
    $get_posts_args['tag'] = $args['tag'];
  }

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
      }

      $start_date = new \DateTime( get_post_meta( $post->ID, 'start_date', true ) );
      $end_date = new \DateTime( get_post_meta( $post->ID, 'end_date', true ) );
      $events[$x]['current_day']['fulldate'] = $start_date->format( 'm/d/y' );
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
  $style = '';

  $template = render_template( $args['template'], $data );
  return $style.$template;
}
add_shortcode( 'event_list', __NAMESPACE__ . '\\event_list' );

/**
 * Renders a handlebars template
 *
 * @param      array  $atts {
 *   @type  string  $data         Key/Value pairs of data to send to the template. Formatted like
 *                                so `key1|value1::key2|value2`. `meta` is a special key which causes the
 *                                shortcode to check the current post for a custom field with the
 *                                name given by the "value". Example: `meta|radius_scheduler` will
 *                                check the current post for a meta field called `radius_scheduler`.
 *                                If found, the $data array passed to the template will have a key
 *                                called `radius_scheduler` with the value of the custom field.
 *   @type  string  $hidecss      CSS selector used to hide this element if $hideifempty is TRUE.
 *                                Default null.
 *   @type  bool    $hideifempty  Used with the special key `meta` in $data. If any meta
 *                                meta values are emtpy, hide this template if `true`.
 *                                Defaults to TRUE.
 *   @type  string  $hideelement  ID of an element to hide when the template rendered by
 *                                this shortcode is shown. Default NULL.
 *   @type  string  $template     The template we are rendering. Default NULL.
 * }
 *
 * @return     string  HTML for the template.
 */
function rendertemplate( $atts ){
  global $post;

  $args = shortcode_atts( [
    'data'        => null,
    'hidecss'     => null,
    'hideifempty' => 1,
    'hideelement' => null,
    'template'    => null,
  ], $atts );

  $data = [];

  if ( $args['hideifempty'] === 'false' ) $args['hideifempty'] = false;
  $args['hideifempty'] = (bool) $args['hideifempty'];

  $args['data'] = ( stristr( $args['data'], '::' ) )? explode( '::', $args['data'] ) : [ $args['data'] ];
  foreach( $args['data'] as $datum ){
    if( ! stristr( $datum, '|' ) )
      return get_alert(['title' => 'Invalid Data', 'description' => 'Please format the data attribute like so: <code>key1:value1,key2:value2</code>.']);
    $datum = explode( '|', $datum );
    if( 'meta' == $datum[0] ){
      $meta = get_post_meta( $post->ID, $datum[1], true );
      if( is_array( $meta ) )
        $meta = $meta[0];
      if( empty( $meta ) && true == $args['hideifempty'] ){
        if( ! empty( $args['hidecss'] ) ){
          return '<style>' . $args['hidecss'] . '{display: none;}</style>';
        } else {
          return null;
        }
      }
      $data[$datum[1]] = $meta;
    } elseif ( 'post' == $datum[0] ) {
      switch( $datum[1] ){
        case 'title':
        case 'post_title':
          $data[$datum[1]] = get_the_title( $post->ID );
          break;

        default:
          $data[$datum[1]] = 'No logic for retrieving `$post->' . esc_attr( $datum[1] ) . '`.';
          break;
      }
    } else {
      $data[$datum[0]] = $datum[1];
    }
  }

  if( is_null( $args['template'] ) ){
    return get_alert(['title' => 'Missing Template', 'description' => 'Please add a template attribute to this shortcode.' ]);
  }

  $html = render_template( $args['template'], $data );
  if( $args['hideelement'] )
    $html.= '<style>#' . $args['hideelement'] . '{display: none;}</style>';
  return $html;
}
add_shortcode( 'rendertemplate', __NAMESPACE__ . '\\rendertemplate' );

/**
 * Show a listing of child pages.
 *
 * @param      array  $atts {
 *   @type  string  $orderby    The column we are ordering by. Defaults to "menu_order".
 *   @type  string  $sort       How we are ordering the results. Defaults to ASC.
 *   @type  string  $parent     The post_title of the parent page whose child pages we want to list. Defaults to `null`.
 * }
 *
 * @return     string  HTML for the subpage list.
 */
function subpage_list( $atts ){
  $args = shortcode_atts( [
    'orderby' => 'menu_order',
    'sort'    => 'ASC',
    'parent'  => null,
  ], $atts );

  global $post;
  $query_args = [
    'child_of'    => $post->ID,
    'title_li'    => null,
    'sort_column' => $args['orderby'],
    'sort_order'  => $args['sort'],
    'echo'        => false,
  ];

  if( ! is_null( $args['parent'] ) ){
    $args['parent'] = html_entity_decode( $args['parent'] );
    $parent = get_page_by_title( $args['parent'] );
    if( $parent ){
      $query_args['parent'] = $parent->ID;
      $query_args['child_of'] = $parent->ID;
    }
  }
  return '<ul>' . wp_list_pages( $query_args ) . '</ul>';
}
add_shortcode( 'subpage_list', __NAMESPACE__ . '\\subpage_list' );
