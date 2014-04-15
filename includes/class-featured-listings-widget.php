<?php
/**
 * This widget presents loop content, based on user selections for sidebar or home page display
 *
 * @since 0.1.0
 * @author agentevolution
 */
class WP_Listings_Featured_Listings_Widget extends WP_Widget {

	function WP_Listings_Featured_Listings_Widget() {
		$widget_ops = array( 'classname' => 'featured-listings', 'description' => __( 'Display grid-style featured listings', 'wp_listings' ) );
		$control_ops = array( 'width' => 300, 'height' => 350 );
		$this->WP_Widget( 'featured-listings', __( 'WP Listings - Featured Listings', 'wp_listings' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {

		/** defaults */
		$instance = wp_parse_args( $instance, array(
			'title' 			=> '',
			'posts_per_page'	=> 3,
			'posts_term'		=> ''
		) );

		extract( $args );

		echo $before_widget;

			if ( ! empty( $instance['title'] ) ) {
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
			}

			if ( !empty( $instance['posts_term'] ) ) {
	            $posts_term = explode( ',', $instance['posts_term'] );
        	}

			$query_args = array(
				'post_type'			=> 'listing',
				$posts_term['0']	=> $posts_term['1'],
				'posts_per_page'	=> $instance['posts_per_page'],
				'paged'				=> get_query_var('paged') ? get_query_var('paged') : 1
			);

			query_posts( $query_args );
			if ( have_posts() ) : while ( have_posts() ) : the_post();

				$loop = ''; /** initialze the $loop variable */

				$loop .= sprintf( '<a href="%s">%s</a>', get_permalink(), genesis_get_image(array('size' => 'properties')) );
				$loop .= sprintf( '<span class="listing-price">%s</span>', genesis_get_custom_field('_listing_price') );
				$loop .= sprintf( '<span class="listing-text">%s</span>', wp_listings_get_status() );
				$loop .= sprintf( '<span class="listing-address">%s</span>', genesis_get_custom_field('_listing_address') );
				$loop .= sprintf( '<span class="listing-city-state-zip">%s, %s %s</span>', wp_listings_get_city(), wp_listings_get_state(), genesis_get_custom_field('_listing_zip' ) );

				$loop .= sprintf( '<a href="%s" class="more-link">%s</a>', get_permalink(), __( 'View Listing', 'wp_listings' ) );

				$toggle = $toggle == 'left' ? 'right' : 'left';

				/** wrap in post class div, and output **/
				printf( '<div class="%s"><div class="widget-wrap"><div class="listing-wrap">%s</div></div></div>', join( ' ', get_post_class() ), apply_filters( 'agent_evolved_featured_listings_widget_loop', $loop ) );

			endwhile; endif;
			wp_reset_query();

		echo $after_widget;

	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( $instance, array(
			'title'				=> '',
			'posts_per_page'	=> 3,
		) );

		printf( '<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>', $this->get_field_id('title'), __( 'Title:', 'wp_listings' ), $this->get_field_id('title'), $this->get_field_name('title'), esc_attr( $instance['title'] ), 'width: 95%;' );

		printf( '<p>%s <input type="text" name="%s" value="%s" size="3" /></p>', __( 'How many results should be returned?', 'wp_listings' ), $this->get_field_name('posts_per_page'), esc_attr( $instance['posts_per_page'] ) );

		echo '<p><label for="'. $this->get_field_id( 'posts_term' ) .'">Display by term:</label>

		<select id="'. $this->get_field_id( 'posts_term' ) .'" name="'. $this->get_field_name( 'posts_term' ) .'">
			<option style="padding-right:10px;" value="" '. selected( '', $instance['posts_term'], false ) .'>'. __( 'All Taxonomies and Terms', 'wp_listings' ) .'</option>';

			$taxonomies = get_object_taxonomies('listing');

			foreach ( $taxonomies as $taxonomy ) {
				$the_tax_object = get_taxonomy($taxonomy);

				echo '<optgroup label="'. esc_attr( $the_tax_object->label ) .'">';

				$terms = get_terms( $taxonomy, 'orderby=name&hide_empty=1' );

				foreach ( $terms as $term )
					echo '<option style="margin-left: 8px; padding-right:10px;" value="'. esc_attr( $the_tax_object->query_var ) . ',' . $term->slug .'" '. selected( esc_attr( $the_tax_object->query_var ) . ',' . $term->slug, $instance['posts_term'], false ) .'>-' . esc_attr( $term->name ) .'</option>';

				echo '</optgroup>';

			}

		echo '</select></p>';
	}
}