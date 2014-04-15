<?php

/**
 * Lists all the terms of a given taxonomy
 *
 * Adds the taxonomy title and a list of the terms associated with that taxonomy
 * used in custom post type templates.
 */
function wp_listings_list_terms($taxonomy) {
	$the_tax_object = get_taxonomy($taxonomy);
	$terms = get_terms($taxonomy);
	$term_list = '';

	$count = count($terms); $i=0;
	if ($count > 0) {
	    foreach ($terms as $term) {
	        $i++;
	    	$term_list .= '<li><a href="' . site_url($taxonomy . '/' . $term->slug) . '" title="' . sprintf(__('View all post filed under %s', 'gbd'), $term->name) . '">' . $term->name . ' (' . $term->count . ')</a></li>';
	    }
		echo '<div class="' . $taxonomy . ' term-list-container">';
		echo '<h3 class="taxonomy-name">' . $the_tax_object->label . '</h3>';
		echo "<ul class=\"term-list\">{$term_list}</ul>";
		echo '</div> <!-- .' . $taxonomy . ' .term-list-container -->';
	}
}


/**
 * Returns true if the queried taxonomy is a taxonomy of the given post type
 */
function wp_listings_is_taxonomy_of($post_type) {
	$taxonomies = get_object_taxonomies($post_type);
	$queried_tax = get_query_var('taxonomy');

	if ( in_array($queried_tax, $taxonomies) ) {
		return true;
	}

	return false;
}