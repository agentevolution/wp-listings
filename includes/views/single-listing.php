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

function single_listing_post_content() {

	global $post;
	$options = get_option('plugin_wp_listings_settings');

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

		if ( get_post_meta($post->ID, '_listing_hide_price', true) == 1 ) {
			$listing_meta .= (get_post_meta($post->ID, '_listing_price_alt', true)) ? sprintf( '<li class="listing-price">%s</li>', get_post_meta( $post->ID, '_listing_price_alt', true ) ) : '';
		} else {
			$listing_meta .= sprintf( '<li class="listing-price">%s %s %s</li>', '<span class="currency-symbol">' . $options['wp_listings_currency_symbol'] . '</span>', get_post_meta( $post->ID, '_listing_price', true ), (isset($options['wp_listings_display_currency_code']) && $options['wp_listings_display_currency_code'] == 1) ? '<span class="currency-code">' . $options['wp_listings_currency_code'] . '</span>' : '' );
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

		echo (get_post_meta($post->ID, '_listing_courtesy', true)) ? '<p class="wp-listings-courtesy">' . get_post_meta($post->ID, '_listing_courtesy', true) . '</p>' : '';

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
				<?php the_content( __( 'View more <span class="meta-nav">&rarr;</span>', 'wp-listings' ) );

				echo (get_post_meta($post->ID, '_listing_featured_on', true)) ? '<p class="wp_listings_featured_on">' . get_post_meta($post->ID, '_listing_featured_on', true) . '</p>' : '';

				if( get_post_meta($post->ID, '_listing_disclaimer', true) ) {
					echo '<p class="wp-listings-disclaimer">' . get_post_meta($post->ID, '_listing_disclaimer', true) . '</p>';
				} elseif ($options['wp_listings_global_disclaimer'] != '' && $options['wp_listings_global_disclaimer'] != null) {
					echo '<p class="wp-listings-disclaimer">' . $options['wp_listings_global_disclaimer'] . '</p>';
				}

				if(class_exists('Idx_Broker_Plugin') && $options['wp_listings_display_idx_link'] == true && get_post_meta($post->ID, '_listing_details_url', true)) {
					echo '<a href="' . get_post_meta($post->ID, '_listing_details_url', true) . '" title="' . get_post_meta($post->ID, '_listing_mls', true) . '">View full listing details</a>';
				}
				?>
			</div><!-- #listing-description -->

			<div id="listing-details">
				<?php
					$details_instance = new WP_Listings();

					$pattern = '<tr class="wp_listings%s"><td class="label">%s</td><td>%s</td></tr>';

					echo '<table class="listing-details">';

                    echo '<tbody class="left">';
                    if ( get_post_meta($post->ID, '_listing_hide_price', true) == 1 ) {
                    	echo (get_post_meta($post->ID, '_listing_price_alt', true)) ? '<tr class="wp_listings_listing_price"><td class="label">' . __('Price:', 'wp-listings') . '</td><td>'.get_post_meta( $post->ID, '_listing_price_alt', true) .'</td></tr>' : '';
                	} elseif(get_post_meta($post->ID, '_listing_price', true)) {
                    	echo '<tr class="wp_listings_listing_price"><td class="label">' . __('Price:', 'wp-listings') . '</td><td><span class="currency-symbol">' . $options['wp_listings_currency_symbol'] . '</span>';
                    	echo get_post_meta( $post->ID, '_listing_price', true) . ' ';
                    	echo (isset($options['wp_listings_display_currency_code']) && $options['wp_listings_display_currency_code'] == 1) ? '<span class="currency-code">' . $options['wp_listings_currency_code'] . '</span>' : '';
                    	echo '</td></tr>';
                	}
                    echo '<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">';
                    echo (get_post_meta($post->ID, '_listing_address', true)) ? '<tr class="wp_listings_listing_address"><td class="label">' . __('Address:', 'wp-listings') . '</td><td itemprop="streetAddress">'.get_post_meta( $post->ID, '_listing_address', true) .'</td></tr>' : '';
                    echo (get_post_meta($post->ID, '_listing_city', true)) ? '<tr class="wp_listings_listing_city"><td class="label">' . __('City:', 'wp-listings') . '</td><td itemprop="addressLocality">'.get_post_meta( $post->ID, '_listing_city', true) .'</td></tr>' : '';
                    echo (get_post_meta($post->ID, '_listing_county', true)) ? '<tr class="wp_listings_listing_county"><td class="label">' . __('County:', 'wp-listings') . '</td><td>'.get_post_meta( $post->ID, '_listing_county', true) .'</td></tr>' : '';
                    echo (get_post_meta($post->ID, '_listing_state', true)) ? '<tr class="wp_listings_listing_state"><td class="label">' . __('State:', 'wp-listings') . '</td><td itemprop="addressRegion">'.get_post_meta( $post->ID, '_listing_state', true) .'</td></tr>' : '';
                    echo (get_post_meta($post->ID, '_listing_zip', true)) ? '<tr class="wp_listings_listing_zip"><td class="label">' . __('Zip Code:', 'wp-listings') . '</td><td itemprop="postalCode">'.get_post_meta( $post->ID, '_listing_zip', true) .'</td></tr>' : '';
                    echo '</div>';
                    echo (get_post_meta($post->ID, '_listing_mls', true)) ? '<tr class="wp_listings_listing_mls"><td class="label">MLS:</td><td>'.get_post_meta( $post->ID, '_listing_mls', true) .'</td></tr>' : '';
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

					echo '<table class="listing-details extended">';
					echo '<tbody class="left">';
					foreach ( (array) $details_instance->extended_property_details['col1'] as $label => $key ) {
						$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
						if (! empty( $detail_value ) ) :
							printf( $pattern, $key, esc_html( $label ), $detail_value );
						endif;
					}
					echo '</tbody>';
					echo '<tbody class="right">';
					foreach ( (array) $details_instance->extended_property_details['col2'] as $label => $key ) {
						$detail_value = esc_html( get_post_meta($post->ID, $key, true) );
						if (! empty( $detail_value ) ) :
							printf( $pattern, $key, esc_html( $label ), $detail_value );
						endif;
					}
					echo '</tbody>';
					echo '</table>';

				if(get_the_term_list( get_the_ID(), 'features', '<li>', '</li><li>', '</li>' ) != null) {
					echo '<h5>' . __('Tagged Features:', 'wp-listings') . '</h5><ul class="tagged-features">';
					echo get_the_term_list( get_the_ID(), 'features', '<li>', '</li><li>', '</li>' );
					echo '</ul><!-- .tagged-features -->';
				}

				if ( get_post_meta( $post->ID, '_listing_home_sum', true) != '' || get_post_meta( $post->ID, '_listing_kitchen_sum', true) != '' || get_post_meta( $post->ID, '_listing_living_room', true) != '' || get_post_meta( $post->ID, '_listing_master_suite', true) != '') { ?>
					<div class="additional-features">
						<h4>Additional Features</h4>
						<h6 class="label"><?php _e("Home Summary", 'wp-listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_home_sum', true)); ?></p>
						<h6 class="label"><?php _e("Kitchen Summary", 'wp-listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_kitchen_sum', true)); ?></p>
						<h6 class="label"><?php _e("Living Room", 'wp-listings'); ?></h6>
						<p class="value"><?php echo do_shortcode(get_post_meta( $post->ID, '_listing_living_room', true)); ?></p>
						<h6 class="label"><?php _e("Master Suite", 'wp-listings'); ?></h6>
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
				<?php echo do_shortcode(get_post_meta( $post->ID, '_listing_video', true)); ?>
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
			elseif(get_post_meta( $post->ID, '_listing_latitude', true) && get_post_meta( $post->ID, '_listing_longitude', true) && get_post_meta( $post->ID, '_listing_automap', true) == 'y') {

				$map_info_content = sprintf('<p style="font-size: 14px; margin-bottom: 0;">%s<br />%s %s, %s</p>', get_post_meta( $post->ID, '_listing_address', true), get_post_meta( $post->ID, '_listing_city', true), get_post_meta( $post->ID, '_listing_state', true), get_post_meta( $post->ID, '_listing_zip', true));

				($options['wp_listings_gmaps_api_key']) ? $map_key = $options['wp_listings_gmaps_api_key'] : $map_key = '';

				echo '<script src="https://maps.googleapis.com/maps/api/js?key=' . $map_key . '"></script>
				<script>
					function initialize() {
						var mapCanvas = document.getElementById(\'map-canvas\');
						var myLatLng = new google.maps.LatLng(' . get_post_meta( $post->ID, '_listing_latitude', true) . ', ' . get_post_meta( $post->ID, '_listing_longitude', true) . ')
						var mapOptions = {
							center: myLatLng,
							zoom: 14,
							mapTypeId: google.maps.MapTypeId.ROADMAP
					    }

					    var marker = new google.maps.Marker({
						    position: myLatLng,
						    icon: \'//s3.amazonaws.com/ae-plugins/wp-listings/images/active.png\'
						});

						var infoContent = \' ' . $map_info_content . ' \';

						var infowindow = new google.maps.InfoWindow({
							content: infoContent
						});

					    var map = new google.maps.Map(mapCanvas, mapOptions);

					    marker.setMap(map);

					    infowindow.open(map, marker);
					}
					google.maps.event.addDomListener(window, \'load\', initialize);
				</script>
				';
				echo '<div id="listing-map"><h3>Location Map</h3><div id="map-canvas" style="width: 100%; height: 350px;"></div></div><!-- .listing-map -->';
			}
		?>

		<?php
			if (function_exists('_p2p_init') && function_exists('agent_profiles_init') ) {
				if(impa_has_listings($post->ID)) {
					echo'<div id="listing-agent">
					<div class="connected-agents">';
					aeprofiles_connected_agents_markup();
					echo '</div></div><!-- .listing-agent -->';
				}
			} elseif (function_exists('_p2p_init') && function_exists('impress_agents_init') ) {
				if(impa_has_listings($post->ID)) {
					echo'<div id="listing-agent">
					<div class="connected-agents">';
					impa_connected_agents_markup();
					echo '</div></div><!-- .listing-agent -->';
				}
			}
		?>

		<div id="listing-contact">

			<?php

			if (get_post_meta( $post->ID, '_listing_contact_form', true) != '') {

				echo do_shortcode(get_post_meta( $post->ID, '_listing_contact_form', true) );

			} elseif (isset($options['wp_listings_default_form']) && $options['wp_listings_default_form'] != '') {

				echo do_shortcode($options['wp_listings_default_form']);

			} else {

				echo '<h4>Listing Inquiry</h4>';
				$firstNameError = '';
				$lastNameError = '';
				$emailError = '';
				$response = '';

				if(isset($_POST['submitted'])) {

					$url = get_permalink();
					$listing = get_the_title();

					if(trim($_POST['firstName']) === '') {
						$firstNameError = 'Please enter your first name.';
						$hasError = true;
					} else {
						$firstName = esc_html(trim($_POST['firstName']));
					}

					if(trim($_POST['lastName']) === '') {
						$lastNameError = 'Please enter your last name.';
						$hasError = true;
					} else {
						$lastName = esc_html(trim($_POST['lastName']));
					}

					if(trim($_POST['email']) === '')  {
						$emailError = 'Please enter your email address.';
						$hasError = true;
					} else if (!is_email(trim($_POST['email']))) {
						$emailError = 'You entered an invalid email address.';
						$hasError = true;
					} else {
						$email = esc_html(trim($_POST['email']));
					}

					$phone = esc_html(trim($_POST['phone']));

					if(function_exists('stripslashes')) {
						$comments = esc_html(stripslashes(trim($_POST['comments'])));
					} else {
						$comments = esc_html(trim($_POST['comments']));
					}

					if($options['wp_listings_captcha_site_key'] != '' && $options['wp_listings_captcha_secret_key'] != '') {
						require_once( WP_LISTINGS_DIR . '/includes/recaptcha.php' );

						// your secret key
						$secret = $options['wp_listings_captcha_secret_key'];

						$gRecaptchaResponse = $_POST["g-recaptcha-response"];
						$remoteIp = $_SERVER["REMOTE_ADDR"];

						$recaptcha = new \ReCaptcha\ReCaptcha($secret);
						$resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
						if ($resp->isSuccess()) {
						    // verified!
						    $emailSent = true;
						} else {
						    $errors = $resp->getErrorCodes();
						    $emailSent = true;
						}
					}

					if(isset($_POST['antispam']) && $_POST['antispam'] == '' || $resp != null && $resp->isSuccess()) {
						if(!isset($hasError)) {
							$emailTo = get_the_author_meta( 'user_email', $post->post_author );
							if (!isset($emailTo) || ($emailTo == '') ){
								$emailTo = get_option('admin_email');
							}
							$subject = 'Listing Inquiry from '. $firstName . ' ' . $lastName;
							$body = 'Name: ' . $firstName . ' ' . $lastName . "\n\n" .'Email: ' . $email . "\n\n" . 'Phone: ' . $phone . "\n\n" . 'Listing: ' . $listing . "\n\n" . 'URL: ' . $url . "\n\n" .'Comments: ' . $comments;
							$headers = 'From: '.$firstName . ' ' . $lastName .' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

							wp_mail($emailTo, $subject, $body, $headers);

							// If option is set. PUT or POST contact form data to IDX as a lead or lead note
							if($options['wp_listings_idx_lead_form'] == 1) {
								$lead_data = array(
									'firstName' => $firstName,
									'lastName'  => $lastName,
									'email'		=> $email,
									'phone'		=> (isset($phone)) ? $phone : ''
								);
								$api_url = 'https://api.idxbroker.com/leads/lead';
								$args = array(
									'method' => 'PUT',
									'headers' => array(
										'content-type' => 'application/x-www-form-urlencoded',
										'accesskey'    => get_option('idx_broker_apikey'),
										'outputtype'   => 'json'
									),
									'sslverify' => false,
									'body'		=> http_build_query($lead_data)
								);
								$response = wp_remote_request($api_url, $args);

								// Check for error then add note
								if (is_wp_error($response)) {
									$hasError = true;
								} else {

									$decoded_response = json_decode($response['body']);
									$note = array(
										'note' => (isset($comments) && $comments != '') ? 'I\'m interested in this listing: <a href="' . $url . '">' . $listing . '</a>' . "\n\n" . 'Comments: ' . $comments : 'I\'m interested in this listing: <a href="' . $url . '">' . $listing . '</a>'
									);

									// Add note if lead already exists
									if($decoded_response == 'Lead already exists.') {
										$args = array_replace($args, array('method' => 'GET', 'body' => null));

										// Get leads
										if ( false === ( $all_leads = get_transient('idx_leads') ) ) {
											$response = wp_remote_request($api_url, $args);
											$all_leads = json_decode($response['body'], 1);
											set_transient('idx_leads', $all_leads, 60*60*1);
										}

										// Loop through leads to match email address
										foreach($all_leads as $leads => $lead) {
											if($lead['email'] == $email) {
												$api_url = 'https://api.idxbroker.com/leads/note/' . $lead['id'];
												$args = array_replace($args, array('method' => 'PUT', 'body' => http_build_query($note)));
												$response = wp_remote_request($api_url, $args);
												if (is_wp_error($response)) {
													$hasError = true;
												}
											}
										}
									} else {
										// Add note for new lead
										$lead_id = $decoded_response->newID;
										$api_url = 'https://api.idxbroker.com/leads/note/' . $lead_id;
										$args = array_replace($args, array('body' => http_build_query($note)));
										$response = wp_remote_request($api_url, $args);
										if (is_wp_error($response)) {
											$hasError = true;
										}
									}
								}
							}
							$emailSent = true;
						}
					} else {
						$emailSent = true; // make spammer think message went through
					}
				}
				?>

			<?php if(isset($emailSent) && $emailSent == true) {	?>
				<div class="thanks">
					<a name="redirectTo"></a>
					<p>Thanks, your message was sent! We'll be in touch shortly.</p>
				</div>
			<?php } else { ?>
				<?php if(isset($hasError)) { ?>
					<a name="redirectTo"></a>
					<label class="error" name="redirectTo">Sorry, an error occured. Please try again.<label>
				<?php } ?>

				<form action="<?php the_permalink(); ?>#redirectTo" id="inquiry-form" method="post">
					<ul class="inquiry-form">
						<li class="firstName">
							<label for="firstName">First name: <span class="required">*</span></label>
							<input type="text" name="firstName" id="firstName" value="<?php if(isset($_POST['firstName'])) echo esc_html($_POST['firstName']);?>" class="required requiredField" />
							<?php if($firstNameError != '') { ?>
								<label class="error"><?=$firstNameError;?></label>
							<?php } ?>
						</li>

						<li class="lastName">
							<label for="lastName">Last name: <span class="required">*</span></label>
							<input type="text" name="lastName" id="lastName" value="<?php if(isset($_POST['lastName'])) echo esc_html($_POST['lastName']);?>" class="required requiredField" />
							<?php if($lastNameError != '') { ?>
								<label class="error"><?=$lastNameError;?></label>
							<?php } ?>
						</li>

						<li class="contactEmail">
							<label for="email">Email: <span class="required">*</span></label>
							<input type="text" name="email" id="email" value="<?php if(isset($_POST['email']))  echo esc_html($_POST['email']);?>" class="required requiredField email" />
							<?php if($emailError != '') { ?>
								<label class="error"><?=$emailError;?></label>
							<?php } ?>
						</li>

						<li class="contactPhone">
							<label for="phone">Phone:</label>
							<input type="text" name="phone" id="phone" value="<?php if(isset($_POST['phone']))  echo esc_html($_POST['phone']);?>" />
						</li>

						<li class="contactComments"><label for="commentsText">Message:</label>
							<textarea name="comments" id="commentsText" rows="6" cols="20"><?php if(isset($_POST['comments'])) echo esc_html($_POST['comments']); ?></textarea>
						</li>

						<?php
						if($options['wp_listings_captcha_site_key'] != '' && $options['wp_listings_captcha_secret_key'] != '') {
							echo '<div class="g-recaptcha" data-sitekey="'. $options['wp_listings_captcha_site_key'] .'"></div>';
							echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
						} else {
							echo '<li>
									<input style="display: none;" type="text" name="antispam" />
								</li>';
						}
						?>

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

	$options = get_option('plugin_wp_listings_settings');

	get_header();
	if(isset($options['wp_listings_custom_wrapper']) && isset($options['wp_listings_start_wrapper']) && $options['wp_listings_start_wrapper'] != '') {
		echo $options['wp_listings_start_wrapper'];
	} else {
		echo '<div id="primary" class="content-area container inner">
			<div id="content" class="site-content" role="main">';
	}

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
					<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'wp-listings' ), __( '1 Comment', 'wp-listings' ), __( '% Comments', 'wp-listings' ) ); ?></span>
					<?php
						endif;

						edit_post_link( __( 'Edit', 'wp-listings' ), '<span class="edit-link">', '</span>' );
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

	if(isset($options['wp_listings_custom_wrapper']) && isset($options['wp_listings_end_wrapper']) && $options['wp_listings_end_wrapper'] != '') {
		echo $options['wp_listings_end_wrapper'];
	} else {
		echo '</div><!-- #content -->
		</div><!-- #primary -->';
	}

	get_sidebar();
	get_footer();

}
