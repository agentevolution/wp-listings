<?php
/**
 * This file contains the methods for interacting with the IDX API
 * to import listing data
 */

if ( ! defined( 'ABSPATH' ) ) exit;
class WPL_Idx_Listing {

	public $_idx;
	
	public function __construct() {		
		//self::get_featured();
	}

	public static function get_featured() {
	
		// require_once(ABSPATH . 'wp-content/plugins/idx-broker-platinum/idx/idx-api.php');
		// $_idx_api = new \IDX\Idx_Api;
		// $properties = $_idx_api->client_properties('featured');
		// $idx_featured_listing_wp_options = get_option('wp_listings_idx_featured_listing_wp_options');

		// $mls = $_idx_api->client_properties('featured');

		// foreach($properties as $i => $v) {
		// 	$key = self::get_key($idx_featured_listing_wp_options, 'listingID', $v['listingID']);
		// 	if($key === false){
		// 		$idx_featured_listing_wp_options[]['listingID'] = $v['listingID'];
		// 	}
		// }
		// update_option('wp_listings_idx_featured_listing_wp_options', $idx_featured_listing_wp_options);

	}

	public static function get_key($array, $key, $needle) {
		if(!$array) return false;
		foreach($array as $index => $value) {
			if($value[$key] == $needle) return $index;
		}
		return false;
	}

	public static function in_array($needle, $haystack, $strict = false) {
		if(!$haystack) return false;
		foreach ($haystack as $item) {
			if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_array($needle, $item, $strict))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Creates a post of listing type using post data from options page
	 * @param  [type] $listings [post data]
	 * @return [type] $featured [description]
	 */
	public static function wp_listings_idx_create_post($listings) {
		if(class_exists( 'Equity_Idx_Api' )) {
			require_once(ABSPATH . 'wp-content/themes/equity/lib/idx/class.Equity_Idx_Api.inc.php');
			$_equity_idx = new Equity_Idx_Api;
			require_once(ABSPATH . 'wp-content/plugins/idx-broker-platinum/idx/idx-api.php');
			$_idx_api = new \IDX\Idx_Api;
			$properties = $_idx_api->client_properties('featured');

			$idx_featured_listing_wp_options = get_option('wp_listings_idx_featured_listing_wp_options');

			foreach($properties as $prop) {

				if(isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) && !get_post($idx_featured_listing_wp_options[$prop['listingID']]['post_id'])) {
					unset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']);
					unset($idx_featured_listing_wp_options[$prop['listingID']]['status']);
			 	}

				if(in_array($prop['listingID'], $listings) && !isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id'])) {

					if(class_exists( 'Equity_Idx_Listing' )) {
						$idx_featured_listing_data = $_equity_idx->equity_listing_ID($prop['idxID'], $prop['listingID']);
					} else {
						$idx_featured_listing_data = $_idx_api->client_properties('featured');
					}

					if($idx_featured_listing_data == false) {
						add_settings_error('wp_listings_idx_listing_settings_group', 'idx_listing_empty', 'The API returned no data for property ' . $prop['listingID'] . '. This is usually caused by your WordPress domain not matching the approved domain in your IDX account.', 'error');
						delete_transient('wp_listings_listing_' . $prop['listingID']);

					} else {
				        $opts = array(
				        	'post_content' => $idx_featured_listing_data['remarksConcat'],
				        	'post_title' => $idx_featured_listing_data['address'],
				        	'post_status' => 'publish',
				        	'post_type' => 'listing'
			        	);
			        	$add_post = wp_insert_post($opts);
						if($add_post) {
							$idx_featured_listing_wp_options[$prop['listingID']] = array(
								'listingID' => $prop['listingID'],
								'post_id'   => $add_post,
								'status'    => 'publish'
								);
							self::wp_listings_idx_insert_post_meta($add_post, $idx_featured_listing_data);

						} elseif(in_array($prop['listingID'], $listings) && $idx_featured_listing_wp_options[$prop['listingID']]['status'] != 'publish') {
							self::wp_listings_idx_change_post_status($idx_featured_listing_wp_options[$prop['listingID']]['post_id'], 'publish');
							$idx_featured_listing_data[$prop['listingID']]['status'] = 'publish';

						} elseif(!in_array($prop['listingID'], $listings) && $idx_featured_listing_wp_options[$prop['listingID']]['status'] == 'publish') {
							self::wp_listings_idx_change_post_status($idx_featured_listing_wp_options[$prop['listingID']]['post_id'], 'draft');
							$idx_featured_listing_data[$prop['listingID']]['status'] = 'draft';

						}
					}

				}
			}
			update_option('wp_listings_idx_featured_listing_wp_options', $idx_featured_listing_wp_options);
			return $idx_featured_listing_options;

		} else {
			require_once(ABSPATH . 'wp-content/plugins/idx-broker-platinum/idx/idx-api.php');
			$_idx_api = new \IDX\Idx_Api;
			$properties = $_idx_api->client_properties('featured');
			$idx_featured_listing_wp_options = get_option('wp_listings_idx_featured_listing_wp_options');

			$mls = $_idx_api->client_properties('featured');

			//foreach($idx_featured_listing_wp_options as $i => $item) {
			foreach($mls as $prop) {

				$key = self::get_key($mls, 'listingID', $prop['listingID']);

				if(!in_array($prop['listingID'], $listings)) {
					$idx_featured_listing_wp_options[$prop['listingID']]['listingID'] = $prop['listingID'];
				}

				// if(isset($item['post_id']) && !get_post($item['post_id'])) {
				// 	unset($idx_featured_listing_wp_options[$key]['post_id']);
				// 	unset($idx_featured_listing_wp_options[$key]['status']);
				// }
				
				if(in_array($prop['listingID'], $listings) && !isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id'])) {
					$opts = array(
						'post_content' => $mls[$key]['remarksConcat'],
						'post_title' => $mls[$key]['address'],
						'post_status' => 'publish',
						'post_type' => 'listing'
					);
					$add_post = wp_insert_post($opts);
					if($add_post) {
						$idx_featured_listing_wp_options[$prop['listingID']]['post_id'] = $add_post;
						$idx_featured_listing_wp_options[$prop['listingID']]['status'] = 'publish';
						self::wp_listings_idx_insert_post_meta($add_post, $mls[$key]);
					}
				}
				// } elseif(in_array($item['listingID'], $listings) && $idx_featured_listing_wp_options[$key]['status'] != 'publish') {
				// 	self::wp_listings_idx_change_post_status($idx_featured_listing_wp_options[$key]['post_id'], 'publish');
				// 	$idx_featured_listing_wp_options[$key]['status'] = 'publish';
				// } elseif(!in_array($item['listingID'], $listings) && $idx_featured_listing_wp_options[$key]['status'] == 'publish') {
				// 	self::wp_listings_idx_change_post_status($idx_featured_listing_wp_options[$key]['post_id'], 'draft');
				// 	$idx_featured_listing_wp_options[$key]['status'] = 'draft';
				// }
			}
			update_option('wp_listings_idx_featured_listing_wp_options', $idx_featured_listing_wp_options);
			return $idx_featured_listing_wp_options;
		}

	}

