<?php
/**
 * Creates a dismissible admin notice via ajax
 *
 * @package   WP Listings
 * @since     1.3
 */

if ( class_exists( 'WP_Listings_Admin_Notice' ) ) {
	return;

}

/**
 * Class WP_Listings_Admin_Notice
 *
 * @package   @WP_Listings_Admin_Notice
 */
class WP_Listings_Admin_Notice {

	/**
	 * The action for the nonce
	 *
	 * @since 1.3
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected static $nonce_action = 'wp_listings_admin_notice';

	/**
	 * The nonce field
	 *
	 * @since 1.3
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected static $nonce_field  = '';

	/**
	 * The ignore key
	 *
	 * @since 1.3
	 *
	 * @access protected
	 *
	 * @var string
	 */
	protected static $ignore_key   = '';

	/**
	 * Output the message
	 *
	 * @since 1.3
	 *
	 * @param string $message The text of the message.
	 * @param bool $error Optional. Whether to show as error or update. Default is notice.
	 * @param string $cap_check Optional. Minimum user capability to show notice to. Default is "activate_plugins"
	 * @param string|bool $ignore_key Optional. The user meta key to use for storing if this message has been dismissed by current user or not. If false, it will be generated.
	 *
	 * @return string|void Admin notice if is_admin() and not dismissed.
	 */
	public static function notice( $message,  $error = false, $cap_check = 'activate_plugins', $ignore_key = false ) {
		if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
			if ( current_user_can( $cap_check ) ) {
				$user_id = get_current_user_id();
				if ( ! is_string( $ignore_key ) ) {
					// wplistings_ignore_3911b2583433f696e5813a503bbb2e65
					$ignore_key = 'wplistings_ignore_' . substr( md5( $ignore_key ), 0, 40 );
				}

				self::$ignore_key = sanitize_key( $ignore_key );

				$dissmised = get_user_meta( $user_id, self::$ignore_key, true );

				if ( ! $dissmised ) {
					if ( $error ) {
						$class = 'error';
					} else {
						$class = 'updated';
					}

					self::$nonce_field = wp_nonce_field( self::$nonce_action );

					$out[] = sprintf( '<div id="%1s" data-key="%2s" class="%3s notice is-dismissible"><p>', self::$ignore_key, self::$ignore_key, $class );
					$out[] = $message;
					$out[] = self::$nonce_field;
					$out[] = '</p></div>';

					add_action( 'admin_enqueue_scripts', array( __CLASS__, 'js_css' ) );
					add_action( 'wp_ajax_wp_listings_admin_notice', array( __CLASS__, 'ajax_cb' ) );

					return implode( '', $out );

				}

			}

		}

	}

	/**
	 * Enqueue JavaScript and CSS for dismiss button.
	 *
	 * @since 1.3
	 *
	 * @uses "admin_enqueue_scripts"
	 */
	public static function js_css() {
		global $wp_version;
		wp_enqueue_style( 'wp-listings-admin-notice', plugin_dir_url( __FILE__ ) . '/includes/css/wp-listings-admin-notice.css' );
		wp_enqueue_script( 'wp-listings-admin', plugin_dir_url( __FILE__ ) . '/includes/js/admin.js' );
		wp_localize_script( 'wp-listings-admin', 'wp_listings_adminL10n', array(
			'nonce'      => wp_create_nonce( self::$nonce_action ),
			'wp_version' => $wp_version,
			'dismiss'    => __( 'Dismiss this notice', 'wp-listings' ),
		) );
	}

	/**
	 * AJAX callback to mark the message dismissed.
	 *
	 * @since 1.3
	 *
	 * @uses "wp_ajax_wp_listings_admin_notice"
	 *
	 * @return bool
	 */
	public static function ajax_cb() {
		if (  ! isset( $_POST[ 'nonce' ] ) || ! wp_verify_nonce( $_POST[ 'nonce' ], self::$nonce_action ) ) {
			return false;
		}

		$nag = sanitize_key( $_POST[ 'nag' ] );
		if ( $nag === $_POST[ 'nag' ] ) {
			update_user_meta( get_current_user_id(), $nag, true );
		}

	}

}
