<?php
/**
 * @package snow-monkey-member-post
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\Shortcode;

use Snow_Monkey\Plugin\SnowMonkeyMemberPost\App\View;

class RegisterForm {

	public function __construct() {
		add_shortcode( 'snow_monkey_member_post_register_form', [ $this, '_view' ] );
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
	 * Return current URL
	 *
	 * @return string
	 */
	protected function _get_current_url() {
		$path = filter_input( INPUT_SERVER, 'REQUEST_URI' );
		return home_url( $path );
	}
}