	public static function wp_listings_idx_change_post_status($post_id, $status){
	    $current_post = get_post( $post_id, 'ARRAY_A' );
	    $current_post['post_status'] = $status;
	    wp_update_post($current_post);
	}

	/**
	 * [wp_listings_idx_insert_post_meta description]
	 * @param  [type] $id  [description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public static function wp_listings_idx_insert_post_meta($id, $idx_featured_listing_data) {

		$imgs = '';
		
		if (class_exists( 'Equity_Idx_Listing' )) {
			foreach ($idx_featured_listing_data['images'] as $image_data => $img) {
				if($image_data == "totalCount") continue;
				$imgs .= sprintf('<img src="%s" alt="%s"/>', $img['url'], $idx_featured_listing_data['address']);
			}
		} else {
			foreach ($idx_featured_listing_data['image'] as $image_data => $img) {
				if($image_data == "totalCount") continue;
				$imgs .= sprintf('<img src="%s" alt="%s"/>', $img['url'], $idx_featured_listing_data['address']);
			}
		}
		
		if ($idx_featured_listing_data['propStatus'] == 'A'){
			$propstatus = 'Active';
		} elseif($idx_featured_listing_data['propStatus'] == 'S') {
			$propstatus = 'Sold';
		} else{
			$propstatus = $idx_featured_listing_data['propStatus'];
		}

		// Add taxonomies
		wp_set_object_terms($id, $idx_featured_listing_data['idxPropType'], 'property-types');
		wp_set_object_terms($id, $idx_featured_listing_data['cityName'], 'locations');
		wp_set_object_terms($id, $propstatus, 'status');

		// Add post meta for existing fields
		update_post_meta($id, '_listing_lot_sqft', $idx_featured_listing_data['acres'].' acres');
		update_post_meta($id, '_listing_price', $idx_featured_listing_data['listingPrice']);
		update_post_meta($id, '_listing_address', $idx_featured_listing_data['address']);
		update_post_meta($id, '_listing_city', $idx_featured_listing_data['cityName']);
		update_post_meta($id, '_listing_state', $idx_featured_listing_data['state']);
		update_post_meta($id, '_listing_zip', $idx_featured_listing_data['zipcode']);
		update_post_meta($id, '_listing_mls', $idx_featured_listing_data['listingID']);
		update_post_meta($id, '_listing_sqft', $idx_featured_listing_data['sqFt']);
		update_post_meta($id, '_listing_year_built', (isset($idx_featured_listing_data['yearBuilt'])) ? $idx_featured_listing_data['yearBuilt'] : '');
		update_post_meta($id, '_listing_bedrooms', $idx_featured_listing_data['bedrooms']);
		update_post_meta($id, '_listing_bathrooms', $idx_featured_listing_data['totalBaths']);
		update_post_meta($id, '_listing_half_bath', $idx_featured_listing_data['partialBaths']);
		update_post_meta($id, '_listing_gallery', apply_filters('wp_listings_idx_listing_gallery', $imgs));

		// Add post meta for all fields
		foreach ($idx_featured_listing_data as $metakey => $metavalue) {	
			if(isset($metavalue) && !is_array($metavalue) && $metavalue != '') {
				update_post_meta($id, '_listing_' . strtolower($metakey), $metavalue);
			} elseif(isset( $metavalue ) && is_array( $metavalue )) {
				foreach ($metavalue as $key => $value) {
					// if(get_post_meta($id, '_listing_' . strtolower($metakey)) && $metakey != 'images') {
					// 	$oldvalue = get_post_meta($id, '_listing_' . strtolower($metakey), true);
					// 	$newvalue = $value . ', ' . $oldvalue;
					// 	update_post_meta($id, '_listing_' . strtolower($metakey), $newvalue);
					// } elseif($metakey != 'images') {
					// 	update_post_meta($id, '_listing_' . strtolower($metakey), $value);
					// }
				}
			}
		}

		/* Pull Featured Image
		---------------------------------------*/
		// Add Featured Image to Post
		$featured_image = (class_exists( 'Equity_Idx_Api' )) ? $idx_featured_listing_data['images']['1']['url'] : $idx_featured_listing_data['image']['0']['url']; // Define the image URL here
		$image_url  = $featured_image; // Define the image URL here
		$upload_dir = wp_upload_dir(); // Set upload folder
		$image_data = file_get_contents($image_url); // Get image data
		$filename   = basename($image_url.'/' . $idx_featured_listing_data['listingID'] . '.jpg'); // Create image file name

