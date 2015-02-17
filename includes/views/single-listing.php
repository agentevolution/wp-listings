<?php
/**
 * The Template for displaying all single listing posts
 *
 * @package WP Listings
 * @since 0.1.0
 */

add_action('wp_enqueue_scripts', 'enqueue_single_listing_scripts');
function enqueue_single_listing_scripts() {
	wp_enqueue_style( 'wp-listings-single' );
	wp_enqueue_style( 'font-awesome' );
	wp_enqueue_script( 'jquery-validate', array('jquery'), true, true );
	wp_enqueue_script( 'fitvids', array('jquery'), true, true );
	wp_enqueue_script( 'wp-listings-single', array('jquery, jquery-ui-tabs', 'jquery-validate'), true, true );
}

/** Set DNS Prefetch to improve performance on single listings templates */
add_filter('wp_head','wp_listings_dnsprefetch', 0);
function wp_listings_dnsprefetch() {
    echo "\n<link rel='dns-prefetch' href='//maxcdn.bootstrapcdn.com' />\n"; // Loads FontAwesome
    echo "<link rel='dns-prefetch' href='//cdnjs.cloudflare.com' />\n"; // Loads FitVids
}

function single_listing_post_content() {

	global $post;

	?>

	<div itemscope itemtype="http://schema.org/SingleFamilyResidence" class="entry-content wplistings-single-listing">

		<div class="listing-image-wrap">
			<?php echo '<div itemprop="image" itemscope itemtype="http://schema.org/ImageObject">'. get_the_post_thumbnail( $post->ID, 'listings-full', array('class' => 'single-listing-image', 'itemprop'=>'contentUrl') ) . '</div>';
			if ( '' != wp_listings_get_status() ) {
				printf( '<span class="listing-status %s">%s</span>', strtolower(str_replace(' ', '-', wp_listings_get_status())), wp_listings_get_status() );
			}
			if ( '' != get_post_meta( $post->ID, '_listing_open_house', true ) ) {
				printf( '<span class="listing-open-house">Open House: %s</span>', get_post_meta( $post->ID, '_listing_open_house', true ) );
			} ?>
		</div><!-- .listing-image-wrap -->

		<?php
		$listing_meta = sprintf( '<ul class="listing-meta">');

		if ( '' != get_post_meta( $post->ID, '_listing_price', true ) ) {
			$listing_meta .= sprintf( '<li class="listing-price">%s</li>', get_post_meta( $post->ID, '_listing_price', true ) );
		}

		if ( '' != wp_listings_get_property_types() ) {
			$listing_meta .= sprintf( '<li class="listing-property-type"><span class="label">Property Type: </span>%s</li>', get_the_term_list( get_the_ID(), 'property-types', '', ', ', '' ) );
		}

		if ( '' != wp_listings_get_locations() ) {
			$listing_meta .= sprintf( '<li class="listing-location"><span class="label">Location: </span>%s</li>', get_the_term_list( get_the_ID(), 'locations', '', ', ', '' ) );
		}

		if ( '' != get_post_meta( $post->ID, '_listing_bedrooms', true ) ) {
			$listing_meta .= sprintf( '<li class="listing-bedrooms"><span class="label">Beds: </span>%s</li>', get_post_meta( $post->ID, '_listing_bedrooms', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_listing_bathrooms', true ) ) {
			$listing_meta .= sprintf( '<li class="listing-bathrooms"><span class="label">Baths: </span>%s</li>', get_post_meta( $post->ID, '_listing_bathrooms', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_listing_sqft', true ) ) {
			$listing_meta .= sprintf( '<li class="listing-sqft"><span class="label">Sq Ft: </span>%s</li>', get_post_meta( $post->ID, '_listing_sqft', true ) );
		}

		if ( '' != get_post_meta( $post->ID, '_listing_lot_sqft', true ) ) {
			$listing_meta .= sprintf( '<li class="listing-lot-sqft"><span class="label">Lot Sq Ft: </span>%s</li>', get_post_meta( $post->ID, '_listing_lot_sqft', true ) );
		}

		$listing_meta .= sprintf( '</ul>');

		echo $listing_meta;

		?>

		<div id="listing-tabs" class="listing-data">

			<ul>
				<li><a href="#listing-description">Description</a></li>

				<li><a href="#listing-details">Details</a></li>


				<?php if (get_post_meta( $post->ID, '_listing_gallery', true) != '') { ?>
					<li><a href="#listing-gallery">Photos</a></li>
				<?php } ?>

				<?php if (get_post_meta( $post->ID, '_listing_video', true) != '') { ?>
					<li><a href="#listing-video">Video / Virtual Tour</a></li>
				<?php } ?>

				<?php if (get_post_meta( $post->ID, '_listing_school_neighborhood', true) != '') { ?>
				<li><a href="#listing-school-neighborhood">Schools &amp; Neighborhood</a></li>
				<?php } ?>
			</ul>

			<div id="listing-description" itemprop="description">
				<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp_listings' ) ); ?>
			</div><!-- #listing-description -->

			<div id="listing-details">
				<?php
					$details_instance = new WP_Listings();

					$pattern = '<tr class="wp_listings%s"><td class="label">%s</td><td>%s</td></tr>';

					echo '<table class="listing-details">';

                    echo '<tbody class="left">';
                    echo '<tr class="wp_listings_listing_price"><td class="label">Price:</td><td>'.get_post_meta( $post->ID, '_listing_price', true) .'</td></tr>';
                    echo '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
                    echo '<tr class="wp_listings_listing_address"><td class="label">Address:</td><td itemprop="streetAddress">'.get_post_meta( $post->ID, '_listing_address', true) .'</td></tr>';
                    echo '<tr class="wp_listings_listing_city"><td class="label">City:</td><td itemprop="addressLocality">'.get_post_meta( $post->ID, '_listing_city', true) .'</td></tr>';
                    echo '<tr class="wp_listings_listing_state"><td class="label">State:</td><td itemprop="addressRegion">'.get_post_meta( $post->ID, '_listing_state', true) .'</td></tr>';
                    echo '<tr class="wp_listings_listing_zip"><td class="label">Zip:</td><td itemprop="postalCode">'.get_post_meta( $post->ID, '_listing_zip', true) .'</td></tr>';
                    echo '</div>';
                    echo '<tr class="wp_listings_listing_mls"><td class="label">MLS:</td><td>'.get_post_meta( $post->ID, '_listing_mls', true) .'</td></tr>';
                    echo '</tbody>';

					echo '<tbody class="right">';
					foreach ( (array) $details_instance->property_details['col2'] as $label => $key ) {
						$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
						if (! empty( $detail_value ) ) :
							printf( $pattern, $key, esc_html( $label ), $detail_value );
						endif;
					}
					echo '</tbody>';

					echo '</table>';

				echo '<h5>Tagged Features</h5><ul class="tagged-features">';
				echo get_the_term_list( get_the_ID(), 'features', '<li>', '</li><li>', '</li>' );
				echo '</ul><!-- .tagged-features -->';

				if ( get_post_meta( $post->ID, '_listing_home_sum', true) != '' || get_post_meta( $post->ID, '_listing_kitchen_sum', true) != '' || get_post_meta( $post->ID, '_listing_living_room', true) != '' || get_post_meta( $post->ID, '_listing_master_suite', true) != '') { ?>
					<div class="additional-features">
						<h4>Additional Features</h4>
						<h6 class="label"><?php _e("Home Summary", 'wp_listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_home_sum', true)); ?></p>
						<h6 class="label"><?php _e("Kitchen Summary", 'wp_listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_kitchen_sum', true)); ?></p>
						<h6 class="label"><?php _e("Living Room", 'wp_listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_living_room', true)); ?></p>
						<h6 class="label"><?php _e("Master Suite", 'wp_listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_master_suite', true)); ?></p>
					</div><!-- .additional-features -->
				<?php
				} ?>

			</div><!-- #listing-details -->

			<?php if (get_post_meta( $post->ID, '_listing_gallery', true) != '') { ?>
			<div id="listing-gallery">
				<?php echo do_shortcode(get_post_meta( $post->ID, '_listing_gallery', true)); ?>
			</div><!-- #listing-gallery -->
			<?php } ?>

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
				<?php echo do_shortcode(get_post_meta( $post->ID, '_listing_school_neighborhood', true)); ?>
				</p>
			</div><!-- #listing-school-neighborhood -->
			<?php } ?>

		</div><!-- #listing-tabs.listing-data -->

		<?php
			if (get_post_meta( $post->ID, '_listing_map', true) != '') {
			echo '<div id="listing-map"><h3>Location Map</h3>';
			echo do_shortcode(get_post_meta( $post->ID, '_listing_map', true) );
			echo '</div><!-- .listing-map -->';
			}
		?>

		<?php
			if (function_exists('_p2p_init') && function_exists('agent_profiles_init') ) {
				echo'<div id="listing-agent">
				<div class="connected-agents">';
				aeprofiles_connected_agents_markup();
				echo '</div></div><!-- .listing-agent -->';
			}
		?>

		<div id="listing-contact" <?php if(!function_exists('aeprofiles_connected_agents_markup')) { echo 'style="width: 100%;"'; }; ?>>
			<h4>Listing Inquiry</h4>
			<?php
			if (get_post_meta( $post->ID, '_listing_contact_form', true) != '') {

				echo do_shortcode(get_post_meta( $post->ID, '_listing_contact_form', true) );

			} else {

				$nameError = '';
				$emailError = '';

				if(isset($_POST['submitted'])) {

				$url = get_permalink();
				$listing = get_the_title();

				if(trim($_POST['contactName']) === '') {
					$nameError = 'Please enter your name.';
					$hasError = true;
				} else {
					$name = trim($_POST['contactName']);
				}

				if(trim($_POST['email']) === '')  {
					$emailError = 'Please enter your email address.';
					$hasError = true;
				} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
					$emailError = 'You entered an invalid email address.';
					$hasError = true;
				} else {
					$email = trim($_POST['email']);
				}

				$phone = trim($_POST['phone']);

				if(function_exists('stripslashes')) {
					$comments = stripslashes(trim($_POST['comments']));
				} else {
					$comments = trim($_POST['comments']);
				}


				if(isset($_POST['antispam']) && $_POST['antispam'] == '') {
					if(!isset($hasError)) {
						$emailTo = get_the_author_meta( 'user_email', $post->post_author );
						if (!isset($emailTo) || ($emailTo == '') ){
							$emailTo = get_option('admin_email');
						}
						$subject = 'Listing Inquiry from '.$name;
						$body = "Name: $name \n\nEmail: $email \n\nPhone: $phone \n\nListing: $listing \n\nURL: $url \n\nComments: $comments";
						$headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

						wp_mail($emailTo, $subject, $body, $headers);
						$emailSent = true;
					}
				} else {
					$emailSent = true; // make spammer think message went through
				}

			} ?>

			<?php if(isset($emailSent) && $emailSent == true) {	?>
				<div class="thanks">
					<a name="redirectTo"></a>
					<p>Thanks, your email was sent! We'll be in touch shortly.</p>
				</div>
			<?php } else { ?>
				<?php if(isset($hasError)) { ?>
					<a name="redirectTo"></a>
					<label class="error" name="redirectTo">Sorry, an error occured. Please try again.<label>
				<?php } ?>

				<form action="<?php the_permalink(); ?>#redirectTo" id="inquiry-form" method="post">
					<ul class="inquiry-form">
						<li class="contactName">
							<label for="contactName">Name: <span class="required">*</span></label>
							<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="required requiredField" />
							<?php if($nameError != '') { ?>
								<label class="error"><?=$nameError;?></label>
							<?php } ?>
						</li>

						<li class="contactEmail">
							<label for="email">Email: <span class="required">*</span></label>
							<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="required requiredField email" />
							<?php if($emailError != '') { ?>
								<label class="error"><?=$emailError;?></label>
							<?php } ?>
						</li>

						<li class="contactPhone">
							<label for="phone">Phone:</label>
							<input type="text" name="phone" id="phone" value="<?php if(isset($_POST['phone']))  echo $_POST['phone'];?>" />
						</li>

						<li class="contactComments"><label for="commentsText">Message:</label>
							<textarea name="comments" id="commentsText" rows="6" cols="20"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
						</li>

						<li>
							<input style="display: none;" type="text" name="antispam" />
						</li>

						<li>
							<input id="submit" type="submit" value="Send Inquiry"></input>
						</li>
					</ul>
					<input type="hidden" name="submitted" id="submitted" value="true" />
				</form>
			<?php }

			}
			?>
		</div><!-- .listing-contact -->

	</div><!-- .entry-content -->

