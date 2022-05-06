<?php
namespace tcw\shortcodes;
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