		// Check folder permission and define file location
		if( wp_mkdir_p( $upload_dir['path'] ) ) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}

		// Create the image file on the server
		if(!file_exists($file))
			file_put_contents( $file, $image_data );

		// Check image file type
		$wp_filetype = wp_check_filetype( $filename, null );

		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => $idx_featured_listing_data['listingID'],
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Create the attachment
		$attach_id = wp_insert_attachment( $attachment, $file, $id );

		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		// Assign metadata to attachment
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// And finally assign featured image to post
		set_post_thumbnail( $id, $attach_id );

	}

}


/**
 * Admin settings page
 * Outputs featured listings to import
 * Enqueues scripts to load
 * Deletes post and post thumbnail
 *
 * @since  1.5
 */
add_action( 'admin_menu', 'wp_listings_idx_listing_register_menu_page');
function wp_listings_idx_listing_register_menu_page() {
	add_submenu_page( 'edit.php?post_type=listing', __( 'Import IDX Listings', 'wp_listings' ), __( 'Import IDX Listings', 'wp_listings' ), 'manage_options', 'wplistings-idx-listing', 'wp_listings_idx_listing_setting_page' );
	add_action( 'admin_init', 'wp_listings_idx_listing_register_settings' );
}

function wp_listings_idx_listing_register_settings() {
	register_setting('wp_listings_idx_listing_settings_group', 'wp_listings_idx_featured_listing_options', array('WPL_Idx_Listing', 'wp_listings_idx_create_post'));
}

