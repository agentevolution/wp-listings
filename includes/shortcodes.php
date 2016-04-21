<?php 
/**
 * Adds shortcode to display listings
 * Adds shortcode to display post meta
 */

add_shortcode( 'listings', 'wp_listings_shortcode' );

function wp_listings_shortcode($atts, $content = null) {
	extract(shortcode_atts(array(
		'id'       => '',
		'taxonomy' => '',
		'term'     => '',
		'limit'    => '',
		'columns'  => ''
	), $atts ) );

	/**
	 * if limit is empty set to all
	 */
	if(!$limit) {
		$limit = -1;
	}

	/**
	 * if columns is empty set to 0
	 */
	if(!$columns) {
		$columns = 0;
	}

	/*
	 * query args based on parameters
	 */
	$query_args = array(
		'post_type'       => 'listing',
		'posts_per_page'  => $limit
	);

	if($id) {
		$query_args = array(
			'post_type'       => 'listing',
			'post__in'        => explode(',', $id)
		);
	}

	if($term && $taxonomy) {
		$query_args = array(
			'post_type'       => 'listing',
			'posts_per_page'  => $limit,
			'tax_query'       => array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'     => $term
				)
			)
		);
	}

	/*
	 * start loop
	 */
	global $post;

	$listings_array = get_posts( $query_args );

	$count = 0;

	$output = '<div class="wp-listings-shortcode">';

	foreach ( $listings_array as $post ) : setup_postdata( $post );

		$count = ( $count == $columns ) ? 1 : $count + 1;

		$first_class = ( 1 == $count ) ? 'first' : '';

		$output .= '<div class="listing-wrap ' . get_column_class($columns) . ' ' . $first_class . '"><div class="listing-widget-thumb"><a href="' . get_permalink() . '" class="listing-image-link">' . get_the_post_thumbnail( $post->ID, 'listings' ) . '</a>';

		if ( '' != wp_listings_get_status() ) {
			$output .= '<span class="listing-status ' . strtolower(str_replace(' ', '-', wp_listings_get_status())) . '">' . wp_listings_get_status() . '</span>';
		}

		$output .= '<div class="listing-thumb-meta">';

		if ( '' != get_post_meta( $post->ID, '_listing_text', true ) ) {
			$output .= '<span class="listing-text">' . get_post_meta( $post->ID, '_listing_text', true ) . '</span>';
		} elseif ( '' != wp_listings_get_property_types() ) {
			$output .= '<span class="listing-property-type">' . wp_listings_get_property_types() . '</span>';
		}

		if ( '' != get_post_meta( $post->ID, '_listing_price', true ) ) {
			$output .= '<span class="listing-price">' . get_post_meta( $post->ID, '_listing_price', true ) . '</span>';
		}

		$output .= '</div><!-- .listing-thumb-meta --></div><!-- .listing-widget-thumb -->';

		if ( '' != get_post_meta( $post->ID, '_listing_open_house', true ) ) {
			$output .= '<span class="listing-open-house">' . __( "Open House", 'wp-listings' ) . ': ' . get_post_meta( $post->ID, '_listing_open_house', true ) . '</span>';
		}

		$output .= '<div class="listing-widget-details"><h3 class="listing-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
		$output .= '<p class="listing-address"><span class="listing-address">' . wp_listings_get_address() . '</span><br />';
		$output .= '<span class="listing-city-state-zip">' . wp_listings_get_city() . ', ' . wp_listings_get_state() . ' ' . get_post_meta( $post->ID, '_listing_zip', true ) . '</span></p>';

		if ( '' != get_post_meta( $post->ID, '_listing_bedrooms', true ) || '' != get_post_meta( $post->ID, '_listing_bathrooms', true ) || '' != get_post_meta( $post->ID, '_listing_sqft', true )) {
			$output .= '<ul class="listing-beds-baths-sqft"><li class="beds">' . get_post_meta( $post->ID, '_listing_bedrooms', true ) . '<span>' . __( "Beds", 'wp-listings' ) . '</span></li> <li class="baths">' . get_post_meta( $post->ID, '_listing_bathrooms', true ) . '<span>' . __( "Baths", 'wp-listings' ) . '</span></li> <li class="sqft">' . get_post_meta( $post->ID, '_listing_sqft', true ) . '<span>' . __( "Square Feet", 'wp-listings' ) . '</span></li></ul>';
		}

		$output .= '</div><!-- .listing-widget-details --></div><!-- .listing-wrap -->';

	endforeach;

	$output .= '</div><!-- .wp-listings-shortcode -->';

	wp_reset_postdata();

	return $output;
	
}

add_shortcode('wp_listings_meta', 'wp_listings_meta_shortcode');
/**
 * Returns meta data for listings
 * @param  array $atts meta key
 * @return string meta value wrapped in span
 */
function wp_listings_meta_shortcode($atts) {
	extract(shortcode_atts(array(
		'key' => ''
	), $atts ) );
	$postid = get_the_id();

	return '<span class=' . $key . '>' . get_post_meta($postid, '_listing_' . $key, true) . '</span>';
}
