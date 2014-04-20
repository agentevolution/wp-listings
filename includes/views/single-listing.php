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

						<?php echo get_the_post_thumbnail( $post->ID, 'full', array('class' => 'single-listing-image') ); ?>

						<div class="iframe-wrap">
							<?php echo get_post_meta( $post->ID, '_listing_video', true); ?>
						</div>

						<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp_listings' ) ); ?>

						<div class="listing-data">
							<div id="property-details">
							<h4>Property Details - WP LISTINGS</h4>
							<?php
								$details_instance = new WP_Listings();

								$pattern = '<tr><td class="label">%s</td><td class="wp_listings[%s]">%s</td></tr>';

								echo '<table class="listing-details one-half first">';

								echo '<tbody>';
								foreach ( (array) $details_instance->property_details['col1'] as $label => $key ) {
									$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
									if (! empty( $detail_value ) ) :
										printf( $pattern, esc_html( $label ), $key, $detail_value );
									endif;
								}
								echo '</tbody>';

								echo '<tbody>';
								foreach ( (array) $details_instance->property_details['col2'] as $label => $key ) {
									$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
									if (! empty( $detail_value ) ) :
										printf( $pattern, esc_html( $label ), $key, $detail_value );
									endif;
								}
								echo '</tbody>';

								echo '</table>';
							?>

							<?php if (get_post_meta( $post->ID, '_listing_vtour_url', true) != '') { ?>
								<p class="vid-tour-url"><span class="label"><?php _e("Virtual Tour:", 'wp_listings'); ?></span><span class="value"><a href="<?php echo get_post_meta( $post->ID, '_vtour_url', true); ?>">View Virtual Tour</a></span></p>
								<?php } ?>

								<?php echo get_the_term_list( get_the_ID(), 'features', '<p class="tagged-features"><span class="label">Tagged Features:</span>&nbsp;', ', ', '</p><!-- .tagged-features -->' ) ?>

							</div><!-- .property-details -->

							<?php if ( get_post_meta( $post->ID, '_listing_home_sum', true) != '' || get_post_meta( $post->ID, '_listing_kitchen_sum', true) != '' || get_post_meta( $post->ID, '_listing_family_room', true) != '' || get_post_meta( $post->ID, '_listing_living_room', true) != '' || get_post_meta( $post->ID, '_listing_master_suite', true) != '') { ?>
								<div class="additional-features">
									<h4>Additional Features</h4>
									<span class="label"><?php _e("Home Summary", 'wp_listings'); ?></span>
									<p class="value"><?php echo get_post_meta( $post->ID, '_listing_home_sum', true); ?></p>
									<span class="label"><?php _e("Kitchen Summary", 'wp_listings'); ?></span>
									<p class="value"><?php echo get_post_meta( $post->ID, '_listing_kitchen_sum', true); ?></p>
									<span class="label"><?php _e("Family Room", 'wp_listings'); ?></span>
									<p class="value"><?php echo get_post_meta( $post->ID, '_listing_family_room', true); ?></p>
									<span class="label"><?php _e("Living Room", 'wp_listings'); ?></span>
									<p class="value"><?php echo get_post_meta( $post->ID, '_listing_living_room', true); ?></p>
									<span class="label"><?php _e("Master Suite", 'wp_listings'); ?></span>
									<p class="value"><?php echo get_post_meta( $post->ID, '_listing_master_suite', true); ?></p>
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