add_action( 'admin_enqueue_scripts', 'wp_listings_idx_listing_scripts' );
function wp_listings_idx_listing_scripts() {
	wp_enqueue_script( 'wp_listings_idx_listing_delete_script', dirname( __FILE__ ) . '/js/idx-listing-import.js', array( 'jquery' ), true );
	wp_enqueue_script( 'jquery-masonry' );
	wp_localize_script( 'wp_listings_idx_listing_delete_script', 'DeleteListingAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	wp_enqueue_style( 'wp_listings_idx_listing_style', dirname( __FILE__ ) . '/css/admin-listing.css' );
}
add_action( 'wp_ajax_wp_listings_idx_listing_delete', 'wp_listings_idx_listing_delete' );
function wp_listings_idx_listing_delete(){

	$permission = check_ajax_referer( 'wp_listings_idx_listing_delete_nonce', 'nonce', false );
	if( $permission == false ) {
		echo 'error';
	}
	else {
		// Delete featured image
		$post_featured_image_id = get_post_thumbnail_id( $_REQUEST['id'] );
		wp_delete_attachment( $post_featured_image_id );

		//Delete post
		wp_delete_post( $_REQUEST['id'] );
		echo 'success';
	}
	die();
}

function wp_listings_idx_listing_setting_page() {

	// Get properties from IDX Broker plugin
	if (class_exists( 'IDX_Broker_Plugin' )) {
		require_once(ABSPATH . 'wp-content/plugins/idx-broker-platinum/idx/idx-api.php');
		$_idx_api = new \IDX\Idx_Api;
		$properties = $_idx_api->client_properties('featured');
	}

	$idx_featured_listing_wp_options = get_option('wp_listings_idx_featured_listing_options');

	settings_errors('wp_listings_idx_listing_settings_group');

	?>
			<h1>Import IDX Listings</h1>
			<p>Select the listings to import.</p>
			<form id="wplistings-idx-listing-import" method="post" action="options.php">
				<label for="selectall"><input type="checkbox" id="selectall"/>Select/Deselect All<br/><em>If importing all listings, it may take some time. <strong class="error">Please be patient.</strong></em></label>
				<?php submit_button('Import Listings'); ?>
				<ol id="selectable" class="grid">
			<div class="grid-sizer"></div>
			<?php
			
			// Show popup if IDX Broker plugin not active or installed
			if( !class_exists( 'IDX_Broker_Plugin') ) {
				// lightbox
				return;
			}
			settings_fields( 'wp_listings_idx_listing_settings_group' );
			do_settings_sections( 'wp_listings_idx_listing_settings_group' );

			if(!$properties) {
				echo 'No featured properties found.';
				return;
			}

			foreach ($properties as $prop) {

				//$key = WPL_Idx_Listing::get_key($properties, 'listingID', $prop['listingID']);

				if(!isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) || !get_post($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) ) {
					$idx_featured_listing_wp_options[$prop['listingID']] = array(
						'listingID' => $prop['listingID'],
						// 'post_id' => '',
						// 'status' => 'unpublished'
						);
				}

				if(isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) && get_post($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) ) {
					$pid = $idx_featured_listing_wp_options[$prop['listingID']]['post_id'];
					$nonce = wp_create_nonce('wp_listings_idx_listing_delete_nonce');
					$delete_listing = sprintf('<a href="%s" data-id="%s" data-nonce="%s" class="delete-post">Delete</a>',
						admin_url( 'admin-ajax.php?action=wp_listings_idx_listing_delete&id=' . $pid . '&nonce=' . $nonce),
							$pid,
							$nonce
					 );
				}
				
				printf('<div class="grid-item post"><label for="%s" class="idx-listing"><li class="%s"><img class="listing" src="%s"><input type="checkbox" id="%s" class="checkbox" name="wp_listings_idx_featured_listing_options[]" value="%s" %s />%s<p>%s<br/>%s</p>%s</li></label></div>',
					$prop['listingID'],
					isset($idx_featured_listing_wp_options[$prop['listingID']]['status']) ? ($idx_featured_listing_wp_options[$prop['listingID']]['status'] == 'publish' ? "imported" : '') : '',
					isset($prop['image']['0']['url']) ? $prop['image']['0']['url'] : '//mlsphotos.idxbroker.com/defaultNoPhoto/noPhotoFull.png',
					$prop['listingID'],
					$prop['listingID'],
					isset($idx_featured_listing_wp_options[$prop['listingID']]['status']) ? ($idx_featured_listing_wp_options[$prop['listingID']]['status'] == 'publish' ? "checked" : '') : '',
					isset($idx_featured_listing_wp_options[$prop['listingID']]['status']) ? ($idx_featured_listing_wp_options[$prop['listingID']]['status'] == 'publish' ? "<span class='imported'><i class='dashicons dashicons-yes'></i>Imported</span>" : '') : '',
					$prop['listingPrice'],
					$prop['address'],
					isset($idx_featured_listing_wp_options[$prop['listingID']]['status']) ? ($idx_featured_listing_wp_options[$prop['listingID']]['status'] == 'publish' ? $delete_listing : '') : ''
					);

				//update_option('wp_listings_idx_featured_listing_wp_options', $idx_featured_listing_wp_options );
			}
			echo '</ol>';
			submit_button('Import Listings');
			//update_option('wp_listings_idx_featured_listing_wp_options', $idx_featured_listing_wp_options);
			?>
			</form>
	<?php
}