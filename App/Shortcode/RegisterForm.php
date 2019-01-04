<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Shortcode;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Config;
use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class RegisterForm {

	public function __construct() {
		add_shortcode( 'snow_monkey_member_post_register_form', [ $this, '_view' ] );
		add_filter( 'registration_errors', [ $this, '_redirect' ], 10000, 3 );
	}

	/**
	 * Register shortcode
	 *
	 * @param array $atts
	 * @return string
	 * @see https://core.trac.wordpress.org/browser/trunk/src/wp-login.php
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
			[
				'redirect_to' => $this->_get_current_url(),
			],
			$atts
		);

		$redirect_to = $atts['redirect_to'];
		$redirect_to = explode( '?', $redirect_to );
		$query_args  = [];
		if ( ! empty( $_GET ) ) {
			$query_args = wp_unslash( $_GET );
		}

		$query_args['checkemail'] = 'registered';
		$atts['redirect_to'] = $redirect_to[0] . '?' . http_build_query( $query_args, '', '&amp;' );

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
	 * @param WP_Error $errors
	 * @param string $sanitized_user_login
	 * @param string $user_email
	 * @return WP_Error
	 */
	public function _redirect( $errors, $sanitized_user_login, $user_email ) {
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
		if ( ! $error_codes ) {
			return $errors;
		}

		$redirect_to = add_query_arg( 'register', $error_codes, $redirect_to );
		$redirect_to = remove_query_arg( 'checkemail', $redirect_to );

		wp_safe_redirect( $redirect_to );
	}

	/**
	 * Return current URL
	 *
	 * @return string
	 */
	protected function _get_current_url() {
		$path = filter_input( INPUT_SERVER, 'REQUEST_URI' );
		return home_url( $path );
	}
}
