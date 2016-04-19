<?php
/*
 * Contains functions for enabling and interacting with the WP REST API
 */

/**
 * Add the listing meta field keys to WP 4.4+ REST API responses for GET and POST
 */
if(function_exists('register_rest_field')) {
	add_action( 'rest_api_init', 'wp_listings_register_listing_meta' );
}
function wp_listings_register_listing_meta() {

	$allowed_meta_keys = allowed_meta_keys();
	foreach($allowed_meta_keys as $listing_meta_key) {
	    register_rest_field( 'listing',
	        $listing_meta_key,
	    	array(
	            'get_callback'    => 'wp_listings_get_listing_meta',
	            'update_callback' => 'wp_listings_update_listing_meta',
	            'schema'          => null,
	        )
	    );
	}
}
/**
 * Another method for adding listing meta field keys to WP 4.4+ REST API responses for GET
 */
add_filter( 'rest_prepare_listing', 'wp_listings_add_meta_to_json', 10, 3 );
function wp_listings_add_meta_to_json($data, $post, $request){

	$response_data = $data->get_data();

	if ( $request['context'] !== 'view' || is_wp_error( $data ) ) {
	    return $data;
	}

	$allowed_meta_keys = allowed_meta_keys();

	foreach($allowed_meta_keys as $listing_meta_key) {
		$meta = get_post_meta( $post->ID, $listing_meta_key, true );

		if(!empty($meta)){
		    $listing_meta[$listing_meta_key] = $meta;
		}
	}

	if($post->post_type == 'listing') {
	    $response_data['listing_meta'] = $listing_meta;
	}

	$data->set_data( $response_data );

	return $data;
}

/**
 * Get the value of the listing meta key
 *
 * @param array $object Details of current post.
 * @param string $listing_meta_key Name of field.
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 */
function wp_listings_get_listing_meta( $object, $listing_meta_key, $request ) {
	$meta = get_post_meta( $object['id'], $listing_meta_key, true );

	if(!empty($meta))
    	return $meta;
}

/**
 * Handler for updating listing meta key
 *
 * @param mixed $value The value of the field
 * @param object $object The object from the response
 * @param string $listing_meta_key Name of field
 *
 * @return bool|int
 */
function wp_listings_update_listing_meta( $value, $object, $listing_meta_key ) {
    if ( ! $value || ! is_string( $value ) ) {
        return;
    }
    $value = sanitize_text_field($value);
    return update_post_meta( $object->ID, $listing_meta_key, strip_tags( $value ) );

}

/**
 * Add Jetpack JSON Rest API Support
 */
function wp_listings_allow_post_types($allowed_post_types) {
	$allowed_post_types[] = 'listing';
	return $allowed_post_types;
}
add_filter( 'rest_api_allowed_post_types', 'wp_listings_allow_post_types');

/**
 * Add Jetpack JSON Rest API Support (Listing MetaData)
 */
function wp_listings_rest_api_allowed_public_metadata( $allowed_meta_keys )
{
    // only run for REST API requests
    if ( ! defined( 'REST_API_REQUEST' ) || ! REST_API_REQUEST )
        return;

    $allowed_meta_keys = allowed_meta_keys();

    return $allowed_meta_keys;
}
add_filter( 'rest_api_allowed_public_metadata', 'wp_listings_rest_api_allowed_public_metadata' );

/**
 * Keep an array of allowed meta fields for the listing via the api methods
 * @return allowed_meta_keys the post meta keys
 */
function allowed_meta_keys() {
	apply_filters('wp_listings_allowed_api_meta_keys', $allowed_meta_keys = array (
		'_listing_price',
	    '_listing_address',
	    '_listing_city',
	    '_listing_county',
	    '_listing_state',
	    '_listing_zip',
	    '_listing_country',
	    '_listing_city',
	    '_listing_longitude',
	    '_listing_latitude',
	    '_listing_mls',
	    '_listing_open_house',
	    '_listing_year_built',
	    '_listing_floors',
	    '_listing_sqft',
	    '_listing_lot_sqft',
	    '_listing_bedrooms',
	    '_listing_bathrooms',
	    '_listing_half_bath',
	    '_listing_garage',
	    '_listing_pool',
	    '_listing_text',
	    '_listing_gallery',
	    '_listing_video',
	    '_listing_map',
	    '_listing_contact_form',
	    '_listing_featured_on',
	    '_listing_home_sum',
	    '_listing_ktichen_sum',
	    '_listing_living_room',
	    '_listing_master_suite',
	    '_listing_school_neighborhood',

	    //IDX
	    '_listing_proptype',
	    '_listing_condo',
	    '_listing_financial',
	    '_listing_city',
	    '_listing_condition',
	    '_listing_construction',
	    '_listing_exterior',
	    '_listing_fencing',
	    '_listing_interior',
	    '_listing_flooring',
	    '_listing_heatcool',
	    '_listing_lotsize',
	    '_listing_location',
	    '_listing_scenery',
	    '_listing_community',
	    '_listing_recreation',
	    '_listing_general',
	    '_listing_inclusions',
	    '_listing_parking',
	    '_listing_rooms',
	    '_listing_laundry',
	    '_listing_utilities',
	    '_listing_disclaimer',
	    '_listing_courtesy'
	) );

	return $allowed_meta_keys;
}
