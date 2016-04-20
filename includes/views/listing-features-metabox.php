<?php

// Featured on
global $post;

echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Featured on (allows shortcodes):<br /><textarea name="wp_listings[_listing_featured_on]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_featured_on', true) ) );

echo '</div><br style="clear: both;" />';

// Home Summary

echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Home Summary (allows shortcodes):<br /><textarea name="wp_listings[_listing_home_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_home_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Kitchen Summary
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Kitchen Summary (allows shortcodes):<br /><textarea name="wp_listings[_listing_kitchen_sum]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_kitchen_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Living Room
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Living Room (allows shortcodes):<br /><textarea name="wp_listings[_listing_living_room]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_living_room', true) ) );

echo '</div><br style="clear: both;" />';

// Master Suite
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Master Suite (allows shortcodes):<br /><textarea name="wp_listings[_listing_master_suite]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_master_suite', true) ) );

echo '</div><br style="clear: both;" />';

// School and Neighborhood Info
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>School and Neighborhood Info (allows shortcodes):<br /><textarea name="wp_listings[_listing_school_neighborhood]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_school_neighborhood', true) ) );

echo '</div><br style="clear: both;" />';

// Disclaimer
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Disclaimer:<br /><textarea name="wp_listings[_listing_disclaimer]" rows="3" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_disclaimer', true) ) );

echo '</div><br style="clear: both;" />';
