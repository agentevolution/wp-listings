<?php
/**
 * This file contains the WP_Listings class.
 */

/**
 * This class handles the creation of the "Listings" post type, and creates a
 * UI to display the Listing-specific data on the admin screens.
 *
 */
class WP_Listings {

	var $settings_page = 'wp-listings-settings';
	var $settings_field = 'wp_listings_taxonomies';
	var $menu_page = 'register-taxonomies';

	var $options;

	/**
	 * Property details array.
	 */
	var $property_details;

	/**
	 * Construct Method.
	 */
	function __construct() {

		$this->options = get_option('plugin_wp_listings_settings');

		$this->property_details = apply_filters( 'wp_listings_property_details', array(
			'col1' => array(
			    __( 'Price:', 'wp_listings' ) 					=> '_listing_price',
			    __( 'Address:', 'wp_listings' )					=> '_listing_address',
			    __( 'City:', 'wp_listings' )					=> '_listing_city',
			    __( 'State:', 'wp_listings' )					=> '_listing_state',
			    __( 'Country:', 'wp_listings' )					=> '_listing_country',
			    __( 'ZIP:', 'wp_listings' )						=> '_listing_zip',
			    __( 'MLS #:', 'wp_listings' ) 					=> '_listing_mls',
				__( 'Open House Time & Date:', 'wp_listings' ) 	=> '_listing_open_house'
			),
			'col2' => array(
			    __( 'Year Built:', 'wp_listings' ) 				=> '_listing_year_built',
			    __( 'Floors:', 'wp_listings' ) 					=> '_listing_floors',
			    __( 'Square Feet:', 'wp_listings' )				=> '_listing_sqft',
				__( 'Lot Square Feet:', 'wp_listings' )			=> '_listing_lot_sqft',
			    __( 'Bedrooms:', 'wp_listings' )				=> '_listing_bedrooms',
			    __( 'Bathrooms:', 'wp_listings' )				=> '_listing_bathrooms',
			    __( 'Half Bathrooms:', 'wp_listings' )			=> '_listing_half_bath',
			    __( 'Garage:', 'wp_listings' )					=> '_listing_garage',
			    __( 'Pool:', 'wp_listings' )					=> '_listing_pool'
			),
		) );

		$this->extended_property_details = apply_filters( 'wp_listings_extended_property_details', array(
			'col1' => array(
			    __( 'Property Type:', 'wp_listings' ) 			=> '_listing_proptype',
			    __( 'Condo:', 'wp_listings' )					=> '_listing_condo',
			    __( 'Financial:', 'wp_listings' )				=> '_listing_financial',
			    __( 'Condition:', 'wp_listings' )				=> '_listing_condition',
			    __( 'Construction:', 'wp_listings' )			=> '_listing_construction',
			    __( 'Exterior:', 'wp_listings' )				=> '_listing_exterior',
			    __( 'Fencing:', 'wp_listings' ) 				=> '_listing_fencing',
				__( 'Interior:', 'wp_listings' ) 				=> '_listing_interior',
				__( 'Flooring:', 'wp_listings' ) 				=> '_listing_flooring',
				__( 'Heat/Cool:', 'wp_listings' ) 				=> '_listing_heatcool'
			),
			'col2' => array(
				__( 'Lot size:', 'wp_listings' ) 				=> '_listing_lostize',
				__( 'Location:', 'wp_listings' ) 				=> '_listing_location',
				__( 'Scenery:', 'wp_listings' )					=> '_listing_scenery',
				__( 'Community:', 'wp_listings' )				=> '_listing_community',
				__( 'Recreation:', 'wp_listings' )				=> '_listing_recreation',
				__( 'General:', 'wp_listings' )					=> '_listing_general',
				__( 'Inclusions:', 'wp_listings' )				=> '_listing_inclusions',
				__( 'Parking:', 'wp_listings' )					=> '_listing_parking',
				__( 'Rooms:', 'wp_listings' )					=> '_listing_rooms',
				__( 'Laundry:', 'wp_listings' )					=> '_listing_laundry',
				__( 'Utilities:', 'wp_listings' )				=> '_listing_utilities'
			),
		) );

		add_action( 'init', array( $this, 'create_post_type' ) );

		add_filter( 'manage_edit-listing_columns', array( $this, 'columns_filter' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );

		add_action( 'admin_menu', array( $this, 'register_meta_boxes' ), 5 );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_init', array( &$this, 'add_options' ) );
		add_action( 'admin_menu', array( &$this, 'settings_init' ), 15 );

	}

	/**
	 * Registers the option to load the stylesheet
	 */
	function register_settings() {
		register_setting( 'wp_listings_options', 'plugin_wp_listings_settings' );
	}

	/**
	 * Sets default slug in options
	 */
	function add_options() {

		$new_options = array(
			'wp_listings_archive_posts_num' => 9,
			'wp_listings_slug' => 'listings'
		);

		if ( empty($this->options['wp_listings_slug']) && empty($this->options['wp_listings_archive_posts_num']) )  {
			add_option( 'plugin_wp_listings_settings', $new_options );
		}
	}

	/**
	 * Adds settings page and IDX Import page to admin menu
	 */
	function settings_init() {
		add_submenu_page( 'edit.php?post_type=listing', __( 'Settings', 'wp_listings' ), __( 'Settings', 'wp_listings' ), 'manage_options', $this->settings_page, array( &$this, 'settings_page' ) );

		add_submenu_page( 'edit.php?post_type=listing', __( 'Import IDX Listings', 'wp_listings' ), __( 'Import IDX Listings', 'wp_listings' ), 'manage_options', 'wplistings-idx-listing', array( &$this, 'wplistings_idx_listing_setting_page') );
	}

	/**
	 * Creates display of settings page along with form fields
	 */
	function settings_page() {
		include( dirname( __FILE__ ) . '/views/wp-listings-settings.php' );
	}

	/**
	 * Creates our "Listing" post type.
	 */
	function create_post_type() {

		$args = apply_filters( 'wp_listings_post_type_args',
			array(
				'labels' => array(
					'name'					=> __( 'Listings', 'wp_listings' ),
					'singular_name'			=> __( 'Listing', 'wp_listings' ),
					'add_new'				=> __( 'Add New', 'wp_listings' ),
					'add_new_item'			=> __( 'Add New Listing', 'wp_listings' ),
					'edit'					=> __( 'Edit', 'wp_listings' ),
					'edit_item'				=> __( 'Edit Listing', 'wp_listings' ),
					'new_item'				=> __( 'New Listing', 'wp_listings' ),
					'view'					=> __( 'View Listing', 'wp_listings' ),
					'view_item'				=> __( 'View Listing', 'wp_listings' ),
					'search_items'			=> __( 'Search Listings', 'wp_listings' ),
					'not_found'				=> __( 'No listings found', 'wp_listings' ),
					'not_found_in_trash'	=> __( 'No listings found in Trash', 'wp_listings' ),
					'filter_items_list'     => __( 'Filter Listings', 'wp_listings' ),
					'items_list_navigation' => __( 'Listings navigation', 'wp_listings' ),
					'items_list'            => __( 'Listings list', 'wp_listings' )
				),
				'public'		=> true,
				'query_var'		=> true,
				'show_in_rest'  => true,
				'rest_base'     => 'listing',
				'rest_controller_class' => 'WP_REST_Posts_Controller',
				'menu_position'	=> 5,
				'menu_icon'		=> 'dashicons-admin-home',
				'has_archive'	=> true,
				'supports'		=> array( 'title', 'editor', 'author', 'comments', 'excerpt', 'thumbnail', 'revisions', 'equity-layouts', 'equity-cpt-archives-settings', 'genesis-seo', 'genesis-layouts', 'genesis-simple-sidebars', 'genesis-cpt-archives-settings', 'publicize', 'wpcom-markdown'),
				'rewrite'		=> array( 'slug' => $this->options['wp_listings_slug'], 'feeds' => true, 'with_front' => false ),
			)
		);

		register_post_type( 'listing', $args );

	}

	function register_meta_boxes() {
		add_meta_box( 'listing_details_metabox', __( 'Property Details', 'wp_listings' ), array( &$this, 'listing_details_metabox' ), 'listing', 'normal', 'high' );
		add_meta_box( 'listing_features_metabox', __( 'Additional Details', 'wp_listings' ), array( &$this, 'listing_features_metabox' ), 'listing', 'normal', 'high' );
		if ( !class_exists( 'Idx_Broker_Plugin' ) ) {
			add_meta_box( 'idx_metabox', __( 'IDX Broker', 'wp_listings' ), array( &$this, 'idx_metabox' ), 'wp-listings-options', 'side', 'core' );
		}
		if( !function_exists( 'equity' ) ) {
			add_meta_box( 'agentevo_metabox', __( 'Equity Framework', 'wp_listings' ), array( &$this, 'agentevo_metabox' ), 'wp-listings-options', 'side', 'core' );
		}
		
	}

	function listing_details_metabox() {
		include( dirname( __FILE__ ) . '/views/listing-details-metabox.php' );
	}

	function listing_features_metabox() {
		include( dirname( __FILE__ ) . '/views/listing-features-metabox.php' );
	}

	function agentevo_metabox() {
		include( dirname( __FILE__ ) . '/views/agentevo-metabox.php' );
	}

	function idx_metabox() {
		include( dirname( __FILE__ ) . '/views/idx-metabox.php' );
	}

	function metabox_save( $post_id, $post ) {

		/** Run only on listings post type save */
		if ( 'listing' != $post->post_type )
			return;

		if ( !isset( $_POST['wp_listings_metabox_nonce'] ) || !wp_verify_nonce( $_POST['wp_listings_metabox_nonce'], 'wp_listings_metabox_save' ) )
	        return $post_id;

	    /** Don't try to save the data under autosave, ajax, or future post */
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
	    if ( defined( 'DOING_CRON' ) && DOING_CRON ) return;

	    /** Check permissions */
	    if ( ! current_user_can( 'edit_post', $post_id ) )
	        return;

	    $property_details = $_POST['wp_listings'];

	    if ( ! isset( $property_details['_listing_hide_price'] ) )
				$property_details['_listing_hide_price'] = 0;

	    /** Store the property details custom fields */
	    foreach ( (array) $property_details as $key => $value ) {

	        /** Save/Update/Delete */
	        if ( $value ) {
	            update_post_meta($post->ID, $key, $value);
	        } else {
	            delete_post_meta($post->ID, $key);
	        }

	    }

	}

	/**
	 * Filter the columns in the "Listings" screen, define our own.
	 */
	function columns_filter ( $columns ) {

		$columns = array(
			'cb'					=> '<input type="checkbox" />',
			'listing_thumbnail'		=> __( 'Thumbnail', 'wp_listings' ),
			'title'					=> __( 'Listing Title', 'wp_listings' ),
			'listing_details'		=> __( 'Details', 'wp_listings' ),
			'listing_tags'			=> __( 'Tags', 'wp_listings' )
		);

		return $columns;

	}

	/**
	 * Filter the data that shows up in the columns in the "Listings" screen, define our own.
	 */
	function columns_data( $column ) {

		global $post, $wp_taxonomies;

		$image_size = 'style="max-width: 115px;"';

		apply_filters( 'wp_listings_admin_listing_details', $admin_details = $this->property_details['col1']);

		if (isset($_GET["mode"]) && trim($_GET["mode"]) == 'excerpt' ) {
			apply_filters( 'wp_listings_admin_extended_details', $admin_details = $this->property_details['col1'] + $this->property_details['col2']);
			$image_size = 'style="max-width: 150px;"';
		}

		$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');

		switch( $column ) {
			case "listing_thumbnail":
				echo '<p><img src="' . $image[0] . '" alt="listing-thumbnail" ' . $image_size . '/></p>';
				break;
			case "listing_details":
				foreach ( (array) $admin_details as $label => $key ) {
					printf( '<b>%s</b> %s<br />', esc_html( $label ), esc_html( get_post_meta($post->ID, $key, true) ) );
				}
				break;
			case "listing_tags":
				_e('<b>Status</b>: ' . get_the_term_list( $post->ID, 'status', '', ', ', '' ) . '<br />', 'wp_listings');
				_e('<b>Property Type:</b> ' . get_the_term_list( $post->ID, 'property-types', '', ', ', '' ) . '<br />', 'wp_listings');
				_e('<b>Location:</b> ' . get_the_term_list( $post->ID, 'locations', '', ', ', '' ) . '<br />', 'wp_listings');
				_e('<b>Features:</b> ' . get_the_term_list( $post->ID, 'features', '', ', ', '' ), 'wp_listings');
				break;
		}

	}

	/**
	 * Adds query var on saving post to show notice
	 * @param  [type] $post_id [description]
	 * @param  [type] $post    [description]
	 * @param  [type] $update  [description]
	 * @return [type]          [description]
	 */
	function save_post( $post_id, $post, $update ) {
		add_filter( 'redirect_post_location', array( &$this, 'add_notice_query_var' ), 99 );
	}

	function add_notice_query_var( $location ) {
		remove_filter( 'redirect_post_location', array( &$this, 'add_notice_query_var' ), 99 );
		return add_query_arg( array( 'wp-listings' => 'show-notice' ), $location );
	}

	/**
	 * Displays admin notices if show-notice url param exists or edit listing page
	 * @return object current screen
	 * @uses  wp_listings_admin_notice
	 */
	function admin_notices() {

		$screen = get_current_screen();

		if ( isset( $_GET['wp-listings']) || $screen->id == 'edit-listing' ) {
			if ( !class_exists( 'Idx_Broker_Plugin') ) {
				echo wp_listings_admin_notice( __( '<strong>Integrate your MLS Listings into WordPress with IDX Broker!</strong> <a href="http://www.idxbroker.com/features/idx-wordpress-plugin">Find out how</a>', 'wp_listings' ), false, 'activate_plugins', (isset( $_GET['wp-listings'])) ? 'wpl_listing_notice_idx' : 'wpl_notice_idx' );
			} else {
			if( !function_exists( 'equity' ) ) {
				echo wp_listings_admin_notice( __( '<strong>Stop filling out forms. Equity automatically enhances your listings with extra details and photos.</strong> <a href="http://www.agentevolution.com/equity/">Find out how</a>', 'wp_listings' ), false, 'activate_plugins', (isset( $_GET['wp-listings'])) ? 'wpl_listing_notice_equity' : 'wpl_notice_equity' );
			}
		}

		return $screen;
	}

	/**
	 * Displays Import IDX listing submenu page
	 * @return [type] [description]
	 */
	function wplistings_idx_listing_setting_page() {
		if(!class_exists( 'IDXB' )) { ?>

			<h1>Import IDX Listings</h1>
				<p>Select the listings to import.</p>
				<form id="equity-idx-listing-import" method="post" action="options.php">
					<label for="selectall"><input type="checkbox" id="selectall"/>Select/Deselect All<br/><em>If importing all listings, it may take some time. <strong class="error">Please be patient.</strong></em></label>
					<?php submit_button('Import Listings'); ?>
					<ol id="selectable" class="grid">
				<div class="grid-sizer"></div>
		<?php

		} else {

			$_idx = new Equity_Idx_Api;
			$_listing = new Equity_Idx_Listing;

			$properties = $_idx->client_properties('featured');

			$idx_featured_listing_wp_options = get_option('equity_idx_featured_listing_wp_options');

			if( !is_plugin_active( 'wp-listings/plugin.php' ) ) {
				echo "<p>To import IDX listings, the <a href='http://wordpress.org/plugins/wp-listings/'>WP Listings</a> plugin must be installed and active.</p>";
				return;
			}

			settings_errors('equity_idx_listing_settings_group');

			?>
			
					<h1>Import IDX Listings</h1>
					<p>Select the listings to import.</p>
					<form id="equity-idx-listing-import" method="post" action="options.php">
						<label for="selectall"><input type="checkbox" id="selectall"/>Select/Deselect All<br/><em>If importing all listings, it may take some time. <strong class="error">Please be patient.</strong></em></label>
						<?php submit_button('Import Listings'); ?>
						<ol id="selectable" class="grid">
					<div class="grid-sizer"></div>
					<?php
					
					settings_fields( 'equity_idx_listing_settings_group' );
					do_settings_sections( 'equity_idx_listing_settings_group' );

					if(!$properties) {
						echo 'No featured properties found.';
						return;
					}

					foreach ($properties as $prop) {

						if(!isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) || !get_post($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) ) {
							$idx_featured_listing_wp_options[$prop['listingID']] = array(
								'listingID' => $prop['listingID']
								);
						}

						if(isset($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) && get_post($idx_featured_listing_wp_options[$prop['listingID']]['post_id']) ) {
							$pid = $idx_featured_listing_wp_options[$prop['listingID']]['post_id'];
							$nonce = wp_create_nonce('equity_idx_listing_delete_nonce');
							$delete_listing = sprintf('<a href="%s" data-id="%s" data-nonce="%s" class="delete-post">Delete</a>',
								admin_url( 'admin-ajax.php?action=equity_idx_listing_delete&id=' . $pid . '&nonce=' . $nonce),
									$pid,
									$nonce
							 );
						}
						
						printf('<div class="grid-item post"><label for="%s" class="idx-listing"><li class="%s"><img class="listing" src="%s"><input type="checkbox" id="%s" class="checkbox" name="equity_idx_featured_listing_options[]" value="%s" %s />%s<p>%s<br/>%s</p>%s</li></label></div>',
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
					}
					echo '</ol>';
					submit_button('Import Listings');
					update_option('equity_idx_featured_listing_wp_options', $idx_featured_listing_wp_options);
					?>
					</form>
			<?php
		}
	}

}