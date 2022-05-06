<?php
namespace tcw\shortcodes;

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