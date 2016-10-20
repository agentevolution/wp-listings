<?php
if( !defined( 'ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') ) exit();

$settings = get_option('plugin_wp_listings_settings');

if($settings['wp_listings_uninstall_delete'] == true) {

	wp_listings_delete_listings();

	// Delete our Options
	delete_site_option('plugin_wp_listings_settings');
	delete_site_option('wp_listings_idx_featured_listing_wp_options');
	delete_site_option('wp_listings_taxonomies');
	delete_site_option('widget_wplistings-featured-listings');
	delete_site_option('widget_listings-search');

	// Delete cron job
	wp_clear_scheduled_hook('wp_listings_idx_update');

}

/* Find and Delete all Listings */
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
				$post_featured_image_id = get_post_thumbnail_id($id);

				wp_delete_attachment( $post_featured_image_id );
				delete_post_meta_by_key( !empty($id->ID) );
                wp_delete_object_term_relationships( $id, $taxonomies );
                wp_delete_post( $id, true );
        }

        $wpdb->query("DELETE FROM `{$wpdb->prefix}options` WHERE option_name LIKE '_transient_equity_listing_%'");

        // Reset PostData
		wp_reset_postdata();
}
