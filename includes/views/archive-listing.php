<?php
/**
 * The template for displaying Listing Archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP Listings
 * @since 0.1.0
 */

get_header(); ?>

	<section id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

						<header class="entry-header">
							<?php
							$object = get_queried_object();

							if ( !isset($object->label) ) {
								$title = '<h1 class="entry-title">' . $object->name . '</h1>';
							} else {
								$title = '<h1 class="entry-title">' . get_bloginfo('name') . ' Listings</h1>';
							}

							echo $title; ?>
						</header><!-- .page-header -->

						<?php

						// Start the Loop.
						while ( have_posts() ) : the_post();

							$loop = sprintf( '<a href="%s">%s</a>', get_permalink(), get_the_post_thumbnail( $post->ID, 'listings' ) );

							if ( '' != get_post_meta( $post->ID, '_listing_price', true ) ) {
								$loop .= sprintf( '<span class="listing-price">%s</span>', get_post_meta( $post->ID, '_listing_price', true ) );
							}

							if ( '' != wp_listings_get_status() ) {
								$loop .= sprintf( '<span class="listing-text">%s</span>', wp_listings_get_status() );
							}

							$loop .= sprintf( '<h3 class="listing-title"><a href="%s">%s</a></h3>', get_permalink(), get_the_title() );

							$loop .= sprintf( '<span class="listing-address">%s</span>', wp_listings_get_address() );

							$loop .= sprintf( '<span class="listing-city-state-zip">%s, %s %s</span>', wp_listings_get_city(), wp_listings_get_state(), get_post_meta($post->ID, '_listing_zip', true ) );

							$loop .= sprintf( '<ul class="listing-beds-baths-sqft"><li class="beds">%s<span>Beds</span></li> <li class="baths">%s<span>Baths</span></li> <li class="sqft">%s<span>Sq ft</span></li></ul>', get_post_meta( $post->ID, '_listing_bedrooms', true ), get_post_meta( $post->ID, '_listing_bathrooms', true ), get_post_meta( $post->ID, '_listing_sqft', true ) );

							if ( '' != get_post_meta( $post->ID, '_listing_open_house', true ) ) {
								$loop .= sprintf( '<span class="listing-open-house">Open House: %s</span>', get_post_meta( $post->ID, '_listing_open_house', true ) );
							}

							printf( '<div class="listing-wrap">%s</div>', $loop );

						endwhile;
						// Previous/next page navigation.
						wp_listings_paging_nav();

						else :
							// If no content, include the "No posts found" template.
							get_template_part( 'content', 'none' );

						endif;

						?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
