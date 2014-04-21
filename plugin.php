<?php
/*
	Plugin Name: WP Listings
	Plugin URI: http://agentevolution.com
	Description: WP Listings is a WordPress listings plugin that adds a directory of real estate listings that is searchable and can be displayed through widgets.
	Author: agentevolution
	Author URI: http://agentevolution.com

	Version: 0.1.0

	License: GNU General Public License v2.0 (or later)
	License URI: http://www.opensource.org/licenses/gpl-license.php
*/

register_activation_hook( __FILE__, 'wp_listings_activation' );
/**
 * This function runs on plugin activation. It flushes the rewrite rules to prevent 404's
 *
 * @since 0.1.0
 */
function wp_listings_activation() {

		/** Flush rewrite rules */
		if ( ! post_type_exists( 'listing' ) ) {
			wp_listings_init();
			global $_wp_listings, $_wp_listings_taxonomies;
			$_wp_listings->create_post_type();
			$_wp_listings_taxonomies->register_taxonomies();
		}
		flush_rewrite_rules();

}

add_action( 'after_setup_theme', 'wp_listings_init' );
/**
 * Initialize WP Listings.
 *
 * Include the libraries, define global variables, instantiate the classes.
 *
 * @since 0.1.0
 */
function wp_listings_init() {

	global $_wp_listings, $_wp_listings_taxonomies;

	define( 'WP_LISTINGS_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP_LISTINGS_VERSION', '0.1.0' );

	/** Load textdomain for translation */
	load_plugin_textdomain( 'wp_listings', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	/** Includes */
	require_once( dirname( __FILE__ ) . '/includes/helpers.php' );
	require_once( dirname( __FILE__ ) . '/includes/functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-listings.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-taxonomies.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-listings-search-widget.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-featured-listings-widget.php' );

	/** Enqueues scripts for single listings */
	add_action('wp_enqueue_scripts', 'add_wp_listings_scripts');
	function add_wp_listings_scripts() {
		wp_register_script( 'wp-listings-tabs', WP_LISTINGS_URL . 'includes/js/listing-tabs.js' );
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-tabs', array('jquery') );
		wp_enqueue_script( 'wp-listings-tabs', array('jquery, jquery-ui-tabs'), true );
    }

	/** Enqueues style file if it exists */
	add_action('wp_enqueue_scripts', 'add_wp_listings_styles');
	function add_wp_listings_styles() {

		if ('1' == get_option('wp_listings_stylesheet_load')) {
			return;
		}

        if ( file_exists(dirname( __FILE__ ) . '/wp-listings.css') ) {
            wp_register_style('wp_listings', WP_LISTINGS_URL . '/wp-listings.css');
            wp_enqueue_style('wp_listings');
        }
    }

	/** Instantiate */
	$_wp_listings = new WP_Listings;
	$_wp_listings_taxonomies = new WP_Listings_Taxonomies;

	add_action( 'widgets_init', 'wp_listings_register_widgets' );

}

/**
 * Register Widgets that will be used in the WP Listings plugin
 *
 * @since 0.1.0
 */
function wp_listings_register_widgets() {

	$widgets = array( 'WP_Listings_Featured_Listings_Widget', 'WP_Listings_Search_Widget' );

	foreach ( (array) $widgets as $widget ) {
		register_widget( $widget );
	}

}