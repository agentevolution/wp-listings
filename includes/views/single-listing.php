<?php
/**
 * The Template for displaying all single listing posts
 *
 * @package WP Listings
 * @since 0.1.0
 */

function single_listing_post_content() {

	global $post;

	?>

	<div class="entry-content">
		
		<?php echo get_the_post_thumbnail( $post->ID, 'full', array('class' => 'single-listing-image') ); ?>

		<div id="listing-tabs" class="listing-data">

			<ul>
				<li><a href="#listing-description">Description</a></li>

				<li><a href="#listing-details">Details</a></li>

				<li><a href="#listing-photos">Photos</a></li>

				<?php if (get_post_meta( $post->ID, '_listing_video', true) != '') { ?>
					<li><a href="#listing-video">Video Virtual Tour</a></li>
				<?php } ?>

				<?php if (get_post_meta( $post->ID, '_listing_school_neighborhood', true) != '') { ?>
				<li><a href="#listing-school-neighborhood">Schools &amp; Neighborhood</a></li>
				<?php } ?>
			</ul>

			<div id="listing-description">
				<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp_listings' ) ); ?>
			</div><!-- #listing-description -->

			<div id="listing-details">
				<?php
					$details_instance = new WP_Listings();

					$pattern = '<tr><td class="label">%s</td><td class="wp_listings[%s]">%s</td></tr>';

					echo '<table class="listing-details">';

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

				if ( get_post_meta( $post->ID, '_listing_home_sum', true) != '' || get_post_meta( $post->ID, '_listing_kitchen_sum', true) != '' || get_post_meta( $post->ID, '_listing_living_room', true) != '' || get_post_meta( $post->ID, '_listing_master_suite', true) != '') { ?>
					<div class="additional-features">
						<h4>Additional Features</h4>
						<h6 class="label"><?php _e("Home Summary", 'wp_listings'); ?></h6>
						<p class="value"><?php echo get_post_meta( $post->ID, '_listing_home_sum', true); ?></p>
						<h6 class="label"><?php _e("Kitchen Summary", 'wp_listings'); ?></h6>
						<p class="value"><?php echo get_post_meta( $post->ID, '_listing_kitchen_sum', true); ?></p>
						<h6 class="label"><?php _e("Living Room", 'wp_listings'); ?></h6>
						<p class="value"><?php echo get_post_meta( $post->ID, '_listing_living_room', true); ?></p>
						<h6 class="label"><?php _e("Master Suite", 'wp_listings'); ?></h6>
						<p class="value"><?php echo get_post_meta( $post->ID, '_listing_master_suite', true); ?></p>
					</div><!-- .additional-features -->
				<?php
				}

				echo '<h5>Tagged Features</h5><ul class="tagged-features">';
				echo get_the_term_list( get_the_ID(), 'features', '<li>', '</li><li>', '</li>' );
				echo '</ul><!-- .tagged-features -->'; ?>

			</div><!-- #listing-details -->


			<?php if (get_post_meta( $post->ID, '_listing_video', true) != '') { ?>
			<div id="listing-video">
				<div class="iframe-wrap">
				<?php echo get_post_meta( $post->ID, '_listing_video', true); ?>
				</div>
			</div><!-- #listing-video -->
			<?php } ?>

			<?php if (get_post_meta( $post->ID, '_listing_school_neighborhood', true) != '') { ?>
			<div id="listing-school-neighborhood">
				<p>
				<?php echo get_post_meta( $post->ID, '_listing_school_neighborhood', true); ?>
				</p>
			</div><!-- #listing-video -->
			<?php } ?>

		</div><!-- #listing-tabs.listing-data -->
	</div><!-- .entry-content -->

<?php
}

if (function_exists('genesis_init')) {

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	remove_action( 'genesis_after_entry', 'genesis_do_author_box_single' );

	remove_action( 'genesis_entry_content' , 'genesis_do_post_content' );
	add_action( 'genesis_entry_content' , 'single_listing_post_content' );

	genesis();

} else {

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

					
			<?php

				single_listing_post_content();


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

}