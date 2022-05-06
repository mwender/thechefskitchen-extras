<?php
namespace tcw\shortcodes;
use function tcw\utilities\{get_alert};

function event_calendar( $atts ){
  wp_enqueue_style( 'caleandar' );
  wp_enqueue_script( 'caleandar-init' );

  //$elementor_preview_active = \Elementor\Plugin::$instance->editor->is_edit_mode();
  $elementor_preview_active = is_elementor_edit_mode();
  if( $elementor_preview_active ){
    $template = get_alert([ 'description' => 'You\'ve successfully setup the Event Calendar shortcode. The calendar will display here when viewing this page from the frontend.' ]);
  } else {
    $calendar_info_html = get_alert(['title' => 'Loading calendar...', 'description' => 'Loading the calendar. One moment...', 'type' => 'info']);
    $template = '<div id="calendar">' . $calendar_info_html . '</div>';
  }
  return $template;
}
add_shortcode( 'eventcalendar', __NAMESPACE__ . '\\event_calendar' );