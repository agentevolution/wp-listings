<?php
/*
	Plugin Name: WP Listings
	Plugin URI: http://wordpress.org/plugins/wp-listings/
	Description: Creates a portable real estate listing management system. Designed to work with any theme using built-in templates.
	Author: Agent Evolution
	Author URI: http://agentevolution.com

	Version: 1.1.3

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
			global $_wp_listings, $_wp_listings_taxonomies, $_wp_listings_templates;
			$_wp_listings->create_post_type();
			$_wp_listings_taxonomies->register_taxonomies();
		}
		flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'wp_listings_deactivation' );
/**
 * This function runs on plugin deactivation. It flushes the rewrite rules to get rid of remnants
 *
 * @since 1.0.8
 */
function wp_listings_deactivation() {

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

	global $_wp_listings, $_wp_listings_taxonomies, $_wp_listings_templates;

	define( 'WP_LISTINGS_URL', plugin_dir_url( __FILE__ ) );
	define( 'WP_LISTINGS_VERSION', '1.1.2' );

	/** Load textdomain for translation */
	load_plugin_textdomain( 'wp_listings', false, basename( dirname( __FILE__ ) ) . '/languages/' );

	/** Includes */
	require_once( dirname( __FILE__ ) . '/includes/helpers.php' );
	require_once( dirname( __FILE__ ) . '/includes/functions.php' );
	require_once( dirname( __FILE__ ) . '/includes/shortcodes.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-listings.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-taxonomies.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-listing-template.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-listings-search-widget.php' );
	require_once( dirname( __FILE__ ) . '/includes/class-featured-listings-widget.php' );

	/** Add theme support for post thumbnails if it does not exist */
	if(!current_theme_supports('post-thumbnails')) {
		add_theme_support( 'post-thumbnails' );
	}

	/** Registers and enqueues scripts for single listings */
	add_action('wp_enqueue_scripts', 'add_wp_listings_scripts');
	function add_wp_listings_scripts() {
		wp_register_script( 'wp-listings-single', WP_LISTINGS_URL . 'includes/js/single-listing.min.js' ); // enqueued only on single listings
		wp_register_script( 'jquery-validate', WP_LISTINGS_URL . 'includes/js/jquery.validate.min.js' ); // enqueued only on single listings
		wp_register_script( 'fitvids', '//cdnjs.cloudflare.com/ajax/libs/fitvids/1.1.0/jquery.fitvids.js', array('jquery'), true, true ); // enqueued only on single listings
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-tabs', array('jquery') );
    }

	/** Enqueues wp-listings.css style file if it exists and is not deregistered in settings */
	add_action('wp_enqueue_scripts', 'add_wp_listings_main_styles');
	function add_wp_listings_main_styles() {

		$options = get_option('plugin_wp_listings_settings');

		/** Register single styles but don't enqueue them **/
		wp_register_style('wp-listings-single', WP_LISTINGS_URL . '/includes/css/wp-listings-single.css');

		/** Register Font Awesome icons but don't enqueue them */
		wp_register_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');

		/** Register Properticons but don't enqueue them */
		wp_register_style('properticons', '//s3.amazonaws.com/properticons/css/properticons.css');

		if ( !isset($options['wp_listings_stylesheet_load']) ) {
			$options['wp_listings_stylesheet_load'] = 0;
		}

		if ('1' == $options['wp_listings_stylesheet_load'] ) {
			return;
		}

        if ( file_exists(dirname( __FILE__ ) . '/includes/css/wp-listings.css') ) {
        	wp_register_style('wp_listings', WP_LISTINGS_URL . 'includes/css/wp-listings.css');
            wp_enqueue_style('wp_listings');
        }
    }

    /** Enqueues wp-listings-widgets.css style file if it exists and is not deregistered in settings */
	add_action('wp_enqueue_scripts', 'add_wp_listings_widgets_styles');
	function add_wp_listings_widgets_styles() {

		$options = get_option('plugin_wp_listings_settings');

		if ( !isset($options['wp_listings_widgets_stylesheet_load']) ) {
			$options['wp_listings_widgets_stylesheet_load'] = 0;
		}

		if ('1' == $options['wp_listings_widgets_stylesheet_load'] ) {
			return;
		}

        if ( file_exists(dirname( __FILE__ ) . '/includes/css/wp-listings-widgets.css') ) {
        	wp_register_style('wp_listings_widgets', WP_LISTINGS_URL . 'includes/css/wp-listings-widgets.css');
            wp_enqueue_style('wp_listings_widgets');
        }
    }

	/** Instantiate */
	$_wp_listings = new WP_Listings;
	$_wp_listings_taxonomies = new WP_Listings_Taxonomies;
	$_wp_listings_templates = new Single_Listing_Template;

	add_action( 'widgets_init', 'wp_listings_register_widgets' );

	// /** For troubleshooting the loaded template */
	// add_action('genesis_entry_header', 'echo_template_name');
	// add_action('genesis_after_post_title', 'echo_template_name');
	// function echo_template_name() {
	// 	global $post, $template;
	// 	echo $template;
	// 	echo get_post_meta( $post->ID, '_wp_post_template', true );
	// }
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