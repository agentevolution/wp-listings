<?php
/**
 * This widget displays listings, based on user input, in any widget area.
 *
 * @package WP Listings
 * @since 0.1.0
 */
class WP_Listings_Featured_Listings_Widget extends WP_Widget {

	function __construct() {
		$widget_ops  = array( 'classname' => 'wplistings-featured-listings clearfix', 'description' => __( 'Display grid-style featured listings', 'wp-listings' ), 'customize_selective_refresh' => true );
		$control_ops = array( 'width' => 300, 'height' => 350 );
		parent::__construct( 'wplistings-featured-listings', __( 'IMPress Listings - Featured Listings', 'wp-listings' ), $widget_ops, $control_ops );
	}

	/**
	 * Returns the column class
	 *
	 * @param int $number_columns
	 * @param int $number_items
	 */
	function get_column_class($number_columns) {

		$column_class = '';

		// Max of six columns
		$number_columns = ( $number_columns > 6 ) ? 6 : (int)$number_columns;

		// column class
		switch ($number_columns) {
			case 0:
			case 1:
				$column_class = '';
				break;
			case 2:
				$column_class = 'one-half';
				break;
			case 3:
				$column_class = 'one-third';
				break;
			case 4:
				$column_class = 'one-fourth';
				break;
			case 5:
				$column_class = 'one-fifth';
				break;
			case 6:
				$column_class = 'one-sixth';
				break;
		}

		return $column_class;
	}

	function widget( $args, $instance ) {

		extract( $args );

		$options = get_option('plugin_wp_listings_settings');

		$column_class = $instance['use_columns'] ? $this->get_column_class($instance['number_columns']) : '';

		echo $before_widget;

			if ( ! empty( $instance['title'] ) ) {
				echo $before_title . apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) . $after_title;
			}

			if ( !empty( $instance['posts_term'] ) ) {
	            $posts_term = explode( ',', $instance['posts_term'] );
        	}

			$query_args = array(
				'post_type'			=> 'listing',
				'posts_per_page'	=> $instance['posts_per_page'],
				'paged'				=> get_query_var('paged') ? get_query_var('paged') : 1
			);

			if ( !empty( $instance['posts_term'] ) && count($posts_term) == 2 ) {
				$query_args[$posts_term['0']] = $posts_term['1'];
			}

			$wp_listings_widget_query = new WP_Query( $query_args );

			$count = 0;

			global $post;

			if ( $wp_listings_widget_query->have_posts() ) : while ( $wp_listings_widget_query->have_posts() ) : $wp_listings_widget_query->the_post();

				$count = ( $count == $instance['number_columns'] ) ? 1 : $count + 1;

				$first_class = ( 1 == $count && 1 == $instance['use_columns'] ) ? ' first' : '';

				$loop = sprintf( '<div class="listing-widget-thumb"><a href="%s" class="listing-image-link">%s</a>', get_permalink(), get_the_post_thumbnail( $post->ID, $instance['image_size'] ) );

				if ( '' != wp_listings_get_status() ) {
					$loop .= sprintf( '<span class="listing-status %s">%s</span>', strtolower(str_replace(' ', '-', wp_listings_get_status())), wp_listings_get_status() );
				}

				$loop .= sprintf( '<div class="listing-thumb-meta">' );

				if ( '' != get_post_meta( $post->ID, '_listing_text', true ) ) {
					$loop .= sprintf( '<span class="listing-text">%s</span>', get_post_meta( $post->ID, '_listing_text', true ) );
				} elseif ( '' != wp_listings_get_property_types() ) {
					$loop .= sprintf( '<span class="listing-property-type">%s</span>', wp_listings_get_property_types() );
				}

				if ( '' != get_post_meta( $post->ID, '_listing_price', true ) ) {
					$loop .= sprintf( '<span class="listing-price"><span class="currency-symbol">%s</span>%s %s</span>', $options['wp_listings_currency_symbol'], get_post_meta( $post->ID, '_listing_price', true ), (isset($options['wp_listings_display_currency_code']) && $options['wp_listings_display_currency_code'] == 1) ? '<span class="currency-code">' . $options['wp_listings_currency_code'] . '</span>': '' );
				}

				$loop .= sprintf( '</div><!-- .listing-thumb-meta --></div><!-- .listing-widget-thumb -->' );

				if ( '' != get_post_meta( $post->ID, '_listing_open_house', true ) ) {
					$loop .= sprintf( '<span class="listing-open-house">Open House: %s</span>', get_post_meta( $post->ID, '_listing_open_house', true ) );
				}

				$loop .= sprintf( '<div class="listing-widget-details"><h3 class="listing-title"><a href="%s">%s</a></h3>', get_permalink(), get_the_title() );
				$loop .= sprintf( '<p class="listing-address"><span class="listing-address">%s</span><br />', wp_listings_get_address() );
				$loop .= sprintf( '<span class="listing-city-state-zip">%s, %s %s</span></p>', wp_listings_get_city(), wp_listings_get_state(), get_post_meta( $post->ID, '_listing_zip', true ) );

