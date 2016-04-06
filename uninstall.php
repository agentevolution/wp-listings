<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

// Delete our Options
// delete_option('plugin_wp_listings_settings');
// delete_option('wp_listings_taxonomies');
// delete_option('widget_wplistings-featured-listings');
// delete_option('widget_listings-search');

// Delete our Transients
// delete_transient('');

/* Find and Delete all Listings */
/*
function wp_listings_delete_listings() {
		global $wpdb;

		// Get all Listings
	    $args = array (
    		'post_type' => array('listing'),
			'nopaging' => true
		);

        // Remove all Listings
		$query = new WP_Query ($args);
			while ($query->have_posts()) {
				$query->the_post();
				$id = get_the_ID();
				$taxonomies = array( 'status', 'locations', 'features', 'property-types' );
				wp_delete_post ($id, true);
				delete_post_meta_by_key(!empty($id->ID));
                wp_delete_object_term_relationships( $id, $taxonomies );
        }

        // Reset PostData
		wp_reset_postdata();
}
*/