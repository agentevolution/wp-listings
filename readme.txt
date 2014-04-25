=== WP Listings ===
Contributors: agentevolution, davebonds, chadajohnson
Tags: real estate, listings, property, properties, listing search, idx
Requires at least: 3.2
Tested up to: 3.9
Stable tag: 0.1.0

WP Listings is a WordPress listings plugin that uses custom post types to add a directory of real estate listings that is searchable and can be displayed through widgets and custom templates.

== Description ==

The WP Listings plugin uses custom post types, custom taxonomies, and widgets to create a listings management system for WordPress. It includes custom templates and widgets for front end display.

You can use the taxonomy creation tool to create your own way of classifying listings (i.e. bedrooms, bathrooms, locations, price ranges), and use those taxonomies to allow users to search for listings.

Includes custom templates for single listings and listing archives.

Allows for any number of custom single listing templates to be created and displayed on a per listing basis.

== Installation ==

1. Upload the entire `wp-listings` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Begin creating listings and listing taxonomies

== Frequently Asked Questions ==

= My theme already has a single listing template. How do I use the one provided with the plugin? =

Some themes may already have a single-listing.php (and archive.php for listing archives) to display listings with the same post type name of 'listings'. To use the one provided with the plugin, delete the template within your theme's folder (recommended to make a backup).

= How can I create a custom single listing template? =

Name your template file single-listing-CUSTOM-NAME.php (replace CUSTOM NAME with your own descriptive name). You can use the single-listing.php within the plugins /includes/views/ folder for a guide on how to display the post type data.

= I already use the AgentPress Listings plugin, can I use this plugin? =

Yes. This plugin can be used instead of the AgentPress Listings plugin. It uses the same post type name and custom field names (along with several new fields), so the posts you've added using AgentPress, along with the associated meta data, will remain attached to the listing post. Just be sure to deactive AgentPress before activating WP Listings.

== Screenshots ==


== Changelog ==

= 0.1.0 =
* Initial beta release