				if ( '' != get_post_meta( $post->ID, '_listing_bedrooms', true ) || '' != get_post_meta( $post->ID, '_listing_bathrooms', true ) || '' != get_post_meta( $post->ID, '_listing_sqft', true )) {
					$loop .= sprintf( '<ul class="listing-beds-baths-sqft"><li class="beds">%s<span>Beds</span></li> <li class="baths">%s<span>Baths</span></li> <li class="sqft">%s<span>Sq ft</span></li></ul>', get_post_meta( $post->ID, '_listing_bedrooms', true ), get_post_meta( $post->ID, '_listing_bathrooms', true ), get_post_meta( $post->ID, '_listing_sqft', true ) );
				}

				$loop .= sprintf('</div><!-- .listing-widget-details -->');

				$loop .= sprintf( '<a href="%s" class="button btn-primary more-link">%s</a>', get_permalink(), __( 'View Listing', 'wp-listings' ) );

				/** wrap in div with possible column class, and output **/
				printf( '<div class="listing %s post-%s"><div class="listing-wrap">%s</div></div>', $column_class . $first_class, $post->ID, apply_filters( 'wp_listings_featured_listings_widget_loop', $loop ) );

			endwhile; endif;
			wp_reset_postdata();

		echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
		$instance['image_size'] 	= strip_tags($new_instance['image_size'] );
		$instance['use_columns']    = (int) $new_instance['use_columns'];
		$instance['number_columns'] = (int) $new_instance['number_columns'];
		$instance['posts_term']     = strip_tags( $new_instance['posts_term'] );

		return $instance;
	}

	function form( $instance ) {

		$instance = wp_parse_args( $instance, array(
			'title'				=> '',
			'posts_per_page'	=> 3,
			'image_size'		=> 'listings',
			'use_columns'       => 0,
			'number_columns'    => 3,
			'posts_term'        => ''
		) );

		printf(
			'<p><label for="%s">%s</label><input type="text" id="%s" name="%s" value="%s" style="%s" /></p>',
			$this->get_field_id('title'),
			__( 'Title:', 'wp-listings' ),
			$this->get_field_id('title'),
			$this->get_field_name('title'),
			esc_attr( $instance['title'] ),
			'width: 95%;'
		); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image Size', 'wp-listings' ); ?>:</label>
			<select id="<?php echo $this->get_field_id( 'image_size' ); ?>" class="wp-listings-image-size-selector" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
				<option value="thumbnail">thumbnail (<?php echo absint( get_option( 'thumbnail_size_w' ) ); ?>x<?php echo absint( get_option( 'thumbnail_size_h' ) ); ?>)</option>
				<?php
				$sizes = wp_listings_get_additional_image_sizes();
				foreach ( (array) $sizes as $name => $size )
					echo '<option value="' . esc_attr( $name ) . '" ' . selected( $name, $instance['image_size'], FALSE ) . '>' . esc_html( $name ) . ' (' . absint( $size['width'] ) . 'x' . absint( $size['height'] ) . ')</option>';
				?>
			</select>
		</p>

		<?php
		printf(
			'<p>%s <input type="text" name="%s" value="%s" size="3" /></p>',
			__( 'How many results should be returned?', 'wp-listings' ),
			$this->get_field_name('posts_per_page'),
			esc_attr( $instance['posts_per_page'] )
		);

		echo '<p><label for="'. $this->get_field_id( 'posts_term' ) .'">Display by term:</label>

		<select id="'. $this->get_field_id( 'posts_term' ) .'" name="'. $this->get_field_name( 'posts_term' ) .'">
			<option style="padding-right:10px;" value="" '. selected( '', $instance['posts_term'], false ) .'>'. __( 'All Taxonomies and Terms', 'wp-listings' ) .'</option>';

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

		?>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['use_columns'], 1); ?> id="<?php echo $this->get_field_id( 'use_columns' ); ?>" name="<?php echo $this->get_field_name( 'use_columns' ); ?>" value="1" />
			<label for="<?php echo $this->get_field_id( 'use_columns' ); ?>">Split listings into columns?</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_columns' ); ?>">Number of columns</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'number_columns' ); ?>" name="<?php echo $this->get_field_name( 'number_columns' ); ?>">
				<option <?php selected($instance['number_columns'], 2); ?> value="2">2</option>
				<option <?php selected($instance['number_columns'], 3); ?> value="3">3</option>
				<option <?php selected($instance['number_columns'], 4); ?> value="4">4</option>
				<option <?php selected($instance['number_columns'], 5); ?> value="5">5</option>
				<option <?php selected($instance['number_columns'], 6); ?> value="6">6</option>
			</select>
		</p>

		<?php
	}
} // EOF
