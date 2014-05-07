=== WP Listings ===
Contributors: agentevolution, davebonds, chadajohnson
Tags: real estate, listings, property, properties, listing search, idx, agentpress
Requires at least: 3.2
Tested up to: 3.9
Stable tag: 1.0.1

Creates a portable listing management system for your WordPress site. Designed to work with any theme using built-in templates.

== Description ==

WP Listings uses custom post types, custom taxonomies, and widgets to create a listing management system for WordPress. It includes custom templates and widgets for front end display.

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

Intergrates with the [Genesis Agent Profiles](http://wordpress.org/plugins/genesis-agent-profiles/) plugin to display the listing agent(s).

Single listings include a contact form for visitors to inquire about a property, or the form can be replaced with your own form plugin shortcode.

Allows for any number of custom single listing templates to be created and displayed on a per listing basis. 

Premium Single Listing Templates available at [agentevolution.com](http://www.agentevolution.com/product-category/listing-templates/).

== Installation ==

1. Upload the entire `wp-listings` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Begin creating listings and listing taxonomies

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

= 1.0.1 =
* Added ability to select an image size in the Featured Listing widget. This allows you to set a custom image size in your theme.

= 1.0 =
* Initial public release

== Credits ==

Uses code from the following plugins:

[Single Post Template](http://wordpress.org/plugins/single-post-template/) by Nathan Rice

[AgentPress Listings](http://wordpress.org/plugins/agentpress-listings/) by StudioPress

[AgentPress Listings Taxonomy Reorder](http://wordpress.org/plugins/agentpress-listings-taxonomy-reorder/) by Robert Iseley