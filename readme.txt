=== WP Listings ===
Contributors: agentevolution, davebonds, chadajohnson, idxbroker
Tags: real estate, listings, property, properties, listing search, idx, agentpress
Requires at least: 3.7
Tested up to: 4.1
Stable tag: 1.2.1

Creates a portable real estate listing management system. Designed to work with any theme using built-in templates.

== Description ==

WP Listings uses custom post types, taxonomies, templates, and widgets to create a listing management system for WordPress. It includes custom templates and widgets for front end display.

View a demo of the plugin in action: [WP Listings demo](http://demo.wp-listings.com/)

You can use the taxonomy creation tool to create your own way of classifying listings (i.e. bedrooms, bathrooms, locations, price ranges) and use those taxonomies to allow users to search for listings. Also includes ability to reorder the taxonomies as needed.

Default taxonomies (and terms):

* Status (Active, Sold, Pending, For Rent, Reduced, New)
* Property Types (Residential, Condo, Townhome, Commercial)
* Location
* Features

Also includes a Featured Listings widget to display listings in any taxonomy (Property type, Status, Location, etc.) and display them in a custom number of columns. Plus, a Search widget to allow visitors to search your listings by taxonomy.

If you're using the AgentPress listings plugin, we've made it easy to switch to WP Listings. The plugin uses the same post type name and data fields (plus several more) so all your entered listing data will remain in place.

Custom data fields include:

* Price
* Address
* MLS Number
* Year Built
* Floors
* Square Feet
* Lot Square Feet
* Bedrooms
* Bathrooms
* Pool
* Open House date and time
* Photo gallery
* Video or virtual tour
* Map
* Home Summary
* Kitchen Summary
* Living Room
* Master Suite
* School and Neighborhood Info

Single listings display the custom data automatically with no need to insert shortcodes to display listing data. If it's entered, it will display on the page.

Integrates with the [Genesis Agent Profiles](http://wordpress.org/plugins/genesis-agent-profiles/) plugin to display the listing agent(s).

Single listings include a contact form for visitors to inquire about a property, or the form can be replaced with your own form plugin shortcode.

Allows for any number of custom single listing templates to be created and displayed on a per listing basis.

Premium Single Listing Templates available at [agentevolution.com](http://www.agentevolution.com/product-category/listing-templates/).

Feel free to contribute to this project on [Github](https://github.com/agentevolution/wp-listings).

== Installation ==

1. Upload the entire `wp-listings` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Begin creating listings and listing taxonomies

= How to use the listings shortcode =

= Basic usage =
Just enter the following shortcode on any post or page

`[listings]`

= Advanced usage =
The shortcode accepts the following parameters:

`id` = listing post id (accepts one or more id's), exclusive, cannot be combined with other parameters, except for columns
`limit` = limit the number of posts to show, defaults to all
`columns` = display output in columns, accepts values 2-6, default is 1 column
`taxonomy` = taxonomy to display (must be used with the term parameter)
`term` = term to display (must be used with the taxonomy parameter)

Example advanced usage:
`[listings taxonomy="status" term="active" limit="10" columns="3"]`
This will display all listings in the "Status" taxonomy, assigned to the "Active" term, limited to 10 listings, in 3 columns

== Frequently Asked Questions ==

= I already use the AgentPress Listings plugin, can I use this plugin? =

Yes. This plugin can be used instead of the AgentPress Listings plugin. It uses the same post type name and custom field names (along with several new fields), so the posts you've added using AgentPress, along with the associated meta data, will remain attached to the listing post. Just be sure to deactivate AgentPress before activating WP Listings.

= My theme already has a single listing template. How do I use the one provided with the plugin? =

Some themes may already have a `single-listing.php` (and archive.php for listing archives) to display listings with the same post type name of 'listings'. Templates placed within your theme folder have precedence. To use the template(s) provided with the plugin, delete the `single-listing.php` and/or the `archive-listing.php` templates within your theme's folder (recommended to make a backup).

= How can I create a custom single listing template? =

Name your template file `single-listing-CUSTOM-NAME.php` (replace CUSTOM NAME with your own descriptive name). You can use the single-listing.php within the plugins /includes/views/ folder for a guide on how to display the post type data. You'll need to include the following block of text at the top of your custom template:
`/*
Single Listing Template: Test Template
Description: Give it a description to help identify
*/`

= How can I remove the default property status terms or property type terms? =

Its possible to remove the default property status terms by using a filter in your theme or custom plugins. Here is an example for the status terms:

`/* Remove Default Status Terms from WP Listings */
add_filter( 'wp_listings_default_status_terms', 'custom_default_status_terms' );
function custom_default_status_terms() {
  $status_terms = array();
  return $status_terms;
}`

Here is an example for the property terms:

`/* Remove Default Property Terms from WP Listings */
add_filter( 'wp_listings_default_property_type_terms', 'custom_default_property_type_terms' );
function custom_default_property_type_terms() {
  $property_type_terms = array();
  return $property_type_terms;
}`

For more FAQ's visit [agentevolution.com](http://www.agentevolution.com/shop/wp-listings/)

== Screenshots ==

1. Listings Admin screen

2. Single Listing Edit screen

3. Register taxonomy screen

4. Featured Listing Widget settings

5. Featured Listing Widget display

6. Listing Search Widget display

7. Single Listing template display

8. Listing archive template display

== Changelog ==

= 1.2.1 =
* Fixed i18n in shortcode output h/t newlocalmedia

= 1.2 =
* Added basic schema support to Single Listings Template (single-listing.php)
* Added basic anti-spam check to native contact form in single-listing.php
* Added support for Jetpack publicize and markdown editors
* Added Jetpack shortcode links in messages about shortcodes
* Added DNS Prefetch Support for scripts used on Single Listings Template
* Added translation to text strings in listings shortcode
* Minified CSS for better site performance, SCSS files included
* Updates to script calls to improve site performance
* Updated jQuery Validate to 1.13.1
* Updated to Font Awesome 4.3.0
* Updated .pot file
* Fixed WP 4.1 issue with photo gallery editor meta box

= 1.1.3 =
* Set with_front on rewrite rules for taxonomies. h/t bhubbard
* Fix undefined index for default state

= 1.1.2 =
* Set with_front parameter to false in rewrite rules
* Update for WP 4.0 compatibility
* Update Font Awesome version number and URL
* Compatibility with Equity theme framework

= 1.1.1 =
* CSS fix for thumbnail overlays with shortcode and archive pages
* Fix for undefined variables in featured istings widget and single listing contact form
* Make default registered terms (status, property-type) filterable
* Make default taxonomy names and slugs translatable, improve translation

= 1.1 =
* Add `[listings]` shortcode to output listings on any post or page
* Add ability to change permalink slug to prevent conflicts
* Rewrite backend settings fields options to simplify

= 1.0.8 =
* Add function to flush rewrite rules on plugin deactivation

= 1.0.7 =
* Add Genesis CPT archive settings support
* Remove widget list item margins affecting some themes

= 1.0.6 =
* Add classes to search widget output for additional styling
* Add priority to author box removal action on Genesis HTML5 themes
* Update Font Awesome version number and use minified version
* Change default taxonomies to be hierarchical (except features)

= 1.0.5 =
* Updated class output for listing status overlay to remove spaces and replace with hyphens
* Remove unused Categories column from admin page
* Remove faulty responsive video CSS. Use fitvids.js instead for videos on single listings

= 1.0.4 =
* Add HTML classes for CSS layout compatibility with a number of various themes
* Rename translation template file

= 1.0.3 =
* Fix for connected agents markup conditional function call

= 1.0.2 =
* CSS fix for widget overlays on themes that absolutley positioned them

= 1.0.1 =
* Added ability to select an image size in the Featured Listing widget. This allows you to set a custom image size in your theme.

= 1.0 =
* Initial public release

== Credits ==

Uses code from the following plugins:

[Single Post Template](http://wordpress.org/plugins/single-post-template/) by Nathan Rice

[AgentPress Listings](http://wordpress.org/plugins/agentpress-listings/) by StudioPress

[AgentPress Listings Taxonomy Reorder](http://wordpress.org/plugins/agentpress-listings-taxonomy-reorder/) by Robert Iseley