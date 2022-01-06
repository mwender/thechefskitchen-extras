=== The Chef's Kitchen Extras ===
Contributors: TheWebist
Donate link: https://mwender.com/
Tags: shortcodes
Requires at least: 5.7
Tested up to: 5.8.1
Requires PHP: 7.4
Stable tag: 0.6.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Extras for the The Chef's Kitchen website.

== Description ==

This plugin provides extra functionality for The Chef's Kitchen website.

=== Elementor Style Button Shortcode ===

Use `[button/]` to render an "Elementor-style" button.

```
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
```

=== Event List Shortcode ===

Use `[event_list]` to render a responsive Event List/Calendar.

```
/**
 * Renders the Event Calendar
 *
 * @return     string  HTML and CSS for the Event Calendar.
 */
 ```

=== Render Template Shortcode ===

```
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
```

=== Sub Pages List ===

Add `[subpage_list/]` to display a list of sub pages.

```
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
```

== Changelog ==

= 0.6.0 =
* Tags are now included as CSS class names in the parent element for events.
* Adding a tag of `cancelled` to an event will display a "Cancelled - Inclement Weather" banner on the event.

= 0.5.1 =
* Adding `draft` post_status as a possible option for showing "all" events in admin Event listing.

= 0.5.0 =
* Adding message to `[event_list]` shortcode when no upcoming events are found.
* Updating default Event admin listing to only show future events.

= 0.4.0 =
* Hyperlinking Food Truck name to website in the Event Calendar.
* Removing Team Member List shortcode.
* Removing Webinar Link shortcode.
* Documenting `[event_list]` shortcode.

= 0.3.0 =
* Updating Event Calendar layout.
* Adding display for Location Featured Images.

= 0.2.1 =
* Bugfix: Proper check for `GOOGLE_MAPS_API_KEY`.

= 0.2.0 =
* Mobile styling for Event Calendar.

= 0.1.2 =
* Updating composer lock file.

= 0.1.1 =
* Removing Google Maps API key.

= 0.1.0 =
* Initial setup. Porting from the "TKA Extras" plugin.
* Updating namespaces, constants, etc to reference "The Chef's Kitchen".
