<?php
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );
remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );
remove_action( 'genesis_post_content' , 'genesis_do_post_content' );
add_action( 'genesis_post_content' , 'genesis_do_listing_post_content' );

function genesis_do_listing_post_content() { ?>

	<?php echo genesis_get_image(array('attr' => array('class' => 'single-listing-image'))); ?>

	<div class="iframe-wrap">
		<?php echo genesis_get_custom_field('_listing_video'); ?>
	</div>

	<?php the_content(); ?>

	<?php
	function ale_get_neighborhood() {

		$neighborhoods = get_the_terms( get_the_ID(), 'locations' );

		if ( empty($neighborhoods) ) {
			return;
		}

		foreach ($neighborhoods as $value) {
			echo $value->name;
		}
	}
	?>

	<div class="listing-data">
		<div class="property-details">
			<h4>Property Details</h4>
			<div class="left">
				<span class="label"><?php _e("Price:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_price'); ?></span>
				<span class="label"><?php _e("Address:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_address'); ?></span>
				<span class="label"><?php _e("City:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_city'); ?></span>
				<span class="label"><?php _e("State:", 'ale'); ?></span><span class="value"><?php echo ale_get_state(); ?></span>
				<span class="label"><?php _e("Zip Code:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_zip'); ?></span>
				<span class="label"><?php _e("Neighborhood:", 'ale'); ?></span><span class="value"><?php ale_get_neighborhood(); ?></span>
			</div><!-- .left -->
			<div class="right">
				<span class="label"><?php _e("MLS #:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_mls'); ?></span>
				<span class="label"><?php _e("Square Feet:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_sqft'); ?></span>
				<span class="label"><?php _e("Lot Square Feet:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_lot_sqft'); ?></span>
				<span class="label"><?php _e("Bedrooms:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_bedrooms'); ?></span>
				<span class="label"><?php _e("Bathrooms:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_listing_bathrooms'); ?></span>
				<span class="label"><?php _e("Open House:", 'ale'); ?></span><span class="value"><?php echo genesis_get_custom_field('_open_house'); ?></span>
			</div><!-- .right -->
			<?php if (genesis_get_custom_field('_vtour_url') != '') { ?>
			<p class="vid-tour-url"><span class="label"><?php _e("Video Tour URL:", 'ale'); ?></span><span class="value"><a href="<?php echo genesis_get_custom_field('_vtour_url'); ?>"><?php echo genesis_get_custom_field('_vtour_url'); ?></a></span></p>
			<?php } ?>
			<p class="tagged-features"><?php echo get_the_term_list( get_the_ID(), 'features', '<span class="label">Tagged Features:</span>&nbsp;', ', ', '' ) ?></p>
		</div><!-- .property-details -->

		<?php if ( genesis_get_custom_field('_listing_home_sum') != '' || genesis_get_custom_field('_listing_kitchen_sum') != '' || genesis_get_custom_field('_listing_family_room') != '' || genesis_get_custom_field('_listing_living_room') != '' || genesis_get_custom_field('_listing_master_suite') != '') { ?>
			<div class="additional-features">
				<h4>Additional Features</h4>
				<span class="label"><?php _e("Home Summary", 'ale'); ?></span>
				<p class="value"><?php echo genesis_get_custom_field('_listing_home_sum'); ?></p>
				<span class="label"><?php _e("Kitchen Summary", 'ale'); ?></span>
				<p class="value"><?php echo genesis_get_custom_field('_listing_kitchen_sum'); ?></p>
				<span class="label"><?php _e("Family Room", 'ale'); ?></span>
				<p class="value"><?php echo genesis_get_custom_field('_listing_family_room'); ?></p>
				<span class="label"><?php _e("Living Room", 'ale'); ?></span>
				<p class="value"><?php echo genesis_get_custom_field('_listing_living_room'); ?></p>
				<span class="label"><?php _e("Master Suite", 'ale'); ?></span>
				<p class="value"><?php echo genesis_get_custom_field('_listing_master_suite'); ?></p>
			</div><!-- .additional-features -->
		<?php } ?>

	</div><!-- .listing-data -->

	<?php

	echo do_shortcode('[gallery link="file"]');

	echo '<div class="map-wrap">';
	ale_show_map();
	echo '</div><!-- .map-wrap -->';

}

genesis();