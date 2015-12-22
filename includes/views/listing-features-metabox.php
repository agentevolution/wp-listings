<?php
function wp_listings_additional_details() {

	global $post;
	$additonal_details = '';

	// Create meta box labels and input fields
	$additonal_details .= '<p>All fields accept text or shortcodes</p>';
	// Featured On
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Featured On:<br /><textarea name="wp_listings[_listing_featured_on]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_featured_on', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Home Summary
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Home Summary:<br /><textarea name="wp_listings[_listing_home_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_home_sum', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Kitchen Summary
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Kitchen Summary:<br /><textarea name="wp_listings[_listing_kitchen_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_kitchen_sum', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Living Room
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Living Room:<br /><textarea name="wp_listings[_listing_living_room]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_living_room', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Master Suite
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Master Suite:<br /><textarea name="wp_listings[_listing_master_suite]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_master_suite', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// School and Neighborhood Info
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>School and Neighborhood Info:<br /><textarea name="wp_listings[_listing_school_neighborhood]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_school_neighborhood', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Custom Disclaimer
	$additonal_details .= '<div style="width: 90%; float: left;">';
	$additonal_details .= 	sprintf( __( '<p><label>Custom Disclaimer:<br /><textarea name="wp_listings[_listing_custom_disclaimer]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_custom_disclaimer', true) ) );
	$additonal_details .= '</div><br style="clear: both;" />';

	// Filter output
	if( has_filter( 'wp_listings_additional_details_meta_boxes' ) ) {
		$additonal_details = apply_filters( 'wp_listings_additional_details_meta_boxes', $additonal_details );
	}

	return $additonal_details;

}

// Output Additional Details meta boxes
echo wp_listings_additional_details();
