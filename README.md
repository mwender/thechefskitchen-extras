# The Chef's Kitchen Extras #
**Contributors:** [thewebist](https://profiles.wordpress.org/thewebist/)  
**Donate link:** https://mwender.com/  
**Tags:** shortcodes  
**Requires at least:** 5.7  
**Tested up to:** 5.9.1  
**Requires PHP:** 7.4  
**Stable tag:** 1.7.2  
**License:** GPLv2 or later  
**License URI:** https://www.gnu.org/licenses/gpl-2.0.html  

Extras for the The Chef's Kitchen website.

## Description ##

This plugin provides extra functionality for The Chef's Kitchen website.

# Elementor Style Button Shortcode #

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

# Event List Shortcode #

Use `[event_list]` to render a responsive Event List/Calendar.

```
/**
 * Renders the Event Calendar
 *
 * @return     string  HTML and CSS for the Event Calendar.
 */
 ```

# Render Template Shortcode #

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

# Sub Pages List #

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

## Changelog ##

### 1.7.2 ###
* Purging page cache before Event Thumbnail generation and after assigning that image as the Post Thumbnail.

### 1.7.1 ###
* Purging page cache when saving Event thumbnail.

### 1.7.0 ###
* Adding auto-generation of Event thumbnails.
* Updating admin listing for Event CPTs. Listing Food Trucks via their Featured Images and adjusting the column widths.
* Adding Featured Image to the admin listing for Event CPTs.

### 1.6.1 ###
* Updating `[event_list/]` shortcode to add `.past-event` to events based on the event's End Date.

### 1.6.0 ###
* Adding `.past-event` to events older than today along with CSS for indicating old events.

### 1.5.0 ###
* Adding `weeks` attribute to `[event_list/]` to allow for setting the number of weeks back the shortcode displays events.

### 1.4.1 ###
* Removing comma between month and year in minimized `caleandar.min.js`.

### 1.4.0 ###
* Updating calendar to show events starting 360 days in the past.
* Removing comma between month and year in calendar title.

### 1.3.1 ###
* BUGFIX: Ensuring variable is an array.

### 1.3.0 ###
* Updating the `[single_event_template]` shortcode to retrieve the "Single Event Template" from the current location if used in the `post_content` for a Location CPT.

### 1.2.2 ###
* BUGFIX: Updating `[event_list]` to query by the current Post ID if we are using it on an Event CPT.

### 1.2.1 ###
* BUGFIX: Dynamically generating Events API endpoint URL for `caleandar.init.js`.

### 1.2.0 ###
* New `[eventcalendar]` shortcode powered by `calenandar.js`.
* Backend support for Single Event views.
  * Added "Single Event Template" option for Location CPTs.
  * `[single_event_template]` shortcode for displaying a Location's Elementor template on a Single Event.
* Adding `is_elementor_edit_mode()` for checking if Elementor is in Edit Mode.

### 1.1.2 ###
* Adding `parent_col_class` for sizing `event-list-03.hbs` according to number of Food Trucks.

### 1.1.1 ###
* Adding "Social Graphic Page" option to "Locations" ACF Options.

### 1.1.0 ###
* Adding `event-list-03.hbs` template with auto-sizing food truck logos.

### 1.0.1 ###
* Adjusting vertical alignment of "Cancelled" banner on `.event-list-two` layout.

### 1.0.0 ###
* Adding "Cancelled" banner option to `.event-list-two` layout for events.

### 0.9.9 ###
* BUGFIX: Running `wp_reset_postdata()` inside `[event_list]`.

### 0.9.8 ###
* BUGFIX: Removing `is_int` check on `limit` option for `[event_list]` shortcode.

### 0.9.7 ###
* Adding `limit` option for `[event_list]` shortcode.

### 0.9.6 ###
* Setting `.event` content to full height of columns.

### 0.9.5 ###
* Adjusting `.event` layout to be full width on mobile.

### 0.9.4 ###
* BUGFIX: Adding `\\` to properly call `get_alt_heading()` inside NAMESPACE.

### 0.9.3 ###
* Adding `[altheading]` shortcode for retreiving a post's "Alternate Heading" custom field.

### 0.9.2 ###
* "Location Tag" support for Location CPTs.

### 0.9.1 ###
* Updating Clinton event styling.

### 0.9.0 ###
* Adding "Thumbnail" column for admin Food Trucks CPT listing.
* Updating namespace.
* Adding `template` attribute for `[event_list]` shortcode to allow for selecting the shortcode's template.
* New Event List display option which includes Food Truck logos.

### 0.8.0 ###
* Adding `elementor-tab-enhancers.js`.
* Adding flex styling for Elementor tabs.

### 0.7.0 ###
* Adding "tag" attribute to `[event_list]` shortcode.
* Automatically saving Event titles in `<date>, <time> at <location>` format/

### 0.6.1 ###
* Bottom margin for Food Truck entries on desktop.

### 0.6.0 ###
* Tags are now included as CSS class names in the parent element for events.
* Adding a tag of `cancelled` to an event will display a "Cancelled - Inclement Weather" banner on the event.

### 0.5.1 ###
* Adding `draft` post_status as a possible option for showing "all" events in admin Event listing.

### 0.5.0 ###
* Adding message to `[event_list]` shortcode when no upcoming events are found.
* Updating default Event admin listing to only show future events.

### 0.4.0 ###
* Hyperlinking Food Truck name to website in the Event Calendar.
* Removing Team Member List shortcode.
* Removing Webinar Link shortcode.
* Documenting `[event_list]` shortcode.

### 0.3.0 ###
* Updating Event Calendar layout.
* Adding display for Location Featured Images.

### 0.2.1 ###
* Bugfix: Proper check for `GOOGLE_MAPS_API_KEY`.

### 0.2.0 ###
* Mobile styling for Event Calendar.

### 0.1.2 ###
* Updating composer lock file.

### 0.1.1 ###
* Removing Google Maps API key.

### 0.1.0 ###
* Initial setup. Porting from the "TKA Extras" plugin.
* Updating namespaces, constants, etc to reference "The Chef's Kitchen".
