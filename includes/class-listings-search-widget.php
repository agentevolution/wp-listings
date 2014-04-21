<?php
/**
 * This widget creates a search form which uses listings' taxonomy for search fields.
 *
 * @package WP Listings
 * @since 0.1.0
 */
class WP_Listings_Search_Widget extends WP_Widget {

	function WP_Listings_Search_Widget() {
		$widget_ops = array( 'classname' => 'listings-search', 'description' => __( 'Display listings search dropdown', 'wp_listings' ) );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'listings-search' );
		$this->WP_Widget( 'listings-search', __( 'WP Listings - Search', 'wp_listings' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> '',
			'button_text'	=> __( 'Search Listings', 'wp_listings' )
		) );

		global $_wp_listings_taxonomies;

		$listings_taxonomies = $_wp_listings_taxonomies->get_taxonomies();

		extract( $args );

		echo $before_widget;

		if ( $instance['title'] ) echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;

		echo '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" ><input type="hidden" value="" name="s" /><input type="hidden" value="listing" name="post_type" />';

		foreach ( $listings_taxonomies as $tax => $data ) {
			if ( ! isset( $instance[$tax] ) || ! $instance[$tax] )
				continue;

			$terms = get_terms( $tax, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 100, 'hierarchical' => false ) );
			if ( empty( $terms ) )
				continue;

			$current = ! empty( $wp_query->query_vars[$tax] ) ? $wp_query->query_vars[$tax] : '';
			echo "<select name='$tax' id='$tax' class='wp-listings-taxonomy'>\n\t";
			echo '<option value="" ' . selected( $current == '', true, false ) . ">{$data['labels']['name']}</option>\n";
			foreach ( (array) $terms as $term )
				echo "\t<option value='{$term->slug}' " . selected( $current, $term->slug, false ) . ">{$term->name}</option>\n";

			echo '</select>';
		}

		echo '<input type="submit" id="searchsubmit" class="searchsubmit" value="'. esc_attr( $instance['button_text'] ) .'" />
		<div class="clear"></div>
		</form>';

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( (array) $instance, array(
			'title'			=> '',
			'button_text'	=> __( 'Search Listings', 'wp_listings' )
		) );

		global $_wp_listings_taxonomies;

		$listings_taxonomies = $_wp_listings_taxonomies->get_taxonomies();
		$new_widget = empty( $instance );

		printf( '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>', $this->get_field_id( 'title' ), __( 'Title:', 'wp_listings' ), $this->get_field_id( 'title' ), $this->get_field_name( 'title' ), esc_attr( $instance['title'] ), 'width: 95%;' );
		?>
		<h5><?php _e( 'Include these taxonomies in the search widget', 'wp_listings' ); ?></h5>
		<?php
		foreach ( (array) $listings_taxonomies as $tax => $data ) {
			$terms = get_terms( $tax );
			if ( empty( $terms ) )
				continue;
		?>
		<p><label><input id="<?php echo $this->get_field_id( $tax ); ?>" type="checkbox" name="<?php echo $this->get_field_name( $tax ); ?>" value="1" <?php checked( 1, $instance[$tax] || $new_widget ); ?>/> <?php echo $data['labels']['name']; ?></label></p>
		<?php
		}

		printf( '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>', $this->get_field_id( 'button_text' ), __( 'Button Text:', 'wp_listings' ), $this->get_field_id( 'button_text' ), $this->get_field_name( 'button_text' ), esc_attr( $instance['button_text'] ), 'width: 95%;' );
	}
}