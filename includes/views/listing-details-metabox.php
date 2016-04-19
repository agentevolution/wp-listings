<?php
wp_nonce_field( 'wp_listings_metabox_save', 'wp_listings_metabox_nonce' );

global $post;

$pattern = '<p><label>%s<br /><input type="text" name="wp_listings[%s]" value="%s" /></label></p>';

echo '<div style="width: 45%; float: left">';

	foreach ( (array) $this->property_details['col1'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div>';

echo '<div style="width: 45%; float: right;">';

	foreach ( (array) $this->property_details['col2'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div><br style="clear: both;" />';

$pattern = '<p><label>%s<br /><textarea type="text" name="wp_listings[%s]" value="%s" rows="2" style="width: 100&#37;;">%s</textarea></label></p>';

_e('<h4>Extended Details:</h4>', 'wp-listings');
echo '<div style="width: 45%; float: left">';

	foreach ( (array) $this->extended_property_details['col1'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ), esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div>';

echo '<div style="width: 45%; float: right;">';

	foreach ( (array) $this->extended_property_details['col2'] as $label => $key ) {
		printf( $pattern, esc_html( $label ), $key, esc_attr( get_post_meta( $post->ID, $key, true ) ), esc_attr( get_post_meta( $post->ID, $key, true ) ) );
	}

echo '</div><br style="clear: both;" />';

echo '<div style="width: 45%; float: left">';
	_e('<h4>Price Options</h4>', 'wp-listings');
	printf( __('<p><label>Hide the price from visitors?<br /> <input type="checkbox" name="wp_listings[_listing_hide_price]" value="1" %s /></label></p>'),
		checked( get_post_meta( $post->ID, '_listing_hide_price', true ), 1, 0 ) );

	_e('<p><label>Text to display instead of price (or leave blank):<br />', 'wp-listings');
	printf( __( '<input type="text" name="wp_listings[_listing_price_alt]" value="%s" /></label></p>', 'wp-listings' ), htmlentities( get_post_meta( $post->ID, '_listing_price_alt', true) ) );
echo '</div>';

echo '<div style="width: 90%; float: left;">';

	_e('<h4>Custom Overlay Text</h4>', 'wp-listings');
	_e('<p><label>Custom text to display as overlay on featured listings<br />', 'wp-listings');
	printf( __( '<input type="text" name="wp_listings[_listing_text]" value="%s" /></label></p>', 'wp-listings' ), htmlentities( get_post_meta( $post->ID, '_listing_text', true) ) );

echo '</div><br style="clear: both;" /><br /><br /><hr>';

echo '<div style="width: 100%; float: left;">';

	_e('<p><label>Photo Gallery (use Add Media button to insert Gallery):<br />', 'wp-listings');

	$wplistings_gallery_content = get_post_meta( $post->ID, '_listing_gallery', true);
	$wplistings_gallery_editor_id = '_listing_gallery';

	$wplistings_gallery_editor_settings = array(
			'wpautop' 			=> false,
			'textarea_name' 	=> 'wp_listings[_listing_gallery]',
			'editor_class'		=> 'wplistings_gallery',
			'textarea_rows'		=> 20,
			'tinymce'			=> true,
			'quicktags'			=> true,
			'drag_drop_upload'	=> true
		);

	wp_editor($wplistings_gallery_content, $wplistings_gallery_editor_id, $wplistings_gallery_editor_settings);

echo '</div><br style="clear: both;" /><br /><br />';

echo '<div style="width: 90%; float: left;">';

	_e('<p><label>Enter Video or Virtual Tour Embed Code (<a href="https://wordpress.org/plugins/jetpack/" target="_blank" rel="nofollow">Jetpack</a> offers several <a href="http://jetpack.me/support/shortcode-embeds/" target="_blank" rel="nofollow">video shortcodes</a>.):<br />', 'wp-listings');
	printf( __( '<textarea name="wp_listings[_listing_video]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_video', true) ) );

echo '</div><br style="clear: both;" />';

echo '<div style="width: 90%; float: left;">';

echo '<div style="width: 45%; float: left">';
	_e('<h4>Map Options</h4>', 'wp-listings');

	if(get_post_meta($post->ID, '_listing_automap', 1) == FALSE) {
    	update_post_meta($post->ID, '_listing_automap', 'y');
    }
	printf( __('<p><label>Automatically insert map based on latitude/longitude? <strong>Will be overridden if a shortode is entered below.</strong><br /> <input type="radio" name="wp_listings[_listing_automap]" value="y" %s>Yes</input> <input type="radio" name="wp_listings[_listing_automap]" value="n" %s>No</input></label></p>'),
		checked( get_post_meta( $post->ID, '_listing_automap', true ), 'y', 0 ),
		checked( get_post_meta( $post->ID, '_listing_automap', true ), 'n', 0 ) );
echo '</div>';
echo '<div style="clear: both; width: 45%; float: left;">';
	printf( __('<p><label>Latitude: <br /><input type="text" name="wp_listings[_listing_latitude]" value="%s" /></label></p>', 'wp-listings'), get_post_meta( $post->ID, '_listing_latitude', true) );
echo '</div>';
echo '<div style="width: 45%; float: right;">';
	printf( __('<p><label>Longitude: <br /><input type="text" name="wp_listings[_listing_longitude]" value="%s" /></label></p>', 'wp-listings'), get_post_meta( $post->ID, '_listing_longitude', true) );
echo '</div><br style="clear: both;" />';

	_e('<p><label>Or enter Map Embed Code or shortcode from Map plugin (such as <a href="http://jetpack.me/support/shortcode-embeds/" target="_blank" rel="nofollow">Jetpack Shortcodes</a>, <a href="https://wordpress.org/plugins/simple-google-maps-short-code/" target="_blank" rel="nofollow">Simple Google Maps Short Code</a> or <a href="https://wordpress.org/plugins/mappress-google-maps-for-wordpress/" target="_blank" rel="nofollow">MapPress</a>):<br /><em>Recommend size: 660x300 (If possible, use 100% width, or your themes content width)</em><br />', 'wp-listings');
	printf( __( '<textarea name="wp_listings[_listing_map]" rows="5" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_map', true) ) );

echo '</div>';

echo '<div style="width: 90%; float: left;">';
	_e('<h4>Contact Form</h4>', 'wp-listings');

	_e('<p><label>If you use a Contact Form plugin, you may enter the Contact Form shortcode here. Otherwise, the single listing template will use a default contact form:<br />', 'wp-listings');
	printf( __( '<textarea name="wp_listings[_listing_contact_form]" rows="1" cols="18" style="%s">%s</textarea></label></p>', 'wp-listings' ), 'width: 99%;', htmlentities( get_post_meta( $post->ID, '_listing_contact_form', true) ) );

echo '</div><br style="clear: both;" />';
