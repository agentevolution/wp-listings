<?php
/**
 * Holds miscellaneous functions for use in the WP Listings plugin
 *
 */

add_image_size( 'listings', 560, 380, TRUE );
add_filter( 'template_include', 'wp_listings_template_include' );

function wp_listings_template_include( $template ) {

	$post_type = 'listing';

    if ( wp_listings_is_taxonomy_of($post_type) ) {
        if ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php' ) ) {
            return get_stylesheet_directory() . '/archive-' . $post_type . '.php';
        } else {
            return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
        }
    }

	if ( is_post_type_archive( $post_type ) ) {
		if ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php') ) {
			return $template;
		} else {
			return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
		}
	}

	if ( $post_type == get_post_type() ) {
		if ( file_exists(get_stylesheet_directory() . '/single-' . $post_type . '.php') ) {
			return $template;
		} else {
			return dirname( __FILE__ ) . '/views/single-' . $post_type . '.php';
		}
	}

	return $template;
}

/**
 * Controls output of default state for the state custom field if there is one set
 */
function wp_listings_get_state() {

	global $post;

	$state = get_post_meta($post->ID, '_listing_state', true);

	$default_state = get_option('wp_listings_default_state');

	if ( empty($default_state) ) {
		$default_state = 'ST';
	}

	if ( empty($state) ) {
		return $default_state;
	}

	return $state;
}

/**
 * Controls output of city name
 */
function wp_listings_get_city() {

	global $post;

	$city = get_post_meta($post->ID, '_listing_city', true);
	
	if ( '' == $city ) {
		$city = 'Cityname';
	}

	return $city;
}

/**
 * Controls output of address
 */
function wp_listings_get_address($post_id = null) {

	global $post;

	$address = get_post_meta($post->ID, '_listing_address', true);

	if ( '' == $address ) {
		$address = 'Address Unavailable';
	}

	return $address;
}

/**
 * Displays the status (active, pending, sold, for rent) of a listing
 */
function wp_listings_get_status($post_id = null) {

	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$listing_status = wp_get_object_terms($post_id, 'status');

	if ( empty($listing_status) || is_wp_error($listing_status) ) {
		return;
	}

	foreach($listing_status as $term) {
		if ( $term->name != 'Featured' ) {
			return $term->name;
		}
	}
}

/**
 * Displays the property type (residential, condo, comemrcial, etc) of a listing
 */
function wp_listings_get_property_types($post_id = null) {

	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$listing_property_types = wp_get_object_terms($post_id, 'property-types');

	if ( empty($listing_property_types) || is_wp_error($listing_property_types) ) {
		return;
	}

	foreach($listing_property_types as $type) {
		return $type->name;
	}
}

function wp_listings_post_number( $query ) {

	if ( !$query->is_main_query() || is_admin() || !is_post_type_archive('listing') ) {
		return;
	}

	$query->query_vars['posts_per_page'] = 9;
}
add_action( 'pre_get_posts', 'wp_listings_post_number' );