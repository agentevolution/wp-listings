<?php
/**
 * The Template for displaying all single listing posts
 *
 * @package WP Listings
 * @since 0.1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php echo get_the_post_thumbnail( 'full', 'attr' => array('class' => 'single-listing-image')); ?>

					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<div class="entry-meta">
							<?php
								if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wp_listings' ), __( '1 Comment', 'wp_listings' ), __( '% Comments', 'wp_listings' ) ); ?></span>
							<?php
								endif;

								edit_post_link( __( 'Edit', 'wp_listings' ), '<span class="edit-link">', '</span>' );
							?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->

					
					<div class="entry-content">
						<div class="iframe-wrap">
							<?php echo get_post_meta('_listing_video', true); ?>
						</div>

						<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp_listings' ) ); ?>

						<?php
						function wp_listings_get_neighborhood() {

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
									<span class="label"><?php _e("Price:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_price', true); ?></span>
									<span class="label"><?php _e("Address:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_address', true); ?></span>
									<span class="label"><?php _e("City:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_city', true); ?></span>
									<span class="label"><?php _e("State:", 'ale'); ?></span><span class="value"><?php echo ale_get_state(); ?></span>
									<span class="label"><?php _e("Zip Code:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_zip', true); ?></span>
									<span class="label"><?php _e("Neighborhood:", 'ale'); ?></span><span class="value"><?php ale_get_neighborhood(); ?></span>
								</div><!-- .left -->
								<div class="right">
									<span class="label"><?php _e("MLS #:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_mls', true); ?></span>
									<span class="label"><?php _e("Square Feet:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_sqft', true); ?></span>
									<span class="label"><?php _e("Lot Square Feet:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_lot_sqft', true); ?></span>
									<span class="label"><?php _e("Bedrooms:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_bedrooms', true); ?></span>
									<span class="label"><?php _e("Bathrooms:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_bathrooms', true); ?></span>
									<span class="label"><?php _e("Open House:", 'ale'); ?></span><span class="value"><?php echo get_post_meta('_listing_open_house', true); ?></span>
								</div><!-- .right -->

								<?php if (get_post_meta('_listing_vtour_url', true) != '') { ?>
								<p class="vid-tour-url"><span class="label"><?php _e("Virtual Tour:", 'ale'); ?></span><span class="value"><a href="<?php echo get_post_meta('_vtour_url', true); ?>">View Virtual Tour</a></span></p>
								<?php } ?>

								<?php echo get_the_term_list( get_the_ID(), 'features', '<p class="tagged-features"><span class="label">Tagged Features:</span>&nbsp;', ', ', '</p><!-- .tagged-features -->' ) ?>
							</div><!-- .property-details -->

							<?php if ( get_post_meta('_listing_home_sum', true) != '' || get_post_meta('_listing_kitchen_sum', true) != '' || get_post_meta('_listing_family_room', true) != '' || get_post_meta('_listing_living_room', true) != '' || get_post_meta('_listing_master_suite', true) != '') { ?>
								<div class="additional-features">
									<h4>Additional Features</h4>
									<span class="label"><?php _e("Home Summary", 'ale'); ?></span>
									<p class="value"><?php echo get_post_meta('_listing_home_sum', true); ?></p>
									<span class="label"><?php _e("Kitchen Summary", 'ale'); ?></span>
									<p class="value"><?php echo get_post_meta('_listing_kitchen_sum', true); ?></p>
									<span class="label"><?php _e("Family Room", 'ale'); ?></span>
									<p class="value"><?php echo get_post_meta('_listing_family_room', true); ?></p>
									<span class="label"><?php _e("Living Room", 'ale'); ?></span>
									<p class="value"><?php echo get_post_meta('_listing_living_room', true); ?></p>
									<span class="label"><?php _e("Master Suite", 'ale'); ?></span>
									<p class="value"><?php echo get_post_meta('_listing_master_suite', true); ?></p>
								</div><!-- .additional-features -->
							<?php } ?>

						</div><!-- .listing-data -->
					</div><!-- .entry-content -->


			<?php 
			// Previous/next post navigation.
					wp_listings_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();