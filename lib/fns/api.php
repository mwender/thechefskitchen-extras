<?php
namespace tcw\api;
use function tcw\shortcodes\{event_list};

add_action( 'rest_api_init', function(){
  register_rest_route( 'tcw/v1', '/events', [
    'methods'   => 'GET',
    'callback'  => __NAMESPACE__ . '\\calendar_api',
  ], false );

  register_rest_route( 'tcw/v1', '/event', [
    'methods'   => 'GET',
    'callback'  => __NAMESPACE__ . '\\save_event_thumbnail',
    'args'      => [
      'id'  => [
        'validate_callback' => function( $param, $request, $key ){
          $event = get_post( $param, OBJECT );
          if( ! is_numeric( $param ) )
            return wp_send_json_error( 'Event ID must be numeric!' );
          if( ! $event )
            return wp_send_json_error( 'No event found with ID: ' . $param );
          if( 'event' != get_post_type( $event ) )
            return wp_send_json_error( 'The ID (' . $param . ') provided does not return an Event CPT.' );

          return true;
        },
        'required'  => true,
      ],
    ],
    'permission_callback' => function(){
      return current_user_can( 'edit_others_posts' );
    }
  ], false );
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
  $today = date( 'Y-m-d', strtotime('-360 days') );

  $get_posts_args = [
    'post_type'       => 'event',
    'posts_per_page'  => $args['limit'],
    'meta_key'        => 'start_date',
    'meta_value'      => $today, /* date('Y-m-d') */
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

function save_event_thumbnail( \WP_REST_Request $request ){
  $event_id = $request->get_param('id');
  if( ! defined( 'APIFLASH_ACCESS_KEY' ) )
    return wp_send_json_error( 'Please define a constant called `APIFLASH_ACCESS_KEY` with your API Flash Access Key.' );

  if( function_exists( 'spinupwp_purge_post' ) )
    spinupwp_purge_post( $event_id );

  $permalink = get_permalink( $event_id );
  if( TCW_DEV_ENV )
    $permalink = str_replace( '.local', '.com', $permalink );

  $params = http_build_query([
    'access_key' => APIFLASH_ACCESS_KEY,
    'url' => $permalink,
    'full_page' => true,
  ]);
  uber_log( '🔔 $permalink = ' . $permalink );
  //return false;

  $image_data = file_get_contents( 'https://api.apiflash.com/v1/urltoimage?' . $params );
  $timestamp = current_time( 'Y-m-d_His' );
  $filename = 'screenshot_' . $event_id . '_' . $timestamp . '.jpg';
  $filename_max = 'screenshot-max_' . $event_id . '_' . $timestamp . '.jpg';

  $wp_upload_dir = wp_get_upload_dir();

  file_put_contents( $wp_upload_dir['path'] . '/' . $filename, $image_data );

  resize_image( 'exact', $wp_upload_dir['path'] . '/' . $filename, $wp_upload_dir['path'] . '/' . $filename_max, 1880, 984 );

  $filetype = wp_check_filetype( $filename_max, null );
  $args = [
    'post_title'      => $filename_max,
    'post_mime_type'  => $filetype['type'],
    'guid'            => $wp_upload_dir['url'] . '/' . $filename_max,
    'post_status'     => 'inherit',
    'post_content'    => '',
  ];
  //WP_CLI::line('👉 importing with $args = ' . print_r( $args, true ) . ' and $import_filename = ' . $import_filename );
  $attachment_id = wp_insert_attachment( $args, $wp_upload_dir['path'] . '/' . $filename );
  if( set_post_thumbnail( $event_id, $attachment_id ) ){
    if( function_exists( 'spinupwp_purge_post' ) )
      spinupwp_purge_post( $event_id );

    wp_send_json([
      'thumbnail_saved' => true,
    ], 200 );
  } else {
    wp_send_json([
      'thumbnail_saved' => false,
    ], 401 );
  }
}

/**
 * Resizes an image.
 *
 * @param      string  $method     The method
 * @param      <type>  $image_loc  The image location
 * @param      <type>  $new_loc    The new location
 * @param      <type>  $width      The width
 * @param      <type>  $height     The height
 *
 * @return     bool    ( description_of_the_return_value )
 */
function resize_image($method,$image_loc,$new_loc,$width,$height) {
  if (!array_key_exists('errors', $GLOBALS) || !is_array($GLOBALS['errors'])) { $GLOBALS['errors'] = array(); }

  if (!in_array($method, array( 'max', 'exact' ) ) ) { $GLOBALS['errors'][] = 'Invalid method selected.'; }

  if (!$image_loc) { $GLOBALS['errors'][] = 'No source image location specified.'; }
  else {
  if ((substr(strtolower($image_loc),0,7) == 'http://') || (substr(strtolower($image_loc),0,7) == 'https://')) { } // don't check to see if file exists since it's not local
    elseif (!file_exists($image_loc)) { $GLOBALS['errors'][] = 'Image source file does not exist.'; }
    $extension = strtolower(substr($image_loc,strrpos($image_loc,'.')));
    if (!in_array($extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = 'Invalid source file extension!'; }
  }

  if (!$new_loc) { $GLOBALS['errors'][] = 'No destination image location specified.'; }
  else {
    $new_extension = strtolower(substr($new_loc,strrpos($new_loc,'.')));
    if (!in_array($new_extension,array('.jpg','.jpeg','.png','.gif','.bmp'))) { $GLOBALS['errors'][] = 'Invalid destination file extension!'; }
  }

  $width = abs(intval($width));
  if (!$width) { $GLOBALS['errors'][] = 'No width specified!'; }

  $height = abs(intval($height));
  if (!$height) { $GLOBALS['errors'][] = 'No height specified!'; }

  if (count($GLOBALS['errors']) > 0) { echo_errors(); return false; }

  if (in_array($extension,array('.jpg','.jpeg'))) { $image = @imagecreatefromjpeg($image_loc); }
  elseif ($extension == '.png') { $image = @imagecreatefrompng($image_loc); }
  elseif ($extension == '.gif') { $image = @imagecreatefromgif($image_loc); }
  elseif ($extension == '.bmp') { $image = @imagecreatefromwbmp($image_loc); }

  if (!$image) { $GLOBALS['errors'][] = 'Image could not be generated!'; }
  else {
    $current_width = imagesx($image);
    $current_height = imagesy($image);
    if ((!$current_width) || (!$current_height)) { $GLOBALS['errors'][] = 'Generated image has invalid dimensions!'; }
  }
  if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); echo_errors(); return false; }

  switch( $method ){
    case 'max':
      $new_image = resize_image_max($image,$width,$height);
      break;
    case 'exact':
      $new_image = resize_image_exact($image,$width,$height);
      break;
  }

  if ((!$new_image) && (count($GLOBALS['errors'] == 0))) { $GLOBALS['errors'][] = 'New image could not be generated!'; }
  if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); echo_errors(); return false; }

  $save_error = false;
  if (in_array($extension,array('.jpg','.jpeg'))) { imagejpeg($new_image,$new_loc) or ($save_error = true); }
  elseif ($extension == '.png') { imagepng($new_image,$new_loc) or ($save_error = true); }
  elseif ($extension == '.gif') { imagegif($new_image,$new_loc) or ($save_error = true); }
  elseif ($extension == '.bmp') { imagewbmp($new_image,$new_loc) or ($save_error = true); }
  if ($save_error) { $GLOBALS['errors'][] = 'New image could not be saved!'; }
  if (count($GLOBALS['errors']) > 0) { @imagedestroy($image); @imagedestroy($new_image); echo_errors(); return false; }

  imagedestroy($image);
  imagedestroy($new_image);

  return true;
}