<?php
}

if (function_exists('equity')) {

	remove_action( 'equity_entry_header', 'equity_post_info', 12 );
	remove_action( 'equity_entry_footer', 'equity_post_meta' );

	remove_action( 'equity_entry_content', 'equity_do_post_content' );
	add_action( 'equity_entry_content', 'single_listing_post_content' );

	equity();

} elseif (function_exists('genesis_init')) {

	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
	remove_action( 'genesis_entry_header', 'genesis_post_info', 12 ); // HTML5
	remove_action( 'genesis_before_post_content', 'genesis_post_info' ); // XHTML
	remove_action( 'genesis_entry_footer', 'genesis_post_meta' ); // HTML5
	remove_action( 'genesis_after_post_content', 'genesis_post_meta' ); // XHTML
	remove_action( 'genesis_after_entry', 'genesis_do_author_box_single', 8 ); // HTML5
	remove_action( 'genesis_after_post', 'genesis_do_author_box_single' ); // XHTML

	remove_action( 'genesis_entry_content', 'genesis_do_post_content' ); // HTML5
	remove_action( 'genesis_post_content', 'genesis_do_post_content' ); // XHTML
	add_action( 'genesis_entry_content', 'single_listing_post_content' ); // HTML5
	add_action( 'genesis_post_content', 'single_listing_post_content' ); // XHTML

	genesis();

} else {

get_header(); ?>

	<div id="primary" class="content-area container inner">
		<div id="content" class="site-content" role="main">

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title" itemprop="name">', '</h1>' ); ?>
						<small><?php if ( function_exists('yoast_breadcrumb') ) { yoast_breadcrumb('<p id="breadcrumbs">','</p>'); } ?></small>
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


				<?php single_listing_post_content(); ?>

				</article><!-- #post-ID -->

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

}