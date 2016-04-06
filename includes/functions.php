<?php
/**
 * Holds miscellaneous functions for use in the WP Listings plugin
 *
 */
add_image_size( 'listings-full', 1060, 9999, false );
add_image_size( 'listings', 560, 380, true );

add_filter( 'template_include', 'wp_listings_template_include' );
function wp_listings_template_include( $template ) {

	global $wp_query;

	$post_type = 'listing';

	if ( $wp_query->is_search && get_post_type() == 'listing' ) {
		if ( file_exists(get_stylesheet_directory() . '/search-' . $post_type . '.php') ) {
			$template = get_stylesheet_directory() . '/search-' . $post_type . '.php';
			return $template;
		} else {
			return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
		}
	}
    if ( wp_listings_is_taxonomy_of($post_type) ) {
    	if ( file_exists(get_stylesheet_directory() . '/taxonomy-' . $post_type . '.php' ) ) {
    	    return get_stylesheet_directory() . '/taxonomy-' . $post_type . '.php';
    	} elseif ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php' ) ) {
        	return get_stylesheet_directory() . '/archive-' . $post_type . '.php';
        } else {
            return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
        }
    }

	if ( is_post_type_archive( $post_type ) ) {
		if ( file_exists(get_stylesheet_directory() . '/archive-' . $post_type . '.php') ) {
			$template = get_stylesheet_directory() . '/archive-' . $post_type . '.php';
			return $template;
		} else {
			return dirname( __FILE__ ) . '/views/archive-' . $post_type . '.php';
		}
	}

	if ( is_single() && $post_type == get_post_type() ) {

		global $post;

		$custom_template = get_post_meta( $post->ID, '_wp_post_template', true );

		/** Prevent directory traversal */
		$custom_template = str_replace( '..', '', $custom_template );

		if( ! $custom_template )
			if( file_exists(get_stylesheet_directory() . '/single-' . $post_type . '.php') )
				return $template;
			else
				return dirname( __FILE__ ) . '/views/single-' . $post_type . '.php';
		else
			if( file_exists( get_stylesheet_directory() . "/{$custom_template}" ) )
				$template = get_stylesheet_directory() . "/{$custom_template}";
			elseif( file_exists( get_template_directory() . "/{$custom_template}" ) )
				$template = get_template_directory() . "/{$custom_template}";

	}

	return $template;
}

/**
 * Controls output of default state for the state custom field if there is one set
 */
function wp_listings_get_state($post_id = null) {
	$options = get_option('plugin_wp_listings_settings');
	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$state = get_post_meta($post_id, '_listing_state', true);
	if (isset($options['wp_listings_default_state'])) {
		$default_state = $options['wp_listings_default_state'];
	}

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
function wp_listings_get_city($post_id = null) {
	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$city = get_post_meta($post_id, '_listing_city', true);
	if ( '' == $city ) {
		$city = 'Cityname';
	}

	return $city;
}
/**
 * Controls output of address
 */
function wp_listings_get_address($post_id = null) {
	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$address = get_post_meta($post_id, '_listing_address', true);
	if ( '' == $address ) {
		$address = 'Address Unavailable';
	}

	return $address;
}
/**
 * Displays the status (active, pending, sold, for rent) of a listing
 */
function wp_listings_get_status($post_id = null, $single = 0) {
	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$listing_status = wp_get_object_terms($post_id, 'status');
	if ( empty($listing_status) || is_wp_error($listing_status) ) {
		return;
	}

	$status = null;
	foreach($listing_status as $term) {
		if ( $term->name != 'Featured') {
			$status .= $term->name;
			if($single == 0) {
				return $status;
			}
			$status .= '<br />';
		}
	}

	return $status;
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
/**
 * Displays the location term of a listing
 */
function wp_listings_get_locations($post_id = null) {
	if ( null == $post_id ) {
		global $post;
		$post_id = $post->ID;
	}

	$listing_locations = wp_get_object_terms($post_id, 'locations');
	if ( empty($listing_locations) || is_wp_error($listing_locations) ) {
		return;
	}

	foreach($listing_locations as $location) {
		return $location->name;
	}
}

function wp_listings_post_number( $query ) {

	if ( !$query->is_main_query() || is_admin() || !is_post_type_archive('listing') ) {
		return;
	}

	$options = get_option('plugin_wp_listings_settings');

	$archive_posts_num = $options['wp_listings_archive_posts_num'];

	if ( empty($archive_posts_num) ) {
		$archive_posts_num = '9';
	}

	$query->query_vars['posts_per_page'] = $archive_posts_num;

}
add_action( 'pre_get_posts', 'wp_listings_post_number' );

/**
 * Add Listings to "At a glance" Dashboard widget
 */
add_filter( 'dashboard_glance_items', 'impress_listings_glance_items', 10, 1 );
function impress_listings_glance_items( $items = array() ) {

    $post_types = array( 'listing' );

    foreach( $post_types as $type ) {

        if( ! post_type_exists( $type ) ) continue;

        $num_posts = wp_count_posts( $type );

        if( $num_posts ) {

            $published = intval( $num_posts->publish );
            $post_type = get_post_type_object( $type );

            $text = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, 'wp-listings' );
            $text = sprintf( $text, number_format_i18n( $published ) );

            if ( current_user_can( $post_type->cap->edit_posts ) ) {
                $items[] = sprintf( '<a class="%1$s-count" href="edit.php?post_type=%1$s">%2$s</a>', $type, $text ) . "\n";
            } else {
                $items[] = sprintf( '<span class="%1$s-count">%2$s</span>', $type, $text ) . "\n";
            }
        }
    }

    return $items;
}

/**
 * Better Jetpack Related Posts Support for Listings
 */
function wp_listings_jetpack_relatedposts( $headline ) {
  if ( is_singular( 'listing' ) ) {
    $headline = sprintf(
        '<h3 class="jp-relatedposts-headline"><em>%s</em></h3>',
        esc_html( 'Similar Listings' )
        );
  }
  return $headline;
}
add_filter( 'jetpack_relatedposts_filter_headline', 'wp_listings_jetpack_relatedposts' );

/**
 * Add Listings to Jetpack Omnisearch
 */
if ( class_exists( 'Jetpack_Omnisearch_Posts' ) ) {
	new Jetpack_Omnisearch_Posts( 'listing' );
}

/**
 * Add Listings to Jetpack sitemap
 */
add_filter( 'jetpack_sitemap_post_types', 'wp_listings_jetpack_sitemap' );
function wp_listings_jetpack_sitemap() {
	$post_types[] = 'listing';
	return $post_types;
}

/**
 * Function to return term image for use on front end
 * @param  num  $term_id the id of the term
 * @param  boolean $html    use html wrapper with wp_get_attachment_image
 * @return mixed  the image with html markup or the image id
 */
function wp_listings_term_image( $term_id, $html = true, $size = 'full' ) {
	$image_id = get_term_meta( $term_id, 'wpl_term_image', true );
	return $image_id && $html ? wp_get_attachment_image( $image_id, $size, false, array('class' => 'wp-listings-term-image') ) : $image_id;
}
