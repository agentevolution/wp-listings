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

	var $settings_field = 'wp_listings_taxonomies';
	var $menu_page = 'register-taxonomies';

	/**
	 * Property details array.
	 */
	var $property_details;

	/**
	 * Construct Method.
	 */
	function __construct() {

		$this->property_details = apply_filters( 'wp_listings_property_details', array(
			'col1' => array(
			    __( 'Price:', 'wp_listings' ) 					=> '_listing_price',
			    __( 'Address:', 'wp_listings' )					=> '_listing_address',
			    __( 'City:', 'wp_listings' )					=> '_listing_city',
			    __( 'State:', 'wp_listings' )					=> '_listing_state',
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
			    __( 'Pool:', 'wp_listings' )					=> '_listing_pool'
			),
		) );

		add_action( 'init', array( $this, 'create_post_type' ) );

		add_filter( 'manage_edit-listing_columns', array( $this, 'columns_filter' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'columns_data' ) );

		add_action( 'admin_menu', array( $this, 'register_meta_boxes' ), 5 );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_menu', array( &$this, 'settings_init' ), 15 );
	}

	/**
	 * Registers the option to load the stylesheet
	 */
	function register_settings() {
		register_setting( 'wp_listings_options', 'wp_listings_stylesheet_load' );
		register_setting( 'wp_listings_options', 'wp_listings_widgets_stylesheet_load' );
		register_setting( 'wp_listings_options', 'wp_listings_default_state' );
		register_setting( 'wp_listings_options', 'wp_listings_archive_posts_num' );
		register_setting( 'wp_listings_options', 'wp_listings_slug' );
	}

	/**
	 * Sets default slug in options
	 */
	// function add_options() {

	// 	$new_options = array(
	// 		'wp_listings_slug' => 'listings'
	// 	);

	// 	if ( empty(get_option('wp_listings_slug')) )  {
	// 		add_option( 'wp_listings_options', $new_options );
	// 	}
	// }

	/**
	 * Adds settings page in admin menu
	 */
	function settings_init() {
		add_submenu_page( 'edit.php?post_type=listing', __( 'Settings', 'wp_listings' ), __( 'Settings', 'wp_listings' ), 'manage_options', 'wp-listings-settings', array( &$this, 'settings_page' ) );
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
					'not_found_in_trash'	=> __( 'No listings found in Trash', 'wp_listings' )
				),
				'public'		=> true,
				'query_var'		=> true,
				'menu_position'	=> 5,
				'menu_icon'		=> 'dashicons-admin-home',
				'has_archive'	=> true,
				'supports'		=> array( 'title', 'editor', 'author', 'comments', 'excerpt', 'thumbnail', 'revisions', 'genesis-seo', 'genesis-layouts', 'genesis-simple-sidebars', 'genesis-cpt-archives-settings'),
				'rewrite'		=> array( 'slug' => 'listings', 'feeds' => true ),
			)
		);

		register_post_type( 'listing', $args );

	}

	function register_meta_boxes() {

		add_meta_box( 'listing_details_metabox', __( 'Property Details', 'wp_listings' ), array( &$this, 'listing_details_metabox' ), 'listing', 'normal', 'high' );
		add_meta_box( 'listing_features_metabox', __( 'Additional Details', 'wp_listings' ), array( &$this, 'listing_features_metabox' ), 'listing', 'normal', 'high' );
		add_meta_box( 'agentevo_metabox', __( 'Agent Evolution', 'wp_listings' ), array( &$this, 'agentevo_metabox' ), 'wp-listings-options', 'side', 'core' );

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

		switch( $column ) {
			case "listing_thumbnail":
				printf( '<p>%s</p>', the_post_thumbnail( 'thumbnail' ) );
				break;
			case "listing_details":
				foreach ( (array) $this->property_details['col1'] as $label => $key ) {
					printf( '<b>%s</b> %s<br />', esc_html( $label ), esc_html( get_post_meta($post->ID, $key, true) ) );
				}
				foreach ( (array) $this->property_details['col2'] as $label => $key ) {
					printf( '<b>%s</b> %s<br />', esc_html( $label ), esc_html( get_post_meta($post->ID, $key, true) ) );
				}
				break;
			case "listing_tags":
				echo '<b>Status</b>: ' . get_the_term_list( $post->ID, 'status', '', ', ', '' ) . '<br /><br />';
				echo '<b>Property Type:</b> ' . get_the_term_list( $post->ID, 'property-types', '', ', ', '' ) . '<br /><br />';
				echo '<b>Location:</b> ' . get_the_term_list( $post->ID, 'locations', '', ', ', '' ) . '<br /><br />';
				echo '<b>Features</b><br />' . get_the_term_list( $post->ID, 'features', '', ', ', '' );
				break;
		}

	}

}