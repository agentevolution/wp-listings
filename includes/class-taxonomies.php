<?php
/**
 * This file contains the WP_Listings_Taxonomies class.
 */

/**
 * This class handles all the aspects of displaying, creating, and editing the
 * user-created taxonomies for the "Listings" post-type.
 *
 */
class WP_Listings_Taxonomies {

	var $settings_field = 'wp_listings_taxonomies';
	var $menu_page = 'register-taxonomies';

	/**
	 * Construct Method.
	 */
	function __construct() {

		add_action( 'admin_init', array( &$this, 'register_settings' ) );
		add_action( 'admin_menu', array( &$this, 'settings_init' ), 15 );
		add_action( 'admin_init', array( &$this, 'actions' ) );
		add_action( 'admin_notices', array( &$this, 'notices' ) );

		add_action( 'init', array( &$this, 'register_taxonomies' ), 15 );
		add_action( 'init', array( $this, 'create_terms' ), 16 );

	}

	function register_settings() {

		register_setting( $this->settings_field, $this->settings_field );
		add_option( $this->settings_field, __return_empty_array(), '', 'yes' );

	}

	function settings_init() {

		add_submenu_page( 'edit.php?post_type=listing', __( 'Register Taxonomies', 'wp_listings' ), __( 'Register Taxonomies', 'wp_listings' ), 'manage_options', $this->menu_page, array( &$this, 'admin' ) );

	}

	function actions() {

		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != $this->menu_page ) {
			return;
		}

		/** This section handles the data if a new taxonomy is created */
		if ( isset( $_REQUEST['action'] ) && 'create' == $_REQUEST['action'] ) {
			$this->create_taxonomy( $_POST['wp_listings_taxonomy'] );
		}

		/** This section handles the data if a taxonomy is deleted */
		if ( isset( $_REQUEST['action'] ) && 'delete' == $_REQUEST['action'] ) {
			$this->delete_taxonomy( $_REQUEST['id'] );
		}

