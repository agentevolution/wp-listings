<?php
wp_nonce_field( 'wp_listings_metabox_save', 'wp_listings_metabox_nonce' );

global $post;

$pattern = '<p><label>%s<br /><input type="text" name="wp_listings[%s]" value="%s" /></label></p>';

echo '<div style="width: 45%; float: left">';

	foreach ( (array) $this->property_details['col1'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div>';

echo '<div style="width: 45%; float: left;">';

	foreach ( (array) $this->property_details['col2'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div><br style="clear: both;" /><br /><br />';


echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Enter Map Embed Code or shortcode from Map plugin (such as <a href="https://wordpress.org/plugins/simple-google-maps-short-code/">Simple Google Maps Short Code</a>):<br /><em>Recommend size: 660x300 (If possible, use 100% width, or your theme\'s content width)</em><br /><textarea name="wp_listings[_listing_map]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_map', true) ) );

echo '</div>';

echo '<div style="width: 90%; float: left;">';

	printf( __( '<p><label>Enter Video or Virtual Tour Embed Code:<br /><textarea name="wp_listings[_listing_video]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp_listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_video', true) ) );

echo '</div><br style="clear: both;" />';