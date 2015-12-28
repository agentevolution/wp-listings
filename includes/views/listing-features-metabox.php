<?php
function wp_listings_additional_details() {

	global $post;
	$additional_details = '';

	// Create meta box labels and input fields
	$additional_details .= '<p>All fields accept text or shortcodes</p>';
	// Featured On
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Featured On:<br /><textarea name="wp_listings[_listing_featured_on]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_featured_on', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Home Summary
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Home Summary:<br /><textarea name="wp_listings[_listing_home_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_home_sum', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Kitchen Summary
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Kitchen Summary:<br /><textarea name="wp_listings[_listing_kitchen_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_kitchen_sum', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Living Room
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Living Room:<br /><textarea name="wp_listings[_listing_living_room]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_living_room', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Master Suite
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Master Suite:<br /><textarea name="wp_listings[_listing_master_suite]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_master_suite', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// School and Neighborhood Info
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>School and Neighborhood Info:<br /><textarea name="wp_listings[_listing_school_neighborhood]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_school_neighborhood', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Custom Disclaimer
	$additional_details .= '<div style="width: 90%; float: left;">';
	$additional_details .= 	sprintf( __( '<p><label>Custom Disclaimer:<br /><textarea name="wp_listings[_listing_custom_disclaimer]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_custom_disclaimer', true) ) );
	$additional_details .= '</div><br style="clear: both;" />';

	// Filter output
	if( has_filter( 'wp_listings_additional_details_meta_boxes' ) ) {
		$additional_details = apply_filters( 'wp_listings_additional_details_meta_boxes', $additional_details );
	}

	return $additional_details;

}

// Output Additional Details meta boxes
echo wp_listings_additional_details();