		/** This section handles the data if a taxonomy is being edited */
		if ( isset( $_REQUEST['action'] ) && 'edit' == $_REQUEST['action'] ) {
			$this->edit_taxonomy( $_POST['wp_listings_taxonomy'] );
		}

	}

	function admin() {

		echo '<div class="wrap">';

			if ( isset( $_REQUEST['view'] ) && 'edit' == $_REQUEST['view'] ) {
				require( dirname( __FILE__ ) . '/views/edit-tax.php' );
			}
			else {
				require( dirname( __FILE__ ) . '/views/create-tax.php' );
			}

		echo '</div>';

	}

	function create_taxonomy( $args = array() ) {

		/**** VERIFY THE NONCE ****/

		/** No empty fields */
		if ( ! isset( $args['id'] ) || empty( $args['id'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );
		if ( ! isset( $args['name'] ) || empty( $args['name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );
		if ( ! isset( $args['singular_name'] ) || empty( $args['singular_name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );

		extract( $args );

		$labels = array(
			'name'					=> strip_tags( $name ),
			'singular_name' 		=> strip_tags( $singular_name ),
			'menu_name'				=> strip_tags( $name ),

			'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
			'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
			'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
			'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
			'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
		);

		$args = array(
			'labels'		=> $labels,
			'hierarchical'	=> true,
			'rewrite'		=> array( 'slug' => $id ),
			'editable'		=> 1
		);

		$tax = array( $id => $args );

		$options = get_option( $this->settings_field );

		/** Update the options */
		update_option( $this->settings_field, wp_parse_args( $tax, $options ) );

		/** Flush rewrite rules */
		$this->register_taxonomies();
		flush_rewrite_rules();

	}

	function delete_taxonomy( $id = '' ) {

		/**** VERIFY THE NONCE ****/

		/** No empty ID */
		if ( ! isset( $id ) || empty( $id ) )
			wp_die( __( "Nice try, partner. But that taxonomy doesn't exist. Click back and try again.", 'wp_listings' ) );

		$options = get_option( $this->settings_field );

		/** Look for the ID, delete if it exists */
		if ( array_key_exists( $id, (array) $options ) ) {
			unset( $options[$id] );
		} else {
			wp_die( __( "Nice try, partner. But that taxonomy doesn't exist. Click back and try again.", 'wp_listings' ) );
		}

		/** Update the DB */
		update_option( $this->settings_field, $options );

	}

	function edit_taxonomy( $args = array() ) {

		/**** VERIFY THE NONCE ****/

		/** No empty fields */
		if ( ! isset( $args['id'] ) || empty( $args['id'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );
		if ( ! isset( $args['name'] ) || empty( $args['name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );
		if ( ! isset( $args['singular_name'] ) || empty( $args['singular_name'] ) )
			wp_die( __( 'Please complete all required fields.', 'wp_listings' ) );

		extract( $args );

		$labels = array(
			'name'					=> strip_tags( $name ),
			'singular_name' 		=> strip_tags( $singular_name ),
			'menu_name'				=> strip_tags( $name ),

			'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
			'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
			'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
			'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
			'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
			'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
			'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
		);

		$args = array(
			'labels'		=> $labels,
			'hierarchical'	=> true,
			'rewrite'		=> array( 'slug' => $id ),
			'editable'		=> 1
		);

		$tax = array( $id => $args );

		$options = get_option( $this->settings_field );

		update_option( $this->settings_field, wp_parse_args( $tax, $options ) );

	}

	function notices() {

		if ( ! isset( $_REQUEST['page'] ) || $_REQUEST['page'] != $this->menu_page ) {
			return;
		}

		$format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';

		if ( isset( $_REQUEST['created'] ) && 'true' == $_REQUEST['created'] ) {
			printf( $format, __('New taxonomy successfully created!', 'wp_listings') );
			return;
		}

		if ( isset( $_REQUEST['edited'] ) && 'true' == $_REQUEST['edited'] ) {
			printf( $format, __('Taxonomy successfully edited!', 'wp_listings') );
			return;
		}

		if ( isset( $_REQUEST['deleted'] ) && 'true' == $_REQUEST['deleted'] ) {
			printf( $format, __('Taxonomy successfully deleted.', 'wp_listings') );
			return;
		}

		return;

	}

	/**
	 * Register the property features taxonomy, manually.
	 */
	function property_features_taxonomy() {

		$name = 'Features';
		$singular_name = 'Feature';

		return array(
			'features' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
				),
				'hierarchical' => 0,
				'rewrite' => array( 'features' ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the status taxonomy, manually.
	 */
	function listing_status_taxonomy() {

		$name = 'Status';
		$singular_name = 'Status';

		return array(
			'status' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
				),
				'hierarchical' => false,
				'rewrite' => array( 'status' ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the property-types taxonomy, manually.
	 */
	function property_type_taxonomy() {

		$name = 'Property Types';
		$singular_name = 'Property Type';

		return array(
			'property-types' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
				),
				'hierarchical' => false,
				'rewrite' => array( 'property-types' ),
				'editable' => 0
			)
		);

	}

	/**
	 * Register the location taxonomy, manually.
	 */
	function listing_location_taxonomy() {

		$name = 'Locations';
		$singular_name = 'Location';

		return array(
			'locations' => array(
				'labels' => array(
					'name'					=> strip_tags( $name ),
					'singular_name' 		=> strip_tags( $singular_name ),
					'menu_name'				=> strip_tags( $name ),

					'search_items'			=> sprintf( __( 'Search %s', 'wp_listings' ), strip_tags( $name ) ),
					'popular_items'			=> sprintf( __( 'Popular %s', 'wp_listings' ), strip_tags( $name ) ),
					'all_items'				=> sprintf( __( 'All %s', 'wp_listings' ), strip_tags( $name ) ),
					'edit_item'				=> sprintf( __( 'Edit %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'update_item'			=> sprintf( __( 'Update %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_new_item'			=> sprintf( __( 'Add New %s', 'wp_listings' ), strip_tags( $singular_name ) ),
					'new_item_name'			=> sprintf( __( 'New %s Name', 'wp_listings' ), strip_tags( $singular_name ) ),
					'add_or_remove_items'	=> sprintf( __( 'Add or Remove %s', 'wp_listings' ), strip_tags( $name ) ),
					'choose_from_most_used'	=> sprintf( __( 'Choose from the most used %s', 'wp_listings' ), strip_tags( $name ) )
				),
				'hierarchical' => false,
				'rewrite' => array( 'locations' ),
				'editable' => 0
			)
		);

	}

	/**
	 * Create the taxonomies.
	 */
	function register_taxonomies() {

		foreach( (array) $this->get_taxonomies() as $id => $data ) {
			register_taxonomy( $id, array( 'listing' ), $data );
		}

	}

	/**
	 * Get the taxonomies.
	 */
	function get_taxonomies() {

		return array_merge( $this->property_features_taxonomy(), $this->listing_status_taxonomy(), $this->listing_location_taxonomy(), $this->property_type_taxonomy(), (array) get_option( $this->settings_field ) );

	}

	/**
	 * Create the terms
	 */
	function create_terms() {

		/** Default terms for status */
		$status_terms = array('Active' => 'active', 'Pending' => 'pending', 'For Rent' => 'for-rent', 'Sold' => 'sold', 'Featured' => 'featured');
		foreach ($status_terms as $term => $slug) {
			if (term_exists($term, 'status')) {
				continue;
			}
			wp_insert_term($term,'status',array('slug' => $slug));
		}

		/** Default terms for property-type */
		$property_type_terms = array('Commercial' => 'commercial', 'Residential' => 'residential');
		foreach ($property_type_terms as $term => $slug) {
			if (term_exists($term, 'property-types')) {
				continue;
			}
			wp_insert_term($term,'property-types', array('slug' => $slug));
		}

	}

}