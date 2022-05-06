<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert};

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