function resize_image_exact( $image, $exact_width, $exact_height ){
  if ( ! array_key_exists( 'errors', $GLOBALS ) || ! is_array( $GLOBALS['errors'] ) ) { $GLOBALS['errors'] = []; }
  $w = imagesx($image); // current width
  $h = imagesy($image); // current height
  if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image could not be resized because it was not a valid image.'; return false; }

  if( ( $w == $exact_width ) && ( $h == $exact_height ) ){ $GLOBALS['errors'][] = 'Image was already ' . $exact_width . 'x' . $exact_height . '.'; return false;  } // no resizing needed

  // try max width first...
  $ratio = $exact_width / $w;
  $new_w = $exact_width;
  $new_h = $h * $ratio;

  // if that didn't work
  if ($new_h > $exact_height) {
    $ratio = $exact_height / $h;
    $new_h = $exact_height;
    $new_w = $w * $ratio;
  }

  // resize the image
  $new_w = round($new_w);
  $new_h = round($new_h);
  $new_image = imagecreatetruecolor($exact_width, $exact_height);
  $whiteBackground = imagecolorallocate( $new_image, 255, 255, 255 );
  imagefill( $new_image, 0, 0, $whiteBackground );

  uber_log( '🔔 $ratio = ' . $ratio );
  if( $ratio < 1 ){
    $extra_w = $exact_width - $new_w;
    $new_x = round( $extra_w/2 );
    $new_y = 0;
  } else {
    $extra_h = $exact_height - $new_h;
    $new_y = round( $extra_h/2 );
    $new_x = 0;
  }

  imagecopyresampled($new_image, $image, $new_x, $new_y, 0, 0, $new_w, $new_h, $w, $h);

  return $new_image;
}

/**
 * Resizes an image to a max width or max height.
 *
 * @param      binary  $image       The image
 * @param      int     $max_width   The maximum width
 * @param      int     $max_height  The maximum height
 *
 * @return     bool    Returns TRUE upon successful save.
 */
function resize_image_max($image, $max_width, $max_height) {
  if (!array_key_exists('errors', $GLOBALS) || !is_array($GLOBALS['errors'])) { $GLOBALS['errors'] = array(); }
  $w = imagesx($image); // current width
  $h = imagesy($image); // current height
  if ((!$w) || (!$h)) { $GLOBALS['errors'][] = 'Image could not be resized because it was not a valid image.'; return false; }

  if (($w <= $max_width) && ($h <= $max_height)) { return $image; } // no resizing needed

  // try max width first...
  $ratio = $max_width / $w;
  $new_w = $max_width;
  $new_h = $h * $ratio;

  // if that didn't work
  if ($new_h > $max_height) {
    $ratio = $max_height / $h;
    $new_h = $max_height;
    $new_w = $w * $ratio;
  }

  // resize the image
  $new_w = round($new_w);
  $new_h = round($new_h);
  $new_image = imagecreatetruecolor($new_w, $new_h);
  imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_w, $new_h, $w, $h);

  return $new_image;
}

function echo_errors() {
  if ((!array_key_exists('errors',$GLOBALS)) || (!is_array($GLOBALS['errors']))) { $GLOBALS['errors'] = array(); }
  foreach ($GLOBALS['errors'] as $error) { echo '<p style="color:red;font-weight:bold;">Error: '.$error.'</p>'; }
}