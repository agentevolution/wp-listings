<?php

// Home Summary
global $post;

echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Home Summary:<br /><textarea name="wp_listings[_listing_home_sum]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_home_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Kitchen Summary
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Kitchen Summary:<br /><textarea name="wp_listings[_listing_kitchen_sum]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_kitchen_sum', true) ) );

echo '</div><br style="clear: both;" />';

// Living Room
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Living Room:<br /><textarea name="wp_listings[_listing_living_room]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_living_room', true) ) );

echo '</div><br style="clear: both;" />';


// Master Suite
echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Master Suite:<br /><textarea name="wp_listings[_listing_master_suite]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_master_suite', true) ) );

echo '</div><br style="clear: both;" />';