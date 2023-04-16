<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\MemberPost\App\Shortcode;

use Snow_Monkey\Plugin\MemberPost\App\Config;
use Snow_Monkey\Plugin\MemberPost\App\View;

class RegisterForm {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'snow_monkey_member_post_register_form', array( $this, '_view' ) );
		add_filter( 'registration_errors', array( $this, '_redirect' ), 10000 );
	}

	/**
	 * Register shortcode
	 *
	 * @see https://core.trac.wordpress.org/browser/trunk/src/wp-login.php
	 *
	 * @param array $atts Array of attributes.
	 * @return string
	 */
	public function _view( $atts ) {
		if ( is_user_logged_in() ) {
			return;
		}

		if ( ! get_option( 'users_can_register' ) ) {
			ob_start();
			View::render( 'shortcode/register-form/restricted' );
			return ob_get_clean();
		}

		$atts = shortcode_atts(
			array(
				'redirect_to' => $this->_get_current_url(),
			),
			$atts
		);

		$atts['redirect_to'] = add_query_arg( 'checkemail', 'registered', $atts['redirect_to'] );

		ob_start();
		if ( 'registered' === filter_input( INPUT_GET, 'checkemail' ) ) {
			View::render( 'shortcode/register-form/registered' );
		} else {
			View::render( 'shortcode/register-form/index', $atts );
		}
		return ob_get_clean();
	}

	/**
	 * Filters the errors encountered when a new user is being registered.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors encountered during registration.
	 * @return WP_Error
	 */
	public function _redirect( $errors ) {
		$nonce_key = Config::get( 'register-form-nonce-key' );
		$nonce     = filter_input( INPUT_POST, $nonce_key );
		if ( ! $nonce ) {
			return $errors;
		}

		if ( ! wp_verify_nonce( $nonce, $nonce_key ) ) {
			return $errors;
		}

		$redirect_to = filter_input( INPUT_POST, 'redirect_to' );
		if ( ! $redirect_to ) {
			return $errors;
		}

		$error_codes = implode( ',', $errors->get_error_codes() );

		if ( empty( $error_codes ) ) {
			wp_safe_redirect( $redirect_to );
		}

		$referer = $this->_get_http_referer();
		if ( $referer ) {
			$redirect_to = add_query_arg( 'register_error_codes', $error_codes, $referer );
			$redirect_to = remove_query_arg( 'checkemail', $redirect_to );
			wp_safe_redirect( $redirect_to );
		}

		$redirect_to = add_query_arg( 'register_error_codes', $error_codes, $redirect_to );
		$redirect_to = remove_query_arg( 'checkemail', $redirect_to );
		wp_safe_redirect( $redirect_to );

		return $errors;
	}

	/**
	 * Return current URL
	 *
	 * @return string
	 */
	protected function _get_current_url() {
		$path = filter_input( INPUT_SERVER, 'REQUEST_URI' );
		$path = remove_query_arg( 'login_error_codes', $path );
		$path = remove_query_arg( 'register_error_codes', $path );
		return home_url( $path );
	}

	/**
	 * Return HTTP_REFERER
	 *
	 * @return string
	 */
	protected function _get_http_referer() {
		$referer = null;

		if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			$referer = $_SERVER['HTTP_REFERER'];
			$referer = remove_query_arg( 'login_error_codes', $referer );
			$referer = remove_query_arg( 'register_error_codes', $referer );
		}

		return $referer;
	}
}
